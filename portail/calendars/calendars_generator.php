<?php
  /**************************
  ******** Calendars ********
  ***************************
  Fonctionnalités :
  - Génération de calendrier
  - Ajout de calendrier
  - Ajout d'annexe
  **************************/

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
      // Récupération des préférences de l'utilisateur
      $preferences = getPreferences($_SESSION['user']['identifiant']);

      // Vérification utilisateur autorisé
      $isAuthorized = getAutorisationUser($preferences);

      // Traitement si utilisateur autorisé
      if ($isAuthorized == true)
      {
        // Récupération de la liste des mois de l'année
        $listeMois = getMonthsCalendars();

        // Initialisation de l'écran
        if (!isset($calendarParameters) AND !isset($_SESSION['calendar']))
          $calendarParameters = initializeCalendar();
        else
        {
          // Récupération des paramètres saisis
          $calendarParameters = getCalendarParameters($_SESSION['calendar']);

          // Détermination des données du calendrier
          $donneesCalendrier = getCalendarDatas($calendarParameters);

          // Récupération des dates des vacances si disponibles
          $vacances = getVacances($calendarParameters);
        }
      }
      break;

    case 'doGenerer':
      // Sauvegarde des paramètres saisis en session
      $nomImage = saveCalendarParameters($_POST, $_FILES);

      // Insertion de l'image dans un dossier temporaire
      if (!empty($nomImage))
        insertImageCalendrier($_POST, $_FILES, $nomImage);
      break;

    case 'doSauvegarder':
      // Sauvegarde de l'image générée
      $year = insertCalendrierGenere($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doAjouter':
      // Insertion d'un calendrier
      $year = insertCalendrier($_POST, $_FILES, $_SESSION['user']['identifiant']);
      break;

    case 'doAjouterAnnexe':
      // Insertion d'une annexe
      insertAnnexe($_POST, $_FILES, $_SESSION['user']['identifiant']);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: calendars_generator.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      CalendarParameters::secureData($calendarParameters);

      if (isset($vacances) AND !empty($vacances))
      {
        foreach ($vacances as &$jourVacances)
        {
          $jourVacances['nom_vacances'] = htmlspecialchars($jourVacances['nom_vacances']);
        }

        unset($jourVacances);
      }
      break;

    case 'doAjouter':
    case 'doAjouterAnnexe':
    case 'doGenerer':
    case 'doSauvegarder':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doAjouter':
    case 'doSauvegarder':
      header('location: calendars.php?year=' . $year . '&action=goConsulter');
      break;

    case 'doAjouterAnnexe':
      header('location: calendars.php?action=goConsulterAnnexes');
      break;

    case 'doGenerer':
      header('location: calendars_generator.php?action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_calendars_generator.php');
      break;
  }
?>
