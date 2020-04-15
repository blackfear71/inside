<?php
  /******************************
  *** Gestion des calendriers ***
  *******************************
  Fonctionnalités :
  - Autorisations d'édition
  - Suppression des calendriers
  ******************************/

  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données
  include_once('modele/metier_calendars.php');
  include_once('modele/physique_calendars.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Récupération des autorisations de gestion des calendriers
      $listeAutorisations      = getAutorisationsCalendars();

      // Récupération de la liste des mois
      $listeMois               = getMonths();

      // Récupération des calendriers à supprimer
			$listeSuppression        = getCalendarsToDelete($listeMois);
			$alerteCalendars         = getAlerteCalendars();

      // Récupération des annexes à supprimer
      $listeSuppressionAnnexes = getAnnexesToDelete();
      $alerteAnnexes           = getAlerteAnnexes();
      break;

    case "doUpdateAutorisations":
      // Mise à jour des autorisations de gestion des calendriers
      updateAutorisations($_POST);
      break;

		case "doDeleteCalendrier":
      // Suppression d'un calendrier
			deleteCalendrier($_POST);
			break;

    case "doDeleteAnnexe":
      // Suppression d'une annexe
      deleteAnnexe($_POST);
      break;

		case "doResetCalendrier":
      // Annulation de la demande de suppression d'un calendrier
			resetCalendrier($_POST);
			break;

    case "doResetAnnexe":
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
			foreach ($listeSuppression as &$calendar)
			{
        $calendar->setTo_delete(htmlspecialchars($calendar->getTo_delete()));
        $calendar->setMonth(htmlspecialchars($calendar->getMonth()));
        $calendar->setYear(htmlspecialchars($calendar->getYear()));
				$calendar->setCalendar(htmlspecialchars($calendar->getCalendar()));
			}

      unset($calendar);

      foreach ($listeSuppressionAnnexes as &$annexe)
			{
				$annexe->setTo_delete(htmlspecialchars($annexe->getTo_delete()));
        $annexe->setAnnexe(htmlspecialchars($annexe->getAnnexe()));
        $annexe->setTitle(htmlspecialchars($annexe->getTitle()));
			}

      unset($annexe);

      foreach ($listeAutorisations as &$autorisation)
      {
        $autorisation['identifiant']      = htmlspecialchars($autorisation['identifiant']);
        $autorisation['pseudo']           = htmlspecialchars($autorisation['pseudo']);
        $autorisation['manage_calendars'] = htmlspecialchars($autorisation['manage_calendars']);
      }

      unset($preference);
      break;

    case "doUpdateAutorisations":
    case "doDeleteCalendrier":
		case "doDeleteAnnexe":
		case "doResetCalendrier":
    case "doResetAnnexe":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case "doUpdateAutorisations":
		case "doDeleteCalendrier":
    case "doDeleteAnnexe":
		case "doResetCalendrier":
    case "doResetAnnexe":
			//header ('location: calendars.php?action=goConsulter');
			break;

    case 'goConsulter':
    default:
      include_once('vue/vue_calendars.php');
      break;
  }
?>
