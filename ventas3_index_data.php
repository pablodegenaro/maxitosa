<?php 
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
require ("conexion.php");
require ("funciones.php");
require ("Functions.php");
require_once ("permisos/Mssql.php");

switch ($_GET["op"]) {

    case 'buscar_doc':
    $sucursal = $_SESSION["codsucu"];
    $numerod = $_POST['numerod'];
    $tipo = $_POST['tipo'];
    
    $query = mssql_query("SELECT NumeroD, NroUnico, Descrip FROM SAFACT WHERE NumeroD='$numerod' AND TipoFac='$tipo' AND CodSucu='$codsucu'");

    if (mssql_num_rows($query)>0) {
        $descrip = mssql_result($query, 0, 'Descrip');
        $data = array (
            "numerod"  => mssql_result($query, 0, 'NumeroD'),
            "nrounico"  => mssql_result($query, 0, 'NroUnico'),
            "descrip" => utf8_encode($descrip),
        );
    } else {
        $data = array (
            "numerod"  => "",
            "nrounico"  => "",
            "descrip" => "",
        );
    }
    
    echo json_encode($data);
    break;
    case "listar_docs":
    $search = $_POST["search"];
    $tipofac = $_POST["tipo"];
    $sucursal = $_SESSION["codsucu"];
    
    if ($search!=='') {
        $datos = Mssql::fetch_assoc(
            mssql_query("SELECT NroUnico, NumeroD, Descrip, TipoFac, MtoTotal, FechaE, ISNULL(MtoTotal/FactorP, 0) Mtotald 
                FROM SAFACT WHERE TipoFac='$tipofac' AND Notas9='APP' AND CodSucu='$sucursal' AND (NumeroD LIKE '%$search%' OR Descrip LIKE '%$search%')")
        );
    } else {
        $datos = Mssql::fetch_assoc(
            mssql_query("SELECT NroUnico, NumeroD, Descrip, TipoFac, MtoTotal, FechaE, ISNULL(MtoTotal/FactorP, 0) Mtotald 
                FROM SAFACT WHERE TipoFac='$tipofac' AND Notas9='APP' AND CodSucu='$sucursal'")
        );
    }

        //declaramos el array
    $data = array();
    foreach ($datos as $key => $row) {
        $sub_array = array();

        $sub_array[] = '<div class="col text-center">
        <button type="button" onClick="seleccionarDoc(\'' . $row['NumeroD'] . '\');"  
        id="' . $row['NumeroD'] . '" 
        class="btn btn-outline-saint btn-xs">
        Seleccionar
        </button>
        </div>';
        $sub_array[] = $row["NumeroD"];
        $sub_array[] = utf8_encode($row["Descrip"]);
        $sub_array[] = date('d/m/Y', strtotime($row["FechaE"]));
        $sub_array[] = number_format($row["MtoTotal"], 2, ',', '.');
        $sub_array[] = number_format($row["Mtotald"], 2, ',', '.');

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

    case "listar_docs_borrar":
    $search = $_POST["search"];
    $tipofac = $_POST["tipo"];
    $sucursal = $_SESSION["codsucu"];
    
    if ($search!=='') {
        $datos = Mssql::fetch_assoc(
            mssql_query("SELECT NroUnico, NumeroD, Descrip, TipoFac, MtoTotal, FechaE, ISNULL(MtoTotal/FactorP, 0) Mtotald 
                FROM SAFACT WHERE TipoFac='$tipofac' AND Notas9='APP' AND CodSucu='$sucursal' AND (NumeroD LIKE '%$search%' OR Descrip LIKE '%$search%')")
        );
    } else {
        $datos = Mssql::fetch_assoc(
            mssql_query("SELECT NroUnico, NumeroD, Descrip, TipoFac, MtoTotal, FechaE, ISNULL(MtoTotal/FactorP, 0) Mtotald 
                FROM SAFACT WHERE TipoFac='$tipofac' AND Notas9='APP' AND CodSucu='$sucursal'")
        );
    }

        //declaramos el array
    $data = array();
    foreach ($datos as $key => $row) {
        $sub_array = array();

        $sub_array[] = '<div class="col text-center">
        <button type="button" onClick="seleccionarDocBorrar(\'' . $row['NumeroD'] . '\');"  
        id="' . $row['NumeroD'] . '" 
        class="btn btn-outline-saint btn-xs">
        Seleccionar
        </button>
        </div>';
        $sub_array[] = $row["NumeroD"];
        $sub_array[] = utf8_encode($row["Descrip"]);
        $sub_array[] = date('d/m/Y', strtotime($row["FechaE"]));
        $sub_array[] = number_format($row["MtoTotal"], 2, ',', '.');
        $sub_array[] = number_format($row["Mtotald"], 2, ',', '.');

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

    case "eliminar_doc":
    $numerod  = $_POST["numerod"];
    $tipo  = $_POST["tipo"];
    $nrounico  = $_POST["nrounico"];
    $sucursal = $_SESSION["codsucu"];
    
    $eliminar = mssql_query("EXEC [App_Delete_Items] @NumeroD ='$numerod', @TipoFac ='$tipo', @CodSucu ='$sucursal' ");

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
    
}