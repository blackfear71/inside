<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
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

  // PHYSIQUE : Lecture des actions de l'utilisateur (étoiles et participation)
  // RETOUR : Tableau des actions
  function physiqueActionsUser($idFilm, $identifiant)
  {
    // Initialisations
    $actionsUser = array('etoiles'       => 0,
                         'participation' => ''
                        );

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM movie_house_users
                        WHERE id_film = ' . $idFilm . ' AND identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    if (isset($data['stars']))
      $actionsUser['etoiles'] = $data['stars'];

    if (isset($data['participation']))
      $actionsUser['participation'] = $data['participation'];

    $req->closeCursor();

    // Retour
    return $actionsUser;
  }

  // PHYSIQUE : Lecture du nombre de participants d'un film
  // RETOUR : Nombre de participants
  function physiqueNombreParticipants($idFilm)
  {
    // Initialisations
    $nombreParticipants = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreParticipants
                        FROM movie_house_users
                        WHERE id_film = ' . $idFilm);

    $data = $req->fetch();

    if ($data['nombreParticipants'] > 0)
      $nombreParticipants = $data['nombreParticipants'];

    $req->closeCursor();

    // Retour
    return $nombreParticipants;
  }

  // PHYSIQUE : Lecture étoile existante
  // RETOUR : Booléen
  function physiqueEtoileExistante($idFilm, $identifiant)
  {
    // Initialisations
    $etoileExistante = false;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                        FROM movie_house_users
                        WHERE id_film = ' . $idFilm . ' AND   identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $etoileExistante = true;

    $req->closeCursor();

    // Retour
    return $etoileExistante;
  }

  // PHYSIQUE : Lecture participation utilisateur
  // RETOUR : Etat de la participation
  function physiqueParticipation($idFilm, $identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM movie_house_users
                        WHERE id_film = ' . $idFilm . ' AND identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    $participation = $data['participation'];

    $req->closeCursor();

    // Retour
    return $participation;
  }

  // PHYSIQUE : Lecture des utilisateurs inscrits
  // RETOUR : Liste des utilisateurs
  function physiqueUsers($equipe)
  {
    // Initialisations
    $listeUsers = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, team, pseudo, avatar, email
                        FROM users
                        WHERE identifiant != "admin" AND status != "I" AND team = "' . $equipe .'"
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

  // PHYSIQUE : Lecture des étoiles d'un film
  // RETOUR : Liste des étoiles
  function physiqueEtoilesFilm($idFilm)
  {
    // Initialisations
    $listeEtoilesFilm = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM movie_house_users
                        WHERE id_film = ' . $idFilm . '
                        ORDER BY stars DESC, identifiant ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Stars à partir des données remontées de la bdd
      $etoile = Stars::withData($data);

      // On ajoute la ligne au tableau
      array_push($listeEtoilesFilm, $etoile);
    }

    $req->closeCursor();

    // Retour
    return $listeEtoilesFilm;
  }

  /****************************************************************************/
  /********************************** INSERT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Insertion étoile utilisateur
  // RETOUR : Aucun
  function physiqueInsertionEtoile($etoile)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO movie_house_users(id_film,
                                                        identifiant,
                                                        stars,
                                                        participation)
                                                VALUES(:id_film,
                                                       :identifiant,
                                                       :stars,
                                                       :participation)');

    $req->execute($etoile);

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Mise à jour étoile utilisateur
  // RETOUR : Aucun
  function physiqueUpdateEtoile($idFilm, $identifiant, $preference)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE movie_house_users
                          SET stars = :stars
                          WHERE id_film = ' . $idFilm . ' AND identifiant = "' . $identifiant . '"');

    $req->execute(array(
      'stars' => $preference
    ));

    $req->closeCursor();
  }

  // PHYSIQUE : Mise à jour participation utilisateur
  // RETOUR : Aucun
  function physiqueUpdateParticipation($idFilm, $identifiant, $participation)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE movie_house_users
                          SET participation = :participation
                          WHERE id_film = ' . $idFilm . ' AND identifiant = "' . $identifiant . '"');

    $req->execute(array(
      'participation' => $participation
    ));

    $req->closeCursor();
  }

  // PHYSIQUE : Mise à jour du statut du film
  // RETOUR : Aucun
  function physiqueUpdateStatusFilm($idFilm, $toDelete, $identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE movie_house
                          SET to_delete       = :to_delete,
                              identifiant_del = :identifiant_del
                          WHERE id = ' . $idFilm);

    $req->execute(array(
      'to_delete'       => $toDelete,
      'identifiant_del' => $identifiant
    ));

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** DELETE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Suppression étoile utilisateur
  // RETOUR : Aucun
  function physiqueDeleteEtoile($idFilm, $identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM movie_house_users
                       WHERE id_film = ' . $idFilm . ' AND identifiant = "' . $identifiant . '"');
  }
?>
