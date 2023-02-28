<?php
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
require ("conexion.php");
require ("funciones.php");
require_once ("permisos/Mssql.php");

$file = "permisos_triunfo_".date('d_m_Y_h_i').".txt";
$fh = fopen($file, 'w');

$menu = mssql_query("SELECT id, nombre, menu_orden, menu_padre, icono, status FROM Menu");
for($i=0; $i < mssql_num_rows($menu); $i++) {
    $output .= str_pad("01", 2);
    $output .= str_pad(mssql_result($menu, $i, "id"), 10, 0, STR_PAD_LEFT);
    $output .= str_pad(mssql_result($menu, $i, "menu_orden"), 3, 0, STR_PAD_LEFT);
    $output .= str_pad(mssql_result($menu, $i, "status"), 1);
    $output .= str_pad(mssql_result($menu, $i, "menu_padre"), 10);
    $output .= str_pad(base64_encode(mssql_result($menu, $i, "icono")), 50);
    $output .= str_pad(base64_encode(mssql_result($menu, $i, "nombre")), 50);
    $output .= "\n";
}

$modulo = mssql_query("SELECT id, nombre, icono, ruta, modulo_orden, menu_id, status FROM Modulos");
for($i=0; $i < mssql_num_rows($modulo); $i++) {
    $output .= str_pad("02", 2);
    $output .= str_pad(mssql_result($modulo, $i, "id"), 10, 0, STR_PAD_LEFT);
    $output .= str_pad(mssql_result($modulo, $i, "modulo_orden"), 3, 0, STR_PAD_LEFT);
    $output .= str_pad(mssql_result($modulo, $i, "menu_id"), 10, 0, STR_PAD_LEFT);
    $output .= str_pad(mssql_result($modulo, $i, "status"), 1);
    $output .= str_pad(base64_encode(mssql_result($modulo, $i, "ruta")), 80);
    $output .= str_pad(base64_encode(mssql_result($modulo, $i, "icono")), 80);
    $output .= str_pad(base64_encode(mssql_result($modulo, $i, "nombre")), 80);
    $output .= "\n";
}

$permisos = mssql_query("SELECT id, id_usuario, id_modulo FROM Permisos");
for($i=0; $i < mssql_num_rows($permisos); $i++) {
    $output .= str_pad("03", 2);
    $output .= str_pad(mssql_result($permisos, $i, "id"), 10, 0, STR_PAD_LEFT);
    $output .= str_pad(mssql_result($permisos, $i, "id_modulo"), 10, 0, STR_PAD_LEFT);
    $output .= str_pad(base64_encode(mssql_result($permisos, $i, "id_usuario")), 50);
    $output .= "\n";
}

$rol = mssql_query("SELECT id, descripcion FROM Roles_app");
for($i=0; $i < mssql_num_rows($rol); $i++) {
    $output .= str_pad("04", 2);
    $output .= str_pad(mssql_result($rol, $i, "id"), 10, 0, STR_PAD_LEFT);
    $output .= str_pad(base64_encode(mssql_result($rol, $i, "descripcion")), 50);
    $output .= "\n";
}

$rol_mod = mssql_query("SELECT id, id_modulo, id_rol FROM Roles_Modulos");
for($i=0; $i < mssql_num_rows($rol_mod); $i++) {
    $output .= str_pad("05", 2);
    $output .= str_pad(mssql_result($rol_mod, $i, "id"), 10, 0, STR_PAD_LEFT);
    $output .= str_pad(mssql_result($rol_mod, $i, "id_modulo"), 10, 0, STR_PAD_LEFT);
    $output .= str_pad(mssql_result($rol_mod, $i, "id_rol"), 10, 0, STR_PAD_LEFT);
    $output .= "\n";
}

$ssurs = mssql_query("SELECT CodUsua, Descrip, rol_id, Access FROM SSUSRS");
for($i=0; $i < mssql_num_rows($ssurs); $i++) {

    $rol_id = mssql_result($ssurs, $i, "rol_id");
    $rol_id = (is_null($rol_id) || empty($rol_id)) ? 0 : $rol_id;

    $output .= str_pad("06", 2);
    $output .= str_pad($rol_id, 10, 0, STR_PAD_LEFT);
    $output .= str_pad(mssql_result($ssurs, $i, "Access"), 1);
    $output .= str_pad(base64_encode(mssql_result($ssurs, $i, "CodUsua")), 50);
    $output .= "\n";
}


fwrite($fh, $output);
fclose($fh);

$enlace = $file;
header ("Content-Disposition: attachment; filename=".$enlace);
header ("Content-Type: application/octet-stream");
header ("Content-Length: ".filesize($enlace));
readfile($enlace);
unlink($file);
?>