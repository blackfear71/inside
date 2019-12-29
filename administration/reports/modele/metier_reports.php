<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/bugs.php');

  // METIER : Lecture liste des bugs / évolutions
  // RETOUR : Tableau des bugs / évolutions
  function getBugs($view, $type)
  {
    // Initialisation tableau des bugs
    $listeBugs = array();

    global $bdd;

    // Lecture de la base en fonction de la vue
    if ($view == "resolved")
      $reponse = $bdd->query('SELECT * FROM bugs WHERE type = "' . $type . '" AND (resolved = "Y" OR resolved = "R") ORDER BY date DESC, id DESC');
    elseif ($view == "unresolved")
      $reponse = $bdd->query('SELECT * FROM bugs WHERE type = "' . $type . '" AND resolved = "N" ORDER BY date DESC, id DESC');
    else
      $reponse = $bdd->query('SELECT * FROM bugs WHERE type = "' . $type . '" ORDER BY date DESC, id DESC');

    while ($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet Idea à partir des données remontées de la bdd
      $bug = Bugs::withData($donnees);

      // Recherche du pseudo et de l'avatar de l'auteur
      $reponse2 = $bdd->query('SELECT identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $bug->getAuthor() . '"');
      $donnees2 = $reponse2->fetch();

      if ($reponse2->rowCount() > 0)
      {
        $bug->setPseudo($donnees2['pseudo']);
        $bug->setAvatar($donnees2['avatar']);
      }

      $reponse2->closeCursor();

      array_push($listeBugs, $bug);
    }

    $reponse->closeCursor();

    return $listeBugs;
  }

  // METIER : Mise à jour du statut d'un bug
  // RETOUR : Top redirection
  function updateBug($post)
  {
    $id_report = $post['id_report'];
    $action    = $post;
    $resolved  = "N";

    unset($action['id_report']);

    global $bdd;

    // Lecture des données
    $req1 = $bdd->query('SELECT * FROM bugs WHERE id = ' . $id_report);
    $data1 = $req1->fetch();

    $author = $data1['author'];
    $status = $data1['resolved'];

    $req1->closeCursor();

    // Mise à jour du statut
    switch (key($action))
    {
      case 'resolve_bug':
        $resolved = "Y";
        break;

      case 'unresolve_bug':
        $resolved = "N";
        break;

      case 'reject_bug':
        $resolved = "R";
        break;

      default:
        break;
    }

    $req2 = $bdd->prepare('UPDATE bugs SET resolved = :resolved WHERE id = ' . $id_report);
    $req2->execute(array(
      'resolved' => $resolved
    ));
    $req2->closeCursor();

    // Génération succès (sauf si rejeté ou remis en cours après rejet)
    if ($resolved != "R" AND $status != "R")
    {
      if ($resolved == "Y")
        insertOrUpdateSuccesValue('compiler', $author, 1);
      else
        insertOrUpdateSuccesValue('compiler', $author, -1);
    }

    return $resolved;
  }

  // METIER : Suppression d'un bug
  // RETOUR : Aucun
  function deleteBug($post)
  {
    $id_report = $post['id_report'];

    global $bdd;

    // Lecture des données et suppression image si présente
    $req1 = $bdd->query('SELECT * FROM bugs WHERE id = ' . $id_report);
    $data1 = $req1->fetch();

    $author   = $data1['author'];
    $resolved = $data1['resolved'];

    if (isset($data1['picture']) AND !empty($data1['picture']))
      unlink ("../../includes/images/reports/" . $data1['picture']);

    $req1->closeCursor();

    // Suppression de la table
    $req2 = $bdd->exec('DELETE FROM bugs WHERE id = ' . $id_report);

    // Génération succès
    insertOrUpdateSuccesValue('debugger', $author, -1);

    if ($resolved == "Y")
      insertOrUpdateSuccesValue('compiler', $author, -1);

    $_SESSION['alerts']['bug_deleted'] = true;
  }
?>
