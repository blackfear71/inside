<?php
  // Lancement de la session
  if (empty(session_id()))
    session_start();

  // Modification de la session
  if ($_SESSION['index']['plateforme'] == 'mobile')
    $_SESSION['index']['plateforme'] = 'web';
  else
    $_SESSION['index']['plateforme'] = 'mobile';

  // Rafraichissement de la page courante
  header('location: ' . $_SERVER['HTTP_REFERER']);
?>
