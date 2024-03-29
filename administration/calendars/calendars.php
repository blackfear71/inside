<?php
    /********************************
    **** Gestion des calendriers ****
    *********************************
    Fonctionnalités :
    - Autorisations d'édition
    - Saisie des périodes de vacances
    - Suppression des calendriers
    - Suppression des annexes
    ********************************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');

    // Contrôles communs Administrateur
    controlsAdmin();

    // Modèle de données
    include_once('modele/metier_calendars.php');
    include_once('modele/physique_calendars.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Récupération de la liste des équipes
            $listeEquipes = getListeEquipes();

            // Récupération des autorisations de gestion des calendriers
            $listeAutorisationsParEquipe = getAutorisationsCalendars();

            // Récupération des périodes de vacances disponibles
            $periodesVacances = getPeriodesVacances();

            // Récupération alerte période de vacances à saisir
            $periodesPresentes = getPeriodesVacancesPresentes();

            // Initialisation des saisies des périodes de vacances
            $saisiesVacances = getSaisieVacances();

            // Récupération de la liste des mois
            $listeMois = getMonths();

            // Récupération des calendriers à supprimer
            $listeSuppression = getCalendarsToDelete($listeMois);

            // Récupération des annexes à supprimer
            $listeSuppressionAnnexes = getAnnexesToDelete();
            break;

        case 'doModifierAutorisations':
            // Récupération de la liste des utilisateurs
            $listeUsers = getUsers();

            // Mise à jour des autorisations de gestion des calendriers
            updateAutorisations($_POST, $listeUsers);
            break;

        case 'doAjouterVacances':
            insertVacancesCSV($_POST);
            break;

        case 'doSupprimerVacances':
            deleteVacancesCSV($_POST);
            break;

        case 'doSupprimerCalendrier':
            // Suppression d'un calendrier
            deleteCalendrier($_POST);
            break;

        case 'doSupprimerAnnexe':
            // Suppression d'une annexe
            deleteAnnexe($_POST);
            break;

        case 'doReinitialiserCalendrier':
            // Annulation de la demande de suppression d'un calendrier
            resetCalendrier($_POST);
            break;

        case 'doReinitialiserAnnexe':
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
            foreach ($listeEquipes as &$equipe)
            {
                $equipe = Team::secureData($equipe);
            }

            unset($equipe);

            foreach ($listeAutorisationsParEquipe as &$equipeAutorisations)
            {
                foreach ($equipeAutorisations as &$autorisation)
                {
                    $autorisation = AutorisationCalendriers::secureData($autorisation);
                }

                unset($autorisation);
            }

            unset($equipeAutorisations);

            foreach ($periodesVacances as &$periodeVacances)
            {
                $periodeVacances = htmlspecialchars($periodeVacances);
            }

            unset($periodeVacances);

            foreach ($saisiesVacances as &$saisieVacances)
            {
                $saisieVacances['nom'] = htmlspecialchars($saisieVacances['nom']);
            }

            unset($saisieVacances);

            foreach ($listeSuppression as &$calendar)
            {
                $calendar = Calendrier::secureData($calendar);
            }

            unset($calendar);

            foreach ($listeSuppressionAnnexes as &$annexe)
            {
                $annexe = Annexe::secureData($annexe);
            }

            unset($annexe);
            break;

        case 'doModifierAutorisations':
        case 'doAjouterVacances':
        case 'doSupprimerVacances':
        case 'doSupprimerCalendrier':
        case 'doSupprimerAnnexe':
        case 'doReinitialiserCalendrier':
        case 'doReinitialiserAnnexe':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doModifierAutorisations':
        case 'doAjouterVacances':
        case 'doSupprimerVacances':
        case 'doSupprimerCalendrier':
        case 'doSupprimerAnnexe':
        case 'doReinitialiserCalendrier':
        case 'doReinitialiserAnnexe':
            header('location: calendars.php?action=goConsulter');
            break;

        case 'goConsulter':
        default:
            include_once('vue/vue_calendars.php');
            break;
    }
?>