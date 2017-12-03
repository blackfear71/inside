<?php
  // Fonction communes
  include_once('../../includes/fonctions_communes.php');
  include_once('../../includes/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données : "module métier"
  include_once('modele/metier_missions.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
      $tabMissions = getMissions();
      break;

    case 'doMission':
      // Contrôle Id mission renseigné
      $old_path = $_SERVER["HTTP_REFERER"];

      if (!isset($_GET['id_mission']) OR !is_numeric($_GET['id_mission']))
        header('location: ' . $old_path);

      validateMission($_SESSION['identifiant'], $_GET['id_mission'], $_SESSION['tableau_missions']);
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
      header('location: ' . $old_path);
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_missions.php');
      break;
  }
?>
