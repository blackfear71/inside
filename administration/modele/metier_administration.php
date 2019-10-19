<?php
  include_once('../includes/functions/appel_bdd.php');
  include_once('../includes/classes/alerts.php');
  include_once('../includes/classes/bugs.php');
  include_once('../includes/classes/calendars.php');
  include_once('../includes/classes/missions.php');
  include_once('../includes/classes/movies.php');
  include_once('../includes/classes/profile.php');
  include_once('../includes/classes/success.php');
  include_once('../includes/libraries/php/imagethumb.php');

  // METIER : Contrôle alertes utilisateurs
  // RETOUR : Booléen
  function getAlerteUsers()
  {
    $alert = false;

    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, pseudo, status FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
    while($data = $req->fetch())
    {
      if ($data['status'] == "Y" OR $data['status'] == "I" OR $data['status'] == "D")
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

  // METIER : Contrôle alertes Annexes
  // RETOUR : Booléen
  function getAlerteAnnexes()
  {
    $alert = false;

    global $bdd;

    $req = $bdd->query('SELECT id, to_delete FROM calendars_annexes WHERE to_delete = "Y"');
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
  function deleteFilm($post)
  {
    $id_film = $post['id_film'];

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
  function resetFilm($post)
  {
    $id_film = $post['id_film'];

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

  // METIER : Lecture des annexes à supprimer
  // RETOUR : Liste des annexes à supprimer
  function getAnnexesToDelete()
  {
    $listToDelete = array();

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM calendars_annexes WHERE to_delete = "Y" ORDER BY id DESC');
    while($donnees = $reponse->fetch())
    {
      $myDelete = Annexe::withData($donnees);

      // On ajoute la ligne au tableau
      array_push($listToDelete, $myDelete);
    }
    $reponse->closeCursor();

    return $listToDelete;
  }

  // METIER : Supprime un calendrier de la base
  // RETOUR : Aucun
  function deleteCalendrier($post)
  {
    $id_cal = $post['id_cal'];

    global $bdd;

    // On efface le calendrier si présent
    $reponse = $bdd->query('SELECT * FROM calendars WHERE id = ' . $id_cal);
    $donnees = $reponse->fetch();

    if (isset($donnees['calendar']) AND !empty($donnees['calendar']))
    {
      unlink ("../includes/images/calendars/" . $donnees['year'] . "/" . $donnees['calendar']);
      unlink ("../includes/images/calendars/" . $donnees['year'] . "/mini/" . $donnees['calendar']);
    }

    $reponse->closeCursor();

    // On efface la ligne de la base
    $reponse2 = $bdd->exec('DELETE FROM calendars WHERE id = ' . $id_cal);

    // Suppression des notifications
    deleteNotification('calendrier', $id_cal);

    $_SESSION['alerts']['calendar_deleted'] = true;
  }

  // METIER : Supprime une annexe de la base
  // RETOUR : Aucun
  function deleteAnnexe($post)
  {
    $id_annexe = $post['id_annexe'];

    global $bdd;

    // On efface l'annexe si présent
    $reponse = $bdd->query('SELECT * FROM calendars_annexes WHERE id = ' . $id_annexe);
    $donnees = $reponse->fetch();

    if (isset($donnees['annexe']) AND !empty($donnees['annexe']))
    {
      unlink ("../includes/images/calendars/annexes/" . $donnees['annexe']);
      unlink ("../includes/images/calendars/annexes/mini/" . $donnees['annexe']);
    }

    $reponse->closeCursor();

    // On efface la ligne de la base
    $reponse2 = $bdd->exec('DELETE FROM calendars_annexes WHERE id = ' . $id_annexe);

    $_SESSION['alerts']['annexe_deleted'] = true;
  }

  // METIER : Réinitialise un calendrier de la base
  // RETOUR : Aucun
  function resetCalendrier($post)
  {
    $id_cal = $post['id_cal'];

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

  // METIER : Réinitialise une annexe de la base
  // RETOUR : Aucun
  function resetAnnexe($post)
  {
    $id_annexe = $post['id_annexe'];

    global $bdd;

    // Mise à jour de la table (remise à N de l'indicateur de demande)
    $to_delete = "N";

    $req = $bdd->prepare('UPDATE calendars_annexes SET to_delete = :to_delete WHERE id = ' . $id_annexe);
    $req->execute(array(
      'to_delete' => $to_delete
    ));
    $req->closeCursor();

    $_SESSION['alerts']['annexe_reseted'] = true;
  }

  // METIER : Lecture liste des bugs / évolutions
  // RETOUR : Tableau des bugs / évolutions
  function getBugs($view, $type)
  {
    // Initialisation tableau des bugs
    $listeBugs = array();

    global $bdd;

    // Lecture de la base en fonction de la vue
    if ($view == "resolved")
      $reponse = $bdd->query('SELECT * FROM bugs WHERE type = "' . $type . '" AND (resolved = "Y" OR resolved = "R") ORDER BY date DESC, id DESC');
    elseif ($view == "unresolved")
      $reponse = $bdd->query('SELECT * FROM bugs WHERE type = "' . $type . '" AND resolved = "N" ORDER BY date DESC, id DESC');
    else
      $reponse = $bdd->query('SELECT * FROM bugs WHERE type = "' . $type . '" ORDER BY date DESC, id DESC');

    while ($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet Idea à partir des données remontées de la bdd
      $bug = Bugs::withData($donnees);

      // Recherche du pseudo et de l'avatar de l'auteur
      $reponse2 = $bdd->query('SELECT identifiant, pseudo, avatar FROM users WHERE identifiant = "' . $bug->getAuthor() . '"');
      $donnees2 = $reponse2->fetch();

      if (isset($donnees2['pseudo']) AND !empty($donnees2['pseudo']))
        $bug->setPseudo($donnees2['pseudo']);
      else
        $bug->setPseudo("Un ancien utilisateur");

      if (isset($donnees2['avatar']) AND !empty($donnees2['avatar']))
        $bug->setAvatar($donnees2['avatar']);

      $reponse2->closeCursor();

      array_push($listeBugs, $bug);
    }

    $reponse->closeCursor();

    return $listeBugs;
  }

  // METIER : Mise à jour du statut d'un bug
  // RETOUR : Top redirection
  function updateBug($post)
  {
    $id_report = $post['id_report'];
    $action    = $post;
    $resolved  = "N";

    unset($action['id_report']);

    global $bdd;

    // Lecture des données
    $req1 = $bdd->query('SELECT * FROM bugs WHERE id = ' . $id_report);
    $data1 = $req1->fetch();

    $author = $data1['author'];
    $status = $data1['resolved'];

    $req1->closeCursor();

    // Mise à jour du statut
    switch (key($action))
    {
      case 'resolve_bug':
        $resolved = "Y";
        break;

      case 'unresolve_bug':
        $resolved = "N";
        break;

      case 'reject_bug':
        $resolved = "R";
        break;

      default:
        break;
    }

    $req2 = $bdd->prepare('UPDATE bugs SET resolved = :resolved WHERE id = ' . $id_report);
    $req2->execute(array(
      'resolved' => $resolved
    ));
    $req2->closeCursor();

    // Génération succès (sauf si rejeté ou remis en cours après rejet)
    if ($resolved != "R" AND $status != "R")
    {
      if ($resolved == "Y")
        insertOrUpdateSuccesValue('compiler', $author, 1);
      else
        insertOrUpdateSuccesValue('compiler', $author, -1);
    }

    return $resolved;
  }

  // METIER : Suppression d'un bug
  // RETOUR : Aucun
  function deleteBug($post)
  {
    $id_report = $post['id_report'];

    global $bdd;

    // Lecture des données et suppression image si présente
    $req1 = $bdd->query('SELECT * FROM bugs WHERE id = ' . $id_report);
    $data1 = $req1->fetch();

    $author   = $data1['author'];
    $resolved = $data1['resolved'];

    if (isset($data1['picture']) AND !empty($data1['picture']))
      unlink ("../includes/images/reports/" . $data1['picture']);

    $req1->closeCursor();

    // Suppression de la table
    $req2 = $bdd->exec('DELETE FROM bugs WHERE id = ' . $id_report);

    // Génération succès
    insertOrUpdateSuccesValue('debugger', $author, -1);

    if ($resolved == "Y")
      insertOrUpdateSuccesValue('compiler', $author, -1);

    $_SESSION['alerts']['bug_deleted'] = true;
  }

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Tableau d'utilisateurs
  function getUsers()
  {
    // Initialisation tableau d'utilisateurs
    $listeUsers = array();

    global $bdd;

    $reponse = $bdd->query('SELECT id, identifiant, ping, status, pseudo, avatar, email, anniversary, experience FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
    while($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet User à partir des données remontées de la bdd
      $user = Profile::withData($donnees);

      // Récupération succès Beginner / Developper
      getSuccessAdmin($user);

      // On ajoute la ligne au tableau
      array_push($listeUsers, $user);
    }
    $reponse->closeCursor();

    return $listeUsers;
  }

  // METIER : Récupération des données de progression
  // RETOUR : Tableau des données de progression
  function getProgress($listUsers)
  {
    $listProgression = array();

    foreach ($listUsers as $user)
    {
      $experience = $user->getExperience();
      $niveau   = convertExperience($experience);
      $exp_min  = 10 * $niveau ** 2;
      $exp_max  = 10 * ($niveau + 1) ** 2;
      $exp_lvl  = $exp_max - $exp_min;
      $progress = $experience - $exp_min;
      $percent  = floor($progress * 100 / $exp_lvl);

      $listProgression[$user->getIdentifiant()] = $niveau;
    }

    return $listProgression;
  }

  // METIER : Lecture succès Beginner et Developper
  // RETOUR : Liste succès
  function getSuccessAdmin($user)
  {
    $listSuccess = array('beginning', 'developper');

    global $bdd;

    foreach ($listSuccess as $success)
    {
      $value = 0;

      // Lecture valeur succès
      $reponse = $bdd->query('SELECT * FROM success_users WHERE reference = "' . $success . '" AND identifiant = "' . $user->getIdentifiant() . '"');
      $donnees = $reponse->fetch();

      if ($reponse->rowCount() > 0)
        $value = $donnees['value'];

      $reponse->closeCursor();

      switch ($success)
      {
        case 'beginning':
          $user->setBeginner($value);
          break;

        case 'developper':
          $user->setDevelopper($value);
          break;

        default:
          break;
      }
    }
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

      $bilan_format = str_replace('.', ',', number_format($bilan, 2, ',', '')) . ' €';

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
    $somme_bilans_format = str_replace('.', ',', number_format($somme_bilans, 2, ',', '')) . ' €';

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

    for ($i = 0; $i < $car; $i++)
    {
      $string .= $chaine[rand()%strlen($chaine)];
    }

    return $string;
  }

  // METIER : Refus réinitialisation mot de passe
  // RETOUR : Aucun
  function resetOldPassword($post)
  {
    $id_user = $post['id_user'];

    global $bdd;

    // Mise à jour de la table (remise à N de l'indicateur de demande)
    $status = "N";

    $req = $bdd->prepare('UPDATE users SET status = :status WHERE identifiant = "' . $id_user . '"');
    $req->execute(array(
      'status' => $status
    ));
    $req->closeCursor();
  }

  // METIER : Réinitialisation mot de passe
  // RETOUR : Aucun
  function setNewPassword($post)
  {
    $id_user = $post['id_user'];

    global $bdd;

    // Mise à jour de la table (remise à N de l'indicateur de demande et du mot de passe)
    $status = "N";
    $salt  = rand();

    // On génère un nouveau mot de passe aléatoire
    $chaine   = random_string(10);
    $password = htmlspecialchars(hash('sha1', $chaine . $salt));

    $req1 = $bdd->prepare('UPDATE users SET salt = :salt, password = :password, status = :status WHERE identifiant = "' . $id_user . '"');
    $req1->execute(array(
      'salt'     => $salt,
      'password' => $password,
      'status'   => $status
    ));
    $req1->closeCursor();

    // Récupération pseudo
    $req2 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $id_user . '"');
    $data2 = $req2->fetch();

    $_SESSION['save']['user_ask_id']   = $id_user;
    $_SESSION['save']['user_ask_name'] = $data2['pseudo'];
    $_SESSION['save']['new_password']  = $chaine;

    $req2->closeCursor();
  }

  // METIER : Validation inscription (mise à jour du status utilisateur)
  // RETOUR : Aucun
  function acceptInscription($post)
  {
    $id_user = $post['id_user'];

    global $bdd;

    // On met simplement à jour le status de l'utilisateur
    $status = "N";

    $req = $bdd->prepare('UPDATE users SET status = :status WHERE identifiant = "' . $id_user . '"');
    $req->execute(array(
      'status' => $status
    ));
    $req->closeCursor();

    // Génération notification nouvel inscrit
    insertNotification('admin', 'inscrit', $id_user);
  }

  // METIER : Refus inscription
  // RETOUR : Aucun
  function resetInscription($post)
  {
    $id_user = $post['id_user'];

    global $bdd;

    // Suppression des préférences
    $req1 = $bdd->exec('DELETE FROM preferences WHERE identifiant = "' . $id_user . '"');

    // Suppression utilisateur
    $req2 = $bdd->exec('DELETE FROM users WHERE identifiant = "' . $id_user . '"');
  }

  // METIER : Validation désinscription
  // RETOUR : Aucun
  function acceptDesinscription($post)
  {
    $id_user = $post['id_user'];

    global $bdd;

    // Suppression des avis movie_house_users
    $req1 = $bdd->exec('DELETE FROM movie_house_users WHERE identifiant = "' . $id_user . '"');

    // Suppression des préférences
    $req2 = $bdd->exec('DELETE FROM preferences WHERE identifiant = "' . $id_user . '"');

    // Suppression des votes collector
    $req3 = $bdd->exec('DELETE FROM collector_users WHERE identifiant = "' . $id_user . '"');

    // Remise en cours des idées non terminées ou rejetées
    $status     = "O";
    $developper = "";

    $req4 = $bdd->prepare('UPDATE ideas SET status = :status, developper = :developper WHERE developper = "' . $id_user . '" AND status != "D" AND status != "R"');
    $req4->execute(array(
      'status'     => $status,
      'developper' => $developper
    ));
    $req4->closeCursor();

    // Suppression des missions
    $req5 = $bdd->exec('DELETE FROM missions_users WHERE identifiant = "' . $id_user . '"');

    // Suppression notification inscription
    deleteNotification('inscrit', $id_user);

    // Suppression des succès
    $req6 = $bdd->exec('DELETE FROM success_users WHERE identifiant = "' . $id_user . '"');

    // Suppression propositions restaurants
    $req7 = $bdd->exec('DELETE FROM food_advisor_users WHERE identifiant = "' . $id_user . '"');

    // Suppression déterminations restaurants
    $req8 = $bdd->exec('DELETE FROM food_advisor_choices WHERE caller = "' . $id_user . '"');

    // Suppression utilisateur
    $req9 = $bdd->exec('DELETE FROM users WHERE identifiant = "' . $id_user . '"');
  }

  // METIER : Refus désinscription
  // RETOUR : Aucun
  function resetDesinscription($post)
  {
    $id_user = $post['id_user'];

    global $bdd;

    $status = "N";

    $req = $bdd->prepare('UPDATE users SET status = :status WHERE identifiant = "' . $id_user . '"');
    $req->execute(array(
      'status' => $status
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
    $defined       = "N";
    $title         = $post['title'];
    $description   = $post['description'];
    $limit_success = $post['limit_success'];
    $explanation   = $post['explanation'];

    // Sauvegarde en cas d'erreur
    $_SESSION['save']['reference_success']   = $reference;
    $_SESSION['save']['level']               = $level;
    $_SESSION['save']['order_success']       = $order_success;
    $_SESSION['save']['title_success']       = $title;
    $_SESSION['save']['description_success'] = $description;
    $_SESSION['save']['limit_success']       = $limit_success;
    $_SESSION['save']['explanation_success'] = $explanation;

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
      // On contrôle la présence du dossier, sinon on le créé
      $dossier = "../includes/images/profil";

      if (!is_dir($dossier))
         mkdir($dossier);

      // On contrôle la présence du dossier des succès, sinon on le créé
      $dossier_succes = $dossier . '/success';

      if (!is_dir($dossier_succes))
         mkdir($dossier_succes);

      // Insertion image
      // Si on a bien une image
   		if ($files['success']['name'] != NULL)
   		{
   			// Dossier de destination
   			$success_dir = $dossier_succes . '/';

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
                                                        defined,
                                                        title,
                                                        description,
                                                        limit_success,
                                                        explanation)
                                                 VALUES(:reference,
                                                        :level,
                                                        :order_success,
                                                        :defined,
                                                        :title,
                                                        :description,
                                                        :limit_success,
                                                        :explanation)');
  				$reponse->execute(array(
  					'reference'     => $reference,
            'level'         => $level,
            'order_success' => $order_success,
            'defined'       => $defined,
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
  function deleteSuccess($post)
  {
    $id_success = $post['id_success'];

    global $bdd;

    // Suppression de l'image
    $req1 = $bdd->query('SELECT id, reference FROM success WHERE id = ' . $id_success);
    $data1 = $req1->fetch();

    if (isset($data1['reference']) AND !empty($data1['reference']))
    {
      $reference = $data1['reference'];
      unlink ("../includes/images/profil/success/" . $data1['reference'] . ".png");
    }

    $req1->closeCursor();

    // Suppression des données utilisateurs
    $req2 = $bdd->exec('DELETE FROM success_users WHERE reference = "' . $reference . '"');

    // Suppression du succès de la base
    $req3 = $bdd->exec('DELETE FROM success WHERE id = ' . $id_success);

    $_SESSION['alerts']['success_deleted'] = true;
  }


  // METIER : Modification succès
  // RETOUR : Aucun
  function updateSuccess($post)
  {
    $update     = array();
    $control_ok = true;
    $erreur     = NULL;

    // Sauvegarde en cas d'erreur
    $_SESSION['save']['save_success'] = $post;

    // Construction tableau pour mise à jour
    foreach ($post['id'] as $id)
    {
      $myUpdate = array('id'            => $post['id'][$id],
                        'level'         => $post['level'][$id],
                        'order_success' => $post['order_success'][$id],
                        'defined'       => $post['defined'][$id],
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
                                                 defined       = :defined,
                                                 title         = :title,
                                                 description   = :description,
                                                 limit_success = :limit_success,
                                                 explanation   = :explanation
                                           WHERE id = ' . $success['id']);
        $req->execute(array(
          'level'         => $success['level'],
          'order_success' => $success['order_success'],
          'defined'       => $success['defined'],
          'title'         => $success['title'],
          'description'   => $success['description'],
          'limit_success' => $success['limit_success'],
          'explanation'   => $success['explanation']
        ));
        $req->closeCursor();
      }

      // On quitte la boucle s'il y a une erreur
      if ($control_ok != true)
        break;
    }

    if ($control_ok != true)
      $erreur = true;
    else
      $_SESSION['alerts']['success_updated'] = true;

    return $erreur;
  }

  // METIER : Initialisation champs erreur modification succès
  // RETOUR : Tableau sauvegardé et trié
  function initModErrSucces($listSuccess, $session_succes)
  {
    foreach ($listSuccess as $success)
    {
      $success->setLevel($session_succes['level'][$success->getId()]);
      $success->setOrder_success($session_succes['order_success'][$success->getId()]);
      $success->setDefined($session_succes['defined'][$success->getId()]);
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
  function changeBeginner($post)
  {
    $identifiant = $post['user_infos'];
    $topBeginner = $post['top_infos'];

    if ($topBeginner == 1)
      $value = 0;
    else
      $value = 1;

    insertOrUpdateSuccesValue('beginning', $identifiant, $value);
  }

  // METIER : Modification top Developper
  // RETOUR : Aucun
  function changeDevelopper($post)
  {
    $identifiant   = $post['user_infos'];
    $topDevelopper = $post['top_infos'];

    if ($topDevelopper == 1)
      $value = 0;
    else
      $value = 1;

    insertOrUpdateSuccesValue('developper', $identifiant, $value);
  }

  // METIER : Initialisation des succès
  // RETOUR : Aucun
  function initializeSuccess($listSuccess, $listUsers)
  {
    global $bdd;

    if (!empty($listSuccess) AND !empty($listUsers))
    {
      // Boucle de traitement sur les utilisateurs
      foreach ($listUsers as $user)
      {
        // Boucle de traitement sur les succès
        foreach ($listSuccess as $success)
        {
          $value  = NULL;
          $action = NULL;

          /**************************************/
          /*** Détermination valeur à insérer ***/
          /**************************************/
          switch ($success->getReference())
          {
            // J'étais là
            case "beginning":
            // Je l'ai fait !
            case "developper":
              $req = $bdd->query('SELECT * FROM success_users WHERE reference = "' . $success->getReference() . '" AND identifiant = "' . $user->getIdentifiant() . '"');
              $data = $req->fetch();

              if ($req->rowCount() > 0)
                $value = $data['value'];

              $req->closeCursor();
              break;

            // Cinéphile amateur
            case "publisher":
              $nb_films_publies = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_films_publies FROM movie_house WHERE identifiant_add = "' . $user->getIdentifiant() . '" AND to_delete != "Y"');
              $data = $req->fetch();
              $nb_films_publies = $data['nb_films_publies'];
              $req->closeCursor();

              $value = $nb_films_publies;
              break;

            // Cinéphile professionnel
            case "viewer":
              $nb_films_vus = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_films_vus FROM movie_house_users WHERE identifiant = "' . $user->getIdentifiant() . '" AND participation = "S"');
              $data = $req->fetch();
              $nb_films_vus = $data['nb_films_vus'];
              $req->closeCursor();

              $value = $nb_films_vus;
              break;

            // Commentateur sportif
            case "commentator":
              $nb_commentaires_films = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_commentaires_films FROM movie_house_comments WHERE author = "' . $user->getIdentifiant() . '"');
              $data = $req->fetch();
              $nb_commentaires_films = $data['nb_commentaires_films'];
              $req->closeCursor();

              $value = $nb_commentaires_films;
              break;

            // Expert acoustique
            case "listener":
              $nb_collector_publiees = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_collector_publiees FROM collector WHERE author = "' . $user->getIdentifiant() . '"');
              $data = $req->fetch();
              $nb_collector_publiees = $data['nb_collector_publiees'];
              $req->closeCursor();

              $value = $nb_collector_publiees;
              break;

            // Dommage collatéral
            case "speaker":
              $nb_collector_speaker = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_collector_speaker FROM collector WHERE speaker = "' . $user->getIdentifiant() . '"');
              $data = $req->fetch();
              $nb_collector_speaker = $data['nb_collector_speaker'];
              $req->closeCursor();

              $value = $nb_collector_speaker;
              break;

            // Rigolo compulsif
            case "funny":
              $nb_collector_user = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_collector_user FROM collector_users WHERE identifiant = "' . $user->getIdentifiant() . '"');
              $data = $req->fetch();
              $nb_collector_user = $data['nb_collector_user'];
              $req->closeCursor();

              $value = $nb_collector_user;
              break;

            // Auto-satisfait
            case "self-satisfied":
              $nb_auto_voted = 0;

              $req = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_auto_voted
                                  FROM collector
                                  LEFT JOIN collector_users
                                  ON (collector.id = collector_users.id_collector AND collector_users.identifiant = "' . $user->getIdentifiant() . '")
                                  WHERE collector.speaker = "' . $user->getIdentifiant() . '"');
              $data = $req->fetch();
              $nb_auto_voted = $data['nb_auto_voted'];
              $req->closeCursor();

              $value = $nb_auto_voted;
              break;

            // Désigné volontaire
            case "buyer":
              $nb_buyer = 0;

              $req = $bdd->query('SELECT COUNT(expense_center.id) AS nb_buyer
                                  FROM expense_center
                                  WHERE (expense_center.buyer = "' . $user->getIdentifiant() . '" AND expense_center.price > 0)
                                  AND EXISTS (SELECT * FROM expense_center_users
                                              WHERE (expense_center.id = expense_center_users.id_expense))');
              $data = $req->fetch();
              $nb_buyer = $data['nb_buyer'];
              $req->closeCursor();

              $value = $nb_buyer;
              break;

            // Profiteur occasionnel
            case "eater":
              $nb_parts = 0;

              $req = $bdd->query('SELECT * FROM expense_center_users WHERE identifiant = "' . $user->getIdentifiant() . '"');
              while($data = $req->fetch())
              {
                $nb_parts += $data['parts'];
              }
              $req->closeCursor();

              $value = $nb_parts;
              break;

            // Mer il et fou !
            case "generous":
              $nb_expense_no_parts = 0;

              $req = $bdd->query('SELECT COUNT(expense_center.id) AS nb_expense_no_parts
                                  FROM expense_center
                                  WHERE (expense_center.buyer = "' . $user->getIdentifiant() . '" AND expense_center.price > 0)
                                  AND NOT EXISTS (SELECT * FROM expense_center_users
                                                  WHERE (expense_center.id = expense_center_users.id_expense
                                                  AND    expense_center_users.identifiant = "' . $user->getIdentifiant() . '"))');
              $data = $req->fetch();
              $nb_expense_no_parts = $data['nb_expense_no_parts'];
              $req->closeCursor();

              $value = $nb_expense_no_parts;
              break;

            // Economie de marché
            case "greedy":
              $bilan = 0;

              $req0 = $bdd->query('SELECT id, identifiant, expenses FROM users WHERE identifiant = "' . $user->getIdentifiant() . '"');
              $data0 = $req0->fetch();
              $bilan = $data0['expenses'];
              $req0->closeCursor();

              // Contrôle si total inférieur au total précédent
              $req1 = $bdd->query('SELECT * FROM success_users WHERE reference = "' . $success->getReference() . '" AND identifiant = "' . $user->getIdentifiant() . '"');
              $data1 = $req1->fetch();

              if ($req1->rowCount() > 0)
              {
                if ($bilan > $data1['value'])
                  $value = $bilan;
              }
              else
                $value = $bilan;

              $req1->closeCursor();
              break;

            // Génie créatif
            case "creator":
              $nb_idees_publiees = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_idees_publiees FROM ideas WHERE author = "' . $user->getIdentifiant() . '"');
              $data = $req->fetch();
              $nb_idees_publiees = $data['nb_idees_publiees'];
              $req->closeCursor();

              $value = $nb_idees_publiees;
              break;

            // Top développeur
            case "applier":
              $nb_idees_resolues = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_idees_resolues FROM ideas WHERE developper = "' . $user->getIdentifiant() . '" AND status = "D"');
              $data = $req->fetch();
              $nb_idees_resolues = $data['nb_idees_resolues'];
              $req->closeCursor();

              $value = $nb_idees_resolues;
              break;

            // Débugger aguerri
            case "debugger":
              $nb_bugs_publies = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_bugs_publies FROM bugs WHERE author = "' . $user->getIdentifiant() . '"');
              $data = $req->fetch();
              $nb_bugs_publies = $data['nb_bugs_publies'];
              $req->closeCursor();

              $value = $nb_bugs_publies;
              break;

            // Compilateur intégré
            case "compiler":
              $nb_bugs_resolus = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_bugs_resolus FROM bugs WHERE author = "' . $user->getIdentifiant() . '" AND resolved = "Y"');
              $data = $req->fetch();
              $nb_bugs_resolus = $data['nb_bugs_resolus'];
              $req->closeCursor();

              $value = $nb_bugs_resolus;
              break;

            // Véritable Jedi
            case "padawan":
              $star_wars_8 = 0;

              // Date de sortie du film
              $req0 = $bdd->query('SELECT id, date_theater FROM movie_house WHERE id = 16');
              $data0 = $req0->fetch();
              $date_sw8 = $data0['date_theater'];
              $req0->closeCursor();

              if (date("Ymd") >= $date_sw8)
              {
                // Participation utilisateur
                $req1 = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = 16 AND identifiant = "' . $user->getIdentifiant() . '" AND participation = "S"');
                $data1 = $req1->fetch();

                if ($req1->rowCount() > 0)
                  $star_wars_8 = 1;

                $req1->closeCursor();

                $value = $star_wars_8;
              }
              break;

            // Radar à bouffe
            case "restaurant-finder":
              $value = 0;
              break;

            case "star-chief":
              $nb_repas_organises = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_repas_organises FROM food_advisor_choices WHERE caller = "' . $user->getIdentifiant() . '"');
              $data = $req->fetch();
              $nb_repas_organises = $data['nb_repas_organises'];
              $req->closeCursor();

              $value = $nb_repas_organises;
              break;

            case "cooker":
              $nb_gateaux_realises = 0;

              $req = $bdd->query('SELECT COUNT(id) AS nb_gateaux_realises FROM cooking_box WHERE identifiant = "' . $user->getIdentifiant() . '" AND cooked = "Y"');
              $data = $req->fetch();
              $nb_gateaux_realises = $data['nb_gateaux_realises'];
              $req->closeCursor();

              $value = $nb_gateaux_realises;
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
            // Le plein de cadeaux !
            case "christmas2018":
            // C'est tout ce que j'ai ?!
            case "christmas2018_2":
              $mission = 0;

              if ($success->getReference() == "christmas2017" OR $success->getReference() == "christmas2017_2")
                $reference = "noel_2017";
              elseif ($success->getReference() == "golden-egg" OR $success->getReference() == "rainbow-egg")
                $reference = "paques_2018";
              elseif ($success->getReference() == "wizard" OR $success->getReference() == "wizard")
                $reference = "halloween_2018";
              elseif ($success->getReference() == "christmas2018" OR $success->getReference() == "christmas2018_2")
                $reference = "noel_2018";

              // Récupération Id mission et date de fin
              $req0 = $bdd->query('SELECT * FROM missions WHERE reference = "' . $reference . '"');
              $data0 = $req0->fetch();

              $id_mission = $data0['id'];
              $date_fin   = $data0['date_fin'];

              $req0->closeCursor();

              if (date('Ymd') > $date_fin)
              {
                // Nombre total d'objectifs sur la mission
                $req1 = $bdd->query('SELECT * FROM missions_users WHERE id_mission = ' . $id_mission . ' AND identifiant = "' . $user->getIdentifiant() . '"');
                while($data1 = $req1->fetch())
                {
                  $mission += $data1['avancement'];
                }
                $req1->closeCursor();
              }

              $value = $mission;
              break;

            default:
              break;
          }

          /****************************************/
          /*** Détermination action à effectuer ***/
          /****************************************/
          if (!is_null($value))
          {
            if ($value != 0)
            {
              $req2 = $bdd->query('SELECT * FROM success_users WHERE reference = "' . $success->getReference() . '" AND identifiant = "' . $user->getIdentifiant() . '"');
              $data2 = $req2->fetch();

              if ($req2->rowCount() > 0)
              {
                // On ne met à jour que si la nouvelle valeur est supérieure à l'ancienne
                if ($value > $data2['value'])
                  $action = 'update';
              }
              else
                $action = 'insert';

              $req2->closeCursor();
            }
          }

          /*************************************************/
          /*** Insertion / modification de chaque succès ***/
          /*************************************************/
          switch ($action)
          {
            case 'insert':
              $req3 = $bdd->prepare('INSERT INTO success_users(reference,
                                                               identifiant,
                                                               value)
                                                        VALUES(:reference,
                                                               :identifiant,
                                                               :value)');
              $req3->execute(array(
                'reference'   => $success->getReference(),
                'identifiant' => $user->getIdentifiant(),
                'value'       => $value
                ));
              $req3->closeCursor();
              break;

            case 'update':
              $req3 = $bdd->prepare('UPDATE success_users
                                     SET value = :value
                                     WHERE reference = "' . $success->getReference() . '" AND identifiant = "' . $user->getIdentifiant() . '"');
              $req3->execute(array(
                'value' => $value
              ));
              $req3->closeCursor();
              break;

            default:
              break;
          }

          /***************************************/
          /*** Purge éventuelle des succès à 0 ***/
          /***************************************/
          $req4 = $bdd->exec('DELETE FROM success_users WHERE value = 0');
        }
      }

      $_SESSION['alerts']['success_initialized'] = true;
    }
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

  // METIER : Mise à jour des informations
  // RETOUR : Aucun
  function updateInfos($user, $post)
  {
    global $bdd;

    // Récupération des données
    $pseudo = trim($post['pseudo']);

    // Mise à jour pseudo seulement si renseigné
    if (!empty($pseudo))
    {
      $req1 = $bdd->prepare('UPDATE users SET pseudo = :pseudo WHERE identifiant = "' . $user . '"');
      $req1->execute(array(
        'pseudo' => $pseudo
      ));
      $req1->closeCursor();

      // Mise à jour du pseudo stocké en SESSION
      $_SESSION['user']['pseudo'] = $pseudo;

      $_SESSION['alerts']['infos_updated'] = true;
    }
  }

  // METIER : Mise à jour de l'avatar (base + fichier)
  // RETOUR : Aucun
  function updateAvatar($user, $files)
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
          unlink ($avatar_dir . $donnees1['avatar'] . "");

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

      if (date('Ymd') < $myMission->getDate_deb() OR (date('Ymd') == $myMission->getDate_deb() AND date('His') < $myMission->getHeure()))
        $myMission->setStatut('V');
      elseif (((date('Ymd') == $myMission->getDate_deb() AND date('His') >= $myMission->getHeure()) OR date('Ymd') > $myMission->getDate_deb()) AND date('Ymd') <= $myMission->getDate_fin())
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

  // METIER : Initialisation ajout mission
  // RETOUR : Objets mission
  function initAddMission()
  {
    $mission = new Mission();
    return $mission;
  }

  // METIER : Récupération mission spécifique pour modification
  // RETOUR : Objet mission
  function initModMission($id)
  {
    $mission = new Mission();

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM missions WHERE id = ' . $id);
    $donnees = $reponse->fetch();

    $mission = Mission::withData($donnees);

    $reponse->closeCursor();

    return $mission;
  }

  // METIER : Initialisation mission en cas d'erreur de saisie (ajout et modification)
  // RETOUR : Objet mission
  function initErrMission($save, $id_mission)
  {
    $save_mission = new Mission();

    if (!empty($id_mission))
      $save_mission->setId($id_mission);

    $save_mission->setMission($save['mission']);
    $save_mission->setDate_deb($save['date_deb']);
    $save_mission->setDate_fin($save['date_fin']);
    $save_mission->setHeure($save['heures'] . $save['minutes'] . '00');
    $save_mission->setDescription($save['description']);
    $save_mission->setReference($save['reference']);
    $save_mission->setObjectif($save['objectif']);
    $save_mission->setExplications($save['explications']);
    $save_mission->setConclusion($save['conclusion']);

    return $save_mission;
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

  // METIER : Insertion d'une nouvelle mission
  // RETOUR : Erreur éventuelle
  function insertMission($post, $files)
  {
    global $bdd;

    // Récupération des données
    $mission      = $post['mission'];
    $date_deb     = $post['date_deb'];
    $date_fin     = $post['date_fin'];
    $heures       = $post['heures'];
    $minutes      = $post['minutes'];
    $description  = $post['description'];
    $reference    = $post['reference'];
    $objectif     = $post['objectif'];
    $explications = $post['explications'];
    $conclusion   = $post['conclusion'];

    // Sauvegarde des données
    $_SESSION['save']['new_mission'] = array('post' => $post, 'files' => $files);
    $control_ok                      = true;

    //var_dump($_SESSION['save']);
    //var_dump($_SESSION['save']['new_mission']['post']);
    //var_dump($_SESSION['save']['new_mission']['files']);

    // Remplacement des caractères spéciaux pour la référence
    $search    = array(" ", "é", "è", "ê", "ë", "à", "â", "ç", "ô", "û");
    $replace   = array("_", "e", "e", "e", "e", "a", "a", "c", "o", "u");
    $reference = str_replace($search, $replace, $reference);

    // Formatage heure
    $heure = $heures . $minutes . '00';

    // Contrôle référence unique
    $req1 = $bdd->query('SELECT * FROM missions WHERE reference = "' . $reference . '"');
    if ($req1->rowCount() > 0)
    {
      $_SESSION['alerts']['already_ref_mission'] = true;
      $control_ok                                = false;
    }
    $req1->closeCursor();

    // Contrôle format date début
    if ($control_ok == true)
    {
      if (validateDate($date_deb, "d/m/Y") != true)
      {
        $_SESSION['alerts']['wrong_date'] = true;
        $control_ok                       = false;
      }
      else
        $date_deb = formatDateForInsert($date_deb);
    }

    // Contrôle format date fin
    if ($control_ok == true)
    {
      if (validateDate($date_fin, "d/m/Y") != true)
      {
        $_SESSION['alerts']['wrong_date'] = true;
        $control_ok                       = false;
      }
      else
        $date_fin = formatDateForInsert($date_fin);
    }

    // Contrôle date début <= date fin
    if ($control_ok == true)
    {
      if ($date_fin < $date_deb)
      {
        $_SESSION['alerts']['date_less'] = true;
        $control_ok                      = false;
      }
    }

    // Contrôle objectif > 0
    if ($control_ok == true)
    {
      if (!is_numeric($objectif) OR $objectif <= 0)
      {
        $_SESSION['alerts']['objective_not_numeric'] = true;
        $control_ok                                  = false;
      }
    }

    // Contrôle images présentes
    if ($control_ok == true)
    {
      foreach ($files as $file)
      {
        if (empty($file['name']) OR $file['name'] == NULL)
        {
          $_SESSION['alerts']['missing_mission_file'] = true;
          $control_ok                                 = false;
        }
      }
    }

    // Insertion des images dans les dossiers
    if ($control_ok == true)
    {
      // On contrôle la présence du dossier des images, sinon on le créé
      $dossier = "../includes/images/missions";

      if (!is_dir($dossier))
        mkdir($dossier);

      // On contrôle la présence du dossier des bannières, sinon on le créé
      $dossier_images = $dossier . "/banners";

      if (!is_dir($dossier_images))
        mkdir($dossier_images);

      // On contrôle la présence du dossier des boutons, sinon on le créé
      $dossier_icones = $dossier . "/buttons";

      if (!is_dir($dossier_icones))
        mkdir($dossier_icones);

      foreach ($files as $key_file => $file)
      {
        // Dossier de destination
        if ($key_file == "mission_image")
          $dest_dir = $dossier_images . '/';
        else
          $dest_dir = $dossier_icones . '/';

        // Fichier
        $name_file = $file['name'];
        $tmp_file  = $file['tmp_name'];
        $size_file = $file['size'];
        $type_file = $file['type'];

        // Taille max
        $maxsize = 8388608; // 8Mo

        // Nouveau nom
        switch ($key_file)
        {
          case "mission_icone_g":
            $new_name = $reference . '_g';
            break;

          case "mission_icone_m":
            $new_name = $reference . '_m';
            break;

          case "mission_icone_d":
            $new_name = $reference . '_d';
            break;

          case "mission_image":
          default:
            $new_name = $reference;
            break;
        }

        // Si le fichier n'est pas trop grand
        if ($size_file < $maxsize)
        {
          // Contrôle fichier temporaire existant
          if (!is_uploaded_file($tmp_file))
          {
            $_SESSION['alerts']['wrong_file'] = true;
            $control_ok                       = false;
            // exit("Le fichier est introuvable");
          }

          // Contrôle type de fichier
          if (!strstr($type_file, 'png'))
          {
            $_SESSION['alerts']['wrong_file'] = true;
            $control_ok                       = false;
            // exit("Le fichier n'est pas une image valide");
          }

          // Contrôle upload (si tout est bon, l'image est envoyée)
          if (!move_uploaded_file($tmp_file, $dest_dir . $new_name . '.png'))
          {
            $_SESSION['alerts']['wrong_file'] = true;
            $control_ok                       = false;
            // exit("Impossible de copier le fichier dans $dest_dir");
          }

          /*if ($control_ok == true)
            echo "Le fichier a bien été uploadé";*/
        }
      }
    }

    // Insertion de l'enregistrement en base
    if ($control_ok == true)
    {
      $req2 = $bdd->prepare('INSERT INTO missions(mission,
                                                  reference,
                                                  date_deb,
                                                  date_fin,
                                                  heure,
                                                  objectif,
                                                  description,
                                                  explications,
                                                  conclusion)
                                          VALUES(:mission,
                                                 :reference,
                                                 :date_deb,
                                                 :date_fin,
                                                 :heure,
                                                 :objectif,
                                                 :description,
                                                 :explications,
                                                 :conclusion)');
      $req2->execute(array(
        'mission'      => $mission,
        'reference'    => $reference,
        'date_deb'     => $date_deb,
        'date_fin'     => $date_fin,
        'heure'        => $heure,
        'objectif'     => $objectif,
        'description'  => $description,
        'explications' => $explications,
        'conclusion'   => $conclusion
        ));
      $req2->closeCursor();

      $_SESSION['alerts']['mission_added'] = true;
    }

    if ($control_ok != true)
      $erreur_mission = true;
    else
      $erreur_mission = NULL;

    return $erreur_mission;
  }

  // METIER : Modification d'une mission existante
  // RETOUR : Id mission
  function updateMission($post, $files)
  {
    global $bdd;

    // Récupération des données
    $id_mission   = $post['id_mission'];
    $mission      = $post['mission'];
    $date_deb     = $post['date_deb'];
    $date_fin     = $post['date_fin'];
    $heures       = $post['heures'];
    $minutes      = $post['minutes'];
    $description  = $post['description'];
    $reference    = $post['reference'];
    $objectif     = $post['objectif'];
    $explications = $post['explications'];
    $conclusion   = $post['conclusion'];

    // Sauvegarde des données
    $_SESSION['save']['old_mission'] = array('post' => $post, 'files' => $files);
    $control_ok                      = true;

    //var_dump($_SESSION['save']);
    //var_dump($_SESSION['save']['old_mission']['post']);
    //var_dump($_SESSION['save']['old_mission']['files']);

    // Remplacement des caractères spéciaux pour la référence
    $search    = array(" ", "é", "è", "ê", "ë", "à", "â", "ç", "ô", "û");
    $replace   = array("_", "e", "e", "e", "e", "a", "a", "c", "o", "u");
    $reference = str_replace($search, $replace, $reference);

    // Formatage heure
    $heure = $heures . $minutes . '00';

    // Contrôle format date début
    if ($control_ok == true)
    {
      if (validateDate($date_deb, "d/m/Y") != true)
      {
        $_SESSION['alerts']['wrong_date'] = true;
        $control_ok                       = false;
      }
      else
        $date_deb = formatDateForInsert($date_deb);
    }

    // Contrôle format date fin
    if ($control_ok == true)
    {
      if (validateDate($date_fin, "d/m/Y") != true)
      {
        $_SESSION['alerts']['wrong_date'] = true;
        $control_ok                       = false;
      }
      else
        $date_fin = formatDateForInsert($date_fin);
    }

    // Contrôle date début <= date fin
    if ($control_ok == true)
    {
      if ($date_fin < $date_deb)
      {
        $_SESSION['alerts']['date_less'] = true;
        $control_ok                      = false;
      }
    }

    // Contrôle objectif > 0
    if ($control_ok == true)
    {
      if (!is_numeric($objectif) OR $objectif <= 0)
      {
        $_SESSION['alerts']['objective_not_numeric'] = true;
        $control_ok                                  = false;
      }
    }

    // Contrôle images présentes, si présentes alors on modifie l'image
    if ($control_ok == true)
    {
      foreach ($files as $key_file => $file)
      {
        if (!empty($file['name']) AND !$file['name'] == NULL)
        {
          // Chemins
          $dossier_images = "../includes/images/missions/banners";
          $dossier_icones = "../includes/images/missions/buttons";

          // Dossier de destination
          if ($key_file == "mission_image")
            $dest_dir = $dossier_images . '/';
          else
            $dest_dir = $dossier_icones . '/';

          // Fichier
          $name_file = $file['name'];
          $tmp_file  = $file['tmp_name'];
          $size_file = $file['size'];
          $type_file = $file['type'];

          // Taille max
          $maxsize = 8388608; // 8Mo

          // Nouveau nom
          switch ($key_file)
          {
            case "mission_icone_g":
              $new_name = $reference . '_g';
              break;

            case "mission_icone_m":
              $new_name = $reference . '_m';
              break;

            case "mission_icone_d":
              $new_name = $reference . '_d';
              break;

            case "mission_image":
            default:
              $new_name = $reference;
              break;
          }

          // Suppression ancienne image
          unlink ($dest_dir . $new_name . '.png');

          // Insertion nouvelle image
          if ($size_file < $maxsize)
          {
            // Contrôle fichier temporaire existant
            if (!is_uploaded_file($tmp_file))
            {
              $_SESSION['alerts']['wrong_file'] = true;
              $control_ok                       = false;
              // exit("Le fichier est introuvable");
            }

            // Contrôle type de fichier
            if (!strstr($type_file, 'png'))
            {
              $_SESSION['alerts']['wrong_file'] = true;
              $control_ok                       = false;
              // exit("Le fichier n'est pas une image valide");
            }

            // Contrôle upload (si tout est bon, l'image est envoyée)
            if (!move_uploaded_file($tmp_file, $dest_dir . $new_name . '.png'))
            {
              $_SESSION['alerts']['wrong_file'] = true;
              $control_ok                       = false;
              // exit("Impossible de copier le fichier dans $dest_dir");
            }

            /*if ($control_ok == true)
              echo "Le fichier a bien été uploadé";*/
          }
        }
      }
    }

    // Modification de l'enregistrement en base
    if ($control_ok == true)
    {
      $req2 = $bdd->prepare('UPDATE missions SET mission      = :mission,
                                                 date_deb     = :date_deb,
                                                 date_fin     = :date_fin,
                                                 heure        = :heure,
                                                 objectif     = :objectif,
                                                 description  = :description,
                                                 explications = :explications,
                                                 conclusion   = :conclusion
                                           WHERE id = ' . $id_mission);
      $req2->execute(array(
        'mission'      => $mission,
        'date_deb'     => $date_deb,
        'date_fin'     => $date_fin,
        'heure'        => $heure,
        'objectif'     => $objectif,
        'description'  => $description,
        'explications' => $explications,
        'conclusion'   => $conclusion
      ));
      $req2->closeCursor();

      $_SESSION['alerts']['mission_updated'] = true;
    }

    return $id_mission;
  }

  // METIER : Suppression d'une mission existante
  // RETOUR : Aucun
  function deleteMission($post)
  {
    $id_mission = $post['id_mission'];

    global $bdd;

    // Lecture référence mission
    $reponse = $bdd->query('SELECT id, reference FROM missions WHERE id = ' . $id_mission);
    $donnees = $reponse->fetch();
    $reference = $donnees['reference'];
    $reponse->closeCursor();

    // Suppression des images
    unlink ("../includes/images/missions/banners/" . $reference . ".png");
    unlink ("../includes/images/missions/buttons/" . $reference . "_g.png");
    unlink ("../includes/images/missions/buttons/" . $reference . "_m.png");
    unlink ("../includes/images/missions/buttons/" . $reference . "_d.png");

    // Suppression de la mission en table
    $reponse2 = $bdd->exec('DELETE FROM missions WHERE id = ' . $id_mission);

    // Suppression des participations en table
    $reponse3 = $bdd->exec('DELETE FROM missions_users WHERE id_mission = ' . $id_mission);

    // Suppression des notifications
    deleteNotification('start_mission', $id_mission);
    deleteNotification('end_mission', $id_mission);
    deleteNotification('one_mission', $id_mission);

    $_SESSION['alerts']['mission_deleted'] = true;
  }

  // METIER : Lecture des thèmes existants par type
  // RETOUR : Tableau des thèmes
  function getThemes($type)
  {
    $themes = array();

    global $bdd;

    // Lecture de la base des thèmes
    $reponse = $bdd->query('SELECT * FROM themes WHERE type = "' . $type . '" ORDER BY date_deb DESC, level ASC');

    while($donnees = $reponse->fetch())
    {
      $myTheme = Theme::withData($donnees);

      // On ajoute la ligne au tableau
      array_push($themes, $myTheme);
    }

    $reponse->closeCursor();

    return $themes;
  }

  // METIER : Insertion nouveau thème
  // RETOUR : Id enregistrement créé
  function insertTheme($post, $files)
  {
    global $bdd;

    // Sauvegarde en session en cas d'erreur
    $_SESSION['save']['theme_title']      = $post['theme_title'];
    $_SESSION['save']['theme_ref']        = $post['theme_ref'];

    if ($post['theme_type'] == "M")
    {
      $_SESSION['save']['theme_date_deb'] = $post['theme_date_deb'];
      $_SESSION['save']['theme_date_fin'] = $post['theme_date_fin'];
      $_SESSION['save']['theme_level']    = '';
    }
    else
    {
      $_SESSION['save']['theme_date_deb'] = '';
      $_SESSION['save']['theme_date_fin'] = '';
      $_SESSION['save']['theme_level']    = $post['theme_level'];
    }

    $new_id     = NULL;
    $control_ok = true;

    // Récupération des données
    $theme     = $post['theme_title'];
    $reference = $post['theme_ref'];
    $logo      = "N";
    $type      = $post['theme_type'];

    if ($type == "M")
    {
      $date_deb = $post['theme_date_deb'];
      $date_fin = $post['theme_date_fin'];
      $level    = '';
    }
    else
    {
      $date_deb = '';
      $date_fin = '';
      $level    = $post['theme_level'];
    }

    // Remplacement des caractères spéciaux pour la référence
    $search    = array(" ", "é", "è", "ê", "ë", "à", "â", "ç", "ô", "û");
    $replace   = array("_", "e", "e", "e", "e", "a", "a", "c", "o", "u");
    $reference = str_replace($search, $replace, $reference);

    // Contrôle référence unique
    $req1 = $bdd->query('SELECT * FROM themes WHERE reference = "' . $reference . '"');
    if ($req1->rowCount() > 0)
    {
      $_SESSION['alerts']['already_ref_theme'] = true;
      $control_ok                              = false;
    }
    $req1->closeCursor();

    if ($type == "M")
    {
      // Contrôle format date début
      if ($control_ok == true)
      {
        if (validateDate($date_deb, "d/m/Y") != true)
        {
          $_SESSION['alerts']['wrong_date'] = true;
          $control_ok                       = false;
        }
        else
          $date_deb = formatDateForInsert($date_deb);
      }

      // Contrôle format date fin
      if ($control_ok == true)
      {
        if (validateDate($date_fin, "d/m/Y") != true)
        {
          $_SESSION['alerts']['wrong_date'] = true;
          $control_ok                       = false;
        }
        else
          $date_fin = formatDateForInsert($date_fin);
      }

      // Contrôle date début <= date fin
      if ($control_ok == true)
      {
        if ($date_fin < $date_deb)
        {
          $_SESSION['alerts']['date_less'] = true;
          $control_ok                      = false;
        }
      }

      // Contrôle chevauchement dates
      if ($control_ok == true)
      {
        $conflict = false;
        $conflict = controlGeneratedTheme($date_deb, $date_fin, NULL);

        if ($conflict == true)
        {
          $_SESSION['alerts']['date_conflict'] = true;
          $control_ok                          = false;
        }
      }
    }
    else
    {
      // Contrôle niveau numérique
      if ($control_ok == true)
      {
        if (!is_numeric($level) OR $level < 0)
        {
          $_SESSION['alerts']['level_theme_numeric'] = true;
          $control_ok                                = false;
        }
      }
    }

    // Contrôle images présentes et indicateur présence logo
    if ($control_ok == true)
    {
      foreach ($files as $key_file => $file)
      {
        // Contrôle présence logo
        if ($key_file == "logo" AND !empty($file['name']) AND !empty($file['type']))
          $logo = "Y";

        // Contrôle présence autres fichiers
        if ($key_file != 'logo' AND (empty($file['name']) OR $file['name'] == NULL))
        {
          $_SESSION['alerts']['missing_theme_file'] = true;
          $control_ok                               = false;
        }
      }
    }

    // Insertion des images dans les dossiers
    if ($control_ok == true)
    {
      // On contrôle la présence du dossier des images, sinon on le créé
      $dossier = "../includes/images/themes";

      if (!is_dir($dossier))
        mkdir($dossier);

      // On contrôle la présence du dossier des entête, sinon on le créé
      $dossier_headers = $dossier . "/headers";

      if (!is_dir($dossier_headers))
        mkdir($dossier_headers);

      // On contrôle la présence du dossier des fonds, sinon on le créé
      $dossier_backgrounds = $dossier . "/backgrounds";

      if (!is_dir($dossier_backgrounds))
        mkdir($dossier_backgrounds);

      // On contrôle la présence du dossier des bas de page, sinon on le créé
      $dossier_footers = $dossier . "/footers";

      if (!is_dir($dossier_footers))
        mkdir($dossier_footers);

      // On contrôle la présence du dossier des logos, sinon on le créé
      $dossier_logos = $dossier . "/logos";

      if (!is_dir($dossier_logos))
        mkdir($dossier_logos);

      foreach ($files as $key_file => $file)
      {
        // Insertion logo si présent ou autre que logo
        if (($key_file =="logo" AND $logo == "Y") OR $key_file != "logo")
        {
          // Dossier de destination
          switch ($key_file)
          {
            case "header":
              $dest_dir = $dossier_headers . '/';
              break;

            case "footer":
              $dest_dir = $dossier_footers . '/';
              break;

            case "logo":
              $dest_dir = $dossier_logos . '/';
              break;

            case "background":
            default:
              $dest_dir = $dossier_backgrounds . '/';
              break;
          }

          // Fichier
          $name_file = $file['name'];
          $tmp_file  = $file['tmp_name'];
          $size_file = $file['size'];
          $type_file = $file['type'];

          // Taille max
          $maxsize = 8388608; // 8Mo

          // Nouveau nom
          switch ($key_file)
          {
            case "header":
              $new_name = $reference . '_h';
              break;

            case "footer":
              $new_name = $reference . '_f';
              break;

            case "logo":
              $new_name = $reference . '_l';
              break;

            case "background":
            default:
              $new_name = $reference;
              break;
          }

          // Si le fichier n'est pas trop grand
          if ($size_file < $maxsize)
          {
            // Contrôle fichier temporaire existant
            if (!is_uploaded_file($tmp_file))
            {
              $_SESSION['alerts']['wrong_file'] = true;
              $control_ok                       = false;
            }

            // Contrôle type de fichier
            if (!strstr($type_file, 'png'))
            {
              $_SESSION['alerts']['wrong_file'] = true;
              $control_ok                       = false;
            }

            // Contrôle upload (si tout est bon, l'image est envoyée)
            if (!move_uploaded_file($tmp_file, $dest_dir . $new_name . '.png'))
            {
              $_SESSION['alerts']['wrong_file'] = true;
              $control_ok                       = false;
            }
          }
        }
      }
    }

    // Insertion de l'enregistrement en base
    if ($control_ok == true)
    {
      $req2 = $bdd->prepare('INSERT INTO themes(reference,
                                                name,
                                                type,
                                                level,
                                                logo,
                                                date_deb,
                                                date_fin)
                                        VALUES(:reference,
                                               :name,
                                               :type,
                                               :level,
                                               :logo,
                                               :date_deb,
                                               :date_fin)');
      $req2->execute(array(
        'reference' => $reference,
        'name'      => $theme,
        'type'      => $type,
        'level'     => $level,
        'logo'      => $logo,
        'date_deb'  => $date_deb,
        'date_fin'  => $date_fin
        ));
      $req2->closeCursor();

      $new_id = $bdd->lastInsertId();

      $_SESSION['alerts']['theme_added'] = true;
    }

    return $new_id;
  }

  // METIER : Modification phrases cultes
  // RETOUR : Id thème
  function updateTheme($post)
  {
    global $bdd;

    $control_ok = true;

    $id_theme = $post['id_theme'];
    $theme    = $post['theme_title'];
    $type     = $post['theme_type'];

    if ($type == "M")
    {
      $date_deb = $post['theme_date_deb'];
      $date_fin = $post['theme_date_fin'];
      $level    = '';
    }
    else
    {
      $date_deb = '';
      $date_fin = '';
      $level    = $post['theme_level'];
    }

    if ($type == "M")
    {
      // Contrôle format date début
      if ($control_ok == true)
      {
        if (validateDate($date_deb, "d/m/Y") != true)
        {
          $_SESSION['alerts']['wrong_date'] = true;
          $control_ok                       = false;
        }
        else
          $date_deb = formatDateForInsert($date_deb);
      }

      // Contrôle format date fin
      if ($control_ok == true)
      {
        if (validateDate($date_fin, "d/m/Y") != true)
        {
          $_SESSION['alerts']['wrong_date'] = true;
          $control_ok                       = false;
        }
        else
          $date_fin = formatDateForInsert($date_fin);
      }

      // Contrôle date début <= date fin
      if ($control_ok == true)
      {
        if ($date_fin < $date_deb)
        {
          $_SESSION['alerts']['date_less'] = true;
          $control_ok                      = false;
        }
      }

      // Contrôle chevauchement dates
      if ($control_ok == true)
      {
        $conflict = false;
        $conflict = controlGeneratedTheme($date_deb, $date_fin, $id_theme);

        if ($conflict == true)
        {
          $_SESSION['alerts']['date_conflict'] = true;
          $control_ok                          = false;
        }
      }
    }
    else
    {
      // Contrôle niveau numérique
      if ($control_ok == true)
      {
        if (!is_numeric($level) OR $level < 0)
        {
          $_SESSION['alerts']['level_theme_numeric'] = true;
          $control_ok                                = false;
        }
      }
    }

    // Modification de l'enregistrement en base
    if ($control_ok == true)
    {
      $req = $bdd->prepare('UPDATE themes SET name     = :name,
                                              type     = :type,
                                              level    = :level,
                                              date_deb = :date_deb,
                                              date_fin = :date_fin
                                        WHERE id       = ' . $id_theme);
      $req->execute(array(
        'name'     => $theme,
        'type'     => $type,
        'level'    => $level,
        'date_deb' => $date_deb,
        'date_fin' => $date_fin
      ));
      $req->closeCursor();

      $_SESSION['alerts']['theme_updated'] = true;
    }

    return $id_theme;
  }

  // METIER : Suppression thème
  // RETOUR : Aucun
  function deleteTheme($post)
  {
    $id_theme = $post['id_theme'];

    global $bdd;

    // Suppression images
    $req1 = $bdd->query('SELECT id, reference, logo FROM themes WHERE id = ' . $id_theme);
    $data1 = $req1->fetch();

    if (isset($data1['reference']) AND !empty($data1['reference']))
    {
      unlink ("../includes/images/themes/headers/" . $data1['reference'] . "_h.png");
      unlink ("../includes/images/themes/backgrounds/" . $data1['reference'] . ".png");
      unlink ("../includes/images/themes/footers/" . $data1['reference'] . "_f.png");

      if ($data1['logo'] == "Y")
        unlink ("../includes/images/themes/logos/" . $data1['reference'] . "_l.png");
    }

    $req1->closeCursor();

    // Suppression enregistrement base
    $req2 = $bdd->exec('DELETE FROM themes WHERE id = ' . $id_theme);

    // Message d'alerte
    $_SESSION['alerts']['theme_deleted'] = true;
  }

  // METIER : Contrôle dates thème non superposées
  // RETOUR : Booléen
  function controlGeneratedTheme($date_deb, $date_fin, $id_theme)
  {
    global $bdd;

    $conflict = false;

    if (!empty($id_theme))
      $reponse = $bdd->query('SELECT * FROM themes WHERE id != ' . $id_theme . ' AND type = "M" ORDER BY date_deb DESC ');
    else
      $reponse = $bdd->query('SELECT * FROM themes WHERE type = "M" ORDER BY date_deb DESC');

    while($donnees = $reponse->fetch())
    {
      if (($date_deb >= $donnees['date_deb'] AND $date_deb <= $donnees['date_fin'])
      OR  ($date_fin >= $donnees['date_deb'] AND $date_fin <= $donnees['date_fin'])
      OR  ($date_deb <= $donnees['date_deb'] AND $date_fin >= $donnees['date_fin']))
      {
        $conflict = true;
        break;
      }
    }

    $reponse->closeCursor();

    return $conflict;
  }

  // METIER : Liste des messages d'alerte
  // RETOUR : Messages d'alerte
  function getAlerts()
  {
    $alerts = array();

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM alerts ORDER BY category ASC, type DESC, alert ASC');
    while($donnees = $reponse->fetch())
    {
      $myAlert = Alerte::withData($donnees);

      // On ajoute la ligne au tableau
      array_push($alerts, $myAlert);
    }
    $reponse->closeCursor();

    return $alerts;
  }

  // METIER : Insertion d'une alerte
  // RETOUR : Id alerte créée
  function insertAlert($post)
  {
    $type      = $post['type_alert'];
    $category  = $post['category_alert'];
    $reference = $post['reference_alert'];
    $message   = $post['message_alert'];

    // Sauvegarde en cas d'erreur
    $_SESSION['save']['type_alert']      = $type;
    $_SESSION['save']['category_alert']  = $category;
    $_SESSION['save']['reference_alert'] = $reference;
    $_SESSION['save']['message_alert']   = $message;

    $new_id     = NULL;
    $control_ok = true;

    global $bdd;

    // Contrôle référence
    $reponse = $bdd->query('SELECT * FROM alerts WHERE alert = "' . $reference . '"');
    $donnees = $reponse->fetch();

    if ($reponse->rowCount() > 0)
    {
      $control_ok = false;
      $_SESSION['alerts']['already_referenced'] = true;
    }

    $reponse->closeCursor();

    // Si contrôles ok, insertion table
    if ($control_ok == true)
    {
      $reponse2 = $bdd->prepare('INSERT INTO alerts(category,
                                                    type,
                                                    alert,
                                                    message)
                                             VALUES(:category,
                                                    :type,
                                                    :alert,
                                                    :message)');
      $reponse2->execute(array(
        'category' => $category,
        'type'     => $type,
        'alert'    => $reference,
        'message'  => $message
        ));
      $reponse2->closeCursor();

      $new_id = $bdd->lastInsertId();
      $_SESSION['alerts']['alert_added'] = true;
    }

    return $new_id;
  }

  // METIER : Modification d'une alerte
  // RETOUR : Id alerte
  function updateAlert($post, $id_alert)
  {
    $id_alert  = $post['id_alert'];
    $type      = $post['type_alert'];
    $category  = $post['category_alert'];
    $reference = $post['reference_alert'];
    $message   = $post['message_alert'];

    $control_ok = true;

    global $bdd;

    // Contrôle référence
    $reponse = $bdd->query('SELECT * FROM alerts WHERE alert = "' . $reference . '" AND id != ' . $id_alert);
    $donnees = $reponse->fetch();

    if ($reponse->rowCount() > 0)
    {
      $control_ok = false;
      $_SESSION['alerts']['already_referenced'] = true;
    }

    $reponse->closeCursor();

    // Si contrôles ok, modification table
    if ($control_ok == true)
    {
      $reponse2 = $bdd->prepare('UPDATE alerts SET category = :category,
                                                   type     = :type,
                                                   alert    = :alert,
                                                   message  = :message
                                             WHERE id = ' . $id_alert);
      $reponse2->execute(array(
        'category' => $category,
        'type'     => $type,
        'alert'    => $reference,
        'message'  => $message
      ));
      $reponse2->closeCursor();

      $_SESSION['alerts']['alert_updated'] = true;
    }

    return $id_alert;
  }

  // METIER : Suppression d'une alerte
  // RETOUR : Aucun
  function deleteAlert($post)
  {
    $id_alert = $post['id_alert'];

    global $bdd;

    // Suppression de l'alerte de la base
    $reponse = $bdd->exec('DELETE FROM alerts WHERE id = ' . $id_alert);

    $_SESSION['alerts']['alert_deleted'] = true;
  }
?>
