<?php 
require("conexion.php");

session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
$timeZone = new DateTimeZone('America/La_Paz');
$hms = new DateTime("now",  $timeZone); 
$params = json_decode(file_get_contents('php://input'));
$usuario = $_SESSION['login'];
$codsucu = $_SESSION['codsucu1'];
$codesta = $_SESSION['codesta1'];

//REALIZA LA OPERACION DEL EFECTIVO INGRESADO
function actualizarDenominacion($denom,$moneda,$codVendedor,$codcliente,$factura,$fecha,$tipo_doc,$correlativo,$codsucu,$codesta){
    $sum_bs = 0;
    $sum_dl = 0;
    $sum_eu = 0;
    $monto = 0;
    $denom_update = "UPDATE SAREC_DENOM SET monto = 0,debe = 0, haber = 0 WHERE correlativo = '$correlativo' AND codsucu = '$codsucu'";
    mssql_query($denom_update);
    switch($moneda){
        case 'BS':  for($i = 0 ; $i < count($denom);$i++){
            if($i == 0 && $denom[$i] != ''){
                $sum_bs = $sum_bs + (100 * intval($denom[$i]));
                $denom_1 =  $denom[$i];
            }
            if($i == 1 && $denom[$i] != ''){
                $sum_bs = $sum_bs + (50 * intval($denom[$i]));
                $denom_2 =  $denom[$i];
            }
            if($i == 2 && $denom[$i] != ''){
                $sum_bs = $sum_bs + (20 * intval($denom[$i]));
                $denom_3 =  $denom[$i];
            }
            if($i == 3 && $denom[$i] != ''){
                $sum_bs = $sum_bs + (10 * intval($denom[$i]));
                $denom_4 =  $denom[$i];
            }
            if($i == 4 && $denom[$i] != ''){
                $sum_bs = $sum_bs + (5 * intval($denom[$i]));
                $denom_5 =  $denom[$i];
            }
            if($i == 5 && $denom[$i] != ''){
                $sum_bs = $sum_bs + (1 * intval($denom[$i]));
                $denom_6 =  $denom[$i];
            }
            if($i == 6 && $denom[$i] != ''){
                $sum_bs = $sum_bs + (0.5 * intval($denom[$i]));
                $denom_7 =  $denom[$i];
            }
        }
        $monto = $sum_bs; 
        break;
        
        case 'DL':  for($i = 0; $i < count($denom); $i++){ 
            
            if($i == 7 && $denom[$i] != ''){
                $sum_dl = $sum_dl + (100 * intval($denom[$i]));
                $denom_1 =  $denom[$i];
            }
            if($i == 8 && $denom[$i] != ''){
                $sum_dl = $sum_dl + (50 * intval($denom[$i]));
                $denom_2 =  $denom[$i];
            }
            if($i == 9 && $denom[$i] != ''){
                $sum_dl = $sum_dl + (20 * intval($denom[$i]));
                $denom_3 =  $denom[$i];
            }
            if($i == 10 && $denom[$i] != ''){
                $sum_dl = $sum_dl + (10 * intval($denom[$i]));
                $denom_4 =  $denom[$i];
            }
            if($i == 11 && $denom[$i] != ''){
                $sum_dl = $sum_dl + (5 * intval($denom[$i]));
                $denom_5 =  $denom[$i];
            }
            if($i == 12 && $denom[$i] != ''){
                $sum_dl = $sum_dl + (2 * intval($denom[$i]));
                $denom_6 =  $denom[$i];
            }
            if($i == 13 && $denom[$i] != ''){
                $sum_dl = $sum_dl + (1 * intval($denom[$i]));
                $denom_7 =  $denom[$i];
            }
        }
        $monto = $sum_dl;
        break;
        
        case 'EU':  for($i = 14 ; $i < count($denom); $i++){
            if($i == 14 && $denom[$i] != ''){
                $sum_eu = $sum_eu + (500 * intval($denom[$i]));
                $denom_1 =  $denom[$i];
            }
            if($i == 15 && $denom[$i] != ''){
                $sum_eu = $sum_eu + (200 * intval($denom[$i]));
                $denom_2 =  $denom[$i];
            }
            if($i == 16 && $denom[$i] != ''){
                $sum_eu = $sum_eu + (100 * intval($denom[$i]));
                $denom_3 =  $denom[$i];
            }
            if($i == 17 && $denom[$i] != ''){
                $sum_eu = $sum_eu + (50 * intval($denom[$i]));
                $denom_4 =  $denom[$i];
            }
            if($i == 18 && $denom[$i] != ''){
                $sum_eu = $sum_eu + (20 * intval($denom[$i]));
                $denom_5 =  $denom[$i];
            }
            if($i == 19 && $denom[$i] != ''){
                $sum_eu = $sum_eu + (10 * intval($denom[$i]));
                $denom_6 =  $denom[$i];
            }
            if($i == 20 && $denom[$i] != ''){
                $sum_eu = $sum_eu + (5 * intval($denom[$i]));
                $denom_7 =  $denom[$i];
            }
        } 
        $monto = $sum_eu;
        break;
    }
    
    
    if($tipo_doc == 'I'){
        $query = "UPDATE SAREC_DENOM SET fecha=getdate(),codVendedor='$codVendedor',codCliente='$codcliente',factura='$factura',tipo_doc='$tipo_doc',monto='$monto',debe='$monto',haber=0,
        denom_1='$denom_1', denom_2='$denom_2', denom_3='$denom_3',denom_4='$denom_4', denom_5='$denom_5', denom_6='$denom_6', denom_7='$denom_7'
        WHERE correlativo = '$correlativo' AND codsucu = '$codsucu'"; 
        $stmt = mssql_query($query);
    }
    if($tipo_doc == 'E'){
        $query = "UPDATE SAREC_DENOM SET fecha=getdate(),codVendedor='$codVendedor',codCliente='$codcliente',factura='$factura',tipo_doc='$tipo_doc',monto='$monto',debe=0,haber='$monto',
        denom_1='$denom_1', denom_2='$denom_2', denom_3='$denom_3',denom_4='$denom_4', denom_5='$denom_5', denom_6='$denom_6', denom_7='$denom_7'
        WHERE correlativo = '$correlativo' AND codsucu = '$codsucu'"; 
        $stmt = mssql_query($query); 
    }
    
    return $monto;
}

    //RETORNO EL SALDO TOTAL POR FILAS
