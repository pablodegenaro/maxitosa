<?php
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
require ("conexion.php");
require_once("permisos/Usuarios.php");
require_once("permisos/Roles.php");
require_once ("permisos/Mssql.php");
require_once ("Functions.php");

switch ($_GET["op"]) {

    case "listar":
    $datos = Roles::todos();
        //declaramos el array
    $data = array();
    foreach ($datos as $row) {

        $sub_array = array();

        $sub_array[] = $row["id"];
        $sub_array[] = $row["descripcion"];

            # el parametro t es el tipo:
            #        0 el tipo es rol (este caso)
            #        1 el tipo es usuario
        $sub_array[] = '<div align="center form-check-inline p-t-30">
        <a href="principal1.php?page=permiso&mod=1&t='. 0 .'&i='. $row["id"] .'">Ver Permisos</a>
        </div>';
        if ($row["id"]=="1") {
            $sub_array[] = 'No Editable';
        } else {
            $sub_array[] = '<div class="col text-center">
            <button type="button" onClick="mostrar(\'' . $row["id"] . '\');"  id="' . $row["id"] . '" class="btn btn-info btn-sm update">Editar</button>' . " " . '
            <button type="button" onClick="eliminar(\'' . $row["id"] . '\',\'' . $row["descripcion"] . '\');"  id="' . $row["id"] . '" class="btn btn-danger btn-sm eliminar">Eliminar</button>
            </div>';
        }

        $data[] = $sub_array;
    }

    $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data);

    echo json_encode($results);
    break;

    case "guardaryeditar":
    $rol_estatus = false;

    $id_rol = $_POST["id_rol"];
    $descripcion = strtoupper($_POST['rol']);

    if (empty($id_rol)) {
        $datos = Roles::getByName($descripcion);

        if (is_array($datos) == true and count($datos) == 0) {
            $rol_estatus = mssql_query("INSERT INTO Roles_app (descripcion) VALUES('$descripcion')");
        }
    } else {
        $rol_estatus = mssql_query("UPDATE Roles_app SET descripcion='$descripcion' WHERE id='$id_rol'");
    }

        //mensaje
    if($rol_estatus){
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
    break;

    case "mostrar":
    $output = array();
    $id_rol = $_POST["id_rol"];

    $datos = Roles::getById($id_rol);

    if (is_array($datos) == true and count($datos) > 0) {
        $output["descripcion"] = $datos[0]["descripcion"];
    }

    echo json_encode($output);
    break;

    case "eliminar":
    $eliminar = false;
    $relacion = true;
    $id_rol = $_POST["id_rol"];

    $datos = Roles::getById($id_rol);
    if(is_array($datos) == true and count($datos) > 0)
    {
            // verifica si el rol esta relacionado con usuarios
        $relacion_usuario = Roles::getRelationRolUser($id_rol);
        if (is_array($relacion_usuario) == true and count($relacion_usuario) == 0) {
            $relacion = true;
            $eliminar = mssql_query("DELETE FROM Roles_app WHERE id = '$id_rol'");
        } else {
            $relacion = false;
        }
    }

        //mensaje
    if($eliminar and $relacion){
        $output = array(
            "mensaje" => "Se eliminó exitosamente!",
            "icono"   => "success"
        );
    }elseif (!$relacion) {
        $output = array(
            "mensaje" => "Existen Usuarios con el rol asignado!",
            "icono"   => "error"
        );
    } else {
        $output = array(
            "mensaje" => "Ocurrió un error al eliminar!",
            "icono"   => "error"
        );
    }

    echo json_encode($output);
    break;
}

?>
