<?php
    include_once('../../includes/functions/appel_bdd.php');

    /****************************************************************************/
    /********************************** SELECT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Lecture du nombre de notifications entre 2 dates
    // RETOUR : Nombre de notifications
    function physiqueNombreNotificationsDates($equipe, $date1, $date2)
    {
        // Initialisations
        $nombreNotifications = 0;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreNotifications
                            FROM notifications
                            WHERE to_delete = "N" AND (team = "' . $equipe . '" OR team = "") AND (date >= "' . $date1 . '" AND date <= "' . $date2 . '")');

        $data = $req->fetch();

        if ($data['nombreNotifications'] > 0)
            $nombreNotifications = $data['nombreNotifications'];

        $req->closeCursor();

        // Retour
        return $nombreNotifications;
    }

    // PHYSIQUE : Lecture du nombre de notifications en fonction de la vue
    // RETOUR : Nombre de notifications
    function physiqueNombreNotifications($vue, $identifiant, $equipe, $date)
    {
        // Initialisations
        $nombreNotifications = 0;

        // Requête
        global $bdd;

        switch ($vue)
        {
            case 'me':
                $req = $bdd->query('SELECT COUNT(*) AS nombreNotifications
                                    FROM notifications
                                    WHERE to_delete = "N" AND (team = "' . $equipe . '" OR team = "") AND (identifiant = "' . $identifiant . '" OR content = "' . $identifiant . '")');
                break;

            case 'week':
                $req = $bdd->query('SELECT COUNT(*) AS nombreNotifications
                                    FROM notifications
                                    WHERE to_delete = "N" AND (team = "' . $equipe . '" OR team = "") AND date <= ' . date('Ymd') . ' AND date > ' . $date);
                break;

            case 'all':
            default:
                $req = $bdd->query('SELECT COUNT(*) AS nombreNotifications
                                    FROM notifications
                                    WHERE to_delete = "N" AND team = "' . $equipe . '" OR team = ""');
                break;
        }

        $data = $req->fetch();

        if ($data['nombreNotifications'] > 0)
            $nombreNotifications = $data['nombreNotifications'];

        $req->closeCursor();

        // Retour
        return $nombreNotifications;
    }

    // PHYSIQUE : Lecture des notifications en fonction de la vue
    // RETOUR : Liste des notifications
    function physiqueNotifications($vue, $identifiant, $equipe, $date, $premiereEntree, $nombreParPage)
    {
        // Initialisations
        $listeNotifications = array();

        // Requête
        global $bdd;

        switch ($vue)
        {
            case 'me':
                $req = $bdd->query('SELECT *
                                    FROM notifications
                                    WHERE to_delete = "N" AND (team = "' . $equipe . '" OR team = "") AND (identifiant = "' . $identifiant . '" OR content = "' . $identifiant . '")
                                    ORDER BY date DESC, time DESC, id DESC
                                    LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
                break;

            case 'today':
                $req = $bdd->query('SELECT *
                                    FROM notifications
                                    WHERE to_delete = "N" AND (team = "' . $equipe . '" OR team = "") AND date = ' . date('Ymd') . '
                                    ORDER BY time DESC, id DESC');
                break;

            case 'week':
                $req = $bdd->query('SELECT *
                                    FROM notifications
                                    WHERE to_delete = "N" AND (team = "' . $equipe . '" OR team = "") AND date <= ' . date('Ymd') . ' AND date >= ' . $date . '
                                    ORDER BY date DESC, time DESC, id DESC
                                    LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
                break;

            case 'all':
            default:
                $req = $bdd->query('SELECT *
                                    FROM notifications
                                    WHERE to_delete = "N" AND (team = "' . $equipe . '" OR team = "")
                                    ORDER BY date DESC, time DESC, id DESC
                                    LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
                break;
        }

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Notification à partir des données remontées de la bdd
            $notification = Notification::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeNotifications, $notification);
        }

        $req->closeCursor();

        // Retour
        return $listeNotifications;
    }

    // PHYSIQUE : Lecture des utilisateurs inscrits
    // RETOUR : Liste des utilisateurs
    function physiqueUsers($equipe)
    {
        // Initialisations
        $listeUsers = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT users.id, users.identifiant, users.team, users.pseudo
                            FROM users
                            LEFT JOIN notifications ON (notifications.identifiant = users.identifiant OR notifications.content = users.identifiant)
                            WHERE users.identifiant != "admin" AND (users.team = "' . $equipe . '" OR notifications.team = "' . $equipe . '" OR notifications.team = "")
                            ORDER BY users.identifiant ASC');

        while ($data = $req->fetch())
        {
            // Création tableau de correspondance identifiant / pseudo
            $listeUsers[$data['identifiant']] = $data['pseudo'];
        }

        $req->closeCursor();

        // Retour
        return $listeUsers;
    }

    // PHYSIQUE : Lecture film
    // RETOUR : Objet Movie
    function physiqueFilm($idFilm)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM movie_house
                            WHERE id = ' . $idFilm);

        $data = $req->fetch();

        // Instanciation d'un objet Movie à partir des données remontées de la bdd
        $film = Movie::withData($data);

        $req->closeCursor();

        // Retour
        return $film;
    }

    // PHYSIQUE : Lecture calendrier
    // RETOUR : Objet Calendrier
    function physiqueCalendrier($idCalendrier)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM calendars
                            WHERE id = ' . $idCalendrier);

        $data = $req->fetch();

        // Instanciation d'un objet Calendrier à partir des données remontées de la bdd
        $calendrier = Calendrier::withData($data);

        $req->closeCursor();

        // Retour
        return $calendrier;
    }

    // PHYSIQUE : Lecture annexe
    // RETOUR : Objet Annexe
    function physiqueAnnexe($idAnnexe)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM calendars_annexes
                            WHERE id = ' . $idAnnexe);

        $data = $req->fetch();

        // Instanciation d'un objet Annexe à partir des données remontées de la bdd
        $annexe = Annexe::withData($data);

        $req->closeCursor();

        // Retour
        return $annexe;
    }

    // PHYSIQUE : Lecture phrase / image culte
    // RETOUR : Objet Collector
    function physiqueCollector($idCollector)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM collector
                            WHERE id = ' . $idCollector);

        $data = $req->fetch();

        // Instanciation d'un objet Collector à partir des données remontées de la bdd
        $collector = Collector::withData($data);

        $req->closeCursor();

        // Retour
        return $collector;
    }

    // PHYSIQUE : Lecture idée
    // RETOUR : Objet Idea
    function physiqueIdee($idIdee)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM ideas
                            WHERE id = ' . $idIdee);

        $data = $req->fetch();

        // Instanciation d'un objet Idea à partir des données remontées de la bdd
        $idee = Idea::withData($data);

        $req->closeCursor();

        // Retour
        return $idee;
    }

    // PHYSIQUE : Lecture mission
    // RETOUR : Objet Mission
    function physiqueMission($idMission)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM missions
                            WHERE id = ' . $idMission);

        $data = $req->fetch();

        // Instanciation d'un objet Mission à partir des données remontées de la bdd
        $mission = Mission::withData($data);

        $req->closeCursor();

        // Retour
        return $mission;
    }

    // PHYSIQUE : Lecture recette
    // RETOUR : Objet WeekCake
    function physiqueRecette($idRecette)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM cooking_box
                            WHERE id = ' . $idRecette);

        $data = $req->fetch();

        // Instanciation d'un objet WeekCake à partir des données remontées de la bdd
        $recette = WeekCake::withData($data);

        $req->closeCursor();

        // Retour
        return $recette;
    }

    // PHYSIQUE : Lecture parcours
    // RETOUR : Objet Parcours
    function physiqueParcours($idParcours)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM petits_pedestres_parcours
                            WHERE id = ' . $idParcours);

        $data = $req->fetch();

        // Instanciation d'un objet Parcours à partir des données remontées de la bdd
        $parcours = Parcours::withData($data);

        $req->closeCursor();

        // Retour
        return $parcours;
    }

    // PHYSIQUE : Lecture position phrase / image culte dans la table
    // RETOUR : Position
    function physiquePositionCollector($idCollector, $equipe)
    {
        // Initialisations
        $position = 1;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT id, date_collector
                            FROM collector
                            WHERE team = "' . $equipe . '"
                            ORDER BY date_collector DESC, id DESC');

        while ($data = $req->fetch())
        {
            if ($idCollector == $data['id'])
                break;
            else
                $position++;
        }

        $req->closeCursor();

        // Retour
        return $position;
    }

    // PHYSIQUE : Lecture position de l'idée en fonction de la vue
    // RETOUR : Position de l'idée
    function physiquePositionIdee($vue, $idIdee, $equipe, $identifiant)
    {
        // Initialisations
        $positionIdee = 1;

        // Requête
        global $bdd;

        switch ($vue)
        {
            case 'done':
                $req = $bdd->query('SELECT id, date
                                    FROM ideas
                                    WHERE team = "' . $equipe . '" AND (status = "D" OR status = "R")
                                    ORDER BY date DESC, id DESC'
                );
                break;

            case 'inprogress':
                $req = $bdd->query('SELECT id, date
                                    FROM ideas
                                    WHERE team = "' . $equipe . '" AND (status = "O" OR status = "C" OR status = "P")
                                    ORDER BY date DESC, id DESC'
                );
                break;

            case 'mine':
                $req = $bdd->query('SELECT id, date
                                    FROM ideas
                                    WHERE (status = "O" OR status = "C" OR status = "P") AND team = "' . $equipe . '" AND developper = "' . $identifiant . '"
                                    ORDER BY date DESC, id DESC'
                );
                break;

            case 'all':
            default:
                $req = $bdd->query('SELECT id, date
                                    FROM ideas
                                    WHERE team = "' . $equipe . '"
                                    ORDER BY date DESC, id DESC'
                );
                break;
        }

        while ($data = $req->fetch())
        {
            // Incrémentation de la position jusqu'à trouver l'enregistrement
            if ($idIdee == $data['id'])
                break;
            else
                $positionIdee++;
        }

        $req->closeCursor();

        // Retour
        return $positionIdee;
    }
?>