<?php
    /********************************
    *** Informations utilisateurs ***
    *********************************
    Fonctionnalités :
    - Consultation utilisateurs
    - Attribution succès
    ********************************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');
    include_once('../../includes/functions/fonctions_dates.php');

    // Contrôles communs Administrateur
    controlsAdmin();

    // Modèle de données
    include_once('modele/metier_infosusers.php');
    include_once('modele/physique_infosusers.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Récupération de la liste des équipes
            $listeEquipes = getListeEquipes();

            // Récupération de la liste des utilisateurs inscrits
            $listeUsersParEquipe = getUsers();
            break;

        case 'doModifierEquipe':
            // Modification d'une équipe
            updateEquipe($_POST);
            break;

        case 'doSupprimerEquipe':
            // Suppression d'une équipe
            deleteEquipe($_POST);
            break;

        case 'changeBeginnerStatus':
            // Mise à jour du succès "beginning"
            updateSuccesAdmin($_POST, 'beginning');
            break;

        case 'changeDevelopperStatus':
            // Mise à jour du succès "developper"
            updateSuccesAdmin($_POST, 'developper');
            break;

        default:
            // Contrôle action renseignée URL
            header('location: infosusers.php?action=goConsulter');
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

            foreach ($listeUsersParEquipe as &$usersParEquipe)
            {
                foreach ($usersParEquipe as &$user)
                {
                    $user = Profile::secureData($user);
                }

                unset($user);
            }

            unset($usersParEquipe);
            break;

        case 'doModifierEquipe':
        case 'doSupprimerEquipe':
        case 'changeBeginnerStatus':
        case 'changeDevelopperStatus':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doModifierEquipe':
        case 'doSupprimerEquipe':
        case 'changeBeginnerStatus':
        case 'changeDevelopperStatus':
            header('location: infosusers.php?action=goConsulter');
            break;

        case 'goConsulter':
        default:
            include_once('vue/vue_infosusers.php');
            break;
    }
?>