function ingreso_salida($tipo_doc,$monto,$moneda,$factura,$codsucu){
    $queryIngreso = "SELECT max(saldo) as total FROM SAREC_EFECTIVO 
    WHERE factura = '$factura' AND moneda='$moneda' 
    AND codsucu = '$codsucu' AND tipo_doc <> 'A'";
    $stmtIngreso = mssql_query($queryIngreso);
    if(mssql_num_rows($stmtIngreso)!=0){
        while($row = mssql_fetch_array($stmtIngreso)){
            $saldo = $row["total"];
        }
    }
    switch($tipo_doc){
        case 'I':   $saldo = $saldo + $monto;
        return $saldo;
        break;
        
        case 'E':   $saldo = $saldo - $monto;
        return $saldo;
        break;
    }
    return $saldo;
}

$data = array();
$msj = array("error" =>"La factura no pudo ser actualizada"); 

if($params->tipo_doc == 'A'){
    $fecha_act = date('Y-m-d');
    $fecha_act = $fecha_act.' '.$hms->format('H:i:s');
    $factura = $params->factura;
    $tipo_doc = $params->tipo_doc;
    $queryAnular = "SELECT tipo_doc,monto,correlativo,fecha,codVendedor FROM SAREC_EFECTIVO WHERE factura = '$factura'";
    $stmtAnular = mssql_query($queryAnular); 
    if(mssql_num_rows($stmtAnular) != 0){
        while($row = mssql_fetch_array($stmtAnular)){
            $anu = $row['tipo_doc'];
            $monto = $row['monto'];
            $correlativo = $row['correlativo'];
            $fecha = $row['fecha'];
            $codVendedor = $row['codVendedor'];
        }
        if($anu == 'A'){
            echo json_encode(array("error" => 'La factura ya fue Anulada'));
        }else{
            $queryCorrel = "SELECT MAX(correlativo) correlativo FROM SAREC_EFECTIVO";
            $stmt = mssql_query($queryCorrel);
            while($row = mssql_fetch_array($stmt)){
                $utl_correlativo = $row['correlativo'];
            }
            $queryVerificar = "DELETE FROM SAREC_DENOM WHERE factura = '$factura'"; 
            $queryBorraVuelto = "DELETE FROM SAREC_VUELTOS WHERE correlativo = '$correlativo'";
            mssql_query($queryBorraVuelto);
            $stmtVerificar = mssql_query($queryVerificar);
            $updateQuery = "UPDATE SAREC_EFECTIVO SET saldo = 0,usuario = '$usuario',tipo_doc = '$tipo_doc', codsucu='$codsucu', codesta='$codesta',fecha='$fecha_act'
            WHERE factura = '$factura' AND codVendedor = '$codVendedor'";
            $update= mssql_query($updateQuery); 
            $querySaldo = "UPDATE SAREC_EFECTIVO SET saldo = saldo - '$monto' 
            WHERE correlativo > '$correlativo' AND correlativo <= '$utl_correlativo' AND tipo_doc <> 'A'";
            mssql_query($querySaldo);
            if($update){
                echo json_encode(array("ok" => 'Documento Anulado con Exito'));
            }else{  
                echo json_encode($msj);
            }
        }
    }
    
}

