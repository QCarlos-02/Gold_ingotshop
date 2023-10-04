<?php

use PHPMailer\PHPMailer\{PHPMailer, SMTP, Exception};


require_once '../phpmailer/src/PHPMailer.php';
require_once '../phpmailer/src/SMTP.php';
require_once '../phpmailer/src/Exception.php';


$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;      
    $mail->isSMTP();                                           
    $mail->Host       = 'smtp.gmail.com';                     
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = 'cmanuelquintero@unicesar.edu.co';                   
    $mail->Password   = 'Carlos_200204';                               
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;      
    //Recipients
    $mail->setFrom('cmanuelquintero@unicesar.edu.co', 'GOLD SHOP');
    $mail->addAddress('carlosquinterom9@gmail.com', 'Carlos');    
   
 
                                                                  
    //Content
    $mail->isHTML(true);                              
    $mail->Subject = 'Detalles de su compra';

    $cuerpo = '<h4>Gracias por su compra</h4>';
    $cuerpo .= '<p>El ID de su compra es <b> '.$id_transacion .'</b></p>';

    $mail->Body    = utf8_decode($cuerpo);
    $mail->AltBody = 'Le enviamos los detalles de su compra.';

    $mail->setLanguage('es', '../phpmailer/language/phpmailer.lang-es.php');


    $mail->send();
} catch (Exception $e) {
    echo "Error al enviar el email: {$mail->ErrorInfo}";
    //exit;
}