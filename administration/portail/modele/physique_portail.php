<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Appel base "users"
  // RETOUR : Booléen
  function physiqueAlerteUsers()
  {
    // Initialisations
    $alert = false;

    global $bdd;

    // Requête
    $req = $bdd->query('SELECT COUNT(*) AS nombre_status_users
                        FROM users
                        WHERE identifiant != "admin" AND (status = "Y" OR status = "I" OR status = "D")
                        ORDER BY identifiant ASC');
    $data = $req->fetch();

    if ($data['nombre_status_users'] > 0)
      $alert = true;

    $req->closeCursor();

    // Retour
    return $alert;
  }

  // PHYSIQUE : Appel base "movie_house"
  // RETOUR : Booléen
  function physiqueAlerteFilms()
  {
    // Initialisations
    $alert = false;

    global $bdd;

    // Requête
    $req = $bdd->query('SELECT COUNT(*) AS nombre_films_to_delete
                        FROM movie_house
                        WHERE to_delete = "Y"');
    $data = $req->fetch();

    if ($data['nombre_films_to_delete'] > 0)
      $alert = true;

    $req->closeCursor();

    // Retour
    return $alert;
  }

  // PHYSIQUE : Appel base "calendars"
  // RETOUR : Booléen
  function physiqueAlerteCalendars()
  {
    // Initialisations
    $alert = false;

    global $bdd;

    // Requête
    $req = $bdd->query('SELECT COUNT(*) AS nombre_calendars_to_delete
                        FROM calendars
                        WHERE to_delete = "Y"');
    $data = $req->fetch();

    if ($data['nombre_calendars_to_delete'] > 0)
      $alert = true;

    $req->closeCursor();

    // Retour
    return $alert;
  }

  // PHYSIQUE : Appel base "calendars_annexes"
  // RETOUR : Booléen
  function physiqueAlerteAnnexes()
  {
    // Initialisations
    $alert = false;

    global $bdd;

    // Requête
    $req = $bdd->query('SELECT COUNT(*) AS nombre_annexes_to_delete
                        FROM calendars_annexes
                        WHERE to_delete = "Y"');
    $data = $req->fetch();

    if ($data['nombre_annexes_to_delete'] > 0)
      $alert = true;

    $req->closeCursor();

    // Retour
    return $alert;
  }

  // PHYSIQUE : Appel base "bugs"
  // RETOUR : Nombre de bugs
  function physiqueNombreBugs()
  {
    // Initialisations
    $nombre_bugs = 0;

    global $bdd;

    // Requête
    $req = $bdd->query('SELECT COUNT(*) AS nombre_bugs
                        FROM bugs
                        WHERE type = "B" AND resolved = "N"');
    $data = $req->fetch();

    $nombre_bugs = $data['nombre_bugs'];

    $req->closeCursor();

    // Retour
    return $nombre_bugs;
  }

  // PHYSIQUE : Appel base "bugs"
  // RETOUR : Nombre d'évolutions
  function physiqueNombreEvolutions()
  {
    // Initialisations
    $nombre_evolutions = 0;

    global $bdd;

    // Requête
    $req = $bdd->query('SELECT COUNT(*) AS nombre_evolutions
                        FROM bugs
                        WHERE type = "E" AND resolved = "N"');
    $data = $req->fetch();

    $nombre_evolutions = $data['nombre_evolutions'];

    $req->closeCursor();

    // Retour
    return $nombre_evolutions;
  }
?>
