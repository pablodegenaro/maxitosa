<?php
require("conexion.php");
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');

$output = array();
$marcas = isset($_POST['marcas']) ? $_POST['marcas'] : array();
$guardar = $eliminar = true;

$kpi_marcas = array();
$query_kpi_marcas = mssql_query("SELECT id, descripcion, fechae FROM Kpi_marcas ORDER BY id ");
for($i=0;$i<mssql_num_rows($query_kpi_marcas);$i++) {
    $kpi_marcas[] = mssql_result($query_kpi_marcas,$i,"descripcion");
}

if(mssql_num_rows($query_kpi_marcas) > 0) {
    $eliminar = mssql_query("TRUNCATE TABLE Kpi_marcas");
}

if (!empty($marcas) and $eliminar) {
    foreach ($marcas as $marca) {
        $fecha = date("Y/m/d h:i:s");
        $guardar = mssql_query("INSERT INTO Kpi_marcas (descripcion, fechae) VALUES ('$marca','$fecha')");
    }
}

//mensaje
if($eliminar && $guardar){
    $output = array(
        "mensaje" => "Guardado con Exito!",
        "icono"   => "success"
    );
} else {
    $output = array(
        "mensaje" => "Ocurrió un error al Guardar!",
        "icono"   => "error"
    );
}

echo json_encode($output);
?>