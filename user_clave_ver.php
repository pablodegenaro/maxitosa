<?php 
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
    date_default_timezone_set("America/Caracas"); 
    $usuario =$_SESSION['login'];
    $clave_actual = $_POST['clave_actual'];
    $clave_nueva = $_POST['clave_nueva'];
    $hoy = date("F j, Y, g:i a"); 


    $cambio_clave = mssql_query("EXEC [SP_CambioClave] @usuario = '$usuario', @pass='$clave_actual', @newpass='$clave_nueva' ");


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
    $mail->Subject = "Cambio Clave  $usuario";
    $body="El usuario $usuario cambio su Clave Actual: $clave_actual por una nueva: $clave_nueva el $hoy ";
    $mail->Body=$body;
    $mail->Send();

    echo "<script>alert('Clave Actualizada con Exito');</script>";
    echo "<script language=Javascript> location.href=\"principal1.php?page=user_clave&mod=1\";</script>";

} else {
    echo "<script>alert('Error al Intentar cambiar la Clave');</script>";
    header('Location: index.php');
}
?>