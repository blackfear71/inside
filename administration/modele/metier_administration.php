<?php
  include_once('../includes/appel_bdd.php');
  include_once('../includes/classes/movies.php');
  include_once('../includes/classes/bugs.php');

  // METIER : Contrôle alertes utilisateurs
  // RETOUR : Booléen
  function getAlerteUsers()
  {
    $alert = false;

    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, full_name, reset FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
    while($data = $req->fetch())
    {
      if ($data['reset'] == "Y" OR $data['reset'] == "I" OR $data['reset'] == "D")
      {
        $alert = true;
        break;
      }
    }
    $req->closeCursor();

    return $alert;
  }

  // METIER : Contrôle alertes Movie House
  // RETOUR : Booléen
  function getAlerteFilms()
  {
    $alert = false;

    global $bdd;

    $req = $bdd->query('SELECT id, to_delete FROM movie_house WHERE to_delete = "Y"');
    while($data = $req->fetch())
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

  // METIER : Nombre de bugs en attente
  // RETOUR : Nombre de bugs
  function getNbBugs()
  {
    $nb_bugs = 0;

    global $bdd;

    $req = $bdd->query('SELECT COUNT(id) AS nb_bugs FROM bugs WHERE type="B" AND resolved="N"');
    $data = $req->fetch();

    $nb_bugs = $data['nb_bugs'];

    $req->closeCursor();

    return $nb_bugs;
  }

  // METIER : Nombre d'évolutions en attente
  // RETOUR : Nombre d'évolutions
  function getNbEvols()
  {
    $nb_evols = 0;

    global $bdd;

    $req = $bdd->query('SELECT COUNT(id) AS nb_bugs FROM bugs WHERE type="E" AND resolved="N"');
    $data = $req->fetch();

    $nb_evols = $data['nb_bugs'];

    $req->closeCursor();

    return $nb_evols;
  }

  // METIER : Lecture des films à supprimer
  // RETOUR : Liste des films à supprimer
  function getToDelete()
  {
    $listToDelete = array();

    global $bdd;

    $reponse = $bdd->query('SELECT id, film, to_delete FROM movie_house WHERE to_delete = "Y" ORDER BY id ASC');
    while($donnees = $reponse->fetch())
    {
      $myDelete = Movie::withData($donnees);

      // On ajoute la ligne au tableau
      array_push($listToDelete, $myDelete);
    }
    $reponse->closeCursor();

    return $listToDelete;
  }

  // METIER : Supprime un film de la base
  // RETOUR : Aucun
  function deleteFilm($id_film)
  {
    global $bdd;

    // Suppression des avis movie_house_users
    $req1 = $bdd->exec('DELETE FROM movie_house_users WHERE id_film = ' . $id_film);

    // Suppression des commentaires
    $req2 = $bdd->exec('DELETE FROM movie_house_comments WHERE id_film = ' . $id_film );

    // Suppression du film
    $req3 = $bdd->exec('DELETE FROM movie_house WHERE id = ' . $id_film );

    $_SESSION['film_deleted'] = true;
  }

  // METIER : Réinitialise un film de la base
  // RETOUR : Aucun
  function resetFilm($id_film)
  {
    global $bdd;

    // Mise à jour de la table (remise à N de l'indicateur de demande)
    $to_delete = "N";

    $req = $bdd->prepare('UPDATE movie_house SET to_delete = :to_delete WHERE id = ' . $id_film);
    $req->execute(array(
      'to_delete' => $to_delete
    ));
    $req->closeCursor();

    $_SESSION['film_reseted'] = true;
  }

  // METIER : Lecture liste des bugs
  // RETOUR : Tableau des bugs
  function getBugs($view)
  {
    // Initialisation tableau des bugs
    $listeBugs = array();

    global $bdd;

    // Lecture de la base en fonction de la vue
    if ($view == "resolved")
      $reponse = $bdd->query('SELECT * FROM bugs WHERE resolved="Y" ORDER BY id DESC');
    elseif ($view == "unresolved")
      $reponse = $bdd->query('SELECT * FROM bugs WHERE resolved="N" ORDER BY id DESC');
    else
      $reponse = $bdd->query('SELECT * FROM bugs ORDER BY id DESC');

    while ($donnees = $reponse->fetch())
    {
      // Initilialisation variables
      $auteur_bug = "";

      // Instanciation d'un objet Idea à partir des données remontées de la bdd
      $bug = Bugs::withData($donnees);

      // Recherche du nom complet de l'auteur
      $reponse2 = $bdd->query('SELECT identifiant, full_name FROM users WHERE identifiant="' . $bug->getAuthor() . '"');
      $donnees2 = $reponse2->fetch();

      if (isset($donnees2['full_name']) AND !empty($donnees2['full_name']))
        $auteur_bug = $donnees2['full_name'];
      else
        $auteur_bug = "un ancien utilisateur";

      $reponse2->closeCursor();

      // On construit un tableau qu'on alimente avec les données d'un bug
      $myBug = array('id'       => $bug->getId(),
                     'subject'  => $bug->getSubject(),
                     'date'     => $bug->getDate(),
                     'author'   => $bug->getAuthor(),
                     'name_a'   => $auteur_bug,
                     'content'  => $bug->getContent(),
                     'type'     => $bug->getType(),
                     'resolved' => $bug->getResolved()
                     );

      array_push($listeBugs, Bugs::withData($myBug));
    }

    $reponse->closeCursor();

    return $listeBugs;
  }

  // METIER : Mise à jour du statut d'un bug
  // RETOUR : Aucun
  function updateBug($id, $post)
  {
    global $bdd;

    switch (key($post))
    {
      case 'resolve_bug':
        $resolved = "Y";
        break;

      case 'unresolve_bug':
        $resolved = "N";
        break;

      default:
        break;
    }

    $req = $bdd->prepare('UPDATE bugs SET resolved = :resolved WHERE id = ' . $id);
    $req->execute(array(
      'resolved' => $resolved
    ));
    $req->closeCursor();
  }
?>
