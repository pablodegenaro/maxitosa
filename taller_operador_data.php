<?php
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
require ("conexion.php");
require_once("permisos/Usuarios.php");
require_once("permisos/Roles.php");
require_once("permisos/PermisosHelpers.php");
require_once ("permisos/Mssql.php");
require_once ("Functions.php");

switch ($_GET["op"])
{
    case "listar":
    $datos = Mssql::fetch_assoc(
        mssql_query("SELECT * from TLOPER where estatus = 1")
    );

        //declaramos el array
    $data = array();
    foreach ($datos as $key => $row) {
        $sub_array = array();

        $check = ($row['estatus']==1) ? 'checked' : '';
        $sub_array[] = '<div align="text-center">
        <div class="custom-control custom-switch custom-switch-off-light custom-switch-on-saint">
        <input id="estatus_'.$key.'" onchange="cambiarEstado(\'' . $row["usuario"] . '\',\'' . $key . '\')" type="checkbox" class="custom-control-input" '.$check.'>
        <label for="estatus_'.$key.'" class="custom-control-label"></label>
        </div>
        </div>';
        $sub_array[] = $row["usuario"];
        $sub_array[] = $row["cedula"];
        $sub_array[] = $row["descripcion"];
        $sub_array[] = '<div class="col text-center">
        <button type="button" onClick="mostrar(\'' . $row["id"] . '\');"  id="' . $row["id"] . '" class="btn btn-info btn-sm update">Editar</button>' . " " . '
        <button type="button" onClick="eliminar(\'' . $row["id"] . '\',\'' . $row["descripcion"] . '\');"  id="' . $row["id"] . '" class="btn btn-danger btn-sm eliminar">Eliminar</button>
        </div>';

        $data[] = $sub_array;
    }

    $results = array(
                "sEcho" => 1, //Información para el datatables
                "iTotalRecords" => count($data), //enviamos el total registros al datatable
                "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
                "aaData" => $data
            );

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
    
    case 'guardarseleccionado':
    $guardar = false;
    $usuario_id = $_POST["id"];
    $rol_id = $_POST["value"];

    $rol_user_old = '';
    $guardar = Usuarios::saveRol($usuario_id, $rol_id);

        // si $usuario == true
        // registrara los permisos del rol al usuario
    if ($guardar) {
            // evaluamos si tiene permisos, de ser verdadero los elimina de la base de datos
        $cantidad_permisos = count( Permisos::getPermisosPorUsuarioID($usuario_id) );
        if ($cantidad_permisos > 0)
            Permisos::borrar_permiso_user($usuario_id);
            // registramos los permisos del rol seleccionado al usuario
        PermisosHelpers::registrarPermisoUsuarioPorRol(array(
            'user_id' => $usuario_id,
            'rol_id'  => $rol_id,
        ));
    }

        //mensaje
    if($guardar){
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

    case 'guardaracceso':
    $usuario_id = $_POST["codusua"];
    $access = $_POST["access"];

    $guardar = mssql_query("UPDATE SSUSRS SET Access='$access' WHERE codusua = '$usuario_id'");

        //mensaje
    if($guardar) {
        if ($access==1) {
            $output = array(
                "mensaje" => "Acceso Agregado!",
                "icono"   => "success"
            );
        } else {
            $output = array(
                "mensaje" => "Acceso Inhabilitado!",
                "icono"   => "success"
            );
        }
    } else {
        $output = array(
            "mensaje" => "Ocurrió un error al Guardar!",
            "icono"   => "error"
        );
    }

    echo json_encode($output);
    break;
}

?>
