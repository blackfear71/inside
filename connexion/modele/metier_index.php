<?php
  include_once('includes/appel_bdd.php');
  include_once('includes/classes/missions.php');

  // METIER : Connexion utilisateur
  // RETOUR : Indicateur connexion
  function connectUser($post)
  {
    $connected = false;

    if (strtolower($post['login']) == "admin")
      $login = htmlspecialchars(strtolower($post['login']));
    else
      $login = htmlspecialchars(strtoupper($post['login']));

    global $bdd;

    $_SESSION['index']['connected'] = NULL;

    // lecture par requête de la BDD
  	$reponse = $bdd->query('SELECT * FROM users WHERE identifiant = "' . $login . '"');
  	$donnees = $reponse->fetch();

		if ($reponse->rowCount() > 0 AND isset($login) AND $login == $donnees['identifiant']) // 2 boucles if pour comparer pseudo et MDP
		{
			if ($donnees['status'] == "I")
			{
				$_SESSION['alerts']['not_yet']         = true;
				$_SESSION['index']['connected']        = false;
				$_SESSION['alerts']['wrong_connexion'] = false;
			}
			else
			{
				$mdp = htmlspecialchars(hash('sha1', $post['mdp'] . $donnees['salt'])); // On crypte de la même façon qu'à l'identification pour comparer, avec un grain de sel
				if (isset($mdp) AND $mdp == $donnees['password'])
				{
					// Sauvegarde des données utilisateur en SESSION
					$_SESSION['index']['connected']        = true;
					$_SESSION['user']['identifiant']       = $donnees['identifiant'];
          $_SESSION['user']['pseudo']            = $donnees['pseudo'];
					$_SESSION['user']['avatar']            = $donnees['avatar'];
					$_SESSION['alerts']['wrong_connexion'] = false;

					// Recherche et sauvegarde des preferences utilisateur en SESSION
					if ($_SESSION['user']['identifiant'] != "admin")
					{
						$reponse2 = $bdd->query('SELECT * FROM preferences WHERE identifiant = "' . $_SESSION['user']['identifiant'] . '"');
						$donnees2 = $reponse2->fetch();

						$_SESSION['user']['view_movie_house']   = $donnees2['view_movie_house'];
            $_SESSION['user']['view_the_box']       = $donnees2['view_the_box'];
						$_SESSION['user']['view_notifications'] = $donnees2['view_notifications'];

						$reponse2->closeCursor();
					}

          $connected = true;
				}
        // Sinon, on affiche un message d'erreur
				else
				{
					$_SESSION['index']['connected']        = false;
					$_SESSION['alerts']['wrong_connexion'] = true;
				}

				$_SESSION['alerts']['not_yet'] = false;
			}
		}
		else
		{
			$_SESSION['index']['connected']        = false;
			$_SESSION['alerts']['wrong_connexion'] = true;
		}

  	$reponse->closeCursor();

    return $connected;
  }

  // METIER : Inscription utilisateur
  // RETOUR : Aucun
  function subscribe($post)
  {
    // Sauvegarde en session en cas d'erreur
    $_SESSION['index']['identifiant_saisi']               = $post['trigramme'];
    $_SESSION['index']['pseudo_saisi']                    = $post['pseudo'];
    $_SESSION['index']['mot_de_passe_saisi']              = $post['password'];
    $_SESSION['index']['confirmation_mot_de_passe_saisi'] = $post['confirm_password'];

    // Récupération des champs saisis et initialisations utilisateur
    $trigramme        = htmlspecialchars(strtoupper($post['trigramme']));
    $pseudo           = htmlspecialchars($post['pseudo']);
    $salt             = rand();
    $password         = htmlspecialchars(hash('sha1', $post['password'] . $salt));
    $confirm_password = htmlspecialchars(hash('sha1', $post['confirm_password'] . $salt));
    $ping             = "";
    $status           = "I";
    $avatar           = "";
    $email            = "";
    $beginner         = 0;
    $developper       = 0;
    $expenses         = 0;

    // Initialisations préférences
    $ref_theme          = "";
    $view_movie_house   = "H";
    $categories_home    = "YY";
    $today_movie_house  = "N";
    $view_old_movies    = "T;;;";
    $view_the_box       = "P";
    $view_notifications = "T";
    $manage_calendars   = "N";

    global $bdd;

    // Contrôle trigramme sur 3 caractères
    if (strlen($trigramme) == 3)
    {
      // Contrôle trigramme déjà pris
      $reponse = $bdd->query('SELECT id, identifiant FROM users');
      while ($donnees = $reponse->fetch())
      {
        if ($donnees['identifiant'] == $trigramme)
        {
          $_SESSION['alerts']['already_exist'] = true;
          break;
        }
        else
          $_SESSION['alerts']['already_exist'] = false;
      }
      $reponse->closeCursor();

      // Contrôle confirmation mot de passe
      if ($_SESSION['alerts']['already_exist'] == false)
      {
        if ($password == $confirm_password)
        {
          // On créé l'utilisateur
          $req = $bdd->prepare('INSERT INTO users(identifiant,
                                                  salt,
                                                  password,
                                                  ping,
                                                  status,
                                                  pseudo,
                                                  avatar,
                                                  email,
                                                  beginner,
                                                  developper,
                                                  expenses)
                                           VALUES(:identifiant,
                                                  :salt,
                                                  :password,
                                                  :ping,
                                                  :status,
                                                  :pseudo,
                                                  :avatar,
                                                  :email,
                                                  :beginner,
                                                  :developper,
                                                  :expenses
                                                 )');
  				$req->execute(array(
  					'identifiant' => $trigramme,
            'salt'        => $salt,
            'password'    => $password,
            'ping'        => $ping,
            'status'      => $status,
  					'pseudo'      => $pseudo,
  					'avatar'      => $avatar,
            'email'       => $email,
            'beginner'    => $beginner,
            'developper'  => $developper,
            'expenses'    => $expenses
  					));
  				$req->closeCursor();

          // On créé les préférences
          $req = $bdd->prepare('INSERT INTO preferences(identifiant,
                                                        ref_theme,
                                                        view_movie_house,
                                                        categories_home,
                                                        today_movie_house,
                                                        view_old_movies,
                                                        view_the_box,
                                                        view_notifications,
                                                        manage_calendars)
                                                 VALUES(:identifiant,
                                                        :ref_theme,
                                                        :view_movie_house,
                                                        :categories_home,
                                                        :today_movie_house,
                                                        :view_old_movies,
                                                        :view_the_box,
                                                        :view_notifications,
                                                        :manage_calendars)');
          $req->execute(array(
            'identifiant'        => $trigramme,
            'ref_theme'          => $ref_theme,
            'view_movie_house'   => $view_movie_house,
            'categories_home'    => $categories_home,
            'today_movie_house'  => $today_movie_house,
            'view_old_movies'    => $view_old_movies,
            'view_the_box'       => $view_the_box,
            'view_notifications' => $view_notifications,
            'manage_calendars'   => $manage_calendars
            ));
          $req->closeCursor();

          $_SESSION['alerts']['ask_inscription'] = true;
          $_SESSION['alerts']['wrong_confirm']   = false;
        }
        else
        {
          $_SESSION['alerts']['ask_inscription'] = false;
          $_SESSION['alerts']['wrong_confirm']   = true;
        }
      }
    }
    else
      $_SESSION['alerts']['too_short'] = true;

  }

  // METIER : Demande récupération mot de passe
  // RETOUR : Aucun
  function resetPassword($post)
  {
    // Sauvegarde en session en cas d'erreur
    $_SESSION['index']['identifiant_saisi_mdp'] = $post['login'];

    // Récupération des champs saisis et initialisations utilisateur
		$identifiant = htmlspecialchars(strtoupper($post['login']));
		$status      = "N";

    // Initialisation erreurs
		$_SESSION['alerts']['wrong_id']      = false;
		$_SESSION['alerts']['asked']         = false;
		$_SESSION['alerts']['already_asked'] = false;
    $_SESSION['alerts']['not_yet']       = false;

    global $bdd;

		// On vérifie que l'identifiant existe bien
		$reponse = $bdd->query('SELECT id, identifiant, status FROM users');
		while ($donnees = $reponse->fetch())
		{
			if ($identifiant == $donnees['identifiant'])
			{
				if ($donnees['status'] == "Y")
				{
					$_SESSION['alerts']['wrong_id']      = false;
					$_SESSION['alerts']['asked']         = false;
					$_SESSION['alerts']['already_asked'] = true;
					break;
				}
        elseif ($donnees['status'] == "I")
        {
          $_SESSION['alerts']['wrong_id']      = false;
          $_SESSION['alerts']['asked']         = false;
          $_SESSION['alerts']['already_asked'] = false;
          $_SESSION['alerts']['not_yet']       = true;
          break;
        }
				else
				{
					// Mise à jour de la table
					$status = "Y";

					$req = $bdd->prepare('UPDATE users SET status = :status WHERE id = ' . $donnees['id']);
					$req->execute(array(
						'status' => $status
					));
					$req->closeCursor();

					$_SESSION['alerts']['wrong_id']      = false;
					$_SESSION['alerts']['asked']         = true;
					$_SESSION['alerts']['already_asked'] = false;
					break;
				}
			}
			else
			{
				$_SESSION['alerts']['wrong_id']      = true;
				$_SESSION['alerts']['asked']         = false;
				$_SESSION['alerts']['already_asked'] = false;
			}
		}
		$reponse->closeCursor();
  }
?>
