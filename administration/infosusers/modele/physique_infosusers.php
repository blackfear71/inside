<?php
  include_once('../../includes/functions/appel_bdd.php');

  // PHYSIQUE : Appel base "users"
  // RETOUR : Tableau des utilisateurs
  function physiqueUsers()
  {
    // Initialisations
    $listUsers = array();

    global $bdd;

    // Requête
    $req = $bdd->query('SELECT id, identifiant, ping, status, pseudo, avatar, email, anniversary, experience
                        FROM users
                        WHERE identifiant != "admin"
                        ORDER BY identifiant ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet User à partir des données remontées de la bdd
      $myUser = Profile::withData($data);

      // On ajoute la ligne au tableau
      array_push($listUsers, $myUser);
    }

    $req->closeCursor();

    // Retour
    return $listUsers;
  }

  // PHYSIQUE : Appel base "success_users"
  // RETOUR : Valeur succès
  function physiqueSuccessAdmin($success, $identifiant)
  {
    // Initialisation
    $value = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *, COUNT(*) AS nombre_ligne
                        FROM success_users
                        WHERE reference = "' . $success . '" AND identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    if ($data['nombre_ligne'] > 0)
      $value = $data['value'];

    $req->closeCursor();

    // Retour
    return $value;
  }
?>
