<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'marketing.eatrrite@gmail.com';                     //SMTP username
    $mail->Password   = 'jyka wuku euwi ltpf';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('marketing.eatrrite@gmail.com', 'Mailer');
    $mail->AddReplyTo($_POST['email'], 'Subscriber');
    $mail->addAddress('eatrrite@gmail.com', 'Receiver');     //Add a recipient
   

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Hurray!! New Subscriber';
    $mail->Body    = 'Congratulations team we added a new subscriber in your list'.'<br>'.$_POST['email'];
    $mail->AltBody = 'Congratulations team we added a new subscriber in your list'.'<br>'.$_POST['email'];

    $mail->send();
    //echo 'Message has been sent';
    Header( 'Location: ../index.php?success=1#subscribe' );
} catch (Exception $e) {
   Header( 'Location: ../index.php?fail=1#subscribe' );
    //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
