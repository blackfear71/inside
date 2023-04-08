<?php
    include_once('includes/classes/missions.php');
    include_once('includes/classes/profile.php');
    include_once('includes/classes/teams.php');

    // METIER : Initialise les données de sauvegarde en session
    // RETOUR : Aucun
    function initializeSaveSession()
    {
        // Initialisation
        $erreursIndex = array(
            'erreurInscription' => false,
            'erreurPassword'    => false
        );

        // On initialise les champs de saisie s'il n'y a pas d'erreur
        if (((!isset($_SESSION['alerts']['too_short'])       OR  $_SESSION['alerts']['too_short']       != true)
        AND  (!isset($_SESSION['alerts']['already_exist'])   OR  $_SESSION['alerts']['already_exist']   != true)
        AND  (!isset($_SESSION['alerts']['wrong_confirm'])   OR  $_SESSION['alerts']['wrong_confirm']   != true)
        AND  (!isset($_SESSION['alerts']['wrong_id'])        OR  $_SESSION['alerts']['wrong_id']        != true)
        AND  (!isset($_SESSION['alerts']['already_asked'])   OR  $_SESSION['alerts']['already_asked']   != true))
        OR    (isset($_SESSION['alerts']['ask_inscription']) AND $_SESSION['alerts']['ask_inscription'] == true)
        OR    (isset($_SESSION['alerts']['asked'])           AND $_SESSION['alerts']['asked']           == true))
        {
            unset($_SESSION['save']);

            $_SESSION['save']['identifiant_saisi']               = '';
            $_SESSION['save']['pseudo_saisi']                    = '';
            $_SESSION['save']['equipe_saisie']                   = '';
            $_SESSION['save']['autre_equipe']                    = '';
            $_SESSION['save']['mot_de_passe_saisi']              = '';
            $_SESSION['save']['confirmation_mot_de_passe_saisi'] = '';
            $_SESSION['save']['identifiant_saisi_mdp']           = '';
        }
        else
        {
            // Erreur inscription
            if ((isset($_SESSION['alerts']['too_short'])     AND $_SESSION['alerts']['too_short']     == true)
            OR  (isset($_SESSION['alerts']['already_exist']) AND $_SESSION['alerts']['already_exist'] == true)
            OR  (isset($_SESSION['alerts']['wrong_confirm']) AND $_SESSION['alerts']['wrong_confirm'] == true))
                $erreursIndex['erreurInscription'] = true;

            // Erreur mot de passe
            if ((isset($_SESSION['alerts']['already_asked']) AND $_SESSION['alerts']['already_asked'] == true)
            OR  (isset($_SESSION['alerts']['wrong_id'])      AND $_SESSION['alerts']['wrong_id']      == true))
                $erreursIndex['erreurPassword'] = true;
        }

        // Retour
        return $erreursIndex;
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

    // METIER : Connexion utilisateur
    // RETOUR : Indicateur connexion
    function connectUser($dataConnect, $autoConnect)
    {
        // Initialisations
        $control_ok                     = true;
        $connected                      = false;
        $_SESSION['index']['connected'] = NULL;

        // Réinitialisation des cookies
        setcookie('index[identifiant]', null, -1, '/');
        setcookie('index[password]', null, -1, '/');

        // Récupération des données
        if ($autoConnect == true)
        {
            $identifiant = $dataConnect['identifiant'];
            $password    = $dataConnect['password'];
        }
        else
        {
            $password = $dataConnect['mdp'];

            if (strtolower($dataConnect['login']) == 'admin')
                $identifiant = htmlspecialchars(strtolower($dataConnect['login']));
            else
                $identifiant = htmlspecialchars(strtoupper($dataConnect['login']));
        }

        // Lecture des données de l'utilisateur
        $user = physiqueUser($identifiant);

        // Contrôle utilisateur existant
        $control_ok = controleUserExistConnexion($user);

        // Contrôle inscription en cours
        if ($control_ok == true)
            $control_ok = controleStatutConnexion($user->getStatus());

        // Contrôle mot de passe
        if ($control_ok == true)
        {
            // Lecture du mot de passe crypté de l'utilisateur
            $dataPassword = physiquePassword($user->getIdentifiant());

            // Cryptage du mot de passe saisi si besoin
            if ($autoConnect == true)
                $crypted = $password;
            else
                $crypted = htmlspecialchars(hash('sha1', $password . $dataPassword['salt']));

            // Contrôle
            $control_ok = controlePassword($crypted, $dataPassword['password']);
        }

        // Initialisation des données
        if ($control_ok == true)
        {
            // Initialisation de la session utilisateur
            $_SESSION['index']['connected']  = true;
            $_SESSION['user']['identifiant'] = $user->getIdentifiant();
            $_SESSION['user']['pseudo']      = htmlspecialchars($user->getPseudo());
            $_SESSION['user']['avatar']      = $user->getAvatar();

            // Initialisation des préférences utilisateur et du chat
            if ($user->getIdentifiant() != 'admin')
            {
                // Récupération de l'équipe
                $equipe = physiqueEquipe($user->getTeam());

                $_SESSION['user']['equipe']         = $equipe->getReference();
                $_SESSION['user']['libelle_equipe'] = $equipe->getTeam();

                // Récupération des préférences
                $preferences = physiquePreferences($user->getIdentifiant());

                $_SESSION['user']['font']               = $preferences->getFont();
                $_SESSION['user']['celsius']            = $preferences->getCelsius();
                $_SESSION['user']['view_movie_house']   = $preferences->getView_movie_house();
                $_SESSION['user']['view_the_box']       = $preferences->getView_the_box();
                $_SESSION['user']['view_notifications'] = $preferences->getView_notifications();

                if ($preferences->getInit_chat() == 'Y')
                    $_SESSION['chat']['show_chat'] = true;
                else
                    $_SESSION['chat']['show_chat'] = false;

                // Définition des cookies de connexion
                setCookie('index[identifiant]', $user->getIdentifiant(), time() + 60 * 60 * 24 * 365, '/');
                setCookie('index[password]', $crypted, time() + 60 * 60 * 24 * 365, '/');
            }

            // Positionnement indicateur connexion
            $connected = true;
        }

        // Vérification connexion en cas d'erreur
        if ($control_ok == false)
            $_SESSION['index']['connected'] = false;

        // Retour
        return $connected;
    }

    // METIER : Inscription utilisateur
    // RETOUR : Aucun
    function subscribe($post)
    {
        // Initialisations
        $listeUsers = array();
        $control_ok = true;

        // Récupération des données
        $trigramme   = htmlspecialchars(strtoupper(trim($post['trigramme'])));
        $pseudo      = htmlspecialchars(trim($post['pseudo']));
        $salt        = rand();
        $password    = htmlspecialchars(hash('sha1', $post['password'] . $salt));
        $confirm     = htmlspecialchars(hash('sha1', $post['confirm_password'] . $salt));
        $ping        = '';
        $status      = 'I';
        $avatar      = '';
        $email       = '';
        $anniversary = '';
        $experience  = 0;
        $expenses    = 0;

        // Récupération de l'équipe
        $refTeam = '';

        if ($post['equipe'] == 'other')
        {
            $newTeam        = 'temp_' . rand();
            $labelTeam      = trim($post['autre_equipe']);
            $activationTeam = 'N';
        }
        else
            $newTeam = $post['equipe'];

        // Initialisations préférences utilisateur
        $refTheme             = '';
        $font                 = 'Roboto';
        $initChat             = 'Y';
        $celsius              = 'Y';
        $viewMovieHouse       = 'H';
        $categoriesMovieHouse = 'Y;Y;Y;';
        $viewTheBox           = 'P';
        $viewNotifications    = 'T';
        $manageCalendars      = 'N';

        // Sauvegarde en session en cas d'erreur
        $_SESSION['save']['identifiant_saisi']               = $post['trigramme'];
        $_SESSION['save']['pseudo_saisi']                    = $post['pseudo'];
        $_SESSION['save']['equipe_saisie']                   = $post['equipe'];
        $_SESSION['save']['mot_de_passe_saisi']              = $post['password'];
        $_SESSION['save']['confirmation_mot_de_passe_saisi'] = $post['confirm_password'];

        if ($post['equipe'] == 'other')
            $_SESSION['save']['autre_equipe'] = $post['autre_equipe'];
        else
            $_SESSION['save']['autre_equipe'] = '';

        // Contrôle trigramme sur 3 caractères
        $control_ok = controleLongueurTrigramme($trigramme);

        // Contrôle trigramme existant
        if ($control_ok == true)
        {
            // Liste des tables où chercher des identifiants
            $listeTables = array(
                'bugs'                      => 'identifiant',
                'collector'                 => 'author',
                'cooking_box'               => 'identifiant',
                'expense_center'            => 'buyer',
                'expense_center_users'      => 'identifiant',
                'food_advisor_restaurants'  => 'identifiant_add',
                'ideas'                     => 'author',
                'movie_house'               => 'identifiant_add',
                'movie_house_comments'      => 'identifiant',
                'notifications'             => 'identifiant',
                'petits_pedestres_parcours' => 'identifiant_add',
                'users'                     => 'identifiant'
            );

            // Récupération des identifiants dans les tables
            foreach ($listeTables as $table => $colonne)
            {
                // Lecture des identifiants dans les tables
                $identifiantsTable = physiqueIdentifiantsTable($table, $colonne);

                // Fusion des données dans le tableau complet
                $listeUsers = array_merge($listeUsers, $identifiantsTable);
            }

            // Suppression des doublons et tri
            $listeUsers = array_unique($listeUsers);
            sort($listeUsers);

            // Contrôle trigramme existant
            $control_ok = controleTrigrammeUnique($listeUsers, $trigramme);
        }

        // Contrôle saisies mot de passe
        if ($control_ok == true)
            $control_ok = controleConfirmationPassword($password, $confirm);

        // Insertion des enregistrements en base
        if ($control_ok == true)
        {
            // Données utilisateur
            $user = array(
                'identifiant' => $trigramme,
                'team'        => $refTeam,
                'new_team'    => $newTeam,
                'salt'        => $salt,
                'password'    => $password,
                'ping'        => $ping,
                'status'      => $status,
                'pseudo'      => $pseudo,
                'avatar'      => $avatar,
                'email'       => $email,
                'anniversary' => $anniversary,
                'experience'  => $experience,
                'expenses'    => $expenses
            );

            physiqueInsertionUser($user);

            // Préférences utilisateur
            $preferences = array(
                'identifiant'            => $trigramme,
                'ref_theme'              => $refTheme,
                'font'                   => $font,
                'init_chat'              => $initChat,
                'celsius'                => $celsius,
                'view_movie_house'       => $viewMovieHouse,
                'categories_movie_house' => $categoriesMovieHouse,
                'view_the_box'           => $viewTheBox,
                'view_notifications'     => $viewNotifications,
                'manage_calendars'       => $manageCalendars
            );

            physiqueInsertionPreferences($preferences);

            // Nouvelle équipe
            if ($post['equipe'] == 'other')
            {
                $team = array(
                    'reference'  => $newTeam,
                    'team'       => $labelTeam,
                    'activation' => $activationTeam
                );

                physiqueInsertionEquipe($team);
            }

            // Message d'alerte
            $_SESSION['alerts']['ask_inscription'] = true;
        }
    }

    // METIER : Demande de récupération de mot de passe
    // RETOUR : Aucun
    function resetPassword($post)
    {
        // Initialisations
        $control_ok = true;

        // Récupération des données
        $identifiant = htmlspecialchars(strtoupper(trim($post['login'])));
        $status      = 'P';

        // Sauvegarde en session en cas d'erreur
        $_SESSION['save']['identifiant_saisi_mdp'] = $post['login'];

        // Lectures des données de l'utilisateur
        $user = physiqueUser($identifiant);

        // Contrôle utilisateur existant
        $control_ok = controleUserExistReset($user);

        // Contrôle statut utilisateur
        if ($control_ok == true)
            $control_ok = controleStatutReset($user->getStatus());

        // Modification de l'enregistrement en base
        if ($control_ok == true)
        {
            physiqueUpdateStatut($status, $user->getIdentifiant());

            // Message d'alerte
            $_SESSION['alerts']['password_asked'] = true;
        }
    }
?>