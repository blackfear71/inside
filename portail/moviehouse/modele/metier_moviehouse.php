<?php
  include_once('../../includes/appel_bdd.php');
  include_once('../../includes/classes/movies.php');
  include_once('../../includes/classes/profile.php');
  include_once('../../includes/modeles_mails.php');

  // METIER : Contrôle année existante (pour les onglets)
  // RETOUR : Booléen
  function controlYear($year)
  {
    $annee_existante = false;

    if (isset($year) AND is_numeric($year))
    {
      global $bdd;

      $reponse = $bdd->query('SELECT DISTINCT SUBSTR(date_theater, 1, 4) FROM movie_house WHERE to_delete != "Y" ORDER BY SUBSTR(date_theater, 1, 4) ASC');
      while($donnees = $reponse->fetch())
      {
        if ($year == $donnees['SUBSTR(date_theater, 1, 4)'])
          $annee_existante = true;
      }
      $reponse->closeCursor();
    }

    return $annee_existante;
  }

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

  // METIER : Lecture liste des films récents
  // RETOUR : Tableau des films récents
  function getRecents()
  {
    $listRecents = array();

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" ORDER BY SUBSTR(date_add, 1, 4) DESC, id DESC LIMIT 5');
    while($donnees = $reponse->fetch())
    {
      $myRecent = Movie::withData($donnees);

      // On ajoute la ligne au tableau
      array_push($listRecents, $myRecent);
    }
    $reponse->closeCursor();

    return $listRecents;
  }

  // METIER : Lecture liste des films les plus attendus
  // RETOUR : Tableau des films attendus
  function getAttendus($year)
  {
    $listAttendus = array();

    global $bdd;

    // Calcul de la moyenne des étoiles de tous les films (tableau id film/moyenne/total utilisateurs) dont la date est supérieure ou égale à date du jour - 1 mois
    $i                         = 0;
    $total_stars               = 0;
    $total_users               = 0;
    $moyenne_stars             = array();
    $id_film_nouveau           = "";
    $id_film_ancien            = "";
    $date_du_jour_moins_1_mois = date("Ymd", strtotime('now -1 Month'));

    $reponse = $bdd->query('SELECT id, id_film, stars FROM movie_house_users ORDER BY id_film ASC');

    while($donnees = $reponse->fetch())
    {
      // On ne tient pas compte de tous les films à sortir à date du jour - 1 mois
      $reponse2 = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" AND SUBSTR(date_theater, 1, 4) = ' . $year . ' AND id = ' . $donnees['id_film']);

      $donnees2 = $reponse2->fetch();
      {
        $date_film = $donnees2['date_theater'];

        if ($date_film > $date_du_jour_moins_1_mois)
        {
          $id_film_nouveau = $donnees['id_film'];

          if (empty($id_film_ancien) OR $id_film_nouveau == $id_film_ancien)
          {
            $total_stars += $donnees['stars'];
            $total_users++;
          }
          elseif (!empty($id_film_ancien) AND $id_film_nouveau != $id_film_ancien)
          {
            $moyenne_stars[$i][1] = $id_film_ancien;
            $moyenne_stars[$i][2] = $total_stars / $total_users;
            $moyenne_stars[$i][3] = $total_users;

            $i++;
            $total_stars = $donnees['stars'];
            $total_users = 1;
          }

          $id_film_ancien = $id_film_nouveau;
        }
      }

      $reponse2->closeCursor();
    }

    $reponse->closeCursor();

    // On récupère seulement si on a trouvé des films attendus pour cette année
    if (!empty($moyenne_stars))
    {
      // On trie le film par nombre d'utilisateur en premier et par moyenne en 2ème
      $moyenne_stars_tri = $moyenne_stars;
      $tri_1 = NULL;
      $tri_2 = NULL;

      foreach($moyenne_stars as $ligne)
      {
        $tri_1[] = $ligne[3];
        $tri_2[] = $ligne[2];
      }

      array_multisort($tri_1, SORT_DESC, $tri_2, SORT_DESC, $moyenne_stars_tri);

      // On extrait les 5 premières moyennes des films les plus attentus
      $moyenne_stars_tri_coupe = array_slice($moyenne_stars_tri, 0, 5);

      // On alimente le tableau des films attendus
      foreach ($moyenne_stars_tri_coupe as $ligne)
      {
        $nb_users = $ligne[3];
        $average  = str_replace('.', ',', round($ligne[2], 1));

        // On récupère les données du film correspondant
        $reponse3 = $bdd->query('SELECT * FROM movie_house WHERE id = ' . $ligne[1]);
        $donnees3 = $reponse3->fetch();

        $myAttendu = Movie::withData($donnees3);
        $myAttendu->setNb_users($nb_users);
        $myAttendu->setAverage($average);

        // On ajoute la ligne au tableau
        array_push($listAttendus, $myAttendu);

        $reponse->closeCursor();
      }
    }

    // Tri final sur la moyenne
    foreach ($listAttendus as $attendu)
    {
      $tri_average[] = $attendu->getAverage();
    }
    array_multisort($tri_average, SORT_DESC, $listAttendus);

    return $listAttendus;
  }

  // METIER : Lecture des prochaines sorties
  // RETOUR : Tableau des films avec sortie prévue
  function getSorties($year)
  {
    $listSorties = array();

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" AND date_doodle != "" AND date_doodle >= ' . date("Ymd") . ' ORDER BY date_doodle ASC, id DESC LIMIT 5');
    while($donnees = $reponse->fetch())
    {
      $mySortie = Movie::withData($donnees);

      // On ajoute la ligne au tableau
      array_push($listSorties, $mySortie);
    }
    $reponse->closeCursor();

    return $listSorties;
  }

  // METIER : Lecture des années distinctes
  // RETOUR : Liste des années
  function getOnglets()
  {
    $listOnglets = array();

    global $bdd;

    $reponse = $bdd->query('SELECT DISTINCT SUBSTR(date_theater, 1, 4) FROM movie_house WHERE to_delete != "Y" ORDER BY SUBSTR(date_theater, 1, 4) ASC');
    while($donnees = $reponse->fetch())
    {
      // On ajoute la ligne au tableau
      array_push($listOnglets, $donnees['SUBSTR(date_theater, 1, 4)']);
    }
    $reponse->closeCursor();

    return $listOnglets;
  }

  // METIER : Lecture des films par année
  // RETOUR : Liste des films
  function getFilms($year, $user)
  {
    $listFilms = array();

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM movie_house WHERE SUBSTR(date_theater, 1, 4) = ' . $year . ' AND to_delete != "Y" ORDER BY date_theater ASC, film ASC');
    while($donnees = $reponse->fetch())
    {
      $myFilm = Movie::withData($donnees);

      // On récupère le nombre de commentaires
      $reponse2 = $bdd->query('SELECT COUNT(id) AS nb_comments FROM movie_house_comments WHERE id_film = "' . $myFilm->getId() . '"');
      $donnees2 = $reponse2->fetch();

      $myFilm->setNb_comments($donnees2['nb_comments']);

      $reponse2->closeCursor();

      if (isset($user))
      {
        // On récupère les étoiles et la participation de l'utilisateur connecté
        $reponse3 = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = ' . $myFilm->getId() . ' AND identifiant = "' . $user . '"');
        $donnees3 = $reponse3->fetch();

        if (isset($donnees3['stars']))
          $myFilm->setStars_user($donnees3['stars']);

        if (isset($donnees3['participation']))
          $myFilm->setParticipation($donnees3['participation']);

        $reponse3->closeCursor();
      }

      // On récupère le nombre de participants
      $reponse4 = $bdd->query('SELECT COUNT(id) AS nb_users FROM movie_house_users WHERE id_film = ' . $myFilm->getId());
      $donnees4 = $reponse4->fetch();

      $myFilm->setNb_users($donnees4['nb_users']);

      $reponse4->closeCursor();

      // On ajoute la ligne au tableau
      array_push($listFilms, $myFilm);
    }
    $reponse->closeCursor();

    return $listFilms;
  }

  // METIER : Lecture nombre d'utilisateurs inscrits
  // RETOUR : Nombre d'utilisateurs
  function countUsers()
  {
    global $bdd;

    $reponse = $bdd->query('SELECT COUNT(id) AS nb_users FROM users WHERE identifiant != "admin" AND reset != "I"');
    $donnees = $reponse->fetch();

    $nb_users = $donnees['nb_users'];

    $reponse->closeCursor();

    return $nb_users;
  }

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Tableau d'utilisateurs
  function getUsers()
  {
    // Initialisation tableau d'utilisateurs
    $listeUsers = array();

    global $bdd;

    $reponse = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant != "admin"  AND reset != "I" ORDER BY identifiant ASC');
    while($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet User à partir des données remontées de la bdd
      $user = Profile::withData($donnees);

      // On construit un tableau des utilisateurs
      $myUser = array('id'          => $user->getId(),
                      'identifiant' => $user->getIdentifiant(),
                      'pseudo'      => $user->getPseudo(),
                      'avatar'      => $user->getAvatar()
                    );

      // On ajoute la ligne au tableau
      array_push($listeUsers, Profile::withData($myUser));
    }
    $reponse->closeCursor();

    return $listeUsers;
  }

  // METIER : Lecture tableau des films
  // RETOUR : Tableau des films
  function getTabFilms($year, $list_users, $nb_users)
  {
    // Initialisation tableaux des films
    $listeFilms   = array();
    $listeStars   = array();
    $tableauFilms = array();

    global $bdd;

    // Récupération d'une liste des films
    $reponse = $bdd->query('SELECT * FROM movie_house WHERE SUBSTR(date_theater, 1, 4) = ' . $year . ' AND to_delete != "Y" ORDER BY date_theater ASC, film ASC');
    while($donnees = $reponse->fetch())
    {
      // Ajout d'un objet Movie (instancié à partir des données de la base) au tableau des films
      array_push($listeFilms, Movie::withData($donnees));
    }
    $reponse->closeCursor();

    // var_dump($listeFilms);

    // Récupération d'une liste des étoiles
    $reponse2 = $bdd->query('SELECT * FROM movie_house_users ORDER BY identifiant ASC');
    while($donnees2 = $reponse2->fetch())
    {
      // Ajout d'un objet Stars (instancié à partir des données de la base) au tableau de dépenses
      array_push($listeStars, Stars::withData($donnees2));
    }
    $reponse2->closeCursor();

    // var_dump($listeStars);

    // On consolide un nouveau tableau repésentant chaque ligne du tableau des films
    $i = 0;

    foreach ($listeFilms as $film)
    {
      $tableauStars    = array();
      $etoiles_trouvee = false;

      foreach ($list_users as $user)
      {
        foreach ($listeStars as $stars)
        {
          if ($stars->getId_film() == $film->getId())
          {
            if ($stars->getIdentifiant() == $user->getIdentifiant())
            {
              $myStars = array('identifiant'   => $user->getIdentifiant(),
                               'stars'         => $stars->getStars(),
                               'participation' => $stars->getParticipation(),
                              );

              $etoiles_trouvee = true;
            }
          }

          if ($etoiles_trouvee == true)
            break;
        }

        if ($etoiles_trouvee == false)
        {
          $myStars = array('identifiant'   => $user->getIdentifiant(),
                           'stars'         => 0,
                           'participation' => ''
                          );
        }
        else
          $etoiles_trouvee = false;

        // On ajoute la ligne au sous-tableau des étoiles
        array_push($tableauStars, $myStars);
      }

      // var_dump($tableauStars);

      // On compte le nombre d'utilisateurs et on remplit le tableau final seulement si on a atteint le nombre total d'utilisateurs inscrits
      if (count($tableauStars) == $nb_users)
      {
        // On génère une ligne dans le tableau final
        $mySynthese = array('id_film'      => $listeFilms[$i]->getId(),
                            'film'         => $listeFilms[$i]->getFilm(),
                            'date_theater' => $listeFilms[$i]->getDate_theater(),
                            'tableStars'   => $tableauStars
                           );

        // var_dump($mySynthese);

        array_push($tableauFilms, $mySynthese);
     }

     $i++;
   }

   // var_dump($tableauFilms);

   return $tableauFilms;
  }

  // METIER : Insertion film
  // RETOUR : Aucun
  function insertFilmRapide($post, $year, $user)
  {
    // Sauvegarde en session en cas d'erreur
    $_SESSION['nom_film_saisi']      = $post['nom_film'];
    $_SESSION['date_theater_saisie'] = $post['date_theater'];

    $date_a_verifier = $post['date_theater'];

    // Contrôle date à vide
    if (empty($date_a_verifier))
    {
      if (isLastDayOfYearWednesday($year))
      {
        $date_a_verifier = '30/12/' . $year;
        $date_theater    = $year . '1230';
      }
      else
      {
        $date_a_verifier = '31/12/' . $year;
        $date_theater    = $year . '1231';
      }
    }
    else
      $date_theater = formatDateForInsert($date_a_verifier);

    // On décompose la date à contrôler
		list($d, $m, $y) = explode('/', $date_a_verifier);

    // On vérifie le format de la date
		if (checkdate($m, $d, $y))
		{
      global $bdd;

      $film = array('film'            => $post['nom_film'],
                    'to_delete'       => "N",
                    'date_add'        => date("Ymd"),
                    'identifiant_add' => $user,
                    'date_theater'    => $date_theater,
                    'date_release'    => "",
                    'link'            => "",
                    'poster'          => "",
                    'trailer'         => "",
                    'id_url'          => "",
                    'doodle'          => "",
                    'date_doodle'     => "",
                    'time_doodle'     => "",
                    'restaurant'      => "",
                    'place'           => ""
                   );

			// Stockage de l'enregistrement en table
      $req = $bdd->prepare('INSERT INTO movie_house(film,
																										to_delete,
                                                    date_add,
																										identifiant_add,
																										date_theater,
																										date_release,
																										link,
																										poster,
																										trailer,
																										id_url,
																										doodle,
																										date_doodle,
																										time_doodle,
																										restaurant,
																										place)
																						VALUES(:film,
																									 :to_delete,
                                                   :date_add,
																									 :identifiant_add,
																									 :date_theater,
																									 :date_release,
																									 :link,
																									 :poster,
																									 :trailer,
																									 :id_url,
																									 :doodle,
																									 :date_doodle,
																									 :time_doodle,
																									 :restaurant,
																									 :place)');
      $req->execute($film);
		  $req->closeCursor();

      $_SESSION['film_added'] = true;
    }
    else
      $_SESSION['wrong_date'] = true;
  }

  // METIER : Insertion/modification étoiles
  // RETOUR : Aucun
  function insertStars($post, $get, $user)
  {
    // On récupère le choix utilisateur
    if (isset($post['preference'][0]))
      $preference = 0;
    elseif (isset($post['preference'][1]))
      $preference = 1;
    elseif (isset($post['preference'][2]))
      $preference = 2;
    elseif (isset($post['preference'][3]))
      $preference = 3;
    elseif (isset($post['preference'][4]))
      $preference = 4;
    elseif (isset($post['preference'][5]))
      $preference = 5;
    else
      $preference = 0;

    global $bdd;

    // On récupère le numéro du film
    $id_film = $get['id_film'];

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
  }

  // METIER : Insertion/modification participation
  // RETOUR : Aucun
  function insertParticipation($post, $get, $user)
  {
    global $bdd;

    $id_film = $get['id_film'];

    if(isset($post['participate']))
  	{
  		// Lecture de l'état de la participation
  		$req = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = ' . $id_film . ' AND identifiant = "' . $user . '"');
  		$data = $req->fetch();

  		$participation = $data['participation'];

  		$req->closeCursor();

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
  	}
  	elseif(isset($post['seen']))
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
  	}
  }

  // METIER : Contrôle film existant et non à supprimer
  // RETOUR : Booléen
  function controlFilm($id_film)
  {
    global $bdd;

    $filmExistant = false;

    // Contrôle film existant
    $reponse = $bdd->query('SELECT * FROM movie_house WHERE id = ' . $id_film);
    $donnees = $reponse->fetch();

    if ($reponse->rowCount() == 0)
      $_SESSION['doesnt_exist'] = true;

    $reponse->closeCursor();

    // Contrôle film non à supprimer
    include('../../includes/appel_bdd.php');

    $reponse2 = $bdd->query('SELECT id, to_delete FROM movie_house WHERE id = ' . $id_film);
    $donnees2 = $reponse2->fetch();

    if ($donnees2['to_delete'] == "Y")
      $_SESSION['doesnt_exist'] = true;

    $reponse2->closeCursor();

    if ($_SESSION['doesnt_exist'] == false)
      $filmExistant = true;

    return $filmExistant;
  }

  // METIER : Récupération film précédent et suivant pour navigation
  // RETOUR : Liste de films précédent et suivant
  function getNavigation($id_film)
  {
    $listNavigation   = array();
    $bouton_precedent = array();
    $bouton_suivant   = array();

    global $bdd;

    // On récupère l'année du film
    $reponse = $bdd->query('SELECT id, date_theater FROM movie_house WHERE id = ' . $id_film);
    $donnees = $reponse->fetch();

    $anneeCourante = substr($donnees['date_theater'], 0, 4);

    $reponse->closeCursor();

    // On récupère la liste des films pour trouver le film précédent et suivant
    $listFilms = getFilms($anneeCourante, NULL);

    // On cherche le film précédent et suivant dans la liste
    for ($i = 0; $i < count($listFilms); $i++)
    {
      if ($listFilms[$i]->getId() == $id_film)
      {
        // Bouton précédent
        if (isset($listFilms[$i - 1]) AND !empty($listFilms[$i - 1]->getId()) AND !empty($listFilms[$i - 1]->getFilm()))
        {
          // On raccourci le texte s'il est trop long
          $max_caracteres = 15;
          $titre          = $listFilms[$i - 1]->getFilm();

          // Test si la longueur du texte dépasse la limite
          if (strlen($titre) > $max_caracteres)
          {
            // Sélection du maximum de caractères
            $titre = substr($titre, 0, $max_caracteres);

            // Ajout des "..."
            $titre = $titre . '...';
          }

          // Stockage
          $bouton_precedent = array('id'   => $listFilms[$i - 1]->getId(),
                                    'film' => $titre
                                  );
        }

        // Bouton suivant
        if (isset($listFilms[$i + 1]) AND !empty($listFilms[$i + 1]->getId()) AND !empty($listFilms[$i + 1]->getFilm()))
        {
          // On raccourci le texte s'il est trop long
          $max_caracteres = 15;
          $titre          = $listFilms[$i + 1]->getFilm();

          // Test si la longueur du texte dépasse la limite
          if (strlen($titre) > $max_caracteres)
          {
            // Sélection du maximum de caractères
            $titre = substr($titre, 0, $max_caracteres);

            // Ajout des "..."
            $titre = $titre . '...';
          }

          // Stockage
          $bouton_suivant = array('id'   => $listFilms[$i + 1]->getId(),
                                  'film' => $titre
                                 );
        }

        $listNavigation = array('previous' => $bouton_precedent,
                                'next'     => $bouton_suivant
                               );
      }
    }

    return $listNavigation;
  }

  // METIER : Récupération détails film
  // RETOUR : Objet film
  function getDetails($id_film, $user)
  {
    global $bdd;

    // On récupère les données du film
    $reponse = $bdd->query('SELECT * FROM movie_house WHERE id = ' . $id_film);
    $donnees = $reponse->fetch();

    $film = Movie::withData($donnees);

    $reponse->closeCursor();

    // On récupère les étoiles et la participation de l'utilisateur connecté
    if (isset($user))
    {
      $reponse2 = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = ' . $id_film . ' AND identifiant = "' . $user . '"');
      $donnees2 = $reponse2->fetch();

      if (isset($donnees2['stars']))
        $film->setStars_user($donnees2['stars']);

      if (isset($donnees2['participation']))
        $film->setParticipation($donnees2['participation']);

      $reponse2->closeCursor();
    }

    // On récupère le nombre de participants
    $reponse3 = $bdd->query('SELECT COUNT(id) AS nb_users FROM movie_house_users WHERE id_film = ' . $id_film);
    $donnees3 = $reponse3->fetch();

    $film->setNb_users($donnees3['nb_users']);

    $reponse3->closeCursor();

    return $film;
  }

  // METIER : Récupération étoiles utilisateur sur détails film
  // RETOUR : Liste des étoiles utilisateurs
  function getDetailsStars($id_film)
  {
    $listStars = array();

    global $bdd;

    // Récupération d'une liste des étoiles
    $reponse = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = ' . $id_film . ' ORDER BY identifiant ASC');
    while($donnees = $reponse->fetch())
    {
      // On récupère le pseudo des utilisateurs
      $reponse2 = $bdd->query('SELECT id, identifiant, pseudo, avatar, email FROM users WHERE identifiant = "' . $donnees['identifiant'] . '"');
      $donnees2 = $reponse2->fetch();
      {
        $pseudo = $donnees2['pseudo'];
        $avatar = $donnees2['avatar'];
        $email  = $donnees2['email'];
      }
      $reponse2->closeCursor();

      $stars = Stars::withData($donnees);
      $stars->setPseudo($pseudo);
      $stars->setAvatar($avatar);
      $stars->setEmail($email);

      // Ajout d'un objet Stars (instancié à partir des données de la base) au tableau de dépenses
      array_push($listStars, $stars);
    }
    $reponse->closeCursor();

    return $listStars;
  }

  // METIER : Récupération des commentaires sur détails film
  // RETOUR : Liste des commentaires
  function getComments($id_film)
  {
    $listComments = array();

    global $bdd;

    // Récupération d'une liste des commentaires
    $reponse = $bdd->query('SELECT * FROM movie_house_comments WHERE id_film = ' . $id_film . ' ORDER BY id ASC');
    while($donnees = $reponse->fetch())
    {
      // On récupère le pseudo des utilisateurs
      $reponse2 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $donnees['author'] . '"');
      $donnees2 = $reponse2->fetch();

      $pseudo = $donnees2['pseudo'];
      $avatar = $donnees2['avatar'];

      $reponse2->closeCursor();

      $comment = Comments::withData($donnees);
      $comment->setPseudo($pseudo);
      $comment->setAvatar($avatar);

      // Ajout d'un objet Stars (instancié à partir des données de la base) au tableau de dépenses
      array_push($listComments, $comment);
    }
    $reponse->closeCursor();

    return $listComments;
  }

  // METIER : Insertion commentaire sur un détail film
  // RETOUR : Aucun
  function insertComment($post, $get, $user)
  {
    global $bdd;

    // On récupère les données
    $id_film = $get['id_film'];
    $author  = $user;
    $date    = date("Ymd");
    $time    = date("His");
    $comment = $post['comment'];

    // Stockage de l'enregistrement en table
    $req = $bdd->prepare('INSERT INTO movie_house_comments(id_film, author, date, time, comment) VALUES(:id_film, :author, :date, :time, :comment)');
    $req->execute(array(
      'id_film' => $id_film,
      'author' => $author,
      'date' => $date,
      'time' => $time,
      'comment' => $comment
        ));
    $req->closeCursor();
  }

  // METIER : Suppression commentaire sur un détail film
  // RETOUR : Aucun
  function deleteComment($id_comment)
  {
    global $bdd;

    $reponse = $bdd->exec('DELETE FROM movie_house_comments WHERE id = ' . $id_comment);
  }

  // METIER : Modification commentaire sur un détail film
  // RETOUR : Aucun
  function updateComment($id_comment, $post)
  {
    global $bdd;

    // Modification de l'enregistrement en table
    $req = $bdd->prepare('UPDATE movie_house_comments SET comment = :comment WHERE id = ' . $id_comment);
    $req->execute(array(
      'comment' => $post['comment']
    ));
    $req->closeCursor();
  }

  // METIER : Demande de suppression d'un film
  // RETOUR : Aucun
  function deleteFilm($id_film)
  {
    global $bdd;

    $to_delete = "Y";

    // Modification de l'enregistrement en table
    $req = $bdd->prepare('UPDATE movie_house SET to_delete = :to_delete WHERE id = ' . $id_film);
    $req->execute(array(
      'to_delete' => $to_delete
    ));
    $req->closeCursor();

    $_SESSION['film_removed'] = true;
  }

  // METIER : Initialisation champs saisie avancée films
  // RETOUR : Objet Movie
  function initCreFilm()
  {
    $initFilm = new Movie();

    return $initFilm;
  }

  // METIER : Initialisation champs modification avancée films
  // RETOUR : Objet Movie
  function initModFilm($id_film)
  {
    global $bdd;

    $reponse = $bdd->query('SELECT * FROM movie_house WHERE id = ' . $id_film);
    $donnees = $reponse->fetch();

    $initFilm = Movie::withData($donnees);

    $reponse->closeCursor();

    return $initFilm;
  }

  // METIER : Initialisation champs erreur création avancée films
  // RETOUR : Objet Movie
  function initCreErrFilm()
  {
    $initFilm = new Movie();

    $initFilm->setFilm($_SESSION['nom_film_saisi']);
    $initFilm->setDate_theater($_SESSION['date_theater_saisie']);
    $initFilm->setDate_release($_SESSION['date_release_saisie']);
    $initFilm->setTrailer($_SESSION['trailer_saisi']);
    $initFilm->setLink($_SESSION['link_saisi']);
    $initFilm->setPoster($_SESSION['poster_saisi']);
    $initFilm->setDoodle($_SESSION['doodle_saisi']);
    $initFilm->setDate_doodle($_SESSION['date_doodle_saisie']);
    $initFilm->setTime_doodle($_SESSION['time_doodle_saisi']);
    $initFilm->setRestaurant($_SESSION['restaurant_saisi']);
    $initFilm->setPlace($_SESSION['place_saisie']);

    return $initFilm;
  }

  // METIER : Initialisation champs erreur modification avancée films
  // RETOUR : Objet Movie
  function initModErrFilm($id_film)
  {
    $initFilm = new Movie();

    $initFilm->setId($id_film);
    $initFilm->setFilm($_SESSION['nom_film_saisi']);
    $initFilm->setDate_theater($_SESSION['date_theater_saisie']);
    $initFilm->setDate_release($_SESSION['date_release_saisie']);
    $initFilm->setTrailer($_SESSION['trailer_saisi']);
    $initFilm->setLink($_SESSION['link_saisi']);
    $initFilm->setPoster($_SESSION['poster_saisi']);
    $initFilm->setDoodle($_SESSION['doodle_saisi']);
    $initFilm->setDate_doodle($_SESSION['date_doodle_saisie']);
    $initFilm->setTime_doodle($_SESSION['time_doodle_saisi']);
    $initFilm->setRestaurant($_SESSION['restaurant_saisi']);
    $initFilm->setPlace($_SESSION['place_saisie']);

    return $initFilm;
  }

  // METIER : Insertion film saisie avancée
  // RETOUR : Id film créé
  function insertFilmAvance($post, $user)
  {
    $new_id = NULL;

    // Sauvegarde en session en cas d'erreur
    $_SESSION['nom_film_saisi']      = $post['nom_film'];
    $_SESSION['date_theater_saisie'] = $post['date_theater'];
    $_SESSION['date_release_saisie'] = $post['date_release'];
    $_SESSION['trailer_saisi']       = $post['trailer'];
    $_SESSION['link_saisi']          = $post['link'];
    $_SESSION['poster_saisi']        = $post['poster'];
    $_SESSION['doodle_saisi']        = $post['doodle'];
    $_SESSION['date_doodle_saisie']  = $post['date_doodle'];

    if (isset($post['hours_doodle']))
      $_SESSION['hours_doodle_saisies'] = $post['hours_doodle'];
    else
      $_SESSION['hours_doodle_saisies'] = "  ";

    if (isset($post['minutes_doodle']))
      $_SESSION['minutes_doodle_saisies'] = $post['minutes_doodle'];
    else
      $_SESSION['minutes_doodle_saisies'] = "  ";

    $_SESSION['time_doodle_saisi'] = $_SESSION['hours_doodle_saisies'] . $_SESSION['minutes_doodle_saisies'];
    $_SESSION['restaurant_saisi']  = $post['restaurant'];
    $_SESSION['place_saisie']      = $post['place'];

    // Récupération des variables
    $nom_film        = $post['nom_film'];
    $to_delete       = "N";
    $date_add        = date("Ymd");
    $identifiant_add = $user;
    $date_theater    = "";
    $date_release    = "";
    $link            = $post['link'];
    $poster          = $post['poster'];
    $trailer         = $post['trailer'];
    $doodle          = $post['doodle'];
    $date_doodle     = "";

    if (!empty($post['date_doodle']) AND isset($post['hours_doodle']) AND isset($post['minutes_doodle']))
      $time_doodle = $post['hours_doodle'] . $post['minutes_doodle'];
    else
      $time_doodle = "";

    $restaurant   = $post['restaurant'];
    $place        = $post['place'];

    // Récupération ID vidéo
    $id_url = extract_url($trailer);

    // Récupération date sortie cinéma
		$date_a_verifier_1 = $post['date_theater'];

    // Contrôle date à vide
		if (empty($date_a_verifier_1))
		{
			if (isLastDayOfYearWednesday(date('Y')))
			{
				$date_a_verifier_1 = '30/12/' . date('Y');
				$date_theater      = date('Y') . '1230';
			}
			else
			{
				$date_a_verifier_1 = '31/12/' . date('Y');
				$date_theater      = date('Y') . '1231';
			}
		}
		else
			$date_theater = formatDateForInsert($date_a_verifier_1);

    // On décompose la date à contrôler
		list($d, $m, $y) = explode('/', $date_a_verifier_1);

    // On vérifie le format de la date
    if (checkdate($m, $d, $y))
    {
      // Contrôle date sortie DVD
      if (isset($post['date_release']) AND !empty($post['date_release']))
      {
        $date_a_verifier_2 = $post['date_release'];

        list($d, $m, $y) = explode('/', $date_a_verifier_2);

        // On vérifie le format de la date 2 (date sortie dvd/bluray)
        if (checkdate($m, $d, $y))
          $date_release = formatDateForInsert($post['date_release']);
        else
          $_SESSION['wrong_date'] = true;
      }

      // Contrôle date Doodle
      if (isset($post['date_doodle']) AND !empty($post['date_doodle']))
			{
				$date_a_verifier_3 = $post['date_doodle'];

				list($d, $m, $y) = explode('/', $date_a_verifier_3);

				// On vérifie le format de la date 3 (date proposée)
				if (checkdate($m, $d, $y))
					$date_doodle = formatDateForInsert($post['date_doodle']);
				else
					$_SESSION['wrong_date'] = true;
			}

      if ($_SESSION['wrong_date'] != true)
			{
        $film = array('film'            => $nom_film,
                      'to_delete'       => $to_delete,
                      'date_add'        => $date_add,
                      'identifiant_add' => $identifiant_add,
                      'date_theater'    => $date_theater,
                      'date_release'    => $date_release,
                      'link'            => $link,
                      'poster'          => $poster,
                      'trailer'         => $trailer,
                      'id_url'          => $id_url,
                      'doodle'          => $doodle,
                      'date_doodle'     => $date_doodle,
                      'time_doodle'     => $time_doodle,
                      'restaurant'      => $restaurant,
                      'place'           => $place
                     );

        global $bdd;

        // Stockage de l'enregistrement en table
        $req = $bdd->prepare('INSERT INTO movie_house(film,
        																							to_delete,
        																							date_add,
                                                      identifiant_add,
        																							date_theater,
        																							date_release,
        																							link, poster,
        																							trailer,
        																							id_url,
        																							doodle,
        																							date_doodle,
        																							time_doodle,
        																							restaurant,
        																							place)
        																			VALUES(:film,
        																						 :to_delete,
                                                     :date_add,
        																						 :identifiant_add,
        																						 :date_theater,
        																						 :date_release,
        																						 :link, :poster,
        																						 :trailer,
        																						 :id_url,
        																						 :doodle,
        																						 :date_doodle,
        																					   :time_doodle,
        																						 :restaurant,
        																						 :place)');
        $req->execute($film);
        $req->closeCursor();

        $_SESSION['film_added'] = true;

        $new_id = $bdd->lastInsertId();
      }
    }
    else
      $_SESSION['wrong_date'] = true;

    return $new_id;
  }

  // METIER : Modification film saisie avancée
  // RETOUR : Aucun
  function modFilmAvance($id_film, $post)
  {
    // Sauvegarde en session en cas d'erreur
    $_SESSION['nom_film_saisi']      = $post['nom_film'];
    $_SESSION['date_theater_saisie'] = $post['date_theater'];
    $_SESSION['date_release_saisie'] = $post['date_release'];
    $_SESSION['trailer_saisi']       = $post['trailer'];
    $_SESSION['link_saisi']          = $post['link'];
    $_SESSION['poster_saisi']        = $post['poster'];
    $_SESSION['doodle_saisi']        = $post['doodle'];
    $_SESSION['date_doodle_saisie']  = $post['date_doodle'];

    if (isset($post['hours_doodle']))
      $_SESSION['hours_doodle_saisies'] = $post['hours_doodle'];
    else
      $_SESSION['hours_doodle_saisies'] = "  ";

    if (isset($post['minutes_doodle']))
      $_SESSION['minutes_doodle_saisies'] = $post['minutes_doodle'];
    else
      $_SESSION['minutes_doodle_saisies'] = "  ";

    $_SESSION['time_doodle_saisi'] = $_SESSION['hours_doodle_saisies'] . $_SESSION['minutes_doodle_saisies'];
    $_SESSION['restaurant_saisi']  = $post['restaurant'];
    $_SESSION['place_saisie']      = $post['place'];

    // Récupération des variables
    $nom_film     = $post['nom_film'];
    $to_delete    = "N";
    $date_theater = "";
    $date_release = "";
    $link         = $post['link'];
    $poster       = $post['poster'];
    $trailer      = $post['trailer'];
    $doodle       = $post['doodle'];
    $date_doodle  = "";

    if (!empty($post['date_doodle']) AND isset($post['hours_doodle']) AND isset($post['minutes_doodle']))
      $time_doodle = $post['hours_doodle'] . $post['minutes_doodle'];
    else
      $time_doodle = "";

    $restaurant   = $post['restaurant'];
    $place        = $post['place'];

    // Récupération ID vidéo
    $id_url = extract_url($trailer);

    // Récupération date sortie cinéma
    $date_a_verifier_1 = $post['date_theater'];

    // Contrôle date à vide
		if (empty($date_a_verifier_1))
		{
			if (isLastDayOfYearWednesday(date('Y')))
			{
				$date_a_verifier_1 = '30/12/' . date('Y');
				$date_theater      = date('Y') . '1230';
			}
			else
			{
				$date_a_verifier_1 = '31/12/' . date('Y');
				$date_theater      = date('Y') . '1231';
			}
		}
		else
			$date_theater = formatDateForInsert($date_a_verifier_1);

    // On décompose la date à contrôler
    list($d, $m, $y) = explode('/', $date_a_verifier_1);

    // On vérifie le format de la date
    if (checkdate($m, $d, $y))
    {
      // Contrôle date sortie DVD
      if (isset($post['date_release']) AND !empty($post['date_release']))
      {
        $date_a_verifier_2 = $post['date_release'];

        list($d, $m, $y) = explode('/', $date_a_verifier_2);

        // On vérifie le format de la date 2 (date sortie dvd/bluray)
        if (checkdate($m, $d, $y))
          $date_release = formatDateForInsert($post['date_release']);
        else
          $_SESSION['wrong_date'] = true;
      }

      // Contrôle date Doodle
      if (isset($post['date_doodle']) AND !empty($post['date_doodle']))
      {
        $date_a_verifier_3 = $post['date_doodle'];

        list($d, $m, $y) = explode('/', $date_a_verifier_3);

        // On vérifie le format de la date 3 (date proposée)
        if (checkdate($m, $d, $y))
          $date_doodle = formatDateForInsert($post['date_doodle']);
        else
          $_SESSION['wrong_date'] = true;
      }

      if ($_SESSION['wrong_date'] != true)
			{
        $film = array('film'         => $nom_film,
                      'date_theater' => $date_theater,
                      'date_release' => $date_release,
                      'link'         => $link,
                      'poster'       => $poster,
                      'trailer'      => $trailer,
                      'id_url'       => $id_url,
                      'doodle'       => $doodle,
                      'date_doodle'  => $date_doodle,
                      'time_doodle'  => $time_doodle,
                      'restaurant'   => $restaurant,
                      'place'        => $place
                     );

        global $bdd;

        // Modification de l'enregistrement en table
				$req = $bdd->prepare('UPDATE movie_house SET film         = :film,
															                       date_theater = :date_theater,
															                       date_release = :date_release,
															                       link         = :link,
															                       poster       = :poster,
															                       trailer      = :trailer,
															                       id_url       = :id_url,
															                       doodle       = :doodle,
															                       date_doodle  = :date_doodle,
															                       time_doodle  = :time_doodle,
															                       restaurant   = :restaurant,
															                       place        = :place
														                     WHERE id = ' . $id_film);
        $req->execute($film);
        $req->closeCursor();

        $_SESSION['film_modified'] = true;
      }
    }
    else
      $_SESSION['wrong_date'] = true;
  }

  // METIER : Envoi mail sortie film
  // RETOUR : Aucun
  function sendMail($id_film, $details, $participants)
  {
    // Traitement de sécurité
    $details->setId(htmlspecialchars($details->getId()));
    $details->setFilm(htmlspecialchars($details->getFilm()));
    $details->setTo_delete(htmlspecialchars($details->getTo_delete()));
    $details->setDate_add(htmlspecialchars($details->getDate_add()));
    $details->setIdentifiant_add(htmlspecialchars($details->getIdentifiant_add()));
    $details->setDate_theater(htmlspecialchars($details->getDate_theater()));
    $details->setDate_release(htmlspecialchars($details->getDate_release()));
    $details->setLink(htmlspecialchars($details->getLink()));
    $details->setPoster(htmlspecialchars($details->getPoster()));
    $details->setTrailer(htmlspecialchars($details->getTrailer()));
    $details->setId_url(htmlspecialchars($details->getId_url()));
    $details->setDoodle(htmlspecialchars($details->getDoodle()));
    $details->setDate_doodle(htmlspecialchars($details->getDate_doodle()));
    $details->setTime_doodle(htmlspecialchars($details->getTime_doodle()));
    $details->setRestaurant(htmlspecialchars($details->getRestaurant()));
    $details->setNb_comments(htmlspecialchars($details->getNb_comments()));
    $details->setStars_user(htmlspecialchars($details->getStars_user()));
    $details->setParticipation(htmlspecialchars($details->getParticipation()));
    $details->setNb_users(htmlspecialchars($details->getNb_users()));
    $details->setAverage(htmlspecialchars($details->getAverage()));

    foreach ($participants as $participant)
    {
      $participant->setId(htmlspecialchars($participant->getId()));
      $participant->setId_film(htmlspecialchars($participant->getId_film()));
      $participant->setIdentifiant(htmlspecialchars($participant->getIdentifiant()));
      $participant->setPseudo(htmlspecialchars($participant->getPseudo()));
      $participant->setAvatar(htmlspecialchars($participant->getAvatar()));
      $participant->setEmail(htmlspecialchars($participant->getEmail()));
      $participant->setStars(htmlspecialchars($participant->getStars()));
      $participant->setParticipation(htmlspecialchars($participant->getParticipation()));
    }

    // On envoie un mail par personne et non un mail groupé
    foreach ($participants as $participant)
    {
      if ($_SESSION['mail_film_error'] != true)
      {
        if (!empty($participant->getEmail()))
        {
          include_once('../../includes/appel_mail.php');

          // Destinataire
          $mail->clearAddresses();
          $mail->AddAddress($participant->getEmail(), $participant->getPseudo());

          // Objet
          $mail->Subject = 'Votre participation à "' . $details->getFilm() . '"';

          // Contenu message
          $message = getModeleFilm($details, $participants);
          $mail->MsgHTML($message);

          // Envoi du mail avec gestion des erreurs
          if(!$mail->Send())
          {
            echo 'Erreur : ' . $mail->ErrorInfo;
            $_SESSION['mail_film_error'] = true;
            $_SESSION['mail_film_send']  = NULL;
          }
          else
          {
            $_SESSION['mail_film_error'] = NULL;
            $_SESSION['mail_film_send']  = true;
          }

          //var_dump($mail);
          //echo $message;
        }
      }
    }
  }
?>
