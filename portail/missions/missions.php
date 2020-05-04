<?php
  /*************************
  *** Missions : Insider ***
  **************************
  Fonctionnalités :
  - Consulation des missions
  *************************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_missions.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
      $tabMissions = getMissions();
      break;

    case 'doMission':
      if (isset($_SERVER["HTTP_REFERER"]))
        validateMission($_POST, $_SESSION['user']['identifiant'], $_SESSION['missions'][$_POST['key_mission']]);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: missions.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      foreach ($tabMissions as &$ligneMission)
      {
        $ligneMission->setMission(htmlspecialchars($ligneMission->getMission()));
        $ligneMission->setReference(htmlspecialchars($ligneMission->getReference()));
        $ligneMission->setDate_deb(htmlspecialchars($ligneMission->getDate_deb()));
        $ligneMission->setDate_fin(htmlspecialchars($ligneMission->getDate_fin()));
        $ligneMission->setHeure(htmlspecialchars($ligneMission->getHeure()));
        $ligneMission->setObjectif(htmlspecialchars($ligneMission->getObjectif()));
        $ligneMission->setDescription(htmlspecialchars($ligneMission->getDescription()));
        $ligneMission->setExplications(htmlspecialchars($ligneMission->getExplications()));
        $ligneMission->setConclusion(htmlspecialchars($ligneMission->getConclusion()));
        $ligneMission->setStatut(htmlspecialchars($ligneMission->getStatut()));
      }

      unset($ligneMission);
      break;

    case 'doMission':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doMission':
      if (isset($_SERVER["HTTP_REFERER"]))
        header('location: ' . $_SERVER["HTTP_REFERER"]);
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_missions.php');
      break;
  }
?>
