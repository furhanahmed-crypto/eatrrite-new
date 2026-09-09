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
    $mail->AddReplyTo($_POST['email'], $_POST['name']);
    $mail->addAddress('eatrrite@gmail.com', 'Receiver');     //Add a recipient
   

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'New Contact Request form '.$_POST['name'].' with subject '.$_POST['subject'];
    $mail->Body    = 'Hi team'.'<br>'.'We have a new contact request with details '.'<br>'.'Mobile Number: '.$_POST['mobilenumber'].'<br>'.'Message: '.$_POST['messageBody'];
    $mail->AltBody = $_POST['messageBody'];

    $mail->send();
    //echo 'Message has been sent';
    Header( 'Location: ../contact.php?success=1' );
} catch (Exception $e) {
   Header( 'Location: ../contact.php?fail=1' );
    //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
