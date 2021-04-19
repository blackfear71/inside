<?php
  /***************************
  **** CRON Hebdomadaire *****
  ****************************
  Fonctionnalités :
  - Réinitialisation dépenses
  - Génération de log
  ***************************/
  /** TOUS LES LUNDIS A 7H **/
  /**************************/

  // Fonctions communes
  include_once('../includes/functions/metier_commun.php');
  include_once('../includes/functions/physique_commun.php');
  include_once('../includes/functions/fonctions_dates.php');
  include_once('../includes/functions/fonctions_regex.php');

  // Contrôles communs CRON
  controlsCron();

  // Modèle de données
  include_once('modele/metier_cron.php');
  include_once('modele/physique_cron.php');

  // Initialisations
  $typeLog                  = 'h';
  $heureDebut               = date('His');
  $traitementsHebdomadaires = array();

  // Remise à plat des bilans des dépenses
  $traitementBilans = reinitializeExpenses();

  // Ajout du compte-rendu au log
  array_push($traitementsHebdomadaires, $traitementBilans);

  var_dump($_POST);

  // if (!isset($_POST['weekly_cron']))
  // {
    // Envoi d'un mail de gestion à l'administrateur
    $traitementAdmin = sendMailAdmin();

    // Ajout du compte-rendu au log
    array_push($traitementsHebdomadaires, $traitementAdmin);
  // }

  var_dump($traitementAdmin);

  // Détermination + généreux et + radin
  // à développer (après la refonte des dépenses), à conditionner sur "lundi" ?

  // Sauvegarde BDD
  // à développer (stocker dans un dossier), à conditionner sur "lundi" ?

  // Récupération heure de fin de traitement
  $heureFin = date('His');

  // Génération du log de traitement
  generateLog($typeLog, $traitementsHebdomadaires, $heureDebut, $heureFin);

  var_dump($typeLog);
  var_dump($traitementsHebdomadaires);
  var_dump($heureDebut);
  var_dump($heureFin);

  // Redirection si exécution asynchrone
  if (isset($_POST['weekly_cron']))
  {
    // Message d'alerte
    $_SESSION['alerts']['weekly_cron'] = true;

    // Redirection
    // header('location: /inside/administration/cron/cron.php?action=goConsulter');
  }
?>
