<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture préférences utilisateurs
  // RETOUR : Préférences utilisateurs
  function physiqueAutorisationsCalendars()
  {
    // Initialisations
    $listAutorisations = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM preferences
                        ORDER BY identifiant ASC');

    while ($data = $req->fetch())
    {
      $myAutorisation = array('identifiant'      => $data['identifiant'],
                              'pseudo'           => '',
                              'manage_calendars' => $data['manage_calendars']
                             );

      // On ajoute la ligne au tableau
      array_push($listAutorisations, $myAutorisation);
    }

    $req->closeCursor();

    // Retour
    return $listAutorisations;
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

  // PHYSIQUE : Lecture des utilisateurs
  // RETOUR : Liste des utilisateurs
  function physiqueUsers()
  {
    // Initialisations
    $listUsers = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant
                        FROM users
                        WHERE identifiant != "admin"
                        ORDER BY identifiant ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Profile à partir des données remontées de la bdd
      $user = Profile::withData($data);

      // On ajoute la ligne au tableau
      array_push($listUsers, $user);
    }

    $req->closeCursor();

    // Retour
    return $listUsers;
  }

  // PHYSIQUE : Lecture liste des calendriers à supprimer
  // RETOUR : Liste des calendriers
  function physiqueCalendarsToDelete($listMonths)
  {
    // Initialisations
    $listCalendarsToDelete = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM calendars
                        WHERE to_delete = "Y"
                        ORDER BY year DESC, month DESC, id DESC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Calendrier à partir des données remontées de la bdd
      $myCalendar = Calendrier::withData($data);

      // Titre du calendrier
      $myCalendar->setTitle($listMonths[$myCalendar->getMonth()] . ' ' . $myCalendar->getYear());

      // On ajoute la ligne au tableau
      array_push($listCalendarsToDelete, $myCalendar);
    }

    $req->closeCursor();

    // Retour
    return $listCalendarsToDelete;
  }

  // PHYSIQUE : Lecture liste des annexes à supprimer
  // RETOUR : Liste des annexes
  function physiqueAnnexesToDelete()
  {
    // Initialisations
    $listAnnexesToDelete = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM calendars_annexes
                        WHERE to_delete = "Y"
                        ORDER BY id DESC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Annexe à partir des données remontées de la bdd
      $myAnnexe = Annexe::withData($data);

      // On ajoute la ligne au tableau
      array_push($listAnnexesToDelete, $myAnnexe);
    }

    $req->closeCursor();

    // Retour
    return $listAnnexesToDelete;
  }

  // PHYSIQUE : Lecture alerte calendriers
  // RETOUR : Booléen
  function physiqueAlerteCalendars()
  {
    // Initialisations
    $alert = false;

    // Requête
    global $bdd;

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

    // Requête
    global $bdd;

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

  // PHYSIQUE : Lecture données élément Calendars
  // RETOUR : Objet Calendars ou Annexe
  function physiqueDonneesCalendars($idCalendars, $table)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM ' . $table . '
                        WHERE id = ' . $idCalendars);

    $data = $req->fetch();

    // Instanciation d'un objet Calendrier ou Annexe à partir des données remontées de la bdd
    if ($table == 'calendars')
      $calendars = Calendrier::withData($data);
    else
      $calendars = Annexe::withData($data);

    $req->closeCursor();

    // Retour
    return $calendars;
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Mise à jour de la préférence utilisateur
  // RETOUR : Aucun
  function physiqueUpdateAutorisationsCalendars($identifiant, $manageCalendars)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE preferences
                          SET manage_calendars = :manage_calendars
                          WHERE identifiant = "' . $identifiant . '"');

    $req->execute(array(
      'manage_calendars' => $manageCalendars
    ));

    $req->closeCursor();
  }

  // PHYSIQUE : Mise à jour du statut du calendrier ou de l'annexe
  // RETOUR : Aucun
  function physiqueUpdateStatusCalendars($table, $idCalendars, $toDelete)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE ' . $table . '
                          SET to_delete = :to_delete
                          WHERE id = ' . $idCalendars);

    $req->execute(array(
      'to_delete' => $toDelete
    ));

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** DELETE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Suppression calendrier
  // RETOUR : Aucun
  function physiqueDeleteCalendrier($idCalendars)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM calendars
                       WHERE id = ' . $idCalendars);
  }

  // PHYSIQUE : Suppression annexe
  // RETOUR : Aucun
  function physiqueDeleteAnnexe($idCalendars)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM calendars_annexes
                       WHERE id = ' . $idCalendars);
  }
?>
