<?php
    include_once('../../includes/classes/missions.php');
    include_once('../../includes/classes/profile.php');

    // METIER : Initialise les données de sauvegarde en session
    // RETOUR : Erreur
    function initializeSaveSession()
    {
        // Initialisations
        $erreurMission = false;

        // On supprime la session s'il n'y a pas d'erreur
        if ((!isset($_SESSION['alerts']['already_ref_mission'])   OR $_SESSION['alerts']['already_ref_mission']   != true)
        AND (!isset($_SESSION['alerts']['objective_not_numeric']) OR $_SESSION['alerts']['objective_not_numeric'] != true)
        AND (!isset($_SESSION['alerts']['wrong_date'])            OR $_SESSION['alerts']['wrong_date']            != true)
        AND (!isset($_SESSION['alerts']['date_less'])             OR $_SESSION['alerts']['date_less']             != true)
        AND (!isset($_SESSION['alerts']['missing_mission_file'])  OR $_SESSION['alerts']['missing_mission_file']  != true)
        AND (!isset($_SESSION['alerts']['file_too_big'])          OR $_SESSION['alerts']['file_too_big']          != true)
        AND (!isset($_SESSION['alerts']['temp_not_found'])        OR $_SESSION['alerts']['temp_not_found']        != true)
        AND (!isset($_SESSION['alerts']['wrong_file_type'])       OR $_SESSION['alerts']['wrong_file_type']       != true)
        AND (!isset($_SESSION['alerts']['wrong_file'])            OR $_SESSION['alerts']['wrong_file']            != true))
            unset($_SESSION['save']);
        else
            $erreurMission = true;

        // Retour
        return $erreurMission;
    }

    // METIER : Récupération des missions
    // RETOUR : Objets mission
    function getMissions()
    {
        // Récupération de la liste des missions
        $listeMissions = physiqueListeMissions();

        // Tri des missions sur statut (V : à venir, C : en cours, A : ancienne) puis date
        if (!empty($listeMissions))
        {
            // Récupération du tri sur le statut et la date de début
            foreach ($listeMissions as $triMissions)
            {
                $triStatut[]    = $triMissions->getStatut();
                $triDateDebut[] = $triMissions->getDate_deb();
            }

            // Tri
            array_multisort($triStatut, SORT_DESC, $triDateDebut, SORT_DESC, $listeMissions);

            // Réinitialisation du tri
            unset($triStatut);
            unset($triDateDebut);
        }

        // Retour
        return $listeMissions;
    }

    // METIER : Initialisation ajout mission
    // RETOUR : Objets mission
    function initialisationAjoutMission()
    {
        // Instanciation d'un objet Mission vide
        $mission = new Mission();

        // Retour
        return $mission;
    }

    // METIER : Récupération mission spécifique pour modification
    // RETOUR : Objet mission
    function initialisationModificationMission($idMission)
    {
        // Lecture des détails de la mission
        $mission = physiqueMission($idMission);

        // Retour
        return $mission;
    }

    // METIER : Initialisation mission en cas d'erreur de saisie (ajout et modification)
    // RETOUR : Objet mission
    function initialisationErreurMission($saveMission, $idMission)
    {
        // Instanciation d'un objet Mission vide
        $mission = new Mission();

        // Définition de l'id en cas de modification
        if (!empty($idMission))
            $mission->setId($idMission);

        // Définition des données à partir de la sauvegarde
        $mission->setMission($saveMission['mission']);
        $mission->setDate_deb($saveMission['date_deb']);
        $mission->setDate_fin($saveMission['date_fin']);
        $mission->setHeure($saveMission['heures'] . $saveMission['minutes'] . '00');
        $mission->setDescription($saveMission['description']);
        $mission->setReference($saveMission['reference']);
        $mission->setObjectif($saveMission['objectif']);
        $mission->setExplications($saveMission['explications']);
        $mission->setConclusion($saveMission['conclusion']);

        // Retour
        return $mission;
    }

    // METIER : Lecture des succès associés à une mission
    // RETOUR : Tableau des succès d'une mission
    function getSuccesMission($mission)
    {
        // Récupération des succès liés à la mission
        $listeSuccesMission = physiqueSuccesMission($mission->getReference());

        // Retour
        return $listeSuccesMission;
    }

    // METIER : Récupération des participants d'une mission
    // RETOUR : Liste des participants
    function getParticipantsMission($idMission)
    {
        // Récupération de la liste des participants de la mission
        $listeUsersParEquipes = physiqueUsersMission($idMission);

        // Traitement s'il y a des participants
        if (!empty($listeUsersParEquipes))
        {
            // Tri et affectation du rang par équipes
            foreach ($listeUsersParEquipes as &$usersParEquipe)
            {
                // Récupération du tri sur avancement puis identifiant
                foreach ($usersParEquipe as $user)
                {
                    $triTotal[]       = $user->getTotal();
                    $triIdentifiant[] = $user->getIdentifiant();
                }

                // Tri
                array_multisort($triTotal, SORT_DESC, $triIdentifiant, SORT_ASC, $usersParEquipe);

                // Réinitialisation du tri
                unset($triTotal);
                unset($triIdentifiant);

                // Affectation du rang
                $prevTotal   = $usersParEquipe[0]->getTotal();
                $currentRank = 1;

                foreach ($usersParEquipe as &$user)
                {
                    $currentTotal = $user->getTotal();

                    if ($currentTotal != $prevTotal)
                    {
                        $currentRank += 1;
                        $prevTotal    = $user->getTotal();
                    }

                    $user->setRank($currentRank);
                }

                unset($user);
            }
        }

        unset($usersParEquipe);

        // Retour
        return $listeUsersParEquipes;
    }

    // METIER : Lecture des équipes des participants
    // RETOUR : Liste des équipes
    function getEquipesParticipantsMission($idMission)
    {
        // Récupération de la liste des équipes
        $listeEquipes = physiqueEquipesMission($idMission);

        // Retour
        return $listeEquipes;
    }

    // METIER : Insertion d'une nouvelle mission
    // RETOUR : Erreur éventuelle
    function insertMission($post, $files)
    {
        // Initialisations
        $control_ok = true;
        $erreur     = NULL;

        // Récupération des données
        $mission      = $post['mission'];
        $dateDeb      = $post['date_deb'];
        $dateFin      = $post['date_fin'];
        $heures       = $post['heures'];
        $minutes      = $post['minutes'];
        $description  = $post['description'];
        $reference    = $post['reference'];
        $objectif     = $post['objectif'];
        $explications = $post['explications'];
        $conclusion   = $post['conclusion'];

        // Sauvegarde en session en cas d'erreur
        $_SESSION['save']['new_mission'] = array('post' => $post, 'files' => $files);

        // Remplacement des caractères spéciaux pour la référence
        $search    = array(' ', 'é', 'è', 'ê', 'ë', 'à', 'â', 'ç', 'ô', 'û');
        $replace   = array('_', 'e', 'e', 'e', 'e', 'a', 'a', 'c', 'o', 'u');
        $reference = str_replace($search, $replace, $reference);

        // Formatage heure
        $heure = $heures . $minutes . '00';

        // Contrôle référence unique
        $control_ok = controleReferenceUnique($reference);

        // Contrôle format date début
        if ($control_ok == true)
            $control_ok = controleFormatDate($dateDeb);

        // Formatage de la date de début pour insertion
        if ($control_ok == true)
            $dateDeb = formatDateForInsert($dateDeb);

        // Contrôle format date fin
        if ($control_ok == true)
            $control_ok = controleFormatDate($dateFin);

        // Formatage de la date de fin pour insertion
        if ($control_ok == true)
            $dateFin = formatDateForInsert($dateFin);

        // Contrôle date début <= date fin
        if ($control_ok == true)
            $control_ok = controleOrdreDates($dateDeb, $dateFin);

        // Contrôle objectif numérique
        if ($control_ok == true)
            $control_ok = controleObjectifNumerique($objectif);

        // Contrôle images présentes
        if ($control_ok == true)
        {
            foreach ($files as $file)
            {
                $control_ok = controlePresenceFichier($file['name']);
            }
        }

        // Vérification des dossiers et contrôle des fichiers
        if ($control_ok == true)
        {
            // Dossier de destination
            $dossier       = '../../includes/images/missions';
            $dossierImages = $dossier . '/banners';
            $dossierIcones = $dossier . '/buttons';

            // Contrôle en boucle des fichiers avant insertion
            foreach ($files as $keyFile => $file)
            {
                // Nom du fichier
                switch ($keyFile)
                {
                    case 'mission_icone_g':
                        $name = $reference . '_g';
                        break;

                    case 'mission_icone_m':
                        $name = $reference . '_m';
                        break;

                    case 'mission_icone_d':
                        $name = $reference . '_d';
                        break;

                    case 'mission_image':
                    default:
                        $name = $reference;
                        break;
                }

                // Contrôles communs d'un fichier
                $fileDatas = controlsUploadFile($file, $name, 'png');

                // Récupération contrôles
                $control_ok = controleFichier($fileDatas);

                // Arrêt de la boucle en cas d'erreur
                if ($control_ok == false)
                    break;
            }
        }

        // Insertion des images dans les dossiers
        if ($control_ok == true)
        {
            // Insertion des fichiers
            foreach ($files as $keyFile => $file)
            {
                // Nouveau nom
                switch ($keyFile)
                {
                    case 'mission_icone_g':
                        $newName = $reference . '_g.png';
                        break;

                    case 'mission_icone_m':
                        $newName = $reference . '_m.png';
                        break;

                    case 'mission_icone_d':
                        $newName = $reference . '_d.png';
                        break;

                    case 'mission_image':
                    default:
                        $newName = $reference . '.png';
                        break;
                }

                // Données à envoyer pour l'upload
                $fileDatas = array(
                    'control_ok' => true,
                    'new_name'   => $newName,
                    'tmp_file'   => $file['tmp_name'],
                    'type_file'  => $file['type']
                );

                // Upload fichier
                if ($keyFile == 'mission_image')
                    $control_ok = uploadFile($fileDatas, $dossierImages);
                else
                    $control_ok = uploadFile($fileDatas, $dossierIcones);

                // Arrêt de la boucle en cas d'erreur
                if ($control_ok == false)
                    break;
            }
        }

        // Insertion de l'enregistrement en base
        if ($control_ok == true)
        {
            $mission = array(
                'mission'      => $mission,
                'reference'    => $reference,
                'date_deb'     => $dateDeb,
                'date_fin'     => $dateFin,
                'heure'        => $heure,
                'objectif'     => $objectif,
                'description'  => $description,
                'explications' => $explications,
                'conclusion'   => $conclusion
            );

            physiqueInsertionMission($mission);

            // Message d'alerte
            $_SESSION['alerts']['mission_added'] = true;
        }

        // Positionnement erreur
        if ($control_ok != true)
            $erreur = true;

        // Retour
        return $erreur;
    }

    // METIER : Modification d'une mission existante
    // RETOUR : Id mission
    function updateMission($post, $files)
    {
        // Initialisations
        $control_ok = true;

        // Récupération des données
        $idMission    = $post['id_mission'];
        $mission      = $post['mission'];
        $dateDeb      = $post['date_deb'];
        $dateFin      = $post['date_fin'];
        $heures       = $post['heures'];
        $minutes      = $post['minutes'];
        $description  = $post['description'];
        $reference    = $post['reference'];
        $objectif     = $post['objectif'];
        $explications = $post['explications'];
        $conclusion   = $post['conclusion'];

        // Sauvegarde en session en cas d'erreur
        $_SESSION['save']['old_mission'] = array('post' => $post, 'files' => $files);

        // Remplacement des caractères spéciaux pour la référence
        $search    = array(' ', 'é', 'è', 'ê', 'ë', 'à', 'â', 'ç', 'ô', 'û');
        $replace   = array('_', 'e', 'e', 'e', 'e', 'a', 'a', 'c', 'o', 'u');
        $reference = str_replace($search, $replace, $reference);

        // Formatage heure
        $heure = $heures . $minutes . '00';

        // Contrôle format date début
        if ($control_ok == true)
            $control_ok = controleFormatDate($dateDeb);

        // Formatage de la date de début pour insertion
        if ($control_ok == true)
            $dateDeb = formatDateForInsert($dateDeb);

        // Contrôle format date fin
        if ($control_ok == true)
            $control_ok = controleFormatDate($dateFin);

        // Formatage de la date de fin pour insertion
        if ($control_ok == true)
            $dateFin = formatDateForInsert($dateFin);

        // Contrôle date début <= date fin
        if ($control_ok == true)
            $control_ok = controleOrdreDates($dateDeb, $dateFin);

        // Contrôle objectif numérique
        if ($control_ok == true)
            $control_ok = controleObjectifNumerique($objectif);

        // Contrôle images présentes, si présentes alors on modifie l'image
        if ($control_ok == true)
        {
            // Dossier de destination
            $dossier       = '../../includes/images/missions';
            $dossierImages = $dossier . '/banners';
            $dossierIcones = $dossier . '/buttons';

            // Contrôle des fichiers
            foreach ($files as $keyFile => $file)
            {
                if (!empty($file['name']))
                {
                    // Nom du fichier
                    switch ($keyFile)
                    {
                        case 'mission_icone_g':
                            $name = $reference . '_g';
                            break;

                        case 'mission_icone_m':
                            $name = $reference . '_m';
                            break;

                        case 'mission_icone_d':
                            $name = $reference . '_d';
                            break;

                        case 'mission_image':
                        default:
                            $name = $reference;
                            break;
                    }

                    // Contrôles communs d'un fichier
                    $fileDatas = controlsUploadFile($file, $name, 'png');

                    // Récupération contrôles
                    $control_ok = controleFichier($fileDatas);

                    // Arrêt de la boucle en cas d'erreur
                    if ($control_ok == false)
                        break;
                }
            }
        }

        // Insertion des images dans les dossiers
        if ($control_ok == true)
        {
            // Insertion des fichiers
            foreach ($files as $keyFile => $file)
            {
                if (!empty($file['name']))
                {
                    // Nouveau nom
                    switch ($keyFile)
                    {
                        case 'mission_icone_g':
                            $newName = $reference . '_g.png';
                            break;

                        case 'mission_icone_m':
                            $newName = $reference . '_m.png';
                            break;

                        case 'mission_icone_d':
                            $newName = $reference . '_d.png';
                            break;

                        case 'mission_image':
                        default:
                            $newName = $reference . '.png';
                            break;
                    }

                    // Suppression de l'ancienne image
                    if ($keyFile == 'mission_image')
                        unlink($dossierImages . '/' . $newName);
                    else
                        unlink($dossierIcones . '/' . $newName);

                    // Données à envoyer pour l'upload
                    $fileDatas = array(
                        'control_ok' => true,
                        'new_name'   => $newName,
                        'tmp_file'   => $file['tmp_name'],
                        'type_file'  => $file['type']
                    );

                    // Upload fichier
                    if ($keyFile == 'mission_image')
                        $control_ok = uploadFile($fileDatas, $dossierImages);
                    else
                        $control_ok = uploadFile($fileDatas, $dossierIcones);

                    // Arrêt de la boucle en cas d'erreur
                    if ($control_ok == false)
                        break;
                }
            }
        }

        // Modification de l'enregistrement en base
        if ($control_ok == true)
        {
            $mission = array(
                'mission'      => $mission,
                'date_deb'     => $dateDeb,
                'date_fin'     => $dateFin,
                'heure'        => $heure,
                'objectif'     => $objectif,
                'description'  => $description,
                'explications' => $explications,
                'conclusion'   => $conclusion
            );

            physiqueUpdateMission($idMission, $mission);

            // Message d'alerte
            $_SESSION['alerts']['mission_updated'] = true;
        }

        return $idMission;
    }

    // METIER : Suppression d'une mission
    // RETOUR : Aucun
    function deleteMission($post)
    {
        // Récupération des données
        $idMission = $post['id_mission'];

        // Récupération des données de la mission
        $mission = physiqueMission($idMission);

        // Suppression des images
        if (!empty($mission->getReference()))
        {
            unlink('../../includes/images/missions/banners/' . $mission->getReference() . '.png');
            unlink('../../includes/images/missions/buttons/' . $mission->getReference() . '_g.png');
            unlink('../../includes/images/missions/buttons/' . $mission->getReference() . '_m.png');
            unlink('../../includes/images/missions/buttons/' . $mission->getReference() . '_d.png');
        }

        // Suppression des participations
        physiqueDeleteMissionUsers($idMission);

        // Suppression de l'enregistrement en base
        physiqueDeleteMission($idMission);

        // Mise à jour des références de missions des succès
        physiqueUpdateSuccesMission($mission->getReference());

        // Suppression des notifications
        deleteNotification('start_mission', '', $idMission);
        deleteNotification('end_mission', '', $idMission);
        deleteNotification('one_mission', '', $idMission);

        // Message d'alerte
        $_SESSION['alerts']['mission_deleted'] = true;
    }
?>