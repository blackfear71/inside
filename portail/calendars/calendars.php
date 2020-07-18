<?php
  /******************************
  ********** Calendars **********
  *******************************
  Fonctionnalités :
  - Consultation des calendriers
  - Ajout des calendriers
  - Suppression des calendriers
  - Consultation des annexes
  - Ajout des annexes
  - Suppression des annexes
  ******************************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_calendars.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Contrôle si l'année est renseignée et numérique
      if (!isset($_GET['year']) OR !is_numeric($_GET['year']))
        header('location: calendars.php?year=' . date('Y') . '&action=goConsulter');
      else
      {
        // Lecture des données par le modèle
        $anneeExistante = controlYear($_GET['year']);
        $onglets        = getOnglets();
        $calendriers    = getCalendars($_GET['year']);
        $preferences    = getPreferences($_SESSION['user']['identifiant']);
      }
      break;

    case 'goConsulterAnnexes':
      $onglets     = getOnglets();
      $annexes     = getAnnexes();
      $preferences = getPreferences($_SESSION['user']['identifiant']);
      break;

    case 'doAjouter':
      insertCalendrier($_POST, $_FILES, $_SESSION['user']['identifiant']);
      break;

    case 'doAjouterAnnexe':
      insertAnnexe($_POST, $_FILES, $_SESSION['user']['identifiant']);
      break;

    case 'doSupprimer':
      deleteCalendrier($_POST);
      break;

    case 'doSupprimerAnnexe':
      deleteAnnexe($_POST);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: calendars.php?year=' . date('Y') . '&action=goConsulter');
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
        $calendrier->setTo_delete(htmlspecialchars($calendrier->getTo_delete()));
        $calendrier->setMonth(htmlspecialchars($calendrier->getMonth()));
        $calendrier->setYear(htmlspecialchars($calendrier->getYear()));
        $calendrier->setTitle(htmlspecialchars($calendrier->getTitle()));
        $calendrier->setCalendar(htmlspecialchars($calendrier->getCalendar()));
      }

      unset($calendrier);

      $preferences->setRef_theme(htmlspecialchars($preferences->getRef_theme()));
      $preferences->setInit_chat(htmlspecialchars($preferences->getInit_chat()));
      $preferences->setCelsius(htmlspecialchars($preferences->getCelsius()));
      $preferences->setView_movie_house(htmlspecialchars($preferences->getView_movie_house()));
      $preferences->setCategories_movie_house(htmlspecialchars($preferences->getCategories_movie_house()));
      $preferences->setView_the_box(htmlspecialchars($preferences->getView_the_box()));
      $preferences->setView_notifications(htmlspecialchars($preferences->getView_notifications()));
      $preferences->setManage_calendars(htmlspecialchars($preferences->getManage_calendars()));
      break;

    case 'goConsulterAnnexes':
      foreach ($annexes as &$annexe)
      {
        $annexe->setTo_delete(htmlspecialchars($annexe->getTo_delete()));
        $annexe->setAnnexe(htmlspecialchars($annexe->getAnnexe()));
        $annexe->setTitle(htmlspecialchars($annexe->getTitle()));
      }

      unset($annexe);
      break;

    case 'doAjouter':
    case 'doAjouterAnnexe':
    case 'doSupprimer':
    case 'doSupprimerAnnexe':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doAjouter':
      header('location: calendars.php?year=' . $_POST['years'] . '&action=goConsulter');
      break;

    case 'doAjouterAnnexe':
      header('location: calendars.php?action=goConsulterAnnexes');
      break;

    case 'doSupprimer':
      header('location: calendars.php?year=' . $_GET['year'] . '&action=goConsulter');
      break;

    case 'doSupprimerAnnexe':
      header('location: calendars.php?action=goConsulterAnnexes');
      break;

    case 'goConsulterAnnexes':
      include_once('vue/vue_annexes.php');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_calendars.php');
      break;
  }
?>
