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
      $listeMissions = getMissions();
      break;

    case 'doMission':
      if (isset($_SERVER['HTTP_REFERER']))
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
      foreach ($listeMissions as $mission)
      {
        Mission::secureData($mission);
      }
      break;

    case 'doMission':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doMission':
      if (isset($_SERVER['HTTP_REFERER']))
        header('location: ' . $_SERVER['HTTP_REFERER']);
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_missions.php');
      break;
  }
?>
