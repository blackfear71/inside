<?php
  // Démarrage de la session
  session_start();

  // Modification de la session
  if ($_SESSION['index']['mobile'] == true)
    $_SESSION['index']['mobile'] = false;
  else
    $_SESSION['index']['mobile'] = true;

  // Rafraichissement de la page courante
  header('location: ' . $_SERVER['HTTP_REFERER']);
?>
