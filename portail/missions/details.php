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
        $detailsMission->setMission(htmlspecialchars($detailsMission->getMission()));
        $detailsMission->setReference(htmlspecialchars($detailsMission->getReference()));
        $detailsMission->setDate_deb(htmlspecialchars($detailsMission->getDate_deb()));
        $detailsMission->setDate_fin(htmlspecialchars($detailsMission->getDate_fin()));
        $detailsMission->setHeure(htmlspecialchars($detailsMission->getHeure()));
        $detailsMission->setObjectif(htmlspecialchars($detailsMission->getObjectif()));
        $detailsMission->setDescription(htmlspecialchars($detailsMission->getDescription()));
        $detailsMission->setExplications(htmlspecialchars($detailsMission->getExplications()));
        $detailsMission->setConclusion(htmlspecialchars($detailsMission->getConclusion()));
        $detailsMission->setStatut(htmlspecialchars($detailsMission->getStatut()));

        foreach ($participants as &$participant)
        {
          $participant->setIdentifiant(htmlspecialchars($participant->getIdentifiant()));
          $participant->setPseudo(htmlspecialchars($participant->getPseudo()));
          $participant->setAvatar(htmlspecialchars($participant->getAvatar()));
        }

        unset($participant);

        $missionUser['daily'] = htmlspecialchars($missionUser['daily']);
        $missionUser['event'] = htmlspecialchars($missionUser['event']);

        if (date('Ymd') > $detailsMission->getDate_fin())
        {
          foreach ($ranking as &$rankUser)
          {
            $rankUser['identifiant'] = htmlspecialchars($rankUser['identifiant']);
            $rankUser['pseudo']      = htmlspecialchars($rankUser['pseudo']);
            $rankUser['avatar']      = htmlspecialchars($rankUser['avatar']);
            $rankUser['total']       = htmlspecialchars($rankUser['total']);
            $rankUser['rank']        = htmlspecialchars($rankUser['rank']);
          }

          unset($rankUser);
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
