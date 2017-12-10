<?php
  include_once('../includes/appel_bdd.php');
  include_once('../includes/classes/movies.php');
  include_once('../includes/classes/calendars.php');
  include_once('../includes/classes/bugs.php');
  include_once('../includes/classes/profile.php');
  include_once('../includes/classes/success.php');
  include_once('../includes/classes/missions.php');
  include_once('../includes/imagethumb.php');

  // METIER : Contrôle alertes utilisateurs
  // RETOUR : Booléen
  function getAlerteUsers()
  {
    $alert = false;

    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, pseudo, reset FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
    while($data = $req->fetch())
    {
      if ($data['reset'] == "Y" OR $data['reset'] == "I" OR $data['reset'] == "D")
      {
        $alert = true;
        break;
      }
    }
    $req->closeCursor();

    return $alert;
  }

  // METIER : Contrôle alertes Movie House
  // RETOUR : Booléen
  function getAlerteFilms()
  {
    $alert = false;

    global $bdd;

    $req = $bdd->query('SELECT id, to_delete FROM movie_house WHERE to_delete = "Y"');
    while($data = $req->fetch())
    {
      if ($data['to_delete'] == "Y")
      {
        $alert = true;
        break;
      }
    }
    $req->closeCursor();

    return $alert;
  }

  // METIER : Contrôle alertes Calendars
  // RETOUR : Booléen
  function getAlerteCalendars()
  {
    $alert = false;

    global $bdd;

    $req = $bdd->query('SELECT id, to_delete FROM calendars WHERE to_delete = "Y"');
    while($data = $req->fetch())
    {
      if ($data['to_delete'] == "Y")
      {
        $alert = true;
        break;
      }
    }
    $req->closeCursor();

    return $alert;
  }

  // METIER : Nombre de bugs en attente
  // RETOUR : Nombre de bugs
  function getNbBugs()
  {
    $nb_bugs = 0;

    global $bdd;

    $req = $bdd->query('SELECT COUNT(id) AS nb_bugs FROM bugs WHERE type = "B" AND resolved = "N"');
    $data = $req->fetch();

    $nb_bugs = $data['nb_bugs'];

    $req->closeCursor();

    return $nb_bugs;
  }

  // METIER : Nombre d'évolutions en attente
  // RETOUR : Nombre d'évolutions
  function getNbEvols()
  {
    $nb_evols = 0;

    global $bdd;

    $req = $bdd->query('SELECT COUNT(id) AS nb_bugs FROM bugs WHERE type = "E" AND resolved = "N"');
    $data = $req->fetch();

    $nb_evols = $data['nb_bugs'];

    $req->closeCursor();

    return $nb_evols;
  }

  // METIER : Lecture des films à supprimer
  // RETOUR : Liste des films à supprimer
  function getFilmsToDelete()
  {
    $listToDelete = array();

    global $bdd;

    $reponse1 = $bdd->query('SELECT id, film, to_delete, identifiant_add, identifiant_del FROM movie_house WHERE to_delete = "Y" ORDER BY id ASC');
    while($donnees1 = $reponse1->fetch())
    {
      $myDelete = Movie::withData($donnees1);

      // On récupère le pseudo du suppresseur
      $reponse2 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $myDelete->getIdentifiant_del() . '"');
      $donnees2 = $reponse2->fetch();
      $myDelete->setPseudo_del($donnees2['pseudo']);
      $reponse2->closeCursor();

      // On récupère le pseudo de l'ajouteur
      $reponse3 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $myDelete->getIdentifiant_add() . '"');
      $donnees3 = $reponse3->fetch();
      $myDelete->setPseudo_add($donnees3['pseudo']);
      $reponse3->closeCursor();

      // On récupère le nombre de participants
      $reponse4 = $bdd->query('SELECT COUNT(id) AS nb_users FROM movie_house_users WHERE id_film = ' . $myDelete->getId());
      $donnees4 = $reponse4->fetch();
      $myDelete->setNb_users($donnees4['nb_users']);
      $reponse4->closeCursor();

      // On ajoute la ligne au tableau
      array_push($listToDelete, $myDelete);
    }
    $reponse1->closeCursor();

    return $listToDelete;
  }

  // METIER : Supprime un film de la base
  // RETOUR : Aucun
  function deleteFilm($id_film)
  {
    global $bdd;

    // Suppression des avis movie_house_users
    $req1 = $bdd->exec('DELETE FROM movie_house_users WHERE id_film = ' . $id_film);

    // Suppression des commentaires
    $req2 = $bdd->exec('DELETE FROM movie_house_comments WHERE id_film = ' . $id_film );

    // Suppression du film
    $req3 = $bdd->exec('DELETE FROM movie_house WHERE id = ' . $id_film );

    // Suppression des notifications
    deleteNotification('film', $id_film);
    deleteNotification('doodle', $id_film);
    deleteNotification('cinema', $id_film);
    deleteNotification('comments', $id_film);

    $_SESSION['alerts']['film_deleted'] = true;
  }

  // METIER : Réinitialise un film de la base
  // RETOUR : Aucun
  function resetFilm($id_film)
  {
    global $bdd;

    // Mise à jour de la table (remise à N de l'indicateur de demande et effacement identifiant suppression)
    $to_delete       = "N";
    $identifiant_del = "";

    $req = $bdd->prepare('UPDATE movie_house SET to_delete = :to_delete, identifiant_del = :identifiant_del WHERE id = ' . $id_film);
    $req->execute(array(
      'to_delete'       => $to_delete,
      'identifiant_del' => $identifiant_del
    ));
    $req->closeCursor();

    $_SESSION['alerts']['film_reseted'] = true;
  }

  // METIER : Lecture des calendriers à supprimer
  // RETOUR : Liste des calendriers à supprimer
  function getCalendarsToDelete()
  {
    $listToDelete = array();

    $listeMois = array('01' => 'Janvier',
                       '02' => 'Février',
                       '03' => 'Mars',
                       '04' => 'Avril',
                       '05' => 'Mai',
                       '06' => 'Juin',
                       '07' => 'Juillet',
                       '08' => 'Août',
                       '09' => 'Septembre',
                       '10' => 'Octobre',
                       '11' => 'Novembre',
                       '12' => 'Décembre'
                      );

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM calendars WHERE to_delete = "Y" ORDER BY year DESC, month DESC, id DESC');
    while($donnees = $reponse->fetch())
    {
      $myDelete = Calendrier::withData($donnees);
      $myDelete->setTitle($listeMois[$myDelete->getMonth()] . " " . $myDelete->getYear());

      // On ajoute la ligne au tableau
      array_push($listToDelete, $myDelete);
    }
    $reponse->closeCursor();

    return $listToDelete;
  }

  // METIER : Supprime un calendrier de la base
  // RETOUR : Aucun
  function deleteCalendrier($id_cal)
  {
    global $bdd;

    // On efface le calendrier si présent
    $reponse = $bdd->query('SELECT * FROM calendars WHERE id = ' . $id_cal);
    $donnees = $reponse->fetch();

    if (isset($donnees['calendar']) AND !empty($donnees['calendar']))
    {
      unlink ("../portail/calendars/images/" . $donnees['year'] . "/" . $donnees['calendar']);
      unlink ("../portail/calendars/images/" . $donnees['year'] . "/mini/" . $donnees['calendar']);
    }

    $reponse->closeCursor();

    // On efface la ligne de la base
    $reponse2 = $bdd->exec('DELETE FROM calendars WHERE id = ' . $id_cal);

    // Suppression des notifications
    deleteNotification('calendrier', $id_cal);

    $_SESSION['alerts']['calendar_deleted'] = true;
  }

  // METIER : Réinitialise un calendrier de la base
  // RETOUR : Aucun
  function resetCalendrier($id_cal)
  {
    global $bdd;

    // Mise à jour de la table (remise à N de l'indicateur de demande)
    $to_delete = "N";

    $req = $bdd->prepare('UPDATE calendars SET to_delete = :to_delete WHERE id = ' . $id_cal);
    $req->execute(array(
      'to_delete' => $to_delete
    ));
    $req->closeCursor();

    $_SESSION['alerts']['calendar_reseted'] = true;
  }

  // METIER : Lecture liste des bugs
  // RETOUR : Tableau des bugs
  function getBugs($view)
  {
    // Initialisation tableau des bugs
    $listeBugs = array();

    global $bdd;

    // Lecture de la base en fonction de la vue
    if ($view == "resolved")
      $reponse = $bdd->query('SELECT * FROM bugs WHERE resolved = "Y" ORDER BY id DESC');
    elseif ($view == "unresolved")
      $reponse = $bdd->query('SELECT * FROM bugs WHERE resolved = "N" ORDER BY id DESC');
    else
      $reponse = $bdd->query('SELECT * FROM bugs ORDER BY id DESC');

    while ($donnees = $reponse->fetch())
    {
      // Initilialisation variables
      $auteur_bug = "";

      // Instanciation d'un objet Idea à partir des données remontées de la bdd
      $bug = Bugs::withData($donnees);

      // Recherche du nom complet de l'auteur
      $reponse2 = $bdd->query('SELECT identifiant, pseudo FROM users WHERE identifiant = "' . $bug->getAuthor() . '"');
      $donnees2 = $reponse2->fetch();

      if (isset($donnees2['pseudo']) AND !empty($donnees2['pseudo']))
        $auteur_bug = $donnees2['pseudo'];
      else
        $auteur_bug = "un ancien utilisateur";

      $reponse2->closeCursor();

      // On construit un tableau qu'on alimente avec les données d'un bug
      $myBug = array('id'       => $bug->getId(),
                     'subject'  => $bug->getSubject(),
                     'date'     => $bug->getDate(),
                     'author'   => $bug->getAuthor(),
                     'name_a'   => $auteur_bug,
                     'content'  => $bug->getContent(),
                     'type'     => $bug->getType(),
                     'resolved' => $bug->getResolved()
                     );

      array_push($listeBugs, Bugs::withData($myBug));
    }

    $reponse->closeCursor();

    return $listeBugs;
  }

  // METIER : Mise à jour du statut d'un bug
  // RETOUR : Aucun
  function updateBug($id, $post)
  {
    global $bdd;

    switch (key($post))
    {
      case 'resolve_bug':
        $resolved = "Y";
        break;

      case 'unresolve_bug':
        $resolved = "N";
        break;

      default:
        break;
    }

    $req = $bdd->prepare('UPDATE bugs SET resolved = :resolved WHERE id = ' . $id);
    $req->execute(array(
      'resolved' => $resolved
    ));
    $req->closeCursor();
  }

  // METIER : Suppression d'un bug
  // RETOUR : Aucun
  function deleteBug($id)
  {
    global $bdd;

    $req = $bdd->exec('DELETE FROM bugs WHERE id = ' . $id);

    $_SESSION['alerts']['bug_deleted'] = true;
  }

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Tableau d'utilisateurs
  function getUsers()
  {
    // Initialisation tableau d'utilisateurs
    $listeUsers = array();

    global $bdd;

    $reponse = $bdd->query('SELECT id, identifiant, reset, pseudo, avatar, email, beginner, developper FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
    while($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet User à partir des données remontées de la bdd
      $user = Profile::withData($donnees);

      // On ajoute la ligne au tableau
      array_push($listeUsers, $user);
    }
    $reponse->closeCursor();

    return $listeUsers;
  }

  // METIER : Lecture statistiques catégories des utilisateurs inscrits
  // RETOUR : Tableau de nombres de commentaires & bilans des dépenses & phrases cultes
  function getTabCategoriesIns($list_users)
  {
    // Initialisation tableau des statistiques de catégories
    $tabCategories = array();

    global $bdd;

    ////////////////////////////////////////
    // Statistiques utilisateurs inscrits //
    ////////////////////////////////////////
    foreach ($list_users as $user)
    {
      $nb_ajouts     = 0;
      $nb_comments   = 0;
      $bilan         = 0;
      $nb_collectors = 0;

      // Nombre films ajoutés Movie House
      $req0 = $bdd->query('SELECT COUNT(id) AS nb_ajouts FROM movie_house WHERE identifiant_add = "' . $user->getIdentifiant() . '"');
      $data0 = $req0->fetch();

      $nb_ajouts = $data0['nb_ajouts'];

      $req0->closeCursor();

      // Nombre commentaires Movie House
      $req1 = $bdd->query('SELECT COUNT(id) AS nb_comments FROM movie_house_comments WHERE author = "' . $user->getIdentifiant() . '"');
      $data1 = $req1->fetch();

      $nb_comments = $data1['nb_comments'];

      $req1->closeCursor();

      // Bilan des dépenses
      $req2 = $bdd->query('SELECT id, identifiant, expenses FROM users WHERE identifiant = "' . $user->getIdentifiant() . '"');
      $data2 = $req2->fetch();

      $bilan = $data2['expenses'];
      $bilan_format = formatBilanForDisplay($bilan);

      $req2->closeCursor();

      // Nombre phrases cultes Collector Room
      $req3 = $bdd->query('SELECT COUNT(id) AS nb_collectors FROM collector WHERE author = "' . $user->getIdentifiant() . '"');
      $data3 = $req3->fetch();

      $nb_collectors = $data3['nb_collectors'];

      $req3->closeCursor();

      $cat = array('identifiant'   => $user->getIdentifiant(),
                   'pseudo'        => $user->getPseudo(),
                   'nb_ajouts'     => $nb_ajouts,
                   'nb_comments'   => $nb_comments,
                   'bilan'         => $bilan,
                   'bilan_format'  => $bilan_format,
                   'nb_collectors' => $nb_collectors
                  );

      array_push($tabCategories, $cat);
    }

    return $tabCategories;
  }

  // METIER : Lecture statistiques catégories des utilisateurs désinscrits
  // RETOUR : Tableau de nombres de commentaires & bilans des dépenses
  function getTabCategoriesDes($list_users)
  {
    // Initialisation tableau des statistiques de catégories
    $tabCategories = array();

    global $bdd;

    ///////////////////////////////////////////
    // Statistiques utilisateurs désinscrits //
    ///////////////////////////////////////////

    // On cherche les utilisateurs désinscrits qui ont une dépense
    $utilisateurs_desinscrits = array();
    $j = 0;

    $req1 = $bdd->query('SELECT DISTINCT identifiant FROM expense_center_users ORDER BY identifiant ASC');
    while($data1 = $req1->fetch())
    {
      $founded = false;

      foreach ($list_users as $user_ins)
      {
        if ($data1['identifiant'] == $user_ins->getIdentifiant())
        {
          $founded = true;
          break;
        }
      }

      if ($founded == false)
      {
        $utilisateurs_desinscrits[$j] = $data1['identifiant'];
        $j++;
      }
    }
    $req1->closeCursor();

    // On cherche les utilisateurs désinscrits qui ont un achat
		$req2 = $bdd->query('SELECT DISTINCT buyer FROM expense_center ORDER BY buyer ASC');
		while($data2 = $req2->fetch())
		{
			$founded = false;

			// On cherche déja s'il y a un acheteur qui n'est pas dans les inscrits
			foreach ($list_users as $user_ins)
			{
				if ($data2['buyer'] == $user_ins->getIdentifiant())
				{
					$founded = true;
					break;
				}
			}

      // Si c'est le cas, on cherche s'il n'est pas déjà dans les désinscrits
			if ($founded == false)
			{
				foreach ($utilisateurs_desinscrits as $user_des)
				{
					if ($data2['buyer'] == $user_des)
					{
						$founded = true;
						break;
					}
				}
			}

      // Sinon on le rajoute à la liste des désinscrits
      if ($founded == false)
      {
        $utilisateurs_desinscrits[$j] = $data2['buyer'];
        $j++;
      }
    }
    $req2->closeCursor();

    // Utilisateurs désinscrits
    foreach ($utilisateurs_desinscrits as $user_des)
    {
      // Nombre films ajoutés Movie House
      $req3 = $bdd->query('SELECT COUNT(id) AS nb_ajouts FROM movie_house WHERE identifiant_add = "' . $user_des . '"');
      $data3 = $req3->fetch();

      $nb_ajouts = $data3['nb_ajouts'];

      $req3->closeCursor();

      // Nombre de commentaires Movie House
      $req4 = $bdd->query('SELECT COUNT(id) AS nb_comments FROM movie_house_comments WHERE author = "' . $user_des . '"');
      $data4 = $req4->fetch();

      $nb_comments = $data4['nb_comments'];

      $req4->closeCursor();

      // Calcul des bilans pour les utilisateurs désinscrits uniquement
      $bilan = 0;

      $req5 = $bdd->query('SELECT * FROM expense_center ORDER BY id ASC');
      while($data5 = $req5->fetch())
      {
        // Prix d'achat
        $prix_achat = $data5['price'];

        // Identifiant de l'acheteur
        $acheteur = $data5['buyer'];

        // Nombre de parts total et utilisateur
        $nb_parts_total = 0;
        $nb_parts_user = 0;

        $req6 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense = ' . $data5['id']);
        while($data6 = $req6->fetch())
        {
          // Nombre de parts total
          $nb_parts_total += $data6['parts'];

          // Nombre de parts de l'utilisateur
          if ($user_des == $data6['identifiant'])
            $nb_parts_user = $data6['parts'];
        }

        // Prix par parts
        if ($nb_parts_total != 0)
          $prix_par_part = $prix_achat / $nb_parts_total;
        else
          $prix_par_part = 0;

        // On fait la somme des dépenses moins les parts consommées pour trouver le bilan
        if ($data5['buyer'] == $user_des AND $nb_parts_user >= 0)
          $bilan += $prix_achat - ($prix_par_part * $nb_parts_user);
        elseif ($data5['buyer'] != $user_des AND $nb_parts_user > 0)
          $bilan -= $prix_par_part * $nb_parts_user;

        $req6->closeCursor();
      }
      $req5->closeCursor();

      $bilan_format = str_replace('.', ',', number_format($bilan, 2)) . ' €';

      // Nombre phrases cultes Collector Room
      $req7 = $bdd->query('SELECT COUNT(id) AS nb_collectors FROM collector WHERE author = "' . $user_des . '"');
      $data7 = $req7->fetch();

      $nb_collectors = $data7['nb_collectors'];

      $req7->closeCursor();

      $cat = array('identifiant'   => $user_des,
                   'pseudo'        => '',
                   'nb_ajouts'     => $nb_ajouts,
                   'nb_comments'   => $nb_comments,
                   'bilan'         => $bilan,
                   'bilan_format'  => $bilan_format,
                   'nb_collectors' => $nb_collectors
                  );

      array_push($tabCategories, $cat);
    }

    return $tabCategories;
  }

  // METIER : Lecture total catégories
  // RETOUR : Tableau des totaux des catégories
  function getTotCategories($tabIns, $tabDes)
  {
    // Initialisation tableau totaux catégories
    $tabTotCat           = array();
    $nb_tot_ajouts       = 0;
    $nb_tot_commentaires = 0;
    $somme_bilans        = 0;
    $alerte_bilan        = false;
    $nb_tot_collectors   = 0;

    global $bdd;

    // Nombre films ajoutés Movie House
    $req0 = $bdd->query('SELECT COUNT(id) AS nb_ajouts FROM movie_house');
    $data0 = $req0->fetch();

    $nb_tot_ajouts = $data0['nb_ajouts'];

    $req0->closeCursor();

    // Nombre de commentaires total
    $req1 = $bdd->query('SELECT COUNT(id) AS nb_comments FROM movie_house_comments');
    $data1 = $req1->fetch();

    $nb_tot_commentaires = $data1['nb_comments'];

    $req1->closeCursor();

    // Calcul somme bilans utilisateurs inscrits
    foreach ($tabIns as $userIns)
    {
      $somme_bilans += $userIns['bilan'];
    }

    // Calcul somme bilans utilisateurs désinscrits
    foreach ($tabDes as $userDes)
    {
      $somme_bilans += $userDes['bilan'];
    }

    // On cherche les dépenses sans parts
		$depense_0_parts = 0;

		$req2 = $bdd->query('SELECT * FROM expense_center ORDER BY id ASC');
		while($data2 = $req2->fetch())
		{
			$req3 = $bdd->query('SELECT COUNT(id) AS nb_parts_depense FROM expense_center_users WHERE id_expense = ' . $data2['id']);
			$data3 = $req3->fetch();

			if ($data3['nb_parts_depense'] == 0)
				$depense_0_parts += $data2['price'];

			$req3->closeCursor();
		}
		$req2->closeCursor();

    // On retire les dépenses à 0 parts de la somme des bilans
		$somme_bilans = $somme_bilans - $depense_0_parts;

    // Formattage
    $somme_bilans_format = str_replace('.', ',', number_format($somme_bilans, 2)) . ' €';

    // Alerte si bilan non nul (proche de 0 à cause de l'arrondi)
    if ($somme_bilans > 0.01 OR $somme_bilans < -0.01)
      $alerte_bilan = true;

    // Nombre de phrase cultes total
    $req4 = $bdd->query('SELECT COUNT(id) AS nb_collectors FROM collector');
    $data4 = $req4->fetch();

    $nb_tot_collectors = $data4['nb_collectors'];

    $req4->closeCursor();

    $tabTotCat = array('nb_tot_ajouts'       => $nb_tot_ajouts,
                       'nb_tot_commentaires' => $nb_tot_commentaires,
                       'somme_bilans'        => $somme_bilans,
                       'somme_bilans_format' => $somme_bilans_format,
                       'alerte_bilan'        => $alerte_bilan,
                       'nb_tot_collectors'   => $nb_tot_collectors
                        );

    return $tabTotCat;
  }

  // METIER : Lecture statistiques
  // RETOUR : Tableau de nombres de bugs & idées
  function getTabStats($list_users)
  {
    // Initialisation tableau statistiques utilisateurs
    $tabStats = array();

    // Initialisations calcul total des inscrits
    $nb_tot_bugs            = 0;
    $nb_tot_bugs_resolus    = 0;
    $nb_tot_idees           = 0;
    $nb_tot_idees_en_charge = 0;
    $nb_tot_idees_terminees = 0;

    global $bdd;

    ////////////////////////////////////////
    // Statistiques utilisateurs inscrits //
    ////////////////////////////////////////
    foreach ($list_users as $user)
    {
      $stats               = array();
      $nb_bugs             = 0;
      $nb_bugs_resolved    = 0;
      $nb_ideas            = 0;
      $nb_ideas_inprogress = 0;
      $nb_ideas_finished   = 0;

      // Nombre de demandes (bugs/évolutions)
      $req1 = $bdd->query('SELECT COUNT(id) AS nb_bugs FROM bugs WHERE author = "' . $user->getIdentifiant() . '"');
      $data1 = $req1->fetch();

      $nb_bugs      = $data1['nb_bugs'];
      $nb_tot_bugs += $data1['nb_bugs'];

      $req1->closeCursor();

      // Nombre de demandes résolues (bugs/évolutions)
      $req2 = $bdd->query('SELECT COUNT(id) AS nb_bugs_resolus FROM bugs WHERE author = "' . $user->getIdentifiant() . '" AND resolved = "Y"');
      $data2 = $req2->fetch();

      $nb_bugs_resolved     = $data2['nb_bugs_resolus'];
      $nb_tot_bugs_resolus += $data2['nb_bugs_resolus'];

      $req2->closeCursor();

      // Nombre d'idées publiées
      $req3 = $bdd->query('SELECT COUNT(id) AS nb_ideas FROM ideas WHERE author = "' . $user->getIdentifiant() . '"');
      $data3 = $req3->fetch();

      $nb_ideas      = $data3['nb_ideas'];
      $nb_tot_idees += $data3['nb_ideas'];

      $req3->closeCursor();

      // Nombre d'idées en charge
      $req4 = $bdd->query('SELECT COUNT(id) AS nb_ideas_inprogress FROM ideas WHERE developper = "' . $user->getIdentifiant() . '" AND status != "D" AND status != "R"');
      $data4 = $req4->fetch();

      $nb_ideas_inprogress     = $data4['nb_ideas_inprogress'];
      $nb_tot_idees_en_charge += $data4['nb_ideas_inprogress'];

      $req4->closeCursor();

      // Nombre d'idées terminées ou rejetées
      $req5 = $bdd->query('SELECT COUNT(id) AS nb_ideas_finished FROM ideas WHERE developper = "' . $user->getIdentifiant() . '" AND (status = "D" OR status = "R")');
      $data5 = $req5->fetch();

      $nb_ideas_finished       = $data5['nb_ideas_finished'];
      $nb_tot_idees_terminees += $data5['nb_ideas_finished'];

      $req5->closeCursor();

      // On génère une ligne du tableau
      $stats = array('identifiant'         => $user->getIdentifiant(),
                     'pseudo'              => $user->getPseudo(),
                     'nb_bugs'             => $nb_bugs,
                     'nb_bugs_resolved'    => $nb_bugs_resolved,
                     'nb_ideas'            => $nb_ideas,
                     'nb_ideas_inprogress' => $nb_ideas_inprogress,
                     'nb_ideas_finished'   => $nb_ideas_finished
                    );

      array_push($tabStats, $stats);
    }

    ///////////////////////////////////////////
    // Statistiques utilisateurs désinscrits //
    ///////////////////////////////////////////
    $nb_bugs_unsubscribed             = 0;
    $nb_bugs_resolved_unsubscribed    = 0;
    $nb_ideas_unsubscribed            = 0;
    $nb_ideas_inprogress_unsubscribed = 0;
    $nb_ideas_finished_unsubscribed   = 0;

    // Nombre de demandes (bugs/évolutions)
    $req6 = $bdd->query('SELECT COUNT(id) AS nb_bugs FROM bugs');
    $data6 = $req6->fetch();

    $nb_bugs_unsubscribed = $data6['nb_bugs'] - $nb_tot_bugs;

    $req6->closeCursor();

    // Nombre de demandes résolues (bugs/évolutions)
    $req7 = $bdd->query('SELECT COUNT(id) AS nb_bugs FROM bugs WHERE resolved = "Y"');
    $data7 = $req7->fetch();

    $nb_bugs_resolved_unsubscribed = $data7['nb_bugs'] - $nb_tot_bugs_resolus;

    $req7->closeCursor();

    // Nombre d'idées publiées
    $req8 = $bdd->query('SELECT COUNT(id) AS nb_ideas FROM ideas');
    $data8 = $req8->fetch();

    $nb_ideas_unsubscribed = $data8['nb_ideas'] - $nb_tot_idees;

    $req8->closeCursor();

    // Nombre d'idées en charge
    $req9 = $bdd->query('SELECT COUNT(id) AS nb_ideas_inprogress FROM ideas WHERE developper != "" AND status != "D" AND status != "R"');
    $data9 = $req9->fetch();

    $nb_ideas_inprogress_unsubscribed = $data9['nb_ideas_inprogress'] - $nb_tot_idees_en_charge;

    $req9->closeCursor();

    // Nombre d'idées terminées ou rejetées
    $req10 = $bdd->query('SELECT COUNT(id) AS nb_ideas_finished FROM ideas WHERE developper != "" AND (status = "D" OR status = "R")');
    $data10 = $req10->fetch();

    $nb_ideas_finished_unsubscribed = $data10['nb_ideas_finished'] - $nb_tot_idees_terminees;

    $req10->closeCursor();

    // On génère une ligne unique du tableau
    $stats_unsubscribed = array('identifiant'         => "Autres",
                                'pseudo'              => "Anciens utilisateurs",
                                'nb_bugs'             => $nb_bugs_unsubscribed,
                                'nb_bugs_resolved'    => $nb_bugs_resolved_unsubscribed,
                                'nb_ideas'            => $nb_ideas_unsubscribed,
                                'nb_ideas_inprogress' => $nb_ideas_inprogress_unsubscribed,
                                'nb_ideas_finished'   => $nb_ideas_finished_unsubscribed
                  );

    array_push($tabStats, $stats_unsubscribed);

    return $tabStats;
  }

  // METIER : Lecture total statistiques
  // RETOUR : Tableau des totaux des statistiques
  function getTotStats()
  {
    // Initialisation tableau totaux statistiques
    $tabTotStats = array();

    global $bdd;

    // Initialisations calcul total
    $nb_tot_bugs            = 0;
    $nb_tot_bugs_resolus    = 0;
    $nb_tot_idees           = 0;
    $nb_tot_idees_en_charge = 0;
    $nb_tot_idees_terminees = 0;

    // Nombre total des bugs
    $req1 = $bdd->query('SELECT COUNT(id) AS nb_total_bugs FROM bugs');
    $data1 = $req1->fetch();
    $nb_tot_bugs = $data1['nb_total_bugs'];
    $req1->closeCursor();

    // Nombre total des bugs résolus
    $req2 = $bdd->query('SELECT COUNT(id) AS nb_total_resolved FROM bugs WHERE resolved = "Y"');
    $data2 = $req2->fetch();
    $nb_tot_bugs_resolus = $data2['nb_total_resolved'];
    $req2->closeCursor();

    // Nombre total des idées
    $req3 = $bdd->query('SELECT COUNT(id) AS nb_total_ideas FROM ideas');
    $data3 = $req3->fetch();
    $nb_tot_idees = $data3['nb_total_ideas'];
    $req3->closeCursor();

    // Nombre total des idées en charge
    $req4 = $bdd->query('SELECT COUNT(id) AS nb_total_ideas_inprogress FROM ideas WHERE developper != "" AND status != "D" AND status != "R"');
    $data4 = $req4->fetch();
    $nb_tot_idees_en_charge = $data4['nb_total_ideas_inprogress'];
    $req4->closeCursor();

    // Nombre total des idées terminées
    $req5 = $bdd->query('SELECT COUNT(id) AS nb_total_ideas_finished FROM ideas WHERE (status = "D" OR status = "R")');
    $data5 = $req5->fetch();
    $nb_tot_idees_terminees = $data5['nb_total_ideas_finished'];
    $req5->closeCursor();

    $tabTotStats = array('nb_tot_bugs' => $nb_tot_bugs,
                         'nb_tot_bugs_resolus' => $nb_tot_bugs_resolus,
                         'nb_tot_idees' => $nb_tot_idees,
                         'nb_tot_idees_en_charge' => $nb_tot_idees_en_charge,
                         'nb_tot_idees_terminees' => $nb_tot_idees_terminees
                        );

    return $tabTotStats;
  }

  // METIER : Génération nouveau mot de passe
  // RETOUR : Mot de passe aléatoire
  function random_string($car)
  {
    $string = "";
    $chaine = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    srand((double)microtime()*1000000);

    for ($i=0; $i < $car; $i++)
    {
      $string .= $chaine[rand()%strlen($chaine)];
    }

    return $string;
  }

  // METIER : Refus réinitialisation mot de passe
  // RETOUR : Aucun
  function resetOldPassword($id_user)
  {
    global $bdd;

    // Mise à jour de la table (remise à N de l'indicateur de demande)
    $reset = "N";

    $req = $bdd->prepare('UPDATE users SET reset = :reset WHERE id = ' . $id_user);
    $req->execute(array(
      'reset' => $reset
    ));
    $req->closeCursor();
  }

  // METIER : Réinitialisation mot de passe
  // RETOUR : Aucun
  function setNewPassword($id_user)
  {
    global $bdd;

    // Mise à jour de la table (remise à N de l'indicateur de demande et du mot de passe)
    $reset = "N";
    $salt  = rand();

    // On génère un nouveau mot de passe aléatoire
    $chaine   = random_string(10);
    $password = htmlspecialchars(hash('sha1', $chaine . $salt));

    $req = $bdd->prepare('UPDATE users SET salt = :salt, password = :password, reset = :reset WHERE id = ' . $id_user);
    $req->execute(array(
      'salt'         => $salt,
      'password'     => $password,
      'reset'        => $reset
    ));
    $req->closeCursor();

    // Récupération identifiant et pseudo
    $reponse = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE id = ' . $id_user);
    $donnees = $reponse->fetch();

    $_SESSION['user_ask_id'] = $donnees['identifiant'];
    $_SESSION['user_ask_name'] = $donnees['pseudo'];
    $_SESSION['new_password'] = $chaine;

    $reponse->closeCursor();
  }

  // METIER : Validation inscription (mise à jour du status utilisateur)
  // RETOUR : Aucun
  function acceptInscription($id_user)
  {
    global $bdd;

    // On met simplement à jour le status de l'utilisateur
    $reset = "N";

    $req = $bdd->prepare('UPDATE users SET reset = :reset WHERE id = ' . $id_user);
    $req->execute(array(
      'reset' => $reset
    ));
    $req->closeCursor();

    // On récupère l'identifiant
    $req2 = $bdd->query('SELECT id, identifiant FROM users WHERE id = ' . $id_user);
    $data2 = $req2->fetch();
    $identifiant = $data2['identifiant'];
    $req2->closeCursor();

    // Génération notification nouvel inscrit
    insertNotification('admin', 'inscrit', $identifiant);
  }

  // METIER : Refus inscription
  // RETOUR : Aucun
  function resetInscription($id_user)
  {
    global $bdd;

    // Récupération identifiant
    $req1 = $bdd->query('SELECT id, identifiant FROM users WHERE id = ' . $id_user);
    $data1 = $req1->fetch();

    $identifiant = $data1['identifiant'];

    $req1->closeCursor();

    // Suppression des préférences
    $req2 = $bdd->exec('DELETE FROM preferences WHERE identifiant = "' . $identifiant . '"');

    // Suppression utilisateur
    $req3 = $bdd->exec('DELETE FROM users WHERE id = ' . $id_user);
  }

  // METIER : Validation désinscription
  // RETOUR : Aucun
  function acceptDesinscription($id_user)
  {
    global $bdd;

    // Récupération identifiant
    $req1 = $bdd->query('SELECT id, identifiant FROM users WHERE id = ' . $id_user);
    $data1 = $req1->fetch();

    $identifiant = $data1['identifiant'];

    $req1->closeCursor();

    // Suppression des avis movie_house_users
    $req2 = $bdd->exec('DELETE FROM movie_house_users WHERE identifiant = "' . $identifiant . '"');

    // Suppression des préférences
    $req3 = $bdd->exec('DELETE FROM preferences WHERE identifiant = "' . $identifiant . '"');

    // Suppression des votes collector
    $req4 = $bdd->exec('DELETE FROM collector_users WHERE identifiant = "' . $identifiant . '"');

    // Remise en cours des idées non terminées ou rejetées
    $status     = "O";
    $developper = "";

    $req5 = $bdd->prepare('UPDATE ideas SET status = :status, developper = :developper WHERE developper = "' . $identifiant . '" AND status != "D" AND status != "R"');
    $req5->execute(array(
      'status'     => $status,
      'developper' => $developper
    ));
    $req5->closeCursor();

    // Suppression des missions
    $req6 = $bdd->exec('DELETE FROM missions_users WHERE identifiant = "' . $identifiant . '"');

    // Suppression notification inscription
    deleteNotification('inscrit', $identifiant);

    // Suppression utilisateur
    $req7 = $bdd->exec('DELETE FROM users WHERE id = ' . $id_user . ' AND identifiant = "' . $identifiant . '"');
  }

  // METIER : Refus désinscription
  // RETOUR : Aucun
  function resetDesinscription($id_user)
  {
    global $bdd;

    $reset = "N";

    $req = $bdd->prepare('UPDATE users SET reset = :reset WHERE id = ' . $id_user);
    $req->execute(array(
      'reset' => $reset
    ));
    $req->closeCursor();
  }

  // METIER : Récupération préférences tous utilisateurs
  // RETOUR : Liste des préférences
  function getListePreferences()
  {
    $listPreferences = array();
    $pseudo          = '';

    global $bdd;

    $req = $bdd->query('SELECT * FROM preferences ORDER BY identifiant ASC');
    while($data = $req->fetch())
    {
      // Récupération pseudo
      $req2 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $data['identifiant'] . '"');
      $data2 = $req2->fetch();

      $pseudo = $data2['pseudo'];

      $req2->closeCursor();

      $myPreference = array('id'               => $data['id'],
                            'identifiant'      => $data['identifiant'],
                            'pseudo'           => $pseudo,
                            'manage_calendars' => $data['manage_calendars']
                           );
      array_push($listPreferences, $myPreference);
    }
    $req->closeCursor();

    return $listPreferences;
  }

  // METIER : Mise à jour des autorisations sur les calendriers
  // RETOUR : Aucun
  function updateAutorisations($post)
  {
    global $bdd;

    $req = $bdd->query('SELECT * FROM preferences');
    while($data = $req->fetch())
    {
      // Par défaut, le top autorisation est à Non
      $manage_calendars = "N";

      if (!empty($post['autorization']))
      {
        foreach ($post['autorization'] as $id => $ligne)
        {
          if ($data['id'] == $id)
          {
            // Si seulement on a activé bouton, on passe le top autorisation à Oui
            $manage_calendars = "Y";
            break;
          }
        }
      }

      // Dans tous les cas on met à jour chaque préférence de profil
      $req2 = $bdd->prepare('UPDATE preferences SET manage_calendars = :manage_calendars WHERE id = ' . $data['id']);
      $req2->execute(array(
        'manage_calendars' => $manage_calendars
      ));
      $req2->closeCursor();
    }
    $req->closeCursor();

    $_SESSION['alerts']['autorizations_updated'] = true;
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

  // METIER : Insertion nouveau succès
  // RETOUR : Aucun
  function insertSuccess($post, $files)
  {
    $reference     = $post['reference'];
    $level         = $post['level'];
    $order_success = $post['order_success'];
    $title         = $post['title'];
    $description   = $post['description'];
    $limit_success = $post['limit_success'];
    $explanation   = $post['explanation'];

    // Sauvegarde en cas d'erreur
    $_SESSION['reference_success']   = $reference;
    $_SESSION['level']               = $level;
    $_SESSION['order_success']       = $order_success;
    $_SESSION['title_success']       = $title;
    $_SESSION['description_success'] = $description;
    $_SESSION['limit_success']       = $limit_success;
    $_SESSION['explanation_success'] = $explanation;

    $control_ok = true;
    global $bdd;

    // Contrôle référence
    $reponse = $bdd->query('SELECT * FROM success');
    while($donnees = $reponse->fetch())
    {
      if ($reference == $donnees['reference'])
      {
        $control_ok = false;
        $_SESSION['alerts']['already_referenced'] = true;
        break;
      }
    }
    $reponse->closeCursor();

    // Contrôles niveau
    if ($control_ok == true)
    {
      if (!is_numeric($level) OR $level <= 0)
      {
        $control_ok                    = false;
        $_SESSION['alerts']['level_not_numeric'] = true;
      }
    }

    // Contrôles ordonnancement
    if ($control_ok == true)
    {
      if (is_numeric($order_success))
      {
        $reponse = $bdd->query('SELECT * FROM success WHERE level = ' . $level);
        while($donnees = $reponse->fetch())
        {
          if ($order_success == $donnees['order_success'])
          {
            $control_ok                  = false;
            $_SESSION['alerts']['already_ordered'] = true;
            break;
          }
        }
        $reponse->closeCursor();
      }
      else
      {
        $control_ok                    = false;
        $_SESSION['alerts']['order_not_numeric'] = true;
      }
    }

    // Contrôle condition
    if ($control_ok == true)
    {
      if (!is_numeric($limit_success))
      {
        $control_ok                    = false;
        $_SESSION['alerts']['limit_not_numeric'] = true;
      }
    }

    // Si contrôles ok, insertion image puis table
    if ($control_ok == true)
    {
      // Création dossier si inexistant
      $name_success_dir = '../includes/icons/success';

      if (!is_dir($name_success_dir))
         mkdir($name_success_dir);

      // Insertion image
      // Si on a bien une image
   		if ($files['success']['name'] != NULL)
   		{
   			// Dossier de destination
   			$success_dir = '../includes/icons/success/';

   			// Données du fichier
   			$file      = $files['success']['name'];
   			$tmp_file  = $files['success']['tmp_name'];
   			$size_file = $files['success']['size'];
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
   				$type_file = $files['success']['type'];

   				if (!strstr($type_file, 'png'))
   				{
   					exit("Le fichier n'est pas une image valide");
   				}
   				else
   				{
   					$type_image = pathinfo($file, PATHINFO_EXTENSION);
   					$new_name   = $reference . '.' . $type_image;
   				}

   				// Contrôle upload (si tout est bon, l'image est envoyée)
   				if (!move_uploaded_file($tmp_file, $success_dir . $new_name))
   				{
   					exit("Impossible de copier le fichier dans $success_dir");
   				}

          // Créé une miniature de la source vers la destination en la rognant avec une hauteur/largeur max de 500px (cf fonction imagethumb.php)
   				imagethumb($success_dir . $new_name, $success_dir . $new_name, 500, FALSE, TRUE);

   				// echo "Le fichier a bien été uploadé";

   				// On stocke le nouveau succès dans la base
          $reponse = $bdd->prepare('INSERT INTO success(reference,
                                                        level,
                                                        order_success,
                                                        title,
                                                        description,
                                                        limit_success,
                                                        explanation)
                                                 VALUES(:reference,
                                                        :level,
                                                        :order_success,
                                                        :title,
                                                        :description,
                                                        :limit_success,
                                                        :explanation)');
  				$reponse->execute(array(
  					'reference'     => $reference,
            'level'         => $level,
            'order_success' => $order_success,
            'title'         => $title,
            'description'   => $description,
  					'limit_success' => $limit_success,
            'explanation'   => $explanation
  					));
  				$reponse->closeCursor();

   				$_SESSION['alerts']['success_added'] = true;
   			}
   		}
    }
  }

  // METIER : Suppression succès
  // RETOUR : Aucun
  function deleteSuccess($id_success)
  {
    global $bdd;

    // Suppression de l'image
    $req1 = $bdd->query('SELECT id, reference FROM success WHERE id = ' . $id_success);
    $data1 = $req1->fetch();

    if (isset($data1['reference']) AND !empty($data1['reference']))
      unlink ("../includes/icons/success/" . $data1['reference'] . ".png");

    $req1->closeCursor();

    // Suppression du succès de la base
    $req2 = $bdd->exec('DELETE FROM success WHERE id = ' . $id_success);

    $_SESSION['alerts']['success_deleted'] = true;
  }


  // METIER : Modification succès
  // RETOUR : Aucun
  function updateSuccess($post)
  {
    $update     = array();
    $control_ok = true;

    // Sauvegarde en cas d'erreur
    $_SESSION['save_success'] = $post;

    // Construction tableau pour mise à jour
    foreach ($post['id'] as $id)
    {
      $myUpdate = array('id'            => $post['id'][$id],
                        'level'         => $post['level'][$id],
                        'order_success' => $post['order_success'][$id],
                        'title'         => $post['title'][$id],
                        'description'   => $post['description'][$id],
                        'limit_success' => $post['limit_success'][$id],
                        'explanation'   => $post['explanation'][$id],
                       );
      array_push($update, $myUpdate);
    }

    global $bdd;

    foreach ($update as $success)
    {
      // Contrôles niveau
      if ($control_ok == true)
      {
        if (!is_numeric($success['level']) OR $success['level'] <= 0)
        {
          $control_ok                    = false;
          $_SESSION['alerts']['level_not_numeric'] = true;
        }
      }

      // Contrôles ordonnancement
      if ($control_ok == true)
      {
        if (is_numeric($success['order_success']))
        {
          // Contrôle doublons
          foreach ($update as $test_order)
          {
            if ($success['id'] != $test_order['id'] AND $success['order_success'] == $test_order['order_success'] AND $success['level'] == $test_order['level'])
            {
              $control_ok = false;
              $_SESSION['alerts']['already_ordered'] = true;
              break;
            }
          }
        }
        else
        {
          $control_ok = false;
          $_SESSION['alerts']['order_not_numeric'] = true;
        }
      }

      // Contrôle condition
      if ($control_ok == true)
      {
        if (!is_numeric($success['limit_success']))
        {
          $control_ok = false;
          $_SESSION['alerts']['limit_not_numeric'] = true;
        }
      }

      // Mise à jour si pas d'erreur
      if ($control_ok == true)
      {
        $req = $bdd->prepare('UPDATE success SET level         = :level,
                                                 order_success = :order_success,
                                                 title         = :title,
                                                 description   = :description,
                                                 limit_success = :limit_success,
                                                 explanation   = :explanation
                                           WHERE id = ' . $success['id']);
        $req->execute(array(
          'level'         => $success['level'],
          'order_success' => $success['order_success'],
          'title'         => $success['title'],
          'description'   => $success['description'],
          'limit_success' => $success['limit_success'],
          'explanation'   => $success['explanation']
        ));
        $req->closeCursor();

        $_SESSION['alerts']['success_updated'] = true;
      }

      // On quitte la boucle s'il y a une erreur
      if ($control_ok != true)
        break;
    }

    if ($control_ok != true)
      $_SESSION['erreur_succes'] = true;
    else
      $_SESSION['erreur_succes'] = NULL;
  }

  // METIER : Initialisation champs erreur modification succès
  // RETOUR : Tableau sauvegardé et trié
  function initModErrSucces($listSuccess, $session_succes)
  {
    foreach ($listSuccess as $success)
    {
      $success->setLevel($session_succes['level'][$success->getId()]);
      $success->setOrder_success($session_succes['order_success'][$success->getId()]);
      $success->setTitle($session_succes['title'][$success->getId()]);
      $success->setDescription($session_succes['description'][$success->getId()]);
      $success->setLimit_success($session_succes['limit_success'][$success->getId()]);
      $success->setExplanation($session_succes['explanation'][$success->getId()]);

      // Tri sur niveau puis ordonnancement
      $tri_level[] = $success->getLevel();
      $tri_order[] = $success->getOrder_success();
    }

    array_multisort($tri_level, SORT_ASC, $tri_order, SORT_ASC, $listSuccess);

    return $listSuccess;
  }

  // METIER : Modification top Beginner
  // RETOUR : Aucun
  function changeBeginner($user, $topBeginner)
  {
    if ($topBeginner == 1)
      $topBeginner = 0;
    else
      $topBeginner = 1;

    global $bdd;

    $req = $bdd->prepare('UPDATE users SET beginner = :beginner WHERE identifiant = "' . $user . '"');
    $req->execute(array(
      'beginner' => $topBeginner
    ));
    $req->closeCursor();
  }

  // METIER : Modification top Developper
  // RETOUR : Aucun
  function changeDevelopper($user, $topDevelopper)
  {
    global $bdd;

    if ($topDevelopper == 1)
      $topDevelopper = 0;
    else
      $topDevelopper = 1;

    $req = $bdd->prepare('UPDATE users SET developper = :developper WHERE identifiant = "' . $user . '"');
    $req->execute(array(
      'developper' => $topDevelopper
    ));
    $req->closeCursor();
  }

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
    $_SESSION['user']['pseudo']           = $new_pseudo;
    $_SESSION['alerts']['pseudo_changed'] = true;
  }

  // METIER : Mise à jour de l'avatar (base + fichier)
  // RETOUR : Aucun
  function changeAvatar($user, $files)
  {
    $_SESSION['alerts']['avatar_changed'] = false;

    global $bdd;

    // On contrôle la présence du dossier, sinon on le créé
    $dossier = "../profil/avatars";

    if (!is_dir($dossier))
       mkdir($dossier);

    $avatar = rand();

    // Si on a bien une image
    if ($files['avatar']['name'] != NULL)
    {
      // Dossier de destination
      $avatar_dir = '../profil/avatars/';

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

        $_SESSION['user']['avatar']           = $new_name;
        $_SESSION['alerts']['avatar_changed'] = true;
      }
    }
  }

  // METIER : Suppression de l'avatar (base + fichier)
  // RETOUR : Aucun
  function deleteAvatar($user)
  {
    $_SESSION['alerts']['avatar_deleted'] = false;

    global $bdd;

    // On efface l'ancien avatar si présent
    $reponse1 = $bdd->query('SELECT identifiant, avatar FROM users WHERE identifiant = "' . $user . '"');
    $donnees1 = $reponse1->fetch();

    if (isset($donnees1['avatar']) AND !empty($donnees1['avatar']))
      unlink ("../profil/avatars/" . $donnees1['avatar'] . "");

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

      $wrong_password = false;

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

      $_SESSION['alerts']['wrong_password'] = $wrong_password;
    }
  }

  // METIER : Récupération des fichiers de log (10 derniers de chaque)
  // RETOUR : Tableau fichiers logs
  function getLastLogs()
  {
    $logsJ = array();
    $logsH = array();

    // Récupération fichiers journaliers et tri
    $dirJ   = '../cron/logs/daily';

    if (is_dir($dirJ))
    {
      $filesJ = scandir($dirJ, 1);

      // Suppression racines de dossier
      unset($filesJ[array_search('..', $filesJ)]);
      unset($filesJ[array_search('.', $filesJ)]);

      if (!empty($filesJ))
      {
        // Tri sur date
        foreach ($filesJ as $fileJ)
        {
          $tri_anneeJ[]   = substr($fileJ, 12, 4);
          $tri_moisJ[]    = substr($fileJ, 9, 2);
          $tri_jourJ[]    = substr($fileJ, 6, 2);
          $tri_heureJ[]   = substr($fileJ, 17, 2);
          $tri_minuteJ[]  = substr($fileJ, 20, 2);
          $tri_secondeJ[] = substr($fileJ, 23, 2);
        }

        array_multisort($tri_anneeJ, SORT_DESC, $tri_moisJ, SORT_DESC, $tri_jourJ, SORT_DESC, $tri_heureJ, SORT_DESC, $tri_minuteJ, SORT_DESC, $tri_secondeJ, SORT_DESC, $filesJ);
      }

      $logsJ = array_slice($filesJ, 0, 10);
    }

    // Récupération fichiers hebdomadaires et tri
    $dirH   = '../cron/logs/weekly';

    if (is_dir($dirH))
    {
      $filesH = scandir($dirH, 1);

      // Suppression racines de dossier
      unset($filesH[array_search('..', $filesH)]);
      unset($filesH[array_search('.', $filesH)]);

      if (!empty($filesH))
      {
        // Tri sur date
        foreach ($filesH as $fileH)
        {
          $tri_anneeH[]   = substr($fileH, 12, 4);
          $tri_moisH[]    = substr($fileH, 9, 2);
          $tri_jourH[]    = substr($fileH, 6, 2);
          $tri_heureH[]   = substr($fileH, 17, 2);
          $tri_minuteH[]  = substr($fileH, 20, 2);
          $tri_secondeH[] = substr($fileH, 23, 2);
        }

        array_multisort($tri_anneeH, SORT_DESC, $tri_moisH, SORT_DESC, $tri_jourH, SORT_DESC, $tri_heureH, SORT_DESC, $tri_minuteH, SORT_DESC, $tri_secondeH, SORT_DESC, $filesH);
      }

      $logsH = array_slice($filesH, 0, 10);
    }

    $files = array('daily' => $logsJ, 'weekly' => $logsH);

    return $files;
  }

  // METIER : Initialisation ajout mission
  // RETOUR : Objets mission
  function initAddMission()
  {
    $mission = new Mission();
    return $mission;
  }

  // METIER : Récupération des missions
  // RETOUR : Objets mission
  function getMissions()
  {
    $missions = array();

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM missions');
    while($donnees = $reponse->fetch())
    {
      $myMission = Mission::withData($donnees);

      if (date('Ymd') < $myMission->getDate_deb())
        $myMission->setStatut('V');
      elseif (date('Ymd') >= $myMission->getDate_deb() AND date('Ymd') <= $myMission->getDate_fin())
        $myMission->setStatut('C');
      elseif (date('Ymd') > $myMission->getDate_fin())
        $myMission->setStatut('A');

      array_push($missions, $myMission);
    }
    $reponse->closeCursor();

    // Tri sur statut (V : à venir, C : en cours, A : ancienne)
    foreach ($missions as $mission)
    {
      $tri_statut[]   = $mission->getStatut();
      $tri_date_deb[] = $mission->getDate_deb();
    }

    array_multisort($tri_statut, SORT_DESC, $tri_date_deb, SORT_DESC, $missions);

    return $missions;
  }

  // METIER : Récupération mission spécifique
  // RETOUR : Objet mission
  function getMission($id)
  {
    $mission = new Mission();

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM missions WHERE id = ' . $id);
    $donnees = $reponse->fetch();

    $mission = Mission::withData($donnees);

    $reponse->closeCursor();

    return $mission;
  }

  // METIER : Récupération des participants d'une mission
  // RETOUR : Objets Profil
  function getParticipants($id)
  {
    $participants = array();

    global $bdd;

    $reponse = $bdd->query('SELECT DISTINCT identifiant FROM missions_users WHERE id_mission = ' . $id . ' ORDER BY identifiant ASC');
    while($donnees = $reponse->fetch())
    {
      $reponse2 = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $donnees['identifiant'] . '"');
      $donnees2 = $reponse2->fetch();

      $myParticipant = Profile::withData($donnees2);

      $reponse2->closeCursor();

      array_push($participants, $myParticipant);
    }
    $reponse->closeCursor();

    return $participants;
  }

  // METIER : Classement des utilisateurs sur la mission
  // RETOUR : Tableau classement
  function getRankingMission($id, $users)
  {
    $ranking = array();

    global $bdd;

    foreach ($users as $user)
    {
      $totalMission = 0;
      $initRankUser = 0;

      // Nombre total d'objectifs sur la mission
      $reponse = $bdd->query('SELECT * FROM missions_users WHERE id_mission = ' . $id . ' AND identifiant = "' . $user->getIdentifiant() . '"');
      while($donnees = $reponse->fetch())
      {
        $totalMission += $donnees['avancement'];
      }
      $reponse->closeCursor();

      $myRanking = array('identifiant' => $user->getIdentifiant(),
                         'pseudo'      => $user->getPseudo(),
                         'avatar'      => $user->getAvatar(),
                         'total'       => $totalMission,
                         'rank'        => $initRankUser
                       );

      array_push($ranking, $myRanking);
    }

    if (!empty($ranking))
    {
      // Tri sur avancement puis identifiant
      foreach ($ranking as $rankUser)
      {
        $tri_rank[]  = $rankUser['total'];
        $tri_alpha[] = $rankUser['identifiant'];
      }

      array_multisort($tri_rank, SORT_DESC, $tri_alpha, SORT_ASC, $ranking);

      // Affectation du rang
      $prevTotal   = $ranking[0]['total'];
      $currentRank = 1;

      foreach ($ranking as &$rankUser)
      {
        $currentTotal = $rankUser['total'];

        if ($currentTotal != $prevTotal)
        {
          $currentRank += 1;
          $prevTotal = $rankUser['total'];
        }

        $rankUser['rank'] = $currentRank;
      }

      unset($rankUser);
    }

    return $ranking;
  }
?>
