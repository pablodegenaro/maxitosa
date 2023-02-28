<?php
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
ini_set('memory_limit', '512M');
require ("conexion.php");
require_once ("funciones.php");

switch ($_GET["op"]) {

    case "instancias_hijo":
    $instaciasp = '()';
    $instap = $_POST['instap'];
    if (count($instap) > 0) {
        $aux = "";
            //se contruye un string para listar los seleccionados
            //en caso que no haya ninguno, sera vacio
        foreach ($instap as $num)
            $aux .= "$num,";

            //armamos una lista, si no existe ninguno seleccionado no se considera para realizar la consulta
        $instaciasp = "(" . substr($aux, 0, strlen($aux)-1) . ")";
    }

    $insta = mssql_query("SELECT CODINST, DESCRIP, INSPADRE FROM VW_ADM_INSTANCIAS WHERE INSPADRE IN $instaciasp");

    $output = array();
    for ($i=0; $i<mssql_num_rows($insta); $i++) {
        $output[] = array(
            'codinst' => mssql_result($insta,$i,'CODINST'),
            'descrip' => strtoupper(utf8_encode(mssql_result($insta,$i,'DESCRIP')))
        );
    }

    echo json_encode($output);
    break;
}