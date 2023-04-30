<?php
    /****************************
    ******* Event Manager *******
    *****************************
    Fonctionnalités :
    - Consultation des évènements
    ****************************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');
    include_once('../../includes/functions/fonctions_dates.php');
    include_once('../../includes/functions/fonctions_regex.php');

    // Contrôles communs Utilisateur
    controlsUser();

    // Modèle de données
    include_once('modele/metier_eventmanager.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Lecture des données par le modèle
            break;

        default:
            // Contrôle action renseignée URL
            header('location: eventmanager.php?action=goConsulter');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goConsulter':
            break;

        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'goConsulter':
        default:
            include_once('vue/vue_eventmanager.php');
            break;
    }
?>