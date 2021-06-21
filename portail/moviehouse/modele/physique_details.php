<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture film disponible
  // RETOUR : Booléen
  function physiqueFilmDisponible($idFilm, $equipe)
  {
    // Initialisations
    $filmExistant = false;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                        FROM movie_house
                        WHERE id = ' . $idFilm . ' AND to_delete != "Y" AND team = "' . $equipe . '"');

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $filmExistant = true;

    $req->closeCursor();

    // Retour
    return $filmExistant;
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

  // PHYSIQUE : Lecture film précédent existant
  // RETOUR : Booléen
  function physiqueFilmPrecedentExistant($idFilm, $titreFilm, $anneeFilm, $equipe, $dateTheater)
  {
    // Initialisations
    $filmPrecedentExistant = false;

    // Requête
    global $bdd;

    if (!empty($dateTheater))
    {
      $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                          FROM movie_house
                          WHERE SUBSTR(date_theater, 1, 4) = "' . $anneeFilm . '" AND team = "' . $equipe . '" AND (date_theater < "' . $dateTheater . '" OR (date_theater = "' . $dateTheater . '" AND film < "' . $titreFilm . '")) AND id != ' . $idFilm . ' AND to_delete != "Y"');
    }
    else
    {
      $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                          FROM movie_house
                          WHERE date_theater = "" AND team = "' . $equipe . '" AND film < "' . $titreFilm . '" AND id != ' . $idFilm . ' AND to_delete != "Y"');
    }

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $filmPrecedentExistant = true;

    $req->closeCursor();

    // Retour
    return $filmPrecedentExistant;
  }

  // PHYSIQUE : Lecture film précédent
  // RETOUR : Objet Movie
  function physiqueFilmPrecedent($idFilm, $titreFilm, $anneeFilm, $equipe, $dateTheater)
  {
    // Initialisations
    $filmPrecedent = NULL;

    // Requête
    global $bdd;

    if (!empty($dateTheater))
    {
      $req = $bdd->query('SELECT *
                          FROM movie_house
                          WHERE SUBSTR(date_theater, 1, 4) = "' . $anneeFilm . '" AND team = "' . $equipe . '" AND (date_theater < "' . $dateTheater . '" OR (date_theater = "' . $dateTheater . '" AND film < "' . $titreFilm . '")) AND id != ' . $idFilm . ' AND to_delete != "Y"
                          ORDER BY date_theater DESC, film DESC
                          LIMIT 1');
    }
    else
    {
      $req = $bdd->query('SELECT *
                          FROM movie_house
                          WHERE date_theater = "" AND team = "' . $equipe . '" AND film < "' . $titreFilm . '" AND id != ' . $idFilm . ' AND to_delete != "Y"
                          ORDER BY film DESC
                          LIMIT 1');
    }

    $data = $req->fetch();

    // Instanciation d'un objet Movie à partir des données remontées de la bdd
    $filmPrecedent = Movie::withData($data);

    $req->closeCursor();

    // Retour
    return $filmPrecedent;
  }

  // PHYSIQUE : Lecture film précédent existant
  // RETOUR : Booléen
  function physiqueFilmSuivantExistant($idFilm, $titreFilm, $anneeFilm, $equipe, $dateTheater)
  {
    // Initialisations
    $filmSuivantExistant = false;

    // Requête
    global $bdd;

    if (!empty($dateTheater))
    {
      $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                          FROM movie_house
                          WHERE SUBSTR(date_theater, 1, 4) = "' . $anneeFilm . '" AND team = "' . $equipe . '" AND (date_theater > "' . $dateTheater . '" OR (date_theater = "' . $dateTheater . '" AND film > "' . $titreFilm . '")) AND id != ' . $idFilm . ' AND to_delete != "Y"');
    }
    else
    {
      $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                          FROM movie_house
                          WHERE date_theater = "" AND team = "' . $equipe . '" AND film > "' . $titreFilm . '" AND id != ' . $idFilm . ' AND to_delete != "Y"');
    }

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $filmSuivantExistant = true;

    $req->closeCursor();

    // Retour
    return $filmSuivantExistant;
  }

  // PHYSIQUE : Lecture film suivant
  // RETOUR : Objet Movie
  function physiqueFilmSuivant($idFilm, $titreFilm, $anneeFilm, $equipe, $dateTheater)
  {
    // Initialisations
    $filmSuivant = NULL;

    // Requête
    global $bdd;

    if (!empty($dateTheater))
    {
      $req = $bdd->query('SELECT *
                          FROM movie_house
                          WHERE SUBSTR(date_theater, 1, 4) = "' . $anneeFilm . '" AND team = "' . $equipe . '" AND (date_theater > "' . $dateTheater . '" OR (date_theater = "' . $dateTheater . '" AND film > "' . $titreFilm . '")) AND id != ' . $idFilm . ' AND to_delete != "Y"
                          ORDER BY date_theater ASC, film ASC
                          LIMIT 1');
    }
    else
    {
      $req = $bdd->query('SELECT *
                          FROM movie_house
                          WHERE date_theater = "" AND team = "' . $equipe . '" AND film > "' . $titreFilm . '" AND id != ' . $idFilm . ' AND to_delete != "Y"
                          ORDER BY film ASC
                          LIMIT 1');
    }

    $data = $req->fetch();

    // Instanciation d'un objet Movie à partir des données remontées de la bdd
    $filmSuivant = Movie::withData($data);

    $req->closeCursor();

    // Retour
    return $filmSuivant;
  }

  // PHYSIQUE : Lecture des utilisateurs inscrits
  // RETOUR : Liste des utilisateurs
  function physiqueUsersDetailsFilm($idFilm, $equipe)
  {
    // Initialisations
    $listeUsers = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, team, pseudo, avatar, email
                        FROM users
                        WHERE (identifiant != "admin" AND status != "I" AND team = "' . $equipe .'")
                        OR EXISTS (SELECT id, id_film, author
                                   FROM movie_house_comments
                                   WHERE movie_house_comments.author = users.identifiant AND movie_house_comments.id_film = "' . $idFilm . '")
                        ORDER BY identifiant ASC');

    while ($data = $req->fetch())
    {
      // Création tableau de correspondance identifiant / pseudo / avatar
      $listeUsers[$data['identifiant']] = array('pseudo' => $data['pseudo'],
                                                'avatar' => $data['avatar'],
                                                'email'  => $data['email']
                                               );
    }

    $req->closeCursor();

    // Retour
    return $listeUsers;
  }

  // PHYSIQUE : Lecture des commentaires d'un film
  // RETOUR : Liste des commentaires
  function physiqueCommentaires($idFilm)
  {
    // Initialisations
    $listeCommentaires = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM movie_house_comments
                        WHERE id_film = ' . $idFilm . '
                        ORDER BY id ASC');

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

  // PHYSIQUE : Lecture des commentaires du jour
  // RETOUR : Booléen
  function physiqueDernierCommentaireJour($idFilm)
  {
    // Initialisations
    $dernierCommentaireJour = false;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreCommentaires
                        FROM movie_house_comments
                        WHERE id_film = ' . $idFilm . ' AND date = ' . date('Ymd'));

    $data = $req->fetch();

    if ($data['nombreCommentaires'] == 0)
      $dernierCommentaireJour = true;

    $req->closeCursor();

    // Retour
    return $dernierCommentaireJour;
  }

  /****************************************************************************/
  /********************************** INSERT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Insertion commentaire utilisateur
  // RETOUR : Aucun
  function physiqueInsertionCommentaire($commentaire)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO movie_house_comments(id_film,
                                                           author,
                                                           date,
                                                           time,
                                                           comment)
                                                   VALUES(:id_film,
                                                          :author,
                                                          :date,
                                                          :time,
                                                          :comment)');

    $req->execute($commentaire);

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Mise à jour film
  // RETOUR : Aucun
  function physiqueUpdateFilm($idFilm, $film)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE movie_house
                          SET film         = :film,
                              synopsis     = :synopsis,
                              date_theater = :date_theater,
                              date_release = :date_release,
                              link         = :link,
                              poster       = :poster,
                              trailer      = :trailer,
                              id_url       = :id_url,
                              doodle       = :doodle,
                              date_doodle  = :date_doodle,
                              time_doodle  = :time_doodle,
                              restaurant   = :restaurant,
                              place        = :place
                          WHERE id = ' . $idFilm);

    $req->execute($film);

    $req->closeCursor();
  }

  // PHYSIQUE : Mise à jour commentaire
  // RETOUR : Aucun
  function physiqueUpdateCommentaire($idCommentaire, $commentaire)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE movie_house_comments
                          SET comment = :comment
                          WHERE id = ' . $idCommentaire);

    $req->execute(array(
      'comment' => $commentaire
    ));

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** DELETE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Suppression commentaire
  // RETOUR : Aucun
  function physiqueDeleteCommentaire($idCommentaire)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM movie_house_comments
                       WHERE id = ' . $idCommentaire);
  }
?>
