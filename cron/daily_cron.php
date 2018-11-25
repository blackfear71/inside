<?php
  // Lancement de la session
  if (empty(session_id()))
    session_start();

  // Fonctions communes
  include_once('../includes/functions/appel_bdd.php');
  include_once('../includes/functions/fonctions_communes.php');
  include_once('../includes/functions/fonctions_dates.php');
  include_once('fonctions_cron.php');

  /*** Traitements journaliers (tous les jours à 7h)***/
  $type_log    = 'j';
  $heure_debut = date('His');
  $daily_trt   = array();

  // Sortie cinéma du jour
  $films_trt = isCinemaToday();
  array_push($daily_trt, $films_trt);

  // Durée mission
  $duration_missions = durationMissions();

  foreach($duration_missions as $mission)
  {
    switch ($mission['one_day'])
    {
      case "O":
        // Notification mission unique
        $one_mission = isOneDayMission($mission['id_mission']);
        array_push($daily_trt, $one_mission);
        break;

      case "F":
        // Notification début de mission
        $begin_mission = isFirstDayMission($mission['id_mission']);
        array_push($daily_trt, $begin_mission);
        break;

      case "L":
        // Notification fin de mission
        $end_mission = isLastDayMission($mission['id_mission']);
        array_push($daily_trt, $end_mission);
        break;

      case "N":
      default:
        break;
    }
  }

  // Ajout expérience pour les gagnants
  $experience_missions = insertExperienceWinners();

  if (!empty($experience_missions))
    array_push($daily_trt, $experience_missions);

  // Génération log
  $heure_fin = date('His');
  generateLog($type_log, $daily_trt, $heure_debut, $heure_fin);

  // Redirection si asynchrone
  if (isset($_POST['daily_cron']))
  {
    $_SESSION['alerts']['daily_cron'] = true;
    header('location: /inside/administration/cron.php?action=goConsulter');
  }
?>
