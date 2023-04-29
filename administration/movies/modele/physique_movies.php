<?php
    include_once('../../includes/functions/appel_bdd.php');

    /****************************************************************************/
    /********************************** SELECT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Lecture de la liste des équipes activées
    // RETOUR : Liste des équipes
    function physiqueListeEquipes()
    {
        // Initialisations
        $listeEquipes = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM teams
                            WHERE activation = "Y"
                            ORDER BY reference ASC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Team à partir des données remontées de la bdd
            $equipe = Team::withData($data);

            // On ajoute la ligne au tableau
            $listeEquipes[$equipe->getReference()] = $equipe;
        }

        $req->closeCursor();

        // Retour
        return $listeEquipes;
    }

    // PHYSIQUE : Lecture liste des films à supprimer
    // RETOUR : Liste des films à supprimer
    function physiqueFilmsToDelete()
    {
        // Initialisations
        $listeFilmsToDelete = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT movie_house.id, movie_house.film, movie_house.to_delete, movie_house.team, movie_house.identifiant_add, movie_house.identifiant_del, movie_house.poster, U1.pseudo AS pseudo_add, U2.pseudo AS pseudo_del
                            FROM movie_house
                            LEFT JOIN users AS U1 ON movie_house.identifiant_add = U1.identifiant
                            LEFT JOIN users AS U2 ON movie_house.identifiant_del = U2.identifiant
                            WHERE movie_house.to_delete = "Y"
                            ORDER BY movie_house.id ASC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Movie à partir des données remontées de la bdd
            $film = Movie::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeFilmsToDelete, $film);
        }

        $req->closeCursor();

        // Retour
        return $listeFilmsToDelete;
    }

    // PHYSIQUE : Comptage du nombre de participants
    // RETOUR : Nombre de participants
    function physiqueNombreParticipants($idFilm)
    {
        // Initialisations
        $count = 0;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreUsers
                            FROM movie_house_users
                            WHERE id_film = ' . $idFilm);

        $data = $req->fetch();

        $count = $data['nombreUsers'];

        $req->closeCursor();

        // Retour
        return $count;
    }

    // PHYSIQUE : Comptage du nombre de commentaires
    // RETOUR : Nombre de commentaires
    function physiqueNombreCommentaires($idFilm)
    {
        // Initialisations
        $count = 0;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreCommentaires
                            FROM movie_house_comments
                            WHERE id_film = ' . $idFilm);

        $data = $req->fetch();

        $count = $data['nombreCommentaires'];

        $req->closeCursor();

        // Retour
        return $count;
    }

    // PHYSIQUE : Lecture données film
    // RETOUR : Objet Movie
    function physiqueDonneesFilm($idFilm)
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

    // PHYSIQUE : Lecture commentaires film
    // RETOUR : Liste des commentaires
    function physiqueCommentairesFilms($idFilm)
    {
        // Initialisations
        $listeCommentaires = array();
        
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM movie_house_comments
                            WHERE id_film = ' . $idFilm);

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Commentaire à partir des données remontées de la bdd
            $commentaire = Commentaire::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeCommentaires, $commentaire);
        }

        $req->closeCursor();

        // Retour
        return $listeCommentaires;
    }

    // PHYSIQUE : Lecture étoiles film
    // RETOUR : Liste des étoiles
    function physiqueEtoilesFilms($idFilm)
    {
        // Initialisations
        $listeEtoiles = array();
        
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM movie_house_users
                            WHERE id_film = ' . $idFilm);

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Stars à partir des données remontées de la bdd
            $etoile = Stars::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeEtoiles, $etoile);
        }

        $req->closeCursor();

        // Retour
        return $listeEtoiles;
    }

    /****************************************************************************/
    /********************************** UPDATE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Mise à jour statut film
    // RETOUR : Aucun
    function physiqueResetFilm($idFilm, $toDelete, $identifiantDel)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE movie_house
                              SET to_delete = :to_delete,
                                  identifiant_del = :identifiant_del
                              WHERE id = ' . $idFilm);

        $req->execute(array(
            'to_delete'       => $toDelete,
            'identifiant_del' => $identifiantDel
        ));

        $req->closeCursor();
    }

    /****************************************************************************/
    /********************************** DELETE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Suppression avis film
    // RETOUR : Aucun
    function physiqueDeleteAvisFilms($idFilm)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM movie_house_users
                           WHERE id_film = ' . $idFilm);
    }

    // PHYSIQUE : Suppression commentaires film
    // RETOUR : Aucun
    function physiqueDeleteCommentsFilms($idFilm)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM movie_house_comments
                           WHERE id_film = ' . $idFilm);
    }

    // PHYSIQUE : Suppression film
    // RETOUR : Aucun
    function physiqueDeleteFilm($idFilm)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM movie_house
                           WHERE id = ' . $idFilm);
    }
?>