<?php 
require("conexion.php");
$placa = $_POST['placa'];
$modelo = $_POST['modelo'];
$capacidad = $_POST['capacidadkg'];
$volumen = $_POST['volumencm3'];
$id = $_GET['id'];
if (empty($_POST["placa"])) {
	echo "<script language=Javascript> location.href=\"principal1.php?page=vehiculos_crea&mod=1\";</script>";
} else {
	if ($id == ""){
		$busca = mssql_query("SELECT placa from appVehiculo where placa = '$placa'");
		if (mssql_num_rows($busca) == 0){ 
			$insert = mssql_query("INSERT into appVehiculo (placa,modelo,capacidad,volumen) values ('$placa','$modelo','$capacidad','$volumen')");
		}else{
			echo "<script>alert('Error, El Vehiculo Que Usted ha Colocado ya Existe en Sistema');</script>";
			echo "<script language=Javascript> location.href=\"principal1.php?page=vehiculos_crea&mod=1\";</script>";
		}
		if ($insert){
			echo "<script language=Javascript> location.href=\"principal1.php?page=vehiculos&mod=1\";</script>";
		}else{
			echo "<script>alert('Error al Insertar el Vehiculo');</script>";
			echo "<script language=Javascript> location.href=\"principal1.php?page=vehiculos_crea&mod=1\";</script>";
		}
	}else{
		$insert = mssql_query("UPDATE appVehiculo set placa = '$placa', modelo = '$modelo', capacidad = '$capacidad', volumen = '$volumen' where id_vehiculos = '$id'");
		if ($insert){
			echo "<script language=Javascript> location.href=\"principal1.php?page=vehiculos&mod=1\";</script>";
		}else{
			echo "<script>alert('Error al Crear el Vehiculo');</script>";
			echo "<script language=Javascript> location.href=\"principal1.php?page=vehiculos_crea&mod=1&id=$id\";</script>";
		}
	}
}
?>