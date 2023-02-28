<?php 
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
require ("conexion.php");
require ("funciones.php");
require ("Functions.php");
require_once ("permisos/Mssql.php");

switch ($_GET["op"]) {

    case 'vendedor_por_sucursal':
    $sucursal = $_POST["sucursal"];
    $query = mssql_query("SELECT CodVend, Descrip FROM SAVEND WHERE SUBSTRING(CodVend, 1, 1) = '$sucursal' ORDER BY CodVend ASC");

    $output = array();
    for ($i=0; $i<mssql_num_rows($query); $i++) {
        $output[] = array(
            'CodVend' => mssql_result($query,$i,'CodVend'),
            'Descrip' => strtoupper(utf8_encode(mssql_result($query,$i,'Descrip')))
        );
    }
    
    echo json_encode($output);
    break;
}