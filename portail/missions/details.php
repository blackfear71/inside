<?php
  // Fonction communes
  include_once('../../includes/fonctions_communes.php');
  include_once('../../includes/fonctions_dates.php');
  include_once('../../includes/fonctions_regex.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données : "module métier"
  include_once('modele/metier_missions.php');

  // Contrôle si l'id est renseignée et numérique
  if (!isset($_GET['id_mission']) OR !is_numeric($_GET['id_mission']))
    header('location: missions.php?action=goConsulter');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
      $missionExistante = controlMission($_GET['id_mission']);
      if ($missionExistante == true)
      {
        $detailsMission = getMission($_GET['id_mission']);
        $missionUser    = getMissionUser($_GET['id_mission'], $_SESSION['identifiant']);
        $participants   = getParticipants($_GET['id_mission']);
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

        foreach ($participants as $participant)
        {
          $participant->setIdentifiant(htmlspecialchars($participant->getIdentifiant()));
          $participant->setPseudo(htmlspecialchars($participant->getPseudo()));
          $participant->setAvatar(htmlspecialchars($participant->getAvatar()));
        }

        $missionUser['daily'] = htmlspecialchars($missionUser['daily']);
        $missionUser['event'] = htmlspecialchars($missionUser['event']);
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
