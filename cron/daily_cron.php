<?php
  // Lancement de la session
  if (empty(session_id()))
    session_start();

  // Fonctions communes
  include_once('../includes/appel_bdd.php');
  include_once('../includes/fonctions_communes.php');
  include_once('../includes/fonctions_dates.php');
  include_once('fonctions_cron.php');

  /*** Traitements journaliers (tous les jours à 7h)***/
  $type_log    = 'j';
  $heure_debut = date('His');
  $daily_trt   = array();

  // Sortie cinéma du jour
  $films_trt = isCinemaToday();
  array_push($daily_trt, $films_trt);

  // Mise à jour des succès
  // à développer (après la refonte des succès)

  // Génération log
  $heure_fin = date('His');
  generateLog($type_log, $daily_trt, $heure_debut, $heure_fin);

  // Redirection si asynchrone
  if (isset($_POST['daily_cron']))
  {
    $_SESSION['daily_cron'] = true;
    header('location: /inside/administration/cron.php?action=goConsulter');
  }
?>
