<?php
require("conexion.php");
session_start();
$id = $_GET['id'];
$del = mssql_query("DELETE FROM Bancos_App WHERE id = '$id'");
if ($del){
    echo "<script language=Javascript> location.href=\"principal.php?page=saldo_bancos&mod=1\";</script>";
}else{
    echo "<script language=Javascript> alert('Error al Eliminar el Banco');</script>";
    echo "<script language=Javascript> location.href=\"principal.php?page=saldo_bancos&mod=1\";</script>";
}
?>