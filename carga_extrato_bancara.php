
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mt-3">
                <div class="col-sm-6">
                    <h2 class="ml-3">Ingresar Extracto Bancario</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card-body">
                    <div class="card card-saint" id="formulario">
                        <div class="card-header">
                            <h3 class="card-title">Ingresar extracto bancario</h3>
                        </div>
                        <form id="form_file" method="post" action="carga_extrato_bancara_procesa.php" enctype="multipart/form-data">
                            <div class="card-body" style="width:auto;">
                                
                                <div class="form-group row">
                                    <label for="codbanc" class="col-sm-2 col-form-label">Entidad Bancaria</label>
                                    <div class="col-sm-10">
                                        <?php
                                        $bancos = Mssql::fetch_assoc(
                                            mssql_query("SELECT CodBanc, Descripcion FROM SBBANC WHERE Activo=1 ORDER BY Descripcion ASC")
                                        );
                                        ?>
                                        <select class="form-control select2" id="codbanc" name="codbanc" required>
                                            <option value="">--seleccione un banco--</option>
                                            <?php
                                            foreach ($bancos as $i => $banco) { ?>
                                                <option value="<?= $banco["CodBanc"];?>"><?= $banco["CodBanc"]." - ".$banco["Descripcion"];?></option><?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="file1" class="col-sm-2 col-form-label">Archivo Excel</label>
                                    <div  class="col-sm-10">
                                        
                                        <input type="file" id="file" name="file" accept=".xls" required>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer clearfix text-right">
                                <button id="cargar_button" type="submit" onclick="guarda()" name="Submit" class="btn btn-saint">Ingresar</button>
                            </div>
                        </form>
                    </div>
                    <div class="card card-saint">
                        <script type="text/javascript">
                            function guarda(){
                                limpiarBuscar();
                                /* document.forms["registro_usuarios"].submit();*/
                            }
                            function regresa(){
                                window.location.href = "principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1";
                            }
                        </script>
                        <div class="card-header">
                            <h3 class="card-title">Relación de Pre-conciliación</h3>&nbsp;&nbsp;&nbsp;
                        </div>
                        <div class="card-body">
                            <?php
                            $docs = mssql_query("SELECT id, idDoc, NombreDoc, doc.CodBanc, ban.Descripcion, FechaE, Procesado,
                                (SELECT count(*) FROM Docitem_App item WHERE item.idDoc=doc.idDoc) AS total,
                                (SELECT count(*) FROM Docitem_App item WHERE item.idDoc=doc.idDoc AND Procesado=0) AS pendientes,
                                (SELECT count(*) FROM Docitem_App item WHERE item.idDoc=doc.idDoc AND Procesado=1) AS preconciliados,
                                (SELECT count(*) FROM SBTRAN trn INNER JOIN Doc_App doc1 ON doc1.CodBanc = trn.CodBanc
                                    INNER JOIN Docitem_app doci ON doc.idDoc = doci.idDoc WHERE doc1.CodBanc=doc.CodBanc AND doc1.idDoc=doc.idDoc
                                    AND DATEADD(dd, 0, DATEDIFF(dd, 0, trn.Fecha)) = DATEADD(dd, 0, DATEDIFF(dd, 0, doci.FechaE))
                                    AND trn.MtoCr=doci.Credito AND trn.MtoDb=doci.Debito AND doci.Procesado=0 AND trn.Estado=0
                                    ) AS relacionados 
                                FROM Doc_app doc
                                INNER JOIN SBBANC ban ON ban.CodBanc=doc.CodBanc
                                ORDER BY Procesado ASC, FechaE DESC ");
                                ?> 

                                <?php
                                if (isset($_SESSION['mensaje'])) {
                                    ?>
                                    <div class="alert alert-default-<?= $_SESSION['bg_mensaje'];?> alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h5><i class="icon fas <?= $_SESSION['icono'];?>"></i> Atención!</h5>
                                        <?= $_SESSION['mensaje'];?>
                                    </div>
                                    <?php
                                    unset($_SESSION['bg_mensaje']);
                                    unset($_SESSION['icono']);
                                    unset($_SESSION['mensaje']);
                                }
                                ?>

                                <table id="example4" class="table table-sm table-bordered table-striped p-0">
                                    <thead style="background-color: #00137f;color: white;">
                                        <tr class="text-center">
                                            <th>#</th>
                                            <th>Banco</th>
                                            <th>Documento</th>
                                            <th>Fecha Ingresado</th>
                                            <th>Estatus</th>
                                            <th>Total Registros</th>
                                            <th>Registros pend.</th>
                                            <th>PreConciliados</th>
                                            <th>Relacionados</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php for ($i = 0; $i < mssql_num_rows($docs); $i++) {


                                            $id = mssql_result($docs, $i, "id");
                                            $idDoc = mssql_result($docs, $i, "idDoc");
                                            $NombreDoc = mssql_result($docs, $i, "NombreDoc");
                                            $CodBanc = mssql_result($docs, $i, "CodBanc");
                                            $Descripcion = mssql_result($docs, $i, "Descripcion");
                                            $FechaE = mssql_result($docs, $i, "FechaE");
                                            $Procesado = mssql_result($docs, $i, "Procesado");
                                            $total = mssql_result($docs, $i, "total");
                                            $Pendientes = mssql_result($docs, $i, "pendientes");
                                            $Preconciliados = mssql_result($docs, $i, "preconciliados");
                                            $Relacionados = mssql_result($docs, $i, "relacionados");

                                            # Estatus del eventos
                                            $estColor = '';
                                            $estText = '';
                                            if ($Procesado=='0') {
                                                $estColor = 'dark';
                                                $estText = 'Por revisar';
                                            } else {
                                                $estColor = 'info';
                                                $estText = 'Procesado';
                                            }

                                            ?>
                                            <tr>
                                                <td class="text-center"><?php echo $i+1; ?></td>
                                                <td class="text-center"><?php echo $Descripcion; ?></td>
                                                <td class="text-center">
                                                    <form id="form_delete<?= $id; ?>" action="carga_extrato_bancara_eliminar.php" method="POST">
                                                        <input type="hidden" id="id_doc" name="id_doc" value="<?= $id; ?>"/>
                                                        <?php echo utf8_encode($NombreDoc); ?>
                                                        <?php
                                                        if ( ($Procesado=='0') || ($Procesado==1 && $Preconciliados==0 && $Relacionados==0 && $total==$Pendientes) ) { ?>
                                                            <i onclick="eliminar(<?= '\''.$id.'\',\''.utf8_encode($NombreDoc).'\''; ?>)" class="fa fa-trash text-red delete"></i>
                                                            <?php
                                                        } ?>
                                                    </form>    
                                                </td>
                                                <td class="text-center"><?php echo date("d/m/Y h:i A", strtotime($FechaE)); ?></td>
                                                <td class="text-center">
                                                    <small class="badge badge-<?= $estColor; ?>">
                                                        <?= $estText; ?>
                                                    </small>
                                                </td> 
                                                <td class="text-center"><?php echo intval($total); ?></td>
                                                <td class="text-center"><?php echo intval($Pendientes); ?></td>
                                                <td class="text-center"><?php echo intval($Preconciliados); ?></td>
                                                <td class="text-center"><?php echo intval($Relacionados); ?></td>
                                                <td class="text-center">
                                                    <a href="principal1.php?page=carga_extrato_bancara_ver&mod=1&i=<?= $id; ?>" class="btn btn-outline-saint btn-sm">
                                                        Ver Detalles
                                                    </a>
                                                </td> 
                                            </tr>
                                            <?php 
                                        }?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <button type="button" onclick="regresa()" class="btn btn-outline-saint">Regresar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


</div>
<?php require_once("footer.php");?>
<script type="text/javascript">
    $(function() {
        $('#codbanc').one('select2:open', function(e) {
            $('input.select2-search__field').prop('placeholder', 'Buscar...');
        });
    });

    function regresa(){
        window.location.href = "principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1";
    }

    function eliminar(id, name) {
        Swal.fire({
            // title: '¿Estas Seguro?',
            text: '¿Estas Seguro de Eliminar "'+name+'" ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                $('#form_delete'+id).submit();
            }
        })
    }
</script>