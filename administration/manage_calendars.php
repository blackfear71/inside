<?php
  // Contrôles communs Administrateur
  include_once('../includes/controls_admin.php');

  // Modèle de données : "module métier"
  include_once('modele/metier_administration.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
			$listeSuppression = getCalendarsToDelete();
			$alerteCalendars  = getAlerteCalendars();
      break;

		case "doDeleteCalendrier":
			deleteCalendrier($_GET['delete_id']);
			break;

		case "doResetCalendrier":
			resetCalendrier($_GET['delete_id']);
			break;

    default:
      // Contrôle action renseignée URL
      header('location: manage_calendars.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
			foreach ($listeSuppression as $calendar)
			{
				$calendar->setId(htmlspecialchars($calendar->getId()));
        $calendar->setTo_delete(htmlspecialchars($calendar->getTo_delete()));
        $calendar->setMonth(htmlspecialchars($calendar->getMonth()));
        $calendar->setYear(htmlspecialchars($calendar->getYear()));
				$calendar->setCalendar(htmlspecialchars($calendar->getCalendar()));
			}
      break;

		case "doDeleteCalendrier":
		case "doResetCalendrier":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
		case "doDeleteCalendrier":
		case "doResetCalendrier":
			header ('location: manage_calendars.php?action=goConsulter');
			break;

    case 'goConsulter':
    default:
      include_once('vue/vue_manage_calendars.php');
      break;
  }
?>
