<?php
echo 1;
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

    $htmlTemplate = fopen('plantilla-email.html', 'r');
    $htmlTemplateString = stream_get_contents($htmlTemplate);

    $htmlTemplateString = str_replace("nombre_label", $name, $htmlTemplateString);
    $htmlTemplateString = str_replace("correo_label", $email, $htmlTemplateString);
    $htmlTemplateString = str_replace("asunto_label", $subject, $htmlTemplateString);
    $htmlTemplateString = str_replace("mensaje_label", $message, $htmlTemplateString);
    $htmlTemplateString = str_replace("á", '&aacute;', str_replace("é", '&eacute;', str_replace("í", '&iacute;', str_replace("ó", '&oacute;', str_replace("ú", '&uacute;', $htmlTemplateString)))));

    //Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.titan.email';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'administracion@biosima.org';                     //SMTP username
    $mail->Password   = 'AYTKwqCj7g';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('administracion@biosima.org', 'BIOSIMA PAGINA');
    $mail->addAddress('administracion@biosima.org', 'BIOSIMA PAGINA');
    $mail->addReplyTo('ibarrag@biosima.com.mx', 'Jesus Ibarra');
    $mail->addReplyTo('ledon@biosima.com.mx', 'Dulce Ledon');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Contacto enviado desde wwww.biosima.org';
    $mail->Body    = $htmlTemplateString;
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();

    $json = array();
    $json["status"] = 1;
    $json["mensaje"] = "Correo enviado correctamente";

    echo "Correo enviado correctamente";
    logs("Correo enviado correctamente");

    echo json_encode($json);
} catch (Exception $e) {
    echo $e->getMessage();
    logs($e->getMessage());
    $json["status"] = 0;
    $json["mensaje"] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    echo json_encode($json);
}


function logs($msn)
{
    $date = date('d-m-Y h:i:s');

    $file = fopen("../log.txt", "a");
    fputs($file,$date . ' - ' .  $msn . PHP_EOL);
    fclose($file);
}
?>