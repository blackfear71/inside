<?php
    /**************************
    ******** Calendars ********
    ***************************
    Fonctionnalités :
    - Génération de calendrier
    - Ajout de calendrier
    - Ajout d'annexe
    **************************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');
    include_once('../../includes/functions/fonctions_dates.php');
    include_once('../../includes/functions/fonctions_images.php');

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

            // Traitement si utilisateur autorisé ou redirection si non accessible
            if ($isAuthorized == true)
            {
                // Récupération de la liste des mois de l'année
                $listeMois = getMonthsCalendars();

                // Initialisation du générateur de calendriers
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

                // Initialisation ou récupération des paramètres du générateur d'annexes
                if (!isset($annexeParameters) AND !isset($_SESSION['annexe']))
                    $annexeParameters = initializeAnnexe();
                else
                    $annexeParameters = getAnnexeParameters($_SESSION['annexe']);
            }
            else
                header('location: calendars.php?year=' . date('Y') . '&action=goConsulter');
            break;

        case 'doGenererCalendrier':
            // Sauvegarde des paramètres saisis en session
            $nomImage = saveCalendarParameters($_POST, $_FILES);

            // Insertion de l'image dans un dossier temporaire
            if (!empty($nomImage))
                insertImageCalendrier($_FILES, $nomImage);
            break;

        case 'doGenererAnnexe':
            // Sauvegarde des paramètres saisis en session
            $nomImage = saveAnnexeParameters($_POST, $_FILES);

            // Insertion de l'image dans un dossier temporaire
            if (!empty($nomImage))
                insertImageAnnexe($_FILES, $nomImage);
            break;

        case 'doSauvegarderCalendrier':
            // Sauvegarde de l'image générée
            $year = insertCalendrierGenere($_POST, $_SESSION['user']);
            break;

        case 'doSauvegarderAnnexe':
            // Sauvegarde de l'image générée
            insertAnnexeGeneree($_POST, $_SESSION['user']);
            break;

        case 'doAjouterCalendrier':
            // Insertion d'un calendrier
            $year = insertCalendrier($_POST, $_FILES, $_SESSION['user']);
            break;

        case 'doAjouterAnnexe':
            // Insertion d'une annexe
            insertAnnexe($_POST, $_FILES, $_SESSION['user']);
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
            $calendarParameters = CalendarParameters::secureData($calendarParameters);

            if (isset($vacances) AND !empty($vacances))
            {
                foreach ($vacances as &$jourVacances)
                {
                    $jourVacances['nom_vacances'] = htmlspecialchars($jourVacances['nom_vacances']);
                }

                unset($jourVacances);
            }

            $annexeParameters = AnnexeParameters::secureData($annexeParameters);
            break;

        case 'doAjouterCalendrier':
        case 'doAjouterAnnexe':
        case 'doGenererCalendrier':
        case 'doGenererAnnexe':
        case 'doSauvegarderCalendrier':
        case 'doSauvegarderAnnexe':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doAjouterCalendrier':
        case 'doSauvegarderCalendrier':
            header('location: calendars.php?year=' . $year . '&action=goConsulter');
            break;

        case 'doAjouterAnnexe':
        case 'doSauvegarderAnnexe':
            header('location: calendars.php?action=goConsulterAnnexes');
            break;

        case 'doGenererCalendrier':
            header('location: calendars_generator.php?action=goConsulter');
            break;

        case 'doGenererAnnexe':
            header('location: calendars_generator.php?action=goConsulter&anchor=scrollGenerator');
            break;

        case 'goConsulter':
        default:
            include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_calendars_generator.php');
            break;
    }
?>