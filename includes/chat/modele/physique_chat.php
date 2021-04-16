<?php
  include_once($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture des utilisateurs pour le chat
  // RETOUR : Liste des utilisateurs
  function physiqueUsersChat()
  {
    // Initialisations
    $listeUsers = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, pseudo, avatar
                        FROM users
                        WHERE identifiant != "admin"
                        ORDER BY identifiant ASC');

    while ($data = $req->fetch())
    {
      $user = array('identifiant' => $data['identifiant'],
                    'pseudo'      => $data['pseudo'],
                    'avatar'      => $data['avatar']
                   );

      // On ajoute la ligne au tableau
      array_push($listeUsers, $user);
    }

    $req->closeCursor();

    // Retour
    return $listeUsers;
  }
?>
