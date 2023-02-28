<?php 

header('Content-type: application/vnd.ms-excel;charset=UTF-8');
header("Content-Disposition: attachment; filename=resumen_recepcion_caja.xls");
header("Pragma: no-cache");
header("Expires: 0");
require_once 'PHPExcel_new/PHPExcel.php';
session_start();

require("conexion.php");

$fecha = $_SESSION['fecha'];
$codsucu = $_SESSION['codsucu1'];
$codesta = $_SESSION['codesta1'];

$data = array();  
$dataBS = array();
$dataDL = array();
$dataEU = array();
$dataRevAnu = array();
$dataTablaBS = array();
$dataTablaDL = array();
$dataTablaEU = array();
$sumBS = 0;
$sumDL = 0;
$sumEU = 0;

$query = "SELECT fecha,codVendedor,codCliente,otros,factura,
CASE moneda WHEN 'BS' THEN 'Bolivares' WHEN 'DL' THEN 'Dolares' WHEN 'EU' THEN 'Euros' END as moneda,monto,correlativo,
CASE tipo_doc WHEN 'I' THEN 'INGRESO' WHEN 'E' THEN 'EGRESO' WHEN 'A' THEN 'ANULADO' END AS tipo_doc
,debe,haber,observacion 
FROM SAREC_EFECTIVO WHERE CAST(fecha as DATE) = '$fecha' AND tipo_doc <> 'A' AND moneda = 'BS' AND codsucu='$codsucu' ORDER BY correlativo ASC";
$stmt = mssql_query($query);
if(mssql_num_rows($stmt)!=0){
    while($row = mssql_fetch_array($stmt)){
        $dataTablaBS[] = $row;
    }
}
$query = "SELECT fecha,codVendedor,codCliente,otros,factura,
CASE moneda WHEN 'BS' THEN 'Bolivares' WHEN 'DL' THEN 'Dolares' WHEN 'EU' THEN 'Euros' END as moneda,monto,correlativo,
CASE tipo_doc WHEN 'I' THEN 'INGRESO' WHEN 'E' THEN 'EGRESO' WHEN 'A' THEN 'ANULADO' END AS tipo_doc
,debe,haber,observacion 
FROM SAREC_EFECTIVO WHERE CAST(fecha as DATE) = '$fecha' AND tipo_doc <> 'A' AND moneda = 'DL' AND codsucu='$codsucu' ORDER BY correlativo ASC";
$stmt = mssql_query($query);
if(mssql_num_rows($stmt)!=0){
    while($row = mssql_fetch_array($stmt)){
        $dataTablaDL[] = $row;
    }
}
$query = "SELECT fecha,codVendedor,codCliente,otros,factura,
CASE moneda WHEN 'BS' THEN 'Bolivares' WHEN 'DL' THEN 'Dolares' WHEN 'EU' THEN 'Euros' END as moneda,monto,correlativo,
CASE tipo_doc WHEN 'I' THEN 'INGRESO' WHEN 'E' THEN 'EGRESO' WHEN 'A' THEN 'ANULADO' END AS tipo_doc
,debe,haber,observacion 
FROM SAREC_EFECTIVO WHERE CAST(fecha as DATE) = '$fecha' AND tipo_doc <> 'A' AND moneda = 'EU' AND codsucu='$codsucu' ORDER BY correlativo ASC";
$stmt = mssql_query($query);
if(mssql_num_rows($stmt)!=0){
    while($row = mssql_fetch_array($stmt)){
        $dataTablaEU[] = $row;
    }
}

