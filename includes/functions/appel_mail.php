<?php
    // Appel au serveur de mails
    if (isset($_SERVER['DOCUMENT_ROOT']) AND !empty($_SERVER['DOCUMENT_ROOT']))
    {
        // Inclusions web
        require($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/libraries/php/phpmailer/class.phpmailer.php');
        require($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/libraries/php/phpmailer/class.smtp.php');
    }
    else
    {
        // Inclusions CRON
        require('../includes/libraries/php/phpmailer/class.phpmailer.php');
        require('../includes/libraries/php/phpmailer/class.smtp.php');
    }

    // Données du serveur
    $mail             = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPAuth   = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host       = 'smtp.gmail.com';
    $mail->Port       = 465;

    // Authentification
    $mail->Username = 'adresse.mail@mail.mail';
    $mail->Password = 'password';

    // Expéditeur
    $mail->SetFrom('adresse.mail@mail.mail', 'Inside');
?>