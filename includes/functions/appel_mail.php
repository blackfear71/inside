<?php
  // Appel au serveur de mails
  require($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/libraries/php/phpmailer/class.phpmailer.php');

  // Données du serveur
  $mail           = new PHPMailer();
  $mail->Host     = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Port     = 465; // Par défaut

  // Authentification
  $mail->Username = 'adresse.mail@mail.mail';
  $mail->Password = 'password';

  // Expéditeur
  $mail->SetFrom('adresse.mail@mail.mail', 'Inside');
?>
