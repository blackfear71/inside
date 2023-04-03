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
                    $detailsParcours = getDetailsParcours($_GET['id_parcours'], $_SESSION['user']['identifiant']);

                    // Récupération de la liste des utilisateurs
                    $listeUsersDetails = getUsersDetailsParcours($_GET['id_parcours'], $_SESSION['user']['equipe']);

                    // Récupération des participations au parcours
                    $listeParticipantsParDate = getParticipantsParcours($_GET['id_parcours'], $listeUsersDetails);
                }
            }
            break;

        case 'doModifier':
            // Modification d'un parcours
            $idParcours = updateParcours($_POST, $_FILES, $_SESSION['user']);
            break;

        case 'doSupprimer':
            // Suppression d'un parcours
            deleteParcours($_POST, $_SESSION['user']['identifiant']);
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

                foreach ($listeParticipantsParDate as &$participantsParDate)
                {
                    foreach ($participantsParDate as &$participant)
                    {
                        $participant = ParticipationCourse::secureData($participant);
                    }

                    unset($participant);
                }

                unset($participantsParDate);

                // Conversion JSON
                $detailsParcoursJson = json_encode(convertForJsonDetailsParcours($detailsParcours));
            }
            break;

        case 'doModifier':
        case 'doSupprimer':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doModifier':
            header('location: details.php?id_parcours=' . $idParcours . '&action=goConsulter');
            break;

        case 'doSupprimer':
            header('location: petitspedestres.php?action=goConsulter');
            break;

        case 'goConsulter':
        default:
            include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_details.php');
            break;
    }
?>