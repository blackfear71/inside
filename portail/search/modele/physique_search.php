<?php
    include_once('../../includes/functions/appel_bdd.php');

    /****************************************************************************/
    /********************************** SELECT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Lecture des films pour la recherche
    // RETOUR : Liste des films
    function physiqueRechercheFilms($recherche, $equipe)
    {
        // Initialisations
        $resultatRecherche = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM movie_house
                            WHERE to_delete != "Y" AND team = "' . $equipe . '" AND film LIKE "%' . $recherche . '%"
                            ORDER BY date_theater DESC, film ASC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Movie à partir des données remontées de la bdd
            $movie = Movie::withData($data);

            // On ajoute la ligne au tableau
            array_push($resultatRecherche, $movie);
        }

        $req->closeCursor();

        // Retour
        return $resultatRecherche;
    }

    // PHYSIQUE : Lecture des restaurants pour la recherche
    // RETOUR : Liste des restaurants
    function physiqueRechercheRestaurants($recherche, $equipe)
    {
        // Initialisations
        $resultatRecherche = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM food_advisor_restaurants
                            WHERE team = "' . $equipe . '" AND name LIKE "%' . $recherche . '%"
                            ORDER BY location ASC, name ASC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Restaurant à partir des données remontées de la bdd
            $restaurant = Restaurant::withData($data);

            // On ajoute la ligne au tableau
            array_push($resultatRecherche, $restaurant);
        }

        $req->closeCursor();

        // Retour
        return $resultatRecherche;
    }

    // PHYSIQUE : Lecture des parcours pour la recherche
    // RETOUR : Liste des parcours
    function physiqueRechercheParcours($recherche, $equipe)
    {
        // Initialisations
        $resultatRecherche = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM petits_pedestres_parcours
                            WHERE team = "' . $equipe . '" AND nom LIKE "%' . $recherche . '%"
                            ORDER BY nom ASC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Parcours à partir des données remontées de la bdd
            $parcours = Parcours::withData($data);

            // On ajoute la ligne au tableau
            array_push($resultatRecherche, $parcours);
        }

        $req->closeCursor();

        // Retour
        return $resultatRecherche;
    }

    // PHYSIQUE : Lecture des missions pour la recherche
    // RETOUR : Liste des missions
    function physiqueRechercheMissions($recherche)
    {
        // Initialisations
        $resultatRecherche = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM missions
                            WHERE date_deb <= ' . date('Ymd') . ' AND mission LIKE "%' . $recherche . '%"
                            ORDER BY date_deb DESC, mission ASC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Mission à partir des données remontées de la bdd
            $mission = Mission::withData($data);

            // On ajoute la ligne au tableau
            array_push($resultatRecherche, $mission);
        }

        $req->closeCursor();

        // Retour
        return $resultatRecherche;
    }
?>