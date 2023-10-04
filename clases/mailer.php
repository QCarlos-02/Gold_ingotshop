<?php
use PHPMailer\PHPMailer\{PHPMailer, SMTP, Exception};

class Mailer {
    function enviarEmail($email, $asunto, $cuerpo){
    require_once __DIR__ .'/../config/config.php';
    require_once  __DIR__ .'/../phpmailer/src/PHPMailer.php';
    require_once  __DIR__ .'/../phpmailer/src/SMTP.php';
    require_once  __DIR__ .'/../phpmailer/src/Exception.php';

    $mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF;      
    $mail->isSMTP();                                           
    $mail->Host       = 'smtp.gmail.com';                     
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = 'cmanuelquintero@unicesar.edu.co';                   
    $mail->Password   = 'Carlos_200204';                               
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;      
    //Recipients
    $mail->setFrom('cmanuelquintero@unicesar.edu.co', 'GOLD SHOP');
    $mail->addAddress($email);    
   
 
                                                                  
    //Content
    $mail->isHTML(true);                              
    $mail->Subject = $asunto;

   

    $mail->Body  = utf8_decode($cuerpo);
    

    $mail->setLanguage('es', '../phpmailer/language/phpmailer.lang-es.php');


   if($mail->send()){
    return true;
   } else {
    return false;
   }

} catch (Exception $e) {
    echo "Error al enviar el email: {$mail->ErrorInfo}";
    return false;
    //exit;
}
    }
}