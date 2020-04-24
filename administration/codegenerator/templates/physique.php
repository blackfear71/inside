<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture liste
  // RETOUR : Liste
  function physiqueSelect($id)
  {
    // Initialisations
    $retour = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM table
                        WHERE id = ' . $id . '
                        ORDER BY id DESC');

    while ($data = $req->fetch())
    {
      $myDatas = Class::withData($data);

      array_push($retour, $myDatas);
    }

    $req->closeCursor();

    // Retour
    return $retour;
  }

  /****************************************************************************/
  /********************************** INSERT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Insertion
  // RETOUR : Aucun
  function physiqueInsert($champ1, $champ2)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO table(champ1, champ2)
                          VALUES(:champ1, :champ2)');

    $req->execute(array(
      'champ1' => $champ1,
      'champ2' => $champ2
    ));

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Mise à jour
  // RETOUR : Aucun
  function physiqueUpdate($champ1, $champ2, $id)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE table
                          SET champ1 = :champ1,
                              champ2 = :champ2
                          WHERE id = ' . $id);

    $req->execute(array(
      'champ1' => $champ1,
      'champ2' => $champ2
    ));

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** DELETE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Suppression
  // RETOUR : Aucun
  function physiqueDelete($id)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM table
                       WHERE id = ' . $id);
  }
?>
