<?php
  include_once('../../includes/functions/appel_bdd.php');

  // METIER : Contrôle alertes utilisateurs
  // RETOUR : Booléen
  function getAlerteUsers()
  {
    $alert = false;

    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, pseudo, status FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
    while($data = $req->fetch())
    {
      if ($data['status'] == "Y" OR $data['status'] == "I" OR $data['status'] == "D")
      {
        $alert = true;
        break;
      }
    }
    $req->closeCursor();

    return $alert;
  }
?>
