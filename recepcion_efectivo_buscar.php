<?php 

require("conexion.php");
session_start();
$params = json_decode(file_get_contents('php://input'));
$codsucu = $_SESSION['codsucu1'];
$codesta = $_SESSION['codesta1'];
$moneda = $_SESSION["moneda"];
$factura = $_POST["factura"];
$data = array();
$dataDenom = array();
$dataRevAnu = array();

if($_SESSION['codsucu1'] == '00000'){
    $FieldName = 'LenCajaP';
}
if($_SESSION['codsucu1'] == '00001'){
    $FieldName = 'LenCajaM';
}
if($_SESSION['codsucu1'] == '00002'){
    $FieldName = 'LenCajaC';
}

if($moneda == "Bolivares"){
    $moneda = "BS";
}

if($moneda == "Dolares"){
    $moneda = "DL";
}

if($moneda == "Euros"){
    $moneda = "EU";
}
/** **************************************************************************** */
/** *******************MUESTRA LA INFORMACION POR MONEDA************************ */
/** **************************************************************************** */

if($params->factura){
    $query = "SELECT convert(varchar,fecha,103) as fecha,codVendedor,nombVendedor,codCliente,otros,nombCliente,
    CASE tipo_doc WHEN 'I' THEN 'INGRESO' WHEN 'E' THEN 'EGRESO' END AS tipo_doc,factura
    ,correlativo,CASE moneda WHEN 'BS' THEN 'Bolivares' WHEN 'DL' THEN 'Dolares' WHEN 'EU' THEN 'Euros' END as moneda,debe,haber,
    saldo,observacion,monto
    FROM SAREC_EFECTIVO WHERE factura = '$params->factura' AND tipo_doc <> 'A' ORDER BY moneda ASC";
    $stmt = mssql_query($query);
    if(mssql_num_rows($stmt)!=0){
        while($row = mssql_fetch_array($stmt)){
            $data[] = $row;
        }
    }
    $moneda = $data[0]['moneda'];
    if($moneda == 'Bolivares'){
        $moneda = 'BS';
    }
    if($moneda == 'Dolares'){
        $moneda = 'DL';
    }
    if($moneda == 'Euros'){
        $moneda = 'EU';
    }
    $queryRevAnu = "SELECT convert(varchar,fecha,103) as fecha,codVendedor,codCliente,otros,
    CASE tipo_doc WHEN 'R' THEN 'REVERSO' WHEN 'A' THEN 'ANULADO' END AS tipo_doc,factura,
    CASE moneda WHEN 'BS' THEN 'Bolivares' WHEN 'DL' THEN 'Dolares' WHEN 'EU' THEN 'Euros' END as moneda
    ,monto,correlativo,debe,haber,
    saldo,observacion
    FROM SAREC_EFECTIVO WHERE CAST(fecha as DATE) = '$fecha' AND moneda = '$moneda' AND tipo_doc = 'R' OR tipo_doc = 'A' ORDER BY moneda ASC";
    $stmt = mssql_query($queryRevAnu); 
    if(mssql_num_rows($stmt)!=0){
        while($row = mssql_fetch_array($stmt)){
            $dataRevAnu[] = $row;
        }
    }

    $querySearch = "SELECT denom_1,denom_2,denom_3,denom_4,denom_5,denom_6,denom_7
    FROM SAREC_DENOM WHERE moneda ='$moneda' AND factura = '$params->factura'";
    $stmtBS = mssql_query($querySearch);
    if(mssql_num_rows($stmtBS)!=0){
        while($row = mssql_fetch_array($stmtBS)){
            $dataDenom[] = $row;
        }
    }else{
        $dataDenom[0]["denom_1"] = 0;
        $dataDenom[0]["denom_2"] = 0;
        $dataDenom[0]["denom_3"] = 0;
        $dataDenom[0]["denom_4"] = 0;
        $dataDenom[0]["denom_5"] = 0;
        $dataDenom[0]["denom_6"] = 0;
        $dataDenom[0]["denom_7"] = 0;
    }

    echo json_encode(array("data"=>$data,"denom"=>$dataDenom));
}

