<?php
  // Lancement de la session
  if (empty(session_id()))
    session_start();

  // Fonctions communes
  include_once('../includes/functions/appel_bdd.php');
  include_once('../includes/functions/metier_commun.php');
  include_once('../includes/functions/fonctions_dates.php');
  include_once('../includes/functions/fonctions_regex.php');
  include_once('fonctions_cron.php');

  /*** Traitements hebdomadaires (tous les lundi à 7h)***/
  $typeLog    = 'h';
  $heureDebut = date('His');
  $weeklyTrt  = array();

  // Remise à plat des bilans des dépenses
  $bilansTrt = reinitializeExpenses();
  array_push($weeklyTrt, $bilansTrt);

  // Détermination + généreux et + radin
  // à développer (après la refonte des dépenses), à conditionner sur "lundi" ?

  // Sauvegarde BDD
  // à développer (stocker dans un dossier), à conditionner sur "lundi" ?

  // Génération log
  $heureFin = date('His');
  generateLog($typeLog, $weeklyTrt, $heureDebut, $heureFin);

  // Redirection si asynchrone
  if (isset($_POST['weekly_cron']))
  {
    $_SESSION['alerts']['weekly_cron'] = true;
    header('location: /inside/administration/cron/cron.php?action=goConsulter');
  }
?>
