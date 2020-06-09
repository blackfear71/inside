<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture des utilisateurs
  // RETOUR : Tableau des utilisateurs
  function physiqueUsers()
  {
    // Initialisations
    $listeUsers = array();

    global $bdd;

    // Requête
    $req = $bdd->query('SELECT id, identifiant, ping, status, pseudo, avatar, email, anniversary, experience
                        FROM users
                        WHERE identifiant != "admin"
                        ORDER BY identifiant ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Profile à partir des données remontées de la bdd
      $myUser = Profile::withData($data);

      // On ajoute la ligne au tableau
      array_push($listeUsers, $myUser);
    }

    $req->closeCursor();

    // Retour
    return $listeUsers;
  }

  // PHYSIQUE : Lecture des succès administrateur
  // RETOUR : Valeur succès
  function physiqueSuccessAdmin($success, $identifiant)
  {
    // Initialisation
    $value = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *, COUNT(*) AS nombreLignes
                        FROM success_users
                        WHERE reference = "' . $success . '" AND identifiant = "' . $identifiant . '"');

    $data = $req->fetch();

    if ($data['nombreLignes'] > 0)
      $value = $data['value'];

    $req->closeCursor();

    // Retour
    return $value;
  }
?>
