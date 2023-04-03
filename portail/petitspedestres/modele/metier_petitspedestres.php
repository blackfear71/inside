<?php
    include_once('../../includes/classes/parcours.php');

    // METIER : Initialise les données de sauvegarde en session
    // RETOUR : Aucun
    function initializeSaveSession()
    {
        // On initialise les champs de saisie s'il n'y a pas d'erreur
        if ((!isset($_SESSION['alerts']['file_too_big'])    OR $_SESSION['alerts']['file_too_big']    != true)
        AND (!isset($_SESSION['alerts']['temp_not_found'])  OR $_SESSION['alerts']['temp_not_found']  != true)
        AND (!isset($_SESSION['alerts']['wrong_file_type']) OR $_SESSION['alerts']['wrong_file_type'] != true)
        AND (!isset($_SESSION['alerts']['wrong_file'])      OR $_SESSION['alerts']['wrong_file']      != true)
        AND (!isset($_SESSION['alerts']['empty_parcours'])  OR $_SESSION['alerts']['empty_parcours']  != true)
        AND (!isset($_SESSION['alerts']['wrong_distance'])  OR $_SESSION['alerts']['wrong_distance']  != true))
        {
            unset($_SESSION['save']);

            $_SESSION['save']['nom_parcours_saisie']      = '';
            $_SESSION['save']['distance_parcours_saisie'] = '';
            $_SESSION['save']['lieu_parcours_saisie']     = '';
        }
    }

    // METIER : Récupération du tableau de bord
    // RETOUR : Tableau de bord
    function getTableauDeBord($identifiant)
    {
        // Lecture des statistiques de l'utilisateur
        $tableauDeBord = physiqueTableauDeBord($identifiant);
        
        // Retour
        return $tableauDeBord;
    }
    
    // METIER : Récupération des dernières courses
    // RETOUR : Liste des dernières courses
    function getDernieresCourses($sessionUser)
    {
        // Récupération des données
        $identifiant = $sessionUser['identifiant'];
        $equipe      = $sessionUser['equipe'];

        // Lecture des dernières courses de l'utilisateur
        $dernieresCourses = physiqueDernieresCourses($identifiant, $equipe);
        
        // Retour
        return $dernieresCourses;
    }

    // METIER : Récupération des parcours
    // RETOUR : Liste des parcours
    function getListeParcours($equipe)
    {
        // Lecture des parcours
        $listeParcours = physiqueListeParcours($equipe);
        
        // Retour
        return $listeParcours;
    }

    // METIER : Insertion d'un nouveau parcours
    // RETOUR : Id parcours
    function insertParcours($post, $files, $sessionUser)
    {
        // Initialisations
        $idParcours = NULL;
        $control_ok = true;

        // Récupération des données
        $identifiant      = $sessionUser['identifiant'];
        $toDelete         = 'N';
        $equipe           = $sessionUser['equipe'];
        $identifiantAdd   = $sessionUser['identifiant'];
        $identifiantDel   = '';
        $nomParcours      = $post['nom_parcours'];
        $distanceParcours = formatDistanceForInsert($post['distance_parcours']);
        $lieuParcours     = $post['lieu_parcours'];

        // Sauvegarde en session en cas d'erreur
        $_SESSION['save']['nom_parcours_saisie']      = $post['nom_parcours'];
        $_SESSION['save']['distance_parcours_saisie'] = $post['distance_parcours'];
        $_SESSION['save']['lieu_parcours_saisie']     = $post['lieu_parcours'];

        // Contrôle distance numérique
        $control_ok = controleDistanceNumerique($distanceParcours);

        // Insertion document
        if ($control_ok == true)
        {
            // Insertion document
            $documentParcours = uploadParcours($files['document_parcours'], 'document_' . rand(), 'document');

            // Contrôle saisie non vide
            $control_ok = controleContenuParcours($post, $documentParcours['new_name']);
        }

        // Insertion image (si renseignée)
        if ($control_ok == true)
        {
            if (!empty($files['image_parcours']['name']))
                $imageParcours = uploadParcours($files['image_parcours'], 'picture_' . rand(), 'picture');
            else
            {
                $imageParcours = array(
                    'new_name' => '',
                    'type'     => ''
                );
            }
        }

        // Insertion de l'enregistrement en base
        if ($control_ok == true)
        {
            $parcours = array(
                'to_delete'       => $toDelete,
                'team'            => $equipe,
                'identifiant_add' => $identifiantAdd,
                'identifiant_del' => $identifiantDel,
                'name'            => $nomParcours,
                'distance'        => $distanceParcours,
                'location'        => $lieuParcours,
                'picture'         => $imageParcours['new_name'],
                'document'        => $documentParcours['new_name'],
                'type'            => $documentParcours['type']
            );

            $idParcours = physiqueInsertionParcours($parcours);

            // Insertion notification
            insertNotification('parcours', $equipe, $idParcours, $identifiant);

            // Génération succès
            insertOrUpdateSuccesValue('explorer', $identifiant, 1);

            // Ajout expérience
            insertExperience($identifiant, 'add_parcours');

            // Message d'alerte
            $_SESSION['alerts']['parcours_added'] = true;
        }

        // Retour
        return $idParcours;
    }

    // METIER : Modification d'un parcours
    // RETOUR : Id parcours
    function updateParcours($post, $files, $sessionUser)
    {
        // Initialisations
        $control_ok = true;

        // Récupération des données
        $idParcours       = $post['id_parcours'];
        $identifiant      = $sessionUser['identifiant'];
        $equipe           = $sessionUser['equipe'];
        $nomParcours      = $post['nom_parcours'];
        $distanceParcours = formatDistanceForInsert($post['distance_parcours']);
        $lieuParcours     = $post['lieu_parcours'];

        // Lecture des données du parcours
        $ancienParcours = physiqueParcours($idParcours);

        // Contrôle distance numérique
        $control_ok = controleDistanceNumerique($distanceParcours);

        // Insertion nouveau document et suppression ancien
        if ($control_ok == true)
        {
            if (!empty($files['document_parcours']['name']))
            {
                // Insertion document
                $documentParcours = uploadParcours($files['document_parcours'], 'document_' . rand(), 'document');

                // Contrôle saisie non vide
                $control_ok = controleContenuParcours($post, $documentParcours['new_name']);

                // Suppression ancien document
                if ($control_ok == true)
                {
                    if (!empty($ancienParcours->getDocument()))
                    {
                        switch ($ancienParcours->getType())
                        {
                            case 'document':
                                unlink('../../includes/datas/petitspedestres/' . $ancienParcours->getDocument());
                                break;

                            case 'picture':
                                unlink('../../includes/images/petitspedestres/documents/' . $ancienParcours->getDocument());
                                break;

                            default:
                                break;
                        }
                    }
                }
            }
            else
            {
                // On conserve l'ancien document
                $documentParcours = array(
                    'new_name' => $ancienParcours->getDocument(),
                    'type'     => $ancienParcours->getType()
                );
            }
        }

        // Insertion image (si renseignée) et suppression ancienne
        if ($control_ok == true)
        {
            if (!empty($files['image_parcours']['name']))
            {
                // Insertion image
                $imageParcours = uploadParcours($files['image_parcours'], 'picture_' . rand(), 'picture');

                // Suppression ancienne image
                if (!empty($ancienParcours->getPicture()))
                    unlink('../../includes/images/petitspedestres/pictures/' . $ancienParcours->getPicture());
            }
            else
            {
                // On conserve l'ancienne image
                $imageParcours = array(
                    'new_name' => $ancienParcours->getPicture(),
                    'type'     => ''
                );
            }
        }

        // Modification de l'enregistrement en base
        if ($control_ok == true)
        {
            $parcours = array(
                'name'     => $nomParcours,
                'distance' => $distanceParcours,
                'location' => $lieuParcours,
                'picture'  => $imageParcours['new_name'],
                'document' => $documentParcours['new_name'],
                'type'     => $documentParcours['type']
            );

            physiqueUpdateParcours($ancienParcours->getId(), $parcours);

            // Message d'alerte
            $_SESSION['alerts']['parcours_updated'] = true;
        }

        // Retour
        return $idParcours;
    }

    // METIER : Formatage et insertion fichier
    // RETOUR : Nom fichier avec extension
    function uploadParcours($file, $name, $typeFile)
    {
        // Initialisations
        $document   = array(
            'new_name' => '',
            'type'     => ''
        );
        $control_ok = true;

        // Dossier de destination
        switch ($typeFile)
        {
            case 'picture':
                $dossier = '../../includes/images/petitspedestres/pictures';
                $type    = 'picture';
                break;

            case 'document':
                if (strstr($file['type'], 'pdf'))
                {
                    $dossier = '../../includes/datas/petitspedestres';
                    $type    = 'document';
                }
                else
                {
                    $dossier = '../../includes/images/petitspedestres/documents';
                    $type    = 'picture';
                }
                break;

            default:
                $dossier = '';
                break;
        }

        // Contrôles fichier
        $fileDatas = controlsUploadFile($file, $name, 'all');

        // Récupération contrôles
        $control_ok = controleFichier($fileDatas);

        // Upload fichier
        if ($control_ok == true)
            $control_ok = uploadFile($fileDatas, $dossier);

        // Récupération des données
        if ($control_ok == true)
        {
            $document = array(
                'new_name' => $fileDatas['new_name'],
                'type'     => $type,
            );
        }

        // Retour
        return $document;
    }

    // METIER : Demande de suppression d'un parcours
    // RETOUR : Aucun
    function deleteParcours($post, $identifiant)
    {
        // Récupération des données
        $idParcours = $post['id_parcours'];
        $equipe     = $post['team_parcours'];
        $toDelete   = 'Y';

        // Modification de l'enregistrement en base
        physiqueUpdateStatusParcours($idParcours, $toDelete, $identifiant);

        // Mise à jour du statut des notifications
        updateNotification('parcours', $equipe, $idParcours, $toDelete);

        // Message d'alerte
        $_SESSION['alerts']['parcours_removed'] = true;
    }

    // METIER : Contrôle parcours existant
    // RETOUR : Booléen
    function isParcoursDisponible($idParcours, $equipe)
    {
        // Contrôle parcours disponible
        $parcoursDisponible = controleParcoursDisponible($idParcours, $equipe);

        // Retour
        return $parcoursDisponible;
    }

    // METIER : Récupération détails parcours
    // RETOUR : Objet Parcours
    function getDetailsParcours($idParcours, $identifiant)
    {
        // Récupération des détails d'un parcours
        $parcours = physiqueParcours($idParcours);

        // Retour
        return $parcours;
    }

    // METIER : Récupération de la liste des utilisateurs d'un parcours
    // RETOUR : Liste des utilisateurs
    function getUsersDetailsParcours($idParcours, $equipe)
    {
        // Lecture des utilisateurs
        $listeUsers = physiqueUsersDetailsParcours($idParcours, $equipe);

        // Retour
        return $listeUsers;
    }

    // METIER : Récupération des participations d'un parcours
    // RETOUR : Liste des participations
    function getParticipantsParcours($idParcours, $listeUsers)
    {
        // Récupération de la liste des participations
        $listeParticipantsParDate = physiqueParticipantsParcours($idParcours);

        // Récupération pseudo et avatar
        foreach ($listeParticipantsParDate as $participantsParDate)
        {
            foreach ($participantsParDate as $participant)
            {
                $participant->setPseudo($listeUsers[$participant->getIdentifiant()]['pseudo']);
                $participant->setAvatar($listeUsers[$participant->getIdentifiant()]['avatar']);
            }
        }

        // Retour
        return $listeParticipantsParDate;
    }

    // METIER : Conversion de la liste d'objets des parcours en tableau simple pour JSON
    // RETOUR : Tableau des parcours
    function convertForJsonListeParcours($listeParcours)
    {
        // Initialisations
        $listeParcoursAConvertir = array();

        foreach ($listeParcours as $parcoursAConvertir)
        {
            $listeParcoursAConvertir[$parcoursAConvertir->getId()] = $parcoursAConvertir->getName();
        }

        // Retour
        return $listeParcoursAConvertir;
    }

    // METIER : Conversion des détails d'un parcours en tableau simple pour JSON
    // RETOUR : Tableau des détails
    function convertForJsonDetailsParcours($detailsParcours)
    {
        // Conversion de l'objet en tableau pour envoyer au Javascript
        $detailsAConvertir = array(
            'id'       => $detailsParcours->getId(),
            'name'     => $detailsParcours->getName(),
            'distance' => $detailsParcours->getDistance(),
            'location' => $detailsParcours->getLocation(),
            'picture'  => $detailsParcours->getPicture(),
            'document' => $detailsParcours->getDocument(),
            'type'     => $detailsParcours->getType()
        );

        // Retour
        return $detailsAConvertir;
    }
?>