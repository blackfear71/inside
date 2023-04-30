<?php
    /******************************
    ********** Calendars **********
    *******************************
    Fonctionnalités :
    - Consultation des calendriers
    - Suppression des calendriers
    - Consultation des annexes
    - Suppression des annexes
    ******************************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');
    include_once('../../includes/functions/fonctions_dates.php');
    include_once('../../includes/functions/fonctions_regex.php');

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
                $anneeExistante = controlYear($_GET['year'], $_SESSION['user']['equipe']);

                // Récupération des onglets (années)
                $onglets = getOnglets($_SESSION['user']['equipe']);

                // Récupération de la liste des mois de l'année
                $listeMois = getMonthsCalendars();

                // Récupération de la liste des calendriers
                $calendriers = getCalendars($_GET['year'], $_SESSION['user']['equipe']);

                // Récupération des préférences de l'utilisateur
                $preferences = getPreferences($_SESSION['user']['identifiant']);
            }
            break;

        case 'goConsulterAnnexes':
            // Récupération des onglets (années)
            $onglets = getOnglets($_SESSION['user']['equipe']);

            // Récupération de la liste des annexes
            $annexes = getAnnexes($_SESSION['user']['equipe']);

            // Récupération des préférences de l'utilisateur
            $preferences = getPreferences($_SESSION['user']['identifiant']);
            break;

        case 'doSupprimerCalendrier':
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
            foreach ($onglets as &$annee)
            {
                $annee = htmlspecialchars($annee);
            }

            unset($annee);

            foreach ($listeMois as &$mois)
            {
                $mois = htmlspecialchars($mois);
            }

            unset($mois);

            foreach ($calendriers as &$calendrier)
            {
                $calendrier = Calendrier::secureData($calendrier);
            }

            unset($calendrier);
            
            $preferences = Preferences::secureData($preferences);
            break;

        case 'goConsulterAnnexes':
            foreach ($onglets as &$annee)
            {
                $annee = htmlspecialchars($annee);
            }

            unset($annee);

            foreach ($annexes as &$annexe)
            {
                $annexe = Annexe::secureData($annexe);
            }

            unset($annexe);

            $preferences = Preferences::secureData($preferences);
            break;

        case 'doSupprimerCalendrier':
        case 'doSupprimerAnnexe':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doSupprimerAnnexe':
            header('location: calendars.php?action=goConsulterAnnexes');
            break;

        case 'doSupprimerCalendrier':
            header('location: calendars.php?year=' . $_GET['year'] . '&action=goConsulter');
            break;

        case 'goConsulterAnnexes':
        case 'goConsulter':
        default:
            include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_calendars.php');
            break;
    }
?>