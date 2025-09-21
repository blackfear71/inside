<?php
    include_once('../../includes/classes/profile.php');
    include_once('../../includes/classes/success.php');
    include_once('../../includes/classes/teams.php');

    // METIER : Lecture des données profil
    // RETOUR : Objet Profile
    function getProfile($identifiant)
    {
        // Récupération des données du profil
        $profil = physiqueProfil($identifiant);

        // Retour
        return $profil;
    }

    // METIER : Lecture des données équipe
    // RETOUR : Objet Team
    function getEquipe($reference)
    {
        // Récupération des données de l'équipe
        $equipe = physiqueEquipe($reference);

        // Retour
        return $equipe;
    }

    // METIER : Lecture des données statistiques profil
    // RETOUR : Objet Statistiques
    function getStatistiques($user)
    {
        // Films ajoutés
        $nombreFilms = physiqueNombreLignesTable('movie_house', 'identifiant_add', $user->getIdentifiant());

        // Commentaires films
        $nombreComments = physiqueNombreLignesTable('movie_house_comments', 'identifiant', $user->getIdentifiant());

        // Restaurants ajoutés
        $nombreRestaurants = physiqueNombreLignesTable('food_advisor_restaurants', 'identifiant_add', $user->getIdentifiant());

        // Réservations de restaurants
        $nombreReservations = physiqueNombreLignesTable('food_advisor_choices', 'caller', $user->getIdentifiant());

        // Gâteaux de la semaine
        $nombreGateauxSemaine = physiqueGateauxSemaineUser($user->getIdentifiant());

        // Recettes partagées
        $nombreRecettes = physiqueRecettesUser($user->getIdentifiant());

        // Bilan des dépenses
        $bilanUser = $user->getExpenses();

        // Phrases et images cultes ajoutées
        $nombreCollector = physiqueNombreLignesTable('collector', 'author', $user->getIdentifiant());

        // Parcours ajoutés
        $nombreParcours = physiqueNombreLignesTable('petits_pedestres_parcours', 'identifiant_add', $user->getIdentifiant());

        // Participations parcours
        $nombreParticipations = physiqueNombreLignesTable('petits_pedestres_users', 'identifiant', $user->getIdentifiant());

        // Idées publiées
        $nombreTheBox = physiqueNombreLignesTable('ideas', 'author', $user->getIdentifiant());

        // Bugs soumis
        $nombreBugsSoumis = physiqueBugsEvolutionsSoumisUser($user->getIdentifiant(), 'B');

        // Evolutions soumises
        $nombreEvolutionsSoumises = physiqueBugsEvolutionsSoumisUser($user->getIdentifiant(), 'E');

        // Génération d'un objet StatistiquesProfil
        $statistiques = array(
            'nb_films_ajoutes'       => $nombreFilms,
            'nb_comments'            => $nombreComments,
            'nb_collectors'          => $nombreCollector,
            'nb_restaurants_ajoutes' => $nombreRestaurants,
            'nb_reservations'        => $nombreReservations,
            'nb_gateaux'             => $nombreGateauxSemaine,
            'nb_recettes'            => $nombreRecettes,
            'expenses'               => $bilanUser,
            'nb_ideas'               => $nombreTheBox,
            'nb_bugs'                => $nombreBugsSoumis,
            'nb_evolutions'          => $nombreEvolutionsSoumises,
            'nb_parcours'            => $nombreParcours,
            'nb_participations'      => $nombreParticipations
        );

        $tableauStatistiques = StatistiquesProfil::withData($statistiques);

        // Retour
        return $tableauStatistiques;
    }

    // METIER : Lecture des données préférences
    // RETOUR : Objet Preferences
    function getPreferences($identifiant)
    {
        // Récupération des données préférences
        $preferences = physiquePreferences($identifiant);

        // Retour
        return $preferences;
    }

    // METIER : Lecture de la liste des équipes
    // RETOUR : Liste des équipes
    function getListeEquipes()
    {
        // Lecture de la liste des équipes
        $listeEquipes = physiqueListeEquipes();

        // Retour
        return $listeEquipes;
    }

    // METIER : Récupération des données de progression
    // RETOUR : Tableau des données de progression
    function getProgress($experience)
    {
        // Calcul de la progression
        $niveau   = convertExperience($experience);
        $expMin   = 10 * $niveau ** 2;
        $expMax   = 10 * ($niveau + 1) ** 2;
        $expLvl   = $expMax - $expMin;
        $progress = $experience - $expMin;
        $percent  = floor($progress * 100 / $expLvl);

        // Génération d'un objet Progression
        $progression = new Progression();

        $progression->setNiveau($niveau);
        $progression->setExperience_min($expMin);
        $progression->setExperience_max($expMax);
        $progression->setExperience_niveau($expLvl);
        $progression->setProgression($progress);
        $progression->setPourcentage($percent);

        // Retour
        return $progression;
    }

    // METIER : Mise à jour de l'avatar (base + fichier)
    // RETOUR : Aucun
    function updateAvatar($identifiant, $files)
    {
        // Initialisations
        $control_ok = true;
        $avatar     = rand();

        // Dossier de destination
        $dossier = '../../includes/images/profil/avatars';

        // Contrôles fichier
        $fileDatas = controlsUploadFile($files['avatar'], $avatar, 'all');

        // Récupération contrôles
        $control_ok = controleFichier($fileDatas);

        // Upload fichier
        if ($control_ok == true)
            $control_ok = uploadFile($fileDatas, $dossier);

        // Traitement de l'image
        if ($control_ok == true)
        {
            $newName = $fileDatas['new_name'];

            // Création miniature avec une hauteur/largeur max de 400px
            imageThumb($dossier . '/' . $newName, $dossier . '/' . $newName, 400, false, true);

            // Suppression de l'ancien avatar si présent
            $oldAvatar = physiqueAvatarUser($identifiant);

            if (!empty($oldAvatar))
                unlink($dossier . '/' . $oldAvatar . '');

            // Modification de l'enregistrement en base
            physiqueUpdateAvatarUser($identifiant, $newName);

            // Mise à jour de la session
            $_SESSION['user']['avatar'] = $newName;

            // Message d'alerte
            $_SESSION['alerts']['avatar_updated'] = true;
        }
    }

    // METIER : Suppression de l'avatar
    // RETOUR : Aucun
    function deleteAvatar($identifiant)
    {
        // Dossier de destination
        $dossier = '../../includes/images/profil/avatars/';

        // Suppression de l'ancien avatar si présent
        $oldAvatar = physiqueAvatarUser($identifiant);

        if (!empty($oldAvatar))
            unlink($dossier . $oldAvatar . '');

        // Modification de l'enregistrement en base
        physiqueUpdateAvatarUser($identifiant, '');

        // Mise à jour de la session
        $_SESSION['user']['avatar'] = '';

        // Message d'alerte
        $_SESSION['alerts']['avatar_deleted'] = true;
    }

    // METIER : Mise à jour des informations
    // RETOUR : Aucun
    function updateInfos($identifiant, $post, $isMobile)
    {
        // Initialisations
        $control_ok = true;

        // Récupération des données
        $email = $post['email'];

        if (isset($post['pseudo']) AND !empty($post['pseudo']))
            $pseudo = trim($post['pseudo']);
        else
            $pseudo = $_SESSION['user']['pseudo'];

        if (isset($post['anniversaire']) AND !empty($post['anniversaire']))
        {
            if ($isMobile == true)
                $anniversary = formatDateForInsertMobile($post['anniversaire']);
            else
                $anniversary = formatDateForInsert($post['anniversaire']);
        }
        else
            $anniversary = '';

        // Contrôles date d'anniversaire
        if (isset($post['anniversaire']) AND !empty($post['anniversaire']))
        {
            // Contrôle format date
            $control_ok = controleFormatDate($post['anniversaire'], $isMobile);

            // Contrôle date dans le futur
            if ($control_ok == true)
                $control_ok = controleDateFutur($anniversary);
        }

        // Modification de l'enregistrement en base
        if ($control_ok == true)
        {
            $user = array(
                'pseudo'      => $pseudo,
                'email'       => $email,
                'anniversary' => $anniversary
            );

            physiqueUpdateUser($user, $identifiant);

            // Mise à jour de la session
            $_SESSION['user']['pseudo'] = htmlspecialchars($pseudo);

            // Message d'alerte
            $_SESSION['alerts']['infos_updated'] = true;
        }
    }

    // METIER : Mise à jour des préférences
    // RETOUR : Aucun
    function updatePreferences($identifiant, $post)
    {
        // Initialisations
        $categoriesMovieHouse = '';

        // Récupération des données
        $viewNotifications = $post['notifications_view'];
        $viewMovieHouse    = $post['movie_house_view'];
        $viewTheBox        = $post['the_box_view'];
        $initChat          = $post['inside_room_view'];
        $celsius           = $post['celsius_view'];

        if (isset($post['films_semaine']))
            $categoriesMovieHouse .= 'Y;';
        else
            $categoriesMovieHouse .= 'N;';

        if (isset($post['films_waited']))
            $categoriesMovieHouse .= 'Y;';
        else
            $categoriesMovieHouse .= 'N;';

        if (isset($post['films_way_out']))
            $categoriesMovieHouse .= 'Y;';
        else
            $categoriesMovieHouse .= 'N;';

        // Réinitialisation des cookies de position Celsius
        if ($celsius == 'N')
        {
            setcookie('celsius[positionX]', '', -1, '/');
            setcookie('celsius[positionY]', '', -1, '/');
        }

        // Modification de l'enregistrement en base
        $preferences = array(
            'init_chat'              => $initChat,
            'celsius'                => $celsius,
            'view_movie_house'       => $viewMovieHouse,
            'categories_movie_house' => $categoriesMovieHouse,
            'view_the_box'           => $viewTheBox,
            'view_notifications'     => $viewNotifications
        );

        physiqueUpdatePreferences($preferences, $identifiant);

        // Mise à jour de la session
        $_SESSION['user']['celsius']            = $celsius;
        $_SESSION['user']['view_movie_house']   = $viewMovieHouse;
        $_SESSION['user']['view_the_box']       = $viewTheBox;
        $_SESSION['user']['view_notifications'] = $viewNotifications;

        // Message d'alerte
        $_SESSION['alerts']['preferences_updated'] = true;
    }

    // METIER : Mise à jour du mot de passe
    // RETOUR : Aucun
    function updatePassword($identifiant, $post)
    {
        // Initialisations
        $control_ok = true;

        // Si on a saisi toutes les données
        if (!empty($post['old_password'])
        AND !empty($post['new_password'])
        AND !empty($post['confirm_new_password']))
        {
            // Récupération des données du mot de passe
            $crypt = physiqueDonneesPasswordUser($identifiant);

            // Cryptage ancien mot de passe saisi
            $oldPassword = htmlspecialchars(hash('sha1', $post['old_password'] . $crypt['salt']));

            // Contrôle correspondance ancien mot de passe
            $control_ok = controleCorrespondancePassword($oldPassword, $crypt['password']);

            // Contrôle correspondance nouveau mot de passe
            if ($control_ok == true)
            {
                $salt               = rand();
                $newPassword        = htmlspecialchars(hash('sha1', $post['new_password'] . $salt));
                $confirmNewPassword = htmlspecialchars(hash('sha1', $post['confirm_new_password'] . $salt));

                $control_ok = controleCorrespondancePassword($confirmNewPassword, $newPassword);
            }

            // Modification de l'enregistrement en base
            if ($control_ok == true)
            {
                physiqueUpdatePasswordUser($salt, $newPassword, $identifiant);

                // Réinitialisation des cookies de connexion
                setcookie('index[identifiant]', '', -1, '/');
                setcookie('index[password]', '', -1, '/');

                // Définition des nouveaux cookies de connexion
                setCookie('index[identifiant]', $identifiant, [
                    'expires'  => time() + 60 * 60 * 24 * 365,
                    'path'     => '/',
                    'SameSite' => 'Lax'
                ]);

                setCookie('index[password]', $newPassword, [
                    'expires'  => time() + 60 * 60 * 24 * 365,
                    'path'     => '/',
                    'SameSite' => 'Lax'
                ]);

                // Message d'alerte
                $_SESSION['alerts']['password_updated'] = true;
            }
        }
    }

    // METIER : Changement d'équipe
    // RETOUR : Aucun
    function updateEquipe($sessionUser, $post)
    {
        // Initialisations
        $control_ok = true;
        $status     = 'T';

        // Récupération des données
        $identifiant = $sessionUser['identifiant'];
        $oldTeam     = $sessionUser['equipe'];

        if ($post['equipe'] == 'other')
        {
            $newTeam        = 'temp_' . rand();
            $labelTeam      = $post['autre_equipe'];
            $activationTeam = 'N';
        }
        else
            $newTeam = $post['equipe'];

        // Récupération des données utilisateur
        $user = physiqueProfil($identifiant);

        // Contrôle équipe différente
        $control_ok = controleAncienneEquipe($oldTeam, $newTeam);

        // Contrôle dépenses nulles
        if ($control_ok == true)
            $control_ok = controleDepensesNonNulles($user->getExpenses());

        // Création d'une nouvelle équipe si besoin
        if ($control_ok == true)
        {
            if ($post['equipe'] == 'other')
            {
                $team = array(
                    'reference'  => $newTeam,
                    'team'       => $labelTeam,
                    'activation' => $activationTeam
                );

                physiqueInsertionEquipe($team);
            }
        }

        // Modification de l'enregistrement en base
        if ($control_ok == true)
        {
            $equipeUser = array(
                'new_team' => $newTeam,
                'status'   => $status
            );

            physiqueUpdateEquipeUser($equipeUser, $identifiant);

            // Message d'alerte
            $_SESSION['alerts']['ask_team'] = true;
        }
    }

    // METIER : Mise à jour du statut par l'utilisateur (désinscription, mot de passe)
    // RETOUR : Aucun
    function updateStatus($identifiant, $status)
    {
        // Modification de l'enregistrement en base
        physiqueUpdateStatusUser($identifiant, $status);

        // Message d'alerte
        switch ($status)
        {
            case 'D':
                $_SESSION['alerts']['ask_desinscription'] = true;
                break;

            case 'U':
                $_SESSION['alerts']['cancel_status'] = true;
                break;

            default:
                break;
        }
    }

    // METIER : Lecture liste des utilisateurs
    // RETOUR : Liste des utilisateurs
    function getUsers($equipe)
    {
        // Récupération liste des utilisateurs
        $listeUsers = physiqueUsers($equipe);

        // Récupération des données complémentaires
        foreach ($listeUsers as $user)
        {
            // Récupération du niveau
            $user->setLevel(convertExperience($user->getExperience()));
        }

        // Traitement s'il y a des utilisateurs
        if (!empty($listeUsers))
        {
            // Récupération du tri sur expérience puis identifiant
            foreach ($listeUsers as $user)
            {
                $triExp[] = $user->getExperience();
                $triId[]  = $user->getIdentifiant();
            }

            // Tri
            array_multisort($triExp, SORT_DESC, $triId, SORT_ASC, $listeUsers);

            // Réinitialisation du tri
            unset($triExp);
            unset($triId);
        }

        // Retour
        return $listeUsers;
    }

    // METIER : Lecture liste des succès
    // RETOUR : Liste des succès et déblocages
    function getSuccess($identifiant, $listeUsers)
    {
        // Création tableau de correspondance identifiant / pseudo / avatar
        $tableauUsers = array();

        foreach ($listeUsers as $user)
        {
            $tableauUsers[$user->getIdentifiant()] = array(
                'identifiant' => $user->getIdentifiant(),
                'pseudo'      => $user->getPseudo(),
                'avatar'      => $user->getAvatar()
            );
        }

        // Récupération de la liste des succès
        $listeSuccess = physiqueListeSuccess();

        // Récupération des classements des succès
        foreach ($listeSuccess as $success)
        {
            // Récupération valeur succès
            $valueSuccess = physiqueSuccessUser($success->getReference(), $identifiant);

            if ($valueSuccess != NULL)
            {
                // Contrôle pour les missions que la date de fin soit passée
                $missionTermineeOuAutre = controleMissionTermineeOuAutre($success->getMission());

                if ($missionTermineeOuAutre == true)
                    $success->setValue_user($valueSuccess);
            }

            // Récupération du classement des utilisateurs
            if ($success->getDefined() == 'Y' AND $success->getUnicity() != 'Y')
            {
                // Contrôle pour les missions que la date de fin soit passée
                $missionTermineeOuAutre = controleMissionTermineeOuAutre($success->getMission());

                // Récupération de l'avancement des utilisateurs
                $listeRangSuccess = array();

                if ($missionTermineeOuAutre == true)
                {
                    foreach ($tableauUsers as $user)
                    {
                        $rangSuccess = physiqueSuccessUsers($success->getReference(), $success->getLimit_success(), $user);

                        // On ajoute la ligne au tableau
                        if (!empty($rangSuccess))
                            array_push($listeRangSuccess, $rangSuccess);
                    }
                }

                // Traitement s'il y a des utilisateurs
                if (!empty($listeRangSuccess))
                {
                    // Récupération du tri sur la valeur du succès des utilisateurs
                    foreach ($listeRangSuccess as &$rangSuccessUser)
                    {
                        $triSuccess[] = $rangSuccessUser->getValue();
                    }

                    unset($rangSuccessUser);

                    // Tri
                    array_multisort($triSuccess, SORT_DESC, $listeRangSuccess);

                    // Réinitialisation du tri
                    unset($triSuccess);
                }

                // Filtrage du tableau
                if (!empty($listeRangSuccess))
                {
                    // Affectation du rang et suppression si rang > 3 (médaille de bronze)
                    $rangPrecedent = $listeRangSuccess[0]->getValue();
                    $rangCourant   = 1;

                    foreach ($listeRangSuccess as $key => $rangSuccessUser)
                    {
                        $currentTotal = $rangSuccessUser->getValue();

                        if ($currentTotal != $rangPrecedent)
                        {
                            $rangCourant += 1;
                            $rangPrecedent = $rangSuccessUser->getValue();
                        }

                        // Suppression des rangs > 3 sinon on enregistre le rang
                        if ($rangCourant > 3)
                            unset($listeRangSuccess[$key]);
                        else
                            $rangSuccessUser->setRank($rangCourant);
                    }
                }

                // Récupération du classement
                $success->setClassement($listeRangSuccess);
            }
        }

        // Retour
        return $listeSuccess;
    }

    // METIER : Conversion de la liste d'objets des succès en tableau simple pour JSON
    // RETOUR : Tableau des succès
    function convertForJsonListeSucces($listeSucces)
    {
        // Initialisations
        $listeSuccesAConvertir = array();

        // Conversion de la liste d'objets en tableau pour envoyer au Javascript
        foreach ($listeSucces as $succes)
        {
            if ($succes->getDefined() == 'Y' AND $succes->getValue_user() >= $succes->getLimit_success())
            {
                $succesAConvertir = array(
                    'id'            => $succes->getId(),
                    'reference'     => $succes->getReference(),
                    'title'         => $succes->getTitle(),
                    'description'   => $succes->getDescription(),
                    'limit_success' => $succes->getLimit_success(),
                    'explanation'   => $succes->getExplanation(),
                    'classement'    => array()
                );

                $classementSucces = array();

                foreach ($succes->getClassement() as $classement)
                {
                    $classementUser = array(
                        'identifiant' => $classement->getIdentifiant(),
                        'pseudo'      => $classement->getPseudo(),
                        'avatar'      => $classement->getAvatar(),
                        'value'       => $classement->getValue(),
                        'rank'        => $classement->getRank()
                    );

                    array_push($classementSucces, $classementUser);
                }

                $succesAConvertir['classement'] = $classementSucces;
                $listeSuccesAConvertir[$succes->getId()] = $succesAConvertir;
            }
        }

        // Retour
        return $listeSuccesAConvertir;
    }

    // METIER : Lecture des polices de caractères existantes
    // RETOUR : Liste des polices
    function getPolicesCaracteres()
    {
        // Récupération des dossiers de polices
        $polices = scandir('../../includes/fonts', SCANDIR_SORT_ASCENDING);

        // Suppression des racines de dossier
        unset($polices[array_search('..', $polices)]);
        unset($polices[array_search('.', $polices)]);

        // Retour
        return $polices;
    }

    // METIER : Lecture des thèmes existants par type
    // RETOUR : Liste des thèmes
    function getThemes($type, $experience)
    {
        // Initialisations
        $niveau = '';

        // Récupération du niveau
        if ($type == 'U')
            $niveau = convertExperience($experience);

        // Récupération de la liste des thèmes
        $listeThemes = physiqueThemes($type, $niveau);

        // Retour
        return $listeThemes;
    }

    // METIER : Détermine si on a un thème de mission en cours
    // RETOUR : Booléen
    function getThemeMission()
    {
        // Détermination si thème mission en cours
        $isThemeMission = physiqueThemeMission();

        // Retour
        return $isThemeMission;
    }

    // METIER : Mise à jour de la préférence police de caractères utilisateur
    // RETOUR : Aucun
    function updateFont($identifiant, $post)
    {
        // Récupération des données
        $police = $post['police'];

        // Modification de l'enregistrement en base
        physiqueUpdateFont($identifiant, $police);

        // Mise à jour de la session
        $_SESSION['user']['font'] = $police;

        // Message d'alerte
        $_SESSION['alerts']['font_updated'] = true;
    }

    // METIER : Mise à jour de la préférence thème utilisateur
    // RETOUR : Aucun
    function updateTheme($identifiant, $post)
    {
        // Récupération des données
        $idTheme = $post['id_theme'];

        // Lecture de la référence du thème
        $referenceTheme = physiqueReferenceTheme($idTheme);

        // Modification de l'enregistrement en base
        physiqueUpdateTheme($identifiant, $referenceTheme);

        // Mise à jour de la session
        $_SESSION['user']['theme'] = getTheme($referenceTheme);
        
        // Message d'alerte
        $_SESSION['alerts']['theme_updated'] = true;
    }

    // METIER : Supprime la préférence thème utilisateur
    // RETOUR : Aucun
    function deleteTheme($identifiant)
    {
        // Modification de l'enregistrement en base
        physiqueUpdateTheme($identifiant, '');

        // Mise à jour de la session
        $_SESSION['user']['theme'] = getTheme('');
        
        // Message d'alerte
        $_SESSION['alerts']['theme_deleted'] = true;
    }
?>