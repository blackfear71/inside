<?php
  include_once('../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture des sorties organisées le jour-même
  // RETOUR : Liste des films avec sortie le jour-même
  function physiqueSortiesOrganisees()
  {
    // Initialisations
    $listeFilmsSorties = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM movie_house
                        WHERE date_doodle = ' . date('Ymd') . '
                        ORDER BY id ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Movie à partir des données remontées de la bdd
      $filmSortie = Movie::withData($data);

      // On ajoute la ligne au tableau
      array_push($listeFilmsSorties, $filmSortie);
    }

    $req->closeCursor();

    // Retour
    return $listeFilmsSorties;
  }

  // PHYSIQUE : Lecture des missions
  // RETOUR : Liste des données missions
  function physiqueDureesMissions()
  {
    // Initialisations
    $dureesMissions = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM missions
                        WHERE date_deb = ' . date('Ymd') . ' OR date_fin = ' . date('Ymd'));

    while ($data = $req->fetch())
    {
      // Création du tableau des données missions
      if ($data['date_deb'] == $data['date_fin'])
      {
        // Mission unique
        $mission = array('id_mission' => $data['id'],
                         'mission'    => $data['mission'],
                         'duration'   => 'O'
                        );
      }
      elseif (date('Ymd') != $data['date_fin'] AND date('Ymd') == $data['date_deb'])
      {
        // Premier jour
        $mission = array('id_mission' => $data['id'],
                         'mission'    => $data['mission'],
                         'duration'   => 'F'
                        );
      }
      elseif (date('Ymd') != $data['date_deb'] AND date('Ymd') == $data['date_fin'])
      {
        // Dernier jour
        $mission = array('id_mission' => $data['id'],
                         'mission'    => $data['mission'],
                         'duration'   => 'L'
                        );
      }
      else
      {
        // Aucune notification
        $mission = array('id_mission' => $data['id'],
                         'mission'    => $data['mission'],
                         'duration'   => 'N'
                        );
      }

      // On ajoute la ligne au tableau
      array_push($dureesMissions, $mission);
    }

    $req->closeCursor();

    // Retour
    return $dureesMissions;
  }

  // PHYSIQUE : Lecture des missions se terminant la veille
  // RETOUR : Liste des missions
  function physiqueFinsMissionsVeille($date)
  {
    // Initialisations
    $listeMissions = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM missions
                        WHERE date_fin = "' . $date . '"');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Movie à partir des données remontées de la bdd
      $mission = Mission::withData($data);

      // On ajoute la ligne au tableau
      array_push($listeMissions, $mission);
    }

    $req->closeCursor();

    // Retour
    return $listeMissions;
  }

  // PHYSIQUE : Lecture des participants d'une mission
  // RETOUR : Liste des participants
  function physiqueParticipantsMission($idMission)
  {
    // Initialisations
    $listeParticipants = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM missions_users
                        WHERE id_mission = ' . $idMission . '
                        ORDER BY identifiant ASC');

    while ($data = $req->fetch())
    {
      // Création du tableau des données participants
      if (!isset($listeParticipants[$data['identifiant']]) OR empty($listeParticipants[$data['identifiant']]))
      {
        $listeParticipants[$data['identifiant']] = array('avancement' => intval($data['avancement']),
                                                         'rank'       => 0
                                                        );
      }
      else
      {
        $listeParticipants[$data['identifiant']] = array('avancement' => $listeParticipants[$data['identifiant']]['avancement'] + intval($data['avancement']),
                                                         'rank'       => 0
                                                        );
      }
    }

    $req->closeCursor();

    // Retour
    return $listeParticipants;
  }

  // PHYSIQUE : Lecture des utilisateurs inscrits
  // RETOUR : Liste des utilisateurs
  function physiqueUsers()
  {
    // Initialisations
    $listeUsers = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant
                        FROM users
                        WHERE identifiant != "admin" AND status != "I"
                        ORDER BY identifiant ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Profile à partir des données remontées de la bdd
      $user = Profile::withData($data);

      // On ajoute la ligne au tableau
      array_push($listeUsers, $user);
    }

    $req->closeCursor();

    // Retour
    return $listeUsers;
  }

  // PHYSIQUE : Lecture des dépenses
  // RETOUR : Liste des dépenses
  function physiqueDepenses()
  {
    // Initialisations
    $listeDepenses = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM expense_center
                        ORDER BY id ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Expenses à partir des données remontées de la bdd
      $depense = Expenses::withData($data);

      // On ajoute la ligne au tableau
      array_push($listeDepenses, $depense);
    }

    $req->closeCursor();

    // Retour
    return $listeDepenses;
  }

  // PHYSIQUE : Lecture des ^parts d'une dépense
  // RETOUR : Nombre de parts et de participants
  function physiqueNombresParts($idDepense, $identifiant)
  {
    // Initialisations
    $nombresParts = array('nombre_parts_total' => 0,
                          'nombre_parts_user'  => 0,
                          'nombre_users'       => 0
                         );

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM expense_center_users
                        WHERE id_expense = ' . $idDepense);

    while ($data = $req->fetch())
    {
      // Nombre de parts total
      $nombresParts['nombre_parts_total'] += $data['parts'];

      // Nombre de parts de l'utilisateur
      if ($data['identifiant'] == $identifiant)
        $nombresParts['nombre_parts_user'] = $data['parts'];

      // Nombre de participants
      $nombresParts['nombre_users'] += 1;
    }

    $req->closeCursor();

    // Retour
    return $nombresParts;
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Mise à jour bilan d'un utilisateur
  // RETOUR : Aucun
  function physiqueUpdateBilanDepensesUser($identifiant, $bilan)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE users
                          SET expenses = :expenses
                          WHERE identifiant = "' . $identifiant . '"');

    $req->execute(array(
      'expenses' => $bilan
    ));

    $req->closeCursor();
  }
?>
