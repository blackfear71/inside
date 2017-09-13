<?php
  include_once('../includes/appel_bdd.php');
  include_once('../includes/classes/movies.php');
  include_once('../includes/classes/calendars.php');
  include_once('../includes/classes/bugs.php');
  include_once('../includes/classes/profile.php');

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

    $reponse = $bdd->query('SELECT id, film, to_delete FROM movie_house WHERE to_delete = "Y" ORDER BY id ASC');
    while($donnees = $reponse->fetch())
    {
      $myDelete = Movie::withData($donnees);

      // On ajoute la ligne au tableau
      array_push($listToDelete, $myDelete);
    }
    $reponse->closeCursor();

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

    $_SESSION['film_deleted'] = true;
  }

  // METIER : Réinitialise un film de la base
  // RETOUR : Aucun
  function resetFilm($id_film)
  {
    global $bdd;

    // Mise à jour de la table (remise à N de l'indicateur de demande)
    $to_delete = "N";

    $req = $bdd->prepare('UPDATE movie_house SET to_delete = :to_delete WHERE id = ' . $id_film);
    $req->execute(array(
      'to_delete' => $to_delete
    ));
    $req->closeCursor();

    $_SESSION['film_reseted'] = true;
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

    $_SESSION['calendar_deleted'] = true;
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

    $_SESSION['calendar_reseted'] = true;
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

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Tableau d'utilisateurs
  function getUsers()
  {
    // Initialisation tableau d'utilisateurs
    $listeUsers = array();

    global $bdd;

    $reponse = $bdd->query('SELECT id, identifiant, reset, pseudo, avatar FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
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
  // RETOUR : Tableau de nombres de commentaires & bilans des dépenses
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
      $nb_comments = 0;
      $bilan       = 0;

      // Nombre commentaires Movie House
      $req1 = $bdd->query('SELECT COUNT(id) AS nb_comments FROM movie_house_comments WHERE author = "' . $user->getIdentifiant() . '"');
      $data1 = $req1->fetch();

      $nb_comments = $data1['nb_comments'];

      $req1->closeCursor();

      // Bilan des dépenses
      $req2 = $bdd->query('SELECT * FROM expense_center ORDER BY id ASC');
      while($data2 = $req2->fetch())
      {
        // Prix d'achat
        $prix_achat = $data2['price'];

        // Identifiant de l'acheteur
        $acheteur   = $data2['buyer'];

        // Nombre de parts et prix par parts
        $nb_parts_total = 0;
        $nb_parts_user = 0;

        $req3 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense = ' . $data2['id']);
        while($data3 = $req3->fetch())
        {
          // Nombre de parts total
          $nb_parts_total += $data3['parts'];

          // Nombre de parts de l'utilisateur
          if ($user->getIdentifiant() == $data3['identifiant'])
            $nb_parts_user = $data3['parts'];
        }

        if ($nb_parts_total != 0)
          $prix_par_part = $prix_achat / $nb_parts_total;
        else
          $prix_par_part = 0;

        // On fait la somme des dépenses moins les parts consommées pour trouver le bilan
        if ($data2['buyer'] == $user->getIdentifiant())
          $bilan += $prix_achat - ($prix_par_part * $nb_parts_user);
        else
          $bilan -= $prix_par_part * $nb_parts_user;

        $req3->closeCursor();
      }
      $req2->closeCursor();

      $bilan_format = str_replace('.', ',', number_format($bilan, 2)) . ' €';

      $cat = array('identifiant'  => $user->getIdentifiant(),
                   'pseudo'       => $user->getPseudo(),
                   'nb_comments'  => $nb_comments,
                   'bilan'        => $bilan,
                   'bilan_format' => $bilan_format
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

      foreach($list_users as $user_ins)
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
			foreach($list_users as $user_ins)
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
				foreach($utilisateurs_desinscrits as $user_des)
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
    foreach($utilisateurs_desinscrits as $user_des)
    {
      // Nombre de commentaires Movie House
      $req3 = $bdd->query('SELECT COUNT(id) AS nb_comments FROM movie_house_comments WHERE author = "' . $user_des . '"');
      $data3 = $req3->fetch();

      $nb_comments = $data3['nb_comments'];

      $req3->closeCursor();

      // Calcul des bilans pour les utilisateurs désinscrits uniquement
      $bilan = 0;

      $req4 = $bdd->query('SELECT * FROM expense_center ORDER BY id ASC');
      while($data4 = $req4->fetch())
      {
        // Prix d'achat
        $prix_achat = $data4['price'];

        // Identifiant de l'acheteur
        $acheteur = $data4['buyer'];

        // Nombre de parts total et utilisateur
        $nb_parts_total = 0;
        $nb_parts_user = 0;

        $req5 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense = ' . $data4['id']);
        while($data5 = $req5->fetch())
        {
          // Nombre de parts total
          $nb_parts_total += $data5['parts'];

          // Nombre de parts de l'utilisateur
          if ($user_des == $data5['identifiant'])
            $nb_parts_user = $data5['parts'];
        }

        // Prix par parts
        if ($nb_parts_total != 0)
          $prix_par_part = $prix_achat / $nb_parts_total;
        else
          $prix_par_part = 0;

        // On fait la somme des dépenses moins les parts consommées pour trouver le bilan
        if ($data4['buyer'] == $user_des AND $nb_parts_user >= 0)
          $bilan        += $prix_achat - ($prix_par_part * $nb_parts_user);
          //$somme_bilans += $bilan;
        elseif ($data4['buyer'] != $user_des AND $nb_parts_user > 0)
          $bilan -= $prix_par_part * $nb_parts_user;
          //$somme_bilans += $bilan;

        $req5->closeCursor();
      }
      $req4->closeCursor();

      $bilan_format = str_replace('.', ',', number_format($bilan, 2)) . ' €';

      $cat = array('identifiant'  => $user_des,
                   'pseudo'       => '',
                   'nb_comments'  => $nb_comments,
                   'bilan'        => $bilan,
                   'bilan_format' => $bilan_format
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
    $nb_tot_commentaires = 0;
    $somme_bilans        = 0;
    $alerte_bilan        = false;

    global $bdd;

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

    $tabTotCat = array('nb_tot_commentaires' => $nb_tot_commentaires,
                       'somme_bilans'        => $somme_bilans,
                       'somme_bilans_format' => $somme_bilans_format,
                       'alerte_bilan'        => $alerte_bilan
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

    for($i=0; $i < $car; $i++)
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
    $chaine       = random_string(10);
    $mot_de_passe = htmlspecialchars(hash('sha1', $chaine . $salt));

    $req = $bdd->prepare('UPDATE users SET salt = :salt, mot_de_passe = :mot_de_passe, reset = :reset WHERE id = ' . $id_user);
    $req->execute(array(
      'salt'         => $salt,
      'mot_de_passe' => $mot_de_passe,
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

    // Supression utilisateur
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

    // Remise en cours des idées non terminées ou rejetées
    $status     = "O";
    $developper = "";

    $req4 = $bdd->prepare('UPDATE ideas SET status = :status, developper = :developper WHERE developper = "' . $identifiant . '" AND status != "D" AND status != "R"');
    $req4->execute(array(
      'status'     => $status,
      'developper' => $developper
    ));
    $req4->closeCursor();

    // Supression utilisateur
    $req5 = $bdd->exec('DELETE FROM users WHERE id = ' . $id_user . ' AND identifiant = "' . $identifiant . '"');
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
    unset ($post['saisie_autorisations']);

    global $bdd;

    $req = $bdd->query('SELECT * FROM preferences');
    while($data = $req->fetch())
    {
      // Par défaut, le top autorisation est à Non
      $manage_calendars = "N";

      if (!empty($post))
      {
        foreach ($post as $id => $ligne)
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

    $_SESSION['autorizations_updated'] = true;
  }
?>
