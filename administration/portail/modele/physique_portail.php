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

    // Requête
    global $bdd;

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

    // Requête
    global $bdd;

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

  // PHYSIQUE : Lecture du nombre de bugs
  // RETOUR : Nombre de bugs
  function physiqueNombreBugs()
  {
    // Initialisations
    $nombreBugs = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreBugs
                        FROM bugs
                        WHERE type = "B" AND resolved = "N"');

    $data = $req->fetch();

    $nombreBugs = $data['nombreBugs'];

    $req->closeCursor();

    // Retour
    return $nombreBugs;
  }

  // PHYSIQUE : Lecture du nombre d'évolutions
  // RETOUR : Nombre d'évolutions
  function physiqueNombreEvolutions()
  {
    // Initialisations
    $nombreEvolutions = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreEvolutions
                        FROM bugs
                        WHERE type = "E" AND resolved = "N"');

    $data = $req->fetch();

    $nombreEvolutions = $data['nombreEvolutions'];

    $req->closeCursor();

    // Retour
    return $nombreEvolutions;
  }

  // PHYSIQUE : Lecture de toutes les tables de la base
  // RETOUR : Tables de la base
  function physiqueTablesBdd()
  {
    // Initialisations
    $listeTables = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SHOW TABLES');

    while ($data = $req->fetch())
    {
      // On ajoute la ligne au tableau
      array_push($listeTables, $data[0]);
    }

    $req->closeCursor();

    // Retour
    return $listeTables;
  }

  // PHYSIQUE : Lecture des dimensions d'une table
  // RETOUR : Dimensions d'une table
  function physiqueDimensionsTable($table)
  {
    // Initialisations
    $dimensionsTable = array('nombre_colonnes' => 0,
                             'nombre_lignes'   => 0
                            );

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM ' . $table);

    $dimensionsTable['nombre_colonnes'] = $req->columnCount();
    $dimensionsTable['nombre_lignes']   = $req->rowCount();

    $req->closeCursor();

    // Retour
    return $dimensionsTable;
  }

  // PHYSIQUE : Lecture du CREATE TABLE d'une table
  // RETOUR : CREATE TABLE d'une table
  function physiqueCreateTable($table)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SHOW CREATE TABLE ' . $table);

    $data = $req->fetch();

    $createTable = "\n\n" . $data[1] . ";\n\n";

    $req->closeCursor();

    // Retour
    return $createTable;
  }

  // PHYSIQUE : Lecture du contenu d'une table
  // RETOUR : Contenu d'une table
  function physiqueContenuTable($table, $dimensionsTable)
  {
    // Initialisations
    $contenu = '';

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM ' . $table);

    for ($i = 0, $lignesInserees = 0; $i < $dimensionsTable['nombre_colonnes']; $i++, $lignesInserees = 0)
    {
      while ($data = $req->fetch())
      {
        // Toutes les 100 lignes insérées
        if ($lignesInserees == 0 || $lignesInserees % 100 == 0)
          $contenu .= "\nINSERT INTO " . $table . ' VALUES';

        // Parcours de chaque colonne d'une ligne
        $contenu .= "\n(";

        for ($j = 0; $j < $dimensionsTable['nombre_colonnes']; $j++)
        {
          $data[$j] = str_replace("\n","\\n", addslashes($data[$j]));

          if (isset($data[$j]))
            $contenu .= '"' . $data[$j] . '"' ;
          else
            $contenu .= '""';

          if ($j < ($dimensionsTable['nombre_colonnes'] - 1))
            $contenu .= ',';
        }

        $contenu .= ')';

        // Avant la 100ème ligne parcourue ou à la dernière ligne
        if (($lignesInserees != 0 && ($lignesInserees + 1) % 100 == 0) || $lignesInserees + 1 == $dimensionsTable['nombre_lignes'])
          $contenu .= ';';
        else
          $contenu .= ',';

        // Incrémentation du nombre de lignes récupérées
        $lignesInserees = $lignesInserees + 1;
      }
    }

    $req->closeCursor();

    // Retour
    return $contenu;
  }
?>
