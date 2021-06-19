<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture du nombre d'idée en fonction de la vue
  // RETOUR : Nombre d'idées
  function physiqueNombreIdees($vue, $equipe, $identifiant)
  {
    // Initialisations
    $nombreIdees = 0;

    // Requête
    global $bdd;

    switch ($vue)
    {
      case 'inprogress':
        $req = $bdd->query('SELECT COUNT(*) AS nombreIdees
                            FROM ideas
                            WHERE team = "' . $equipe . '" AND (status = "O" OR status = "C" OR status = "P")');
        break;

      case 'mine':
        $req = $bdd->query('SELECT COUNT(*) AS nombreIdees
                            FROM ideas
                            WHERE team = "' . $equipe . '" AND (status = "O" OR status = "C" OR status = "P") AND developper = "' . $identifiant . '"');
        break;

      case 'done':
        $req = $bdd->query('SELECT COUNT(*) AS nombreIdees
                            FROM ideas
                            WHERE team = "' . $equipe . '" AND (status = "D" OR status = "R")');
        break;

      case 'all':
      default:
        $req = $bdd->query('SELECT COUNT(*) AS nombreIdees
                            FROM ideas
                            WHERE team = "' . $equipe . '"');
        break;
    }

    $data = $req->fetch();

    if (isset($data['nombreIdees']))
      $nombreIdees = $data['nombreIdees'];

    $req->closeCursor();

    // Retour
    return $nombreIdees;
  }

  // PHYSIQUE : Lecture des idées en fonction de la vue
  // RETOUR : Liste des idées
  function physiqueIdees($vue, $premiereEntree, $nombreParPage, $equipe, $identifiant)
  {
    // Initialisations
    $listeIdees = array();

    // Requête
    global $bdd;

    switch ($vue)
    {
      case 'done':
        $req = $bdd->query('SELECT *
                            FROM ideas
                            WHERE team = "' . $equipe . '" AND (status = "D" OR status = "R")
                            ORDER BY date DESC, id DESC
                            LIMIT ' . $premiereEntree . ', ' . $nombreParPage
                          );
        break;

      case 'inprogress':
        $req = $bdd->query('SELECT *
                            FROM ideas
                            WHERE team = "' . $equipe . '" AND (status = "O" OR status = "C" OR status = "P")
                            ORDER BY date DESC, id DESC
                            LIMIT ' . $premiereEntree . ', ' . $nombreParPage
                          );
        break;

      case 'mine':
        $req = $bdd->query('SELECT *
                            FROM ideas
                            WHERE team = "' . $equipe . '" AND (status = "O" OR status = "C" OR status = "P") AND developper = "' . $identifiant . '"
                            ORDER BY date DESC, id DESC
                            LIMIT ' . $premiereEntree . ', ' . $nombreParPage
                          );
        break;

      case 'all':
      default:
        $req = $bdd->query('SELECT *
                            FROM ideas
                            WHERE team = "' . $equipe . '"
                            ORDER BY date DESC, id DESC
                            LIMIT ' . $premiereEntree . ', ' . $nombreParPage
                          );
        break;
    }

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Idea à partir des données remontées de la bdd
      $idee = Idea::withData($data);

      // On ajoute la ligne au tableau
      array_push($listeIdees, $idee);
    }

    $req->closeCursor();

    // Retour
    return $listeIdees;
  }

  // PHYSIQUE : Lecture des données d'un utilisateur
  // RETOUR : Objet Profile
  function physiqueUser($identifiant)
  {
    // Initialisations
    $user = NULL;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, pseudo, avatar, COUNT(*) AS nombreUser
                        FROM users
                        WHERE identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    // Instanciation d'un objet Profile à partir des données remontées de la bdd
    if ($data['nombreUser'] > 0)
      $user = Profile::withData($data);

    $req->closeCursor();

    // Retour
    return $user;
  }

  // PHYSIQUE : Lecture d'une idée
  // RETOUR : Objet Idea
  function physiqueIdee($idIdee)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM ideas
                        WHERE id = ' . $idIdee);

    $data = $req->fetch();

    // Instanciation d'un objet Idea à partir des données remontées de la bdd
    $idee = Idea::withData($data);

    $req->closeCursor();

    // Retour
    return $idee;
  }

  // PHYSIQUE : Lecture position de l'idée en fonction de la vue
  // RETOUR : Position de l'idée
  function physiquePositionIdee($vue, $idIdee, $equipe, $identifiant)
  {
    // Initialisations
    $positionIdee = 1;

    // Requête
    global $bdd;

    switch ($vue)
    {
      case 'done':
        $req = $bdd->query('SELECT id, date
                            FROM ideas
                            WHERE team = "' . $equipe . '" AND (status = "D" OR status = "R")
                            ORDER BY date DESC, id DESC'
                          );
        break;

      case 'inprogress':
        $req = $bdd->query('SELECT id, date
                            FROM ideas
                            WHERE team = "' . $equipe . '" AND (status = "O" OR status = "C" OR status = "P")
                            ORDER BY date DESC, id DESC'
                          );
        break;

      case 'mine':
        $req = $bdd->query('SELECT id, date
                            FROM ideas
                            WHERE team = "' . $equipe . '" AND (status = "O" OR status = "C" OR status = "P") AND developper = "' . $identifiant . '"
                            ORDER BY date DESC, id DESC'
                          );
        break;

      case 'all':
      default:
        $req = $bdd->query('SELECT id, date
                            FROM ideas
                            WHERE team = "' . $equipe . '"
                            ORDER BY date DESC, id DESC'
                          );
        break;
    }

    while ($data = $req->fetch())
    {
      // Incrémentation de la position jusqu'à trouver l'enregistrement
      if ($idIdee == $data['id'])
        break;
      else
        $positionIdee++;
    }

    $req->closeCursor();

    // Retour
    return $positionIdee;
  }

  /****************************************************************************/
  /********************************** INSERT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Insertion nouvelle idée
  // RETOUR : Id idée
  function physiqueInsertionIdee($idee)
  {
    // Initialisations
    $newId = NULL;

    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO ideas(team,
                                            subject,
                                            date,
                                            author,
                                            content,
                                            status,
                                            developper)
                                    VALUES(:team,
                                           :subject,
                                           :date,
                                           :author,
                                           :content,
                                           :status,
                                           :developper)');

    $req->execute($idee);

    $req->closeCursor();

    $newId = $bdd->lastInsertId();

    // Retour
    return $newId;
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Mise à jour idée
  // RETOUR : Aucun
  function physiqueUpdateIdee($idIdee, $idee)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE ideas
                          SET status     = :status,
                              developper = :developper
                          WHERE id = ' . $idIdee);

    $req->execute($idee);

    $req->closeCursor();
  }
?>
