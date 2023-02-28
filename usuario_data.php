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
    $datos = Usuarios::todos();

        //declaramos el array
    $data = array();

    foreach ($datos as $key => $row) {
        $sub_array = array();

            //nivel del rol asignado
        $rol_data = Roles::getById($row["rol_id"]);

        $nivel = '';
        switch ($row["nivel"]) {
            case 1: $nivel = "Directiva";break;
            case 2: $nivel = "Administracion";break;
            case 3: $nivel = "Compras";break;
            case 4: $nivel = "Ventas";break;
            case 5: $nivel = "Logistica";break;
            case 6: $nivel = "Finanzas";break;
            case 7: $nivel = "Contabilidad";break;
            case 8: $nivel = "IT";break;
            case 9: $nivel = "Supervisor";break;
            case 10: $nivel = "Comercial";break;
            default:$nivel = "usuario sin rol";
        }

        $check = ($row['Access']==1) ? 'checked' : '';
        $sub_array[] = '<div align="text-center">
        <div class="custom-control custom-switch custom-switch-off-light custom-switch-on-saint">
        <input id="access_'.$key.'" onchange="guardarAcceso(\'' . $row["codusua"] . '\',\'' . $key . '\')" type="checkbox" class="custom-control-input" '.$check.'>
        <label for="access_'.$key.'" class="custom-control-label"></label>
        </div>
        </div>';
        $sub_array[] = $row["codusua"]
        . '<br><span class="badge badge-secondary mt-1">' . $row["descrip"] . '</span>';
        $sub_array[] =  $nivel ;

            # el parametro t es el tipo:
            #        0 el tipo es rol
            #        1 el tipo es usuario (este caso)
        $sub_array[] = '<div align="center form-check-inline p-t-30">
        <a href="principal1.php?page=permiso&mod=1&t='. 1 .'&i='. $row["codusua"] .'">Ver Permisos</a>
        </div>';
        $sub_array[] = '<div align="text-center">
        <div id="rol'.$key.'_div" class="input-group">
        <select id="rol'.$key.'" name="rol'.$key.'" class="form-control custom-select text-center" onchange="guardarRolSeleccionado(\''. $row["codusua"] .'\',\''. $key .'\')">
        '.Functions::selectListRoles($rol_data[0]['id']).'
        </select>
        </div>
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
