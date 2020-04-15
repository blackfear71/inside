<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture liste des utilisateurs
  // RETOUR : Liste des utilisateurs
  function physiqueUsers()
  {
    // Initialisations
    $listUsers = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, ping, status, pseudo, avatar, email, anniversary, experience
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

  // PHYSIQUE : Lecture liste des succès
  // RETOUR : Liste des succès
  function physiqueListeSuccess()
  {
    // Initialisations
    $listSuccess = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM success
                        ORDER BY level ASC, order_success ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Success à partir des données remontées de la bdd
      $mySuccess = Success::withData($data);

      // On ajoute la ligne au tableau
      array_push($listSuccess, $mySuccess);
    }

    $req->closeCursor();

    // Retour
    return $listSuccess;
  }

  // PHYSIQUE : Lecture du nombre de références existantes
  // RETOUR : Booléen
  function physiqueReferenceUnique($reference)
  {
    // Initialisations
    $isUnique = true;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreReferences
                        FROM success
                        WHERE reference = "' . $reference . '"');

    $data = $req->fetch();

    if ($data['nombreReferences'] > 0)
      $isUnique = false;

    $req->closeCursor();

    // Retour
    return $isUnique;
  }

  // PHYSIQUE : Lecture du nombre d'ordonnancements existants
  // RETOUR : Booléen
  function physiqueOrdonnancementUnique($niveau, $ordre)
  {
    // Initialisations
    $isUnique = true;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreOrdres
                        FROM success
                        WHERE level = "' . $niveau . '" AND order_success = "' . $ordre . '"');

    $data = $req->fetch();

    if ($data['nombreOrdres'] > 0)
      $isUnique = false;

    $req->closeCursor();

    // Retour
    return $isUnique;
  }

  // PHYSIQUE : Lecture données succès
  // RETOUR : Objet Success
  function physiqueSuccess($idSuccess)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM success
                        WHERE id = ' . $idSuccess);

    $data = $req->fetch();

    // Instanciation d'un objet Success à partir des données remontées de la bdd
    $success = Success::withData($data);

    $req->closeCursor();

    // Retour
    return $success;
  }

  // PHYSIQUE : Lecture valeur succès
  // RETOUR : Valeur du succès
  function physiqueValueSuccess($table, $listConditions, $valueColumn)
  {
    // Initialisations
    $value = NULL;
    $where = '';

    // Construction de la requête
    foreach ($listConditions as $condition)
    {
      if (!empty($condition['operator']))
        $where .= ' ' . $condition['operator'] . ' ' . $condition['column'] . ' ' . $condition['test'] . ' "' . $condition['value'] . '"';
      else
        $where .= $condition['column'] . ' ' . $condition['test'] . ' "' . $condition['value'] . '"';
    }

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *, COUNT(*) AS nombreLignes
                        FROM ' . $table . '
                        WHERE ' . $where);

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $value = $data[$valueColumn];

    $req->closeCursor();

    // Retour
    return $value;
  }

  // PHYSIQUE : Comptage de lignes pour un succès
  // RETOUR : Nombre de lignes
  function physiqueCountSuccess($table, $listConditions)
  {
    // Initialisations
    $value = 0;
    $where = '';

    // Construction de la requête
    foreach ($listConditions as $condition)
    {
      if (!empty($condition['operator']))
      {
        if ($condition['column'] == 'id')
          $where .= ' ' . $condition['operator'] . ' ' . $condition['column'] . ' ' . $condition['test'] . ' ' . $condition['value'];
        else
          $where .= ' ' . $condition['operator'] . ' ' . $condition['column'] . ' ' . $condition['test'] . ' "' . $condition['value'] . '"';
      }
      else
      {
        if ($condition['column'] == 'id')
          $where .= $condition['column'] . ' ' . $condition['test'] . ' ' . $condition['value'];
        else
          $where .= $condition['column'] . ' ' . $condition['test'] . ' "' . $condition['value'] . '"';
      }
    }

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                        FROM ' . $table . '
                        WHERE ' . $where);

    $data = $req->fetch();

    $value = $data['nombreLignes'];

    $req->closeCursor();

    // Retour
    return $value;
  }

  // PHYSIQUE : Somme de lignes pour un succès
  // RETOUR : Somme des lignes
  function physiqueSumSuccess($table, $listConditions, $sumColumn)
  {
    // Initialisations
    $value = 0;
    $where = '';

    // Construction de la requête
    foreach ($listConditions as $condition)
    {
      if (!empty($condition['operator']))
      {
        if ($condition['column'] == 'id')
          $where .= ' ' . $condition['operator'] . ' ' . $condition['column'] . ' ' . $condition['test'] . ' ' . $condition['value'];
        else
          $where .= ' ' . $condition['operator'] . ' ' . $condition['column'] . ' ' . $condition['test'] . ' "' . $condition['value'] . '"';
      }
      else
      {
        if ($condition['column'] == 'id')
          $where .= $condition['column'] . ' ' . $condition['test'] . ' ' . $condition['value'];
        else
          $where .= $condition['column'] . ' ' . $condition['test'] . ' "' . $condition['value'] . '"';
      }
    }

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM ' . $table . '
                        WHERE ' . $where);

    while ($data = $req->fetch())
    {
      $value += $data[$sumColumn];
    }

    $req->closeCursor();

    // Retour
    return $value;
  }

  // PHYSIQUE : Récupération du succès "self-satisfied"
  // RETOUR : Valeur du succès
  function physiqueSelfSatisfiedSuccess($identifiant)
  {
    // Initialisations
    $value = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nombreAutoVotes
                        FROM collector
                        LEFT JOIN collector_users
                        ON (collector.id = collector_users.id_collector AND collector_users.identifiant = "' . $identifiant . '")
                        WHERE collector.speaker = "' . $identifiant . '"');

    $data = $req->fetch();

    $value = $data['nombreAutoVotes'];

    $req->closeCursor();

    // Retour
    return $value;
  }

  // PHYSIQUE : Récupération du succès "buyer"
  // RETOUR : Valeur du succès
  function physiqueBuyerSuccess($identifiant)
  {
    // Initialisations
    $value = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(expense_center.id) AS nombreAchats
                        FROM expense_center
                        WHERE (expense_center.buyer = "' . $identifiant . '" AND expense_center.price > 0)
                        AND EXISTS (SELECT *
                                    FROM expense_center_users
                                    WHERE (expense_center.id = expense_center_users.id_expense))');

    $data = $req->fetch();

    $value = $data['nombreAchats'];

    $req->closeCursor();

    // Retour
    return $value;
  }

  // PHYSIQUE : Récupération du succès "generous"
  // RETOUR : Valeur du succès
  function physiqueGenerousSuccess($identifiant)
  {
    // Initialisations
    $value = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(expense_center.id) AS nombreDepensesSansParts
                        FROM expense_center
                        WHERE (expense_center.buyer = "' . $identifiant . '" AND expense_center.price > 0)
                        AND NOT EXISTS (SELECT *
                                        FROM expense_center_users
                                        WHERE (expense_center.id = expense_center_users.id_expense AND expense_center_users.identifiant = "' . $identifiant . '"))');

    $data = $req->fetch();

    $value = $data['nombreDepensesSansParts'];

    $req->closeCursor();

    // Retour
    return $value;
  }

  // PHYSIQUE : Récupération du bilan d'un utilisateur
  // RETOUR : Bilan des dépenses
  function physiqueBilanUser($identifiant)
  {
    // Initialisations
    $bilan = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, expenses
                        FROM users
                        WHERE identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    $bilan = $data['expenses'];

    $req->closeCursor();

    // Retour
    return $bilan;
  }

  // PHYSIQUE : Récupération date de sortie film
  // RETOUR : Date de sortie film
  function physiqueDateSortieFilm($idFilm)
  {
    // Initialisations
    $dateSortie = '';

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, date_theater
                        FROM movie_house
                        WHERE id = ' . $idFilm);

    $data = $req->fetch();

    $dateSortie = $data['date_theater'];

    $req->closeCursor();

    // Retour
    return $dateSortie;
  }

  // PHYSIQUE : Récupération données mission
  // RETOUR : Objet Mission
  function physiqueDonneesMission($reference)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM missions
                        WHERE reference = "' . $reference . '"');

    $data = $req->fetch();

    $mission = Mission::withData($data);

    $req->closeCursor();

    // Retour
    return $mission;
  }

  /****************************************************************************/
  /********************************** INSERT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Insertion nouveau succès
  // RETOUR : Aucun
  function physiqueInsertionSuccess($success)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO success(reference,
                                              level,
                                              order_success,
                                              defined,
                                              unicity,
                                              title,
                                              description,
                                              limit_success,
                                              explanation)
                                      VALUES(:reference,
                                             :level,
                                             :order_success,
                                             :defined,
                                             :unicity,
                                             :title,
                                             :description,
                                             :limit_success,
                                             :explanation)');

    $req->execute($success);

    $req->closeCursor();
  }

  // PHYSIQUE : Insertion valeur succès utilisateur
  // RETOUR : Aucun
  function physiqueInsertionSuccessUser($successUser)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO success_users(reference,
                                                    identifiant,
                                                    value)
                                            VALUES(:reference,
                                                   :identifiant,
                                                   :value)');

    $req->execute($successUser);

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Mise à jour succès
  // RETOUR : Aucun
  function physiqueUpdateSuccess($success)
  {
    // Initialisations
    $idSuccess = $success['id'];
    unset($success['id']);

    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE success
                          SET level         = :level,
                              order_success = :order_success,
                              defined       = :defined,
                              unicity       = :unicity,
                              title         = :title,
                              description   = :description,
                              limit_success = :limit_success,
                              explanation   = :explanation
                          WHERE id = ' . $idSuccess);

    $req->execute($success);

    $req->closeCursor();
  }

  // PHYSIQUE : Mise à jour valeur succès utilisateur
  // RETOUR : Aucun
  function physiqueUpdateSuccessUser($reference, $identifiant, $value)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE success_users
                          SET value = :value
                          WHERE reference = "' . $reference . '" AND identifiant = "' . $identifiant . '"');

    $req->execute(array(
      'value' => $value
    ));

    $req->closeCursor();
  }

  // PHYSIQUE : Rénumérotation des id
  // RETOUR : Aucun
  function physiqueRenumerotationSuccess()
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('SET @new_id = 0;
                       UPDATE success_users
                       SET id = (@new_id := @new_id + 1);
                       ALTER TABLE success_users
                       AUTO_INCREMENT = 1;');
  }

  /****************************************************************************/
  /********************************** DELETE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Suppression données utilisateurs d'un succès
  // RETOUR : Aucun
  function physiqueDeleteSuccessUsers($reference)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM success_users
                       WHERE reference = "' . $reference . '"');
  }

  // PHYSIQUE : Suppression succès
  // RETOUR : Aucun
  function physiqueDeleteSuccess($reference)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM success
                       WHERE reference = "' . $reference . '"');
  }

  // PHYSIQUE : Suppression des succès (sauf exceptions)
  // RETOUR : Aucun
  function physiqueDeleteSuccessAdmin()
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM success_users
                       WHERE reference != "beginning"
                         AND reference != "developper"
                         AND reference != "greedy"
                         AND reference != "restaurant-finder"');
  }

  // PHYSIQUE : Suppression succès valeur nulle
  // RETOUR : Aucun
  function physiqueDeleteSuccessNoValue()
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM success_users
                       WHERE value = 0');

  }
?>
