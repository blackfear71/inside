<?php
  /*************************
  *** Missions : Insider ***
  **************************
  Fonctionnalités :
  - Détails de la mission
  *************************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');
  include_once('../../includes/functions/fonctions_regex.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_missions.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Contrôle si l'id est renseignée et numérique
      if (!isset($_GET['id_mission']) OR !is_numeric($_GET['id_mission']))
        header('location: missions.php?action=goConsulter');
      else
      {
        // Lecture liste des données par le modèle
        $missionExistante = controlMission($_GET['id_mission']);

        if ($missionExistante == true)
        {
          $detailsMission = getMission($_GET['id_mission']);
          $participants   = getParticipants($_GET['id_mission']);
          $missionUser    = getMissionUser($_GET['id_mission'], $_SESSION['user']['identifiant']);

          if (date('Ymd') > $detailsMission->getDate_fin())
            $ranking = getRankingMission($_GET['id_mission'], $participants);
        }
      }
      break;

    default:
      // Contrôle action renseignée URL
      header('location: details.php?id_mission=' . $_GET['id_mission'] . '&action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      if ($missionExistante == true)
      {
        Mission::secureData($detailsMission);

        foreach ($participants as $participant)
        {
          Profile::secureData($participant);
        }

        $missionUser['daily'] = htmlspecialchars($missionUser['daily']);
        $missionUser['event'] = htmlspecialchars($missionUser['event']);

        if (date('Ymd') > $detailsMission->getDate_fin())
        {
          foreach ($ranking as $rankUser)
          {
            ParticipantMission::secureData($rankUser);
          }
        }
      }
      break;

    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'goConsulter':
    default:
      include_once('vue/vue_details.php');
      break;
  }
?>
