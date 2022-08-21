<?php

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

/**
 * 
 */
function send_mail( string $toName, string $subject
                  , string $toMail, string $message
                  , bool $html=false): string {
    /* Server settings from mailtrap.io
    See also https://packagist.org/packages/phpmailer/phpmailer */

    $ret = "ok";

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        // Server settings
        // $mail->SMTPDebug  = SMTP::DEBUG_SERVER;           //Enable verbose debug output
        $mail->isSMTP();                                  //Send using SMTP
        $mail->Host       = 'smtp.mailtrap.io';           //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                         //Enable SMTP authentication
        $mail->Username   = '';             //SMTP username
        $mail->Password   = '';             //SMTP password
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  //Enable implicit TLS encryption
        $mail->Port       = 2525;                         //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('from@example.com', 'Mailer');
        $mail->addAddress($toMail, $toName);  //Add a recipient, name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');       //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');  //Optional name

        //Content
        $mail->isHTML($html);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
    } catch (Exception $e) {
        $ret = $mail->ErrorInfo;
    }

    return $ret;
}

/*
Para usar realmente con Gmail:
    - En Gestionar Cuenta ir a Seguridad y generar
    contraseña para aplicaciones (elegir otra y
    ponerle un nombre significativo).

    - Codigo:
    
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'user@gmail.com';
    $mail->Password   = 'passGeneradaPaso1';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom('from@example.com', 'Mailer');
    $mail->addAddress($toMail, $toName);

    $mail->isHTML($html);
    $mail->Subject    = $subject;
    $mail->Body       = $message;

    $mail->send();
*/
?>