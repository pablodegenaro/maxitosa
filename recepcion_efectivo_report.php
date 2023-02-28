<?php 
header('Content-type: application/vnd.ms-excel;charset=UTF-8');
header("Content-Disposition: attachment; filename=resumen_recepcion_caja.xls");
header("Pragma: no-cache");
header("Expires: 0");

session_start();

require("conexion.php");

$fecha = $_SESSION["fecha"];
$codsucu = $_SESSION['codsucu1'];
$codesta = $_SESSION['codesta1'];
$moneda = $_SESSION['moneda'];
$data = array();  
$dataBS = array();
$dataDL = array();
$dataEU = array();
$dataRevAnu = array();
$dataTablaBS = array();
$dataTablaDL = array();
$dataTablaEU = array();
$dataDenom = array();
$sumBS = 0;
$sumDL = 0;
$sumEU = 0;
$sum = 0;


$query = "SELECT fecha,codVendedor,codCliente,otros,factura,
CASE moneda WHEN 'BS' THEN 'Bolivares' WHEN 'DL' THEN 'Dolares' WHEN 'EU' THEN 'Euros' END as moneda,monto,correlativo,
CASE tipo_doc WHEN 'I' THEN 'INGRESO' WHEN 'E' THEN 'EGRESO' WHEN 'A' THEN 'ANULADO' END AS tipo_doc
,debe,haber,observacion 
FROM SAREC_EFECTIVO WHERE CAST(fecha as DATE) = '$fecha' AND tipo_doc <> 'A' AND moneda = '$moneda' AND codsucu='$codsucu' ORDER BY correlativo ASC";
$stmt = mssql_query($query);
if(mssql_num_rows($stmt)!=0){
    while($row = mssql_fetch_array($stmt)){
        $data[] = $row;
    }
}

