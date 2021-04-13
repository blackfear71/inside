<?php
  /*****************************
  **** Gestion des missions ****
  ******************************
  Fonctionnalités :
  - Création des missions
  - Modification des missions
  - Suppression des missions
  - Consultation des classements
  *****************************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');
  include_once('../../includes/functions/fonctions_regex.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données
  include_once('modele/metier_missions.php');
  include_once('modele/controles_missions.php');
  include_once('modele/physique_missions.php');

  // Appels métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Initialisation de la sauvegarde en session et récupération erreur
      $erreurMission = initializeSaveSession();

      // Récupération de la liste des missions
      $listeMissions = getMissions();
      break;

    case 'goAjouter':
      // Initialisation de la sauvegarde en session et récupération erreur
      $erreurMission = initializeSaveSession();

      // Initialisation de l'écran d'ajout de mission
      if (isset($erreurMission) AND $erreurMission == true)
      {
        $detailsMission = initialisationErreurMission($_SESSION['save']['new_mission']['post'], NULL);
        unset($erreurMission);
      }
      else
        $detailsMission = initialisationAjoutMission();
      break;

    case 'goModifier':
      // Contrôle si l'id est renseignée et numérique
      if (!isset($_GET['id_mission']) OR !is_numeric($_GET['id_mission']))
        header('location: missions.php?action=goConsulter');
      else
      {
        // Initialisation de la sauvegarde en session et récupération erreur
        $erreurMission = initializeSaveSession();

        // Initialisation de l'écran de modification de mission
        if (isset($erreurMission) AND $erreurMission == true)
        {
          $detailsMission = initialisationErreurMission($_SESSION['save']['old_mission']['post'], $_GET['id_mission']);
          unset($erreurMission);
        }
        else
          $detailsMission = initialisationModificationMission($_GET['id_mission']);

        // Récupération du classement des participants
        $participants = getParticipants($_GET['id_mission']);
      }
      break;

    case 'doAjouter':
      // Ajout d'une mission
      $erreurMission = insertMission($_POST, $_FILES);
      break;

    case 'doModifier':
      // Modification d'une mission
      $idMission = updateMission($_POST, $_FILES);
      break;

    case 'doSupprimer':
      // Suppression d'une mission
      deleteMission($_POST);
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

    case 'goModifier':
      Mission::secureData($detailsMission);

      foreach ($participants as $participant)
      {
        ParticipantMission::secureData($participant);
      }
      break;

    case 'goAjouter':
      Mission::secureData($detailsMission);
      break;

    case 'doAjouter':
    case 'doModifier':
    case 'doSupprimer':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doAjouter':
      if ($erreurMission == true)
        header('location: missions.php?action=goAjouter');
      else
        header('location: missions.php?action=goConsulter');
      break;

    case 'doModifier':
      header('location: missions.php?id_mission=' . $idMission . '&action=goModifier');
      break;

    case 'doSupprimer':
      header('location: missions.php?action=goConsulter');
      break;

    case 'goAjouter':
    case 'goModifier':
    case 'goConsulter':
    default:
      include_once('vue/vue_missions.php');
      break;
  }
?>
