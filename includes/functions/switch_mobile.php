<?php
  // Lancement de la session
  if (empty(session_id()))
    session_start();

  // Modification de la session
  if ($_SESSION['index']['mobile'] == true)
    $_SESSION['index']['mobile'] = false;
  else
    $_SESSION['index']['mobile'] = true;

  // Rafraichissement de la page courante
  header('location: ' . $_SERVER['HTTP_REFERER']);
?>
