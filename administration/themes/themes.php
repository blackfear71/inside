<?php
    /*************************
    *** Gestion des thèmes ***
    **************************
    Fonctionnalités :
    - Ajout des thèmes
    - Modification des thèmes
    - Suppression des thèmes
    *************************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');
    include_once('../../includes/functions/fonctions_dates.php');

    // Contrôles communs Administrateur
    controlsAdmin();

    // Modèle de données
    include_once('modele/metier_themes.php');
    include_once('modele/controles_themes.php');
    include_once('modele/physique_themes.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Initialisation de la sauvegarde en session
            initializeSaveSession();

            // Récupération des thèmes utilisateurs
            $themesUsers = getThemes('U');

            // Récupération des thèmes de missions
            $themesMissions = getThemes('M');
            break;

        case 'doAjouterTheme':
            // Ajout d'un nouveau thème
            $idTheme = insertTheme($_POST, $_FILES);
            break;

        case 'doModifierTheme':
            // Mise à jour d'un thème
            $idTheme = updateTheme($_POST);
            break;

        case 'doSupprimerTheme':
            // Suppression d'un thème
            deleteTheme($_POST);
            break;

        default:
            // Contrôle action renseignée URL
            header('location: themes.php?action=goConsulter');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goConsulter':
            foreach ($themesUsers as &$themeUsers)
            {
                $themeUsers = Theme::secureData($themeUsers);
            }

            unset($themeUsers);

            foreach ($themesMissions as &$themeMission)
            {
                $themeMission = Theme::secureData($themeMission);
            }

            unset($themeMission);
            break;

        case 'doAjouterTheme':
        case 'doModifierTheme':
        case 'doSupprimerTheme':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doAjouterTheme':
        case 'doModifierTheme':
            if (!empty($idTheme))
                header('location: themes.php?action=goConsulter&anchorTheme=' . $idTheme);
            else
                header('location: themes.php?action=goConsulter');
            break;

        case 'doSupprimerTheme':
            header('location: themes.php?action=goConsulter');
            break;

        case 'goConsulter':
        default:
            include_once('vue/vue_themes.php');
            break;
    }
?>