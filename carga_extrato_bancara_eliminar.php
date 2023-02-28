<?php
date_default_timezone_set('America/Caracas');
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
ini_set('memory_limit', '512M');
require ("conexion.php");

$elimina = true;
$mensaje = '';
$id_doc = isset($_POST["id_doc"]) ? $_POST["id_doc"] : '';

$cantidad_relacion = mssql_query("SELECT * FROM Docitem_App WHERE idDoc = (SELECT idDoc FROM Doc_App Where id='$id_doc')");
if (mssql_num_rows($cantidad_relacion) > 0) {
    $query1 = mssql_query("DELETE FROM Docitem_App WHERE idDoc = (SELECT idDoc FROM Doc_App Where id='$id_doc')");
    if (!$query1) {
        $elimina = false;
    }
}
$query2 = mssql_query("DELETE FROM Doc_App WHERE id='$id_doc'");
if (!$query2) {
    $elimina = false;
}

if ($elimina) {
    $_SESSION['icono'] = "fa-check";
    $_SESSION['mensaje'] = "Archivo eliminado con éxito!";
    $_SESSION['bg_mensaje'] = "info";
} else {
    $_SESSION['icono'] = "fa-exclamation-triangle";
    $_SESSION['mensaje'] = "Error al eliminar el Archivo";
    $_SESSION['bg_mensaje'] = "danger";
}

header("Location: principal1.php?page=carga_extrato_bancara&mod=1");

?>