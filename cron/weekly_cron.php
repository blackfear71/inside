<?php
  // Lancement de la session
  if (empty(session_id()))
    session_start();

  // Fonctions communes
  include_once('../includes/functions/appel_bdd.php');
  include_once('../includes/functions/fonctions_communes.php');
  include_once('../includes/functions/fonctions_dates.php');
  include_once('fonctions_cron.php');

  /*** Traitements hebdomadaires (tous les lundi à 7h)***/
  $type_log    = 'h';
  $heure_debut = date('His');
  $weekly_trt  = array();

  // Remise à plat des bilans des dépenses
  $bilans_trt = reinitializeExpenses();
  array_push($weekly_trt, $bilans_trt);

  // Détermination + généreux et + radin
  // à développer (après la refonte des dépenses), à conditionner sur "lundi" ?

  // Sauvegarde BDD
  // à développer (stocker dans un dossier), à conditionner sur "lundi" ?

  // Génération log
  $heure_fin = date('His');
  generateLog($type_log, $weekly_trt, $heure_debut, $heure_fin);

  // Redirection si asynchrone
  if (isset($_POST['weekly_cron']))
  {
    $_SESSION['alerts']['weekly_cron'] = true;
    header('location: /inside/administration/cron/cron.php?action=goConsulter');
  }
?>
