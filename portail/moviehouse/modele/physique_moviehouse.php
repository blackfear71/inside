<?php
    include_once('../../includes/functions/appel_bdd.php');

    /****************************************************************************/
    /********************************** SELECT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Lecture nombre de lignes existantes pour une année
    // RETOUR : Booléen
    function physiqueAnneeExistante($annee, $equipe)
    {
        // Initialisations
        $anneeExistante = false;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                            FROM movie_house
                            WHERE SUBSTR(date_theater, 1, 4) = "' . $annee . '" AND team = "' . $equipe . '" AND to_delete != "Y"');

        $data = $req->fetch();

        if ($data['nombreLignes'] > 0)
            $anneeExistante = true;

        $req->closeCursor();

        // Retour
        return $anneeExistante;
    }

    // PHYSIQUE : Lecture nombre de lignes existantes sans année
    // RETOUR : Booléen
    function physiqueSansAnnee($equipe)
    {
        // Initialisations
        $anneeExistante = false;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                            FROM movie_house
                            WHERE date_theater = "" AND team = "' . $equipe . '" AND to_delete != "Y"');

        $data = $req->fetch();

        if ($data['nombreLignes'] > 0)
            $anneeExistante = true;

        $req->closeCursor();

        // Retour
        return $anneeExistante;
    }

    // PHYSIQUE : Lecture des années existantes
    // RETOUR : Liste des années
    function physiqueOnglets($equipe)
    {
        // Initialisations
        $onglets = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT DISTINCT SUBSTR(date_theater, 1, 4)
                            FROM movie_house
                            WHERE to_delete != "Y" AND team = "' . $equipe . '"
                            ORDER BY SUBSTR(date_theater, 1, 4) DESC');

        while ($data = $req->fetch())
        {
            // On ajoute la ligne au tableau
            array_push($onglets, $data['SUBSTR(date_theater, 1, 4)']);
        }

        $req->closeCursor();

        // Retour
        return $onglets;
    }

    // PHYSIQUE : Lecture des films récents
    // RETOUR : Liste des films récents
    function physiqueFilmsRecents($annee, $equipe, $limite)
    {
        // Initialisations
        $listeFilmsRecents = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM movie_house
                            WHERE to_delete != "Y" AND SUBSTR(date_add, 1, 4) = "' . $annee . '" AND team = "' . $equipe . '"
                            ORDER BY SUBSTR(date_add, 1, 4) DESC, id DESC
                            LIMIT ' . $limite);

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Movie à partir des données remontées de la bdd
            $filmRecent = Movie::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeFilmsRecents, $filmRecent);
        }

        $req->closeCursor();

        // Retour
        return $listeFilmsRecents;
    }

    // PHYSIQUE : Lecture des films qui sortent dans la semaine
    // RETOUR : Liste des films qui sortent dans la semaine
    function physiqueFilmsSemaine($jourDebut, $jourFin, $equipe)
    {
        // Initialisations
        $listeFilmsSemaine = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM movie_house
                            WHERE to_delete != "Y" AND team = "' . $equipe . '" AND date_theater >= "' . $jourDebut . '" AND date_theater <= "' . $jourFin . '"
                            ORDER BY date_theater ASC, id DESC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Movie à partir des données remontées de la bdd
            $filmSemaine = Movie::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeFilmsSemaine, $filmSemaine);
        }

        $req->closeCursor();

        // Retour
        return $listeFilmsSemaine;
    }

    // PHYSIQUE : Lecture des films de l'année recherchée
    // RETOUR : Liste des films de l'année
    function physiqueFilmsAnnee($annee, $dateJourMoins1Mois, $equipe)
    {
        // Initialisations
        $listeFilmsAnnee = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM movie_house
                            WHERE to_delete != "Y" AND SUBSTR(date_theater, 1, 4) = "' . $annee . '" AND team = "' . $equipe . '"
                            ORDER BY date_theater ASC');

        while ($data = $req->fetch())
        {
            // On récupère les films si ce n'est pas l'année courante ou jusqu'à un mois en arrière si c'est l'année courante
            if ($annee != date('Y') OR ($annee == date('Y') AND $data['date_theater'] > $dateJourMoins1Mois))
            {
                // Instanciation d'un objet Movie à partir des données remontées de la bdd
                $filmAnnee = Movie::withData($data);

                // On ajoute la ligne au tableau
                array_push($listeFilmsAnnee, $filmAnnee);
            }
        }

        $req->closeCursor();

        // Retour
        return $listeFilmsAnnee;
    }

    // PHYSIQUE : Lecture des statistiques d'un film
    // RETOUR : Statistiques d'un film
    function physiqueStatsFilm($idFilm)
    {
        // Initialisations
        $statsFilm = array(
            'nombre_users'  => 0,
            'total_etoiles' => 0
        );

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM movie_house_users
                            WHERE id_film = ' . $idFilm);

        while ($data = $req->fetch())
        {
            $statsFilm['nombre_users']  += 1;
            $statsFilm['total_etoiles'] += $data['stars'];
        }

        $req->closeCursor();

        // Retour
        return $statsFilm;
    }

    // PHYSIQUE : Lecture des sorties organisées pour une année
    // RETOUR : Liste des films avec sortie
    function physiqueSortiesOrganisees($annee, $equipe, $limite)
    {
        // Initialisations
        $listeFilmsSorties = array();

        // Requête
        global $bdd;

        if ($annee == date('Y'))
        {
            $req = $bdd->query('SELECT *
                                FROM movie_house
                                WHERE to_delete != "Y" AND team = "' . $equipe . '" AND date_doodle != "" AND date_doodle >= ' . date('Ymd') . ' AND SUBSTR(date_doodle, 1, 4) = ' . $annee . '
                                ORDER BY date_doodle ASC, id DESC LIMIT ' . $limite);
        }
        elseif ($annee > date('Y'))
        {
            $req = $bdd->query('SELECT *
                                FROM movie_house
                                WHERE to_delete != "Y" AND team = "' . $equipe . '" AND date_doodle != "" AND SUBSTR(date_doodle, 1, 4) = ' . $annee . '
                                ORDER BY date_doodle ASC, id DESC LIMIT ' . $limite);
        }
        elseif ($annee < date('Y'))
        {
            $req = $bdd->query('SELECT *
                                FROM movie_house
                                WHERE to_delete != "Y" AND team = "' . $equipe . '" AND date_doodle != "" AND SUBSTR(date_doodle, 1, 4) = ' . $annee . '
                                ORDER BY date_doodle ASC, id DESC');
        }

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Movie à partir des données remontées de la bdd
            $filmSortie = Movie::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeFilmsSorties, $filmSortie);
        }

        $req->closeCursor();

        // Retour
        return $listeFilmsSorties;
    }

    // PHYSIQUE : Lecture des films de l'année
    // RETOUR : Liste des films
    function physiqueFilms($annee, $equipe)
    {
        // Initialisations
        $listeFilms = array();

        // Requête
        global $bdd;

        if ($annee == 'none')
        {
            $req = $bdd->query('SELECT *
                                FROM movie_house
                                WHERE date_theater = "" AND to_delete != "Y" AND team = "' . $equipe . '"
                                ORDER BY date_add DESC, film ASC');
        }
        else
        {
            $req = $bdd->query('SELECT *
                                FROM movie_house
                                WHERE SUBSTR(date_theater, 1, 4) = "' . $annee . '" AND to_delete != "Y" AND team = "' . $equipe . '"
                                ORDER BY date_theater ASC, film ASC');
        }

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Movie à partir des données remontées de la bdd
            $film = Movie::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeFilms, $film);
        }

        $req->closeCursor();

        // Retour
        return $listeFilms;
    }

    // PHYSIQUE : Lecture du nombre de commentaires d'un film
    // RETOUR : Nombre de commentaires
    function physiqueNombreCommentaires($idFilm)
    {
        // Initialisations
        $nombreCommentaires = 0;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreCommentaires
                            FROM movie_house_comments
                            WHERE id_film = "' . $idFilm . '"');

        $data = $req->fetch();

        if ($data['nombreCommentaires'] > 0)
            $nombreCommentaires = $data['nombreCommentaires'];

        $req->closeCursor();

        // Retour
        return $nombreCommentaires;
    }

    /****************************************************************************/
    /********************************** INSERT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Insertion nouveau film
    // RETOUR : Id film
    function physiqueInsertionFilm($film)
    {
        // Initialisations
        $newId = NULL;

        // Requête
        global $bdd;

        $req = $bdd->prepare('INSERT INTO movie_house(to_delete,
                                                      team,
                                                      film,
                                                      date_add,
                                                      identifiant_add,
                                                      identifiant_del,
                                                      synopsis,
                                                      date_theater,
                                                      date_release,
                                                      link,
                                                      poster,
                                                      trailer,
                                                      id_url,
                                                      doodle,
                                                      date_doodle,
                                                      time_doodle,
                                                      restaurant,
                                                      place)
                                              VALUES(:to_delete,
                                                     :team,
                                                     :film,
                                                     :date_add,
                                                     :identifiant_add,
                                                     :identifiant_del,
                                                     :synopsis,
                                                     :date_theater,
                                                     :date_release,
                                                     :link,
                                                     :poster,
                                                     :trailer,
                                                     :id_url,
                                                     :doodle,
                                                     :date_doodle,
                                                     :time_doodle,
                                                     :restaurant,
                                                     :place)');

        $req->execute($film);

        $req->closeCursor();

        $newId = $bdd->lastInsertId();

        // Retour
        return $newId;
    }
?>