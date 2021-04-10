<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture nombre de lignes existantes pour une année
  // RETOUR : Booléen
  function physiqueAnneeExistante($annee)
  {
    // Initialisations
    $anneeExistante = false;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                        FROM calendars
                        WHERE year = "' . $annee . '" AND to_delete != "Y"');

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $anneeExistante = true;

    $req->closeCursor();

    // Retour
    return $anneeExistante;
  }

  // PHYSIQUE : Lecture des années existantes
  // RETOUR : Liste des années
  function physiqueOnglets()
  {
    // Initialisations
    $onglets = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT DISTINCT year
                        FROM calendars
                        WHERE to_delete != "Y"
                        ORDER BY year DESC');

    while ($data = $req->fetch())
    {
      // On ajoute la ligne au tableau
      array_push($onglets, $data['year']);
    }

    $req->closeCursor();

    // Retour
    return $onglets;
  }

  // PHYSIQUE : Lecture des calendriers
  // RETOUR : Liste des calendriers
  function physiqueCalendriers($annee)
  {
    // Initialisations
    $listeCalendriers = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM calendars
                        WHERE year = ' . $annee . ' AND to_delete != "Y"
                        ORDER BY month DESC, id DESC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Calendrier à partir des données remontées de la bdd
      $calendrier = Calendrier::withData($data);

      // Titre du calendrier
      $calendrier->setTitle(formatMonthForDisplayStrong($calendrier->getMonth())) ;

      // On ajoute la ligne au tableau
      array_push($listeCalendriers, $calendrier);
    }

    $req->closeCursor();

    // Retour
    return $listeCalendriers;
  }

  // PHYSIQUE : Lecture des annexes
  // RETOUR : Liste des annexes
  function physiqueAnnexes()
  {
    // Initialisations
    $listeAnnexes = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM calendars_annexes
                        WHERE to_delete != "Y"
                        ORDER BY id DESC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Annexe à partir des données remontées de la bdd
      $annexe = Annexe::withData($data);

      // On ajoute la ligne au tableau
      array_push($listeAnnexes, $annexe);
    }

    $req->closeCursor();

    // Retour
    return $listeAnnexes;
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

  /****************************************************************************/
  /********************************** INSERT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Insertion nouveau calendrier
  // RETOUR : Id calendrier
  function physiqueInsertionCalendrier($calendar)
  {
    // Initialisations
    $newId = NULL;

    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO calendars(to_delete,
                                                month,
                                                year,
                                                calendar)
                                        VALUES(:to_delete,
                                               :month,
                                               :year,
                                               :calendar)');

    $req->execute($calendar);

    $req->closeCursor();

    $newId = $bdd->lastInsertId();

    // Retour
    return $newId;
  }

  // PHYSIQUE : Insertion nouvelle annexe
  // RETOUR : Id annexe
  function physiqueInsertionAnnexe($annexe)
  {
    // Initialisations
    $newId = NULL;
    
    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO calendars_annexes(to_delete,
                                                        annexe,
                                                        title)
                                                VALUES(:to_delete,
                                                       :annexe,
                                                       :title)');

    $req->execute($annexe);

    $req->closeCursor();

    $newId = $bdd->lastInsertId();

    // Retour
    return $newId;
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
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
?>
