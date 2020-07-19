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

      foreach ($calendriers as $calendrier)
      {
        Calendrier::secureData($calendrier);
      }

      Preferences::secureData($preferences);
      break;

    case 'goConsulterAnnexes':
      foreach ($annexes as $annexe)
      {
        Annexe::secureData($annexe);
      }
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
