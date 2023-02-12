<?php
    include_once('../../includes/functions/modeles_mails.php');
    include_once('../../includes/classes/movies.php');
    include_once('../../includes/classes/profile.php');

    // METIER : Initialise les données de sauvegarde en session
    // RETOUR : Aucun
    function initializeSaveSession()
    {
        // On initialise les champs de saisie s'il n'y a pas d'erreur
        if ((!isset($_SESSION['alerts']['wrong_date'])            OR $_SESSION['alerts']['wrong_date']            != true)
        AND (!isset($_SESSION['alerts']['wrong_date_doodle'])     OR $_SESSION['alerts']['wrong_date_doodle']     != true)
        AND (!isset($_SESSION['alerts']['restaurant_incomplete']) OR $_SESSION['alerts']['restaurant_incomplete'] != true))
        {
            unset($_SESSION['save']);

            $_SESSION['save']['nom_film_saisi']         = '';
            $_SESSION['save']['date_theater_saisie']    = '';
            $_SESSION['save']['date_release_saisie']    = '';
            $_SESSION['save']['trailer_saisi']          = '';
            $_SESSION['save']['link_saisi']             = '';
            $_SESSION['save']['poster_saisi']           = '';
            $_SESSION['save']['synopsis_saisi']         = '';
            $_SESSION['save']['doodle_saisi']           = '';
            $_SESSION['save']['date_doodle_saisie']     = '';
            $_SESSION['save']['time_doodle_saisi']      = '';
            $_SESSION['save']['hours_doodle_saisies']   = '';
            $_SESSION['save']['minutes_doodle_saisies'] = '';
            $_SESSION['save']['restaurant_saisi']       = '';
            $_SESSION['save']['place_saisie']           = '';
        }
    }

    // METIER : Lecture des données préférences
    // RETOUR : Objet Preferences
    function getPreferences($identifiant)
    {
        // Lecture des préférences utilisateur
        $preferences = physiquePreferences($identifiant);

        // Retour
        return $preferences;
    }

    // METIER : Lecture des utilisateurs
    // RETOUR : Liste des utilisateurs
    function getUsers($equipe)
    {
        // Lecture des utilisateurs
        $listeUsers = physiqueUsers($equipe);

        // Retour
        return $listeUsers;
    }

    // METIER : Insertion / modification étoiles utilisateur
    // RETOUR : Id film
    function insertStar($post, $identifiant)
    {
        // Récupération des données
        $idFilm = $post['id_film'];

        if (isset($post['preference_0']))
            $preference = 0;
        elseif (isset($post['preference_1']))
            $preference = 1;
        elseif (isset($post['preference_2']))
            $preference = 2;
        elseif (isset($post['preference_3']))
            $preference = 3;
        elseif (isset($post['preference_4']))
            $preference = 4;
        elseif (isset($post['preference_5']))
            $preference = 5;
        else
            $preference = 0;

        // Traitement du choix utilisateur
        if ($preference == 0)
        {
            // Suppression de l'enregistrement en base
            physiqueDeleteEtoile($idFilm, $identifiant);
        }
        else
        {
            // Vérification existence d'une étoile pour le film
            $etoileExistante = physiqueEtoileExistante($idFilm, $identifiant);

            // Insertion ou mise à jour étoile utilisateur
            if ($etoileExistante == true)
            {
                // Modification de l'enregistrement en base
                physiqueUpdateEtoile($idFilm, $identifiant, $preference);
            }
            else
            {
                // Insertion de l'enregistrement en base
                $etoile = array(
                    'id_film'       => $idFilm,
                    'identifiant'   => $identifiant,
                    'stars'         => $preference,
                    'participation' => 'N'
                );

                physiqueInsertionEtoile($etoile);
            }
        }

        // Retour
        return $idFilm;
    }

    // METIER : Insertion / modification participation
    // RETOUR : Id film
    function insertParticipation($post, $identifiant)
    {
        // Récupération des données
        $idFilm = $post['id_film'];

        // Traitement de l'action
        if (isset($post['participate']) OR isset($post['seen']))
        {
            // Lecture des données du film
            $film = physiqueFilm($idFilm);

            // Lecture de l'état de la participation
            $participationActuelle = physiqueParticipation($idFilm, $identifiant);

            // Gestion de la participation
            if (isset($post['participate']))
            {
                // Inversion de la participation
                if ($participationActuelle == 'P')
                    $participationNouvelle = 'N';
                else
                    $participationNouvelle = 'P';
            }

            // Gestion de la vue
            if (isset($post['seen']))
            {
                // Inversion de la vue
                if ($participationActuelle == 'S')
                    $participationNouvelle = 'N';
                else
                    $participationNouvelle = 'S';
            }

            // Modification de l'enregistrement en base
            physiqueUpdateParticipation($idFilm, $identifiant, $participationNouvelle);

            // Génération succès (participation)
            if (isset($post['participate']))
            {
                if ($participationActuelle == 'S')
                    insertOrUpdateSuccesValue('viewer', $identifiant, -1);

                if (stripos($film->getFilm(), 'Les derniers Jedi') !== false)
                    insertOrUpdateSuccesValue('padawan', $identifiant, 0);
            }

            // Génération succès (vue)
            if (isset($post['seen']))
            {
                // Génération succès
                if ($participationNouvelle == 'S')
                    insertOrUpdateSuccesValue('viewer', $identifiant, 1);
                else
                    insertOrUpdateSuccesValue('viewer', $identifiant, -1);

                if (stripos($film->getFilm(), 'Les derniers Jedi') !== false)
                {
                    if ($participationNouvelle == 'S')
                        insertOrUpdateSuccesValue('padawan', $identifiant, 1);
                    else
                        insertOrUpdateSuccesValue('padawan', $identifiant, 0);
                }
            }
        }

        // Retour
        return $idFilm;
    }
?>