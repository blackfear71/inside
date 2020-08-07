<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/profile.php');
  include_once('../../includes/classes/success.php');
  include_once('../../includes/libraries/php/imagethumb.php');

  // METIER : Lecture des données profil
  // RETOUR : Objet Profile
  function getProfile($user)
  {
    global $bdd;

    // Lecture des données utilisateur
    $reponse = $bdd->query('SELECT * FROM users WHERE identifiant = "' . $user . '"');
    $donnees = $reponse->fetch();

    // Instanciation d'un objet Profile à partir des données remontées de la bdd
    $profile = Profile::withData($donnees);

    $reponse->closeCursor();

    return $profile;
  }

  // METIER : Lecture des données statistiques profil
  // RETOUR : Objet Statistiques
  function getStatistiques($user)
  {
    $nombreFilmsAjoutes = 0;
    $nombreCommentaires = 0;
    $nombreReservations = 0;
    $nombreGateaux      = 0;
    $nombreRecettes     = 0;
    $expenses           = 0;
    $nombreCollectors   = 0;
    $nombreIdees        = 0;
    $nombreBugs         = 0;
    $nombreEvolutions   = 0;

    global $bdd;

    // Nombre de films ajoutés Movie House
    $reponse = $bdd->query('SELECT COUNT(id) AS nb_films_ajoutes FROM movie_house WHERE identifiant_add = "' . $user . '" AND to_delete != "Y"');
    $donnees = $reponse->fetch();

    $nombreFilmsAjoutes = $donnees['nb_films_ajoutes'];

    $reponse->closeCursor();

    // Nombre de commentaires Movie House
    $reponse0 = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" ORDER BY id ASC');
    while ($donnees0 = $reponse0->fetch())
    {
      $reponse1 = $bdd->query('SELECT * FROM movie_house_comments WHERE id_film = ' . $donnees0['id'] . ' AND author = "' . $user . '"');

      if ($reponse1->rowCount() > 0)
        $nombreCommentaires++;

      $reponse1->closeCursor();
    }
    $reponse0->closeCursor();

    // Nombre de réservations de restaurants
    $reponse2 = $bdd->query('SELECT COUNT(id) AS nb_reservations FROM food_advisor_choices WHERE caller = "' . $user . '"');
    $donnees2 = $reponse2->fetch();

    $nombreReservations = $donnees2['nb_reservations'];

    $reponse2->closeCursor();

    // Nombre de gâteaux faits
    $reponse3 = $bdd->query('SELECT COUNT(id) AS nb_gateaux FROM cooking_box WHERE identifiant = "' . $user . '" AND cooked = "Y"');
    $donnees3 = $reponse3->fetch();

    $nombreGateaux = $donnees3['nb_gateaux'];

    $reponse3->closeCursor();

    // Nombre de recettes saisies
    $reponse4 = $bdd->query('SELECT COUNT(id) AS nb_recettes FROM cooking_box WHERE identifiant = "' . $user . '" AND name != "" AND picture != ""');
    $donnees4 = $reponse4->fetch();

    $nombreRecettes = $donnees4['nb_recettes'];

    $reponse4->closeCursor();

    // Solde des dépenses
    $reponse5 = $bdd->query('SELECT id, identifiant, expenses FROM users WHERE identifiant = "' . $user . '"');
    $donnees5 = $reponse5->fetch();

    $expenses = $donnees5['expenses'];

    $reponse5->closeCursor();

    // Nombre de phrases cultes soumises
    $reponse6 = $bdd->query('SELECT COUNT(id) AS nb_collectors FROM collector WHERE author = "' . $user . '"');
    $donnees6 = $reponse6->fetch();

    $nombreCollectors = $donnees6['nb_collectors'];

    $reponse6->closeCursor();

    // Nombre d'idées soumises
    $reponse7 = $bdd->query('SELECT COUNT(id) AS nb_idees FROM ideas WHERE author = "' . $user . '"');
    $donnees7 = $reponse7->fetch();

    $nombreIdees = $donnees7['nb_idees'];

    $reponse7->closeCursor();

    // Nombre de bugs rapportés
    $reponse8 = $bdd->query('SELECT COUNT(id) AS nb_bugs FROM bugs WHERE author = "' . $user . '" AND type = "B"');
    $donnees8 = $reponse8->fetch();

    $nombreBugs = $donnees8['nb_bugs'];

    $reponse8->closeCursor();

    // Nombre d'évolutions proposées
    $reponse9 = $bdd->query('SELECT COUNT(id) AS nb_evolutions FROM bugs WHERE author = "' . $user . '" AND type = "E"');
    $donnees9 = $reponse9->fetch();

    $nombreEvolutions = $donnees9['nb_evolutions'];

    $reponse9->closeCursor();

    // On construit un tableau avec les données statistiques
    $statistiques = array('nb_films_ajoutes' => $nombreFilmsAjoutes,
                          'nb_comments'      => $nombreCommentaires,
                          'nb_reservations'  => $nombreReservations,
                          'nb_gateaux'       => $nombreGateaux,
                          'nb_recettes'      => $nombreRecettes,
                          'expenses'         => $expenses,
                          'nb_collectors'    => $nombreCollectors,
                          'nb_ideas'         => $nombreIdees,
                          'nb_bugs'          => $nombreBugs,
                          'nb_evolutions'    => $nombreEvolutions
                         );

    // Instanciation d'un objet StatistiquesProfil à partir des données remontées de la bdd
    $tableauStatistiques = StatistiquesProfil::withData($statistiques);

    return $tableauStatistiques;
  }

  // METIER : Lecture des données préférences
  // RETOUR : Objet Preferences
  function getPreferences($user)
  {
    global $bdd;

    // Lecture des préférences
    $reponse = $bdd->query('SELECT * FROM preferences WHERE identifiant = "' . $user . '"');
    $donnees = $reponse->fetch();

    // Instanciation d'un objet Profile à partir des données remontées de la bdd
    $preferences = Preferences::withData($donnees);

    $reponse->closeCursor();

    return $preferences;
  }

  // METIER : Récupération des données de progression
  // RETOUR : Tableau des données de progression
  function getProgress($experience)
  {
    $progression = new Progression();

    $niveau   = convertExperience($experience);
    $expMin   = 10 * $niveau ** 2;
    $expMax   = 10 * ($niveau + 1) ** 2;
    $expLvl   = $expMax - $expMin;
    $progress = $experience - $expMin;
    $percent  = floor($progress * 100 / $expLvl);

    $progression->setNiveau($niveau);
    $progression->setExperience_min($expMin);
    $progression->setExperience_max($expMax);
    $progression->setExperience_niveau($expLvl);
    $progression->setProgression($progress);
    $progression->setPourcentage($percent);

    return $progression;
  }

  // METIER : Mise à jour de l'avatar (base + fichier)
  // RETOUR : Aucun
  function updateAvatar($user, $files)
  {
    global $bdd;

    $control_ok = true;

    // On vérifie la présence du dossier, sinon on le créé
    $dossier = '../../includes/images/profil';

    if (!is_dir($dossier))
      mkdir($dossier);

    // On vérifie la présence du dossier d'avatars, sinon on le créé
    $dossierAvatars = $dossier . '/avatars';

    if (!is_dir($dossierAvatars))
      mkdir($dossierAvatars);

    // Dossier de destination et nom du fichier
    $avatarDir = $dossierAvatars . '/';
    $avatar    = rand();

    // Contrôles fichier
    $fileDatas = controlsUploadFile($files['avatar'], $avatar, 'all');

    // Traitements fichier
    if ($fileDatas['control_ok'] == true)
    {
      // Upload fichier
      $control_ok = uploadFile($fileDatas, $avatarDir);

      if ($control_ok == true)
      {
        $newName = $fileDatas['new_name'];

        // Créé une miniature de la source vers la destination en la rognant avec une hauteur/largeur max de 400px (cf fonction imagethumb.php)
        imagethumb($avatarDir . $newName, $avatarDir . $newName, 400, FALSE, TRUE);

        // On efface l'ancien avatar si présent
        $reponse1 = $bdd->query('SELECT identifiant, avatar FROM users WHERE identifiant = "' . $user . '"');
        $donnees1 = $reponse1->fetch();

        if (isset($donnees1['avatar']) AND !empty($donnees1['avatar']))
          unlink($dossierAvatars . '/' . $donnees1['avatar'] . '');

        $reponse1->closeCursor();

        // On stocke la référence du nouvel avatar dans la base
        $reponse2 = $bdd->prepare('UPDATE users SET avatar = :avatar WHERE identifiant = "' . $user . '"');
        $reponse2->execute(array(
          'avatar' => $newName
        ));
        $reponse2->closeCursor();

        $_SESSION['user']['avatar']           = $newName;
        $_SESSION['alerts']['avatar_updated'] = true;
      }
    }
  }

  // METIER : Suppression de l'avatar (base + fichier)
  // RETOUR : Aucun
  function deleteAvatar($user)
  {
    global $bdd;

    // On efface l'ancien avatar si présent
    $reponse1 = $bdd->query('SELECT identifiant, avatar FROM users WHERE identifiant = "' . $user . '"');
    $donnees1 = $reponse1->fetch();

    if (isset($donnees1['avatar']) AND !empty($donnees1['avatar']))
      unlink('../../includes/images/profil/avatars/' . $donnees1['avatar'] . '');

    $reponse1->closeCursor();

    // On efface la référence de l'ancien avatar dans la base
    $newName = '';

    $reponse2 = $bdd->prepare('UPDATE users SET avatar = :avatar WHERE identifiant = "' . $user . '"');
    $reponse2->execute(array(
      'avatar' => $newName
    ));
    $reponse2->closeCursor();

    $_SESSION['user']['avatar']           = '';
    $_SESSION['alerts']['avatar_deleted'] = true;
  }

  // METIER : Mise à jour des informations
  // RETOUR : Aucun
  function updateInfos($user, $post)
  {
    $control_ok = true;
    global $bdd;

    // Récupération des données
    $pseudo      = trim($post['pseudo']);
    $email       = $post['email'];
    $anniversary = $post['anniversaire'];

    // Contrôle date anniversaire
    if ($control_ok == true)
    {
      if (isset($anniversary) AND !empty($anniversary))
      {
        // On contrôle la date
        if (validateDate($anniversary) != true)
        {
          $_SESSION['alerts']['wrong_date'] = true;
          $control_ok                       = false;
        }
        else
        {
          $anniversary = formatDateForInsert($anniversary);

          // Contrôle date dans le futur
          if ($anniversary >= date('Ymd'))
          {
            $_SESSION['alerts']['date_future'] = true;
            $control_ok                        = false;
          }
        }
      }
    }

    // Mise à jour pseudo seulement si renseigné
    if ($control_ok == true AND !empty($pseudo))
    {
      $req1 = $bdd->prepare('UPDATE users SET pseudo = :pseudo WHERE identifiant = "' . $user . '"');
      $req1->execute(array(
        'pseudo' => $pseudo
      ));
      $req1->closeCursor();

      // Mise à jour de la session
      $_SESSION['user']['pseudo'] = htmlspecialchars($pseudo);
    }

    // Mise à jour de l'adresse mail
    if ($control_ok == true)
    {
      $req2 = $bdd->prepare('UPDATE users SET email  = :email WHERE identifiant = "' . $user . '"');
      $req2->execute(array(
        'email'  => $email
      ));
      $req2->closeCursor();
    }

    // Mise à jour date anniversaire
    if ($control_ok == true)
    {
      $req3 = $bdd->prepare('UPDATE users SET anniversary  = :anniversary WHERE identifiant = "' . $user . '"');
      $req3->execute(array(
        'anniversary'  => $anniversary
      ));
      $req3->closeCursor();
    }

    if ($control_ok == true)
      $_SESSION['alerts']['infos_updated'] = true;
  }

  // METIER : Mise à jour des préférences
  // RETOUR : Aucun
  function updatePreferences($user, $post)
  {
    global $bdd;

    // Préférences Notifications
    $viewNotifications = $post['notifications_view'];

		// Préférences MOVIE HOUSE
		$viewMovieHouse        = $post['movie_house_view'];
		$categoriesMovieHouse  = '';

    if (isset($post['films_semaine']))
			$categoriesMovieHouse .= 'Y;';
		else
			$categoriesMovieHouse .= 'N;';

		if (isset($post['films_waited']))
			$categoriesMovieHouse .= 'Y;';
		else
			$categoriesMovieHouse .= 'N;';

		if (isset($post['films_way_out']))
			$categoriesMovieHouse .= 'Y;';
		else
			$categoriesMovieHouse .= 'N;';

		// Préférences #THEBOX
		$viewTheBox = $post['the_box_view'];

    // Préférences INSIDE Room
    $init_chat = $post['inside_room_view'];

    // Préférences Celsius
    $celsius = $post['celsius_view'];

    // Mise à jour de la table des préférences utilisateur
    $reponse = $bdd->prepare('UPDATE preferences SET init_chat              = :init_chat,
                                                     celsius                = :celsius,
                                                     view_movie_house       = :view_movie_house,
                                                     categories_movie_house = :categories_movie_house,
                                                     view_the_box           = :view_the_box,
                                                     view_notifications     = :view_notifications
                                               WHERE identifiant = "' . $user . '"');
    $reponse->execute(array(
      'init_chat'              => $init_chat,
      'celsius'                => $celsius,
      'view_movie_house'       => $viewMovieHouse,
      'categories_movie_house' => $categoriesMovieHouse,
      'view_the_box'           => $viewTheBox,
      'view_notifications'     => $viewNotifications
    ));
    $reponse->closeCursor();

    // Mise à jour des préférences stockées en SESSION
    $_SESSION['user']['celsius']            = $celsius;
    $_SESSION['user']['view_movie_house']   = $viewMovieHouse;
    $_SESSION['user']['view_the_box']       = $viewTheBox;
    $_SESSION['user']['view_notifications'] = $viewNotifications;

    $_SESSION['alerts']['preferences_updated'] = true;
  }

  // METIER : Mise à jour du mot de passe
  // RETOUR : Aucun
  function updatePassword($user, $post)
  {
    if (!empty($post['old_password'])
    AND !empty($post['new_password'])
    AND !empty($post['confirm_new_password']))
  	{
      global $bdd;

  		// Lecture des données actuelles de l'utilisateur
  		$reponse = $bdd->query('SELECT id, identifiant, salt, password FROM users WHERE identifiant = "' . $user . '"');
  		$donnees = $reponse->fetch();

  		$old_password = htmlspecialchars(hash('sha1', $post['old_password'] . $donnees['salt']));

  		if ($old_password == $donnees['password'])
  		{
  			$salt                 = rand();
  			$new_password         = htmlspecialchars(hash('sha1', $post['new_password'] . $salt));
  			$confirm_new_password = htmlspecialchars(hash('sha1', $post['confirm_new_password'] . $salt));

  			if ($new_password == $confirm_new_password)
  			{
  				$req = $bdd->prepare('UPDATE users SET salt = :salt, password = :password WHERE identifiant = "' . $user . '"');
  				$req->execute(array(
  					'salt'     => $salt,
  					'password' => $new_password
  				));
  				$req->closeCursor();

  				$_SESSION['alerts']['password_updated'] = true;
  			}
  			else
  			   $_SESSION['alerts']['wrong_password'] = true;
  		}
  		else
  		  $_SESSION['alerts']['wrong_password'] = true;

  		$reponse->closeCursor();
    }
  }

  // METIER : Mise à jour du statut par l'utilisateur (désinscription, mot de passe)
  // RETOUR : Aucun
  function updateStatus($user, $status)
  {
    global $bdd;

    $reponse = $bdd->prepare('UPDATE users SET status = :status WHERE identifiant = "' . $user . '"');
    $reponse->execute(array(
      'status' => $status
    ));
    $reponse->closeCursor();

    switch ($status)
    {
      case 'D':
        $_SESSION['alerts']['ask_desinscription'] = true;
        break;

      case 'N':
        $_SESSION['alerts']['cancel_status'] = true;
        break;

      default:
        break;
    }
  }

  // METIER : Lecture liste des succès
  // RETOUR : Liste des succès et déblocages
  function getSuccess($identifiant, $listUsers)
  {
    $listeSuccess = array();

    global $bdd;

    // Création tableau de correspondance identifiant / pseudo / avatar
    $tableUsers = array();

    foreach ($listUsers AS $user)
    {
      $tableUsers[$user->getIdentifiant()] = array('pseudo' => htmlspecialchars($user->getPseudo()),
                                                   'avatar' => htmlspecialchars($user->getAvatar())
                                                  );
    }

    // Lecture des données utilisateur
    $reponse = $bdd->query('SELECT * FROM success ORDER BY level ASC, order_success ASC');
    while ($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet Success à partir des données remontées de la bdd
      $success = Success::withData($donnees);

      // Recherche des données utilisateur
      $reponse2 = $bdd->query('SELECT * FROM success_users WHERE reference = "' . $donnees['reference'] . '" AND identifiant = "' . $identifiant . '"');
      $donnees2 = $reponse2->fetch();

      if ($reponse2->rowCount() > 0)
      {
        // Contrôle pour les missions que la date de fin soit passée
        $ended = isMissionEnded($donnees['reference']);

        if ($ended == true)
          $success->setValue_user($donnees2['value']);
      }

      $reponse2->closeCursor();

      // Récupération du classement des utilisateurs
      if ($success->getDefined() == 'Y' AND $success->getUnicity() != 'Y')
        $success->setClassement(getRankUsers($success, $tableUsers));

      array_push($listeSuccess, $success);
    }
    $reponse->closeCursor();

    // Retour
    return $listeSuccess;
  }

  // METIER : Récupération classement des utilisateurs pour un succès
  // RETOUR : Classement
  function getRankUsers($success, $tableUsers)
  {
    // Création tableau des classements
    $listeRangSuccess = array();

    global $bdd;

    if ($success->getDefined() == 'Y' AND $success->getUnicity() != 'Y')
    {
      // Boucle pour parcourir tous les succès débloqués par les utilisateurs
      $reponse = $bdd->query('SELECT * FROM success_users WHERE reference = "' . $success->getReference() . '" ORDER BY value DESC');
      while ($donnees = $reponse->fetch())
      {
        // Contrôle pour les missions que la date de fin soit passée
        $ended = isMissionEnded($success->getReference());

        if ($ended == true)
        {
          // On vérifie que l'utilisateur a débloqué le succès pour l'ajouter
          if ($donnees['value'] >= $success->getLimit_success())
          {
            $rangSuccess = new Classement();

            $rangSuccess->setIdentifiant($donnees['identifiant']);
            $rangSuccess->setPseudo($tableUsers[$donnees['identifiant']]['pseudo']);
            $rangSuccess->setAvatar($tableUsers[$donnees['identifiant']]['avatar']);
            $rangSuccess->setValue($donnees['value']);

            array_push($listeRangSuccess, $rangSuccess);
          }
        }
      }
      $reponse->closeCursor();

      // On filtre le tableau
      if (!empty($listeRangSuccess))
      {
        // Affectation du rang et suppression si rang > 3 (médaille de bronze)
        $prevRank    = $listeRangSuccess[0]->getValue();
        $currentRank = 1;

        foreach ($listeRangSuccess as $key => &$rangSuccessUser)
        {
          $currentTotal = $rangSuccessUser->getValue();

          if ($currentTotal != $prevRank)
          {
            $currentRank += 1;
            $prevRank     = $rangSuccessUser->getValue();
          }

          // Suppression des rangs > 3 sinon on enregistre le rang
          if ($currentRank > 3)
            unset($listeRangSuccess[$key]);
          else
            $rangSuccessUser->setRank($currentRank);
        }

        unset($rangSuccessUser);
      }
    }

    return $listeRangSuccess;
  }

  // METIER : Conversion du tableau d'objet des succès en tableau simple pour JSON
  // RETOUR : Tableau des succès
  function convertForJson($listeSucces)
  {
    // On transforme les objets en tableau pour envoyer au Javascript
    $listeSuccesAConvertir = array();

    foreach ($listeSucces as $succes)
    {
      if ($succes->getDefined() == 'Y' AND $succes->getValue_user() >= $succes->getLimit_success())
      {
        $succesAConvertir = array('id'            => $succes->getId(),
                                  'reference'     => $succes->getReference(),
                                  'title'         => $succes->getTitle(),
                                  'description'   => $succes->getDescription(),
                                  'limit_success' => $succes->getLimit_success(),
                                  'explanation'   => $succes->getExplanation()
                                 );

        $listeSuccesAConvertir[$succes->getId()] = $succesAConvertir;
      }
    }

    return $listeSuccesAConvertir;
  }

  // METIER : Contrôle pour les missions que la date de fin soit passée
  // RETOUR : Booléen
  function isMissionEnded($reference)
  {
    $ended          = false;
    $refMission     = '';
    $dateFinMission = '';

    // Contrôle pour les missions que la date de fin soit passée
    switch ($reference)
    {
      case 'christmas2017':
      case 'christmas2017_2':
        $refMission = 'noel_2017';
        break;

      case 'golden-egg':
      case 'rainbow-egg':
        $refMission = 'paques_2018';
        break;

      case 'wizard':
        $refMission = 'halloween_2018';
        break;

      case 'christmas2018':
      case 'christmas2018_2':
        $refMission = 'noel_2018';
        break;

      case 'christmas2019':
        $refMission = 'noel_2019';
        break;

      default:
        break;
    }

    if (!empty($refMission))
    {
      global $bdd;

      $reponse = $bdd->query('SELECT * FROM missions WHERE reference = "' . $refMission . '"');
      $donnees = $reponse->fetch();
      $dateFinMission = $donnees['date_fin'];
      $reponse->closeCursor();
    }

    if (empty($refMission) OR (!empty($refMission) AND date('Ymd') > $dateFinMission))
      $ended = true;

    return $ended;
  }

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Tableau d'utilisateurs
  function getUsers()
  {
    // Initialisation tableau d'utilisateurs
    $listeUsers = array();

    global $bdd;

    $reponse = $bdd->query('SELECT id, identifiant, ping, status, pseudo, avatar, email, experience FROM users WHERE identifiant != "admin" AND status != "I" ORDER BY identifiant ASC');
    while ($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet User à partir des données remontées de la bdd
      $user = Profile::withData($donnees);

      $user->setLevel(convertExperience($user->getExperience()));

      // On ajoute la ligne au tableau
      array_push($listeUsers, $user);
    }
    $reponse->closeCursor();

    // Tri sur expérience puis identifiant
    if (!empty($listeUsers))
    {
      foreach ($listeUsers as $user)
      {
        $triExp[] = $user->getExperience();
        $triId[]  = $user->getIdentifiant();
      }

      array_multisort($triExp, SORT_DESC, $triId, SORT_ASC, $listeUsers);
    }

    // Retour
    return $listeUsers;
  }

  // METIER : Supprime la préférence utilisateur du thème
  // RETOUR : Aucun
  function deleteTheme($user)
  {
    global $bdd;

    $refTheme = '';

    $reponse = $bdd->prepare('UPDATE preferences SET ref_theme = :ref_theme WHERE identifiant = "' . $user . '"');
    $reponse->execute(array(
      'ref_theme' => $refTheme
    ));
    $reponse->closeCursor();

    $_SESSION['alerts']['theme_deleted'] = true;
  }

  // METIER : Lecture des thèmes existants par type
  // RETOUR : Tableau des thèmes
  function getThemes($type, $experience)
  {
    $listeThemes = array();

    global $bdd;

    // Lecture de la base des thèmes
    if ($type == 'U')
    {
      $niveau  = convertExperience($experience);
      $reponse = $bdd->query('SELECT * FROM themes WHERE type = "' . $type . '" AND level <= ' . $niveau . ' ORDER BY CAST(level AS UNSIGNED) ASC');
    }
    else
      $reponse = $bdd->query('SELECT * FROM themes WHERE type = "' . $type . '" AND date_deb <= ' . date('Ymd') . ' ORDER BY date_deb DESC');

    while ($donnees = $reponse->fetch())
    {
      $theme = Theme::withData($donnees);

      // On ajoute la ligne au tableau
      array_push($listeThemes, $theme);
    }

    $reponse->closeCursor();

    return $listeThemes;
  }

  // METIER : Détermine si on a un thème de mission en cours
  // RETOUR : Booléen
  function getThemeMission()
  {
    $isThemeMission = false;

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM themes WHERE type = "M" AND date_deb <= ' . date('Ymd') . ' AND date_fin >= ' . date('Ymd') . ' ORDER BY id ASC');

    if ($reponse->rowCount() > 0)
      $isThemeMission = true;

    $reponse->closeCursor();

    return $isThemeMission;
  }

  // METIER : Mise à jour de la préférence thème utilisateur
  // RETOUR : Aucun
  function updateTheme($user, $post)
  {
    // Récupération des données
    $idTheme = $post['id_theme'];

    global $bdd;

    // Lecture de la référence du thème
    $reponse = $bdd->query('SELECT * FROM themes WHERE id = ' . $idTheme);
    $donnees = $reponse->fetch();
    $refTheme = $donnees['reference'];
    $reponse->closeCursor();

    // Mise à jour de la préférence utilisateur
    $reponse2 = $bdd->prepare('UPDATE preferences SET ref_theme = :ref_theme WHERE identifiant = "' . $user . '"');
    $reponse2->execute(array(
      'ref_theme' => $refTheme
    ));
    $reponse2->closeCursor();

    $_SESSION['alerts']['theme_updated'] = true;
  }
?>
