<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$mail = new PHPMailer(true);

try {
    $type = $_POST['type'];

    if ($type == 1) {
        $name = "N/A";
        $email = $_POST['email'];
        $subject = "N/A";
        $message = "N/A";
    } else {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];
    }

    $htmlTemplate = fopen('plantilla-email.html','r');
    $htmlTemplateString = stream_get_contents($htmlTemplate); 
    
    $htmlTemplateString = str_replace("nombre_label",$name,$htmlTemplateString);
    $htmlTemplateString = str_replace("correo_label",$email,$htmlTemplateString);
    $htmlTemplateString = str_replace("asunto_label",$subject,$htmlTemplateString);
    $htmlTemplateString = str_replace("mensaje_label",$message,$htmlTemplateString);
    $htmlTemplateString = str_replace("á",'&aacute;',str_replace("é",'&eacute;',str_replace("í",'&iacute;',str_replace("ó",'&oacute;',str_replace("ú",'&uacute;',$htmlTemplateString)))));

    //Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'gdrmgdrm12@gmail.com';                     //SMTP username
    $mail->Password   = 'fcvvzzzyywgqiajq';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('gdrmgdrm12@gmail.com', 'BIOSIMA PAGINA');
    $mail->addAddress('gdrmgdrm12@gmail.com', 'CLIENTE');     //Add a recipient
    // $mail->addAddress('ellen@example.com');               //Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Contacto enviado desde wwww.biosima.org';
    $mail->Body    = $htmlTemplateString;
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();

    $json = array();
    $json["status"] = 1;
    $json["mensaje"] = "Correo enviado correctamente";
    echo json_encode($json);
} catch (Exception $e) {
    $json["status"] = 0;
    $json["mensaje"] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    echo json_encode($json);
}
