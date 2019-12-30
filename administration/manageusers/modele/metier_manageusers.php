<?php
  include_once('../../includes/functions/appel_bdd.php');

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
    $chaine   = generateRandomString(10);
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

  // METIER : Génération nouveau mot de passe
  // RETOUR : Mot de passe aléatoire
  function generateRandomString($car)
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
    $id_user    = $post['id_user'];
    $control_ok = true;

    global $bdd;

    // Récupération des données utilisateur
    $req0 = $bdd->query('SELECT id, identifiant, pseudo, avatar, expenses FROM users WHERE identifiant = "' . $id_user . '"');
    $data0 = $req0->fetch();

    $pseudo   = $data0['pseudo'];
    $avatar   = $data0['avatar'];
    $expenses = $data0['expenses'];

    $req0->closeCursor();

    // Contrôle dépenses nulles
    if ($expenses > 0.01 OR $expenses < -0.01)
    {
      $_SESSION['alerts']['expenses_not_null'] = true;
      $control_ok                              = false;
    }

    // Enregistrement du pseudo dans les phrases cultes (speaker avec passage à "other")
    if ($control_ok == true)
    {
      $req1 = $bdd->prepare('UPDATE collector SET speaker = :speaker, type_speaker = :type_speaker WHERE speaker = "' . $id_user . '"');
      $req1->execute(array(
        'speaker'      => substr($pseudo, 0, 100),
        'type_speaker' => "other"
      ));
      $req1->closeCursor();
    }

    // Remise en cours des idées non terminées ou rejetées
    if ($control_ok == true)
    {
      $status     = "O";
      $developper = "";

      $req2 = $bdd->prepare('UPDATE ideas SET status = :status, developper = :developper WHERE developper = "' . $id_user . '" AND status != "D" AND status != "R"');
      $req2->execute(array(
        'status'     => $status,
        'developper' => $developper
      ));
      $req2->closeCursor();
    }

    // Suppressions
    if ($control_ok == true)
    {
      // Suppression des avis movie_house_users
      $req3 = $bdd->exec('DELETE FROM movie_house_users WHERE identifiant = "' . $id_user . '"');

      // Suppression des votes collector
      $req4 = $bdd->exec('DELETE FROM collector_users WHERE identifiant = "' . $id_user . '"');

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

      // Suppression semaines gâteau
      $req9 = $bdd->exec('DELETE FROM cooking_box WHERE (year > ' . date("Y") . ' OR (year = ' . date("Y") . ' AND week > ' . date("W") . ')) AND identifiant = "' . $id_user . '"');

      // Suppression avatar
      unlink ('../../includes/images/profil/avatars/' . $avatar);

      // Suppression des préférences
      $req10 = $bdd->exec('DELETE FROM preferences WHERE identifiant = "' . $id_user . '"');

      // Suppression utilisateur
      $req11 = $bdd->exec('DELETE FROM users WHERE identifiant = "' . $id_user . '"');
    }
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
?>
