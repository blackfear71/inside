<?php
    /*****************************
    **** Gestion des missions ****
    ******************************
    Fonctionnalités :
    - Création des missions
    - Modification des missions
    - Suppression des missions
    - Consultation des classements
    *****************************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');
    include_once('../../includes/functions/fonctions_dates.php');
    include_once('../../includes/functions/fonctions_regex.php');

    // Contrôles communs Administrateur
    controlsAdmin();

    // Modèle de données
    include_once('modele/metier_missions.php');
    include_once('modele/controles_missions.php');
    include_once('modele/physique_missions.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Initialisation de la sauvegarde en session et récupération erreur
            $erreurMission = initializeSaveSession();

            // Récupération de la liste des missions
            $listeMissions = getMissions();
            break;

        case 'goAjouter':
            // Initialisation de la sauvegarde en session et récupération erreur
            $erreurMission = initializeSaveSession();

            // Initialisation de l'écran d'ajout de mission
            if (isset($erreurMission) AND $erreurMission == true)
            {
                $detailsMission = initialisationErreurMission($_SESSION['save']['new_mission']['post'], NULL);
                unset($erreurMission);
            }
            else
                $detailsMission = initialisationAjoutMission();
            break;

        case 'goModifier':
            // Contrôle si l'id est renseignée et numérique
            if (!isset($_GET['id_mission']) OR !is_numeric($_GET['id_mission']))
                header('location: missions.php?action=goConsulter');
            else
            {
                // Initialisation de la sauvegarde en session et récupération erreur
                $erreurMission = initializeSaveSession();

                // Initialisation de l'écran de modification de mission
                if (isset($erreurMission) AND $erreurMission == true)
                {
                    $detailsMission = initialisationErreurMission($_SESSION['save']['old_mission']['post'], $_GET['id_mission']);
                    unset($erreurMission);
                }
                else
                    $detailsMission = initialisationModificationMission($_GET['id_mission']);

                // Récupération des succès de la mission
                $succesMission = getSuccesMission($detailsMission);

                // Récupération du classement des participants
                $listeParticipantsParEquipes = getParticipantsMission($_GET['id_mission']);

                // Récupération des équipes des participants
                $listeEquipesParticipants = getEquipesParticipantsMission($listeParticipantsParEquipes);
            }
            break;

        case 'doAjouterMission':
            // Ajout d'une mission
            $erreurMission = insertMission($_POST, $_FILES);
            break;

        case 'doModifierMission':
            // Modification d'une mission
            $idMission = updateMission($_POST, $_FILES);
            break;

        case 'doSupprimerMission':
            // Suppression d'une mission
            deleteMission($_POST);
            break;

        default:
            // Contrôle action renseignée URL
            header('location: missions.php?action=goConsulter');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goConsulter':
            foreach ($listeMissions as &$mission)
            {
                $mission = Mission::secureData($mission);
            }

            unset($mission);
            break;

        case 'goModifier':
            $detailsMission = Mission::secureData($detailsMission);

            foreach ($succesMission as &$succes)
            {
                $succes = Success::secureData($succes);
            }

            unset($succes);

            foreach ($listeParticipantsParEquipes as &$participantsParEquipes)
            {
                foreach ($participantsParEquipes as &$participant)
                {
                    $participant = ParticipantMission::secureData($participant);
                }

                unset($participant);
            }

            unset($participantsParEquipes);

            foreach ($listeEquipesParticipants as &$equipe)
            {
                $equipe = Team::secureData($equipe);
            }

            unset($equipe);
            break;

        case 'goAjouter':
            $detailsMission = Mission::secureData($detailsMission);
            break;

        case 'doAjouterMission':
        case 'doModifierMission':
        case 'doSupprimerMission':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doAjouterMission':
            if ($erreurMission == true)
                header('location: missions.php?action=goAjouter');
            else
                header('location: missions.php?action=goConsulter');
            break;

        case 'doModifierMission':
            header('location: missions.php?id_mission=' . $idMission . '&action=goModifier');
            break;

        case 'doSupprimerMission':
            header('location: missions.php?action=goConsulter');
            break;

        case 'goAjouter':
        case 'goModifier':
        case 'goConsulter':
        default:
            include_once('vue/vue_missions.php');
            break;
    }
?>