<?php
  // METIER : Initialise les données de sauvegarde en session
  // RETOUR : Aucun
  function initializeSaveSession()
  {
    // On initialise les champs de saisie s'il n'y a pas d'erreur
    if ((!isset($_SESSION['alerts']['wrong_date'])        OR $_SESSION['alerts']['wrong_date']        != true)
    AND (!isset($_SESSION['alerts']['wrong_date_doodle']) OR $_SESSION['alerts']['wrong_date_doodle'] != true))
    {
      unset($_SESSION['save']);

      $_SESSION['save']['nom_film_saisi']         = '';
      $_SESSION['save']['date_theater_saisie']    = '';
      $_SESSION['save']['date_release_saisie']    = '';
      $_SESSION['save']['trailer_saisi']          = '';
      $_SESSION['save']['link_saisi']             = '';
      $_SESSION['save']['poster_saisi']           = '';
      $_SESSION['save']['synopsis_saisi']         = '';
      $_SESSION['save']['doodle_saisi']           = '';
      $_SESSION['save']['date_doodle_saisie']     = '';
      $_SESSION['save']['time_doodle_saisi']      = '';
      $_SESSION['save']['hours_doodle_saisies']   = '';
      $_SESSION['save']['minutes_doodle_saisies'] = '';
      $_SESSION['save']['restaurant_saisi']       = '';
      $_SESSION['save']['place_saisie']           = '';
    }
  }

  // METIER : Contrôle année existante (pour les onglets)
  // RETOUR : Booléen
  function controlYear($year)
  {
    $anneeExistante = false;

    if (isset($year))
    {
      global $bdd;

      if (is_numeric($year))
      {
        $reponse = $bdd->query('SELECT * FROM movie_house WHERE SUBSTR(date_theater, 1, 4) = "' . $year . '" AND to_delete != "Y"');

        if ($reponse->rowCount() > 0)
          $anneeExistante = true;

        $reponse->closeCursor();
      }
      elseif ($year == 'none')
      {
        $reponse = $bdd->query('SELECT * FROM movie_house WHERE date_theater = "" AND to_delete != "Y"');

        if ($reponse->rowCount() > 0)
          $anneeExistante = true;

        $reponse->closeCursor();
      }
    }

    return $anneeExistante;
  }

  // METIER : Lecture liste des films récents
  // RETOUR : Liste des films récents
  function getRecents($year)
  {
    $listeFilmsRecents = array();

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" AND SUBSTR(date_add, 1, 4) = "' . $year . '" ORDER BY SUBSTR(date_add, 1, 4) DESC, id DESC LIMIT 5');
    while ($donnees = $reponse->fetch())
    {
      $filmRecent = Movie::withData($donnees);

      // On ajoute la ligne au tableau
      array_push($listeFilmsRecents, $filmRecent);
    }
    $reponse->closeCursor();

    return $listeFilmsRecents;
  }

  // METIER : Vérifie si la semaine en cours doit être affichée pour les sorties de la semaine
  // RETOUR : Booléen
  function controlWeek($currentYear)
  {
    $afficherSemaine = true;

    // Calcul des dates de la semaine
    $nombreJoursLundi    = 1 - date('N');
    $nombreJoursDimanche = 7 - date('N');
    $lundi               = date('Ymd', strtotime('+' . $nombreJoursLundi . ' days'));
    $dimanche            = date('Ymd', strtotime('+' . $nombreJoursDimanche . ' days'));

    // Récupération des années
    $yearOfMonday = substr($lundi, 0, 4);
    $yearOfSunday = substr($dimanche, 0, 4);

    // Contrôle
    if ($currentYear != $yearOfMonday AND $currentYear != $yearOfSunday)
      $afficherSemaine = false;

    return $afficherSemaine;
  }

  // METIER : Lecture liste des films qui sortent la semaine courante
  // RETOUR : Listes des films qui sortent la semaine courante
  function getSemaine()
  {
    // Calcul des dates de la semaine
    $nombreJoursLundi    = 1 - date('N');
    $nombreJoursDimanche = 7 - date('N');
    $lundi               = date('Ymd', strtotime('+' . $nombreJoursLundi . ' days'));
    $dimanche            = date('Ymd', strtotime('+' . $nombreJoursDimanche . ' days'));

    $listeFilmsSemaine = array();

    global $bdd;

    // Récupération des films éligibles
    $reponse = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" AND date_theater >= "' . $lundi . '" AND date_theater <= "' . $dimanche . '" ORDER BY id DESC');
    while ($donnees = $reponse->fetch())
    {
      $filmSemaine = Movie::withData($donnees);
      array_push($listeFilmsSemaine, $filmSemaine);
    }
    $reponse->closeCursor();

    return $listeFilmsSemaine;
  }

  // METIER : Lecture liste des films les plus attendus
  // RETOUR : Liste des films attendus
  function getAttendus($year)
  {
    $listeFilmsAttendus = array();

    global $bdd;

    // Calcul date du jour - 1 mois
    $dateJourMoins1Mois = date('Ymd', strtotime('now -1 Month'));

    // Récupération des films éligibles
    $reponse = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" AND SUBSTR(date_theater, 1, 4) = "' . $year . '" ORDER BY date_theater ASC');
    while ($donnees = $reponse->fetch())
    {
      // On récupère les films si ce n'est pas l'année courante ou jusqu'à un mois en arrière si c'est l'année courante
      if ($year != date('Y') OR ($year == date('Y') AND $donnees['date_theater'] > $dateJourMoins1Mois))
      {
        // Récupération des données
        $filmAttendu = Movie::withData($donnees);

        // Récupération nombre d'utilisateurs et moyenne
        $nombreUsers = 0;
        $totalStars  = 0;
        $average     = 0;

        $reponse2 = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = ' . $filmAttendu->getId());
        while ($donnees2 = $reponse2->fetch())
        {
          $nombreUsers += 1;
          $totalStars  += $donnees2['stars'];
        }
        $reponse2->closeCursor();

        if ($nombreUsers > 0)
        {
          $average = str_replace('.', ',', round($totalStars / $nombreUsers, 1));

          $filmAttendu->setNb_users($nombreUsers);
          $filmAttendu->setAverage($average);

          // On ajoute la ligne au tableau seulement s'il y a des participants ou une moyenne
          if ($filmAttendu->getAverage() != 0)
            array_push($listeFilmsAttendus, $filmAttendu);
        }
      }
    }
    $reponse->closeCursor();

    // Tris
    if (isset($listeFilmsAttendus) AND !empty($listeFilmsAttendus))
    {
      // On trie les films par nombre d'utilisateurs en 1er et par moyenne en 2ème
      $triNombreUsers = NULL;
      $triMoyenne     = NULL;

      foreach ($listeFilmsAttendus as $attendu)
      {
        $triNombreUsers[] = $attendu->getNb_users();
        $triMoyenne[]     = $attendu->getAverage();
      }

      array_multisort($triNombreUsers, SORT_DESC, $triMoyenne, SORT_DESC, $listeFilmsAttendus);

      // On extrait les 5 premièrs films les plus attentus
      $listeFilmsAttendus = array_slice($listeFilmsAttendus, 0, 5);

      // Tri final sur la moyenne
      foreach ($listeFilmsAttendus as $attendu)
      {
        $triAverage[] = $attendu->getAverage();
      }
      array_multisort($triAverage, SORT_DESC, $listeFilmsAttendus);
    }

    // Retour
    return $listeFilmsAttendus;
  }

  // METIER : Lecture des prochaines sorties
  // RETOUR : Liste des films avec sortie prévue
  function getSorties($year)
  {
    $listeFilmsSorties = array();

    global $bdd;

    if ($year == date('Y'))
      $reponse = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" AND date_doodle != "" AND date_doodle >= ' . date('Ymd') . ' AND SUBSTR(date_doodle, 1, 4) = ' . $year . ' ORDER BY date_doodle ASC, id DESC LIMIT 5');
    elseif ($year > date('Y'))
      $reponse = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" AND date_doodle != "" AND SUBSTR(date_doodle, 1, 4) = ' . $year . ' ORDER BY date_doodle ASC, id DESC LIMIT 5');
    elseif ($year < date('Y'))
      $reponse = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" AND date_doodle != "" AND SUBSTR(date_doodle, 1, 4) = ' . $year . ' ORDER BY date_doodle ASC, id DESC');

    while ($donnees = $reponse->fetch())
    {
      $filmSortie = Movie::withData($donnees);

      // On ajoute la ligne au tableau
      array_push($listeFilmsSorties, $filmSortie);
    }
    $reponse->closeCursor();

    return $listeFilmsSorties;
  }

  // METIER : Lecture des années distinctes
  // RETOUR : Liste des années
  function getOnglets()
  {
    $listOnglets = array();

    global $bdd;

    $reponse = $bdd->query('SELECT DISTINCT SUBSTR(date_theater, 1, 4) FROM movie_house WHERE to_delete != "Y" ORDER BY SUBSTR(date_theater, 1, 4) DESC');
    while ($donnees = $reponse->fetch())
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

    $tableauStars = array();

    foreach ($listFilms as $film)
    {
      $listeStarsFilm = array();

      $reponse = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = ' . $film->getId() . ' ORDER BY stars DESC, identifiant ASC');
      while ($donnees = $reponse->fetch())
      {
        $reponse2 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $donnees['identifiant'] . '"');
        $donnees2 = $reponse2->fetch();

        $pseudo = $donnees2['pseudo'];
        $avatar = $donnees2['avatar'];

        $reponse2->closeCursor();

        $starsFilm = new Stars();

        $starsFilm->setIdentifiant($donnees['identifiant']);
        $starsFilm->setPseudo($pseudo);
        $starsFilm->setAvatar($avatar);
        $starsFilm->setStars($donnees['stars']);

        array_push($listeStarsFilm, $starsFilm);
      }
      $reponse->closeCursor();

      $tableauStars[$film->getId()] = $listeStarsFilm;
    }

    return $tableauStars;
  }

  // METIER : Insertion film
  // RETOUR : Id film créé
  function insertFilm($post, $user)
  {
    $newId      = NULL;
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
      $_SESSION['save']['hours_doodle_saisies'] = '  ';

    if (isset($post['minutes_doodle']))
      $_SESSION['save']['minutes_doodle_saisies'] = $post['minutes_doodle'];
    else
      $_SESSION['save']['minutes_doodle_saisies'] = '  ';

    $_SESSION['save']['time_doodle_saisi'] = $_SESSION['save']['hours_doodle_saisies'] . $_SESSION['save']['minutes_doodle_saisies'];
    $_SESSION['save']['restaurant_saisi']  = $post['restaurant'];
    $_SESSION['save']['place_saisie']      = $post['place'];

    // Récupération des variables
    $nomFilm        = $post['nom_film'];
    $toDelete       = 'N';
    $dateAdd        = date('Ymd');
    $identifiantAdd = $user;
    $identifiantDel = '';
    $synopsis       = $post['synopsis'];
    $dateTheater    = formatDateForInsert($post['date_theater']);
    $dateRelease    = formatDateForInsert($post['date_release']);
    $link           = $post['link'];
    $poster         = $post['poster'];
    $trailer        = $post['trailer'];
    $doodle         = $post['doodle'];
    $dateDoodle     = formatDateForInsert($post['date_doodle']);

    if (!empty($post['date_doodle']) AND isset($post['hours_doodle']) AND isset($post['minutes_doodle']))
      $timeDoodle = $post['hours_doodle'] . $post['minutes_doodle'];
    else
      $timeDoodle = '';

    $restaurant = $post['restaurant'];
    $place      = $post['place'];

    // Récupération ID vidéo
    $idUrl = extractUrl($trailer);

    // Contrôle date sortie cinéma
    if (isset($post['date_theater']) AND !empty($post['date_theater']))
    {
      if (validateDate($post['date_theater']) != true)
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
        if (validateDate($post['date_release']) != true)
        {
          $_SESSION['alerts']['wrong_date'] = true;
          $control_ok                       = false;
        }
      }
    }

    // Contrôle date Doodle
    if ($control_ok == true)
    {
      if (isset($post['date_doodle']) AND !empty($post['date_doodle']))
      {
        if (validateDate($post['date_doodle']) != true)
        {
          $_SESSION['alerts']['wrong_date'] = true;
          $control_ok                       = false;
        }
      }
    }

    // Contrôle date Doodle >= date sortie film
    if ($control_ok == true)
    {
      if (!empty($dateTheater) AND !empty($dateDoodle))
      {
        if ($dateDoodle < $dateTheater)
        {
          $_SESSION['alerts']['wrong_date_doodle'] = true;
          $control_ok                              = false;
        }
      }
    }

    // Insertion en base
    if ($control_ok == true)
    {
      $film = array('film'            => $nomFilm,
                    'to_delete'       => $toDelete,
                    'date_add'        => $dateAdd,
                    'identifiant_add' => $identifiantAdd,
                    'identifiant_del' => $identifiantDel,
                    'synopsis'        => $synopsis,
                    'date_theater'    => $dateTheater,
                    'date_release'    => $dateRelease,
                    'link'            => $link,
                    'poster'          => $poster,
                    'trailer'         => $trailer,
                    'id_url'          => $idUrl,
                    'doodle'          => $doodle,
                    'date_doodle'     => $dateDoodle,
                    'time_doodle'     => $timeDoodle,
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
      $newId = $bdd->lastInsertId();

      // Génération notification film ajouté
      insertNotification($user, 'film', $newId);

      // Génération notification Doodle renseigné
      if (!empty($doodle))
        insertNotification($user, 'doodle', $newId);

      // Génération succès
      insertOrUpdateSuccesValue('publisher', $user, 1);

      // Ajout expérience
      insertExperience($user, 'add_film');

      $_SESSION['alerts']['film_added'] = true;
    }

    return $newId;
  }
?>
