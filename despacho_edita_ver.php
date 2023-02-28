<?php 
require("conexion.php");
session_start();
$fechad = $_POST['fechadespacho'];
$destino = $_POST['destino'];
$placa = $_POST['placa'];
$chofer = $_POST['chofer'];
$correl = $_GET['correl'];
$fechai = normalize_date($fechad);

if (empty($_GET["correl"])) {
	echo "<script language=Javascript> location.href=\"principal1.php?page=despacho_crea&mod=1\";</script>";
} else {
	$update = mssql_query("UPDATE appfacturas set fechad='$fechai', nota ='$destino', placa ='$placa', cedula_chofer ='$chofer' where correl = '$correl'");
	if ($update){

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
		$mail->Subject = "Se Edito el Despacho # $correl";
		$body="El usuario $usuario Edito el Despacho # $correl .";
		$mail->Body=$body;
		$mail->Send();
		echo "<script language=Javascript> location.href=\"principal1.php?page=despacho_visual&mod=1&correl2=$correl&usuario=$usuario\";</script>";
	}else{
		echo "<script>alert('Error al Editar el Despacho');</script>";
		echo "<script language=Javascript> location.href=\"principal1.php?page=despacho_visual&mod=1&correl2=$correl&usuario=$usuario\";</script>";
	}
	
}
?>