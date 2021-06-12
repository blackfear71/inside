<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture de la liste des équipes activées
  // RETOUR : Liste des équipes
  function physiqueListeEquipes()
  {
    // Initialisations
    $listeEquipes = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM teams
                        WHERE activation = "Y"
                        ORDER BY team ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Team à partir des données remontées de la bdd
      $equipe = Team::withData($data);

      // On ajoute la ligne au tableau
      $listeEquipes[$equipe->getReference()] = $equipe;
    }

    $req->closeCursor();

    // Retour
    return $listeEquipes;
  }

  // PHYSIQUE : Lecture des utilisateurs
  // RETOUR : Tableau des utilisateurs
  function physiqueUsers()
  {
    // Initialisations
    $listeUsers = array();

    global $bdd;

    // Requête
    $req = $bdd->query('SELECT id, identifiant, team, ping, status, pseudo, avatar, email, anniversary, experience
                        FROM users
                        WHERE identifiant != "admin" AND status != "I"
                        ORDER BY identifiant ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Profile à partir des données remontées de la bdd
      $user = Profile::withData($data);

      // On ajoute la ligne au tableau
      array_push($listeUsers, $user);
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
