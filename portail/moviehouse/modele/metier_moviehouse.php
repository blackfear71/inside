<?php
    // METIER : Contrôle année existante (pour les onglets)
    // RETOUR : Booléen
    function controlYear($annee, $equipe)
    {
        // Initialisations
        $anneeExistante = false;

        // Vérification année présente en base
        if (isset($annee))
        {
            if (is_numeric($annee))
                $anneeExistante = physiqueAnneeExistante($annee, $equipe);
            elseif ($annee == 'none')
                $anneeExistante = physiqueSansAnnee($equipe);
        }

        // Retour
        return $anneeExistante;
    }

    // METIER : Lecture années distinctes pour les onglets
    // RETOUR : Liste des années existantes
    function getOnglets($equipe)
    {
        // Récupération de la liste des années existantes
        $onglets = physiqueOnglets($equipe);

        // Retour
        return $onglets;
    }

    // METIER : Lecture liste des films récents
    // RETOUR : Liste des films récents
    function getFilmsRecents($annee, $equipe, $isMobile)
    {
        // Initialisations
        if ($isMobile == true)
            $limite = 4;
        else
            $limite = 5;

        // Récupération de la liste des films récents
        $listeFilmsRecents = physiqueFilmsRecents($annee, $equipe, $limite);

        // Retour
        return $listeFilmsRecents;
    }

    // METIER : Vérifie si la semaine en cours doit être affichée pour les sorties de la semaine
    // RETOUR : Booléen
    function isWeekYear($annee)
    {
        // Initialisations
        $afficherSemaine = true;

        // Calcul des dates de la semaine
        $nombreJoursLundi    = 1 - date('N');
        $nombreJoursDimanche = 7 - date('N');
        $lundi               = date('Ymd', strtotime('+' . $nombreJoursLundi . ' days'));
        $dimanche            = date('Ymd', strtotime('+' . $nombreJoursDimanche . ' days'));

        // Récupération des années
        $anneeDuLundi    = substr($lundi, 0, 4);
        $anneeDuDimanche = substr($dimanche, 0, 4);

        // Vérification si la semaine fait partie de l'année affichée
        if ($annee != $anneeDuLundi AND $annee != $anneeDuDimanche)
            $afficherSemaine = false;

        // Retour
        return $afficherSemaine;
    }

    // METIER : Lecture liste des films qui sortent la semaine courante
    // RETOUR : Listes des films qui sortent la semaine courante
    function getSortiesSemaine($equipe)
    {
        // Calcul des dates de la semaine
        $nombreJoursLundi    = 1 - date('N');
        $nombreJoursDimanche = 7 - date('N');
        $lundi               = date('Ymd', strtotime('+' . $nombreJoursLundi . ' days'));
        $dimanche            = date('Ymd', strtotime('+' . $nombreJoursDimanche . ' days'));

        // Récupération de la liste des films qui sortent dans la semaine
        $listeFilmsSemaine = physiqueFilmsSemaine($lundi, $dimanche, $equipe);

        // Retour
        return $listeFilmsSemaine;
    }

    // METIER : Lecture liste des films les plus attendus
    // RETOUR : Liste des films attendus
    function getFilmsAttendus($annee, $equipe, $isMobile)
    {
        // Initialisations
        $listeFilmsAttendus = array();

        if ($isMobile == true)
            $limite = 4;
        else
            $limite = 5;

        // Calcul date du jour - 1 mois
        $dateJourMoins1Mois = date('Ymd', strtotime('now - 1 Month'));

        // Récupération de la liste des films de l'année recherchée
        $listeFilmsAnnee = physiqueFilmsAnnee($annee, $dateJourMoins1Mois, $equipe);

        // Récupération du nombre d'utilisateurs et de la moyenne des étoiles pour chaque film
        foreach ($listeFilmsAnnee as $film)
        {
            $statsFilm = physiqueStatsFilm($film->getId(), $equipe);

            // Si il y a au moins un utilisateur avec des étoiles, on calcule la moyenne
            if ($statsFilm['nombre_users'] > 0)
            {
                $average = str_replace('.', ',', round($statsFilm['total_etoiles'] / $statsFilm['nombre_users'], 1));

                // Ajout des données complémentaires
                $film->setNb_users($statsFilm['nombre_users']);
                $film->setAverage($average);

                // On ajoute la ligne au tableau seulement s'il y a des participants et une moyenne
                if ($average != 0)
                    array_push($listeFilmsAttendus, $film);
            }
        }

        // Tris
        if (isset($listeFilmsAttendus) AND !empty($listeFilmsAttendus))
        {
            // Récupération du tri sur le nombre d'utilisateurs puis par moyenne
            foreach ($listeFilmsAttendus as $film)
            {
                $triNombreUsers[] = $film->getNb_users();
                $triMoyenne[]     = $film->getAverage();
            }

            // Tri
            array_multisort($triNombreUsers, SORT_DESC, $triMoyenne, SORT_DESC, $listeFilmsAttendus);

            // Réinitialisation du tri
            unset($triNombreUsers);
            unset($triMoyenne);

            // Extraction des X premièrs films les plus attentus
            $listeFilmsAttendus = array_slice($listeFilmsAttendus, 0, $limite);

            // Récupération du tri des films restants sur la moyenne
            foreach ($listeFilmsAttendus as $film)
            {
                $triAverage[] = $film->getAverage();
            }

            // Tri
            array_multisort($triAverage, SORT_DESC, $listeFilmsAttendus);

            // Réinitialisation du tri
            unset($triAverage);
        }

        // Retour
        return $listeFilmsAttendus;
    }

    // METIER : Lecture des prochaines sorties cinéma organisées
    // RETOUR : Liste des prochaines sorties cinéma organisées
    function getSortiesOrganisees($annee, $equipe, $isMobile)
    {
        // Initialisations
        if ($isMobile == true)
            $limite = 4;
        else
            $limite = 5;

        // Récupération de la liste des films avec une sortie cinéma organisée
        $listeFilmsSorties = physiqueSortiesOrganisees($annee, $equipe, $limite);

        // Retour
        return $listeFilmsSorties;
    }

    // METIER : Lecture des films par année
    // RETOUR : Liste des films
    function getFilms($annee, $sessionUser)
    {
        // Récupération des données
        $identifiant = $sessionUser['identifiant'];
        $equipe      = $sessionUser['equipe'];

        // Récupération de la liste des films de l'année
        $listeFilms = physiqueFilms($annee, $equipe, $identifiant);

        // Récupération du nombre de participants
        foreach ($listeFilms as $film)
        {
            $film->setNb_users(physiqueNombreParticipants($film->getId()));
        }

        // Retour
        return $listeFilms;
    }

    // METIER : Récupère les étoiles utilisateurs de chaque film
    // RETOUR : Tableau des étoiles utilisateurs
    function getEtoilesFichesFilms($listeFilms, $listeUsers, $equipe)
    {
        // Initialisations
        $listeEtoiles = array();

        // Récupération des étoiles pour chaque film
        foreach ($listeFilms as $film)
        {
            // Récupération des étoiles
            $listeEtoilesFilm = physiqueEtoilesFilm($film->getId(), $listeUsers, $equipe);

            // On ajoute la ligne au tableau
            $listeEtoiles[$film->getId()] = $listeEtoilesFilm;
        }

        // Retour
        return $listeEtoiles;
    }

    // METIER : Insertion d'un film
    // RETOUR : Id film
    function insertFilm($post, $sessionUser, $isMobile)
    {
        // Initialisations
        $idFilm     = NULL;
        $control_ok = true;

        // Récupération des données
        $identifiant    = $sessionUser['identifiant'];
        $equipe         = $sessionUser['equipe'];
        $nomFilm        = $post['nom_film'];
        $toDelete       = 'N';
        $dateAdd        = date('Ymd');
        $identifiantAdd = $sessionUser['identifiant'];
        $identifiantDel = '';
        $synopsis       = $post['synopsis'];
        $dateTheater    = $post['date_theater'];
        $dateRelease    = $post['date_release'];
        $link           = $post['link'];
        $poster         = $post['poster'];
        $trailer        = $post['trailer'];
        $doodle         = $post['doodle'];
        $dateDoodle     = $post['date_doodle'];

        if (!empty($post['date_doodle']) AND isset($post['hours_doodle']) AND isset($post['minutes_doodle']))
            $timeDoodle = $post['hours_doodle'] . $post['minutes_doodle'];
        else
            $timeDoodle = '';

        $restaurant = $post['restaurant'];
        $place      = $post['place'];

        // Sauvegarde en session en cas d'erreur
        $_SESSION['save']['nom_film_saisi']      = $post['nom_film'];
        $_SESSION['save']['date_theater_saisie'] = $post['date_theater'];
        $_SESSION['save']['date_release_saisie'] = $post['date_release'];
        $_SESSION['save']['trailer_saisi']       = $post['trailer'];
        $_SESSION['save']['link_saisi']          = $post['link'];
        $_SESSION['save']['poster_saisi']        = $post['poster'];
        $_SESSION['save']['synopsis_saisi']      = $post['synopsis'];
        $_SESSION['save']['doodle_saisi']        = $post['doodle'];
        $_SESSION['save']['date_doodle_saisie']  = $post['date_doodle'];

        if (isset($post['hours_doodle']))
            $_SESSION['save']['hours_doodle_saisies'] = $post['hours_doodle'];
        else
            $_SESSION['save']['hours_doodle_saisies'] = '  ';

        if (isset($post['minutes_doodle']))
            $_SESSION['save']['minutes_doodle_saisies'] = $post['minutes_doodle'];
        else
            $_SESSION['save']['minutes_doodle_saisies'] = '  ';

        $_SESSION['save']['time_doodle_saisi'] = $_SESSION['save']['hours_doodle_saisies'] . $_SESSION['save']['minutes_doodle_saisies'];
        $_SESSION['save']['restaurant_saisi']  = $post['restaurant'];
        $_SESSION['save']['place_saisie']      = $post['place'];

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

        // Extraction de l'ID vidéo et insertion de l'enregistrement en base
        if ($control_ok == true)
        {
            // Extraction de l'ID de la vidéo à partir de l'URL
            $idUrl = extractUrl($trailer);

            // Insertion de l'enregistrement en base
            $film = array(
                'to_delete'       => $toDelete,
                'team'            => $equipe,
                'film'            => $nomFilm,
                'date_add'        => $dateAdd,
                'identifiant_add' => $identifiantAdd,
                'identifiant_del' => $identifiantDel,
                'synopsis'        => $synopsis,
                'date_theater'    => $dateTheater,
                'date_release'    => $dateRelease,
                'link'            => $link,
                'poster'          => $poster,
                'trailer'         => $trailer,
                'id_url'          => $idUrl,
                'doodle'          => $doodle,
                'date_doodle'     => $dateDoodle,
                'time_doodle'     => $timeDoodle,
                'restaurant'      => $restaurant,
                'place'           => $place
            );

            $idFilm = physiqueInsertionFilm($film);

            // Insertion notifications
            insertNotification('film', $equipe, $idFilm, $identifiant);

            if (!empty($doodle))
                insertNotification('doodle', $equipe, $idFilm, $identifiant);

            if (!empty($dateDoodle) AND $dateDoodle == date('Ymd'))
                insertNotification('cinema', $equipe, $idFilm, 'admin');

            // Génération succès
            insertOrUpdateSuccesValue('publisher', $identifiant, 1);

            // Ajout expérience
            insertExperience($identifiant, 'add_film');

            // Message d'alerte
            $_SESSION['alerts']['film_added'] = true;
        }

        // Retour
        return $idFilm;
    }
?>