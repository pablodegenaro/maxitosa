    <?php 
    require_once 'PHPMailer/class.phpmailer.php';
    $mail = new phpmailer();
    $mail->SMTPDebug  = 2;
    $mail->PluginDir = "";
    $mail->Mailer = "smtp";
    $mail->Host = "mail.rsistems.tech";
    $mail->Port="587";
    $mail->SMTPAuth = true;
    $mail->IsHTML(true);
    $mail->CharSet="utf-8";
    $mail->Username = "noreply@rsistems.tech";
    $mail->Password = 'Str00ngP4$M0Rd';
    $mail->From = "noreply@rsistems.tech";
    $mail->FromName = "Sistema de Capital Humano";
    $mail->Timeout=15;
    $mail->AddAddress("soporte.rsistems@gmail.com");
    $mail->Subject = "asdasd";
    $body="sadds";
    $mail->Body=$body;
    if ($mail->Send()) { echo 'enviado';} else { echo "no enviado";}