<?php
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
require ("conexion.php");
require_once("permisos/Modulos.php");
require_once("permisos/Menu.php");
require_once ("permisos/Roles.php");
require_once ("permisos/Mssql.php");
require_once ("Functions.php");


switch ($_GET["op"]) {

    case "listar_dashboard":
    $datos = Roles::todos();

        //declaramos el array
    $data = array();

    foreach ($datos as $key => $row) {
        $sub_array = array();

        $sub_array[] = $key+1;
        $sub_array[] = $row["descripcion"];
        $sub_array[] = '<div align="text-center">
        <div id="dashboard'.$key.'_div" class="input-group">
        <select id="dashboard'.$key.'" name="dashboard'.$key.'" class="form-control custom-select" onchange="guardarMenuSeleccionado(\''. $row["id"] .'\',\''. $key .'\',\'dashboard\')">
        '.Functions::selectListDashboard($row["dashboard"]).'
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
    $id = $_POST["id"];
    $value = $_POST["tipo_value"];

    $guardar = Roles::updateDashboardInModule($id, $value);

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