if($params->fecha){
    if($_SESSION['codsucu1'] == '00000'){
        $FieldName = 'LenCajaP';
    }
    if($_SESSION['codsucu1'] == '00001'){
        $FieldName = 'LenCajaM';
    }
    if($_SESSION['codsucu1'] == '00002'){
        $FieldName = 'LenCajaC';
    }
    $queryCorrel = "SELECT MAX(ValueInt) ValueInt FROM SACORRELSIS WHERE FieldName = '$FieldName'";
    $stmtCorrel = mssql_query($queryCorrel);
    if(mssql_num_rows($stmtCorrel)!=0){
        while($row = mssql_fetch_array($stmtCorrel)){
            $correlativo = $row['ValueInt'];
        }
    }

    $querySearch = "SELECT 
    CASE moneda WHEN 'BS' THEN 'Bolivares' WHEN 'DL' THEN 'Dolares' WHEN 'EU' THEN 'Euros' END as moneda,
    total_100 = (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda ='$params->moneda' AND debe > haber) - (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda = '$params->moneda' AND haber > debe) ,
    total_50 = (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda ='$params->moneda' AND debe > haber) - (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda ='$params->moneda' AND haber > debe),
    total_20 = (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda ='$params->moneda' AND debe > haber) - (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda ='$params->moneda' AND haber > debe),
    total_10 = (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda ='$params->moneda' AND debe > haber) - (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda ='$params->moneda' AND haber > debe),
    total_5 = (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda ='$params->moneda' AND debe > haber) - (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda ='$params->moneda' AND haber > debe),
    total_2 = (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda ='$params->moneda' AND debe > haber) - (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda = '$params->moneda' AND haber > debe),
    total_1 = (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda ='$params->moneda' AND debe > haber) - (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda ='$params->moneda' AND haber > debe)
    FROM SAREC_DENOM WHERE moneda ='$params->moneda' group by moneda";
    $stmtBS = mssql_query($querySearch);
    if(mssql_num_rows($stmtBS)!=0){
        while($row = mssql_fetch_array($stmtBS)){
            $dataDenom[] = $row;
        }
    }else{
        if($params->moneda == 'BS'){
            $dataDenom  = array("moneda"=>'Bolivares',"total_100"=>0,"total_50"=>0,"total_20"=>0,"total_10"=>0,"total_5"=>0,"total_2"=>0,"total_1"=>0);
        }
        if($params->moneda == 'DL'){
            $dataDenom  = array("moneda"=>'Dolares',"total_100"=>0,"total_50"=>0,"total_20"=>0,"total_10"=>0,"total_5"=>0,"total_2"=>0,"total_1"=>0);
        }
        if($params->moneda == 'EU'){
            $dataDenom  = array("moneda"=>'Euros',"total_100"=>0,"total_50"=>0,"total_20"=>0,"total_10"=>0,"total_5"=>0,"total_2"=>0,"total_1"=>0);
        }

    }
    echo json_encode(array("correlativo" => $correlativo,"denom"=>$dataDenom));
}

if($params->codVendedor){ 
    $queryCorrel = "SELECT MAX(ValueInt) ValueInt FROM SACORRELSIS WHERE FieldName = '$FieldName'";
    $stmtCorrel = mssql_query($queryCorrel);
    if(mssql_num_rows($stmtCorrel)!=0){
        while($row = mssql_fetch_array($stmtCorrel)){
            $correlativo = $row['ValueInt'];
        }
    }
    if($params->moneda == "Bolivares"){
        $params->moneda = "BS";
    }
    
    if($params->moneda == "Dolares"){
        $params->moneda = "DL";
    }
    
    if($params->moneda == "Euros"){
        $params->moneda = "EU";
    }
    $query = "SELECT CONCAT(codVendedor,' ',Descrip) AS nombreVendedor,codVendedor,Descrip,
    CASE moneda WHEN 'BS' THEN 'Bolivares' WHEN 'DL' THEN 'Dolares' WHEN 'EU' THEN 'Euros' END AS moneda,
    saldo = sum(debe) - sum(haber)
    FROM SAREC_VUELTOS  WHERE codVendedor = '$params->codVendedor' AND moneda = '$params->moneda' AND codsucu = '$codsucu'
    GROUP BY codVendedor,Descrip,moneda"; 
    $stmt = mssql_query($query);
    if(mssql_num_rows($stmt)!=0){
        while($row = mssql_fetch_array($stmt)){
            $data[] = $row;
        }
    }
    echo json_encode(array("data"=>$data,"correlativo" => $correlativo));
}


?>