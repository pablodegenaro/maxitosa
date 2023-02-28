<?php
session_start();
require('conexion.php');
    //$params = json_decode(file_get_contents('php://input'));
$usuario = $_SESSION['login'];
$codsucu = $_SESSION['codsucu1'];
$codesta = $_SESSION['codesta1'];
$fecha = $_SESSION['fecha'];
$nombre = $_POST['nombre'];
$codVendedor = $_POST['codVendedor'];
$moneda = $_POST['moneda'];
$factura = $_POST['factura'];


if($_SESSION['codsucu1'] == '00000'){
    $FieldName = 'LenCajaP';
}
if($_SESSION['codsucu1'] == '00001'){
    $FieldName = 'LenCajaM';
}
if($_SESSION['codsucu1'] == '00002'){
    $FieldName = 'LenCajaC';
}
if($moneda == 'Bolivares'){
    $moneda = 'BS';
}
if($moneda == 'Dolares'){
    $moneda = 'DL';
}
if($moneda == 'Euros'){
 $moneda = 'EU'; 
}
$tipo_doc = $_POST['tipo_doc'];
$monto_acreditado = $_POST['monto_acreditado'];
$monto_retirar = $_POST['monto'];
$observacion = $_POST['observacion'];
$denom = $_POST['denom'];
$correlativo = $_POST['correlativo'];
if($tipo_doc == 'Retiro'){
    $tipo_doc = 'E';
}

