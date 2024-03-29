<?php
    include_once('../../includes/functions/appel_bdd.php');

    /****************************************************************************/
    /********************************** SELECT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Lecture des informations utilisateur
    // RETOUR : Pseudo utilisateur
    function physiquePseudoUser($identifiant)
    {
        // Initialisations
        $pseudo = '';

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT id, identifiant, pseudo
                            FROM users
                            WHERE identifiant = "' . $identifiant . '"');

        $data = $req->fetch();

        if (!empty($data))
            $pseudo = $data['pseudo'];

        $req->closeCursor();

        // Retour
        return $pseudo;
    }

    // PHYSIQUE : Lecture préférences utilisateur
    // RETOUR : Objet Preferences
    function physiquePreferences($identifiant)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM preferences
                            WHERE identifiant = "' . $identifiant . '"');

        $data = $req->fetch();

        // Instanciation d'un objet Preferences à partir des données remontées de la bdd
        $preference = Preferences::withData($data);

        $req->closeCursor();

        // Retour
        return $preference;
    }

    // PHYSIQUE : Lecture anniversaires utilisateurs
    // RETOUR : Pseudos
    function physiqueNewsAnniversaires($equipe)
    {
        // Initialisations
        $anniversaires = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT id, identifiant, pseudo, anniversary
                            FROM users
                            WHERE SUBSTR(anniversary, 5, 4) = "' . date('md') . '" AND team = "' . $equipe . '"
                            ORDER BY identifiant ASC');

        while ($data = $req->fetch())
        {
            // On ajoute la ligne au tableau
            array_push($anniversaires, $data['pseudo']);
        }

        $req->closeCursor();

        // Retour
        return $anniversaires;
    }

    // PHYSIQUE : Lecture dernier film ajouté
    // RETOUR : Objet Movie
    function physiqueDernierFilm($equipe)
    {
        // Initialisations
        $film = NULL;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM movie_house
                            WHERE to_delete != "Y" AND team = "' . $equipe . '"
                            ORDER BY date_add DESC, id DESC
                            LIMIT 1');

        $data = $req->fetch();

        if (!empty($data))
        {
            // Instanciation d'un objet Movie à partir des données remontées de la bdd
            $film = Movie::withData($data);
        }

        $req->closeCursor();

        // Retour
        return $film;
    }

    // PHYSIQUE : Lecture film sortie cinéma
    // RETOUR : Objet Movie
    function physiqueSortieFilm($equipe)
    {
        // Initialisations
        $film = NULL;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM movie_house
                            WHERE to_delete != "Y" AND team = "' . $equipe . '" AND date_doodle >= "' . date('Ymd') . '"
                            ORDER BY date_doodle ASC, id ASC
                            LIMIT 1');

        $data = $req->fetch();

        if (!empty($data))
        {
            // Instanciation d'un objet Movie à partir des données remontées de la bdd
            $film = Movie::withData($data);
        }

        $req->closeCursor();

        // Retour
        return $film;
    }

    // PHYSIQUE : Lecture réservation restaurant
    // RETOUR : Id restaurant
    function physiqueRestaurantReserved($equipe)
    {
        // Initialisations
        $nomRestaurant = '';

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT food_advisor_restaurants.name
                            FROM food_advisor_choices
                            LEFT JOIN food_advisor_restaurants ON food_advisor_restaurants.id = food_advisor_choices.id_restaurant
                            WHERE food_advisor_choices.date = "' . date('Ymd') . '" AND food_advisor_choices.reserved = "Y" AND food_advisor_choices.team = "' . $equipe . '"');

        $data = $req->fetch();

        if (!empty($data))
            $nomRestaurant = $data['name'];

        $req->closeCursor();

        // Retour
        return $nomRestaurant;
    }

    // PHYSIQUE : Lecture vote utilisateur
    // RETOUR : Booléen
    function physiqueVoteUser($equipe, $identifiant)
    {
        // Initialisations
        $voted = false;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                            FROM food_advisor_users
                            WHERE date = "' . date('Ymd') . '" AND team = "' . $equipe . '" AND identifiant = "' . $identifiant . '"');

        $data = $req->fetch();

        if ($data['nombreLignes'] > 0)
            $voted = true;

        $req->closeCursor();

        // Retour
        return $voted;
    }

    // PHYSIQUE : Lecture gâteau de la semaine
    // RETOUR : Objet WeekCake
    function physiqueGateauSemaine($equipe)
    {
        // Initialisations
        $gateau = NULL;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT cooking_box.*, users.pseudo
                            FROM cooking_box
                            LEFT JOIN users ON cooking_box.identifiant = users.identifiant
                            WHERE cooking_box.team = "' . $equipe . '" AND cooking_box.week = "' . date('W') . '" AND cooking_box.year = "' . date('Y') . '"');

        $data = $req->fetch();

        if (!empty($data))
        {
            // Instanciation d'un objet WeekCake à partir des données remontées de la bdd
            $gateau = WeekCake::withData($data);

            $gateau->setPseudo($data['pseudo']);
        }

        $req->closeCursor();

        // Retour
        return $gateau;
    }

    // PHYSIQUE : Lecture dernière phrase culte
    // RETOUR : Objet Collector
    function physiqueDernierCollector($equipe)
    {
        // Initialisations
        $collector = NULL;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM collector
                            WHERE type_collector = "T" AND team = "' . $equipe . '"
                            ORDER BY date_add DESC, id DESC
                            LIMIT 1');

        $data = $req->fetch();

        if (!empty($data))
        {
            // Instanciation d'un objet Collector à partir des données remontées de la bdd
            $collector = Collector::withData($data);
        }

        $req->closeCursor();

        // Retour
        return $collector;
    }

    // PHYSIQUE : Lecture position phrase culte
    // RETOUR : Position phrase culte
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
            if ($data['id'] == $idCollector)
                break;
            else
                $position++;
        }

        $req->closeCursor();

        // Retour
        return $position;
    }

    // PHYSIQUE : Lecture missions actives ou récentes
    // RETOUR : Liste des missions
    function physiqueMissionsRecentes($date1, $date2)
    {
        // Initialisations
        $listeMissions = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM missions
                            WHERE date_deb <= "' . $date1 . '" AND date_fin >= "' . $date2 . '"
                            ORDER BY date_deb ASC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Mission à partir des données remontées de la bdd
            $mission = Mission::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeMissions, $mission);
        }

        $req->closeCursor();

        // Retour
        return $listeMissions;
    }

    // PHYSIQUE : Lecture des participants d'une mission
    // RETOUR : Liste des utilisateurs
    function physiqueUsersMission($idMission, $equipe)
    {
        // Initialisations
        $listeUsers = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT missions_users.id, missions_users.id_mission, missions_users.team, missions_users.identifiant, SUM(missions_users.avancement) AS total, users.pseudo
                            FROM missions_users
                            INNER JOIN users ON (users.identifiant = missions_users.identifiant AND users.team = "' . $equipe . '")
                            WHERE missions_users.id_mission = ' . $idMission . ' AND missions_users.team = "' . $equipe . '"
                            GROUP BY missions_users.identifiant
                            ORDER BY missions_users.identifiant ASC');

        while ($data = $req->fetch())
        {
            // Récupération des identifiants
            $user = array(
                'identifiant' => $data['identifiant'],
                'equipe'      => $data['team'],
                'pseudo'      => $data['pseudo'],
                'total'       => $data['total'],
                'rank'        => 0
            );

            // On ajoute la ligne au tableau
            array_push($listeUsers, $user);
        }

        $req->closeCursor();

        // Retour
        return $listeUsers;
    }
?>