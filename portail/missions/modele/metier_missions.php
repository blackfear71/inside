<?php
    include_once('../../includes/classes/missions.php');
    include_once('../../includes/classes/profile.php');
    include_once('../../includes/classes/success.php');

    // METIER : Lecture des missions
    // RETOUR : Liste des missions
    function getMissions()
    {
        // Récupération de la liste des missions
        $listeMissions = physiqueMissions();

        // Tri des missions sur statut (V : à venir, C : en cours, A : ancienne) puis date
        if (!empty($listeMissions))
        {
            foreach ($listeMissions as $triMissions)
            {
                $triStatut[]    = $triMissions->getStatut();
                $triDateDebut[] = $triMissions->getDate_deb();
            }

            array_multisort($triStatut, SORT_DESC, $triDateDebut, SORT_DESC, $listeMissions);
        }

        // Retour
        return $listeMissions;
    }

    // METIER : Validation d'un bouton de mission en cours
    // RETOUR : Aucun
    function validateMission($post, $sessionUser, $mission, $pageCourante)
    {
        // Récupération des données
        $identifiant      = $sessionUser['identifiant'];
        $equipe           = $sessionUser['equipe'];
        $referenceMission = $post['ref_mission'];
        $cleMission       = $post['key_mission'];

        // Traitement de validation du bouton de mission
        if (!empty($mission))
        {
            if (isset($pageCourante) AND strpos($pageCourante, $mission[$referenceMission]['page']) !== false)
            {
                // Vérification mission du jour commencée par l'utilisateur
                $missionCommencee = physiqueMissionCommencee($mission[$referenceMission]['id_mission'], $identifiant);

                // Mise à jour ou insertion de la mission pour l'utilisateur
                if ($missionCommencee == true)
                {
                    // Récupération de l'avancement d'une mission
                    $avancement = physiqueAvancementMission($mission[$referenceMission]['id_mission'], $identifiant);

                    // Modification de l'enregistrement en base
                    $avancementUser = $avancement['daily'] + 1;

                    physiqueUpdateMissionUser($mission[$referenceMission]['id_mission'], $identifiant, $avancementUser);
                }
                else
                {
                    // Insertion de l'enregistrement en base
                    $missionUser = array(
                        'id_mission'   => $mission[$referenceMission]['id_mission'],
                        'team'         => $equipe,
                        'identifiant'  => $identifiant,
                        'avancement'   => 1,
                        'date_mission' => date('Ymd')
                    );

                    physiqueInsertionMissionUser($missionUser);
                }

                // Génération succès mission
                insertOrUpdateSuccesMission($mission[$referenceMission]['reference'], $identifiant);

                // Suppression du bouton correspondant pour ne pas cliquer à nouveau dessus
                unset($mission[$referenceMission]);

                // Mise à jour de la session
                $_SESSION['missions'][$cleMission] = $mission;
            }

            // Si la mission est terminée
            if (empty($mission))
            {
                // Ajout expérience
                insertExperience($identifiant, 'all_missions');

                // Message d'alerte
                $_SESSION['alerts']['mission_achieved'] = true;
            }
        }
    }

    // METIER : Vérification mission existante
    // RETOUR : Booléen
    function isMissionDisponible($idMission)
    {
        // Contrôle mission disponible
        $missionDisponible = controlMissionDisponible($idMission);

        // Retour
        return $missionDisponible;
    }

    // METIER : Lecture des détails d'une mission
    // RETOUR : Objet mission
    function getMission($idMission)
    {
        // Récupération de la mission
        $mission = physiqueMission($idMission);

        // Retour
        return $mission;
    }

    // METIER : Récupération des participants d'une mission
    // RETOUR : Liste des participants
    function getParticipants($idMission, $equipe)
    {
        // Récupération de la liste des participants d'une mission
        $listeParticipants = physiqueParticipantsMission($idMission, $equipe);

        // Retour
        return $listeParticipants;
    }

    // METIER : Lecture de l'avancement de l'utilisateur (quotidien et évènement)
    // RETOUR : Tableau des pourcentages d'avancement
    function getMissionUser($detailsMission, $idMission, $identifiant)
    {
        // Récupération de l'avancement d'une mission
        $avancement = physiqueAvancementMission($idMission, $identifiant);

        // Calcul de l'objectif total en fonction du nombre de jours de la mission
        $nombreJoursMission = ecartDatesMission($detailsMission->getDate_deb(), $detailsMission->getDate_fin());
        $objectifTotal      = $detailsMission->getObjectif() * $nombreJoursMission;

        // Calcul de l'avancement en pourcentages
        $avancement['daily_percent'] = ($avancement['daily'] * 100) / $detailsMission->getObjectif();
        $avancement['event_percent'] = ($avancement['event'] * 100) / $objectifTotal;

        // Retour
        return $avancement;
    }

    // METIER : Lecture des succès associés à une mission pour l'utilisateur
    // RETOUR : Tableau des succès d'une mission
    function getSuccesMission($mission, $identifiant)
    {
        // Récupération des succès liés à la mission
        $listeSuccesMission = physiqueSuccesMissionUser($mission->getReference(), $identifiant);

        // Retour
        return $listeSuccesMission;
    }

    // METIER : Lecture du classement des utilisateurs d'une mission
    // RETOUR : Tableau de classement d'une mission
    function getRankingMission($idMission, $participants)
    {
        // Initialisations
        $rankingUsers = array();

        // Récupération de l'avancement de chaque participant
        foreach ($participants as $user)
        {
            $avancementUser = physiqueAvancementMission($idMission, $user->getIdentifiant());

            // Génération d'un objet ParticipantMission
            $rankUser = new ParticipantMission();

            $rankUser->setIdentifiant($user->getIdentifiant());
            $rankUser->setTeam($user->getTeam());
            $rankUser->setPseudo($user->getPseudo());
            $rankUser->setAvatar($user->getAvatar());
            $rankUser->setTotal($avancementUser['event']);
            $rankUser->setRank(0);

            // On ajoute la ligne au tableau
            array_push($rankingUsers, $rankUser);
        }

        // Tri et affectation du rang
        if (!empty($rankingUsers))
        {
            // Tri sur avancement puis identifiant
            foreach ($rankingUsers as $rankUser)
            {
                $triRank[]  = $rankUser->getTotal();
                $triAlpha[] = $rankUser->getIdentifiant();
            }

            if (!empty($rankingUsers))
                array_multisort($triRank, SORT_DESC, $triAlpha, SORT_ASC, $rankingUsers);

            // Affectation du rang
            $prevTotal   = $rankingUsers[0]->getTotal();
            $currentRank = 1;

            foreach ($rankingUsers as $rankUser)
            {
                $currentTotal = $rankUser->getTotal();

                if ($currentTotal != $prevTotal)
                {
                    $currentRank += 1;
                    $prevTotal = $rankUser->getTotal();
                }

                $rankUser->setRank($currentRank);
            }
        }

        // Retour
        return $rankingUsers;
    }

    // METIER : Lecture de la participation de l'utilisateur si changement d'équipe
    // RETOUR : Booléen
    function getParticipationNoRankingMission($idMission, $sessionUser)
    {
        // Récupération des données
        $identifiant = $sessionUser['identifiant'];
        $equipe      = $sessionUser['equipe'];

        // Vérification si l'utilisateur a participé en étant dans un autre équipe
        $participationUser = physiqueParticipationUser($idMission, $equipe, $identifiant);

        // Retour
        return $participationUser;
    }
?>