function retiroDenominacion($denom,$factura,$moneda,$codVendedor,$codcliente,$correlativo,$monto_retirar,$tipo_doc,$usuario,$codsucu,$codesta){
    $sum_bs = 0;
    $sum_dl = 0;
    $sum_eu = 0;
    $monto = 0;

    $data = array();
    $querySearch = "SELECT 
    total_100 = (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda ='$moneda' AND debe > haber) - (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda = '$moneda' AND haber > debe) ,
    total_50 = (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda ='$moneda' AND debe > haber) - (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda ='$moneda' AND haber > debe),
    total_20 = (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda ='$moneda' AND debe > haber) - (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda ='$moneda' AND haber > debe),
    total_10 = (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda ='$moneda' AND debe > haber) - (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda ='$moneda' AND haber > debe),
    total_5 = (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda ='$moneda' AND debe > haber) - (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda ='$moneda' AND haber > debe),
    total_2 = (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda ='$moneda' AND debe > haber) - (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda = '$moneda' AND haber > debe),
    total_1 = (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda ='$moneda' AND debe > haber) - (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda ='$moneda' AND haber > debe)
    FROM SAREC_DENOM WHERE moneda ='$moneda' group by moneda";
    $stmt = mssql_query($querySearch);
    if(mssql_num_rows($stmt)!=0){
        while($row = mssql_fetch_array($stmt)){
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
            $data[] = $row;
        }
    }
    
    switch($moneda){
        case 'BS':  for($i = 0 ; $i < count($denom);$i++){
            if($i == 0 && $denom[$i] != ''){
                $sum_bs = $sum_bs + (100 * intval($denom[$i]));
                $denom_1 =  $denom[$i];
                if($denom_1 > $data[0]['total_100']){
                    $band = 0;
                }else{
                    $band = 1;
                }
            }
            if($i == 1 && $denom[$i] != ''){
                $sum_bs = $sum_bs + (50 * intval($denom[$i]));
                $denom_2 =  $denom[$i];
                if($denom_2 > $data[0]['total_50']){
                    $band = 0;
                }else{
                    $band = 1;
                }
            }
            if($i == 2 && $denom[$i] != ''){
                $sum_bs = $sum_bs + (20 * intval($denom[$i]));
                $denom_3 =  $denom[$i];
                if($denom_3 > $data[0]['total_20']){
                    $band = 0;
                }else{
                    $band = 1;
                }
            }
            if($i == 3 && $denom[$i] != ''){
                $sum_bs = $sum_bs + (10 * intval($denom[$i]));
                $denom_4 =  $denom[$i];
                if($denom_4 > $data[0]['total_10']){
                    $band = 0;
                }else{
                    $band = 1;
                }
            }
            if($i == 4 && $denom[$i] != ''){
                $sum_bs = $sum_bs + (5 * intval($denom[$i]));
                $denom_5 =  $denom[$i];
                if($denom_1 > $data[0]['total_5']){
                    $band = 0;
                }else{
                    $band = 1;
                }
            }
            if($i == 5 && $denom[$i] != ''){
                $sum_bs = $sum_bs + (1 * intval($denom[$i]));
                $denom_6 =  $denom[$i];
                if($denom_6 > $data[0]['total_2']){
                    $band = 0;
                }else{
                    $band = 1;
                }
                if($data[0]['total_2'] == 0){
                    $band == 2;
                }
            }
            if($i == 6 && $denom[$i] != ''){
                $sum_bs = $sum_bs + (0.5 * intval($denom[$i]));
                $denom_7 =  $denom[$i];
                if($denom_1 > $data[0]['total_1']){
                    $band = 0;
                }else{
                    $band = 1;
                }
            }
        }
        $monto_denom = $sum_bs; 
        break;
        
        case 'DL':  for($i = 0; $i < count($denom); $i++){ 
            
            if($i == 7 && $denom[$i] != ''){
                $sum_dl = $sum_dl + (100 * intval($denom[$i]));
                $denom_1 =  $denom[$i];
                if($denom_1 > $data[0]['total_100']){
                    $band = 0;
                }else{
                    $band = 1;
                }
            }
            if($i == 8 && $denom[$i] != ''){
                $sum_dl = $sum_dl + (50 * intval($denom[$i]));
                $denom_2 =  $denom[$i];
                if($denom_2 > $data[0]['total_50']){
                    $band = 0;
                }else{
                    $band = 1;
                }
            }
            if($i == 9 && $denom[$i] != ''){
                $sum_dl = $sum_dl + (20 * intval($denom[$i]));
                $denom_3 =  $denom[$i];
                if($denom_3 > $data[0]['total_20']){
                    $band = 0;
                }else{
                    $band = 1;
                }
            }
            if($i == 10 && $denom[$i] != ''){
                $sum_dl = $sum_dl + (10 * intval($denom[$i]));
                $denom_4 =  $denom[$i];
                if($denom_4 > $data[0]['total_10']){
                    $band = 0;
                }else{
                    $band = 1;
                }
            }
            if($i == 11 && $denom[$i] != ''){
                $sum_dl = $sum_dl + (5 * intval($denom[$i]));
                $denom_5 =  $denom[$i];
                if($denom_5 > $data[0]['total_5']){
                    $band = 0;
                }else{
                    $band = 1;
                }
            }
            if($i == 12 && $denom[$i] != ''){
                $sum_dl = $sum_dl + (2 * intval($denom[$i]));
                $denom_6 =  $denom[$i];
                if($denom_6 > $data[0]['total_2']){
                    $band = 0;
                }else{
                    $band = 1;
                }
            }
            if($i == 13 && $denom[$i] != ''){
                $sum_dl = $sum_dl + (1 * intval($denom[$i]));
                $denom_7 =  $denom[$i];
                if($denom_7 > $data[0]['total_1']){
                    $band = 0;
                }else{
                    $band = 1;
                }
            }
        }
        $monto_denom = $sum_dl;
        break;
        
        case 'EU':  for($i = 14 ; $i < count($denom); $i++){
            if($i == 14 && $denom[$i] != ''){
                $sum_eu = $sum_eu + (500 * intval($denom[$i]));
                $denom_1 =  $denom[$i];
                if($denom_1 > $data[0]['total_100']){
                    $band = 0;
                }else{
                    $band = 1;
                }
            }
            if($i == 15 && $denom[$i] != ''){
                $sum_eu = $sum_eu + (200 * intval($denom[$i]));
                $denom_2 =  $denom[$i];
                if($denom_2 > $data[0]['total_50']){
                    $band = 0;
                }else{
                    $band = 1;
                }
            }
            if($i == 16 && $denom[$i] != ''){
                $sum_eu = $sum_eu + (100 * intval($denom[$i]));
                $denom_3 =  $denom[$i];
                if($denom_3 > $data[0]['total_20']){
                    $band = 0;
                }else{
                    $band = 1;
                }
            }
            if($i == 17 && $denom[$i] != ''){
                $sum_eu = $sum_eu + (50 * intval($denom[$i]));
                $denom_4 =  $denom[$i];
                if($denom_4 > $data[0]['total_10']){
                    $band = 0;
                }else{
                    $band = 1;
                }
            }
            if($i == 18 && $denom[$i] != ''){
                $sum_eu = $sum_eu + (20 * intval($denom[$i]));
                $denom_5 =  $denom[$i];
                if($denom_5 > $data[0]['total_5']){
                    $band = 0;
                }else{
                    $band = 1;
                }
            }
            if($i == 19 && $denom[$i] != ''){
                $sum_eu = $sum_eu + (10 * intval($denom[$i]));
                $denom_6 =  $denom[$i];
                if($denom_6 > $data[0]['total_2']){
                    $band = 0;
                }else{
                    $band = 1;
                }
            }
            if($i == 20 && $denom[$i] != ''){
                $sum_eu = $sum_eu + (5 * intval($denom[$i]));
                $denom_7 =  $denom[$i];
                if($denom_7 > $data[0]['total_1']){
                    $band = 0;
                }else{
                    $band = 1;
                }
            }
        } 
        $monto_denom = $sum_eu;
        break;
    }

    if($band == 1){
        if($monto_denom > $monto_retirar){
            $msj = 'error1';
            return $msj;
        }
        if($monto_retirar > $monto_denom){
            $msj = 'error2';
            return $msj;
        }
        else{
            $msj = 'ok';
            $haber = $monto_denom;
            $query = "INSERT INTO SAREC_DENOM (fecha,codVendedor,codCliente,factura,moneda,monto,tipo_doc,debe,haber,correlativo,denom_1,
                denom_2,denom_3,denom_4,denom_5,denom_6,denom_7,usuario,codsucu,codesta) 
            VALUES (getdate(),'$codVendedor','','$factura','$moneda',$monto_denom,'$tipo_doc',0,'$haber','$correlativo','$denom_1',
                '$denom_2','$denom_3','$denom_4','$denom_5','$denom_6','$denom_7','$usuario','$codsucu','$codesta')"; 
            $stmt = mssql_query($query);
            return $msj;
        }   
    }
    if($band == 0){
        $msj = 'error3';
        return $msj;
    }
    
}

