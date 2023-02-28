<?php
$id = $_GET['i'];
$docApp = mssql_query("SELECT id, idDoc, NombreDoc, CodBanc, FechaE, Procesado FROM Doc_app WHERE id='$id'");
$idDoc = mssql_result($docApp, 0, "idDoc");
$codBanc = mssql_result($docApp, 0, "CodBanc");
$fechaed = mssql_result($docApp, 0, "FechaE");

$tran = Mssql::fetch_assoc(
    mssql_query("SELECT CodBanc, Documento, Fecha, Comentario1, MtoDb, MtoCr, Monto FROM SBTRAN 
                WHERE CodBanc='$codBanc' AND Estado=0 ORDER BY Fecha DESC")
);

$docs = Mssql::fetch_assoc(
    mssql_query("SELECT doci.id, doc.CodBanc, NomperBanc, doci.FechaE, Concepto, Debito, Credito, Saldo, Refere
                FROM Doc_app doc
                INNER JOIN Docitem_app doci ON doc.idDoc = doci.idDoc
                INNER JOIN SBBANC banc ON banc.CodBanc = doc.CodBanc
                WHERE banc.CodBanc='$codBanc' AND doc.idDoc='$idDoc' AND doci.Procesado=0")
);

$arrPend = array();
$nombreBanc = '';
if (count($docs)>0) {
    $nombreBanc = " (".$docs[0]['NomperBanc']." ".date('d/m/Y', strtotime($fechaed)).")";
    foreach ($docs as $doc) {
        # variables
        $documento = '';
        $docId = $doc['id'];
        $concepto = $doc['Concepto'];
        $refere = $doc['Refere'];
        $fecha = date('d/m/Y', strtotime($doc['FechaE']));
        $montoDoc = ($doc['Debito'] + $doc['Credito']);
        $mtoCr = 0;
        $mtoDb = 0;
        $monto = 0;

        # buscar si existe en alguna transaccion
        foreach ($tran as $j => $trn) {
            if ((floatval($trn['MtoDb']) == floatval($doc['Debito'])) and (floatval($trn['MtoCr']) == floatval($doc['Credito'])) and
                (date('Y-m-d', strtotime($doc['FechaE'])) == date('Y-m-d', strtotime($trn['Fecha']))) 
            ) {
                $documento = $trn['Documento'];
                $mtoCr = $trn['MtoCr']; 
                $mtoDb = $trn['MtoDb'];
                $monto = $trn['Monto'];
                break;
            }
        }

        # llenar el $arrPend
        $arrPend[] = array(
            'id'         => $docId,
            'existeTran' => (strlen($documento)>0),
            'documento'  => $documento,
            'refere'     => $refere,
            'concepto'   => $concepto,
            'fecha'      => $fecha,
            'debito'     => $doc['Debito'],
            'credito'    => $doc['Credito'],
            'saldo'      => $doc['Saldo'],
            'mtoDoc'     => $montoDoc,
            'mtoTrn'     => $monto,
            'esCreDeb'   => ($mtoCr>0),
        );
    }
}
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mt-3">
                <div class="col-sm-12">
                    <h2 class="ml-3">Extracto Bancario<?= $nombreBanc; ?></h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card-body">

                    <?php
                    $cons = Mssql::fetch_assoc(
                        mssql_query("SELECT Documento, Fecha, MtoDb, MtoCr, doci.Saldo, Refere, doci.Concepto FROM SBTRAN trn
                                    INNER JOIN Doc_App doc ON doc.CodBanc = trn.CodBanc
                                    INNER JOIN Docitem_app doci ON doc.idDoc = doci.idDoc
                                    WHERE doc.CodBanc='$codBanc' AND doc.idDoc='$idDoc' 
                                    AND DATEADD(dd, 0, DATEDIFF(dd, 0, trn.Fecha)) = DATEADD(dd, 0, DATEDIFF(dd, 0, doci.FechaE))
                                    AND trn.MtoCr=doci.Credito AND trn.MtoDb=doci.Debito AND doci.Procesado=1 AND trn.Estado=1")
                    );

                    if (count($cons) > 0) {
                        ?> 
                        <div class="card card-saint mb-4">
                            <div class="card-header">
                                <h3 class="card-title">Preconciliados</h3>&nbsp;&nbsp;&nbsp;
                                <button type="button" onclick="regresa()" class="btn btn-secondary float-right">Volver atrás</button>
                            </div>
                            <div class="card-body">
                                
                                <table id="example4" class="table table-sm table-bordered table-striped p-0">
                                    <thead style="background-color: #00137f;color: white;">
                                        <tr class="text-center">
                                            <th>Nro. Documento</th>
                                            <th>Fecha Doc.</th>
                                            <th>Débito</th>
                                            <th>Crédito</th>
                                            <th>Saldo</th>
                                            <th>Referencia</th>
                                            <th>Texto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        foreach ($cons as $key => $con) {
                                            ?>
                                            <tr>
                                                <td class="text-center"><?php echo $con['Documento']; ?></td>
                                                <td class="text-center"><?php echo date('d/m/Y', strtotime($con['Fecha'])); ?></td>
                                                <td class="text-center"><?php echo rdecimal($con['MtoDb'], 2); ?></td>
                                                <td class="text-center"><?php echo rdecimal($con['MtoCr'], 2); ?></td>
                                                <td class="text-center"><?php echo rdecimal($con['Saldo'], 2); ?></td>
                                                <td class="text-center"><?php echo $con['Refere']; ?></td>
                                                <td class="text-center"><?php echo utf8_decode($con['Concepto']); ?></td>
                                            </tr>
                                            <?php 
                                        }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    
                    <div class="card card-saint">
                        <div class="card-header">
                            <h3 class="card-title">Pendientes</h3>&nbsp;&nbsp;&nbsp;
                        </div>
                        <form name="formulario" method="post" action="carga_extrato_bancara_precon.php">
                            <div class="card-body">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <input type="hidden" name="codbanc" value="<?php echo trim($codBanc); ?>">
                                <input type="hidden" name="idDoc" value="<?php echo trim($idDoc); ?>">
                                <?php
                                $items = mssql_query("SELECT id, NombreDoc, CodBanc, FechaE, Procesado 
                                                    FROM Doc_App ORDER BY Procesado ASC, FechaE DESC ");
                                ?> 

                                <table id="example4" class="table table-sm table-bordered table-striped p-0">
                                    <thead style="background-color: #00137f;color: white;">
                                        <tr class="text-center">
                                            <th>Proc</th>
                                            <!-- <th>Asignación</th> -->
                                            <th>Nro. Documento</th>
                                            <th>Fecha Doc.</th>
                                            <th>Estatus</th>
                                            <th>Débito</th>
                                            <th>Crédito</th>
                                            <th>Saldo</th>
                                            <th>Referencia</th>
                                            <th>Texto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        foreach ($arrPend as $key => $items) {
                                            
                                            # Estatus del eventos
                                            $estColor = '';
                                            $estText = '';
                                            if ($items['mtoTrn'] == 0) {
                                                $estColor = 'warning';
                                                $estText = 'no relacionado';
                                            } else {
                                                $estColor = 'success';
                                                $estText = 'relacionado';
                                            }

                                            ?>
                                            <tr>
                                                <td class="text-center"> 
                                                    <div class="col text-center">
                                                        <div class="form-check">
                                                            <input name="proc[]" value="<?= $items['documento']; ?>-<?= $items['id']; ?>" class="form-check-input proc" type="checkbox">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center"><?php echo $items['documento']; ?></td>
                                                <td class="text-center"><?php echo $items['fecha']; ?></td>
                                                <td class="text-center">
                                                    <small class="badge badge-<?= $estColor; ?>">
                                                        <?= $estText; ?>
                                                    </small>
                                                </td> 
                                                <td class="text-right"><?php echo rdecimal($items['debito'], 2); ?></td>
                                                <td class="text-right"><?php echo rdecimal($items['credito'], 2); ?></td>
                                                <td class="text-right"><?php echo rdecimal($items['saldo'], 2); ?></td>
                                                <td class="text-center"><?php echo $items['refere']; ?></td>
                                                <td class="text-center"><?php echo utf8_encode($items['concepto']); ?></td>
                                            </tr>
                                            <?php 
                                        }?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <button type="button" onclick="regresa()" class="btn btn-outline-saint">Regresar</button>
                                <button type="submit" name="Submit" class="btn btn-saint float-right">Procesar</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>


</div>
<?php require_once("footer.php");?>
<script type="text/javascript">
    function guarda(){
        if (window.confirm("¿Estas seguro de "+$('#codbanc').text()+" es el banco para el reporte seleccionado?")){
            /* document.forms["registro_usuarios"].submit();*/
        }
    }
    function regresa(){
        window.location.href = "principal1.php?page=carga_extrato_bancara&mod=1";
    }
</script>