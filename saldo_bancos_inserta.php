<?php
require("conexion.php");
$descrip = $_POST['descrip'];
$nrocta = $_POST['nrocta'];
$saldo = $_POST['saldo'];
$id = $_GET['id'];

if ($id == ""){
    $insert = mssql_query("INSERT into Bancos_App (Descrip,NroCta,Saldo) values ('$descrip','$nrocta','$saldo')");
    if ($insert){
        echo "<script language=Javascript> location.href=\"principal1.php?page=saldo_bancos&mod=1\";</script>";
    }else{
        echo "<script>alert('Error al Insertar el Banco');</script>";
        echo "<script language=Javascript> location.href=\"principal1.php?page=saldo_bancos_edita&mod=1\";</script>";
    }
}else{
    $insert = mssql_query("UPDATE Bancos_App set Descrip = '$descrip', NroCta = '$nrocta', Saldo = '$saldo' where id = '$id'");
    if ($insert){
        echo "<script language=Javascript> location.href=\"principal1.php?page=saldo_bancos&mod=1\";</script>";
    }else{
        echo "<script>alert('Error al Actualizar el Banco');</script>";
        echo "<script language=Javascript> location.href=\"principal1.php?page=saldo_bancos_edita&mod=1&id=$id\";</script>";
    }
}

?>