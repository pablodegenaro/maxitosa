<?php 
set_time_limit(0);
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
  $numerod = $_POST['numerod'];
  $proveedor = $_POST['proveedor'];
  $usuario=$_SESSION['nombre_p'];

  $elimina = false;
  $mensaje = '';


  $query = mssql_query("SELECT fechat from saacxc where numerod ='$numerod' and codvend='$edv' and tipocxc='41'");
  //$fechaanterior = mssql_result($cobranza,0,"Fechat");

  if (mssql_num_rows($query) > 0) {

    $query1 = mssql_query("
      DECLARE 
      @CodProv varchar (25) = '$numerod',
      @NumeroD varchar (25) = '$proveedor'

      begin
      update A 
      set A.Existen = A.Existen - B.Cantidad
      from SAEXIS A inner join SAITEMCOM B on A.CodProd = B.CodItem and A.CodUbic = B.CodUbic
      where A.CodProd = B.CodItem and A.CodUbic = B.CodUbic
      and CodProv = @CodProv and NumeroD = @NumeroD and tipocomp='H'
      end
      begin
      delete from SACOMP where NumeroD = @NumeroD and CodProv = @CodProv and tipocomp='H'

      delete from SAITEMCOM where NumeroD = @NumeroD and CodProv = @CodProv and tipocomp='H'

      delete from SAACXP where (NumeroD = @NumeroD and CodProv = @CodProv) or (NumeroN = @NumeroD and CodProv = @CodProv) 

      delete from SATAXCOM where NumeroD = @NumeroD and CodProv = @CodProv and tipocomp='H'

      delete from SATAXITC where NumeroD = @NumeroD and CodProv = @CodProv and tipocomp='H'
      end

      begin 
      delete from SAPAGCXP where NroPpal Not in (select NroUnico from SAACXP) 
      end");

    if ($query1) {

      $elimina = true;
    }
  }

  if ($elimina) {
    $_SESSION['icono'] = "fa-check";
    $_SESSION['mensaje'] = "Compra Eliminada con Exito!";
    $_SESSION['bg_mensaje'] = "info";

    /*$query = mssql_query("INSERT INTO auditoria_cambio_fecha_cobranza  (usuario,fechan,fechac, numerod, fechaa) VALUES  ('$usuario', '$fechat', getdate(), '$numerod', '$fechaanterior') ");*/

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
    $mail->AddAddress("");
    $mail->AddCC("");
    $mail->AddBCC("soporte.rsistems@gmail.com");
    $mail->Subject = "Se Edito fecha de la cobranza $numerod";
    $body="El usuario $usuario Edito la fecha de la cobranza $numerod con la nueva fecha $fechat";
    $mail->Body=$body;
    /*   $mail->Send();*/

  } else {
    $_SESSION['icono'] = "fa-exclamation-triangle";
    $_SESSION['mensaje'] = "Error al intentar Eliminar la Compra";
    $_SESSION['bg_mensaje'] = "danger";
  }

  echo "<script language=Javascript> location.href=\"principal1.php?page=borar_compra&mod=1\";</script>";
}

?>