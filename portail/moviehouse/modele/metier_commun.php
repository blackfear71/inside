<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/functions/modeles_mails.php');
  include_once('../../includes/classes/movies.php');
  include_once('../../includes/classes/profile.php');

  // METIER : Lecture des données préférences
  // RETOUR : Objet Preferences
  function getPreferences($user)
  {
    global $bdd;

    // Lecture des préférences
    $reponse = $bdd->query('SELECT * FROM preferences WHERE identifiant = "' . $user . '"');
    $donnees = $reponse->fetch();

    // Instanciation d'un objet Profil à partir des données remontées de la bdd
    $preferences = Preferences::withData($donnees);

    $reponse->closeCursor();

    return $preferences;
  }

  // METIER : Lecture des films par année
  // RETOUR : Liste des films
  function getFilms($year, $user)
  {
    $listFilms = array();

    global $bdd;

    if ($year == "none")
      $reponse = $bdd->query('SELECT * FROM movie_house WHERE date_theater = "" AND to_delete != "Y" ORDER BY date_add DESC, film ASC');
    else
      $reponse = $bdd->query('SELECT * FROM movie_house WHERE SUBSTR(date_theater, 1, 4) = "' . $year . '" AND to_delete != "Y" ORDER BY date_theater ASC, film ASC');
    while ($donnees = $reponse->fetch())
    {
      $myFilm = Movie::withData($donnees);

      // Dans le cas de la recherche de films pour les boutons précédent/suivant, on n'a pas besoin de toutes les données
      if (isset($user))
      {
        // On récupère le nombre de commentaires
        $reponse2 = $bdd->query('SELECT COUNT(id) AS nb_comments FROM movie_house_comments WHERE id_film = "' . $myFilm->getId() . '"');
        $donnees2 = $reponse2->fetch();
        $myFilm->setNb_comments($donnees2['nb_comments']);
        $reponse2->closeCursor();

        // On récupère les étoiles et la participation de l'utilisateur connecté
        $reponse3 = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = ' . $myFilm->getId() . ' AND identifiant = "' . $user . '"');
        $donnees3 = $reponse3->fetch();

        if (isset($donnees3['stars']))
          $myFilm->setStars_user($donnees3['stars']);

        if (isset($donnees3['participation']))
          $myFilm->setParticipation($donnees3['participation']);

        $reponse3->closeCursor();

        // On récupère le nombre de participants
        $reponse4 = $bdd->query('SELECT COUNT(id) AS nb_users FROM movie_house_users WHERE id_film = ' . $myFilm->getId());
        $donnees4 = $reponse4->fetch();
        $myFilm->setNb_users($donnees4['nb_users']);
        $reponse4->closeCursor();
      }

      // On ajoute la ligne au tableau
      array_push($listFilms, $myFilm);
    }
    $reponse->closeCursor();

    return $listFilms;
  }

  // METIER : Insertion/modification étoiles
  // RETOUR : Id film
  function insertStars($post, $user)
  {
    // On récupère le choix utilisateur
    if (isset($post['preference_0']))
      $preference = 0;
    elseif (isset($post['preference_1']))
      $preference = 1;
    elseif (isset($post['preference_2']))
      $preference = 2;
    elseif (isset($post['preference_3']))
      $preference = 3;
    elseif (isset($post['preference_4']))
      $preference = 4;
    elseif (isset($post['preference_5']))
      $preference = 5;
    else
      $preference = 0;

    global $bdd;

    // On récupère le numéro du film
    $id_film = $post['id_film'];

    // On récupère l'identifiant de l'utilisateur
    $identifiant = $user;

    if ($preference == 0)
		{
			// Suppression de la table
			$req = $bdd->exec('DELETE FROM movie_house_users WHERE id_film = ' . $id_film . ' AND identifiant = "' . $identifiant . '"');
		}
		else
		{
			// On verifie qu'il n'existe pas déjà un choix pour ce film
			$existe = false;

			$req1 = $bdd->query('SELECT COUNT(id) AS existe_deja FROM movie_house_users WHERE id_film = ' . $id_film . '
																						                                      AND   identifiant = "' . $identifiant . '"
																						                                      ORDER BY id ASC');
			$data1 = $req1->fetch();

			if (is_numeric($data1['existe_deja']) AND $data1['existe_deja'] > 0)
				$existe = true;

			$req1->closeCursor();

			// Si trouvé alors on fait une MAJ
			if ($existe == true)
			{
				$req2 = $bdd->prepare('UPDATE movie_house_users SET stars = :stars WHERE id_film = ' . $id_film . ' AND identifiant = "' . $identifiant . '"');
				$req2->execute(array(
					'stars' => $preference
				));
				$req2->closeCursor();
			}
			// Sinon on insère une nouvelle ligne
			else
			{
        $vote = array('id_film'       => $id_film,
        					    'identifiant'   => $identifiant,
        					    'stars'         => $preference,
        					    'participation' => "N");

				$req3 = $bdd->prepare('INSERT INTO movie_house_users(id_film, identifiant, stars, participation) VALUES(:id_film, :identifiant, :stars, :participation)');
				$req3->execute($vote);
				$req3->closeCursor();
			}
		}

    return $id_film;
  }

  // METIER : Insertion/modification participation
  // RETOUR : Id film
  function insertParticipation($post, $user)
  {
    global $bdd;

    $id_film = $post['id_film'];

    if (isset($post['participate']))
    {
      // Lecture de l'état de la participation
      $req = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = ' . $id_film . ' AND identifiant = "' . $user . '"');
      $data = $req->fetch();

      $participation = $data['participation'];

      $req->closeCursor();

      // Génération succès
      if ($participation == "S")
        insertOrUpdateSuccesValue('viewer', $user, -1);

      // Inversion de la participation
      if ($participation == "P")
        $participation = "N";
      else
        $participation = "P";

      // Mise à jour
      $req2 = $bdd->prepare('UPDATE movie_house_users SET participation = :participation WHERE id_film = ' . $id_film . ' AND identifiant = "' . $user . '"');
      $req2->execute(array(
        'participation' => $participation
      ));
      $req2->closeCursor();

      // Génération succès
      if ($id_film == 16)
        insertOrUpdateSuccesValue('padawan', $user, 0);
    }
    elseif (isset($post['seen']))
    {
      // Lecture de l'état de la vue
      $req = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = ' . $id_film . ' AND identifiant = "' . $user . '"');
      $data = $req->fetch();

      $participation = $data['participation'];

      $req->closeCursor();

      // Inversion de la vue
      if ($participation == "S")
        $participation = "N";
      else
        $participation = "S";

      // Mise à jour
      $req2 = $bdd->prepare('UPDATE movie_house_users SET participation = :participation WHERE id_film = ' . $id_film . ' AND identifiant = "' . $user . '"');
      $req2->execute(array(
        'participation' => $participation
      ));
      $req2->closeCursor();

      // Génération succès
      if ($participation == "S")
        insertOrUpdateSuccesValue('viewer', $user, 1);
      else
        insertOrUpdateSuccesValue('viewer', $user, -1);

      if ($id_film == 16)
      {
        if ($participation == "S")
          insertOrUpdateSuccesValue('padawan', $user, 1);
        else
          insertOrUpdateSuccesValue('padawan', $user, 0);
      }
    }

    return $id_film;
  }
?>
