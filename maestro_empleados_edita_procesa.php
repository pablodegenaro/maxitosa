<?php
require ("conexion.php");
session_name('S1sTem@RsIsT3m@#$%$@pP');
session_start();
error_reporting(0);
set_time_limit(0);

$codclie = $_POST['codclie'];
$ficha = $_POST['ficha'];
$nomina = $_POST['nomina'];


$query = mssql_query("UPDATE saclie_99 SET ficha='$ficha', nomina='$nomina' WHERE CodClie='$codclie'");

echo "<script language=Javascript> location.href=\"principal1.php?page=maestro_empleados&mod=1\";</script>";

