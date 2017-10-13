<?php
  include_once('../includes/appel_bdd.php');
  include_once('../includes/classes/profile.php');
  include_once('../includes/classes/success.php');
  include_once('../includes/imagethumb.php');

  // METIER : Lecture des données préférences
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
    global $bdd;

    // Nombre de films ajoutés Movie House
    $reponse0 = $bdd->query('SELECT COUNT(id) AS nb_films_ajoutes FROM movie_house WHERE identifiant_add = "' . $user . '"');
    $donnees0 = $reponse0->fetch();

    $nb_films_ajoutes = $donnees0['nb_films_ajoutes'];

    $reponse0->closeCursor();

    // Nombre de commentaires Movie House
    $reponse1 = $bdd->query('SELECT COUNT(id) AS nb_comments FROM movie_house_comments WHERE author = "' . $user . '"');
    $donnees1 = $reponse1->fetch();

    $nb_comments = $donnees1['nb_comments'];

    $reponse1->closeCursor();

    // Solde des dépenses
    $reponse2 = $bdd->query('SELECT * FROM expense_center ORDER BY id ASC');

    $expenses = 0;

    while($donnees2 = $reponse2->fetch())
    {
      // Prix d'achat
      $prix_achat = $donnees2['price'];

      // Identifiant de l'acheteur
      $acheteur = $donnees2['buyer'];

      // Nombre de parts et prix par parts
      $reponse3 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense = ' . $donnees2['id']);

      $nb_parts_total = 0;
      $nb_parts_user = 0;

      while($donnees3 = $reponse3->fetch())
      {
        // Nombre de parts total
        $nb_parts_total = $nb_parts_total + $donnees3['parts'];

        // Nombre de parts de l'utilisateur
        if ($user == $donnees3['identifiant'])
          $nb_parts_user = $donnees3['parts'];
      }

      if ($nb_parts_total != 0)
        $prix_par_part = $prix_achat / $nb_parts_total;
      else
        $prix_par_part = 0;

      // On fait la somme des dépenses moins les parts consommées pour trouver le bilan
      if ($donnees2['buyer'] == $user)
        $expenses = $expenses + $prix_achat - ($prix_par_part * $nb_parts_user);
      else
        $expenses = $expenses - ($prix_par_part * $nb_parts_user);

      $reponse3->closeCursor();

    }

    $reponse2->closeCursor();

    $expenses = str_replace('.', ',', round($expenses, 2));

    // Nombre de phrases cultes soumises
    $reponse4 = $bdd->query('SELECT COUNT(id) AS nb_collectors FROM collector WHERE author = "' . $user . '"');
    $donnees4 = $reponse4->fetch();

    $nb_collectors = $donnees4['nb_collectors'];

    $reponse4->closeCursor();

    // Nombre d'idées soumises
    $reponse5 = $bdd->query('SELECT COUNT(id) AS nb_idees FROM ideas WHERE author = "' . $user . '"');
    $donnees5 = $reponse5->fetch();

    $nb_ideas = $donnees5['nb_idees'];

    $reponse5->closeCursor();

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
    $_SESSION['pseudo'] = $new_pseudo;

    $_SESSION['pseudo_changed'] = true;
  }

  // METIER : Mise à jour de l'avatar (base + fichier)
  // RETOUR : Aucun
  function changeAvatar($user, $files)
  {
    $_SESSION['avatar_changed'] = false;

    global $bdd;

    // On contrôle la présence du dossier, sinon on le créé
    $dossier = "avatars";

    if (!is_dir($dossier))
       mkdir($dossier);

 		$avatar = rand();

 		// Si on a bien une image
 		if ($files['avatar']['name'] != NULL)
 		{
 			// Dossier de destination
 			$avatar_dir = 'avatars/';

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
 					unlink ("avatars/" . $donnees1['avatar'] . "");

 				$reponse1->closeCursor();

 				// On stocke la référence du nouvel avatar dans la base
 				$reponse2 = $bdd->prepare('UPDATE users SET avatar = :avatar WHERE identifiant = "' . $user . '"');
 				$reponse2->execute(array(
 					'avatar' => $new_name
 				));
 				$reponse2->closeCursor();

 				$_SESSION['avatar_changed'] = true;
 			}
 		}
  }

  // METIER : Supression de l'avatar (base + fichier)
  // RETOUR : Aucun
  function deleteAvatar($user)
  {
    $_SESSION['avatar_deleted'] = false;

    global $bdd;

    // On efface l'ancien avatar si présent
    $reponse1 = $bdd->query('SELECT identifiant, avatar FROM users WHERE identifiant = "' . $user . '"');
    $donnees1 = $reponse1->fetch();

    if (isset($donnees1['avatar']) AND !empty($donnees1['avatar']))
      unlink ("avatars/" . $donnees1['avatar'] . "");

    $reponse1->closeCursor();

    // On efface la référence de l'ancien avatar dans la base
    $new_name = "";

    $reponse2 = $bdd->prepare('UPDATE users SET avatar = :avatar WHERE identifiant = "' . $user . '"');
    $reponse2->execute(array(
      'avatar' => $new_name
    ));
    $reponse2->closeCursor();

    $_SESSION['avatar_deleted'] = true;
  }

  // METIER : Mise à jour des préférences
  // RETOUR : Aucun
  function modifyPreferences($user, $post)
  {
    $_SESSION['preferences_updated'] = false;

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

		// Préférences #THEBOX
		$view_the_box = $post['the_box_view'];

		// Mise à jour de la table des préférences utilisateur
		$reponse = $bdd->prepare('UPDATE preferences SET view_movie_house  = :view_movie_house,
																								     categories_home   = :categories_home,
																								     today_movie_house = :today_movie_house,
																								     view_the_box      = :view_the_box
																					     WHERE identifiant = "' . $user . '"');
		$reponse->execute(array(
			'view_movie_house'  => $view_movie_house,
			'categories_home'   => $categories_home,
			'today_movie_house' => $today_movie_house,
			'view_the_box'      => $view_the_box
		));
		$reponse->closeCursor();

    // Mise à jour des préférences stockées en SESSION
    $_SESSION['view_movie_house']  = $view_movie_house;
    $_SESSION['view_the_box']      = $view_the_box;

		$_SESSION['preferences_updated'] = true;
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

    $_SESSION['mail_updated'] = true;
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
  		$reponse = $bdd->query('SELECT id, identifiant, salt, mot_de_passe FROM users WHERE identifiant = "' . $user . '"');
  		$donnees = $reponse->fetch();

  		$wrong_password = false;

  		$old_password = htmlspecialchars(hash('sha1', $post['old_password'] . $donnees['salt']));

  		if ($old_password == $donnees['mot_de_passe'])
  		{
  			$salt                 = rand();
  			$new_password         = htmlspecialchars(hash('sha1', $post['new_password'] . $salt));
  			$confirm_new_password = htmlspecialchars(hash('sha1', $post['confirm_new_password'] . $salt));

  			if ($new_password == $confirm_new_password)
  			{
  				$req = $bdd->prepare('UPDATE users SET salt = :salt, mot_de_passe = :mot_de_passe WHERE identifiant = "' . $user . '"');
  				$req->execute(array(
  					'salt' => $salt,
  					'mot_de_passe' => $new_password
  				));
  				$req->closeCursor();

  				$wrong_password = false;
  			}
  			else
  			{
  				$wrong_password = true;
  			}
  		}
  		else
  		{
  			$wrong_password = true;
  		}

  		$reponse->closeCursor();

  		$_SESSION['wrong_password'] = $wrong_password;
    }
  }

  // METIER : Mise à jour du top désinscription
  // RETOUR : Aucun
  function askUnsubscribe($user)
  {
    $_SESSION['ask_desinscription'] = false;

    global $bdd;

    $reset = "D";

    $reponse = $bdd->prepare('UPDATE users SET reset = :reset WHERE identifiant = "' . $user . '"');
    $reponse->execute(array(
      'reset' => $reset
    ));
    $reponse->closeCursor();

    $_SESSION['ask_desinscription'] = true;
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
      switch($success->getReference())
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

          $req = $bdd->query('SELECT COUNT(id) AS nb_films_publies FROM movie_house WHERE identifiant_add = "' . $user . '"');
          $data = $req->fetch();

          $nb_films_publies = $data['nb_films_publies'];

          $req->closeCursor();

          $successUser[$success->getId()] = $nb_films_publies;
          break;

        // Cinéphile professionnel
        case "viewer":
          $nb_films_vus = 0;

          $req = $bdd->query('SELECT COUNT(id) AS nb_films_vus FROM movie_house_users WHERE identifiant = "' . $user . '" AND participation = "S"');
          $data = $req->fetch();

          $nb_films_vus = $data['nb_films_vus'];

          $req->closeCursor();

          $successUser[$success->getId()] = $nb_films_vus;
          break;

        // Commentateur sportif
        case "commentator":
          $nb_commentaires_films = 0;

          $req = $bdd->query('SELECT COUNT(id) AS nb_commentaires_films FROM movie_house_comments WHERE author = "' . $user . '"');
          $data = $req->fetch();

          $nb_commentaires_films = $data['nb_commentaires_films'];

          $req->closeCursor();

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

        // Boucle pour parcourir tous les utilisateurs
        $req = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
        while($data = $req->fetch())
        {
          // Récupération succès utilisateur courant
          $successUser = getSuccessUser($listSuccess, $data['identifiant']);

          if (isset($successUser[$success->getId()]))
          {
            if ($successUser[$success->getId()] >= $success->getLimit_success())
            {
              $myRank = array('identifiant' => $data['identifiant'],
                              'pseudo'      => $data['pseudo'],
                              'value'       => $successUser[$success->getId()]
                            );
              array_push($rankSuccess, $myRank);
            }
          }
        }
        $req->closeCursor();

        // Tri tableau sur valeur du succès
        if (!empty($rankSuccess))
        {
          $tri_rank = array();

          foreach ($rankSuccess as $rank)
          {
            $tri_rank[] = $rank['value'];
          }

          array_multisort($tri_rank, SORT_DESC, $rankSuccess);

          // On découpe le tableau pour ne garder que les 3 premiers
          array_slice($rankSuccess, 3);

          $myGlobalRanks = array('id'     => $success->getId(),
                                 'podium' => $rankSuccess
                                );

          array_push($rankUsers, $myGlobalRanks);
        }
      }
    }

    return $rankUsers;
  }
?>
