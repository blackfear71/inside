<?php
    /*******************************
    ***** Les Petits Pédestres *****
    ********************************
    Fonctionnalités :
    - Détails du parcours
    - Modification du parcours
    - Suppression du parcours
    - Ajout des participations
    - Modification des participations
    - Suppression des participations
    ********************************/
    
    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');
    include_once('../../includes/functions/fonctions_dates.php');
    include_once('../../includes/functions/fonctions_images.php');
    include_once('../../includes/functions/fonctions_regex.php');
    
    // Contrôles communs Utilisateur
    controlsUser();

    // Modèle de données
    include_once('modele/metier_petitspedestres.php');
    include_once('modele/controles_petitspedestres.php');
    include_once('modele/physique_petitspedestres.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Contrôle si l'id est renseignée et numérique
            if (!isset($_GET['id_parcours']) OR !is_numeric($_GET['id_parcours']))
                header('location: petitspedestres.php?action=goConsulter');
            else
            {
                // Initialisation de la sauvegarde en session
                initializeSaveSession();

                // Vérification parcours disponible
                $parcoursExistant = isParcoursDisponible($_GET['id_parcours'], $_SESSION['user']['equipe']);

                if ($parcoursExistant == true)
                {
                    // Récupération des détails du parcours
                    $detailsParcours = getDetailsParcours($_GET['id_parcours'], $_SESSION['user']);

                    // Récupération de la liste des utilisateurs
                    $listeUsersDetails = getUsersDetailsParcours($_GET['id_parcours'], $_SESSION['user']['equipe']);

                    // Récupération des participations au parcours
                    $listeParticipationsParDate = getParticipantsParcours($_GET['id_parcours'], $listeUsersDetails);
                }
            }
            break;

        case 'doModifierParcours':
            // Modification d'un parcours
            $idParcours = updateParcours($_POST, $_FILES, $_SESSION['user']);
            break;

        case 'doSupprimerParcours':
            // Suppression d'un parcours
            deleteParcours($_POST, $_SESSION['user']['identifiant']);
            break;

        case 'doAjouterParticipation':
            // Insertion d'une participation
            $dateParticipation = insertParticipation($_POST, $_SESSION['user'], false);
            break;

        case 'doAjouterParticipationMobile':
            // Insertion d'une participation
            $dateParticipation = insertParticipation($_POST, $_SESSION['user'], true);
            break;

        case 'doModifierParticipation':
            // Modification d'une participation
            $dateParticipation = updateParticipation($_POST, $_SESSION['user'], false);
            break;

        case 'doModifierParticipationMobile':
            // Modification d'une participation
            $dateParticipation = updateParticipation($_POST, $_SESSION['user'], true);
            break;

        case 'doSupprimerParticipation':
            // Suppression d'une participation
            $idParcours = deleteParticipation($_POST, $_SESSION['user']['identifiant']);
            break;

        default:
            // Contrôle action renseignée URL
            header('location: details.php?id_parcours=' . $_GET['id_parcours'] . '&action=goConsulter');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goConsulter':
            if ($parcoursExistant == true)
            {
                $detailsParcours = Parcours::secureData($detailsParcours);

                foreach ($listeUsersDetails as &$user)
                {
                    $user['pseudo'] = htmlspecialchars($user['pseudo']);
                    $user['avatar'] = htmlspecialchars($user['avatar']);
                }

                unset($user);

                foreach ($listeParticipationsParDate as &$participationsParDate)
                {
                    foreach ($participationsParDate as &$participation)
                    {
                        $participation = ParticipationCourse::secureData($participation);
                    }

                    unset($participation);
                }

                unset($participationsParDate);

                // Conversion JSON
                $detailsParcoursJson     = json_encode(convertForJsonDetailsParcours($detailsParcours));
                $listeParticipationsJson = json_encode(convertForJsonListeParticipations($listeParticipationsParDate));
            }
            break;

        case 'doModifierParcours':
        case 'doSupprimerParcours':
        case 'doAjouterParticipation':
        case 'doAjouterParticipationMobile':
        case 'doModifierParticipation':
        case 'doModifierParticipationMobile':
        case 'doSupprimerParticipation':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doModifierParcours':
        case 'doSupprimerParticipation':
            header('location: details.php?id_parcours=' . $idParcours . '&action=goConsulter');
            break;

        case 'doSupprimerParcours':
            header('location: petitspedestres.php?action=goConsulter');
            break;

        case 'doAjouterParticipation':
        case 'doAjouterParticipationMobile':
        case 'doModifierParticipation':
        case 'doModifierParticipationMobile':
            if (!empty($dateParticipation))
                header('location: details.php?id_parcours=' . $_GET['id_parcours'] . '&action=goConsulter&anchor=' . $dateParticipation);
            else
                header('location: details.php?id_parcours=' . $_GET['id_parcours'] . '&action=goConsulter');
            break;

        case 'goConsulter':
        default:
            include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_details.php');
            break;
    }
?>