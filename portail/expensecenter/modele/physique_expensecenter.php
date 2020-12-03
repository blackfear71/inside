<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture des utilisateurs inscrits
  // RETOUR : Liste des utilisateurs
  function physiqueUsers()
  {
    // Initialisations
    $listeUsers = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, pseudo, avatar, expenses
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

  // PHYSIQUE : Lecture des données d'un utilisateur
  // RETOUR : Objet Profile
  function physiqueUser($identifiant)
  {
    // Initialisations
    $user = NULL;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, pseudo, avatar, expenses, COUNT(*) AS nombreUser
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

  // PHYSIQUE : Lecture nombre de lignes existantes pour une année
  // RETOUR : Booléen
  function physiqueAnneeExistante($annee)
  {
    // Initialisations
    $anneeExistante = false;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                        FROM expense_center
                        WHERE SUBSTR(date, 1, 4) = "' . $annee . '"');

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

    $req = $bdd->query('SELECT DISTINCT SUBSTR(date, 1, 4)
                        FROM expense_center
                        ORDER BY SUBSTR(date, 1, 4) DESC');

    while ($data = $req->fetch())
    {
      // On ajoute la ligne au tableau
      array_push($onglets, $data['SUBSTR(date, 1, 4)']);
    }

    $req->closeCursor();

    // Retour
    return $onglets;
  }

  // PHYSIQUE : Lecture des dépenses
  // RETOUR : Liste des dépenses
  function physiqueDepenses($annee)
  {
    // Initialisations
    $listeDepenses = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM expense_center
                        WHERE SUBSTR(date, 1, 4) = ' . $annee . '
                        ORDER BY date DESC, id DESC');

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

  // PHYSIQUE : Lecture d'une dépense
  // RETOUR : Objet Expenses
  function physiqueDepense($idDepense)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM expense_center
                        WHERE id = ' . $idDepense);

    $data = $req->fetch();

    // Instanciation d'un objet Expenses à partir des données remontées de la bdd
    $depense = Expenses::withData($data);

    $req->closeCursor();

    // Retour
    return $depense;
  }

  // PHYSIQUE : Lecture des parts d'une dépense
  // RETOUR : Liste des parts
  function physiquePartsDepense($idDepense)
  {
    // Initialisations
    $listePartsDepense = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM expense_center_users
                        WHERE id_expense = ' . $idDepense . '
                        ORDER BY identifiant ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Parts à partir des données remontées de la bdd
      $partDepense = Parts::withData($data);

      // On ajoute la ligne au tableau
      array_push($listePartsDepense, $partDepense);
    }

    $req->closeCursor();

    // Retour
    return $listePartsDepense;
  }

  // PHYSIQUE : Lecture des parts d'une dépense par utilisateur
  // RETOUR : Tableau des parts
  function physiquePartsDepenseUsers($idDepense)
  {
    // Initialisations
    $listePartsUsers = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM expense_center_users
                        WHERE id_expense = ' . $idDepense . '
                        ORDER BY identifiant ASC');

    while ($data = $req->fetch())
    {
      $listePartsUsers[$data['identifiant']] = $data['parts'];
    }

    $req->closeCursor();

    // Retour
    return $listePartsUsers;
  }

  /****************************************************************************/
  /********************************** INSERT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Insertion nouvelle dépense
  // RETOUR : Id dépense
  function physiqueInsertionDepense($depense)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO expense_center(date,
                                                     price,
                                                     buyer,
                                                     comment,
                                                     type)
                                             VALUES(:date,
                                                    :price,
                                                    :buyer,
                                                    :comment,
                                                    :type)');

    $req->execute($depense);

    $req->closeCursor();

    $newId = $bdd->lastInsertId();

    // Retour
    return $newId;
  }

  // PHYSIQUE : Insertion nouvelle part
  // RETOUR : Aucun
  function physiqueInsertionPart($part)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO expense_center_users(id_expense,
                                                           identifiant,
                                                           parts)
                                                   VALUES(:id_expense,
                                                          :identifiant,
                                                          :parts)');

    $req->execute($part);

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Mise à jour bilan
  // RETOUR : Aucun
  function physiqueUpdateBilan($identifiant, $bilan)
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

  // PHYSIQUE : Mise à jour dépense
  // RETOUR : Aucun
  function physiqueUpdateDepense($idDepense, $depense)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE expense_center
                          SET date    = :date,
                              price   = :price,
                              buyer   = :buyer,
                              comment = :comment
                          WHERE id = ' . $idDepense);

    $req->execute($depense);

    $req->closeCursor();
  }

  // PHYSIQUE : Mise à jour part
  // RETOUR : Aucun
  function physiqueUpdatePart($idDepense, $identifiant, $depense)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE expense_center_users
                          SET parts = :parts
                          WHERE id_expense = ' . $idDepense . ' AND identifiant = "' . $identifiant . '"');

    $req->execute(array(
      'parts' => $depense
    ));

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** DELETE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Suppression d'une dépense
  // RETOUR : Aucun
  function physiqueDeleteDepense($idDepense)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM expense_center
                       WHERE id = ' . $idDepense);
  }

  // PHYSIQUE : Suppression toutes parts d'une dépense
  // RETOUR : Aucun
  function physiqueDeleteParts($idDepense)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM expense_center_users
                       WHERE id_expense = ' . $idDepense);
  }
?>
