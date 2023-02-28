<?php
require("conexion.php");
session_start();
$id = $_GET['id'];
$del = mssql_query("DELETE FROM appVehiculo WHERE id_vehiculos = '$id'");
if ($del){
	echo "<script language=Javascript> location.href=\"principal.php?page=vehiculos&mod=1\";</script>";
}else{
	echo "<script language=Javascript> alert('Error al Eliminar el Vehiculo');</script>";
	echo "<script language=Javascript> location.href=\"principal.php?page=vehiculos&mod=1\";</script>";
}
?>