<?php
  require('libraries/phpmailer/class.phpmailer.php');

  $mail             = new PHPMailer();
  $mail->Host       = 'smtp.gmail.com';
  $mail->SMTPAuth   = true;
  $mail->Port       = 465; // Par défaut

  // Authentification
  $mail->Username   = "adresse.mail@mail.mail";
  $mail->Password   = "password";

  // Expéditeur
  $mail->SetFrom('adresse.mail@mail.mail', 'Inside');
?>
