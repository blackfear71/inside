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
  include_once('../../includes/functions/fonctions_communes.php');
  include_once('../../includes/functions/fonctions_dates.php');
  include_once('../../includes/functions/fonctions_regex.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données : "module métier"
  include_once('modele/metier_missions.php');

  // Initialisation sauvegarde saisie
	if ((!isset($_SESSION['alerts']['already_ref_mission'])   OR $_SESSION['alerts']['already_ref_mission']   != true)
  AND (!isset($_SESSION['alerts']['objective_not_numeric']) OR $_SESSION['alerts']['objective_not_numeric'] != true)
  AND (!isset($_SESSION['alerts']['wrong_date'])            OR $_SESSION['alerts']['wrong_date']            != true)
  AND (!isset($_SESSION['alerts']['date_less'])             OR $_SESSION['alerts']['date_less']             != true)
  AND (!isset($_SESSION['alerts']['missing_mission_file'])  OR $_SESSION['alerts']['missing_mission_file']  != true)
  AND (!isset($_SESSION['alerts']['file_too_big'])          OR $_SESSION['alerts']['file_too_big']          != true)
  AND (!isset($_SESSION['alerts']['temp_not_found'])        OR $_SESSION['alerts']['temp_not_found']        != true)
  AND (!isset($_SESSION['alerts']['wrong_file_type'])       OR $_SESSION['alerts']['wrong_file_type']       != true)
  AND (!isset($_SESSION['alerts']['wrong_file'])            OR $_SESSION['alerts']['wrong_file']            != true))
	{
    unset($_SESSION['save']);
	}

  if ((isset($_SESSION['alerts']['already_ref_mission'])   AND $_SESSION['alerts']['already_ref_mission']   == true)
  OR  (isset($_SESSION['alerts']['objective_not_numeric']) AND $_SESSION['alerts']['objective_not_numeric'] == true)
  OR  (isset($_SESSION['alerts']['wrong_date'])            AND $_SESSION['alerts']['wrong_date']            == true)
  OR  (isset($_SESSION['alerts']['date_less'])             AND $_SESSION['alerts']['date_less']             == true)
  OR  (isset($_SESSION['alerts']['missing_mission_file'])  AND $_SESSION['alerts']['missing_mission_file']  == true)
  OR  (isset($_SESSION['alerts']['file_too_big'])          AND $_SESSION['alerts']['file_too_big']          == true)
  OR  (isset($_SESSION['alerts']['temp_not_found'])        AND $_SESSION['alerts']['temp_not_found']        == true)
  OR  (isset($_SESSION['alerts']['wrong_file_type'])       AND $_SESSION['alerts']['wrong_file_type']       == true)
  OR  (isset($_SESSION['alerts']['wrong_file'])            AND $_SESSION['alerts']['wrong_file']            == true))
  {
    $erreur_mission = true;
  }

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
      $tabMissions = getMissions();
      break;

    case 'goAjouter':
      if (isset($erreur_mission) AND $erreur_mission == true)
      {
        $detailsMission = initErrMission($_SESSION['save']['new_mission']['post'], NULL);
        unset($erreur_mission);
      }
      else
        $detailsMission = initAddMission();
      break;

    case 'goModifier':
      // Contrôle si l'id est renseignée et numérique
      if (!isset($_GET['id_mission']) OR !is_numeric($_GET['id_mission']))
        header('location: missions.php?action=goConsulter');
      else
      {
        if (isset($erreur_mission) AND $erreur_mission == true)
        {
          $detailsMission = initErrMission($_SESSION['save']['old_mission']['post'], $_GET['id_mission']);
          unset($erreur_mission);
        }
        else
          $detailsMission = initModMission($_GET['id_mission']);

        $participants = getParticipants($_GET['id_mission']);
        $ranking      = getRankingMission($_GET['id_mission'], $participants);
      }
      break;

    case 'doAjouter':
      $erreur_mission = insertMission($_POST, $_FILES);
      break;

    case 'doModifier':
      $id_mission = updateMission($_POST, $_FILES);
      break;

    case 'doSupprimer':
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

    case 'goModifier':
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

      foreach ($ranking as &$rankUser)
      {
        $rankUser['identifiant'] = htmlspecialchars($rankUser['identifiant']);
        $rankUser['pseudo']      = htmlspecialchars($rankUser['pseudo']);
        $rankUser['avatar']      = htmlspecialchars($rankUser['avatar']);
        $rankUser['total']       = htmlspecialchars($rankUser['total']);
        $rankUser['rank']        = htmlspecialchars($rankUser['rank']);
      }

      unset($rankUser);
      break;

    case 'goAjouter':
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
      if ($erreur_mission == true)
        header('location: missions.php?action=goAjouter');
      else
        header('location: missions.php?action=goConsulter');
      break;

    case 'doModifier':
      header('location: missions.php?id_mission=' . $id_mission . '&action=goModifier');
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
