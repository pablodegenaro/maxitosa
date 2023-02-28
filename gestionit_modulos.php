<?php
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
require ("conexion.php");
require_once("permisos/Modulos.php");
require_once("permisos/Menu.php");
require_once ("permisos/Mssql.php");
require_once ("Functions.php");

switch ($_GET["op"]) {

    case "listar_modulos":
    $datos = Modulos::todos();

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

        $sub_array[] = ''.$row["ruta"];
        $sub_array[] = '<div align="text-center">
        <div id="menu'.$key.'_div" class="input-group">
        <select id="menu'.$key.'" name="menu'.$key.'" class="form-control custom-select" onchange="guardarMenuSeleccionado(\''. $row["id"] .'\',\''. $key .'\',\'modulo\')">
        '.Functions::selectListMenus($row["menu_id"]).'
        </select>
        </div>
        </div>';
        $sub_array[] = $row["nombre"];
        $sub_array[] = '<i class="'.$row["icono"].'"></i> ' . $row["icono"];
        $sub_array[] = $row["modulo_orden"];
        $sub_array[] = '<div class="col text-center">
        <button type="button" onClick="cambiarEstado_modulo(\'' . $row["id"] . '\',\'' . $row["status"] . '\');" name="estado" id="' . $row["id"] . '" class="' . $atrib . '">' . $est . '</button>' . " " . '
        <button type="button" onClick="mostrar_modulo(\'' . $row["id"] . '\');"  id="' . $row["id"] . '" class="btn btn-outline-saint btn-sm update">Editar</button>' . " " . '
        <button type="button" onClick="eliminar_modulo(\'' . $row["id"] . '\',\''. $row["nombre"] . '\');"  id="' . $row["id"] . '" class="btn btn-saint btn-sm eliminar">Eliminar</button>
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

    case "mostrar_modulo":
    $output=array();
    $id_modulo = $_POST["id_modulo"];
    $output['lista_menus'] = Menu::todosWithProyect();

    if($id_modulo != -1){
            //el parametro id_usuario se envia por AJAX cuando se edita el usuario
        $datos = Modulos::getById($id_modulo);

        foreach ($datos as $row) {
            $output["id"] = $row["id"];
            $output["nombre"] = $row["nombre"];
            $output["icono"] = $row["icono"];
            $output["ruta"] = $row["ruta"];
            $output["modulo_orden"] = $row["modulo_orden"];
            $output["menu_id"] = $row["menu_id"];
            $output["status"] = $row["status"];
        }
    }

    echo json_encode($output);
    break;

    case "guardaryeditar_modulo":
    $modulo = false;

    $id_modulo    = $_POST['id_modulo'];
    $orden_modulo = $_POST['orden_modulo'];
    $nombre  = ucwords($_POST['nombre']);
    $icono   = !empty($_POST['icono']) ? $_POST['icono'] : 'far fa-dot-circle';
    $ruta    = $_POST['ruta'];

    $menu_id = $_POST['menu_id'];
    $estado  = $_POST['estado'];

    if (empty($id_modulo)) {

        $datos = Modulos::getByRoute($ruta);

        if (is_array($datos) == true and count($datos) == 0) {
            $modulo = mssql_query("INSERT INTO Modulos (nombre, icono, ruta, modulo_orden, menu_id, status) VALUES('$nombre','$icono','$ruta','$orden_modulo','$menu_id','$estado')");
        }

    } else {
        $modulo = mssql_query("UPDATE Modulos SET nombre='$nombre', icono='$icono', ruta='$ruta', modulo_orden='$orden_modulo', menu_id='$menu_id', status='$estado'  WHERE id='$id_modulo'");
    }

    $output = '';
        //mensaje
    if($modulo){
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

    case "activarydesactivar_modulo":
    $id = $_POST["id"];
    $activo  = $_POST["est"];

    $datos = Modulos::getById($id);
        //valida el id
    if (is_array($datos) == true and count($datos) > 0) {
            //si esta activo(1) lo situamos cero(0), y viceversa
        ($activo == "0") ? $activo = 1 : $activo = 0;
            //edita el estado
        $estado = mssql_query("update Modulos set status='$activo' where id='$id'");
            //evalua que se realizara el query
        ($estado) ? $output["mensaje"] = "Actualizacion realizada Exitosamente" : $output["mensaje"] = "Error al Actualizar";
    }

    echo json_encode($output);
    break;

    case "eliminar_modulo":
    $eliminar = false;
    $id = $_POST["id"];

    $modulo = Modulos::getById($id);
    if(is_array($modulo) == true and count($modulo) > 0) {
        $eliminar = mssql_query("DELETE FROM Modulos WHERE id = '$id'");;
    }

        //mensaje
    if($eliminar){
        $output = array(
            "mensaje" => "Se eliminó exitosamente!",
            "icono"   => "success"
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
    $id = $_POST["id"];
    $menu_id = $_POST["tipo_value"];

    $guardar = Modulos::updateMenuIdInModule($id, $menu_id);

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
}

?>
