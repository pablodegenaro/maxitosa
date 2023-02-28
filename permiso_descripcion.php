<?php
require ("conexion.php");
require_once("permisos/Roles.php");
require_once("permisos/Usuarios.php");
require_once ("permisos/Mssql.php");

$output = array();
$id = $_POST['id'];
$tipo = $_POST['tipo'];

# el parametro tipo:
#        0 el tipo es rol
#        1 el tipo es usuario
switch ($tipo) {
    case 0:
    $rol_data = Roles::getById($id);
    $output['descripcion'] = $rol_data[0]['descripcion'];
    break;
    case 1:
    $usuario_data = Usuarios::byCodUsua($id);
    $output['descripcion'] = $usuario_data[0]['descrip'];
    break;
}

echo json_encode($output);
