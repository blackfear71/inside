<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/collectors.php');
  include_once('../../includes/classes/profile.php');

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Tableau d'utilisateurs
  function getUsers()
  {
    // Initialisation tableau d'utilisateurs
    $listeUsers = array();

    global $bdd;

    $reponse = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant != "admin"  AND status != "I" ORDER BY identifiant ASC');
    while($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet User à partir des données remontées de la bdd
      $user = Profile::withData($donnees);

      // On construit un tableau des utilisateurs
      $myUser = array('id'          => $user->getId(),
                      'identifiant' => $user->getIdentifiant(),
                      'pseudo'      => $user->getPseudo(),
                      'avatar'      => $user->getAvatar()
                    );

      // On ajoute la ligne au tableau
      array_push($listeUsers, Profile::withData($myUser));
    }
    $reponse->closeCursor();

    return $listeUsers;
  }

  // METIER : Lecture nombre de pages en fonction du filtre
  // RETOUR : Nombre de pages
  function getPages($filtre, $identifiant)
  {
    $nb_pages     = 0;
    $nb_col       = 0;
    $nb_par_page  = 18;
    $min_golden   = 6;

    global $bdd;

    // Calcul du nombre total d'enregistrements pour chaque filtre
    switch ($filtre)
    {
      case "noVote":
        $req = $bdd->query('SELECT COUNT(collector.id)
                            AS nb_col
                            FROM collector
                            WHERE NOT EXISTS (SELECT id, id_collector, identifiant
                                              FROM collector_users
                                              WHERE (collector.id = collector_users.id_collector
                                              AND    collector_users.identifiant = "' . $identifiant . '"))');

        break;

      case "meOnly":
        $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector WHERE speaker = "' . $identifiant . '"');
        break;

      case "byMe":
        $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector WHERE author = "' . $identifiant . '"');
        break;

      case "usersOnly":
        $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector WHERE (type_speaker = "user" AND speaker != "' . $identifiant . '")');
        break;

      case "othersOnly":
        $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector WHERE type_speaker = "other"');
        break;

      case "textOnly":
        $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector WHERE type_collector = "T"');
        break;

      case "picturesOnly":
        $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector WHERE type_collector = "I"');
        break;

      case "topCulte":
        $req = $bdd->query('SELECT COUNT(collector.id)
                            AS nb_col
                            FROM collector
                            WHERE (SELECT COUNT(collector_users.id)
                                   FROM collector_users
                                   WHERE collector_users.id_collector = collector.id) >= ' . $min_golden);
        break;

      case "none":
      default:
        $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector');
        break;
    }

    $data = $req->fetch();

    if (isset($data['nb_col']))
      $nb_col = $data['nb_col'];

    $req->closeCursor();

    $nb_pages = ceil($nb_col / $nb_par_page);

    return $nb_pages;
  }

  // METIER : Lecture des phrases cultes
  // RETOUR : Liste phrases cultes
  function getCollectors($listUsers, $nb_pages, $page, $identifiant, $tri, $filtre)
  {
    $listCollectors = array();
    $nb_par_page    = 18;
    $min_golden     = 6;

    // Contrôle dernière page
    if ($page > $nb_pages)
      $page = $nb_pages;

    // Calcul première entrée
    $premiere_entree = ($page - 1) * $nb_par_page;

    // Détermination sens tri
    switch ($tri)
    {
      case "dateAsc":
        $order = "collector.date_collector ASC, collector.id ASC";
        break;

      case "dateDesc":
      default:
        $order = "collector.date_collector DESC, collector.id DESC";
        break;
    }

    // Lecture des enregistrements en fonction du filtre
    global $bdd;

    switch ($filtre)
    {
      case "noVote":
        $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE NOT EXISTS (SELECT id, id_collector, identifiant
                                                  FROM collector_users
                                                  WHERE (collector.id = collector_users.id_collector
                                                  AND    collector_users.identifiant = "' . $identifiant . '"))
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiere_entree . ', ' . $nb_par_page);

        break;

      case "meOnly":
        $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE (collector.speaker = "' . $identifiant . '")
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiere_entree . ', ' . $nb_par_page);
        break;

      case "byMe":
        $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE (collector.author = "' . $identifiant . '")
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiere_entree . ', ' . $nb_par_page);
        break;

      case "usersOnly":
        $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE (collector.type_speaker = "user" AND collector.speaker != "' . $identifiant . '")
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiere_entree . ', ' . $nb_par_page);
        break;

      case "othersOnly":
        $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE (collector.type_speaker = "other")
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiere_entree . ', ' . $nb_par_page);
        break;

      case "textOnly":
        $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE (collector.type_collector = "T")
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiere_entree . ', ' . $nb_par_page);
        break;

      case "picturesOnly":
        $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE (collector.type_collector = "I")
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiere_entree . ', ' . $nb_par_page);
        break;

      case "topCulte":
        $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE (SELECT COUNT(collector_users.id)
                                       FROM collector_users
                                       WHERE collector_users.id_collector = collector.id) >= ' . $min_golden . '
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiere_entree . ', ' . $nb_par_page);
        break;

      case "none":
      default:
        $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiere_entree . ', ' . $nb_par_page);
        break;
    }

    while($donnees = $reponse->fetch())
    {
      $myCollector = Collector::withData($donnees);

      // Nombre de votes
      $myCollector->setNb_votes($donnees['nb_votes']);

      // Recherche pseudo
      foreach ($listUsers as $user)
      {
        if ($myCollector->getAuthor() == $user->getIdentifiant())
        {
          $myCollector->setName_a($user->getPseudo());
        }

        if ($myCollector->getSpeaker() == $user->getIdentifiant())
        {
          $myCollector->setName_s($user->getPseudo());
          $myCollector->setAvatar_s($user->getAvatar());
        }
      }

      // Auteur "autre"
      if (!empty($myCollector->getSpeaker()) AND $myCollector->getType_s() == "other")
      {
        $myCollector->setName_s($myCollector->getSpeaker());
        $myCollector->setSpeaker("other");
      }

      // Si pas de pseudo "auteur"
      if (empty($myCollector->getName_a()) AND $myCollector->getType_s() == "user")
      {
        $myCollector->setAuthor("");
        $myCollector->setName_a("un ancien utilisateur");
      }

      // Si pas de pseudo "speaker"
      if (empty($myCollector->getName_s())AND $myCollector->getType_s() == "user")
      {
        $myCollector->setSpeaker("");
        $myCollector->setName_s("un ancien utilisateur");
      }

      array_push($listCollectors, $myCollector);
    }
    $reponse->closeCursor();

    return $listCollectors;
  }

  // METIER : Insertion phrases cultes
  // RETOUR : Id enregistrement créé
  function insertCollector($post, $files, $user)
  {
    $new_id     = NULL;
    $control_ok = true;

    // Sauvegarde en session en cas d'erreur
    $_SESSION['save']['speaker']        = $post['speaker'];
    $_SESSION['save']['other_speaker']  = $post['other_speaker'];
    $_SESSION['save']['date_collector'] = $post['date_collector'];
    $_SESSION['save']['type_collector'] = $post['type_collector'];
    $_SESSION['save']['context']        = $post['context'];

    if ($post['type_collector'] == "T")
      $_SESSION['save']['collector']    = $post['collector'];

    // On contrôle la date
    if ($control_ok == true)
    {
      $date_a_verifier = $post['date_collector'];

      if (validateDate($date_a_verifier, "d/m/Y") != true)
      {
        $_SESSION['alerts']['wrong_date'] = true;
        $control_ok                       = false;
      }
    }

    if ($control_ok == true)
    {
      //Formatage du texte ou insertion image
      if ($post['type_collector'] == "T")
        $collector = deleteInvisible($post['collector']);
      elseif ($post['type_collector'] == "I")
        $collector = uploadImage($files, rand());

      global $bdd;

      if ($post['speaker'] == "other")
      {
        $collector = array('date_add'       => date("Ymd"),
                           'author'         => $user,
                           'speaker'        => $post['other_speaker'],
                           'type_speaker'   => $post['speaker'],
                           'date_collector' => formatDateForInsert($date_a_verifier),
                           'type_collector' => $post['type_collector'],
                           'collector'      => $collector,
                           'context'        => deleteInvisible($post['context'])
                          );
      }
      else
      {
        $collector = array('date_add'       => date("Ymd"),
                           'author'         => $user,
                           'speaker'        => $post['speaker'],
                           'type_speaker'   => "user",
                           'date_collector' => formatDateForInsert($date_a_verifier),
                           'type_collector' => $post['type_collector'],
                           'collector'      => $collector,
                           'context'        => deleteInvisible($post['context'])
                          );
      }

			// Stockage de l'enregistrement en table
      $req = $bdd->prepare('INSERT INTO collector(date_add,
                                                  author,
																									speaker,
                                                  type_speaker,
																									date_collector,
                                                  type_collector,
																									collector,
                                                  context
                                                 )
																			     VALUES(:date_add,
                                                  :author,
																									:speaker,
                                                  :type_speaker,
																								  :date_collector,
                                                  :type_collector,
																								  :collector,
                                                  :context
                                                 )');
      $req->execute($collector);
		  $req->closeCursor();

      // Génération notification phrase culte ajoutée
      $new_id = $bdd->lastInsertId();

      if ($post['type_collector'] == "T")
        insertNotification($user, 'culte', $new_id);
      elseif ($post['type_collector'] == "I")
        insertNotification($user, 'culte_image', $new_id);

      // Génération succès
      insertOrUpdateSuccesValue('listener', $user, 1);

      if ($post['speaker'] != "other")
        insertOrUpdateSuccesValue('speaker', $post['speaker'], 1);

      // Ajout expérience
      insertExperience($user, 'add_collector');

      // Message d'alerte
      if ($post['type_collector'] == "T")
        $_SESSION['alerts']['collector_added'] = true;
      elseif ($post['type_collector'] == "I")
        $_SESSION['alerts']['image_collector_added'] = true;
    }

    return $new_id;
  }

  // METIER : Formatage et insertion image Collector
  // RETOUR : Nom fichier avec extension
  function uploadImage($files, $name)
  {
    $new_name = "";

    // On contrôle la présence du dossier, sinon on le créé
    $dossier = "../../includes/images/collector";

    if (!is_dir($dossier))
      mkdir($dossier);

    // Si on a bien une image
 		if ($files['image']['name'] != NULL)
 		{
 			// Dossier de destination
 			$image_dir = $dossier . '/';

 			// Données du fichier
 			$file      = $files['image']['name'];
 			$tmp_file  = $files['image']['tmp_name'];
 			$size_file = $files['image']['size'];
      $maxsize   = 8388608; // 8Mo

      // Si le fichier n'est pas trop grand
 			if ($size_file < $maxsize)
 			{
 				// Contrôle fichier temporaire existant
 				if (!is_uploaded_file($tmp_file))
 					exit("Le fichier est introuvable");

 				// Contrôle type de fichier
 				$type_file = $files['image']['type'];

 				if (!strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp') && !strstr($type_file, 'gif') && !strstr($type_file, 'png'))
 					exit("Le fichier n'est pas une image valide");
 				else
 				{
 					$type_image = pathinfo($file, PATHINFO_EXTENSION);
 					$new_name   = $name . '.' . $type_image;
 				}

 				// Contrôle upload (si tout est bon, l'image est envoyée)
 				if (!move_uploaded_file($tmp_file, $image_dir . $new_name))
 					exit("Impossible de copier le fichier dans $image_dir");

 				// echo "Le fichier a bien été uploadé";

        // Rotation de l'image
        $rotate = rotateImage($image_dir . $new_name, $type_image);
 			}
 		}

    return $new_name;
  }

  // METIER : Suppression phrases cultes
  // RETOUR : Aucun
  function deleteCollector($id_col)
  {
    global $bdd;

    // Suppression image
    $req1 = $bdd->query('SELECT id, type_collector, collector FROM collector WHERE id = ' . $id_col);
    $data1 = $req1->fetch();

    $collector      = $data1['collector'];
    $type_collector = $data1['type_collector'];

    if (isset($collector) AND !empty($collector) AND $type_collector == "I")
      unlink ("../../includes/images/collector/" . $data1['collector']);

    $req1->closeCursor();

    // Suppression enregistrement base
    $req2 = $bdd->exec('DELETE FROM collector WHERE id = ' . $id_col);

    // Suppression des notifications
    if ($type_collector == "T")
      deleteNotification('culte', $id_col);
    elseif ($type_collector == "I")
      deleteNotification('culte_image', $id_col);

    // Message d'alerte
    if ($type_collector == "T")
      $_SESSION['alerts']['collector_deleted'] = true;
    elseif ($type_collector == "I")
      $_SESSION['alerts']['image_collector_deleted'] = true;
  }

  // METIER : Modification phrases cultes
  // RETOUR : Aucun
  function updateCollector($post, $files, $id_col)
  {
    $control_ok = true;

    global $bdd;

    // On contrôle la date
    if ($control_ok == true)
    {
      $date_a_verifier = $post['date_collector'];

      if (validateDate($date_a_verifier, "d/m/Y") != true)
      {
        $_SESSION['alerts']['wrong_date'] = true;
        $control_ok                       = false;
      }
    }

    // Suppression ancienne image ou récupération collector
    if ($control_ok == true)
    {
      if ($post['type_collector'] == "I")
      {
        // On récupère le nom de l'image existante
        $reponse = $bdd->query('SELECT id, type_collector, collector FROM collector WHERE id = ' . $id_col);
        $donnees = $reponse->fetch();
        $collector      = $donnees['collector'];
        $type_collector = $donnees['type_collector'];
        $reponse->closeCursor();

        // Si on modifie l'image, on supprime l'ancienne et on insère la nouvelle
        if ($files['image']['name'] != NULL)
        {
          if (isset($collector) AND !empty($collector) AND $type_collector == "I")
            unlink ("../../includes/images/collector/" . $collector);

          $collector   = uploadImage($files, rand());
        }
      }
      else
        $collector     = $post['collector'];
    }

    // Mise à jour en base
    if ($control_ok == true)
    {
      // Speaker
      if ($post['speaker'] == "other")
      {
        $speaker      = $post['other_speaker'];
        $type_speaker = "other";
      }
      else
      {
        // On récupère éventuellement l'identifiant si l'utilisateur est désinscrit
        if (!isset($post['speaker']))
        {
          $reponse = $bdd->query('SELECT id, speaker FROM collector WHERE id = ' . $id_col);
          $donnees = $reponse->fetch();
          $speaker = $donnees['speaker'];
          $reponse->closeCursor();
        }
        else
          $speaker = $post['speaker'];

        // Type speaker
        $type_speaker = "user";
      }

      // Modification de l'enregistrement en base
      $req = $bdd->prepare('UPDATE collector SET speaker        = :speaker,
                                                 type_speaker   = :type_speaker,
                                                 date_collector = :date_collector,
                                                 collector      = :collector,
                                                 context        = :context
                                           WHERE id             = ' . $id_col);
      $req->execute(array(
        'speaker'        => $speaker,
        'type_speaker'   => $type_speaker,
        'date_collector' => formatDateForInsert($post['date_collector']),
        'collector'      => deleteInvisible($collector),
        'context'        => deleteInvisible($post['context'])
      ));
      $req->closeCursor();

      // Message d'alerte
      if ($post['type_collector'] == "T")
        $_SESSION['alerts']['collector_updated'] = true;
      elseif ($post['type_collector'] == "I")
        $_SESSION['alerts']['image_collector_updated'] = true;
    }
  }

  // METIER : Lecture des votes utilisateur
  // RETOUR : Liste des votes
  function getVotesUser($list_collectors, $user)
  {
    $listVotes = array();

    global $bdd;

    foreach ($list_collectors as $collector)
    {
      $reponse = $bdd->query('SELECT * FROM collector_users WHERE id_collector = ' . $collector->getId() . ' AND identifiant = "' . $user . '"');
      $donnees = $reponse->fetch();

      if ($reponse->rowCount() > 0)
      {
        $myVote = VotesCollector::withData($donnees);
        array_push($listVotes, $myVote);
      }

      $reponse->closeCursor();
    }

    return $listVotes;
  }

  // METIER : Liste des votes par phrase culte
  // RETOUR : Liste des votes
  function getVotes($list_collectors)
  {
    $listVotes      = array();
    $listSmileys    = array();
    $listUsers      = array();
    $myIdentifiants = array();

    global $bdd;

    foreach ($list_collectors as $collector)
    {
      for ($i = 1; $i <= 8; $i++)
      {
        // Recherche du nombre de smileys
        $req = $bdd->query('SELECT COUNT(id) AS nb_smileys FROM collector_users WHERE id_collector = ' . $collector->getId() . ' AND vote = ' . $i);
        $data = $req->fetch();

        $listSmileys[$i] = $data['nb_smileys'];

        $req->closeCursor();

        // Recherche des noms
        $myArray = array();

        $req2 = $bdd->query('SELECT * FROM collector_users WHERE id_collector = ' . $collector->getId() . ' AND vote = ' . $i . ' ORDER BY identifiant ASC');
        while($data2 = $req2->fetch())
        {
          if ($req2->rowCount() > 0)
          {
            $req3 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $data2['identifiant'] . '"');
            $data3 = $req3->fetch();

            $pseudo = $data3['pseudo'];

            $req3->closeCursor();

            $myIdentifiants = array('identifiant' => $data2['identifiant'],
                                    'pseudo'      => $pseudo
                                   );
            array_push($myArray, $myIdentifiants);
          }
        }
        $req2->closeCursor();

        $listUsers[$i] = $myArray;
      }

      $myVotes = array('id'           => $collector->getId(),
                       'identifiants' => $listUsers,
                       'smileys'      => $listSmileys
                      );

      array_push($listVotes, $myVotes);
    }

    return $listVotes;
  }

  // METIER : Insertion ou mise à jour vote
  // RETOUR : Aucun
  function voteCollector($post, $user, $id_col)
  {
    $selfSatisfied = false;

    if (isset($post['smiley_1']))
      $vote = 1;
    elseif (isset($post['smiley_2']))
      $vote = 2;
    elseif (isset($post['smiley_3']))
      $vote = 3;
    elseif (isset($post['smiley_4']))
      $vote = 4;
    elseif (isset($post['smiley_5']))
      $vote = 5;
    elseif (isset($post['smiley_6']))
      $vote = 6;
    elseif (isset($post['smiley_7']))
      $vote = 7;
    elseif (isset($post['smiley_8']))
      $vote = 8;
    else
      $vote = 0;

    global $bdd;

    // On cherche s'il existe déjà un vote
    $reponse = $bdd->query('SELECT * FROM collector_users WHERE id_collector = ' . $id_col . ' AND identifiant = "' . $user . '"');
    $donnees = $reponse->fetch();

    // Si on a un vote mais on supprime l'avis
    if ($reponse->rowCount() > 0 AND $vote == 0)
    {
      $reponse2 = $bdd->exec('DELETE FROM collector_users WHERE id = ' . $donnees['id']);
    }
    // Si on a déjà un vote, on met à jour
    elseif ($reponse->rowCount() > 0 AND $vote > 0)
    {
      $reponse2 = $bdd->prepare('UPDATE collector_users SET vote = :vote WHERE id = ' . $donnees['id']);
      $reponse2->execute(array(
        'vote' => $vote
      ));
      $reponse2->closeCursor();
    }
    // Sinon on insère
    elseif ($reponse->rowCount() == 0 AND $vote > 0)
    {
      $reponse2 = $bdd->prepare('INSERT INTO collector_users(id_collector, identifiant, vote) VALUES(:id_collector, :identifiant, :vote)');
      $reponse2->execute(array(
        'id_collector' => $id_col,
        'identifiant'  => $user,
        'vote'         => $vote
        ));
      $reponse2->closeCursor();
    }

    // Génération succès (quand on vote, une seule prise en compte, et quand on retire son vote)
    $reponse3 = $bdd->query('SELECT * FROM collector WHERE id = ' . $id_col . ' AND speaker = "' . $user . '"');
    $donnees3 = $reponse3->fetch();

    if ($reponse3->rowCount() > 0)
      $selfSatisfied = true;

    $reponse3->closeCursor();

    if ($reponse->rowCount() == 0 AND $vote > 0)
    {
      insertOrUpdateSuccesValue('funny', $user, 1);

      if ($selfSatisfied == true)
        insertOrUpdateSuccesValue('self-satisfied', $user, 1);
    }

    if ($reponse->rowCount() > 0 AND $vote == 0)
    {
      insertOrUpdateSuccesValue('funny', $user, -1);

      if ($selfSatisfied == true)
        insertOrUpdateSuccesValue('self-satisfied', $user, -1);
    }

    $reponse->closeCursor();
  }

  // METIER : Suppression des votes si phrase culte supprimée
  // RETOUR : Aucun
  function deleteVotes($id_col)
  {
    global $bdd;

    $req = $bdd->exec('DELETE FROM collector_users WHERE id_collector = ' . $id_col);
  }

  // METIER : Récupère le numéro de page pour une notification Collector
  // RETOUR : Numéro de page
  function numPageCollector($id)
  {
    $numPage     = 0;
    $nb_par_page = 18;
    $position    = 1;

    global $bdd;

    // On cherche la position de la phrase culte dans la table
    $reponse = $bdd->query('SELECT id, date_collector FROM collector ORDER BY date_collector DESC, id DESC');
    while($donnees = $reponse->fetch())
    {
      if ($id == $donnees['id'])
        break;
      else
        $position++;
    }
    $reponse->closeCursor();

    $numPage = $nb_pages = ceil($position / $nb_par_page);

    return $numPage;
  }
?>
