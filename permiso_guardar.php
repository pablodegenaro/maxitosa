<?php
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
require ("conexion.php");
require_once("permisos/Permisos.php");
require_once("permisos/PermisosHelpers.php");

$reponse = false;
$output = array();

$state = $_POST['state'];
$tipo = $_POST['tipo'];

$data = array(
    'id' => $_POST['id'],
    'modulo_id' => $_POST['modulo_id'],
);

if ($state=="true") {
    $mensajeError = 'registrar';
    $mensajeSuccess = 'registró';
    switch ($tipo) {
        case 0: // permisos por rol
        $reponse = Permisos::registrar_rolmod($data) and PermisosHelpers::registrarPermisoPorRol($data);
        break;
        case 1: // permisos por usuario
        $reponse = Permisos::registrar_permiso($data);
        break;
    }
} else {
    $mensajeError = 'eliminar';
    $mensajeSuccess = 'eliminó';
    switch ($tipo) {
        case 0: // permisos por rol
        $reponse = Permisos::borrar_rolmod($data) and PermisosHelpers::borrarPermisoPorRol($data);
        break;
        case 1: // permisos por usuario
        $reponse = Permisos::borrar_permiso($data);
        break;
    }
}

if ($reponse) {
    $output["mensaje"] = "Se $mensajeSuccess correctamente";
    $output["icono"] = "success";
} else {
    $output["mensaje"] = "Error al $mensajeError";
    $output["icono"] = "error";
}

echo json_encode($output);
