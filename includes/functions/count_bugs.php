<?php
  include_once('appel_bdd.php');

  if (empty(session_id()))
    session_start();

  global $bdd;

  // Récupération compteur de bugs/évolutions
  $nb_bugs = 0;

  $reponse = $bdd->query('SELECT COUNT(id) AS nb_bugs FROM bugs WHERE resolved = "N"');
  $donnees = $reponse->fetch();
  $nb_bugs = $donnees['nb_bugs'];
  $reponse->closeCursor();

  // Récupération de la sortie
  $data = array('identifiant' => $_SESSION['user']['identifiant'],
                'nbBugs'      => $nb_bugs
               );

  $dataJson = json_encode($data);

  echo $dataJson;
?>
