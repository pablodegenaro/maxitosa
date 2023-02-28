<?php 

require("conexion.php");
session_start();
$params = json_decode(file_get_contents('php://input'));
$codsucu = $_SESSION['codsucu1'];
$moneda = $params->moneda;
if($moneda == 'Bolivares'){
    $moneda = 'BS';
}
if($moneda == 'Dolares'){
    $moneda = 'DL';
}

if($moneda == 'Euros'){
    $moneda = 'EU';
}


$data = array();


$queryTabla = "SELECT 
CASE moneda WHEN 'BS' THEN 'Bolivares' WHEN 'DL' THEN 'Dolares' WHEN 'EU' THEN 'Euros' END as moneda,
total_100 = (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_1),0) as denom_1 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu'),
total_50 = (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_2),0) as denom_2 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu'),
total_20 = (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_3),0) as denom_3 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu'),
total_10 = (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_4),0) as denom_4 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu'),
total_5 = (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_5),0) as denom_5 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu'),
total_2 = (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_6),0) as denom_6 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu'),
total_1 = (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda ='$moneda' AND debe > haber AND codsucu = '$codsucu') - (select isnull(sum(denom_7),0) as denom_7 from SAREC_DENOM where moneda = '$moneda' AND haber > debe AND codsucu = '$codsucu')

FROM SAREC_DENOM WHERE moneda ='$moneda' AND codsucu = '$codsucu' group by moneda";
$stmtTabla = mssql_query($queryTabla); 
if(mssql_num_rows($stmtTabla)!=0){
    while($row = mssql_fetch_array($stmtTabla)){
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
        $data[] = $row;
    }
    echo json_encode(array("data"=>$data));
}
if(mssql_num_rows($stmtTabla)==0){
    echo json_encode(array("error"=>"no posee saldo en esta denominacion"));
}



?>