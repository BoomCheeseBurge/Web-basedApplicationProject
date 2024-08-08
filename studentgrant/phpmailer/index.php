<?php
//Include required phpmailer files
require 'includes/PHPMailer.php';
require 'includes/SMTP.php';
require 'includes/Exception.php';
//Define namespaces
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//Create instance of phpmailer
$mail = new PHPMailer();
//Set mailer to use smtp
$mail->isSMTP();
//Define smtp host
$mail->Host = "smtp.gmail.com";
//Enable smtp authentication
$mail->SMTPAuth = "true";
//Set type of encryption (ssl/tls)
$mail->SMTPSecure = "tls";
//Set port to connect smtp
$mail->Port = "587";
//Set gmail username
$mail->Username = "johndoe@gmail.com";
//Set gmail password
$mail->Password = "";
//Set email sucject
$mail->Subject = "Test Email Using PHPMailer";
//Set email sender
$mail->setFrom("johndoe@gmail.com");
//Enable HTML
$mail->isHTML(true);
//Email body
$mail->Body = "<h1>This is HTML h1 Heading</h1><br>";
//Add recipient
$mail->addAddress("johndoe@gmail.com");
//Finally send email
if ($mail->send()) {
    echo "Email sent...!";
} else {
    echo "Error...!";
}
//Closing smtp connection
$mail->smtpClose();
