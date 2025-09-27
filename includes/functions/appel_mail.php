<?php
    // Appel au serveur de mails
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    if (isset($_SERVER['DOCUMENT_ROOT']) AND !empty($_SERVER['DOCUMENT_ROOT']))
    {
        // Inclusions web
        require($_SERVER['DOCUMENT_ROOT'] . '/includes/libraries/php/PHPMailer/src/DSNConfigurator.php');
        require($_SERVER['DOCUMENT_ROOT'] . '/includes/libraries/php/PHPMailer/src/Exception.php');
        require($_SERVER['DOCUMENT_ROOT'] . '/includes/libraries/php/PHPMailer/src/OAuthTokenProvider.php');
        require($_SERVER['DOCUMENT_ROOT'] . '/includes/libraries/php/PHPMailer/src/OAuth.php');
        require($_SERVER['DOCUMENT_ROOT'] . '/includes/libraries/php/PHPMailer/src/PHPMailer.php');
        require($_SERVER['DOCUMENT_ROOT'] . '/includes/libraries/php/PHPMailer/src/POP3.php');
        require($_SERVER['DOCUMENT_ROOT'] . '/includes/libraries/php/PHPMailer/src/SMTP.php');
    }
    else
    {
        // Inclusions CRON
        require('../includes/libraries/php/PHPMailer/src/DSNConfigurator.php');
        require('../includes/libraries/php/PHPMailer/src/Exception.php');
        require('../includes/libraries/php/PHPMailer/src/OAuthTokenProvider.php');
        require('../includes/libraries/php/PHPMailer/src/OAuth.php');
        require('../includes/libraries/php/PHPMailer/src/PHPMailer.php');
        require('../includes/libraries/php/PHPMailer/src/POP3.php');
        require('../includes/libraries/php/PHPMailer/src/SMTP.php');
    }

    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    try
    {
        // Données du serveur
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
        $mail->Port       = 465;

        // Authentification
        $mail->Username   = 'adresse.mail@mail.mail';
        $mail->Password   = 'password';

        // Expéditeur
        $mail->SetFrom('adresse.mail@mail.mail', 'Inside');

        // Certificat DKIM (pour éviter le spam, à générer ici : https://dkimcore.org/tools/keys.html)
		$mail->DKIM_domain = 'inside.ddns.net';
		
		if (isset($_SERVER['DOCUMENT_ROOT']) AND !empty($_SERVER['DOCUMENT_ROOT']))
			$mail->DKIM_private = $_SERVER['DOCUMENT_ROOT'] . '/includes/libraries/php/PHPMailer/dkim_private.pem';
		else
			$mail->DKIM_private = '../includes/libraries/php/PHPMailer/dkim_private.pem';

		$mail->DKIM_selector   = 'phpmailer';
		$mail->DKIM_passphrase = '';
		$mail->DKIM_identity   = $mail->From;
    }
    catch (Exception $e)
    {
        echo "Erreur : {$mail->ErrorInfo}";
    }
?>