<?php
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';
require 'vendor/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp-relay.brevo.com'; // Brevo SMTP
        $mail->SMTPAuth   = true;
        $mail->Username   = '87d214001@smtp-brevo.com'; //
        $mail->Password   = 'yh23H08c6mYrwDNB'; //  SMTP Key from Brevo
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS encryption
        $mail->Port       = 587; // TLS port

        // Sender & Recipient
        $mail->setFrom('rsdsl@outlook.com', 'Live Vault');
        $mail->addAddress($to);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // Send Email
        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
