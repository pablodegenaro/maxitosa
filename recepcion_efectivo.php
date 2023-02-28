<?php 
require("conexion.php");
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
ini_set('memory_limit', '512M');

$fecha = $_SESSION['fecha'];
$moneda = $_SESSION['moneda'];
$codsucu = $_SESSION['codsucu1'];
$codesta = $_SESSION['codesta1'];
$sum = 0;
if($fecha && $moneda){
    $saldoAnterior = "SELECT total_saldo = sum(debe) - sum(haber) FROM SAREC_EFECTIVO WHERE CAST(fecha as DATE) < CAST('$fecha' AS DATE) AND moneda = '$moneda' AND codsucu = '$codsucu'";
    $stmtSaldoAnterior = mssql_query($saldoAnterior);
    if(mssql_num_rows($stmtSaldoAnterior) != 0){
        while($row = mssql_fetch_array($stmtSaldoAnterior)){
            $sum = $row['total_saldo'];
        }
    }
    $query = "SELECT convert(varchar,fecha,105) as fecha,codVendedor,codCliente,otros,
    CASE tipo_doc WHEN 'I' THEN 'INGRESO' WHEN 'E' THEN 'EGRESO' END AS tipo_doc,factura,
    CASE moneda WHEN 'BS' THEN 'Bolivares' WHEN 'DL' THEN 'Dolares' WHEN 'EU' THEN 'Euros' END as moneda
    ,monto,correlativo,debe,haber,observacion
    FROM SAREC_EFECTIVO WHERE CAST(fecha as DATE) = '$fecha' AND moneda = '$moneda' AND tipo_doc <> 'A' AND codsucu = '$codsucu' ORDER BY correlativo ASC";
    $stmt = mssql_query($query);
    if(mssql_num_rows($stmt)!=0){
        while($row = mssql_fetch_array($stmt)){
            $data[] = $row;
        }
    }

    $queryRevAnu = "SELECT convert(varchar,fecha,105) as fecha,codVendedor,codCliente,otros,
    CASE tipo_doc WHEN 'R' THEN 'REVERSO' WHEN 'A' THEN 'ANULADO' END AS tipo_doc,factura,
    CASE moneda WHEN 'BS' THEN 'Bolivares' WHEN 'DL' THEN 'Dolares' WHEN 'EU' THEN 'Euros' END as moneda
    ,monto,correlativo,debe,haber,
    saldo,observacion
    FROM SAREC_EFECTIVO WHERE CAST(fecha as DATE) = '$fecha' AND moneda = '$moneda' AND tipo_doc <> 'I' AND tipo_doc <> 'E' AND codsucu = '$codsucu' ORDER BY correlativo ASC";
    $stmt = mssql_query($queryRevAnu);
    if(mssql_num_rows($stmt)!=0){
        while($row = mssql_fetch_array($stmt)){
            $dataRevAnu[] = $row;
        }
    }


    $querySearch = "SELECT 
    total_100 = (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda = '$moneda' AND haber > debe) ,
    total_50 = (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda ='$moneda' AND haber > debe),
    total_20 = (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda ='$moneda' AND haber > debe),
    total_10 = (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda ='$moneda' AND haber > debe),
    total_5 = (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda ='$moneda' AND haber > debe),
    total_2 = (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda = '$moneda' AND haber > debe),
    total_1 = (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda ='$moneda' AND haber > debe),
    sum(debe) - sum(haber) as saldo
    FROM SAREC_DENOM WHERE CAST(fecha as DATE) = '$fecha' AND moneda ='$moneda' AND codsucu = '$codsucu' group by moneda";
    $stmtBS = mssql_query($querySearch);
    if(mssql_num_rows($stmtBS)!=0){
        while($row = mssql_fetch_array($stmtBS)){
            if( $row["total_100"] == NULL ||  $row["total_100"] == ''){
                $row["total_100"] = 0;
            }
            if( $row["total_50"] == NULL ||  $row["total_50"] == ''){
                $row["total_50"] = 0;
            }
            if( $row["total_20"] == NULL ||  $row["total_20"] == ''){
                $row["total_20"] = 0;
            }
            if( $row["total_10"] == NULL ||  $row["total_10"] == ''){
                $row["total_10"] = 0;
            }
            if( $row["total_5"] == NULL ||  $row["total_5"] == ''){
                $row["total_5"] = 0;
            }
            if( $row["total_2"] == NULL || $row["total_2"] == ''){
                $row["total_2"] = 0;
            }
            if( $row["total_1"] == NULL ||  $row["total_1"] == ''){
                $row["total_1"] = 0;
            }
            if( $row["saldo"] == NULL ||  $row["saldo"] == ''){
                $row["saldo"] = 0;
            }
            $dataDenom[] = $row;
        }
    }else{
        $dataDenom[0]["total_100"] = 0;
        $dataDenom[0]["total_50"] = 0;
        $dataDenom[0]["total_20"] = 0;
        $dataDenom[0]["total_10"] = 0;
        $dataDenom[0]["total_5"] = 0;
        $dataDenom[0]["total_2"] = 0;
        $dataDenom[0]["total_1"] = 0;
        $dataDenom[0]["saldo"] = 0;
    }
    $dataVueltos = array();
    $queryVueltos = "SELECT codVendedor,Descrip,moneda,
    saldo = sum(debe) - sum(haber)
    FROM SAREC_VUELTOS  WHERE moneda = '$moneda'  AND codsucu = '$codsucu'
    GROUP BY codVendedor,Descrip,moneda"; 
    $stmtVueltos = mssql_query($queryVueltos);
    if(mssql_num_rows($stmtVueltos) != 0){
        while($row = mssql_fetch_array($stmtVueltos)){
            $dataVueltos[] = $row;
        }
    }

}
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!--  <h2 id="title_permisos">Ultima Activacion Clientes</h2> -->
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="principal1.php?page=<?= str_replace(".php", "", $_SESSION['dashboard']); ?>&mod=1">Inicio</a></li>
                        <li class="breadcrumb-item active">Caja</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="col-md-12">
            <div class="card card-saint">
                <div class="card-header">
                    <div class="card-title">
                        Cantidad de billetes por denominacion
                    </div>
                    <button type="button" onclick="regresa()" class="btn btn-default float-right">Regresar</button>
                </div>
                <div class="card-body" >
                    <div id="denominacion">
                        <?php if($moneda == 'BS'):?>
                            <table id="tablaBS" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
                                <thead style="background-color: #00137f;color: white;">
                                    <tr>
                                        <td width="10%">Denominacion</td>
                                        <td width="13%">100 Bs</td>
                                        <td>50 Bs</td>
                                        <td>20 Bs</td>
                                        <td>10 Bs</td>
                                        <td>5 Bs</td>
                                        <td>1 Bs</td>
                                        <td width="13%">0.5 Bs</td>
                                        <td>Total</td>                       
                                    </tr>
                                </thead> 
                                <tbody>
                                    <?php foreach($dataDenom as $item): ?>
                                        <tr>
                                            <td>Bolivares</td>
                                            <td><?php echo $item['total_100'] ?></td>
                                            <td><?php echo $item['total_50'] ?></td>
                                            <td><?php echo $item['total_20'] ?></td>
                                            <td><?php echo $item['total_10'] ?></td>
                                            <td><?php echo $item['total_5'] ?></td>
                                            <td><?php echo $item['total_2'] ?></td>
                                            <td><?php echo $item['total_1'] ?></td>
                                            <td><?php echo $item['saldo'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>     
                            </table>   
                        <?php endif;?>
                        <?php if($moneda == 'DL'):?>
                            <table id="tablaDL" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
                                <thead style="background-color: #00137f;color: white;">
                                    <tr>
                                        <td width="10%"><strong>Denominacion</strong></td>
                                        <td>100 $</td>
                                        <td>50 $</td>
                                        <td>20 $</td>
                                        <td>10 $</td>
                                        <td>5 $</td>
                                        <td>2 $</td>
                                        <td>1 $</td>
                                        <td>Total</td> 
                                    </tr>
                                </thead> 
                                <tbody>
                                    <?php foreach($dataDenom as $item): ?>
                                        <tr>
                                            <td>Dolares</td>
                                            <td><?php echo $item['total_100'] ?></td>
                                            <td><?php echo $item['total_50'] ?></td>
                                            <td><?php echo $item['total_20'] ?></td>
                                            <td><?php echo $item['total_10'] ?></td>
                                            <td><?php echo $item['total_5'] ?></td>
                                            <td><?php echo $item['total_2'] ?></td>
                                            <td><?php echo $item['total_1'] ?></td>
                                            <td><?php echo $item['saldo'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>     
                            </table>
                        <?php endif;?>
                        <?php if($moneda == 'EU'):?>
                            <table id="tablaEU" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
                                <thead style="background-color: #00137f;color: white;">
                                    <tr>
                                        <td width="10%"><strong>Denominacion</strong></td>
                                        <td>500 €</td>
                                        <td>200 €</td>
                                        <td>100 €</td>
                                        <td>50 €</td>
                                        <td>20 €</td>
                                        <td>10 €</td>
                                        <td>5 €</td>
                                        <td>Total</td> 
                                    </tr>
                                </thead> 
                                <tbody>
                                    <?php foreach($dataDenom as $item): ?>
                                        <tr>
                                            <td>Bolivares</td>
                                            <td><?php echo $item['total_100'] ?></td>
                                            <td><?php echo $item['total_50'] ?></td>
                                            <td><?php echo $item['total_20'] ?></td>
                                            <td><?php echo $item['total_10'] ?></td>
                                            <td><?php echo $item['total_5'] ?></td>
                                            <td><?php echo $item['total_2'] ?></td>
                                            <td><?php echo $item['total_1'] ?></td>
                                            <td><?php echo $item['saldo'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>     
                            </table>  
                        <?php endif;?>         
                    </div> 
                </div>
            </div>

            <?php if(!$data):?>

            <?php else:?>    
                <div class="card card-saint">
                    <div class="card-header">Ingresos y Egresos</div>
                    <div class="card-body" >
                        <table id="tablaRecepcion" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
                            <thead style="background-color: #00137f;color: white;">
                                <tr>
                                    <td>Correl</td>
                                    <td>Fecha</td>
                                    <td>Vendedor</td>
                                    <td>Cliente</td>
                                    <td>Foraneo</td>
                                    <td>Factura</td>
                                    <td>Tipo</td>
                                    <td>Moneda</td>
                                    <td>Debe</td>
                                    <td>Haber</td>
                                    <td>Saldo</td>
                                    <td>Observacion</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data as $item):?>
                                    <tr>
                                        <td><?php echo $item['correlativo']?></td>
                                        <td><?php echo $item['fecha']?></td>
                                        <td><?php echo $item['codVendedor']?></td>
                                        <td><?php echo $item['codCliente']?></td>
                                        <td><?php echo $item['otros']?></td>
                                        <td><?php echo $item['factura']?></td>
                                        <td><?php echo $item['tipo_doc']?></td>
                                        <td><?php echo $item['moneda']?></td>
                                        <td><?php echo $item['debe']?></td>
                                        <td><?php echo $item['haber']?></td>
                                        <td>
                                            <?php if($item['tipo_doc'] == 'ANULADO'){ echo 0;}
                                            if($item['tipo_doc'] == 'INGRESO'){$sum = $sum + $item['debe']; echo number_format($sum,2);}
                                            if($item['tipo_doc'] == 'EGRESO'){$sum = $sum - $item['haber']; echo number_format($sum,2);}
                                            ?>
                                        </td>
                                        <td><?php echo $item['observacion']?></td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif;?>                        

            <?php if(!$dataRevAnu):?>
            <?php else:?>
                <div class="card card-saint">
                    <div class="card-header">Facturas Anuladas</div>
                    <div class="card-body" >
                        <table id="tablaRevAnu" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
                            <thead style="background-color: #00137f;color: white;">
                                <tr>
                                    <td>Correl</td>
                                    <td>Fecha</td>
                                    <td>Vendedor</td>
                                    <td>Cliente</td>
                                    <td>Foraneo</td>
                                    <td>Factura</td>
                                    <td>Tipo</td>
                                    <td>Moneda</td>
                                    <td>Debe</td>
                                    <td>Haber</td>
                                    <td>Saldo</td>
                                    <td>Observacion</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($dataRevAnu as $item):?>
                                    <tr>
                                        <td><?php echo $item['correlativo']?></td>
                                        <td><?php echo $item['fecha']?></td>
                                        <td><?php echo $item['codVendedor']?></td>
                                        <td><?php echo $item['codCliente']?></td>
                                        <td><?php echo $item['otros']?></td>
                                        <td><?php echo $item['factura']?></td>
                                        <td><?php echo $item['tipo_doc']?></td>
                                        <td><?php echo $item['moneda']?></td>
                                        <td><?php echo $item['debe']?></td>
                                        <td><?php echo $item['haber']?></td>
                                        <td><?php echo $item['saldo']?></td>
                                        <td><?php echo $item['observacion']?></td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif;?>                        

            <?php if(!$dataVueltos): ?>
            <?php else:?>
                <div class="card card-saint">
                    <div class="card-header">Vueltos Pendientes</div>
                    <div class="card-body" >
                        <table id="tablaVueltos" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
                            <thead style="background-color: #00137f;color: white;">
                                <tr>
                                    <td>Codigo</td>
                                    <td>Nombre</td>
                                    <td>Moneda</td>
                                    <?php if($moneda == 'BS'):?>
                                        <td>Saldo Acreditado en Bs</td>
                                    <?php endif; ?>
                                    <?php if($moneda == 'DL'):?>
                                        <td>Saldo Acreditado en $</td>
                                    <?php endif; ?>
                                    <?php if($moneda == 'EU'):?>
                                        <td>Saldo Acreditado en €</td>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($dataVueltos as $item):?>
                                    <tr>
                                        <td><?php echo $item['codVendedor']?></td>
                                        <td><?php echo $item['Descrip']?></td>
                                        <?php if($item['moneda'] == 'BS'):?>
                                            <td>Bs</td>
                                        <?php endif;?>
                                        <?php if($item['moneda'] == 'DL'):?>
                                            <td>$</td>
                                        <?php endif;?>
                                        <?php if($item['moneda'] == 'EU'):?>
                                            <td>€</td>
                                        <?php endif;?>
                                        <td><?php echo $item['saldo']?></td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif;?>    
        </div>
    </section>
</div>

<?php include "footer.php"; ?>
<script src="Icons.js" type="text/javascript"></script>
<script src="recepcion_efectivo.js"></script>
