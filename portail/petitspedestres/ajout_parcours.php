<?php
  session_start();

  // En cas d'erreur on sauvegarde les données entrée en session
  $_SESSION['nom_parcours'] = $_POST['nom_parcours'];
  $_SESSION['distance'] = $_POST['distance'];

	include ('../../includes/appel_bdd.php');
  include ('../../includes/classes/parcours.php');

  $parcours = array (
      'nom' => $_POST['nom_parcours'],
      'distance' => $_POST['distance'],
      'lieu' => '',
      'image' => ''
      );

  $monParcours = Parcours::withData($parcours);

  if (is_numeric($monParcours->getDistance()))
  {
    $req = $bdd->prepare('INSERT INTO petits_pedestres_parcours(nom, distance, lieu, image) VALUES(:nom, :distance, :lieu, :image)');
    $req->execute($parcours);
  	$req->closeCursor();
  }
  else
  {
    $_SESSION['erreur_distance'] = true;
  }

  header('location: ../petitspedestres.php');
?>
