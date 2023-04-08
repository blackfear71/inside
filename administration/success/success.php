<?php
    /***************************
    **** Gestion des succès ****
    ****************************
    Fonctionnalités :
    - Création des succès
    - Modification des succès
    - Suppression des succès
    - Initialisation des succès
    ***************************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');
    include_once('../../includes/functions/fonctions_regex.php');
    include_once('../../includes/functions/fonctions_images.php');

    // Contrôles communs Administrateur
    controlsAdmin();

    // Modèle de données
    include_once('modele/metier_success.php');
    include_once('modele/controles_success.php');
    include_once('modele/physique_success.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Initialisation de la sauvegarde en session
            initializeSaveSession($_GET['action']);

            // Récupération de la liste des succès
            $listeSuccess = getSuccess();

            // Récupération de la liste des missions
            $listeMissions = getMissions();
            break;

        case 'goModifier':
            // Récupération de la liste des succès
            $listeSuccess = getSuccess();

            // Récupération de la liste des missions
            $listeMissions = getMissions();

            // Initialisation de la sauvegarde en session et récupération erreur
            $erreurSuccess = initializeSaveSession($_GET['action']);

            // Sauvegarde des données saisies en cas d'erreur
            if ($erreurSuccess == true)
                $listeSuccess = initialisationErreurModificationSucces($listeSuccess);
            break;

        case 'doAjouterSucces':
            // Ajout d'un nouveau succès
            insertSuccess($_POST, $_FILES);
            break;

        case 'doSupprimerSucces':
            // Suppression d'un succès
            deleteSuccess($_POST);
            break;

        case 'doModifierSucces':
            // Mise à jour de tous les succès
            $erreurUpdateSuccess = updateSuccess($_POST);
            break;

        case 'doInitialiserSucces':
            // Récupération de la liste des utilisateurs
            $listeUsers = getUsers();

            // Récupération de la liste des succès
            $listeSuccess = getSuccess();

            // Réinitialisation des succès
            initializeSuccess($listeSuccess, $listeUsers);
            break;

        case 'doPurgerSucces':
            // Purge des succès
            purgeSuccess();
            break;

        default:
            // Contrôle action renseignée URL
            header('location: success.php?action=goConsulter');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goConsulter':
        case 'goModifier':
            foreach ($listeSuccess as &$success)
            {
                $success = Success::secureData($success);
            }

            unset($success);

            foreach ($listeMissions as &$mission)
            {
                $mission = Mission::secureData($mission);
            }

            unset($mission);
            break;

        case 'doAjouterSucces':
        case 'doSupprimerSucces':
        case 'doModifierSucces':
        case 'doInitialiserSucces':
        case 'doPurgerSucces':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doModifierSucces':
            if ($erreurUpdateSuccess == true)
                header('location: success.php?error=true&action=goModifier');
            else
                header('location: success.php?action=goConsulter');
            break;

        case 'doAjouterSucces':
        case 'doSupprimerSucces':
        case 'doInitialiserSucces':
        case 'doPurgerSucces':
            header('location: success.php?action=goConsulter');
            break;

        case 'goModifier':
            include_once('vue/vue_update_success.php');
            break;

        case 'goConsulter':
        default:
            include_once('vue/vue_success.php');
            break;
    }
?>