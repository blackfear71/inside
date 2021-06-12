<?php
  /******************************
  *** Gestion des calendriers ***
  *******************************
  Fonctionnalités :
  - Autorisations d'édition
  - Suppression des calendriers
  ******************************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/physique_commun.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données
  include_once('modele/metier_calendars.php');
  include_once('modele/physique_calendars.php');

  // Appels métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Récupération de la liste des équipes
      $listeEquipes = getListeEquipes();

      // Récupération des autorisations de gestion des calendriers
      $listeAutorisationsParEquipe = getAutorisationsCalendars();

      // Récupération des périodes de vacances disponibles
      $periodesVacances = getPeriodesVacances();

      // Récupération alerte période de vacances à saisir
      $periodesPresentes = getPeriodesVacancesPresentes();

      // Initialisation des saisies des périodes de vacances
      $saisiesVacances = getSaisieVacances();

      // Récupération de la liste des mois
      $listeMois = getMonths();

      // Récupération des calendriers à supprimer
			$listeSuppression = getCalendarsToDelete($listeMois);
			$alerteCalendars  = getAlerteCalendars();

      // Récupération des annexes à supprimer
      $listeSuppressionAnnexes = getAnnexesToDelete();
      $alerteAnnexes           = getAlerteAnnexes();
      break;

    case 'doUpdateAutorisations':
      // Récupération de la liste des utilisateurs
      $listeUsers = getUsers();

      // Mise à jour des autorisations de gestion des calendriers
      updateAutorisations($_POST, $listeUsers);
      break;

    case 'doInsererVacances':
      insertVacancesCSV($_POST);
      break;

    case 'doDeleteVacances':
      deleteVacancesCSV($_POST);
      break;

		case 'doDeleteCalendrier':
      // Suppression d'un calendrier
			deleteCalendrier($_POST);
			break;

    case 'doDeleteAnnexe':
      // Suppression d'une annexe
      deleteAnnexe($_POST);
      break;

		case 'doResetCalendrier':
      // Annulation de la demande de suppression d'un calendrier
			resetCalendrier($_POST);
			break;

    case 'doResetAnnexe':
      // Annulation de la demande de suppression d'une annexe
      resetAnnexe($_POST);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: calendars.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      foreach ($listeEquipes as $equipe)
      {
        Team::secureData($equipe);
      }
      
      foreach ($listeAutorisationsParEquipe as $equipeAutorisations)
      {
        foreach ($equipeAutorisations as $autorisation)
        {
          AutorisationCalendriers::secureData($autorisation);
        }
      }

      foreach ($periodesVacances as &$periodeVacances)
      {
        $periodeVacances = htmlspecialchars($periodeVacances);
      }

      unset($periodeVacances);

      foreach ($saisiesVacances as &$saisieVacances)
      {
        $saisieVacances['nom'] = htmlspecialchars($saisieVacances['nom']);
      }

      unset($saisieVacances);

			foreach ($listeSuppression as $calendar)
			{
        Calendrier::secureData($calendar);
			}

      foreach ($listeSuppressionAnnexes as $annexe)
			{
        Annexe::secureData($annexe);
			}
      break;

    case 'doUpdateAutorisations':
    case 'doInsererVacances':
    case 'doDeleteVacances':
    case 'doDeleteCalendrier':
		case 'doDeleteAnnexe':
		case 'doResetCalendrier':
    case 'doResetAnnexe':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doUpdateAutorisations':
    case 'doInsererVacances':
    case 'doDeleteVacances':
		case 'doDeleteCalendrier':
    case 'doDeleteAnnexe':
		case 'doResetCalendrier':
    case 'doResetAnnexe':
			header ('location: calendars.php?action=goConsulter');
			break;

    case 'goConsulter':
    default:
      include_once('vue/vue_calendars.php');
      break;
  }
?>
