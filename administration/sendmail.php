<?php
  require('../includes/phpmailer/class.phpmailer.php');

  $mail             = new PHPMailer();
  $mail->Host       = 'smtp.gmail.com';
  $mail->SMTPAuth   = true;
  $mail->Port       = 465; // Par défaut

  // Authentification
  $mail->Username   = "mailadress";
  $mail->Password   = "password";

  // Expéditeur
  $mail->SetFrom('mailadress', 'Inside');
  // Destinataire
  $mail->AddAddress('mailadress', 'User');
  // Objet
  $mail->Subject = 'Objet du message';

  // Contenu message
  $mail->MsgHTML('Corps du message en HTML');

  // Envoi du mail avec gestion des erreurs
  if(!$mail->Send())
    echo 'Erreur : ' . $mail->ErrorInfo;
?>
