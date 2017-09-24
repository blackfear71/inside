<?php
  include_once('../includes/appel_bdd.php');
  include_once('../includes/classes/profile.php');
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

    // Nombre de commentaires Movie House
    $reponse = $bdd->query('SELECT COUNT(id) AS nb_comments FROM movie_house_comments WHERE author = "' . $user . '"');
    $donnees = $reponse->fetch();

    $nb_comments = $donnees['nb_comments'];

    $reponse->closeCursor();

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
    $myStats = array('nb_comments'   => $nb_comments,
                     'expenses'      => $expenses,
                     'nb_collectors' => $nb_collectors,
                     'nb_ideas'      => $nb_ideas,
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

 				// Créé une miniature de la source vers la destination en la rognant avec une hauteur/largeur max de 200px (cf fonction imagethumb.php)
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
    $_SESSION['categories_home']   = $categories_home;
    $_SESSION['today_movie_house'] = $today_movie_house;
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
?>
