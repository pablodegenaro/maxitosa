<?php
require("conexion.php");
session_start();
$correl = $_GET['id'];
$usuario = $_SESSION['login'];
// $factura = $_GET['factura'];
$del = mssql_query("DELETE FROM appfacturasft WHERE correl = '$correl'");
$del1 = mssql_query("DELETE FROM appfacturas_detft WHERE correl = '$correl'");

if ($del & $del1){

  require 'PHPMailer/class.phpmailer.php';
    $mail = new PHPMailer(true);
// Server settings
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = 'html';
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->SMTPSecure= 'ssl';
    $mail->Port = 465;
    $mail->Username = 'no.responder.eltriunfo@gmail.com';
    $mail->Password = 'oweagrbckonxufal';
// Sender &amp; Recipient
    $mail->From = 'no.responder.eltriunfo@gmail.com';
    $mail->FromName = "Sistema de Logistica y Despacho El Triunfo C.A";
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->Timeout=15;
  $mail->AddAddress("soporte.rsistems@gmail.com");
    //$mail->AddCC("");
    //$mail->AddBCC("");
  $mail->Subject = "Eliminado el Despacho # $correl FT";
  $body="El usuario $usuario elimino el Despacho # $correl . FT";
  $mail->Body=$body;
  $mail->Send();

  echo "<script language=Javascript> location.href=\"principal1.php?page=despacho_relacionft&mod=1\";</script>";
}else{
 echo "<script language=Javascript> alert('Error al Eliminar el Despacho');</script>";
 echo "<script language=Javascript> location.href=\"principal1.php?page=despacho_relacionft&mod=1\";</script>";
}
?>