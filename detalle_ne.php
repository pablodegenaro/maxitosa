<?php
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
ini_set('memory_limit', '512M');
require ("conexion.php");
require_once ("Functions.php");
require_once ("permisos/Mssql.php");

$numerod = $_POST["documento_id"];
$datos = Mssql::fetch_assoc(
    mssql_query("SELECT NumeroD, CodItem, Descrip1, CodUbic, Cantidad, Precio/factorp as preciod, (TotalItem+MtoTax) / factorp AS Total 
                        FROM saitemfac WHERE TipoFac IN ('C','D') AND numerod = '$numerod'")
);

//declaramos el array
$data = array();
foreach ($datos as $key => $row) {
    $sub_array = array();

    $sub_array[] = $row["CodItem"];
    $sub_array[] = $row["Descrip1"];
    $sub_array[] = $row["CodUbic"];
    $sub_array[] = Functions::rdecimal($row["Cantidad"], 0);
    $sub_array[] = Functions::rdecimal($row["preciod"],2);
    $sub_array[] = Functions::rdecimal($row["Total"],2);

    $data[] = $sub_array;
}

$results = array(
    "sEcho" => 1, //Información para el datatables
    "iTotalRecords" => count($data), //enviamos el total registros al datatable
    "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
    "aaData" => $data
);

echo json_encode($results);