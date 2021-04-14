<?php
  /************************************
  ********** CRON Quotidien ***********
  *************************************
  Fonctionnalités :
  - Notification sortie cinéma
  - Notification mission unique
  - Notification début mission
  - Notification fin mission
  - Ajout expérience gagnants mission
  - Génération de log
  ************************************/
  /******* TOUS LES JOURS A 7H *******/
  /***********************************/

  // Fonctions communes
  include_once('../includes/functions/metier_commun.php');
  include_once('../includes/functions/physique_commun.php');
  include_once('../includes/functions/fonctions_dates.php');

  // Contrôles communs CRON
  controlsCron();

  // Modèle de données
  include_once('modele/metier_cron.php');
  include_once('modele/physique_cron.php');

  // Initialisations
  $typeLog                = 'j';
  $heureDebut             = date('His');
  $traitementsQuotidiens  = array();

  // Génération notification sortie cinéma du jour
  $traitementSortieCinema = generateNotificationsSortieCinema();

  // Ajout du compte-rendu au log
  array_push($traitementsQuotidiens, $traitementSortieCinema);

  // Génération notifications missions
  $traitementMissions = generateNotificationsMissions();

  // Ajout des compte-rendus au log
  foreach ($traitementMissions as $traitementMission)
  {
    array_push($traitementsQuotidiens, $traitementMission);
  }

  // Ajout expérience pour les gagnants des missions
  $traitementExperienceMissions = insertExperienceGagnants();

  // Ajout des compte-rendus au log
  foreach ($traitementExperienceMissions as $traitementExperienceMission)
  {
    array_push($traitementsQuotidiens, $traitementExperienceMission);
  }

  // Récupération heure de fin de traitement
  $heureFin = date('His');

  // Génération du log de traitement
  generateLog($typeLog, $traitementsQuotidiens, $heureDebut, $heureFin);

  // Redirection si exécution asynchrone
  if (isset($_POST['daily_cron']))
  {
    // Message d'alerte
    $_SESSION['alerts']['daily_cron'] = true;

    // Redirection
    header('location: /inside/administration/cron/cron.php?action=goConsulter');
  }
?>
