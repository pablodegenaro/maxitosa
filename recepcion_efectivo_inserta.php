<?php 
require("conexion.php");

session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
$timeZone = new DateTimeZone('America/La_Paz');
$hms = new DateTime("now",  $timeZone); 
$usuario = $_SESSION['login'];
$codsucu = $_SESSION['codsucu1'];
$codesta = $_SESSION['codesta1'];

if($_SESSION['codsucu1'] == '00000'){
    $FieldName = 'LenCajaP';
}
if($_SESSION['codsucu1'] == '00001'){
    $FieldName = 'LenCajaM';
}
if($_SESSION['codsucu1'] == '00002'){
    $FieldName = 'LenCajaC';
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $fecha_e = $_POST["fecha"];
    $fecha = $fecha_e." ".$hms->format('H:i:s');
    $codVendedor = $_POST["vendedor"];
    $codClie = $_POST["clientev"];
    $codcliente = $_POST["cliente"];

    if(!$codcliente){
        $codcliente = $codClie;
    }
    
    $factura = $_POST["factura"];
    $moneda = $_POST["moneda"];
    $correlativo = $_POST["correlativo"];
    $tipo_doc = $_POST["tipo_doc"];
    $observacion = $_POST["observacion"];
    $denom= $_POST["denom"];
    $otros = $_POST["otros"];
    $monto = $_POST["monto"];

    if(!$fecha && !$codVendedor && !$codcliente && !$factura && !$monto && !$moneda && !$correlativo && !$tipo_doc){
        unset($_POST);
        return;
    }else{
     
        $query1 = "SELECT Descrip FROM SAVEND WHERE codVend ='".$codVendedor."'";
        $query2 = "SELECT Descrip FROM SACLIE WHERE codClie ='".$codcliente."'";
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
        crearRecEfectivo($codVendedor,$nombVendedor,$codcliente,
            $nombCliente,$otros,$factura,$monto,$saldo_denom,
            $moneda,$correlativo,$tipo_doc,$observacion,$usuario,
            $codsucu,$codesta,$denom,$FieldName);
        
        
    }    
}

//RETORNO EL SALDO TOTAL POR FILAS
function ingreso_salida($tipo_doc,$monto,$moneda,$codsucu){
    $queryIngreso = "SELECT max(saldo) as total FROM SAREC_EFECTIVO WHERE moneda='$moneda' AND codsucu = '$codsucu' AND tipo_doc <> 'A'";
    $stmtIngreso = mssql_query($queryIngreso);
    if(mssql_num_rows($stmtIngreso)!=0){
        while($row = mssql_fetch_array($stmtIngreso)){
            $saldo = $row["total"];
        }
    }
    // A SON INGRESOS B EGRESOS
    switch($tipo_doc){
        case 'I':   $saldo = $saldo + $monto;
        return $saldo;
        break;

        case 'E':   $saldo = $saldo - $monto;
        return $saldo;
        break;
    }
}


