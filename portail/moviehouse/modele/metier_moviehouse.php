<?php
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

  // METIER : Lecture liste des films récents
  // RETOUR : Tableau des films récents
  function getRecents($year)
  {
    $listRecents = array();

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" AND SUBSTR(date_add, 1, 4) = "' . $year . '" ORDER BY SUBSTR(date_add, 1, 4) DESC, id DESC LIMIT 6');
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

    // Calcul date du jour - 1 mois
    $date_du_jour_moins_1_mois = date("Ymd", strtotime('now -1 Month'));

    // Récupération des films éligibles
    $reponse = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" AND SUBSTR(date_theater, 1, 4) = "' . $year . '" ORDER BY date_theater ASC');
    while($donnees = $reponse->fetch())
    {
      // On récupère les films si ce n'est pas l'année courante ou jusqu'à un mois en arrière si c'est l'année courante
      if ($year != date("Y") OR ($year == date("Y") AND $donnees['date_theater'] > $date_du_jour_moins_1_mois))
      {
        // Récupération des données
        $myAttendu = Movie::withData($donnees);

        // Récupération nombre d'utilisateurs et moyenne
        $nb_users    = 0;
        $total_stars = 0;
        $average     = 0;

        $reponse2 = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = ' . $myAttendu->getId());
        while($donnees2 = $reponse2->fetch())
        {
          $nb_users    += 1;
          $total_stars += $donnees2['stars'];
        }
        $reponse2->closeCursor();

        if ($nb_users > 0)
        {
          $average = str_replace('.', ',', round($total_stars / $nb_users, 1));

          $myAttendu->setNb_users($nb_users);
          $myAttendu->setAverage($average);

          // On ajoute la ligne au tableau seulement s'il y a des participants ou une moyenne
          if ($myAttendu->getAverage() != 0)
            array_push($listAttendus, $myAttendu);
        }
      }
    }
    $reponse->closeCursor();

    // Tris
    if (isset($listAttendus) AND !empty($listAttendus))
    {
      // On trie les films par nombre d'utilisateurs en 1er et par moyenne en 2ème
      $tri_1 = NULL;
      $tri_2 = NULL;

      foreach ($listAttendus as $attendu)
      {
        $tri_1[] = $attendu->getNb_users();
        $tri_2[] = $attendu->getAverage();
      }

      array_multisort($tri_1, SORT_DESC, $tri_2, SORT_DESC, $listAttendus);

      // On extrait les 6 premièrs films les plus attentus
      $listAttendus = array_slice($listAttendus, 0, 6);

      // Tri final sur la moyenne
      foreach ($listAttendus as $attendu)
      {
        $tri_average[] = $attendu->getAverage();
      }
      array_multisort($tri_average, SORT_DESC, $listAttendus);
    }

    return $listAttendus;
  }

  // METIER : Lecture des prochaines sorties
  // RETOUR : Tableau des films avec sortie prévue
  function getSorties($year)
  {
    $listSorties = array();

    global $bdd;

    if ($year == date("Y"))
      $reponse = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" AND date_doodle != "" AND date_doodle >= ' . date("Ymd") . ' ORDER BY date_doodle ASC, id DESC LIMIT 6');
    else
      $reponse = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" AND date_doodle != "" AND SUBSTR(date_doodle, 1, 4) = ' . $year . ' ORDER BY date_doodle ASC, id DESC LIMIT 6');
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

    $reponse = $bdd->query('SELECT DISTINCT SUBSTR(date_theater, 1, 4) FROM movie_house WHERE to_delete != "Y" ORDER BY SUBSTR(date_theater, 1, 4) DESC');
    while($donnees = $reponse->fetch())
    {
      // On ajoute la ligne au tableau
      array_push($listOnglets, $donnees['SUBSTR(date_theater, 1, 4)']);
    }
    $reponse->closeCursor();

    return $listOnglets;
  }

  // METIER : Récupère les étoiles utilisateurs de chaque film
  // RETOUR : Tableau des étoiles utilisateurs
  function getStarsFiches($listFilms)
  {
    global $bdd;

    $tabStars = array();

    foreach ($listFilms as $film)
    {
      $starsFilm = array();

      $reponse = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = ' . $film->getId() . ' ORDER BY stars DESC, identifiant ASC');
      while($donnees = $reponse->fetch())
      {
        $reponse2 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $donnees['identifiant'] . '"');
        $donnees2 = $reponse2->fetch();

        $pseudo = $donnees2['pseudo'];
        $avatar = $donnees2['avatar'];

        $reponse2->closeCursor();

        $myStars = array('identifiant' => $donnees['identifiant'],
                         'pseudo'      => $pseudo,
                         'avatar'      => $avatar,
                         'stars'       => $donnees['stars']
                        );

        array_push($starsFilm, $myStars);
      }
      $reponse->closeCursor();

      $tabStars[$film->getId()] = $starsFilm;
    }

    return $tabStars;
  }

  // METIER : Lecture nombre d'utilisateurs inscrits
  // RETOUR : Nombre d'utilisateurs
  function countUsers()
  {
    global $bdd;

    $reponse = $bdd->query('SELECT COUNT(id) AS nb_users FROM users WHERE identifiant != "admin" AND status != "I"');
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

    $reponse = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant != "admin"  AND status != "I" ORDER BY identifiant ASC');
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

    //var_dump($listeFilms);

    // Récupération d'une liste des étoiles
    $reponse2 = $bdd->query('SELECT * FROM movie_house_users ORDER BY identifiant ASC');
    while($donnees2 = $reponse2->fetch())
    {
      // Ajout d'un objet Stars (instancié à partir des données de la base) au tableau de dépenses
      array_push($listeStars, Stars::withData($donnees2));
    }
    $reponse2->closeCursor();

    //var_dump($listeStars);

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

      //var_dump($tableauStars);

      // On compte le nombre d'utilisateurs et on remplit le tableau final seulement si on a atteint le nombre total d'utilisateurs inscrits
      if (count($tableauStars) == $nb_users)
      {
        // On génère une ligne dans le tableau final
        $mySynthese = array('id_film'      => $listeFilms[$i]->getId(),
                            'film'         => $listeFilms[$i]->getFilm(),
                            'date_theater' => $listeFilms[$i]->getDate_theater(),
                            'tableStars'   => $tableauStars
                           );

        //var_dump($mySynthese);

        array_push($tableauFilms, $mySynthese);
     }

     $i++;
   }

   //var_dump($tableauFilms);

   return $tableauFilms;
  }

  // METIER : Insertion film
  // RETOUR : Id film créé
  function insertFilm($post, $user)
  {
    $new_id     = NULL;
    $control_ok = true;

    // Sauvegarde en session en cas d'erreur
    $_SESSION['save']['nom_film_saisi']      = $post['nom_film'];
    $_SESSION['save']['date_theater_saisie'] = $post['date_theater'];
    $_SESSION['save']['date_release_saisie'] = $post['date_release'];
    $_SESSION['save']['trailer_saisi']       = $post['trailer'];
    $_SESSION['save']['link_saisi']          = $post['link'];
    $_SESSION['save']['poster_saisi']        = $post['poster'];
    $_SESSION['save']['synopsis_saisi']      = $post['synopsis'];
    $_SESSION['save']['doodle_saisi']        = $post['doodle'];
    $_SESSION['save']['date_doodle_saisie']  = $post['date_doodle'];

    if (isset($post['hours_doodle']))
      $_SESSION['save']['hours_doodle_saisies'] = $post['hours_doodle'];
    else
      $_SESSION['save']['hours_doodle_saisies'] = "  ";

    if (isset($post['minutes_doodle']))
      $_SESSION['save']['minutes_doodle_saisies'] = $post['minutes_doodle'];
    else
      $_SESSION['save']['minutes_doodle_saisies'] = "  ";

    $_SESSION['save']['time_doodle_saisi'] = $_SESSION['save']['hours_doodle_saisies'] . $_SESSION['save']['minutes_doodle_saisies'];
    $_SESSION['save']['restaurant_saisi']  = $post['restaurant'];
    $_SESSION['save']['place_saisie']      = $post['place'];

    // Récupération des variables
    $nom_film        = $post['nom_film'];
    $to_delete       = "N";
    $date_add        = date("Ymd");
    $identifiant_add = $user;
    $identifiant_del = "";
    $synopsis        = $post['synopsis'];
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

    // Contrôle date sortie cinéma
    if ($control_ok == true)
    {
  		$date_a_verifier_1 = $post['date_theater'];

      // Vérification date à vide
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

      // On contrôle la date
      if (validateDate($date_a_verifier_1, "d/m/Y") != true)
      {
        $_SESSION['alerts']['wrong_date'] = true;
        $control_ok                       = false;
      }
    }

    // Contrôle date sortie DVD / Bluray
    if ($control_ok == true)
    {
      if (isset($post['date_release']) AND !empty($post['date_release']))
      {
        $date_a_verifier_2 = $post['date_release'];

        // On contrôle la date
        if (validateDate($date_a_verifier_2, "d/m/Y") != true)
        {
          $_SESSION['alerts']['wrong_date'] = true;
          $control_ok                       = false;
        }
        else
          $date_release = formatDateForInsert($date_a_verifier_2);
      }
    }

    // Contrôle date Doodle
    if ($control_ok == true)
    {
      if (isset($post['date_doodle']) AND !empty($post['date_doodle']))
      {
        $date_a_verifier_3 = $post['date_doodle'];

        // On contrôle la date
        if (validateDate($date_a_verifier_3, "d/m/Y") != true)
        {
          $_SESSION['alerts']['wrong_date'] = true;
          $control_ok                       = false;
        }
        else
          $date_doodle = formatDateForInsert($date_a_verifier_3);
      }
    }

    // Contrôle date Doodle >= date sortie film
    if ($control_ok == true)
    {
      if (isset($post['date_theater']) AND !empty($post['date_theater']) AND isset($post['date_doodle']) AND !empty($post['date_doodle']))
      {
        if ($date_doodle < $date_theater)
        {
          $_SESSION['alerts']['wrong_date_doodle'] = true;
          $control_ok                              = false;
        }
      }
    }

    // Insertion en base
    if ($control_ok == true)
    {
      $film = array('film'            => $nom_film,
                    'to_delete'       => $to_delete,
                    'date_add'        => $date_add,
                    'identifiant_add' => $identifiant_add,
                    'identifiant_del' => $identifiant_del,
                    'synopsis'        => $synopsis,
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
                                                    identifiant_del,
                                                    synopsis,
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
      																						 :identifiant_del,
                                                   :synopsis,
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

      // Id pour redirection sur détails
      $new_id = $bdd->lastInsertId();

      // Génération notification film ajouté
      insertNotification($user, 'film', $new_id);

      // Génération notification Doodle renseigné
      if (!empty($doodle))
        insertNotification($user, 'doodle', $new_id);

      // Génération succès
      insertOrUpdateSuccesValue('publisher', $user, 1);

      // Ajout expérience
      insertExperience($user, 'add_film');

      $_SESSION['alerts']['film_added'] = true;
    }

    return $new_id;
  }
?>
