<?php
  include_once('../includes/functions/appel_bdd.php');
  include_once('../includes/classes/profile.php');
  include_once('../includes/classes/success.php');
  include_once('../includes/libraries/php/imagethumb.php');

  // METIER : Lecture des données profil
  // RETOUR : Objet Profile
  function getProfile($user)
  {
    global $bdd;

    // Lecture des données utilisateur
    $reponse = $bdd->query('SELECT * FROM users WHERE identifiant = "' . $user . '"');
    $donnees = $reponse->fetch();

    // Instanciation d'un objet Profil à partir des données remontées de la bdd
    $profile = Profile::withData($donnees);

    $reponse->closeCursor();

    return $profile;
  }

  // METIER : Lecture des données statistiques profil
  // RETOUR : Objet Statistiques
  function getStatistiques($user)
  {
    $nb_films_ajoutes = 0;
    $nb_comments      = 0;
    $expenses         = 0;
    $nb_collectors    = 0;
    $nb_ideas         = 0;

    global $bdd;

    // Nombre de films ajoutés Movie House
    $reponse = $bdd->query('SELECT COUNT(id) AS nb_films_ajoutes FROM movie_house WHERE identifiant_add = "' . $user . '" AND to_delete != "Y"');
    $donnees = $reponse->fetch();

    $nb_films_ajoutes = $donnees['nb_films_ajoutes'];

    $reponse->closeCursor();

    // Nombre de commentaires Movie House
    $reponse0 = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" ORDER BY id ASC');
    while($donnees0 = $reponse0->fetch())
    {
      $reponse1 = $bdd->query('SELECT * FROM movie_house_comments WHERE id_film = ' . $donnees0['id'] . ' AND author = "' . $user . '"');
      $donnees1 = $reponse1->fetch();

      if ($reponse1->rowCount() > 0)
        $nb_comments++;

      $reponse1->closeCursor();
    }
    $reponse0->closeCursor();

    // Solde des dépenses
    $reponse2 = $bdd->query('SELECT id, identifiant, expenses FROM users WHERE identifiant = "' . $user . '"');
    $donnees2 = $reponse2->fetch();

    $expenses = $donnees2['expenses'];

    $reponse2->closeCursor();

    // Nombre de phrases cultes soumises
    $reponse3 = $bdd->query('SELECT COUNT(id) AS nb_collectors FROM collector WHERE author = "' . $user . '"');
    $donnees3 = $reponse3->fetch();

    $nb_collectors = $donnees3['nb_collectors'];

    $reponse3->closeCursor();

    // Nombre d'idées soumises
    $reponse4 = $bdd->query('SELECT COUNT(id) AS nb_idees FROM ideas WHERE author = "' . $user . '"');
    $donnees4 = $reponse4->fetch();

    $nb_ideas = $donnees4['nb_idees'];

    $reponse4->closeCursor();

    // On construit un tableau avec les données statistiques
    $myStats = array('nb_films_ajoutes' => $nb_films_ajoutes,
                     'nb_comments'      => $nb_comments,
                     'expenses'         => $expenses,
                     'nb_collectors'    => $nb_collectors,
                     'nb_ideas'         => $nb_ideas,
                    );

    // Instanciation d'un objet Statistiques à partir des données remontées de la bdd
    $statistiques = Statistiques::withData($myStats);

    return $statistiques;
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

  // METIER : Mise à jour du pseudo
  // RETOUR : Aucun
  function changePseudo($user, $post)
  {
    $new_pseudo = $post['new_pseudo'];

    global $bdd;

    // Mise à jour du pseudo
    $reponse = $bdd->prepare('UPDATE users SET pseudo = :pseudo WHERE identifiant = "' . $user . '"');
    $reponse->execute(array(
      'pseudo' => $new_pseudo
    ));
    $reponse->closeCursor();

    // Mise à jour du pseudo stocké en SESSION
    $_SESSION['user']['pseudo'] = $new_pseudo;

    $_SESSION['alerts']['pseudo_updated'] = true;
  }

  // METIER : Mise à jour de l'avatar (base + fichier)
  // RETOUR : Aucun
  function changeAvatar($user, $files)
  {
    global $bdd;

    // On contrôle la présence du dossier, sinon on le créé
    $dossier = "../includes/images/profil";

    if (!is_dir($dossier))
      mkdir($dossier);

    // On contrôle la présence du dossier d'avatars, sinon on le créé
    $dossier_avatars = $dossier . "/avatars";

    if (!is_dir($dossier_avatars))
      mkdir($dossier_avatars);

 		$avatar = rand();

 		// Si on a bien une image
 		if ($files['avatar']['name'] != NULL)
 		{
 			// Dossier de destination
 			$avatar_dir = $dossier_avatars . '/';

 			// Données du fichier
 			$file      = $files['avatar']['name'];
 			$tmp_file  = $files['avatar']['tmp_name'];
 			$size_file = $files['avatar']['size'];
      $maxsize   = 8388608; // 8Mo

      // Si le fichier n'est pas trop grand
 			if ($size_file < $maxsize)
 			{
 				// Contrôle fichier temporaire existant
 				if (!is_uploaded_file($tmp_file))
 				{
 					exit("Le fichier est introuvable");
 				}

 				// Contrôle type de fichier
 				$type_file = $files['avatar']['type'];

 				if (!strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp') && !strstr($type_file, 'gif') && !strstr($type_file, 'png'))
 				{
 					exit("Le fichier n'est pas une image valide");
 				}
 				else
 				{
 					$type_image = pathinfo($file, PATHINFO_EXTENSION);
 					$new_name   = $avatar . '.' . $type_image;
 				}

 				// Contrôle upload (si tout est bon, l'image est envoyée)
 				if (!move_uploaded_file($tmp_file, $avatar_dir . $new_name))
 				{
 					exit("Impossible de copier le fichier dans $avatar_dir");
 				}

 				// Créé une miniature de la source vers la destination en la rognant avec une hauteur/largeur max de 400px (cf fonction imagethumb.php)
 				imagethumb($avatar_dir . $new_name, $avatar_dir . $new_name, 400, FALSE, TRUE);

 				// echo "Le fichier a bien été uploadé";

 				// On efface l'ancien avatar si présent
 				$reponse1 = $bdd->query('SELECT identifiant, avatar FROM users WHERE identifiant = "' . $user . '"');
 				$donnees1 = $reponse1->fetch();

 				if (isset($donnees1['avatar']) AND !empty($donnees1['avatar']))
 					unlink ($dossier . "/" . $donnees1['avatar'] . "");

 				$reponse1->closeCursor();

 				// On stocke la référence du nouvel avatar dans la base
 				$reponse2 = $bdd->prepare('UPDATE users SET avatar = :avatar WHERE identifiant = "' . $user . '"');
 				$reponse2->execute(array(
 					'avatar' => $new_name
 				));
 				$reponse2->closeCursor();

        $_SESSION['user']['avatar']           = $new_name;
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
      unlink ("../includes/images/profil/avatars/" . $donnees1['avatar'] . "");

    $reponse1->closeCursor();

    // On efface la référence de l'ancien avatar dans la base
    $new_name = "";

    $reponse2 = $bdd->prepare('UPDATE users SET avatar = :avatar WHERE identifiant = "' . $user . '"');
    $reponse2->execute(array(
      'avatar' => $new_name
    ));
    $reponse2->closeCursor();

    $_SESSION['user']['avatar']           = '';
    $_SESSION['alerts']['avatar_deleted'] = true;
  }

  // METIER : Mise à jour des préférences
  // RETOUR : Aucun
  function updatePreferences($user, $post)
  {
    $error                           = false;

    global $bdd;

		// Préférences MOVIE HOUSE
		$view_movie_house = $post['movie_house_view'];

		$categories_home = "";

		if (isset($post['films_waited']))
			$categories_home .= "Y";
		else
			$categories_home .= "N";

		if (isset($post['films_way_out']))
			$categories_home .= "Y";
		else
			$categories_home .= "N";

		if (isset($post['affiche_date']))
			$today_movie_house = "Y";
		else
			$today_movie_house = "N";

    if ($post['old_movies_view'] == "T")
      $view_old_movies = "T;;;";
    else
    {
      if (!is_numeric($post['duration']) OR !ctype_digit($post['duration']) OR $post['duration'] <= 0)
      {
        $_SESSION['alerts']['duration_not_correct'] = true;
        $error                                      = true;
      }
      else
      {
        switch ($post['type_duration'])
        {
          case "J":
            if ($post['duration'] > 365)
            {
              $_SESSION['alerts']['duration_too_long'] = true;
              $error                                   = true;
            }
            break;

          case "S":
            if ($post['duration'] > 52)
            {
              $_SESSION['alerts']['duration_too_long'] = true;
              $error                                   = true;
            }
            break;

          case "M":
            if ($post['duration'] > 12)
            {
              $_SESSION['alerts']['duration_too_long'] = true;
              $error                                   = true;
            }
            break;

          default:
            break;
        }
        $view_old_movies = $post['old_movies_view'] . ";" . $post['type_duration'] . ";" . $post['duration'] . ";";
      }
    }

		// Préférences #THEBOX
		$view_the_box = $post['the_box_view'];

    // Préférences Notifications
    $view_notifications = $post['notifications_view'];

    if ($error == false)
    {
      // Mise à jour de la table des préférences utilisateur
      $reponse = $bdd->prepare('UPDATE preferences SET view_movie_house   = :view_movie_house,
                                                       categories_home    = :categories_home,
                                                       today_movie_house  = :today_movie_house,
                                                       view_old_movies    = :view_old_movies,
                                                       view_the_box       = :view_the_box,
                                                       view_notifications = :view_notifications
                                                 WHERE identifiant = "' . $user . '"');
      $reponse->execute(array(
        'view_movie_house'   => $view_movie_house,
        'categories_home'    => $categories_home,
        'today_movie_house'  => $today_movie_house,
        'view_old_movies'    => $view_old_movies,
        'view_the_box'       => $view_the_box,
        'view_notifications' => $view_notifications
      ));
      $reponse->closeCursor();

      // Mise à jour des préférences stockées en SESSION
      $_SESSION['user']['view_movie_house']   = $view_movie_house;
      $_SESSION['user']['view_the_box']       = $view_the_box;
      $_SESSION['user']['view_notifications'] = $view_notifications;

      $_SESSION['alerts']['preferences_updated'] = true;
    }
  }

  // METIER : Modification adresse mail
  // RETOUR : Aucun
  function updateMail($user, $post)
  {
    if (isset($post['suppression_mail']))
      $mail = "";
    else
      $mail = $post['mail'];

    global $bdd;

    // Mise à jour de l'adresse mail utilisateur
		$reponse = $bdd->prepare('UPDATE users SET email  = :email WHERE identifiant = "' . $user . '"');
		$reponse->execute(array(
			'email'  => $mail
		));
		$reponse->closeCursor();

    $_SESSION['alerts']['mail_updated'] = true;
  }

  // METIER : Mise à jour du mot de passe
  // RETOUR : Aucun
  function changeMdp($user, $post)
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
  function changeStatus($user, $status)
  {
    global $bdd;

    $reponse = $bdd->prepare('UPDATE users SET status = :status WHERE identifiant = "' . $user . '"');
    $reponse->execute(array(
      'status' => $status
    ));
    $reponse->closeCursor();

    switch ($status)
    {
      case "D":
        $_SESSION['alerts']['ask_desinscription'] = true;
        break;

      case "N":
        $_SESSION['alerts']['cancel_status'] = true;
        break;

      default:
        break;
    }
  }

  // METIER : Lecture liste des succès
  // RETOUR : Liste des succès
  function getSuccess()
  {
    $listSuccess = array();

    global $bdd;

    // Lecture des données utilisateur
    $reponse = $bdd->query('SELECT * FROM success');
    while($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet Success à partir des données remontées de la bdd
      $mySuccess = Success::withData($donnees);
      array_push($listSuccess, $mySuccess);
    }
    $reponse->closeCursor();

    // Tri sur niveau puis ordonnancement
    foreach ($listSuccess as $success)
    {
      $tri_level[] = $success->getLevel();
      $tri_order[] = $success->getOrder_success();
    }
    array_multisort($tri_level, SORT_ASC, $tri_order, SORT_ASC, $listSuccess);

    return $listSuccess;
  }

  // METIER : Succès de l'utilisateur courant
  // RETOUR : Succès utilisateur
  function getSuccessUser($listSuccess, $user)
  {
    $successUser = array();

    global $bdd;

    // Recherche des données
    foreach ($listSuccess as $success)
    {
      switch ($success->getReference())
      {
        // J'étais là
        case "beginning":
          $true_insider = 0;

          $req = $bdd->query('SELECT id, identifiant, beginner FROM users WHERE identifiant = "' . $user . '"');
          $data = $req->fetch();

          $true_insider = $data['beginner'];

          $req->closeCursor();

          $successUser[$success->getId()] = $true_insider;
          break;

        // Je l'ai fait !
        case "developper":
          $developper = 0;

          $req = $bdd->query('SELECT id, identifiant, developper FROM users WHERE identifiant = "' . $user . '"');
          $data = $req->fetch();

          $developper = $data['developper'];

          $req->closeCursor();

          $successUser[$success->getId()] = $developper;
          break;

        // Cinéphile amateur
        case "publisher":
          $nb_films_publies = 0;

          $req = $bdd->query('SELECT COUNT(id) AS nb_films_publies FROM movie_house WHERE identifiant_add = "' . $user . '" AND to_delete != "Y"');
          $data = $req->fetch();

          $nb_films_publies = $data['nb_films_publies'];

          $req->closeCursor();

          $successUser[$success->getId()] = $nb_films_publies;
          break;

        // Cinéphile professionnel
        case "viewer":
          $nb_films_vus = 0;

          $req1 = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" ORDER BY id ASC');
          while($data1 = $req1->fetch())
          {
            $req2 = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = ' . $data1['id'] . ' AND identifiant = "' . $user . '" AND participation = "S"');
            $data2 = $req2->fetch();

            if ($req2->rowCount() > 0)
              $nb_films_vus++;

            $req2->closeCursor();
          }
          $req1->closeCursor();

          $successUser[$success->getId()] = $nb_films_vus;
          break;

        // Commentateur sportif
        case "commentator":
          $nb_commentaires_films = 0;

          $req1 = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" ORDER BY id ASC');
          while($data1 = $req1->fetch())
          {
            $req2 = $bdd->query('SELECT * FROM movie_house_comments WHERE id_film = ' . $data1['id'] . ' AND author = "' . $user . '"');
            $data2 = $req2->fetch();

            if ($req2->rowCount() > 0)
              $nb_commentaires_films++;

            $req2->closeCursor();
          }
          $req1->closeCursor();

          $successUser[$success->getId()] = $nb_commentaires_films;
          break;

        // Expert acoustique
        case "listener":
          $nb_collector_publiees = 0;

          $req = $bdd->query('SELECT COUNT(id) AS nb_collector_publiees FROM collector WHERE author = "' . $user . '"');
          $data = $req->fetch();

          $nb_collector_publiees = $data['nb_collector_publiees'];

          $req->closeCursor();

          $successUser[$success->getId()] = $nb_collector_publiees;
          break;

        // Dommage collatéral
        case "speaker":
          $nb_collector_speaker = 0;

          $req = $bdd->query('SELECT COUNT(id) AS nb_collector_speaker FROM collector WHERE speaker = "' . $user . '"');
          $data = $req->fetch();

          $nb_collector_speaker = $data['nb_collector_speaker'];

          $req->closeCursor();

          $successUser[$success->getId()] = $nb_collector_speaker;
          break;

        // Rigolo compulsif
        case "funny":
          $nb_collector_user = 0;

          $req = $bdd->query('SELECT COUNT(id) AS nb_collector_user FROM collector_users WHERE identifiant = "' . $user . '"');
          $data = $req->fetch();

          $nb_collector_user = $data['nb_collector_user'];

          $req->closeCursor();

          $successUser[$success->getId()] = $nb_collector_user;
          break;

        // Désigné volontaire
        case "buyer":
          $nb_buyer = 0;

          $req = $bdd->query('SELECT COUNT(id) AS nb_buyer FROM expense_center WHERE buyer = "' . $user . '" AND price > 0');
          $data = $req->fetch();

          $nb_buyer = $data['nb_buyer'];

          $req->closeCursor();

          $successUser[$success->getId()] = $nb_buyer;
          break;

        // Profiteur occasionnel
        case "eater":
          $nb_parts = 0;

          $req = $bdd->query('SELECT * FROM expense_center_users WHERE identifiant = "' . $user . '"');
          while($data = $req->fetch())
          {
            $nb_parts += $data['parts'];
          }
          $req->closeCursor();

          $successUser[$success->getId()] = $nb_parts;
          break;

        // Génie créatif
        case "creator":
          $nb_idees_publiees = 0;

          $req = $bdd->query('SELECT COUNT(id) AS nb_idees_publiees FROM ideas WHERE author = "' . $user . '"');
          $data = $req->fetch();

          $nb_idees_publiees = $data['nb_idees_publiees'];

          $req->closeCursor();

          $successUser[$success->getId()] = $nb_idees_publiees;
          break;

        // Top développeur
        case "applier":
          $nb_idees_resolues = 0;

          $req = $bdd->query('SELECT COUNT(id) AS nb_idees_resolues FROM ideas WHERE developper = "' . $user . '" AND status = "D"');
          $data = $req->fetch();

          $nb_idees_resolues = $data['nb_idees_resolues'];

          $req->closeCursor();

          $successUser[$success->getId()] = $nb_idees_resolues;
          break;

        // Débugger aguerri
        case "debugger":
          $nb_bugs_publies = 0;

          $req = $bdd->query('SELECT COUNT(id) AS nb_bugs_publies FROM bugs WHERE author = "' . $user . '"');
          $data = $req->fetch();

          $nb_bugs_publies = $data['nb_bugs_publies'];

          $req->closeCursor();

          $successUser[$success->getId()] = $nb_bugs_publies;
          break;

        // Compilateur intégré
        case "compiler":
          $nb_bugs_resolus = 0;

          $req = $bdd->query('SELECT COUNT(id) AS nb_bugs_resolus FROM bugs WHERE author = "' . $user . '" AND resolved = "Y"');
          $data = $req->fetch();

          $nb_bugs_resolus = $data['nb_bugs_resolus'];

          $req->closeCursor();

          $successUser[$success->getId()] = $nb_bugs_resolus;
          break;

        // Mer il et fou !
        case "generous":
          $nb_expense_no_parts = 0;

          $req1 = $bdd->query('SELECT * FROM expense_center WHERE buyer = "' . $user . '" AND price > 0');
          while($data1 = $req1->fetch())
          {
            $no_parts = true;

            $req2 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense = ' . $data1['id']);
            while($data2 = $req2->fetch())
            {
              if ($data2['identifiant'] == $user)
              {
                $no_parts = false;
                break;
              }
            }
            $req2->closeCursor();

            if ($no_parts == true)
              $nb_expense_no_parts++;
          }
          $req1->closeCursor();

          $successUser[$success->getId()] = $nb_expense_no_parts;
          break;

        // Auto-satisfait
        case "self-satisfied":
          $nb_auto_voted = 0;

          $req1 = $bdd->query('SELECT * FROM collector WHERE speaker = "' . $user . '"');
          while($data1 = $req1->fetch())
          {
            $req2 = $bdd->query('SELECT * FROM collector_users WHERE id_collector = ' . $data1['id']);
            while($data2 = $req2->fetch())
            {
              if ($data2['identifiant'] == $user)
              {
                $nb_auto_voted++;
                break;
              }
            }
            $req2->closeCursor();
          }
          $req1->closeCursor();

          $successUser[$success->getId()] = $nb_auto_voted;
          break;

        // Véritable Jedi
        case "padawan":
          $star_wars_8 = 0;

          // Date de sortie du film
          $req1 = $bdd->query('SELECT id, date_theater FROM movie_house WHERE id = 16');
          $data1 = $req1->fetch();

          $date_sw8 = $data1['date_theater'];

          $req1->closeCursor();

          // Participation utilisateur
          $req2 = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = 16 AND identifiant = "' . $user . '" AND participation = "S"');
          $data2 = $req2->fetch();

          if ($req2->rowCount() > 0 AND date("Ymd") >= $date_sw8)
            $star_wars_8 = 1;

          $req2->closeCursor();

          $successUser[$success->getId()] = $star_wars_8;
          break;

        // Economie de marché
        case "greedy":
          $bilan = 0;

          // Bilan des dépenses
          $req = $bdd->query('SELECT id, identifiant, expenses FROM users WHERE identifiant = "' . $user . '"');
          $data = $req->fetch();

          $bilan = $data['expenses'];

          $req->closeCursor();

          $successUser[$success->getId()] = $bilan;
          break;

        // Lutin de Noël
        case "christmas2017":
        // Je suis ton Père Noël !
        case "christmas2017_2":
        // Un coeur en or
        case "golden-egg":
        // Mettre tous ses oeufs dans le même panier
        case "rainbow-egg":
        // Apprenti sorcier
        case "wizard":
          $mission = 0;

          if ($success->getReference() == "christmas2017" OR $success->getReference() == "christmas2017_2")
            $reference = "noel_2017";
          elseif ($success->getReference() == "golden-egg" OR $success->getReference() == "rainbow-egg")
            $reference = "paques_2018";
          elseif ($success->getReference() == "wizard" OR $success->getReference() == "wizard")
            $reference = "halloween_2018";

          // Récupération Id mission
          $req = $bdd->query('SELECT * FROM missions WHERE reference = "' . $reference . '"');
          $data = $req->fetch();

          $id_mission = $data['id'];
          $date_fin   = $data['date_fin'];

          $req->closeCursor();

          if (date('Ymd') > $date_fin)
          {
            // Nombre total d'objectifs sur la mission
            $req2 = $bdd->query('SELECT * FROM missions_users WHERE id_mission = ' . $id_mission . ' AND identifiant = "' . $user . '"');
            while($data2 = $req2->fetch())
            {
              $mission += $data2['avancement'];
            }
            $req2->closeCursor();
          }

          $successUser[$success->getId()] = $mission;
        break;

        default:
          break;
      }
    }

    // Tri des succès
    ksort($successUser);

    return $successUser;
  }

  // METIER : Classement des succès des utilisateurs
  // RETOUR : Tableau des classement
  function getRankUsers($listSuccess)
  {
    $rankUsers = array();

    global $bdd;

    // Boucle pour parcourir tous les succès
    foreach ($listSuccess as $success)
    {
      if ($success->getLimit_success() > 1)
      {
        $rankSuccess = array();

        $liste_succes_unique = array();
        array_push($liste_succes_unique, $success);

        // Boucle pour parcourir tous les utilisateurs
        $req = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
        while($data = $req->fetch())
        {
          // Récupération succès utilisateur courant
          $successUser = getSuccessUser($liste_succes_unique, $data['identifiant']);

          if (isset($successUser[$success->getId()]))
          {
            if ($successUser[$success->getId()] >= $success->getLimit_success())
            {
              $myRank = array('identifiant' => $data['identifiant'],
                              'pseudo'      => $data['pseudo'],
                              'value'       => $successUser[$success->getId()],
                              'rank'        => 0
                            );
              array_push($rankSuccess, $myRank);
            }
          }
        }
        $req->closeCursor();

        // Tri tableau sur valeur du succès
        if (!empty($rankSuccess))
        {
          // Tri podium
          $tri_rank = array();

          foreach ($rankSuccess as $rank)
          {
            $tri_rank[] = $rank['value'];
          }

          array_multisort($tri_rank, SORT_DESC, $rankSuccess);

          // Affectation du rang
          $prevRank    = $rankSuccess[0]['value'];
          $currentRank = 1;

          foreach ($rankSuccess as $key => &$rankSuccessUser)
          {
          	$currentTotal = $rankSuccessUser['value'];

          	if ($currentTotal != $prevRank)
          	{
          	  $currentRank += 1;
          	  $prevRank = $rankSuccessUser['value'];
          	}

            // Suppression des rangs > 3 sinon on enregistre le rang
            if ($currentRank > 3)
              unset($rankSuccess[$key]);
            else
          	 $rankSuccessUser['rank'] = $currentRank;
          }

          unset($rankSuccessUser);

          $myGlobalRanks = array('id'             => $success->getId(),
                                 'level'          => $success->getLevel(),
                                 'order_success'  => $success->getOrder_success(),
                                 'podium'         => $rankSuccess
                                );

          array_push($rankUsers, $myGlobalRanks);
        }
      }
    }

    return $rankUsers;
  }
?>
