<?php

/*
 * Scriptino per inviare email.
 */

function inviamail($mittente, $nome_mittente, $destinatario, $oggetto, $corpo) {
    require_once dirname(__FILE__) . "/../libs-backend/PHPMailer/class.phpmailer.php";

    $mail = new PHPMailer();
    $mail->CharSet = 'UTF-8';
    $mail->SetLanguage('it');
    $mail->Host = "localhost";
    $mail->From = $mittente;
    $mail->FromName = $nome_mittente;
    $mail->AddAddress($destinatario);
    $mail->WordWrap = 50;
    $mail->IsHTML(true);
    // Set email format to HTML
    $footer = "<br /><hr /> Messaggio inviato da PMS &#169; - Player Management System <br />";
    $mail->Subject = $oggetto;
    $mail->Body = $corpo . $footer;

    if (!$mail->Send()) {
        echo $mail->ErrorInfo;
    }
}

?>
