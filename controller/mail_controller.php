<?php

//////////////// Use PHP Mailer namespaces ////////////////
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//////////////// Include PHP Mailer autoload.php file ////////////////
require_once('../assets/PHPMailer/vendor/autoload.php');

class mail_send
{
    //////////////// Setup SMTP username and password ////////////////
    protected $sender_email = "sample.beyondtr@gmail.com";
    protected $password     = "abcd123&&";

    //////////////// Method of sending emails ////////////////
    function Send_Mail($email, $subject, $body)
    {

        ////////////// Create an instance; passing `true` enables exceptions ////////////
        $mail = new PHPMailer;
        // $GLOBALS['mail'] = $mail;
        $mail->isSMTP();

        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

        $mail->Username = $this->sender_email;
        $mail->Password = $this->password;

        $mail->setFrom($this->sender_email, 'BLOOD TRANSFUSION SERVICES(Sample)');
        $mail->addAddress($email); //Add a recipient
        $mail->addReplyTo($this->sender_email); //Add a reply mail

        $mail->isHTML(true); // HTML enable
        $mail->WordWrap = 50;

        $mail->Subject = $subject;
        $mail->Body    = $body;
        $result = $mail->Send(); // Return boolean

        return $result;
    }
}