//REALIZA LA OPERACION DEL EFECTIVO INGRESADO
function crearDenominacion($denom,$moneda,$codVendedor,$codcliente,$factura,$tipo_doc,$correlativo,$usuario,$codsucu,$codesta){
    $sum_bs = 0;
    $sum_dl = 0;
    $sum_eu = 0;
    $monto = 0;
    $data = array();
    $querySearch = "SELECT 
    total_100 = (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu'),
    total_50 = (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu'),
    total_20 = (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu'),
    total_10 = (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu'),
    total_5 = (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu'),
    total_2 = (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu'),
    total_1 = (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu')
    FROM SAREC_DENOM WHERE moneda ='$moneda' AND codsucu = '$codsucu' group by moneda";
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
                if($denom_5> $data[0]['total_5']){
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
            }
            if($i == 6 && $denom[$i] != ''){
                $sum_bs = $sum_bs + (0.5 * intval($denom[$i]));
                $denom_7 =  $denom[$i];
                if($denom_7 > $data[0]['total_1']){
                    $band = 0;
                }else{
                    $band = 1;
                }
            }
        }
        $monto = $sum_bs; 
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
        $monto = $sum_dl;
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
        $monto = $sum_eu;
        break;
    }


    if($tipo_doc == 'I'){
        $debe = $monto;
        $query = "INSERT INTO SAREC_DENOM (fecha,codVendedor,codCliente,factura,moneda,monto,tipo_doc,debe,haber,correlativo,denom_1,
            denom_2,denom_3,denom_4,denom_5,denom_6,denom_7,usuario,codsucu,codesta) 
        VALUES (getdate(),'$codVendedor','$codcliente','$factura','$moneda',$monto,'$tipo_doc','$debe',0,'$correlativo','$denom_1',
            '$denom_2','$denom_3','$denom_4','$denom_5','$denom_6','$denom_7','$usuario','$codsucu','$codesta')"; 
        $stmt = mssql_query($query);
        return $monto;
    }
    if($tipo_doc == 'E'){
        if($band == 0){
            $msj = 'error2';
            return $msj;
        }else{
            $haber = $monto;
            $query = "INSERT INTO SAREC_DENOM (fecha,codVendedor,codCliente,factura,moneda,monto,tipo_doc,debe,haber,correlativo,denom_1,
                denom_2,denom_3,denom_4,denom_5,denom_6,denom_7,usuario,codsucu,codesta) 
            VALUES (getdate(),'$codVendedor','$codcliente','$factura','$moneda',$monto,'$tipo_doc',0,'$haber','$correlativo','$denom_1',
                '$denom_2','$denom_3','$denom_4','$denom_5','$denom_6','$denom_7','$usuario','$codsucu','$codesta')"; 
            $stmt = mssql_query($query);
            return $monto; 
        }
    }
}

/*****INGRESO LA DATA SUMINISTRADA******/
function crearRecEfectivo($codVendedor,$nombVendedor,$cliente,$nombCliente,$otros,$factura,$monto,$saldo_denom,$moneda,$correlativo,$tipo_doc,$observacion,$usuario,$codsucu,$codesta,$denom,$FieldName){
    if(count($moneda)>0){
      
        $monto_recibido = crearDenominacion($denom,$moneda,$codVendedor,$codcliente,$factura,$tipo_doc,$correlativo,$usuario,$codsucu,$codesta);
        $saldo = ingreso_salida($tipo_doc,$monto_recibido,$moneda,$codsucu);
        if(!$observacion){
            $observacion = '';
        }
        if($saldo == 0){
            $saldo = $monto_recibido;
        }
        if($saldo < 0){
            $saldo = 0;
            echo json_encode(array("error"=>"La caja actual no posee saldo disponible"));
        }else{
            if($tipo_doc == 'I'){
                if(count($moneda)>1){
                    $tipo_pago = 1; 
                }else{
                    $tipo_pago = 0;
                }
                if($otros != NULL){
                    $query= "INSERT INTO SAREC_EFECTIVO (fecha,codVendedor,nombVendedor,codCliente,nombCliente,otros,factura,monto,moneda,correlativo,tipo_doc,debe,haber,saldo,observacion,usuario,codsucu,codesta,tipo_pago)
                    VALUES (getdate(),'$codVendedor','$nombVendedor','$cliente','$nombCliente','$otros','$factura','$monto_recibido','$moneda','$correlativo','$tipo_doc','$monto_recibido',0,'$saldo','$observacion','$usuario','$codsucu','$codesta','$tipo_pago')";
                    $stmt = mssql_query($query); 
                }else{
                    if($monto>0){
                        $monto_acreditado = $monto_recibido - floatval($monto);
                    }
                        //SI EL MONTO O TOTAL A PAGAR ES NULL NO TOMA EL MONTO_ACREDITADO
                    if($monto_acreditado > 0 ){
                        $query= "INSERT INTO SAREC_EFECTIVO (fecha,codVendedor,nombVendedor,codCliente,nombCliente,factura,monto,moneda,correlativo,tipo_doc,debe,haber,saldo,monto_acreditado,observacion,usuario,codsucu,codesta,tipo_pago)
                        VALUES (getdate(),'$codVendedor','$nombVendedor','$cliente','$nombCliente','$factura','$monto_recibido','$moneda','$correlativo','$tipo_doc','$monto_recibido',0,'$saldo','$monto_acreditado','$observacion','$usuario','$codsucu','$codesta','$tipo_pago')";
                        $stmt = mssql_query($query);                           ;
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
                            '$nombVendedor'
                            ,'$moneda'
                            ,'$monto_acreditado'
                            ,'$monto_acreditado'
                            ,0
                            ,getdate()
                            ,'$correlativo'
                            ,'$usuario'
                            ,'$codsucu'
                            ,'$codesta')";
                        mssql_query($queryVueltosInsert);
                        
                    }else{
                        $query= "INSERT INTO SAREC_EFECTIVO (fecha,codVendedor,nombVendedor,codCliente,nombCliente,factura,monto,moneda,correlativo,tipo_doc,debe,haber,saldo,monto_acreditado,observacion,usuario,codsucu,codesta,tipo_pago)
                        VALUES (getdate(),'$codVendedor','$nombVendedor','$cliente','$nombCliente','$factura','$monto_recibido','$moneda','$correlativo','$tipo_doc','$monto_recibido',0,'$saldo',0,'$observacion','$usuario','$codsucu','$codesta','$tipo_pago')";
                        $stmt = mssql_query($query);
                    }              
                }
                $updateCorrelativo = "UPDATE SACORRELSIS SET ValueInt = ValueInt + 1 WHERE FieldName = '$FieldName'";
                mssql_query($updateCorrelativo); 
                enviarDatos($factura,$correlativo,$FieldName,$codsucu); 
            }    
            
            if($tipo_doc == 'E'){
                if($monto_recibido == 'error2'){
                    echo json_encode(array("error2" => "No existe billetes de esta denominacion"));
                }else{
                    if($otros != NULL){
                        $query= "INSERT INTO SAREC_EFECTIVO (fecha,codVendedor,nombVendedor,codCliente,nombCliente,otros,factura,monto,moneda,correlativo,tipo_doc,debe,haber,saldo,observacion,usuario,codsucu,codesta,tipo_pago)
                        VALUES (getdate(),'$codVendedor','$nombVendedor','$cliente','$nombCliente','$otros','$factura','$monto_recibido','$moneda','$correlativo','$tipo_doc',0,'$monto_recibido','$saldo','$observacion','$usuario','$codsucu','$codesta','$tipo_pago')";
                        $stmt = mssql_query($query);
                    }else{
                        $query= "INSERT INTO SAREC_EFECTIVO (fecha,codVendedor,nombVendedor,codCliente,nombCliente,factura,monto,moneda,correlativo,tipo_doc,debe,haber,saldo,observacion,usuario,codsucu,codesta,tipo_pago)
                        VALUES (getdate(),'$codVendedor','$nombVendedor','$cliente','$nombCliente','$factura','$monto_recibido','$moneda','$correlativo','$tipo_doc',0,'$monto_recibido','$saldo','$observacion','$usuario','$codsucu','$coddesta','$tipo_pago')";
                        $stmt = mssql_query($query);
                    }
                    $updateCorrelativo = "UPDATE SACORRELSIS SET ValueInt = ValueInt + 1 WHERE FieldName = '$FieldName'";
                    mssql_query($updateCorrelativo); 
                    enviarDatos($factura,$correlativo,$FieldName,$codsucu); 
                }
            }      
        }
    }
    
}

/** ENVIA LA DATA A LA VISTA RECEPCION_EFECTIVO_CREAR */
function  enviarDatos($factura,$correlativo,$FieldName,$codsucu){
    $data = array();
    //convert(varchar,fecha,105) as fecha
    $query2 = "SELECT correlativo,convert(varchar,fecha,105) as fecha,codVendedor,codCliente,otros,
    factura,CASE tipo_doc WHEN 'I' THEN 'INGRESO' WHEN 'E' THEN 'EGRESO' END AS tipo_doc,
    CASE moneda WHEN 'BS' THEN 'Bolivares' WHEN 'DL' THEN 'Dolares' WHEN 'EU' THEN 'Euros' END as moneda,
    debe,haber,saldo,observacion FROM SAREC_EFECTIVO 
    WHERE factura = '$factura' AND correlativo = '$correlativo' AND codsucu = '$codsucu'AND tipo_doc <> 'A'
    ORDER BY fecha asc";
    $stmt = mssql_query($query2);
    if(mssql_num_rows($stmt)!=0){
        while($row = mssql_fetch_array($stmt)){
            $data[] = $row;
        }
    }
    $queryCorrel = "SELECT MAX(ValueInt) ValueInt FROM SACORRELSIS WHERE FieldName = '$FieldName'";
    $stmtCorrel = mssql_query($queryCorrel);
    if(mssql_num_rows($stmtCorrel)!=0){
        while($row = mssql_fetch_array($stmtCorrel)){
            $ncorrelativo = $row['ValueInt'];
        }
    }
    echo json_encode(array("data"=>$data,"correlativo"=>$ncorrelativo));
}
?>