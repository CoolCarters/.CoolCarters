<?php
function sendEmail($to, $subject, $message) {
    $headers  = "From: CoolCarters <no-reply@coolcarters.com>\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    return mail($to, $subject, $message, $headers); // Use mail() or PHPMailer
}
