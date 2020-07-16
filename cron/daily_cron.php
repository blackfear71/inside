<?php
  // Lancement de la session
  if (empty(session_id()))
    session_start();

  // Fonctions communes
  include_once('../includes/functions/appel_bdd.php');
  include_once('../includes/functions/metier_commun.php');
  include_once('../includes/functions/fonctions_dates.php');
  include_once('fonctions_cron.php');

  /*** Traitements journaliers (tous les jours à 7h)***/
  $typeLog    = 'j';
  $heureDebut = date('His');
  $dailyTrt   = array();

  // Sortie cinéma du jour
  $filmsTrt = isCinemaToday();
  array_push($dailyTrt, $filmsTrt);

  // Durée mission
  $durationMissions = durationMissions();

  foreach ($durationMissions as $mission)
  {
    switch ($mission['one_day'])
    {
      case 'O':
        // Notification mission unique
        $oneMission = isOneDayMission($mission['id_mission']);
        array_push($dailyTrt, $oneMission);
        break;

      case 'F':
        // Notification début de mission
        $beginMission = isFirstDayMission($mission['id_mission']);
        array_push($dailyTrt, $beginMission);
        break;

      case 'L':
        // Notification fin de mission
        $endMission = isLastDayMission($mission['id_mission']);
        array_push($dailyTrt, $endMission);
        break;

      case 'N':
      default:
        break;
    }
  }

  // Ajout expérience pour les gagnants
  $experienceMissions = insertExperienceWinners();

  if (!empty($experienceMissions))
    array_push($dailyTrt, $experienceMissions);

  // Génération log
  $heureFin = date('His');
  generateLog($typeLog, $dailyTrt, $heureDebut, $heureFin);

  // Redirection si asynchrone
  if (isset($_POST['daily_cron']))
  {
    $_SESSION['alerts']['daily_cron'] = true;
    header('location: /inside/administration/cron/cron.php?action=goConsulter');
  }
?>
