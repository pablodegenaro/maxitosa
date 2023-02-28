<?php
require ("conexion.php");
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
error_reporting(0);
set_time_limit(0);

$marca = $_POST['marca'];
$profit1 = $_POST['profit1'];
$profit2 = $_POST['profit2'];
$profit3 = $_POST['profit3'];

$guarda = true;

for ($i=0; $i<count($marca); $i++) {
    $query = mssql_query("EXEC [Precio_Marca] @P1 ='$profit1[$i]' ,
        @P2 ='$profit2[$i]', 
        @P3 ='$profit3[$i]',
        @Marca ='$marca[$i]'");
    if ($query==false) {
        $guarda = false;
    }
}

if ($guarda) {
    $_SESSION['icono'] = "fa-check";
    $_SESSION['mensaje'] = "Guardado Exitosamente!.";
    $_SESSION['bg_mensaje'] = "success";
} else {
    $_SESSION['icono'] = "fa-exclamation-triangle";
    $_SESSION['mensaje'] = "Error al Guardar!.";
    $_SESSION['bg_mensaje'] = "warning";
}

echo "<script language=Javascript> location.href=\"principal1.php?page=aumento_precios_marca&mod=1\";</script>";