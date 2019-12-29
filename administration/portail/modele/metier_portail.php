<?php
  include_once('../../includes/functions/appel_bdd.php');

  // METIER : Nombre de bugs en attente
  // RETOUR : Nombre de bugs
  function getNbBugs()
  {
    $nb_bugs = 0;

    global $bdd;

    $req = $bdd->query('SELECT COUNT(id) AS nb_bugs FROM bugs WHERE type = "B" AND resolved = "N"');
    $data = $req->fetch();
    $nb_bugs = $data['nb_bugs'];
    $req->closeCursor();

    return $nb_bugs;
  }

  // METIER : Nombre d'évolutions en attente
  // RETOUR : Nombre d'évolutions
  function getNbEvols()
  {
    $nb_evols = 0;

    global $bdd;

    $req = $bdd->query('SELECT COUNT(id) AS nb_bugs FROM bugs WHERE type = "E" AND resolved = "N"');
    $data = $req->fetch();
    $nb_evols = $data['nb_bugs'];
    $req->closeCursor();

    return $nb_evols;
  }
?>
