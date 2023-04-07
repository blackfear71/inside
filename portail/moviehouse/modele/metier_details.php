<?php
    // METIER : Contrôle film existant et non à supprimer
    // RETOUR : Booléen
    function isFilmDisponible($idFilm, $equipe)
    {
        // Contrôle film disponible
        $filmDisponible = controleFilmDisponible($idFilm, $equipe);

        // Retour
        return $filmDisponible;
    }

    // METIER : Récupération détails film
    // RETOUR : Objet Movie
    function getDetails($idFilm, $identifiant)
    {
        // Récupération des données du film
        $film = physiqueFilm($idFilm);

        // Récupération des étoiles et de la participation de l'utilisateur
        $actionsUser = physiqueActionsUser($idFilm, $identifiant);

        $film->setStars_user($actionsUser['etoiles']);
        $film->setParticipation($actionsUser['participation']);

        // Récupération du nombre de participants
        $film->setNb_users(physiqueNombreParticipants($idFilm));

        // Retour
        return $film;
    }

    // METIER : Récupération films précédent et suivant pour navigation
    // RETOUR : Liste des films précédent et suivant
    function getNavigation($film, $equipe)
    {
        // Initialisations
        $boutonPrecedent = array(
            'id'   => '',
            'film' => ''
        );
        $boutonSuivant   = array(
            'id'   => '',
            'film' => ''
        );

        // Récupération des données
        $idFilm      = $film->getId();
        $titreFilm   = $film->getFilm();
        $dateTheater = $film->getDate_theater();
        $anneeFilm   = substr($dateTheater, 0, 4);

        // Vérification film précédent existant
        $filmPrecedentExistant = physiqueFilmPrecedentExistant($idFilm, $titreFilm, $anneeFilm, $equipe, $dateTheater);

        // Récupération du film précédent
        if ($filmPrecedentExistant == true)
            $filmPrecedent = physiqueFilmPrecedent($idFilm, $titreFilm, $anneeFilm, $equipe, $dateTheater);

        // Vérification film suivant existant
        $filmSuivantExistant = physiqueFilmSuivantExistant($idFilm, $titreFilm, $anneeFilm, $equipe, $dateTheater);

        // Récupération du film suivant
        if ($filmSuivantExistant == true)
            $filmSuivant = physiqueFilmSuivant($idFilm, $titreFilm, $anneeFilm, $equipe, $dateTheater);

        // Création du bouton film précédent
        if (isset($filmPrecedent) AND !empty($filmPrecedent))
        {
            $titreFilmPrecedent      = formatString($filmPrecedent->getFilm(), 15);
            $boutonPrecedent['id']   = $filmPrecedent->getId();
            $boutonPrecedent['film'] = $titreFilmPrecedent;
        }

        // Création du bouton film suivant
        if (isset($filmSuivant) AND !empty($filmSuivant))
        {
            $titreFilmSuivant      = formatString($filmSuivant->getFilm(), 15);
            $boutonSuivant['id']   = $filmSuivant->getId();
            $boutonSuivant['film'] = $titreFilmSuivant;
        }

        // On ajoute la ligne au tableau
        $listeNavigation = array(
            'previous' => $boutonPrecedent,
            'next'     => $boutonSuivant
        );

        // Retour
        return $listeNavigation;
    }

    // METIER : Récupération de la liste des utilisateurs d'un film
    // RETOUR : Liste des utilisateurs
    function getUsersDetailsFilm($idFilm, $equipe)
    {
        // Lecture des utilisateurs
        $listeUsers = physiqueUsersDetailsFilm($idFilm, $equipe);

        // Retour
        return $listeUsers;
    }

    // METIER : Récupération des étoiles utilisateurs d'un film
    // RETOUR : Liste des étoiles utilisateurs
    function getEtoilesDetailsFilm($idFilm, $listeUsers, $equipe)
    {
        // Récupération des étoiles
        $listeEtoilesFilm = physiqueEtoilesFilm($idFilm, $listeUsers, $equipe);

        // Récupération pseudo et avatar
        foreach ($listeEtoilesFilm as $etoilesFilm)
        {
            $etoilesFilm->setPseudo($listeUsers[$etoilesFilm->getIdentifiant()]['pseudo']);
            $etoilesFilm->setAvatar($listeUsers[$etoilesFilm->getIdentifiant()]['avatar']);
            $etoilesFilm->setEmail($listeUsers[$etoilesFilm->getIdentifiant()]['email']);
        }

        // Retour
        return $listeEtoilesFilm;
    }

    // METIER : Conversion de la liste d'objets des détails d'un film en tableau simple pour JSON
    // RETOUR : Tableau des détails
    function convertForJsonDetailsFilm($detailsFilm)
    {
        // Conversion de l'objet en tableau pour envoyer au Javascript
        $detailsAConvertir = array(
            'id'             => $detailsFilm->getId(),
            'film'           => $detailsFilm->getFilm(),
            'date_theater'   => $detailsFilm->getDate_theater(),
            'date_release'   => $detailsFilm->getDate_release(),
            'trailer'        => $detailsFilm->getTrailer(),
            'link'           => $detailsFilm->getLink(),
            'poster'         => $detailsFilm->getPoster(),
            'synopsis'       => $detailsFilm->getSynopsis(),
            'doodle'         => $detailsFilm->getDoodle(),
            'date_doodle'    => $detailsFilm->getDate_doodle(),
            'hours_doodle'   => substr($detailsFilm->getTime_doodle(), 0, 2),
            'minutes_doodle' => substr($detailsFilm->getTime_doodle(), 2, 2),
            'restaurant'     => $detailsFilm->getRestaurant(),
            'place'          => $detailsFilm->getPlace()
        );

        // Retour
        return $detailsAConvertir;
    }

    // METIER : Modification d'un film
    // RETOUR : Id film
    function updateFilm($post, $sessionUser, $isMobile)
    {
        // Initialisations
        $control_ok = true;

        // Récupération des données
        $identifiant = $sessionUser['identifiant'];
        $equipe      = $sessionUser['equipe'];
        $idFilm      = $post['id_film'];
        $nomFilm     = $post['nom_film'];
        $dateTheater = $post['date_theater'];
        $dateRelease = $post['date_release'];
        $link        = $post['link'];
        $poster      = $post['poster'];
        $synopsis    = $post['synopsis'];
        $trailer     = $post['trailer'];
        $doodle      = $post['doodle'];
        $dateDoodle  = $post['date_doodle'];

        if (!empty($post['date_doodle']) AND isset($post['hours_doodle']) AND isset($post['minutes_doodle']))
            $timeDoodle = $post['hours_doodle'] . $post['minutes_doodle'];
        else
            $timeDoodle = '';

        $restaurant = $post['restaurant'];
        $place      = $post['place'];

        // Contrôle date sortie cinéma
        if (isset($dateTheater) AND !empty($dateTheater))
        {
            // Contrôle format date sortie cinéma
            if ($control_ok == true)
                $control_ok = controleFormatDate($dateTheater, $isMobile);

            // Formatage de la date de sortie cinéma pour insertion
            if ($control_ok == true)
            {
                if ($isMobile == true)
                    $dateTheater = formatDateForInsertMobile($dateTheater);
                else
                    $dateTheater = formatDateForInsert($dateTheater);
            }
        }

        // Contrôle date sortie DVD / Bluray
        if ($control_ok == true)
        {
            if (isset($dateRelease) AND !empty($dateRelease))
            {
                // Contrôle format date sortie DVD / Bluray
                if ($control_ok == true)
                    $control_ok = controleFormatDate($dateRelease, $isMobile);

                // Formatage de la date de sortie DVD / Bluray pour insertion
                if ($control_ok == true)
                {
                    if ($isMobile == true)
                        $dateRelease = formatDateForInsertMobile($dateRelease);
                    else
                        $dateRelease = formatDateForInsert($dateRelease);
                }
            }
        }

        // Contrôle date Doodle
        if ($control_ok == true)
        {
            if (isset($dateDoodle) AND !empty($dateDoodle))
            {
                // Contrôle format date Doodle
                if ($control_ok == true)
                    $control_ok = controleFormatDate($dateDoodle, $isMobile);

                // Formatage de la date Doodle pour insertion
                if ($control_ok == true)
                {
                    if ($isMobile == true)
                        $dateDoodle = formatDateForInsertMobile($dateDoodle);
                    else
                        $dateDoodle = formatDateForInsert($dateDoodle);
                }
            }
        }

        // Contrôle date sortie film <= date Doodle
        if ($control_ok == true)
        {
            if (isset($dateTheater) AND !empty($dateTheater) AND isset($dateDoodle) AND !empty($dateDoodle))
                $control_ok = controleOrdreDates($dateTheater, $dateDoodle);
        }

        // Contrôle moment restaurant renseigné
        if ($control_ok == true)
            $control_ok = controleMomentRestaurantSaisi($restaurant, $place);

        // Extraction de l'ID vidéo et modification de l'enregistrement en base
        if ($control_ok == true)
        {
            // Extraction de l'ID de la vidéo à partir de l'URL
            $idUrl = extractUrl($trailer);

            // Modification de l'enregistrement en base
            $film = array(
                'film'         => $nomFilm,
                'synopsis'     => $synopsis,
                'date_theater' => $dateTheater,
                'date_release' => $dateRelease,
                'link'         => $link,
                'poster'       => $poster,
                'trailer'      => $trailer,
                'id_url'       => $idUrl,
                'doodle'       => $doodle,
                'date_doodle'  => $dateDoodle,
                'time_doodle'  => $timeDoodle,
                'restaurant'   => $restaurant,
                'place'        => $place
            );

            physiqueUpdateFilm($idFilm, $film);

            // Gestion notification Doodle
            if (empty($doodle))
            {
                // Suppression notification si Doodle supprimé
                deleteNotification('doodle', $equipe, $idFilm);
            }
            else
            {
                // Vérification si Doodle renseigné et notification existante
                $notificationDoodleExist = controlNotification('doodle', $idFilm, $equipe);

                // Insertion notification
                if ($notificationDoodleExist != true)
                    insertNotification('doodle', $equipe, $idFilm, $identifiant);
            }

            // Gestion notification sortie cinéma
            if (empty($dateDoodle))
            {
                // Suppression notification si Date sortie supprimée (cas notification générée par batch puis date supprimée le jour même)
                deleteNotification('cinema', $equipe, $idFilm);
            }
            else
            {
                // Si la sortie est programmée pour le jour même
                if ($dateDoodle == date('Ymd'))
                {
                    // Vérification si sortie cinéma programmée et notification existante
                    $notificationCinemaExist = controlNotification('cinema', $idFilm, $equipe);

                    // Insertion notification
                    if ($notificationCinemaExist != true)
                        insertNotification('cinema', $equipe, $idFilm, 'admin');
                }
            }

            // Message d'alerte
            $_SESSION['alerts']['film_updated'] = true;
        }

        // Retour
        return $idFilm;
    }

    // METIER : Lecture de la vue à partir des préférences utilisateur
    // RETOUR : Vue
    function getVueSuppression($identifiant)
    {
        // Lecture des préférences utilisateur
        $preferences = physiquePreferences($identifiant);

        // Détermination de la vue
        switch ($preferences->getView_movie_house())
        {
            case 'C':
                $viewMovieHouse = 'cards';
                break;

            case 'H':
            default:
                $viewMovieHouse = 'home';
                break;
        }

        // Retour
        return $viewMovieHouse;
    }

    // METIER : Demande de suppression d'un film
    // RETOUR : Aucun
    function deleteFilm($post, $identifiant)
    {
        // Récupération des données
        $idFilm   = $post['id_film'];
        $equipe   = $post['team_film'];
        $toDelete = 'Y';

        // Modification de l'enregistrement en base
        physiqueUpdateStatusFilm($idFilm, $toDelete, $identifiant);

        // Mise à jour du statut des notifications
        updateNotification('film', $equipe, $idFilm, $toDelete);
        updateNotification('doodle', $equipe, $idFilm, $toDelete);
        updateNotification('cinema', $equipe, $idFilm, $toDelete);
        updateNotification('comments', $equipe, $idFilm, $toDelete);

        // Message d'alerte
        $_SESSION['alerts']['film_removed'] = true;
    }

    // METIER : Récupération des commentaires d'un film
    // RETOUR : Liste des commentaires
    function getCommentaires($idFilm, $listeUsers)
    {
        // Récupération de la liste des commentaires
        $listeCommentaires = physiqueCommentaires($idFilm);

        // Récupération pseudo et avatar
        foreach ($listeCommentaires as $commentaire)
        {
            $commentaire->setPseudo($listeUsers[$commentaire->getIdentifiant()]['pseudo']);
            $commentaire->setAvatar($listeUsers[$commentaire->getIdentifiant()]['avatar']);
        }

        // Retour
        return $listeCommentaires;
    }

    // METIER : Insertion commentaire sur un détail film
    // RETOUR : Id film
    function insertCommentaire($post, $sessionUser)
    {
        // Récupération des données
        $identifiant = $sessionUser['identifiant'];
        $equipe      = $sessionUser['equipe'];
        $idFilm      = $post['id_film'];
        $comment     = $post['comment'];

        // Insertion de l'enregistrement en table
        $commentaire = array(
            'id_film'     => $idFilm,
            'identifiant' => $identifiant,
            'date'        => date('Ymd'),
            'time'        => date('His'),
            'comment'     => $comment
        );

        physiqueInsertionCommentaire($commentaire);

        // Vérification notification déjà présente
        $notificationCommentsExist = controlNotification('comments', $idFilm, $equipe);

        // Insertion notification
        if ($notificationCommentsExist != true)
            insertNotification('comments', $equipe, $idFilm, $identifiant);
            
        // Génération succès
        insertOrUpdateSuccesValue('commentator', $identifiant, 1);

        // Retour
        return $idFilm;
    }

    // METIER : Modification commentaire sur un détail film
    // RETOUR : Id film et commentaire
    function updateCommentaire($post)
    {
        // Récupération des données
        $commentaire       = $post['comment'];
        $idFilmCommentaire = array(
            'id_film'    => $post['id_film'],
            'id_comment' => $post['id_comment']
        );

        // Modification de l'enregistrement en base
        physiqueUpdateCommentaire($idFilmCommentaire['id_comment'], $commentaire);

        // Retour
        return $idFilmCommentaire;
    }

    // METIER : Suppression commentaire sur un détail film
    // RETOUR : Id film
    function deleteCommentaire($post, $sessionUser)
    {
        // Récupération des données
        $identifiant   = $sessionUser['identifiant'];
        $equipe        = $sessionUser['equipe'];
        $idFilm        = $post['id_film'];
        $idCommentaire = $post['id_comment'];

        // Suppression de l'enregistrement en base
        physiqueDeleteCommentaire($idCommentaire);

        // Vérification dernier commentaire de la journée pour ce film
        $dernierCommentaireJour = physiqueDernierCommentaireJour($idFilm);

        // Suppression notification
        if ($dernierCommentaireJour == true)
            deleteNotification('comments', $equipe, $idFilm);

        // Génération succès
        insertOrUpdateSuccesValue('commentator', $identifiant, -1);

        // Retour
        return $idFilm;
    }

    // METIER : Envoi de mail pour sortie film
    // RETOUR : Aucun
    function sendMail($details, $participants)
    {
        // Traitement de sécurité
        $details = Movie::secureData($details);

        foreach ($participants as &$participant)
        {
            $participant = Stars::secureData($participant);
        }

        unset($participant);

        // Récupération du contenu du mail
        $message = getModeleMailFilm($details, $participants);

        // Envoi d'un mail par personne
        foreach ($participants as $participant)
        {
            if (!isset($_SESSION['alerts']['mail_film_error']) OR $_SESSION['alerts']['mail_film_error'] != true)
            {
                if (!empty($participant->getEmail()))
                {
                    // Connexion au serveur de mails et initialisations
                    include_once('../../includes/functions/appel_mail.php');

                    // Destinataire du mail
                    $mail->clearAddresses();
                    $mail->AddAddress($participant->getEmail(), $participant->getPseudo());

                    // Objet du mail
                    $mail->Subject = 'Inside - Votre participation à "' . $details->getFilm() . '"';

                    // Contenu du mail
                    $mail->MsgHTML($message);

                    // Envoi du mail avec message d'alerte
                    if (!$mail->Send())
                        $_SESSION['alerts']['mail_film_error'] = true;
                    else
                        $_SESSION['alerts']['mail_film_send']  = true;
                }
            }
        }
    }
?>