function saldoAcreditado($monto,$moneda,$codsucu){
    $queryIngreso = "SELECT max(saldo) as total FROM SAREC_EFECTIVO WHERE moneda='$moneda' AND codsucu = '$codsucu' AND tipo_doc <> 'A'";
    $stmtIngreso = mssql_query($queryIngreso);
    if(mssql_num_rows($stmtIngreso)!=0){
        while($row = mssql_fetch_array($stmtIngreso)){
            $saldo = $row["total"];
        }
    }

    $saldo = $saldo - $monto;
    return $saldo;
}

$saldo = saldoAcreditado($monto,$moneda,$codsucu);
if(floatval($monto_retirar) > floatval($monto_acreditado)){
    echo json_encode(array("error" => "EL monto a retirar supera el saldo acreditado"));
}else{
    $monto_denom = retiroDenominacion($denom,$factura,$moneda,$codVendedor,$codcliente,$correlativo,$monto_retirar,$tipo_doc,$usuario,$codsucu,$codesta);
    if($monto_denom == 'ok'){
        $updateCorrelativo = "UPDATE SACORRELSIS SET ValueInt = ValueInt + 1 WHERE FieldName = '$FieldName'";
        $monto_acreditado = $monto_acreditado - $monto;
        $query= "INSERT INTO SAREC_EFECTIVO (fecha,codVendedor,nombVendedor,monto,factura,moneda,correlativo,tipo_doc,debe,haber,saldo,monto_acreditado,observacion,usuario,codsucu,codesta)
        VALUES (getdate(),'$codVendedor','$nombre','$monto_retirar','$factura','$moneda','$correlativo','$tipo_doc',0,'$monto_retirar',$saldo,'$monto_acreditado','$observacion','$usuario','$codsucu','$codesta')";
        $stmt = mssql_query($query);
        
        $queryVueltosInsert = "INSERT INTO SAREC_VUELTOS
        (codVendedor
            ,Descrip
            ,moneda
            ,monto
            ,debe
            ,haber
            ,fecha_ini
            ,correlativo
            ,usuario
            ,codsucu
            ,codesta)
        VALUES
        ('$codVendedor',
            '$nombre'
            ,'$moneda'
            ,'$monto_retirar'
            ,0
            ,'$monto_retirar'
            ,getdate()
            ,'$correlativo'
            ,'$usuario'
            ,'$codsucu'
            ,'$codesta')";
        mssql_query($queryVueltosInsert);
        $updateCorrelativo = "UPDATE SACORRELSIS SET ValueInt = ValueInt + 1 WHERE FieldName = '$FieldName'";
        mssql_query($updateCorrelativo);
        if($stmt){
            echo json_encode(array("ok" => "Operacion realizada correctamente"));
        }
    }
    if($monto_denom == 'error1'){
        echo json_encode(array("error1" => "La Cantidad de Billetes supera el limite del monto a retirar"));
    }
    if($monto_denom == 'error2'){
        echo json_encode(array("error2" => "El monto a retirar supera la cantidad de billetes"));
    }
    if($monto_denom == 'error3'){
        echo json_encode(array("error3" => "No existe billetes de esta denominacion"));
    }
    
}

?>