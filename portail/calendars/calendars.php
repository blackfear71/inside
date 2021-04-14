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
  include_once('../../includes/functions/physique_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_calendars.php');
  include_once('modele/controles_calendars.php');
  include_once('modele/physique_calendars.php');

  // Appels métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Contrôle si l'année est renseignée et numérique
      if (!isset($_GET['year']) OR !is_numeric($_GET['year']))
        header('location: calendars.php?year=' . date('Y') . '&action=goConsulter');
      else
      {
        // Vérification année existante
        $anneeExistante = controlYear($_GET['year']);

        // Récupération des onglets (années)
        $onglets = getOnglets();

        // Récupération de la liste des calendriers
        $calendriers = getCalendars($_GET['year']);

        // Récupération des préférences de l'utilisateur
        $preferences = getPreferences($_SESSION['user']['identifiant']);
      }
      break;

    case 'goConsulterAnnexes':
      // Récupération des onglets (années)
      $onglets = getOnglets();

      // Récupération de la liste des annexes
      $annexes = getAnnexes();

      // Récupération des préférences de l'utilisateur
      $preferences = getPreferences($_SESSION['user']['identifiant']);
      break;

    case 'doAjouter':
      // Insertion d'un calendrier
      insertCalendrier($_POST, $_FILES, $_SESSION['user']['identifiant']);
      break;

    case 'doAjouterAnnexe':
      // Insertion d'une annexe
      insertAnnexe($_POST, $_FILES, $_SESSION['user']['identifiant']);
      break;

    case 'doSupprimer':
      // Suppression d'un calendrier
      deleteCalendrier($_POST);
      break;

    case 'doSupprimerAnnexe':
      // Suppression d'une annexe
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
      foreach ($onglets as &$year)
      {
        $year = htmlspecialchars($year);
      }

      unset($year);

      foreach ($annexes as $annexe)
      {
        Annexe::secureData($annexe);
      }

      Preferences::secureData($preferences);
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
    case 'doSupprimerAnnexe':
      header('location: calendars.php?action=goConsulterAnnexes');
      break;

    case 'doSupprimer':
      header('location: calendars.php?year=' . $_GET['year'] . '&action=goConsulter');
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
