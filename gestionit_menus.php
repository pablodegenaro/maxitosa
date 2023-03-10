<?php
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
require ("conexion.php");
require_once("permisos/Modulos.php");
require_once("permisos/Menu.php");
require_once ("permisos/Mssql.php");
require_once ("Functions.php");


switch ($_GET["op"]) {

    case "listar_menu":
    $datos = Menu::todosWithProyect();

        //declaramos el array
    $data = array();

    foreach ($datos as $key => $row) {
        $sub_array = array();

            //ESTADO
        $est = '';
        $atrib = "btn btn-success btn-sm estado";
        switch ($row["status"]) {
            case 0:
            $est = 'INACTIVO';
            $atrib = "btn btn-warning btn-sm estado";
            break;
            case 1:
            $est = 'ACTIVO';
            break;
        }

        $sub_array[] = $row["nombre"];
        $sub_array[] = '<i class="'.$row["icono"].'"></i> ' . $row["icono"];
        $sub_array[] = '<div align="text-center">
        <div id="menu_padre'.$key.'_div" class="input-group">
        <select id="menu_padre'.$key.'" name="menu_padre'.$key.'" class="form-control custom-select" onchange="guardarMenuSeleccionado(\''. $row["id"] .'\',\''. $key .'\',\'menu_padre\')">
        '.Functions::selectListMenus($row["menu_padre"], true, $row['id']).'
        </select>
        </div>
        </div>';
        $sub_array[] = $row["proyecto"];
        $sub_array[] = $row["menu_orden"];
        $sub_array[] = '<div class="col text-center">
        <button type="button" onClick="cambiarEstado_menu(\'' . $row["id"] . '\',\'' . $row["status"] . '\');" name="estado" id="' . $row["id"] . '" class="' . $atrib . '">' . $est . '</button>' . " " . '
        <button type="button" onClick="mostrar_menu(\'' . $row["id"] . '\');"  id="' . $row["id"] . '" class="btn btn-outline-saint btn-sm update">Editar</button>' . " " . '
        <button type="button" onClick="eliminar_menu(\'' . $row["id"] . '\',\''. $row["nombre"] . '\');"  id="' . $row["id"] . '" class="btn btn-saint btn-sm eliminar">Eliminar</button>
        </div>';

        $data[] = $sub_array;
    }

    $results = array(
            "sEcho" => 1, //Informaci??n para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data
        );

    echo json_encode($results);
    break;

    case "mostrar_menu":
    $output=array();
    $id_menu = $_POST["id_menu"];
    $output['lista_menus'] = Menu::todos();

    if($id_menu != -1){
            //el parametro id_usuario se envia por AJAX cuando se edita el usuario
        $datos = Menu::getById($id_menu);

        foreach ($datos as $row) {
            $output["id"]         = $row["id"];
            $output["nombre"]     = $row["nombre"];
            $output["menu_orden"] = $row["menu_orden"];
            $output["menu_padre"] = $row["menu_padre"];
            $output["menu_hijo"]  = $row["menu_hijo"];
            $output["icono"]      = $row["icono"];
            $output["codemenu"]   = $row["codemenu"];
            $output["status"]     = $row["status"];
        }
    }

    echo json_encode($output);
    break;

    case "guardaryeditar_menu":
    $menu = false;

    $id_menu = $_POST['id_menu'];
    $nombre  = ucwords($_POST['nombre']);
    $menu_orden = $_POST['orden'];
    $menu_padre = $_POST['menu_padre'];
    $codemenu = $_POST['menu_proyecto'];
    $icono   = !empty($_POST['icono']) ? $_POST['icono'] : 'far fa-circle';
    $estado  = $_POST['estado'];

    if (empty($id_menu)) {
        $menu = mssql_query("INSERT INTO Menu (nombre, menu_orden, menu_padre, icono, codemenu, status) VALUES('$nombre','$menu_orden','$menu_padre','$icono','$codemenu','$estado')");

    } else {
        $menu = mssql_query("UPDATE Menu SET nombre='$nombre', menu_orden='$menu_orden', menu_padre='$menu_padre', icono='$icono', codemenu='$codemenu', status='$estado' WHERE id='$id_menu'");
    }

        //mensaje
    if($menu){
        $output = array(
            "mensaje" => "Guardado con Exito!",
            "icono"   => "success"
        );
    } else {
        $output = array(
            "mensaje" => "Ocurri?? un error al Guardar!",
            "icono"   => "error"
        );
    }

    echo json_encode($output);
    break;

    case "activarydesactivar_menu":
    $id = $_POST["id"];
    $activo  = $_POST["est"];
        //los parametros id_usuario y est vienen por via ajax
    $datos = Menu::getById($id);
        //valida el id del usuario
    if (is_array($datos) == true and count($datos) > 0) {
            //si esta activo(1) lo situamos cero(0), y viceversa
        ($activo == "0") ? $activo = 1 : $activo = 0;
            //edita el estado
        $estado = mssql_query("update Menu set status='$activo' where id='$id'");
            //evalua que se realizara el query
        ($estado) ? $output["mensaje"] = "Actualizacion realizada Exitosamente" : $output["mensaje"] = "Error al Actualizar";
    }

    echo json_encode($output);
    break;

    case "eliminar_menu":
    $eliminar = false;
    $id = $_POST["id"];

    $menu = Menu::getById($id);
    if(is_array($menu) == true and count($menu) > 0) {
        $eliminar = mssql_query("DELETE FROM Menu WHERE id = '$id'");
    }

        //mensaje
    if($eliminar){
        $output = array(
            "mensaje" => "Se elimin?? exitosamente!",
            "icono"   => "success"
        );
    } else {
        $output = array(
            "mensaje" => "Ocurri?? un error al eliminar!",
            "icono"   => "error"
        );
    }

    echo json_encode($output);
    break;

    case 'guardarseleccionado':
    $id = $_POST["id"];
    $menu_id = $_POST["tipo_value"];

    $guardar = Menu::updateMenuFatherInMenu($id, $menu_id);

        //mensaje
    if($guardar){
        $output = array(
            "mensaje" => "Guardado con Exito!",
            "icono"   => "success"
        );
    } else {
        $output = array(
            "mensaje" => "Ocurri?? un error al Guardar!",
            "icono"   => "error"
        );
    }

    echo json_encode($output);
    break;
}

?>
