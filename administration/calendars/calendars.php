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

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
      $listePreferences        = getListePreferences();
			$listeSuppression        = getCalendarsToDelete();
			$alerteCalendars         = getAlerteCalendars();
      $listeSuppressionAnnexes = getAnnexesToDelete();
      $alerteAnnexes           = getAlerteAnnexes();
      break;

    case "doChangerAutorisations":
      updateAutorisations($_POST);
      break;

		case "doDeleteCalendrier":
			deleteCalendrier($_POST);
			break;

    case "doDeleteAnnexe":
      deleteAnnexe($_POST);
      break;

		case "doResetCalendrier":
			resetCalendrier($_POST);
			break;

    case "doResetAnnexe":
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

      foreach ($listePreferences as &$preference)
      {
        $preference['identifiant']      = htmlspecialchars($preference['identifiant']);
        $preference['pseudo']           = htmlspecialchars($preference['pseudo']);
        $preference['manage_calendars'] = htmlspecialchars($preference['manage_calendars']);
      }

      unset($preference);
      break;

    case "doChangerAutorisations":
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
    case "doChangerAutorisations":
		case "doDeleteCalendrier":
    case "doDeleteAnnexe":
		case "doResetCalendrier":
    case "doResetAnnexe":
			header ('location: calendars.php?action=goConsulter');
			break;

    case 'goConsulter':
    default:
      include_once('vue/vue_calendars.php');
      break;
  }
?>
