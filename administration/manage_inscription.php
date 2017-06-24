<?php
  include('../includes/appel_bdd.php');

  // Inscription utilisateur
  if (isset($_POST['accept_inscription']))
  {
    $reset = "N";

    $req = $bdd->prepare('UPDATE users SET reset=:reset WHERE id=' . $_GET['id_user']);
    $req->execute(array(
      'reset' => $reset
    ));
    $req->closeCursor();
  }
  // Suppression demande
  elseif (isset($_POST['decline_inscription']))
  {
    $req = $bdd->exec('DELETE FROM users WHERE id=' . $_GET['id_user']);
  }

  // Redirection
  header('location: manage_users.php');
?>
