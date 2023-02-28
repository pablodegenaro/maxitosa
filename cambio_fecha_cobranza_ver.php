<?php 
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
  $fechat = $_POST['fechat'];
  $numerod = $_POST['numerod'];
  $edv = $_POST['edv'];
  $usuario=$_SESSION['nombre_p'];

  $elimina = false;
  $mensaje = '';


  $cobranza = mssql_query("SELECT fechat from saacxc where numerod ='$numerod' and codvend='$edv' and tipocxc='41'");
  $fechaanterior = mssql_result($cobranza,0,"Fechat");

  if (mssql_num_rows($cobranza) > 0) {

    $query1 = mssql_query("UPDATE saacxc set fechat = '$fechat' where numerod ='$numerod' and codvend='$edv' and tipocxc='41'");

    if ($query1) {

      $elimina = true;
    }
  }

  if ($elimina) {
    $_SESSION['icono'] = "fa-check";
    $_SESSION['mensaje'] = "Fecha Cambiada con Exito!";
    $_SESSION['bg_mensaje'] = "info";

    $query = mssql_query("INSERT INTO auditoria_cambio_fecha_cobranza  (usuario,fechan,fechac, numerod, fechaa) VALUES  ('$usuario', '$fechat', getdate(), '$numerod', '$fechaanterior') ");

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
    $mail->AddAddress("veronica.gomez@eltriunfo.com.ve");
    $mail->AddCC("daisymar.bonalde@eltriunfo.com.ve");
    $mail->AddBCC("soporte.rsistems@gmail.com");
    $mail->Subject = "Se Edito fecha de la cobranza $numerod";
    $body="El usuario $usuario Edito la fecha de la cobranza $numerod con la nueva fecha $fechat";
    $mail->Body=$body;
    $mail->Send();

  } else {
    $_SESSION['icono'] = "fa-exclamation-triangle";
    $_SESSION['mensaje'] = "Error al Cambiar la Fecha";
    $_SESSION['bg_mensaje'] = "danger";
  }

  echo "<script language=Javascript> location.href=\"principal1.php?page=cambio_fecha_cobranza&mod=1\";</script>";
}

?>