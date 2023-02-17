<?php
    /*************************
    *** Missions : Insider ***
    **************************
    Fonctionnalités :
    - Détails de la mission
    *************************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');
    include_once('../../includes/functions/fonctions_dates.php');
    include_once('../../includes/functions/fonctions_regex.php');

    // Contrôles communs Utilisateur
    controlsUser();

    // Modèle de données
    include_once('modele/metier_missions.php');
    include_once('modele/controles_missions.php');
    include_once('modele/physique_missions.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Contrôle si l'id est renseignée et numérique
            if (!isset($_GET['id_mission']) OR !is_numeric($_GET['id_mission']))
                header('location: missions.php?action=goConsulter');
            else
            {
                // Vérification que la mission existe et est disponible
                $missionExistante = isMissionDisponible($_GET['id_mission']);

                if ($missionExistante == true)
                {
                    // Récupération des détails de la mission
                    $detailsMission = getMission($_GET['id_mission']);

                    // Récupération des participants
                    $participants = getParticipants($_GET['id_mission'], $_SESSION['user']['equipe']);

                    // Récupération des pourcentages d'avancement de l'utilisateur (quotidien et évènement)
                    $missionUser = getMissionUser($detailsMission, $_GET['id_mission'], $_SESSION['user']['identifiant']);

                    // Récupération des résultats
                    if (date('Ymd') > $detailsMission->getDate_fin())
                    {
                        // Récupération des succès de la mission
                        $succesMission = getSuccesMission($detailsMission, $_SESSION['user']['identifiant']);

                        // Récupération du classement des participants
                        $ranking = getRankingMission($_GET['id_mission'], $participants);

                        // Récupération de l'utilisateur hors classement (suite à un changement d'équipe)
                        if (!isset($participants[$_SESSION['user']['identifiant']]) OR empty($participants[$_SESSION['user']['identifiant']]))
                            $participationUserNoRanking = getParticipationNoRankingMission($_GET['id_mission'], $_SESSION['user']);
                    }
                }
            }
            break;

        default:
            // Contrôle action renseignée URL
            header('location: details.php?id_mission=' . $_GET['id_mission'] . '&action=goConsulter');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goConsulter':
            if ($missionExistante == true)
            {
                Mission::secureData($detailsMission);

                foreach ($participants as $participant)
                {
                    Profile::secureData($participant);
                }

                $missionUser['daily']         = htmlspecialchars($missionUser['daily']);
                $missionUser['event']         = htmlspecialchars($missionUser['event']);
                $missionUser['daily_percent'] = htmlspecialchars($missionUser['daily_percent']);
                $missionUser['event_percent'] = htmlspecialchars($missionUser['event_percent']);

                if (date('Ymd') > $detailsMission->getDate_fin())
                {
                    foreach ($succesMission as &$succes)
                    {
                        $succes = Success::secureData($succes);
                    }
                    
                    unset($succes);

                    foreach ($ranking as $rankUser)
                    {
                        ParticipantMission::secureData($rankUser);
                    }
                }
            }
            break;

        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'goConsulter':
        default:
            include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_details.php');
            break;
    }
?>