<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture alerte utilisateurs
  // RETOUR : Booléen
  function physiqueAlerteUsers()
  {
    // Initialisations
    $alert = false;

    global $bdd;

    // Requête
    $req = $bdd->query('SELECT COUNT(*) AS nombreStatusUsers
                        FROM users
                        WHERE identifiant != "admin" AND (status = "Y" OR status = "I" OR status = "D")
                        ORDER BY identifiant ASC');
    $data = $req->fetch();

    if ($data['nombreStatusUsers'] > 0)
      $alert = true;

    $req->closeCursor();

    // Retour
    return $alert;
  }

  // PHYSIQUE : Lecture alerte films
  // RETOUR : Booléen
  function physiqueAlerteFilms()
  {
    // Initialisations
    $alert = false;

    global $bdd;

    // Requête
    $req = $bdd->query('SELECT COUNT(*) AS nombreFilmsToDelete
                        FROM movie_house
                        WHERE to_delete = "Y"');
    $data = $req->fetch();

    if ($data['nombreFilmsToDelete'] > 0)
      $alert = true;

    $req->closeCursor();

    // Retour
    return $alert;
  }

  // PHYSIQUE : Lecture alerte calendriers
  // RETOUR : Booléen
  function physiqueAlerteCalendars()
  {
    // Initialisations
    $alert = false;

    global $bdd;

    // Requête
    $req = $bdd->query('SELECT COUNT(*) AS nombreCalendarsToDelete
                        FROM calendars
                        WHERE to_delete = "Y"');
    $data = $req->fetch();

    if ($data['nombreCalendarsToDelete'] > 0)
      $alert = true;

    $req->closeCursor();

    // Retour
    return $alert;
  }

  // PHYSIQUE : Lecture alerte annexes
  // RETOUR : Booléen
  function physiqueAlerteAnnexes()
  {
    // Initialisations
    $alert = false;

    global $bdd;

    // Requête
    $req = $bdd->query('SELECT COUNT(*) AS nombreAnnexesToDelete
                        FROM calendars_annexes
                        WHERE to_delete = "Y"');
    $data = $req->fetch();

    if ($data['nombreAnnexesToDelete'] > 0)
      $alert = true;

    $req->closeCursor();

    // Retour
    return $alert;
  }

  // PHYSIQUE : Lecture du nombre de bugs
  // RETOUR : Nombre de bugs
  function physiqueNombreBugs()
  {
    // Initialisations
    $nombre_bugs = 0;

    global $bdd;

    // Requête
    $req = $bdd->query('SELECT COUNT(*) AS nombreBugs
                        FROM bugs
                        WHERE type = "B" AND resolved = "N"');
    $data = $req->fetch();

    $nombre_bugs = $data['nombreBugs'];

    $req->closeCursor();

    // Retour
    return $nombre_bugs;
  }

  // PHYSIQUE : Lecture du nombre d'évolutions
  // RETOUR : Nombre d'évolutions
  function physiqueNombreEvolutions()
  {
    // Initialisations
    $nombre_evolutions = 0;

    global $bdd;

    // Requête
    $req = $bdd->query('SELECT COUNT(*) AS nombreEvolutions
                        FROM bugs
                        WHERE type = "E" AND resolved = "N"');
    $data = $req->fetch();

    $nombre_evolutions = $data['nombreEvolutions'];

    $req->closeCursor();

    // Retour
    return $nombre_evolutions;
  }
?>
