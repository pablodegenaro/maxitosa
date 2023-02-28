<?php
require "conexion.php";
$preciobase = $_POST['pbase'];
$porcentaje = $_POST['porcentaje'];
$aplicacion = $_POST['aplicacion'];
$usuario = $_POST['usuario'];
$id     = $_GET['id'];

$update = mssql_query("UPDATE convenio_configuracion set precio_base ='$preciobase', porcentaje='$porcentaje', tipo_aplicacion ='$aplicacion', coduusu='$usuario', ultimo_cambio= getdate()  where descripcion = '$id'");

echo "<script language=Javascript> location.href=\"principal1.php?page=convenio_configuracion&mod=1\";</script>";