$queryDenom = "SELECT 
total_100 = (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu') ,
total_50 = (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda ='$moneda' AND haber > debe AND codsucu = '$codsucu'),
total_20 = (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda ='$moneda' AND haber > debe AND codsucu = '$codsucu'),
total_10 = (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda ='$moneda' AND haber > debe AND codsucu = '$codsucu'),
total_5 = (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda ='$moneda' AND haber > debe AND codsucu = '$codsucu'),
total_2 = (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu'),
total_1 = (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda ='$moneda' AND haber > debe AND codsucu = '$codsucu'),
sum(debe) - sum(haber) as saldo
FROM SAREC_DENOM WHERE moneda ='$moneda' AND CAST(fecha AS DATE) = '$fecha' AND codsucu='$codsucu' group by moneda";
$stmt = mssql_query($queryDenom);
if(mssql_num_rows($stmt)!=0){
    while($row = mssql_fetch_array($stmt)){
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

$queryRevAnu = "SELECT fecha,codVendedor,codCliente,otros,
CASE tipo_doc WHEN 'R' THEN 'REVERSO' WHEN 'A' THEN 'ANULADO' END AS tipo_doc,factura,
CASE moneda WHEN 'BS' THEN 'Bolivares' WHEN 'DL' THEN 'Dolares' WHEN 'EU' THEN 'Euros' END as moneda
,monto,correlativo,debe,haber,
saldo,observacion
FROM SAREC_EFECTIVO WHERE CAST(fecha as DATE) = '$fecha' AND tipo_doc = 'A' AND codsucu='$codsucu' ORDER BY correlativo ASC";
$stmt = mssql_query($queryRevAnu); 
if(mssql_num_rows($stmt)!=0){
    while($row = mssql_fetch_array($stmt)){
        $dataRevAnu[] = $row;
    }
}
$dataVueltos = array();
$queryVueltos = "SELECT sv.Descrip, 
sum(case WHEN se.moneda = 'BS' THEN debe-haber ELSE 0 END) acre_bs, 
sum(case WHEN se.moneda = 'DL' THEN debe-haber ELSE 0 END) acre_dl, 
sum(case WHEN se.moneda = 'EU' THEN debe-haber ELSE 0  end ) acre_eu 
from SAREC_VUELTOS as se 
INNER JOIN SAVEND as sv on sv.CodVend = se.codVendedor  
WHERE codsucu='$codsucu' group by se.codVendedor, sv.Descrip"; 
$stmtVueltos = mssql_query($queryVueltos);
if(mssql_num_rows($stmtVueltos) != 0){
    while($row = mssql_fetch_array($stmtVueltos)){
        $dataVueltos[] = $row;
    }
}


?>

<section class="content">
    <div class="container">
        <div class="col-md-12">
            <div class="card-body">     
                <table  id="tablaBS" class="table table-sm text-center table-condensed table-bordered table-striped">
                    <thead style="background-color: #00137f;color: white;">
                        <tr>
                            <th colspan="12" scope="rowgroup">Ingresos y Egresos</th>
                        </tr>
                        <tr style="text-align:center;background:#219ebc;color: white;">
                            <td><strong>Correl</strong></td>
                            <td><strong>Fecha</strong></td>
                            <td><strong>Vendedor</strong></td>
                            <td><strong>Cliente</strong></td>
                            <td><strong>Foraneo</strong></td>
                            <td><strong>Factura</strong></td>
                            <td><strong>Tipo</strong></td>
                            <td><strong>Moneda</strong></td>
                            <td><strong>Debe</strong></td>
                            <td><strong>Haber</strong></td>
                            <td><strong>Saldo</strong></td>
                            <td><strong>Observacion</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data as $item):?>
                            <tr style="text-align:center;">
                                <td><?php echo $item["correlativo"]?></td>
                                <td><?php echo date('d-m-Y',strtotime($item["fecha"]))?></td>
                                <td><?php echo $item["codVendedor"]?></td>
                                <td><?php echo $item["codCliente"]?></td>
                                <td><?php echo $item["otros"]?></td>
                                <td><?php echo $item["factura"]?></td>
                                <td><?php echo $item["tipo_doc"]?></td>
                                <td><?php echo $item["moneda"]?></td>
                                <td><?php echo $item["debe"]?></td>  
                                <td><?php echo $item["haber"]?></td>    
                                <td>
                                    <?php 
                                    if($item['tipo_doc'] == 'INGRESO'){ $sum =  $sum + $item['debe']; echo number_format( $sum,2);}
                                    if($item['tipo_doc'] == 'EGRESO'){ $sum =  $sum - $item['haber']; echo number_format( $sum,2);}
                                    ?>
                                </td>     
                                <td><?php echo $item["observacion"]?></td> 
                                
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>                
        </div>
    </div>
    <br>
    <div class="container">
        <div class="col-md-12">
            <div class="card-body">     
                <table  id="tablaBS" class="table table-sm text-center table-condensed table-bordered table-striped">
                    <thead style="background-color: #00137f;color: white;">
                        <tr>
                            <th colspan="12" scope="rowgroup">Facturas Anuladas</th>
                        </tr>
                        <tr style="text-align:center;background:#219ebc;color: white;">
                            <td><strong>Correl</strong></td>
                            <td><strong>Fecha</strong></td>
                            <td><strong>Vendedor</strong></td>
                            <td><strong>Cliente</strong></td>
                            <td><strong>Foraneo</strong></td>
                            <td><strong>Factura</strong></td>
                            <td><strong>Tipo</strong></td>
                            <td><strong>Moneda</strong></td>
                            <td><strong>Debe</strong></td>
                            <td><strong>Haber</strong></td>
                            <td><strong>Saldo</strong></td>
                            <td><strong>Observacion</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($dataRevAnu as $item):?>
                            <tr style="text-align:center">
                                <td><?php echo $item["correlativo"]?></td>
                                <td><?php echo date('d-m-Y',strtotime($item["fecha"]))?></td>
                                <td><?php echo $item["codVendedor"]?></td>
                                <td><?php echo $item["codCliente"]?></td>
                                <td><?php echo $item["otros"]?></td>
                                <td><?php echo $item["factura"]?></td>
                                <td><?php echo $item["tipo_doc"]?></td>
                                <td><?php echo $item["moneda"]?></td>
                                <td><?php echo $item["debe"]?></td>  
                                <td><?php echo $item["haber"]?></td>    
                                <td>0</td>      
                                <td><?php echo $item["observacion"]?></td> 
                                
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>                
        </div>
    </div>
    <br>
    <div class="container">
        <div class="col-md-12">
            <div class="card-body">     
                <?php if( $moneda == 'BS'):?>
                    <table id="tablaBS" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
                        <thead style="background-color: #00137f;color: white;">
                            <tr>
                                <th colspan="9" scope="rowgroup">Moneda</th>
                            </tr>
                            <tr style="text-align:center;background:#219ebc;color: white;">
                                <td><strong>Denominacion</strong></td>
                                <td><strong>100 Bs</strong></td>
                                <td><strong>50 Bs</strong></td>
                                <td><strong>20 Bs</strong></td>
                                <td><strong>10 Bs</strong></td>
                                <td><strong>5 Bs</strong></td>
                                <td><strong>1 Bs</strong></td>
                                <td><strong>0.5 Bs</strong></td>
                                <td><strong>Total</strong></td>
                            </tr>
                        </thead> 
                        <tbody>
                            <tr style="text-align:center">  
                                <td>Bolivares</td>
                                <?php foreach($dataDenom as $el):?>
                                    <td><?php echo $el["total_100"]?></td>
                                    <td><?php echo $el["total_50"]?></td>
                                    <td><?php echo $el["total_20"]?></td>
                                    <td><?php echo $el["total_10"]?></td>
                                    <td><?php echo $el["total_5"]?></td>
                                    <td><?php echo $el["total_2"]?></td>
                                    <td><?php echo $el["total_1"]?></td> 
                                    <td><?php if($el["saldo"]==0){echo 0;}else{echo number_format($el["saldo"],2);} ?></td>                                                                                                  
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                <?php endif;?>
                <?php if( $moneda == 'DL'):?>
                    <table id="tablaDL" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
                        <thead style="background-color: #00137f;color: white;">
                            <tr>
                                <th colspan="9" scope="rowgroup">Moneda</th>
                            </tr>
                            <tr style="text-align:center;background:#219ebc;color: white;">
                                <td><strong>Denominacion</strong></td>
                                <td><strong>100 $</strong></td>
                                <td><strong>50 $</strong></td>
                                <td><strong>20 $</strong></td>
                                <td><strong>10 $</strong></td>
                                <td><strong>5 $</strong></td>
                                <td><strong>2 $</strong></td>
                                <td><strong>1 $</strong></td>
                                <td><strong>Total</strong></td>
                            </tr>
                        </thead> 
                        <tbody>
                            <tr style="text-align:center">
                                <td>Dolares</td>
                                <?php foreach($dataDenom as $el):?>
                                    <td><?php echo $el["total_100"]?></td>
                                    <td><?php echo $el["total_50"]?></td>
                                    <td><?php echo $el["total_20"]?></td>
                                    <td><?php echo $el["total_10"]?></td>
                                    <td><?php echo $el["total_5"]?></td>
                                    <td><?php echo $el["total_2"]?></td>
                                    <td><?php echo $el["total_1"]?></td> 
                                    <td><?php if($el["saldo"]==0){echo 0;}else{echo number_format($el["saldo"],2);} ?></td>                                                                                                     
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                <?php endif;?>
                <?php if( $moneda == 'EU'):?>
                    <table id="tablaEU" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
                        <thead style="background-color: #00137f;color: white;">
                            <tr>
                                <th colspan="9" scope="rowgroup">Moneda</th>
                            </tr>
                            <tr style="text-align:center;background:#219ebc;color: white;">
                                <td><strong>Denominacion</strong></td>
                                <td><strong>500 Eu</strong></td>
                                <td><strong>200 Eu</strong></td>
                                <td><strong>100 Eu</strong></td>
                                <td><strong>50 Eu</strong></td>
                                <td><strong>20 Eu</strong></td>
                                <td><strong>10 Eu</strong></td>
                                <td><strong>5 Eu</strong></td>
                                <td><strong>Total</strong></td>
                            </tr>
                        </thead> 
                        <tbody>   
                            <tr style="text-align:center">
                                <td>Euros</td>
                                <?php foreach($dataDenom as $el):?>
                                    <td><?php echo $el["total_100"]?></td>
                                    <td><?php echo $el["total_50"]?></td>
                                    <td><?php echo $el["total_20"]?></td>
                                    <td><?php echo $el["total_10"]?></td>
                                    <td><?php echo $el["total_5"]?></td>
                                    <td><?php echo $el["total_2"]?></td>
                                    <td><?php echo $el["total_1"]?></td> 
                                    <td><?php if($el["saldo"]==0){echo 0;}else{echo number_format($el["saldo"],2);} ?></td>                                                                                               
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                <?php endif;?>
            </div>                
        </div>
    </div>
    <br>
    <div class="container">
        <div class="col-md-12">
            <div class="card-body">      
                <table  id="tablaBS" class="table table-sm text-center table-condensed table-bordered table-striped">
                    <thead style="background-color: #00137f;color: white;">
                        <tr>
                            <th colspan="2" scope="rowgroup">Vueltos Pendientes</th>
                        </tr>
                        <tr style="text-align:center;background:#219ebc;color: white;">
                            <td><strong>Nombre</strong></td>
                            <?php if($moneda == 'BS'):?>
                                <td><strong>Pendiente de Vuelto Bs</strong></td>
                            <?php endif;?>
                            <?php if($moneda == 'DL'):?>
                                <td><strong>Pendiente de Vuelto $</strong></td> 
                            <?php endif;?>
                            <?php if($moneda == 'EU'):?>
                                <td><strong>Pendiente de Vuelto Eu</strong></td>
                            <?php endif;?>    
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($dataVueltos as $item):?>
                            <tr style="text-align:center">
                                <td><?php echo $item['Descrip']?></td>
                                <?php if($moneda == 'BS'):?>
                                    <td><?php echo number_format($item["acre_bs"],2)?></td>
                                <?php endif;?>
                                <?php if($moneda == 'DL'):?>
                                    <td><?php echo number_format($item["acre_dl"],2)?></td>
                                <?php endif;?>
                                <?php if($moneda == 'EU'):?>
                                    <td><?php echo number_format($item["acre_eu"],2)?></td>   
                                <?php endif;?>         
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>                
        </div>
    </div>
</section>   
