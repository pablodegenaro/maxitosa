<?php
require("conexion.php");
session_start();
$factura = $_GET['factura'];
$datos = explode(",", $factura);
$numerod = $datos[0];
$tipofac = $datos[1]; 
$correl = $datos[2]; 
$usuario = $datos[3]; 

$del = mssql_query("DELETE FROM appfacturas_detft WHERE numeros = '$numerod' and tipofac ='$tipofac'");

switch ($tipofac) {
	case "F":
	$tipo_d='Factura';
	break;
	case "C":
	$tipo_d='Nota de Entrega';
	break;
	default:
	echo "";
}

if ($del){
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
	$mail->AddBCC("soporte.rsistems@gmail.com");
	$mail->Subject = "Eliminado el Documento $tipo_d # $numerod - del Despacho # $correl FT";
	$body="El usuario $usuario elimino el Documento $tipo_d # $numerod , correspondiente al despacho $correl . FT";
	$mail->Body=$body;
	$mail->Send();

	echo "<script language=Javascript> location.href=\"principal1.php?page=despacho_visualft&mod=1&correl2=$correl&usuario=$usuario\";</script>";
}else{
	echo "<script language=Javascript> alert('Error al Eliminar el Documento del Despacho');</script>";
	echo "<script language=Javascript> location.href=\"principal1.php?page="+ str_replace(".php", "", $_SESSION['dashboard'])
	+"&mod=1\";</script>";
}
?>