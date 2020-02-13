<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/movies.php');

  // METIER : Lecture des films à supprimer
  // RETOUR : Liste des films à supprimer
  function getFilmsToDelete()
  {
    $listToDelete = array();

    global $bdd;

    $reponse1 = $bdd->query('SELECT id, film, to_delete, identifiant_add, identifiant_del FROM movie_house WHERE to_delete = "Y" ORDER BY id ASC');
    while ($donnees1 = $reponse1->fetch())
    {
      $myDelete = Movie::withData($donnees1);

      // On récupère le pseudo du suppresseur
      $reponse2 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $myDelete->getIdentifiant_del() . '"');
      $donnees2 = $reponse2->fetch();
      $myDelete->setPseudo_del($donnees2['pseudo']);
      $reponse2->closeCursor();

      // On récupère le pseudo de l'ajouteur
      $reponse3 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $myDelete->getIdentifiant_add() . '"');
      $donnees3 = $reponse3->fetch();
      $myDelete->setPseudo_add($donnees3['pseudo']);
      $reponse3->closeCursor();

      // On récupère le nombre de participants
      $reponse4 = $bdd->query('SELECT COUNT(id) AS nb_users FROM movie_house_users WHERE id_film = ' . $myDelete->getId());
      $donnees4 = $reponse4->fetch();
      $myDelete->setNb_users($donnees4['nb_users']);
      $reponse4->closeCursor();

      // On ajoute la ligne au tableau
      array_push($listToDelete, $myDelete);
    }
    $reponse1->closeCursor();

    return $listToDelete;
  }

  // METIER : Contrôle alertes Movie House
  // RETOUR : Booléen
  function getAlerteFilms()
  {
    $alert = false;

    global $bdd;

    $req = $bdd->query('SELECT id, to_delete FROM movie_house WHERE to_delete = "Y"');
    while ($data = $req->fetch())
    {
      if ($data['to_delete'] == "Y")
      {
        $alert = true;
        break;
      }
    }
    $req->closeCursor();

    return $alert;
  }

  // METIER : Supprime un film de la base
  // RETOUR : Aucun
  function deleteFilm($post)
  {
    $id_film = $post['id_film'];

    global $bdd;

    // Suppression des avis movie_house_users
    $req1 = $bdd->exec('DELETE FROM movie_house_users WHERE id_film = ' . $id_film);

    // Suppression des commentaires
    $req2 = $bdd->exec('DELETE FROM movie_house_comments WHERE id_film = ' . $id_film );

    // Suppression du film
    $req3 = $bdd->exec('DELETE FROM movie_house WHERE id = ' . $id_film );

    // Suppression des notifications
    deleteNotification('film', $id_film);
    deleteNotification('doodle', $id_film);
    deleteNotification('cinema', $id_film);
    deleteNotification('comments', $id_film);

    $_SESSION['alerts']['film_deleted'] = true;
  }

  // METIER : Réinitialise un film de la base
  // RETOUR : Aucun
  function resetFilm($post)
  {
    $id_film = $post['id_film'];

    global $bdd;

    // Mise à jour de la table (remise à N de l'indicateur de demande et effacement identifiant suppression)
    $to_delete       = "N";
    $identifiant_del = "";

    $req = $bdd->prepare('UPDATE movie_house SET to_delete = :to_delete, identifiant_del = :identifiant_del WHERE id = ' . $id_film);
    $req->execute(array(
      'to_delete'       => $to_delete,
      'identifiant_del' => $identifiant_del
    ));
    $req->closeCursor();

    $_SESSION['alerts']['film_reseted'] = true;
  }
?>
