<?php
namespace app\components;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailHelper
{
public static function send($to, $subject, $body, $fromEmail = null, $fromName = null)
{
    $mail = new PHPMailer(true);
    try {
        $fromEmail = $fromEmail ?? \Yii::$app->params['senderEmail'];
        $fromName  = $fromName  ?? \Yii::$app->params['senderName'];

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = \Yii::$app->params['smtpUser'];
        $mail->Password   = \Yii::$app->params['smtpPass'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        \Yii::error("Mail Error: {$mail->ErrorInfo}", __METHOD__);
        return false;
    }
}

}