$queryBs = "SELECT 
total_100 = (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda ='BS' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda = 'BS' AND haber > debe AND codsucu = '$codsucu'),
total_50 = (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda ='BS' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda = 'BS' AND haber > debe AND codsucu = '$codsucu'),
total_20 = (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda ='BS' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda = 'BS' AND haber > debe AND codsucu = '$codsucu'),
total_10 = (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda ='BS' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda = 'BS' AND haber > debe AND codsucu = '$codsucu'),
total_5 = (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda ='BS' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda = 'BS' AND haber > debe AND codsucu = '$codsucu'),
total_2 = (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda ='BS' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda = 'BS' AND haber > debe AND codsucu = '$codsucu'),
total_1 = (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda ='BS' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda = 'BS' AND haber > debe AND codsucu = '$codsucu'),
sum(debe) - sum(haber) as saldo
FROM SAREC_DENOM WHERE moneda ='BS' AND CAST(fecha AS DATE) = '$fecha' AND codsucu='$codsucu' group by moneda";
$stmtBS = mssql_query($queryBs);
if(mssql_num_rows($stmtBS)!=0){
    while($row = mssql_fetch_array($stmtBS)){
        $dataBS[] = $row;
    }
}else{
    $dataBS[0]["total_100"] = 0;
    $dataBS[0]["total_50"] = 0;
    $dataBS[0]["total_20"] = 0;
    $dataBS[0]["total_10"] = 0;
    $dataBS[0]["total_5"] = 0;
    $dataBS[0]["total_2"] = 0;
    $dataBS[0]["total_1"] = 0;
    $dataBS[0]["saldo"] = 0;
}

$queryDL = "SELECT 
total_100 = (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda ='DL' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda = 'DL' AND haber > debe AND codsucu = '$codsucu') ,
total_50 = (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda ='DL' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda ='DL' AND haber > debe AND codsucu = '$codsucu'),
total_20 = (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda ='DL' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda ='DL' AND haber > debe AND codsucu = '$codsucu'),
total_10 = (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda ='DL' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda ='DL' AND haber > debe AND codsucu = '$codsucu'),
total_5 = (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda ='DL' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda ='DL' AND haber > debe AND codsucu = '$codsucu'),
total_2 = (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda ='DL' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda = 'DL' AND haber > debe AND codsucu = '$codsucu'),
total_1 = (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda ='DL' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda ='DL' AND haber > debe AND codsucu = '$codsucu'),
sum(debe) - sum(haber) as saldo
FROM SAREC_DENOM WHERE moneda ='DL' AND CAST(fecha AS DATE) = '$fecha' AND codsucu='$codsucu' group by moneda";
$stmtDL = mssql_query($queryDL);
if(mssql_num_rows($stmtDL)!=0){
    while($row = mssql_fetch_array($stmtDL)){
        $dataDL[] = $row;
    }
}else{
    $dataDL[0]["total_100"] = 0;
    $dataDL[0]["total_50"] = 0;
    $dataDL[0]["total_20"] = 0;
    $dataDL[0]["total_10"] = 0;
    $dataDL[0]["total_5"] = 0;
    $dataDL[0]["total_2"] = 0;
    $dataDL[0]["total_1"] = 0;
    $dataDL[0]["saldo"] = 0;
}

