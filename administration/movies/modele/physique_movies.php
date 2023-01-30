<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture liste des films à supprimer
  // RETOUR : Liste des films à supprimer
  function physiqueFilmsToDelete()
  {
    // Initialisations
    $listeFilmsToDelete = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, film, to_delete, team, identifiant_add, identifiant_del, poster
                        FROM movie_house
                        WHERE to_delete = "Y"
                        ORDER BY id ASC');

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

  // PHYSIQUE : Lecture des informations utilisateur
  // RETOUR : Pseudo utilisateur
  function physiquePseudoUser($identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, pseudo
                        FROM users
                        WHERE identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    $pseudo = $data['pseudo'];

    $req->closeCursor();

    // Retour
    return $pseudo;
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

  // PHYSIQUE : Lecture identifiant ajout film
  // RETOUR : Identifiant
  function physiqueIdentifiantAjoutFilm($idFilm)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM movie_house
                        WHERE id = ' . $idFilm);

    $data = $req->fetch();

    $identifiant = $data['identifiant_add'];

    $req->closeCursor();

    // Retour
    return $identifiant;
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
  function physiqueDeleteFilms($idFilm)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM movie_house
                       WHERE id = ' . $idFilm );
  }
?>
