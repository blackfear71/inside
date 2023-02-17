<?php
    /*******************************
    *** Gestion des utilisateurs ***
    ********************************
    Fonctionnalités :
    - Réinitialisation mot de passe
    - Inscriptions
    - Désinscriptions
    - Consultation des statistiques
    *******************************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');
    include_once('../../includes/functions/fonctions_regex.php');

    // Contrôles communs Administrateur
    controlsAdmin();

    // Modèle de données
    include_once('modele/metier_manageusers.php');
    include_once('modele/controles_manageusers.php');
    include_once('modele/physique_manageusers.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Initialisation de la sauvegarde en session
            initializeSaveSession();

            // Récupération de la liste des équipes
            $listeEquipes = getListeEquipes();

            // Récupération des utilisateurs inscrits et désinscrits
            $listeUsersParEquipe = getUsers();
            $listeUsersDes       = getUsersDes($listeUsersParEquipe);

            // Récupération des statistiques par catégories et des statistiques de demandes et publications
            $tableauStatistiquesIns = getStatistiquesInscrits($listeUsersParEquipe);
            $tableauStatistiquesDes = getStatistiquesDesinscrits($listeUsersDes);

            // Récupération des totaux
            $totalStatistiques = getTotalStatistiques($tableauStatistiquesIns, $tableauStatistiquesDes);
            break;

        case 'doChangerMdp':
            // Réinitialisation du mot de passe
            setNewPassword($_POST);
            break;

        case 'doAnnulerMdp':
            // Annulation de la réinitialisation du mot de passe
            resetOldPassword($_POST);
            break;

        case 'doAccepterEquipe':
            // Validation changement d'équipe
            acceptEquipe($_POST, true);
            break;

        case 'doRefuserEquipe':
            // Annulation changement d'équipe
            declineEquipe($_POST);
            break;

        case 'doAccepterInscription':
            // Validation de l'inscription
            acceptInscription($_POST);
            break;

        case 'doRefuserInscription':
            // Annulation de l'inscription
            declineInscription($_POST);
            break;

        case 'doAccepterDesinscription':
            // Validation de la désinscription
            acceptDesinscription($_POST);
            break;

        case 'doRefuserDesinscription':
            // Annulation de la désinscription
            updateStatusUser($_POST, 'U');
            break;

        case 'doForcerDesinscription':
            // Forçage de la désinscription
            updateStatusUser($_POST, 'D');
            break;

        default:
            // Contrôle action renseignée URL
            header('location: manageusers.php?action=goConsulter');
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

            foreach ($listeUsersDes as &$userDes)
            {
                $userDes = htmlspecialchars($userDes);
            }

            unset($userDes);

            foreach ($tableauStatistiquesIns as &$statistiquesIns)
            {
                $statistiquesIns = StatistiquesAdmin::secureData($statistiquesIns);
            }

            unset($statistiquesIns);

            foreach ($tableauStatistiquesDes as &$statistiquesDes)
            {
                $statistiquesDes = StatistiquesAdmin::secureData($statistiquesDes);
            }

            unset($statistiquesDes);

            $totalStatistiques = TotalStatistiquesAdmin::secureData($totalStatistiques);

            // Conversion JSON
            $tableauStatistiquesInsJson = json_encode(convertForJsonStatistiques($tableauStatistiquesIns));
            $tableauStatistiquesDesJson = json_encode(convertForJsonStatistiques($tableauStatistiquesDes));
            break;

        case 'doChangerMdp':
        case 'doAnnulerMdp':
        case 'doAccepterEquipe':
        case 'doRefuserEquipe':
        case 'doAccepterInscription':
        case 'doRefuserInscription':
        case 'doAccepterDesinscription':
        case 'doRefuserDesinscription':
        case 'doForcerDesinscription':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doChangerMdp':
        case 'doAnnulerMdp':
        case 'doAccepterEquipe':
        case 'doRefuserEquipe':
        case 'doAccepterInscription':
        case 'doRefuserInscription':
        case 'doAccepterDesinscription':
        case 'doRefuserDesinscription':
        case 'doForcerDesinscription':
            header('location: manageusers.php?action=goConsulter');
            break;

        case 'goConsulter':
        default:
            include_once('vue/vue_manageusers.php');
            break;
    }
?>