$queryEU = "SELECT 
total_100 = (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda ='EU' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda = 'EU' AND haber > debe AND codsucu = '$codsucu') ,
total_50 = (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda ='EU' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda ='EU' AND haber > debe AND codsucu = '$codsucu'),
total_20 = (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda ='EU' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda ='EU' AND haber > debe AND codsucu = '$codsucu'),
total_10 = (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda ='EU' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda ='EU' AND haber > debe AND codsucu = '$codsucu'),
total_5 = (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda ='EU' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda ='EU' AND haber > debe AND codsucu = '$codsucu'),
total_2 = (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda ='EU' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda = 'EU' AND haber > debe AND codsucu = '$codsucu'),
total_1 = (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda ='EU' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda ='EU' AND haber > debe AND codsucu = '$codsucu'),
sum(debe) - sum(haber) as saldo
FROM SAREC_DENOM WHERE moneda ='EU' AND CAST(fecha AS DATE) = '$fecha' AND codsucu='$codsucu' group by moneda";
$stmtEU = mssql_query($queryEU);
if(mssql_num_rows($stmtEU)!=0){
    while($row = mssql_fetch_array($stmtEU)){
        $dataEU[] = $row;
    }
}else{
    $dataEU[0]["total_100"] = 0;
    $dataEU[0]["total_50"] = 0;
    $dataEU[0]["total_20"] = 0;
    $dataEU[0]["total_10"] = 0;
    $dataEU[0]["total_5"] = 0;
    $dataEU[0]["total_2"] = 0;
    $dataEU[0]["total_1"] = 0;
    $dataEU[0]["saldo"] = 0;
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
                            <th colspan="12" scope="rowgroup">Ingresos y Egresos Bolivares</th>
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
                        <?php foreach($dataTablaBS as $item):?>
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
                                <td>
                                    <?php 
                                    if($item['tipo_doc'] == 'INGRESO'){$sumBS = $sumBS + $item['debe']; echo number_format($sumBS,2);}
                                    if($item['tipo_doc'] == 'EGRESO'){$sumBS = $sumBS - $item['haber']; echo number_format($sumBS,2);}
                                    ?>
                                </td>     
                                <td><?php echo $item["observacion"]?></td> 
                                
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                <br>
                <table  id="tablaDL" class="table table-sm text-center table-condensed table-bordered table-striped">
                    <thead style="background-color: #00137f;color: white;">
                        <tr>
                            <th colspan="12" scope="rowgroup">Ingresos y Egresos Dolares</th>
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
                        <?php foreach($dataTablaDL as $item):?>
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
                                <td>
                                    <?php 
                                    if($item['tipo_doc'] == 'INGRESO'){$sumDL = $sumDL + $item['debe']; echo number_format($sumDL,2);}
                                    if($item['tipo_doc'] == 'EGRESO'){$sumDL = $sumDL - $item['haber']; echo number_format($sumDL,2);}
                                    ?>
                                </td>     
                                <td><?php echo $item["observacion"]?></td> 
                                
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                <br>
                <table  id="tablaEU" class="table table-sm text-center table-condensed table-bordered table-striped">
                    <thead style="background-color: #00137f;color: white;">
                        <tr>
                            <th colspan="12" scope="rowgroup">Ingresos y Egresos Euros</th>
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
                        <?php foreach($dataTablaEU as $item):?>
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
                                <td>
                                    <?php 
                                    if($item['tipo_doc'] == 'INGRESO'){$sumEU = $sumEU + $item['debe']; echo number_format($sumEU,2);}
                                    if($item['tipo_doc'] == 'EGRESO'){$sumEU = $sumEU - $item['haber']; echo number_format($sumEU,2);}
                                    ?>
                                </td>     
                                <td><?php echo $item["observacion"]?></td> 
                                
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                <br>
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
                <br>
            </div>                
        </div>
    </div>
    <br>
    <div class="container">
        <div class="col-md-12">
            <div class="card-body">     
                <table id="tablaBS" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
                    <thead style="background-color: #00137f;color: white;">
                        <tr>
                            <th colspan="9" scope="rowgroup">Monedas</th>
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
                            <?php foreach($dataBS as $el):?>
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
                <br>
                <table id="tablaDL" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
                    <thead style="background-color: #00137f;color: white;">
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
                            <?php foreach($dataDL as $el):?>
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
                <br>
                <table id="tablaEU" class="table table-sm text-center table-condensed table-bordered table-striped" width="100%">
                    <thead style="background-color: #00137f;color: white;">
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
                            <?php foreach($dataEU as $el):?>
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
                            <th colspan="4" scope="rowgroup">Vueltos Pendientes</th>
                        </tr>
                        <tr style="text-align:center;background:#219ebc;color: white;">
                            <td><strong>Nombre</strong></td>
                            <td><strong>Pendiente de Vuelto Bs</strong></td>
                            <td><strong>Pendiente de Vuelto $</strong></td> 
                            <td><strong>Pendiente de Vuelto Eu</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($dataVueltos as $item):?>
                            <tr style="text-align:center">
                                <td><?php echo $item['Descrip']?></td>
                                <td><?php echo number_format($item["acre_bs"],2)?></td>
                                <td><?php echo number_format($item["acre_dl"],2)?></td>
                                <td><?php echo number_format($item["acre_eu"],2)?></td>            
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>                
        </div>
    </div>
</section>
