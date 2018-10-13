<?php
  include_once('../../includes/appel_bdd.php');
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

  // METIER : Lecture nombre de pages
  // RETOUR : Nombre de pages
  function getPages()
  {
    $nb_pages    = 0;
    $nb_col      = 0;
    $nb_par_page = 18;

    global $bdd;

    $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector');
    $data = $req->fetch();

    $nb_col = $data['nb_col'];

    $req->closeCursor();

    $nb_pages = ceil($nb_col / $nb_par_page);

    return $nb_pages;
  }

  // METIER : Lecture des phrases cultes
  // RETOUR : Liste phrases cultes
  function getCollectors($listUsers, $nb_pages, $page)
  {
    $listCollectors = array();
    $nb_par_page    = 18;

    // Contrôle dernière page
    if ($page > $nb_pages)
      $page = $nb_pages;

    // Calcul première entrée
    $premiere_entree = ($page - 1) * $nb_par_page;

    // Lecture des enregistrements
    global $bdd;

    $reponse = $bdd->query('SELECT * FROM collector ORDER BY date_collector DESC, id DESC LIMIT ' . $premiere_entree . ', ' . $nb_par_page);
    while($donnees = $reponse->fetch())
    {
      $myCollector = Collector::withData($donnees);

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
  // RETOUR : Aucun
  function insertCollector($post, $user)
  {
    // Sauvegarde en session en cas d'erreur
    $_SESSION['save']['speaker']        = $post['speaker'];
    $_SESSION['save']['other_speaker']  = $post['other_speaker'];
    $_SESSION['save']['date_collector'] = $post['date_collector'];
    $_SESSION['save']['collector']      = $post['collector'];
    $_SESSION['save']['context']        = $post['context'];

    $date_a_verifier = $post['date_collector'];

    // On décompose la date à contrôler
    list($d, $m, $y) = explode('/', $date_a_verifier);

    // On vérifie le format de la date
    if (checkdate($m, $d, $y))
    {
      global $bdd;

      if ($post['speaker'] == "other")
      {
        $collector = array('date_add'       => date("Ymd"),
                           'author'         => $_SESSION['user']['identifiant'],
                           'speaker'        => $post['other_speaker'],
                           'type_speaker'   => $post['speaker'],
                           'date_collector' => formatDateForInsert($date_a_verifier),
                           'collector'      => deleteInvisible($post['collector']),
                           'context'        => deleteInvisible($post['context'])
                          );
      }
      else
      {
        $collector = array('date_add'       => date("Ymd"),
                           'author'         => $_SESSION['user']['identifiant'],
                           'speaker'        => $post['speaker'],
                           'type_speaker'   => "user",
                           'date_collector' => formatDateForInsert($date_a_verifier),
                           'collector'      => deleteInvisible($post['collector']),
                           'context'        => deleteInvisible($post['context'])
                          );
      }

			// Stockage de l'enregistrement en table
      $req = $bdd->prepare('INSERT INTO collector(date_add,
                                                  author,
																									speaker,
                                                  type_speaker,
																									date_collector,
																									collector,
                                                  context
                                                 )
																			     VALUES(:date_add,
                                                  :author,
																									:speaker,
                                                  :type_speaker,
																								  :date_collector,
																								  :collector,
                                                  :context
                                                 )');
      $req->execute($collector);
		  $req->closeCursor();

      // Génération notification phrase culte ajoutée
      $new_id = $bdd->lastInsertId();

      insertNotification($user, 'culte', $new_id);

      $_SESSION['alerts']['collector_added'] = true;
    }
    else
      $_SESSION['alerts']['wrong_date'] = true;
  }

  // METIER : Suppression phrases cultes
  // RETOUR : Aucun
  function deleteCollector($id_col)
  {
    global $bdd;

    $req = $bdd->exec('DELETE FROM collector WHERE id = ' . $id_col);

    // Suppression des notifications
    deleteNotification('culte', $id_col);

    $_SESSION['alerts']['collector_deleted'] = true;
  }

  // METIER : Modification phrases cultes
  // RETOUR : Aucun
  function updateCollector($post, $id_col)
  {
    $date_a_verifier = $post['date_collector'];

    // On décompose la date à contrôler
    list($d, $m, $y) = explode('/', $date_a_verifier);

    // On vérifie le format de la date
    if (checkdate($m, $d, $y))
    {
      global $bdd;

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
                                           WHERE id = ' . $id_col);
      $req->execute(array(
        'speaker'        => $speaker,
        'type_speaker'   => $type_speaker,
        'date_collector' => formatDateForInsert($post['date_collector']),
        'collector'      => deleteInvisible($post['collector']),
        'context'        => deleteInvisible($post['context'])
      ));
      $req->closeCursor();

      $_SESSION['alerts']['collector_modified'] = true;
    }
    else
      $_SESSION['alerts']['wrong_date'] = true;
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

    $reponse->closeCursor();
  }

  // METIER : Suppression des votes si phrase culte supprimée
  // RETOUR : Aucun
  function deleteVotes($id_col)
  {
    global $bdd;

    $req = $bdd->exec('DELETE FROM collector_users WHERE id_collector = ' . $id_col);
  }
?>
