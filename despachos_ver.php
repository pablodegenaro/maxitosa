<?php 
session_start();
session_name('S1sTem@RsIsT3m@#$%$@pP');
if ($_SESSION['login']) {
    $fechad1 = $_POST['fechadespacho'];
    $cedula_chofer = $_POST['chofer'];
    $placa = $_POST['vehiculo'];
    $nota = $_POST['destino'];
    $usuario = $_POST['usuario'];
    $producto = $_POST['check_lista'];

    $fechae = normalize_date($fechad1);
    $fechad = normalize_date($fechad1);

    $correl1 = mssql_query("SELECT max(correl) as correl from appfacturas ");
    if ( mssql_result($correl1, 0, 'correl') >= 1) {
        $correl =  mssql_result($correl1, 0, 'correl') + 1; 
    } else {
        $correl = 1 ; 
    };
    $proce_cabezera = mssql_query("EXEC [Envio_cabezera] @fechae ='$fechae', @usuario ='$usuario', @correl =$correl, @fechad ='$fechad',   @nota ='$nota', @cedula_chofer ='$cedula_chofer', @placa ='$placa' ");

    
    $checked_contador = count($producto);
    foreach($producto as $seleccion) {
        $proce_detalle    = mssql_query("EXEC Envio @NroPhP = '$seleccion' ");
    }; 

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
    $mail->Subject = "Se Creo el Despacho # $correl";
    $body="El usuario $usuario Creo el Despacho # $correl .";
    $mail->Body=$body;
    $mail->Send();


    echo("<script>location.href = 'principal1.php?page=despacho_visual&mod=1&correl2=$correl&usuario=$usuario';</script>");
    
} else {
    header('Location: index.php');
}
?>