if(!$params){
    $codVendedor = $_POST["vendedor"];
    $codCliente = $_POST["cliente"];
    $correlativo = $_POST["correlativo"];
    $observacion = $_POST["observacion"];
    $factura = $_POST['factura1'];
    $monto = $_POST["monto"];
    $moneda = $_POST['moneda'];
    $denom = $_POST['denom'];
    $tipo_doc = $_POST['tipo_doc'];
        //$fecha = date('Y-m-d',strtotime($_POST["fecha1"]));
    if($moneda == "Bolivares"){
        $moneda = "BS";
    }
    if($moneda == "Dolares"){
        $moneda = "DL";
    }
    if($moneda == "Euros"){
        $moneda = "EU";
    }

    $queryCorrel = "SELECT MAX(correlativo) correlativo FROM SAREC_EFECTIVO";
    $stmt = mssql_query($queryCorrel);
    while($row = mssql_fetch_array($stmt)){
        $ult_correlativo = $row['correlativo'];
    } 
    
    $queryMonto = "SELECT monto FROM SAREC_EFECTIVO where factura = '$factura'";
    $stmt = mssql_query($queryMonto);
    while($row = mssql_fetch_array($stmt)){
        $monto_anterior = $row['monto'];
    } 

    if($tipo_doc == 'I'){
        $fecha_act = date('Y-m-d');
        $fecha_act = $fecha_act.' '.$hms->format('H:i:s');
        $query1 = "SELECT Descrip FROM SAVEND WHERE codVend ='".$codVendedor."'";
        $query2 = "SELECT Descrip FROM SACLIE WHERE codClie ='".$codCliente."'";
        $stmt1 = mssql_query($query1);
        $stmt2 = mssql_query($query2);
        if(mssql_num_rows($stmt1)!=0){
            while($row = mssql_fetch_array($stmt1)){
                $nombVendedor = $row["Descrip"];
            }
        }
        if(mssql_num_rows($stmt2)!=0){
            while($row = mssql_fetch_array($stmt2)){
                $nombCliente = str_replace("'",'',$row["Descrip"]);
            }
        }
        for($i = 0; $i < count($moneda);$i++){
            $saldo = ingreso_salida($tipo_doc,$monto[$i],$moneda,$factura,$codsucu);
            $monto_recibido = actualizarDenominacion($denom,$moneda,$codVendedor,$codcliente,$factura,$fecha,$tipo_doc,$correlativo,$codsucu,$codesta);
            $monto_acreditado = $monto_recibido - floatval($monto[$i]);
            if($monto_acreditado > 0){
                $update = "UPDATE SAREC_EFECTIVO SET fecha=getdate(), codVendedor = '$codVendedor',nombVendedor = '$nombVendedor',
                codCliente='$codCliente',nombCliente='$nombCliente',factura='$factura',monto='$monto_recibido',tipo_doc = '$tipo_doc',debe='$monto_recibido',haber=0,
                monto_acreditado='$monto_acreditado',observacion='$observacion',usuario = '$usuario',codsucu='$codsucu',codesta='$codesta'
                WHERE correlativo = '$correlativo' AND codsucu = '$codsucu'"; 
            }else{
                $update = "UPDATE SAREC_EFECTIVO SET fecha=getdate(), codVendedor = '$codVendedor',nombVendedor = '$nombVendedor',
                codCliente='$codCliente',nombCliente='$nombCliente',factura='$factura',monto='$monto_recibido',tipo_doc = '$tipo_doc',debe='$monto_recibido',haber=0,
                monto_acreditado='$monto_acreditado',observacion='$observacion',usuario = '$usuario',codsucu='$codsucu',codesta='$codesta'
                WHERE correlativo = '$correlativo' AND codsucu = '$codsucu'";
            }        
            if($correlativo == $ult_correlativo){
                $res = $saldo - $monto_anterior;
                $monto_recibido = $monto_recibido + $res;
                $updateSaldo = "UPDATE SAREC_EFECTIVO SET saldo = '$monto_recibido' WHERE correlativo = '$correlativo' AND codsucu = '$codsucu' ";
                mssql_query($updateSaldo);
            }else{
                $getSaldo = "SELECT saldo as total FROM SAREC_EFECTIVO WHERE factura = '$factura' AND moneda = '$moneda' AND codsucu = '$codsucu' AND tipo_doc <> 'A'";
                $stmtSaldo = mssql_query($getSaldo);
                if(mssql_num_rows($stmtSaldo)!=0){
                    while($row = mssql_fetch_array($stmtSaldo)){
                        $saldo = $row["total"];
                    }
                }
                $res = $saldo - $monto_anterior;
                $monto_recibido = $monto_recibido + $res;
                $updateSaldo1 = "UPDATE SAREC_EFECTIVO SET saldo = '$monto_recibido' WHERE correlativo = '$correlativo' AND codsucu = '$codsucu'";
                mssql_query($updateSaldo1);
                $updateSaldo2 = "UPDATE SAREC_EFECTIVO SET saldo = monto + '$monto_recibido' WHERE correlativo > $correlativo AND correlativo <= $ult_correlativo";
                mssql_query($updateSaldo2);
            }       
            
            $update = mssql_query($update);    
            if($update){
                echo json_encode(array("ok"=>"El documento fue actualizado con exito"));
            }
            if(!$update){
                echo json_encode(array("error"=>"El documento no puede ser actualizado"));
            }      
        }  
    }
    if($tipo_doc == 'E'){       
        $query1 = "SELECT Descrip FROM SAVEND WHERE codVend ='".$codVendedor."'";
        $query2 = "SELECT Descrip FROM SACLIE WHERE codClie ='".$codCliente."'";
        $stmt1 = mssql_query($query1);
        $stmt2 = mssql_query($query2);
        if(mssql_num_rows($stmt1)!=0){
            while($row = mssql_fetch_array($stmt1)){
                $nombVendedor = $row["Descrip"];
            }
        }
        if(mssql_num_rows($stmt2)!=0){
            while($row = mssql_fetch_array($stmt2)){
                $nombCliente = str_replace("'",'',$row["Descrip"]);
            }
        }
        for($i = 0; $i < count($moneda);$i++){
            $monto_recibido = actualizarDenominacion($denom,$moneda,$codVendedor,$codcliente,$factura,$fecha,$tipo_doc,$correlativo,$codsucu,$codesta);
            $update = "UPDATE SAREC_EFECTIVO SET fecha=getdate(), codVendedor = '$codVendedor',nombVendedor = '$nombVendedor',
            codCliente='$codCliente',nombCliente='$nombCliente',factura='$factura',monto='$monto_recibido',tipo_doc = '$tipo_doc',debe=0,haber='$monto_recibido',saldo='$monto_recibido',
            monto_acreditado='$monto_acreditado',observacion='$observacion',usuario = '$usuario',codsucu='$codsucu',codesta='$codesta'
            WHERE correlativo = '$correlativo' AND codsucu = '$codsucu'"; echo $update;
            $updateSaldo = "UPDATE SAREC_EFECTIVO SET saldo = saldo - $monto_recibido WHERE correlativo > '$correlativo' AND correlativo <= '$correlativo'";
            $update = mssql_query($update);    
            if($update){
                echo json_encode(array("ok"=>"El documento fue actualizado con exito"));
            }
            if(!$update){
                echo json_encode(array("error"=>"El documento no puede ser actualizado"));
            }   
        }
    }
}


?>
