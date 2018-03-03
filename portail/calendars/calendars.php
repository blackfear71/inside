<?php
  // Fonction communes
  include_once('../../includes/fonctions_communes.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Contrôle si l'année est renseignée et numérique
	if (!isset($_GET['year']) OR !is_numeric($_GET['year']))
		header('location: calendars.php?year=' . date("Y") . '&action=goConsulter');

  // Modèle de données : "module métier"
  include_once('modele/metier_calendars.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture des données par le modèle
      $anneeExistante = controlYear($_GET['year']);
      $onglets        = getOnglets();
      $calendriers    = getCalendars($_GET['year']);
      $preferences    = getPreferences($_SESSION['user']['identifiant']);
      break;

    case "doAjouter":
      insertCalendrier($_POST, $_FILES, $_SESSION['user']['identifiant']);
      break;

    case "doSupprimer":
      deleteCalendrier($_GET['id_cal']);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: calendars.php?year=' . date("Y") . '&action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      foreach ($onglets as &$year)
      {
        $year = htmlspecialchars($year);
      }

      unset($year);

      foreach ($calendriers as &$calendrier)
      {
        $calendrier->setMonth(htmlspecialchars($calendrier->getMonth()));
        $calendrier->setYear(htmlspecialchars($calendrier->getYear()));
        $calendrier->setTitle(htmlspecialchars($calendrier->getTitle()));
        $calendrier->setCalendar(htmlspecialchars($calendrier->getCalendar()));
        $calendrier->setWidth(htmlspecialchars($calendrier->getWidth()));
        $calendrier->setHeight(htmlspecialchars($calendrier->getHeight()));
      }

      unset($calendrier);

      $preferences->setRef_theme(htmlspecialchars($preferences->getRef_theme()));
      $preferences->setView_movie_house(htmlspecialchars($preferences->getView_movie_house()));
      $preferences->setCategories_home(htmlspecialchars($preferences->getCategories_home()));
      $preferences->setToday_movie_house(htmlspecialchars($preferences->getToday_movie_house()));
      $preferences->setView_old_movies(htmlspecialchars($preferences->getView_old_movies()));
      $preferences->setView_the_box(htmlspecialchars($preferences->getView_the_box()));
      $preferences->setView_notifications(htmlspecialchars($preferences->getView_notifications()));
      $preferences->setManage_calendars(htmlspecialchars($preferences->getManage_calendars()));
      break;

    case "doAjouter":
    case "doSupprimer":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case "doAjouter":
      header('location: calendars.php?year=' . $_POST['years'] . '&action=goConsulter');
      break;

    case "doSupprimer":
      header('location: calendars.php?year=' . $_GET['year'] . '&action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_calendars.php');
      break;
  }
?>
