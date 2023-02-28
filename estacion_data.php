<?php 
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
require ("conexion.php");
require_once ("permisos/Mssql.php");

switch ($_GET["op"]) {

    case 'estaciones_sucursal':
        $sucursal = $_POST['sucursal'];

        $datos =  Mssql::fetch_assoc(
            mssql_query("SELECT CodEsta FROM SAESTA WHERE CodSucu='$sucursal'")
        );

        echo json_encode($datos);
        break;
}