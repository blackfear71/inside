<?php
    include_once('../classes/notifications.php');
    include_once('../classes/profile.php');

    // METIER : Déconnecte un utilisateur
    // RETOUR : Aucun
    function disconnectUser()
    {
        // Destruction des variables de la session
        session_unset();

        // Destruction de la session (pas bon dans ce cas sinon la page index ne trouve plus les variables dont elle a besoin et affiche un message d'erreur)
        //session_destroy();

        // Réinitialisation des cookies de connexion
        setcookie('index[identifiant]', '', -1, '/');
        setcookie('index[password]', '', -1, '/');
        setcookie('index[page]', '', -1, '/');

        // Après avoir détruit la variable de connexion, on la réinitialise pour éviter les erreurs au retour sur index.php
        $_SESSION['index']['connected'] = false;

        // Retour sur index.php
        header('location: /inside/index.php?action=goConsulter');
    }

    // METIER : Bascule entre la version web et la version mobile
    // RETOUR : Aucun
    function switchMobile()
    {
        // Modification de la session
        if ($_SESSION['index']['plateforme'] == 'mobile')
            $_SESSION['index']['plateforme'] = 'web';
        else
            $_SESSION['index']['plateforme'] = 'mobile';

        // Rafraichissement de la page courante
        header('location: ' . $_SERVER['HTTP_REFERER']);
    }

    // METIER : Récupération du nombre de notifications en temps réel
    // RETOUR : Tableau notifications
    function countNotifications($sessionUser)
    {
        // Récupération des données
        $identifiant       = $sessionUser['identifiant'];
        $equipe            = $sessionUser['equipe'];
        $viewNotifications = $sessionUser['view_notifications'];

        // Récupération préférence
        switch ($viewNotifications)
        {
            case 'M':
                $viewNotifications = 'me';
                $page              = '&page=1';
                break;

            case 'T':
                $viewNotifications = 'today';
                $page              = '';
                break;

            case 'W':
                $viewNotifications = 'week';
                $page              = '&page=1';
                break;

            case 'A':
            default:
                $viewNotifications = 'all';
                $page              = '&page=1';
                break;
        }

        // Récupération du nombre de notifications du jour
        $nombreNotificationsJour = physiqueNombreNotificationsDates($equipe, date('Ymd'), date('Ymd'));

        // Concaténation des données pour JS
        $data = array(
            'identifiant'             => $identifiant,
            'nombreNotificationsJour' => $nombreNotificationsJour,
            'view'                    => $viewNotifications,
            'page'                    => $page
        );

        $dataJson = json_encode($data);

        // Retour
        echo $dataJson;
    }

    // METIER : Récupération du détail des notifications en temps réel
    // RETOUR : Tableau détails notifications
    function getDetailsNotifications($sessionUser)
    {
        // Récupération des données
        $identifiant = $sessionUser['identifiant'];
        $equipe      = $sessionUser['equipe'];

        // Récupération des notifications du jour
        $nombreNotificationsJour = physiqueNombreNotificationsDates($equipe, date('Ymd'), date('Ymd'));

        // Calcul des dates de la semaine
        $nombreJoursLundi    = 1 - date('N');
        $nombreJoursDimanche = 7 - date('N');
        $lundi               = date('Ymd', strtotime('+' . $nombreJoursLundi . ' days'));
        $aujourdhui          = date('Ymd', strtotime('+' . $nombreJoursDimanche . ' days'));

        // Récupération des notifications du jour
        $nombreNotificationsSemaine = physiqueNombreNotificationsDates($equipe, $lundi, $aujourdhui);

        // Concaténation des données pour JS
        $data = array(
            'identifiant'                => $identifiant,
            'nombreNotificationsJour'    => $nombreNotificationsJour,
            'nombreNotificationsSemaine' => $nombreNotificationsSemaine
        );

        $dataJson = json_encode($data);

        // Retour
        echo $dataJson;
    }

    // METIER : Récupération du nombre de bugs en temps réel
    // RETOUR : Tableau nombre de bugs
    function countBugs($sessionUser)
    {
        // Récupération des données
        $identifiant = $sessionUser['identifiant'];
        $equipe      = $sessionUser['equipe'];

        // Récupération du nombre de bugs
        $nombreBugs = physiqueNombreBugs($equipe);

        // Concaténation des données pour JS
        $data = array(
            'identifiant' => $identifiant,
            'nombreBugs'  => $nombreBugs
        );

        $dataJson = json_encode($data);

        // Retour
        echo $dataJson;
    }

    // METIER : Récupération du ping des utilisateurs
    // RETOUR : Liste utilisateurs et pings
    function getPings($equipe)
    {
        // Récupération de la liste des utilisateurs
        $listeUsers = physiquePingsUsers($equipe);

        // Traitement de sécurité et tri sur statut connexion puis identifiant
        foreach ($listeUsers as &$user)
        {
            $user['identifiant']          = htmlspecialchars($user['identifiant']);
            $user['pseudo']               = htmlspecialchars($user['pseudo']);
            $user['avatar']               = htmlspecialchars($user['avatar']);
            $user['ping']                 = htmlspecialchars($user['ping']);
            $user['connected']            = htmlspecialchars($user['connected']);
            $user['date_last_connection'] = htmlspecialchars($user['date_last_connection']);
            $user['hour_last_connection'] = htmlspecialchars($user['hour_last_connection']);

            $triStatut[]      = $user['connected'];
            $triIdentifiant[] = $user['identifiant'];
        }

        unset($user);

        // Tri
        array_multisort($triStatut, SORT_DESC, $triIdentifiant, SORT_ASC, $listeUsers);

        // Réinitialisation du tri
        unset($triStatut);
        unset($triIdentifiant);

        // Concaténation des données pour JS
        $listeUsersJson = json_encode($listeUsers);

        // Retour
        echo $listeUsersJson;
    }

    // METIER : Mise à jour du ping d'un utilisateur
    // RETOUR : Indicateur de connexion
    function updatePing()
    {
        $userConnected = true;

        // Mise à jour si on est un utilisateur connecté
        if ($_SESSION['index']['connected'] == true AND $_SESSION['user']['identifiant'] != 'admin')
        {
            // Modification de l'enregistrement en base
            $ping = date('Y-m-d_H-i-s_') . rand(1, 11111111);

            physiqueUpdatePing($ping, $_SESSION['user']['identifiant']);
        }
        else
            $userConnected = false;

        // Concaténation des données pour JS
        $userConnectedJson = json_encode($userConnected);

        // Retour
        echo $userConnectedJson;
    }
?>