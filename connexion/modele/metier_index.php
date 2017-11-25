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

    $_SESSION['connected'] = NULL;

    // lecture par requête de la BDD
  	$reponse = $bdd->query('SELECT * FROM users WHERE identifiant = "' . $login . '"');
  	$donnees = $reponse->fetch();

		if ($reponse->rowCount() > 0 AND isset($login) AND $login == $donnees['identifiant']) // 2 boucles if pour comparer pseudo et MDP
		{
			if ($donnees['reset'] == "I")
			{
				$_SESSION['not_yet']         = true;
				$_SESSION['connected']       = false;
				$_SESSION['wrong_connexion'] = false;
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
					$_SESSION['avatar']          = $donnees['avatar'];
					$_SESSION['wrong_connexion'] = false;

					// Recherche et sauvegarde des preferences utilisateur en SESSION
					if ($_SESSION['identifiant'] != "admin")
					{
						$reponse2 = $bdd->query('SELECT * FROM preferences WHERE identifiant = "' . $_SESSION['identifiant'] . '"');
						$donnees2 = $reponse2->fetch();

						$_SESSION['view_movie_house']   = $donnees2['view_movie_house'];
            $_SESSION['view_the_box']       = $donnees2['view_the_box'];
						$_SESSION['view_notifications'] = $donnees2['view_notifications'];

						$reponse2->closeCursor();
					}

          $connected = true;
				}
        // Sinon, on affiche un message d'erreur
				else
				{
					$_SESSION['connected']       = false;
					$_SESSION['wrong_connexion'] = true;
				}

				$_SESSION['not_yet'] = false;
			}
		}
		else
		{
			$_SESSION['connected']       = false;
			$_SESSION['wrong_connexion'] = true;
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
    $beginner         = 0;
    $developper       = 0;
    $expenses         = 0;

    // Initialisations préférences
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
          $req = $bdd->prepare('INSERT INTO users(identifiant,
                                                  salt,
                                                  mot_de_passe,
                                                  reset,
                                                  pseudo,
                                                  avatar,
                                                  email,
                                                  beginner,
                                                  developper,
                                                  expenses)
                                           VALUES(:identifiant,
                                                  :salt,
                                                  :mot_de_passe,
                                                  :reset,
                                                  :pseudo,
                                                  :avatar,
                                                  :email,
                                                  :beginner,
                                                  :developper,
                                                  :expenses
                                                 )');
  				$req->execute(array(
  					'identifiant'  => $trigramme,
            'salt'         => $salt,
            'mot_de_passe' => $password,
            'reset'        => $reset,
  					'pseudo'       => $pseudo,
  					'avatar'       => $avatar,
            'email'        => $email,
            'beginner'     => $beginner,
            'developper'   => $developper,
            'expenses'     => $expenses
  					));
  				$req->closeCursor();

          // On créé les préférences
          $req = $bdd->prepare('INSERT INTO preferences(identifiant,
                                                        view_movie_house,
                                                        categories_home,
                                                        today_movie_house,
                                                        view_old_movies,
                                                        view_the_box,
                                                        view_notifications,
                                                        manage_calendars)
                                                 VALUES(:identifiant,
                                                        :view_movie_house,
                                                        :categories_home,
                                                        :today_movie_house,
                                                        :view_old_movies,
                                                        :view_the_box,
                                                        :view_notifications,
                                                        :manage_calendars)');
          $req->execute(array(
            'identifiant'        => $trigramme,
            'view_movie_house'   => $view_movie_house,
            'categories_home'    => $categories_home,
            'today_movie_house'  => $today_movie_house,
            'view_old_movies'    => $view_old_movies,
            'view_the_box'       => $view_the_box,
            'view_notifications' => $view_notifications,
            'manage_calendars'   => $manage_calendars
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
    else
      $_SESSION['too_short'] = true;

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

  // METIER : Récupération mission active
  // RETOUR : Objet mission
  function getMission()
  {
    $mission   = NULL;
    $date_jour = date('Ymd');

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM missions WHERE ' . $date_jour . ' >= date_deb AND ' . $date_jour . ' <= date_fin');
    $donnees = $reponse->fetch();

    if ($reponse->rowCount() > 0)
      $mission = Mission::withData($donnees);

    $reponse->closeCursor();

    return $mission;
  }

  // METIER : Contrôle mission déjà complétée
  // RETOUR : Nombre de missions à générer
  function controlMissionComplete($user, $mission)
  {
    $missionToGenerate = 0;
    $date_jour         = date('Ymd');

    global $bdd;

    // Objectif mission
    $reponse1 = $bdd->query('SELECT * FROM missions WHERE id = ' . $mission->getId());
    $donnees1 = $reponse1->fetch();

    $objectifMission = $donnees1['objectif'];

    $reponse1->closeCursor();

    // Objectif atteint par l'utilisateur dans la journée
    $reponse2 = $bdd->query('SELECT * FROM missions_users WHERE id_mission = ' . $mission->getId() . ' AND identifiant = "' . $user . '" AND date_mission = ' . $date_jour);
    $donnees2 = $reponse2->fetch();

    $avancementUser = $donnees2['avancement'];

    $reponse2->closeCursor();

    if ($avancementUser < $objectifMission)
      $missionToGenerate = $objectifMission - $avancementUser;

    return $missionToGenerate;
  }

  // METIER : Génération contexte mission (boutons)
  // RETOUR : Tableau contexte
  function generateMissions($nb, $mission)
  {
    $missions                  = array();

    $listPages                 = array('/inside/portail/bugs/bugs.php',
                                       '/inside/portail/calendars/calendars.php',
                                       '/inside/portail/collector/collector.php',
                                       '/inside/portail/expensecenter/expensecenter.php',
                                       '/inside/portail/moviehouse/details.php',
                                       '/inside/portail/moviehouse/mailing.php',
                                       '/inside/portail/moviehouse/moviehouse.php',
                                       '/inside/portail/moviehouse/saisie.php',
                                       '/inside/portail/notifications/notifications.php',
                                       '/inside/portail/petitspedestres/parcours.php',
                                       '/inside/portail/portail/portail.php',
                                       '/inside/portail/missions/missions.php',
                                       '/inside/portail/missions/details.php',
                                       '/inside/profil/profil.php',
                                      );
    $listZonesCompletes        = array('header',
                                       'nav',
                                       'aside',
                                       'footer',
                                       //'article'
                                      );
    $listZonesPartielles       = array('header',
                                       'aside',
                                       'footer',
                                       //'article'
                                      );
    $listPositionsHorizontales = array('left',
                                       'right',
                                       'middle',
                                      );
    $listPositionsVerticales   = array('top',
                                       'bottom',
                                       'middle'
                                      );

    for ($i = 0; $i < $nb; $i++)
    {
      // Id mission
      $id_mission = $mission->getId();

      // Référence mission remplie
      $ref_mission = $i;

      // Page
      $page = $listPages[array_rand($listPages)];

      // Zone
      switch ($page)
      {
        case '/inside/portail/calendars/calendars.php':
        case '/inside/portail/collector/collector.php':
        case '/inside/portail/expensecenter/expensecenter.php':
        case '/inside/portail/moviehouse/details.php':
        case '/inside/portail/moviehouse/mailing.php':
        case '/inside/portail/moviehouse/moviehouse.php':
        case '/inside/portail/moviehouse/saisie.php':
        case '/inside/portail/petitspedestres/parcours.php':
        case '/inside/portail/missions/missions.php':
        case '/inside/portail/missions/details.php':
          $zone = $listZonesCompletes[array_rand($listZonesCompletes)];
          break;

        case '/inside/portail/bugs/bugs.php':
        case '/inside/portail/notifications/notifications.php':
        case '/inside/portail/portail/portail.php':
        case '/inside/profil/profil.php':
        default:
          $zone = $listZonesPartielles[array_rand($listZonesPartielles)];
          break;
      }

      // Position
      switch ($zone)
      {
        case 'header':
        case 'nav':
        case 'footer':
          $position = $listPositionsHorizontales[array_rand($listPositionsHorizontales)];
          break;

        case 'aside':
        default:
          $position = $listPositionsVerticales[array_rand($listPositionsVerticales)];
          break;
      }

      if ($position == 'left' OR $position == 'top' OR $position =='bottom' OR ($position == 'middle' AND $zone == 'aside'))
        $icone = $mission->getReference() . '_g';
      elseif ($position == 'right')
        $icone = $mission->getReference() . '_d';
      elseif ($position == 'middle' AND $zone != 'aside')
        $icone = $mission->getReference() . '_m';

      $classe = $zone . '_' . $position . '_mission';

      $myMission = array('id_mission'  => $id_mission,
                         'ref_mission' => $ref_mission,
                         'page'        => $page,
                         'zone'        => $zone,
                         'position'    => $position,
                         'icon'        => $icone,
                         'class'       => $classe
                        );

      array_push($missions, $myMission);
    }

    return $missions;
  }
?>
