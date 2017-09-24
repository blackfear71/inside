<?php
  include_once('includes/appel_bdd.php');

  // METIER : Connexion utilisateur
  // RETOUR : Indicateur connexion
  function connectUser($post)
  {
    $connected = false;

    if ($post['login'] == "admin")
      $login = htmlspecialchars($post['login']);
    else
      $login = htmlspecialchars(strtoupper($post['login']));

    global $bdd;

    // lecture par requête de la BDD
  	$reponse = $bdd->query('SELECT * FROM users');
  	while ($donnees = $reponse->fetch())
  	{
  		$_SESSION['connected'] = NULL;

  		if (isset($login) AND $login == $donnees['identifiant']) // 2 boucles if pour comparer pseudo et MDP
  		{
  			if ($donnees['reset'] == "I")
  			{
  				$_SESSION['not_yet']   = true;
  				$_SESSION['connected'] = false;
  				$_SESSION['wrong_connexion']     = false;
  				break;
  			}
  			else
  			{
  				$mdp = htmlspecialchars(hash('sha1', $post['mdp'] . $donnees['salt'])); // On crypte de la même façon qu'à l'identification pour comparer, avec un grain de sel
  				if (isset($mdp) AND $mdp == $donnees['mot_de_passe'])
  				{
  					// Sauvegarde des données utilisateur en SESSION
  					$_SESSION['connected']       = true;
  					$_SESSION['identifiant']     = $donnees['identifiant'];
  					$_SESSION['pseudo']          = $donnees['pseudo'];
  					$_SESSION['wrong_connexion'] = false;

  					// Recherche et sauvegarde des preferences utilisateur en SESSION
  					if ($_SESSION['identifiant'] != "admin")
  					{
  						$reponse2 = $bdd->query('SELECT * FROM preferences WHERE identifiant = "' . $_SESSION['identifiant'] . '"');
  						$donnees2 = $reponse2->fetch();

  						$_SESSION['view_movie_house']  = $donnees2['view_movie_house'];
  						$_SESSION['view_the_box']      = $donnees2['view_the_box'];

  						$reponse2->closeCursor();
  					}

            $connected = true;
  					break; // Important sinon la boucle continue et la variable connected passera forcément sur false alors qu'elle doit rester true !
  				}
          // Sinon, on affiche un message d'erreur
  				else
  				{
  					$_SESSION['connected']       = false;
  					$_SESSION['wrong_connexion'] = true;
  					break;
  				}

  				$_SESSION['not_yet'] = false;
  			}
  		}
  		else
  		{
  			$_SESSION['connected']       = false;
  			$_SESSION['wrong_connexion'] = true;
  		}
  	}

  	$reponse->closeCursor();

    return $connected;
  }

  // METIER : Inscription utilisateur
  // RETOUR : Aucun
  function subscribe($post)
  {
    // Sauvegarde en session en cas d'erreur
    $_SESSION['identifiant_saisi']               = $post['trigramme'];
    $_SESSION['pseudo_saisi']                    = $post['pseudo'];
    $_SESSION['mot_de_passe_saisi']              = $post['password'];
    $_SESSION['confirmation_mot_de_passe_saisi'] = $post['confirm_password'];

    // Récupération des champs saisis et initialisations utilisateur
    $trigramme        = htmlspecialchars(strtoupper($post['trigramme']));
    $pseudo           = htmlspecialchars($post['pseudo']);
    $salt             = rand();
    $password         = htmlspecialchars(hash('sha1', $post['password'] . $salt));
    $confirm_password = htmlspecialchars(hash('sha1', $post['confirm_password'] . $salt));
    $reset            = "I";
    $avatar           = "";
    $email            = "";

    // Initialisations préférences
    $view_movie_house  = "H";
    $categories_home   = "NN";
    $today_movie_house = "N";
    $view_the_box      = "P";
    $manage_calendars  = "N";

    global $bdd;

    // Contrôle trigramme déjà pris
    $reponse = $bdd->query('SELECT id, identifiant FROM users');
    while ($donnees = $reponse->fetch())
    {
      if ($donnees['identifiant'] == $trigramme)
      {
        $_SESSION['already_exist'] = true;
        break;
      }
      else
        $_SESSION['already_exist'] = false;
    }
    $reponse->closeCursor();

    // Contrôle confirmation mot de passe
    if ($_SESSION['already_exist'] == false)
    {
      if ($password == $confirm_password)
      {
        // On créé l'utilisateur
        $req = $bdd->prepare('INSERT INTO users(identifiant, salt, mot_de_passe, reset, pseudo, avatar, email) VALUES(:identifiant, :salt, :mot_de_passe, :reset, :pseudo, :avatar, :email)');
				$req->execute(array(
					'identifiant'  => $trigramme,
          'salt'         => $salt,
          'mot_de_passe' => $password,
          'reset'        => $reset,
					'pseudo'       => $pseudo,
					'avatar'       => $avatar,
          'email'        => $email
					));
				$req->closeCursor();

        // On créé les préférences
        $req = $bdd->prepare('INSERT INTO preferences(identifiant, view_movie_house, categories_home, today_movie_house, view_the_box, manage_calendars) VALUES(:identifiant, :view_movie_house, :categories_home, :today_movie_house, :view_the_box, :manage_calendars)');
        $req->execute(array(
          'identifiant'       => $trigramme,
          'view_movie_house'  => $view_movie_house,
          'categories_home'   => $categories_home,
          'today_movie_house' => $today_movie_house,
          'view_the_box'      => $view_the_box,
          'manage_calendars'  => $manage_calendars
          ));
        $req->closeCursor();

        $_SESSION['ask_inscription'] = true;
        $_SESSION['wrong_confirm']   = false;
      }
      else
      {
        $_SESSION['ask_inscription'] = false;
        $_SESSION['wrong_confirm']   = true;
      }
    }
  }

  // METIER : Demande récupération mot de passe
  // RETOUR : Aucun
  function resetPassword($post)
  {
    // Sauvegarde en session en cas d'erreur
    $_SESSION['identifiant_saisi_mdp'] = $post['login'];

    // Récupération des champs saisis et initialisations utilisateur
		$identifiant = htmlspecialchars(strtoupper($post['login']));
		$reset       = "N";

    // Initialisation erreurs
		$_SESSION['wrong_id']      = false;
		$_SESSION['asked']         = false;
		$_SESSION['already_asked'] = false;
    $_SESSION['not_yet']       = false;

    global $bdd;

		// On vérifie que l'identifiant existe bien
		$reponse = $bdd->query('SELECT id, identifiant, reset FROM users');
		while ($donnees = $reponse->fetch())
		{
			if ($identifiant == $donnees['identifiant'])
			{
				if ($donnees['reset'] == "Y")
				{
					$_SESSION['wrong_id']      = false;
					$_SESSION['asked']         = false;
					$_SESSION['already_asked'] = true;
					break;
				}
        elseif ($donnees['reset'] == "I")
        {
          $_SESSION['wrong_id']      = false;
          $_SESSION['asked']         = false;
          $_SESSION['already_asked'] = false;
          $_SESSION['not_yet']       = true;
          break;
        }
				else
				{
					// Mise à jour de la table
					$reset = "Y";

					$req = $bdd->prepare('UPDATE users SET reset = :reset WHERE id = ' . $donnees['id']);
					$req->execute(array(
						'reset' => $reset
					));
					$req->closeCursor();

					$_SESSION['wrong_id']      = false;
					$_SESSION['asked']         = true;
					$_SESSION['already_asked'] = false;
					break;
				}
			}
			else
			{
				$_SESSION['wrong_id']      = true;
				$_SESSION['asked']         = false;
				$_SESSION['already_asked'] = false;
			}
		}
		$reponse->closeCursor();
  }
?>
