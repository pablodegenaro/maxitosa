<?php
require "conexion.php";
$cedula = $_POST['cedula'];
$chofer = $_POST['chofer'];
$id     = $_GET['id'];
if (empty($_POST["cedula"])) {
	echo "<script language=Javascript> location.href=\"principal1.php?page=choferes_crea&mod=1\";</script>";
} else {
if ($id == "") {
////////////////////////////////////////
    $busca = mssql_query("SELECT cedula from appChofer where cedula = '$cedula'");
    if (mssql_num_rows($busca) == 0) {
        $insert = mssql_query("INSERT into appChofer (cedula,descripcion, estatus) values ('$cedula','$chofer', '1') ");
    } else {
        echo "<script>alert('Error, El Chofer Que Usted ha Colocado ya Existe en Sistema');</script>";
        echo "<script language=Javascript> location.href=\"principal1.php?page=choferes_crea&mod=1\";</script>";
    }
    if ($insert) {
        echo "<script language=Javascript> location.href=\"principal1.php?page=choferes&mod=1\";</script>";
    } else {
        echo "<script>alert('Error al Insertar el Chofer');</script>";
        echo "<script language=Javascript> location.href=\"principal1.php?page=choferes_crea&mod=1\";</script>";
    }
////////////////////////////////////////
} else {
    $insert = mssql_query("UPDATE appChofer set cedula = '$cedula', descripcion = '$chofer', estatus='1' where id_chofer = '$id'");
    if ($insert) {
        echo "<script language=Javascript> location.href=\"principal1.php?page=choferes&mod=1\";</script>";
    } else {
        echo "<script>alert('Error al Crear el Chofer');</script>";
        echo "<script language=Javascript> location.href=\"principal1.php?page=choferes_crea&mod=1&id=$id\";</script>";
    }
}
}