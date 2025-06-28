<?php

namespace core\classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use core\controllers\Main;

class EnviarEmail
{

    public function enviar_email_confirmacao_novo_cliente($email_cliente, $purl)
    {
        // Gera o link usando a função segura
        $link = Store::generateUrl('confirmar_email&purl=' . $purl);

        //Load Composer's autoloader
        //require 'vendor/autoload.php';

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = EMAIL_HOST;                  //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = EMAIL_FROM;                     //SMTP username
            $mail->Password   = EMAIL_PASS;                             //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = EMAIL_PORT;                            //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $mail->CharSet = 'UTF-8';
            //Recipients
            $mail->setFrom(EMAIL_FROM, APP_NAME);
            // Colocar email do cliente
            // $mail->addAddress("email do cliente", "Registro de conta");     //Add a recipient
            $mail->addAddress($email_cliente, "Registo Conta");               //Name is optional
            // $mail->addReplyTo('info@example.com', 'Information');
            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');

            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments

            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            // Texto que vais colocar para enviar ------
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = APP_NAME . ' - Confirmação de conta';
            $html = '<p>Seja bem Vindo à Nossa Loja ' . APP_NAME . '.</p>';
            $html .= '<p>Para poder Entrar na Nossa Loja, Necessita sonfirmar o seu Email.</p>';
            $html .= '<p>Para confirmar o Email, click no Link abaixo:</p>';
            $html .= '<p><a href="' . $link . '">Confirmar Email</a></p>';
            $mail->Body = $html;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return $mail->ErrorInfo ?: $e->getMessage();
        }
    }

    public function enviar_email_recuperacao_senha($email_cliente, $token)
    {
        // Gera o link usando a função segura
        $link = Store::generateUrl('redefinir_senha&token=' . $token);

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = EMAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = EMAIL_FROM;
            $mail->Password   = EMAIL_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = EMAIL_PORT;
            $mail->CharSet    = 'UTF-8';
            
            //Recipients
            $mail->setFrom(EMAIL_FROM, APP_NAME);
            $mail->addAddress($email_cliente, "Recuperação de Senha");

            //Content
            $mail->isHTML(true);
            $mail->Subject = APP_NAME . ' - Recuperação de Senha';
            $html = '<p>Você solicitou a recuperação de senha da sua conta no ' . APP_NAME . '.</p>';
            $html .= '<p>Para criar uma nova senha, clique no link abaixo:</p>';
            $html .= '<p><a href="' . $link . '">Redefinir Senha</a></p>';
            $html .= '<p>Se você não solicitou esta recuperação, ignore este email.</p>';
            $html .= '<p>Este link expira em 1 hora.</p>';
            $mail->Body = $html;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
