<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture alerte équipes
  // RETOUR : Booléen
  function physiqueAlerteEquipes()
  {
    // Initialisations
    $alert = false;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreEquipes
                        FROM teams
                        WHERE activation = "Y" AND NOT EXISTS (SELECT id, identifiant, team
                                                               FROM users
                                                               WHERE teams.reference = users.team)');

    $data = $req->fetch();

    if ($data['nombreEquipes'] > 0)
      $alert = true;

    $req->closeCursor();

    // Retour
    return $alert;
  }

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
                        WHERE identifiant != "admin" AND (status = "P" OR status = "I" OR status = "D" OR status = "T")
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
                             'nombre_lignes'   => 0,
                             'colonnes'        => '',
                             'types'           => array()
                            );

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM ' . $table);

    $dimensionsTable['nombre_colonnes'] = $req->columnCount();
    $dimensionsTable['nombre_lignes']   = $req->rowCount();

    // Récupération des colonnes et de leurs types
    for ($i = 0; $i < $dimensionsTable['nombre_colonnes']; $i++)
    {
      $dimensionsTable['colonnes'] .= '`' . $req->getColumnMeta($i)['name'] . '`';

      if ($i < ($dimensionsTable['nombre_colonnes'] - 1))
        $dimensionsTable['colonnes'] .= ', ';

      array_push($dimensionsTable['types'], $req->getColumnMeta($i)['native_type']);
    }

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
        // Si c'est la première ligne, on insère une instruction INSERT INTO)
        if ($lignesInserees == 0)
          $contenu .= "\nINSERT INTO `" . $table . '` (' . $dimensionsTable['colonnes'] . ') VALUES';

        // Parcours de chaque colonne d'une ligne
        $contenu .= "\n(";

        for ($j = 0; $j < $dimensionsTable['nombre_colonnes']; $j++)
        {
          $data[$j] = str_replace("\r\n","\\r\\n", addslashes($data[$j]));

          if (isset($data[$j]))
          {
            // Les INT et FLOAT restent au format numérique
            if ($dimensionsTable['types'][$j] == 'LONG' OR $dimensionsTable['types'][$j] == 'FLOAT')
              $contenu .= $data[$j];
            else
              $contenu .= '\'' . $data[$j] . '\'';
          }
          else
            $contenu .= '\'\'';

          if ($j < ($dimensionsTable['nombre_colonnes'] - 1))
            $contenu .= ', ';
        }

        $contenu .= ')';

        // Si c'est la dernière ligne, on termine l'instruction INSERT INTO
        if ($lignesInserees + 1 == $dimensionsTable['nombre_lignes'])
          $contenu .= ";\n\n";
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
