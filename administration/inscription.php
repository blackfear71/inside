<?php
  include('../includes/appel_bdd.php');

  // Inscription utilisateur
  if (isset($_POST['accept_inscription']))
  {
    // On met simplement à jour le status de l'utilisateur
    $reset = "N";

    $req = $bdd->prepare('UPDATE users SET reset=:reset WHERE id = ' . $_GET['id_user']);
    $req->execute(array(
      'reset' => $reset
    ));
    $req->closeCursor();  
  }
  // Suppression demande inscription
  elseif (isset($_POST['decline_inscription']))
  {
    $req = $bdd->exec('DELETE FROM users WHERE id=' . $_GET['id_user']);
  }
  // Désinscription utilisateur
  elseif (isset($_POST['accept_desinscription']))
  {
    // Récupération identifiant
    $req1 = $bdd->query('SELECT id, identifiant FROM users WHERE id = ' . $_GET['id_user']);
    $data1 = $req1->fetch();
    $identifiant = $data1['identifiant'];
    $req1->closeCursor();

    // Suppression des avis movie_house_users
    $req2 = $bdd->exec('DELETE FROM movie_house_users WHERE identifiant = "' . $identifiant . '"');

    // Suppression des préférences
    $req3 = $bdd->exec('DELETE FROM preferences WHERE identifiant = "' . $identifiant . '"');

    // Remise en cours des idées non terminées ou rejetées
    $status = "O";
    $developper = "";

    $req4 = $bdd->prepare('UPDATE ideas SET status=:status, developper=:developper WHERE developper = "' . $identifiant . '" AND status != "D" AND status != "R"');
    $req4->execute(array(
      'status' => $status,
      'developper' => $developper
    ));
    $req4->closeCursor();

    // Supression utilisateur
    $req5 = $bdd->exec('DELETE FROM users WHERE id = ' . $_GET['id_user'] . ' AND identifiant = "' . $identifiant . '"');
  }
  // Suppression demande désinscription
  elseif (isset($_POST['decline_desinscription']))
  {
    $reset = "N";

    $req = $bdd->prepare('UPDATE users SET reset=:reset WHERE id=' . $_GET['id_user']);
    $req->execute(array(
      'reset' => $reset
    ));
    $req->closeCursor();
  }

  // Redirection
  header('location: manage_users.php');
?>
