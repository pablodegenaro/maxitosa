<?php
require("conexion.php");
$id = $_GET['id'];
$del = mssql_query("UPDATE appChofer set estatus='0' where id_chofer = '$id'");
if ($del){
	echo "<script language=Javascript> location.href=\"principal1.php?page=choferes&mod=1\";</script>";
}else{
	echo "<script language=Javascript> alert('Error al Eliminar el Chofer');</script>";
	echo "<script language=Javascript> location.href=\"principal1.php?page=choferes&mod=1\";</script>";
}
?>