<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/collectors.php');
  include_once('../../includes/classes/profile.php');

  // METIER : Initialise les données de sauvegarde en session
  // RETOUR : Aucun
  function initializeSaveSession()
  {
    // On initialise les champs de saisie s'il n'y a pas d'erreur
    if ((!isset($_SESSION['alerts']['wrong_date'])      OR $_SESSION['alerts']['wrong_date']      != true)
    AND (!isset($_SESSION['alerts']['file_too_big'])    OR $_SESSION['alerts']['file_too_big']    != true)
    AND (!isset($_SESSION['alerts']['temp_not_found'])  OR $_SESSION['alerts']['temp_not_found']  != true)
    AND (!isset($_SESSION['alerts']['wrong_file_type']) OR $_SESSION['alerts']['wrong_file_type'] != true)
    AND (!isset($_SESSION['alerts']['wrong_file'])      OR $_SESSION['alerts']['wrong_file']      != true))
    {
      unset($_SESSION['save']);

      $_SESSION['save']['speaker']        = '';
      $_SESSION['save']['other_speaker']  = '';
      $_SESSION['save']['date_collector'] = '';
      $_SESSION['save']['type_collector'] = '';
      $_SESSION['save']['collector']      = '';
      $_SESSION['save']['context']        = '';
    }
  }

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Tableau d'utilisateurs
  function getUsers()
  {
    // Initialisation tableau d'utilisateurs
    $listeUsers = array();

    global $bdd;

    $reponse = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant != "admin" AND status != "I" ORDER BY identifiant ASC');
    while ($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet User à partir des données remontées de la bdd
      $user = Profile::withData($donnees);

      // Création tableau de correspondance identifiant / pseudo / avatar
      $listeUsers[$user->getIdentifiant()] = array('pseudo' => $user->getPseudo(),
                                                   'avatar' => $user->getAvatar()
                                                  );
    }
    $reponse->closeCursor();

    // Retour
    return $listeUsers;
  }

  // METIER : Calcule le pourcentage de votes nécessaire pour devenir top culte
  // RETOUR : Minimum pour être top culte
  function getMinGolden($listeUsers)
  {
    $nombreUsers = count($listeUsers);
    $minGolden   = floor(($nombreUsers * 75) / 100);

    return $minGolden;
  }

  // METIER : Lecture nombre de pages en fonction du filtre
  // RETOUR : Nombre de pages
  function getPages($filtre, $identifiant, $minGolden)
  {
    $nombrePages      = 0;
    $nombreCollectors = 0;
    $nombreParPage    = 18;

    global $bdd;

    // Calcul du nombre total d'enregistrements pour chaque filtre
    switch ($filtre)
    {
      case 'noVote':
        $req = $bdd->query('SELECT COUNT(collector.id)
                            AS nb_col
                            FROM collector
                            WHERE NOT EXISTS (SELECT id, id_collector, identifiant
                                              FROM collector_users
                                              WHERE (collector.id = collector_users.id_collector
                                              AND    collector_users.identifiant = "' . $identifiant . '"))');

        break;

      case 'meOnly':
        $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector WHERE speaker = "' . $identifiant . '"');
        break;

      case 'byMe':
        $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector WHERE author = "' . $identifiant . '"');
        break;

      case 'usersOnly':
        $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector WHERE (type_speaker = "user" AND speaker != "' . $identifiant . '")');
        break;

      case 'othersOnly':
        $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector WHERE type_speaker = "other"');
        break;

      case 'textOnly':
        $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector WHERE type_collector = "T"');
        break;

      case 'picturesOnly':
        $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector WHERE type_collector = "I"');
        break;

      case 'topCulte':
        $req = $bdd->query('SELECT COUNT(collector.id)
                            AS nb_col
                            FROM collector
                            WHERE (SELECT COUNT(collector_users.id)
                                   FROM collector_users
                                   WHERE collector_users.id_collector = collector.id) >= ' . $minGolden);
        break;

      case 'none':
      default:
        $req = $bdd->query('SELECT COUNT(id) AS nb_col FROM collector');
        break;
    }

    $data = $req->fetch();

    if (isset($data['nb_col']))
      $nombreCollectors = $data['nb_col'];

    $req->closeCursor();

    $nombrePages = ceil($nombreCollectors / $nombreParPage);

    return $nombrePages;
  }

  // METIER : Lecture des phrases cultes
  // RETOUR : Liste phrases cultes
  function getCollectors($listeUsers, $nombrePages, $minGolden, $page, $identifiant, $tri, $filtre)
  {
    $listeCollectors = array();
    $nombreParPage   = 18;

    // Contrôle dernière page
    if ($page > $nombrePages)
      $page = $nombrePages;

    // Calcul première entrée
    $premiereEntree = ($page - 1) * $nombreParPage;

    // Détermination sens tri
    switch ($tri)
    {
      case 'dateAsc':
        $order = 'collector.date_collector ASC, collector.id ASC';
        break;

      case 'dateDesc':
      default:
        $order = 'collector.date_collector DESC, collector.id DESC';
        break;
    }

    // Lecture des enregistrements en fonction du filtre
    global $bdd;

    switch ($filtre)
    {
      case 'noVote':
        $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE NOT EXISTS (SELECT id, id_collector, identifiant
                                                  FROM collector_users
                                                  WHERE (collector.id = collector_users.id_collector
                                                  AND    collector_users.identifiant = "' . $identifiant . '"))
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiereEntree . ', ' . $nombreParPage);

        break;

      case 'meOnly':
        $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE (collector.speaker = "' . $identifiant . '")
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
        break;

      case 'byMe':
        $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE (collector.author = "' . $identifiant . '")
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
        break;

      case 'usersOnly':
        $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE (collector.type_speaker = "user" AND collector.speaker != "' . $identifiant . '")
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
        break;

      case 'othersOnly':
        $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE (collector.type_speaker = "other")
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
        break;

      case 'textOnly':
        $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE (collector.type_collector = "T")
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
        break;

      case 'picturesOnly':
        $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE (collector.type_collector = "I")
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
        break;

      case 'topCulte':
        $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                WHERE (SELECT COUNT(collector_users.id)
                                       FROM collector_users
                                       WHERE collector_users.id_collector = collector.id) >= ' . $minGolden . '
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
        break;

      case 'none':
      default:
        $reponse = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nb_votes
                                FROM collector
                                LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                GROUP BY collector.id
                                ORDER BY ' . $order . ' LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
        break;
    }

    while ($donnees = $reponse->fetch())
    {
      // Récupération objet collector
      $collector = Collector::withData($donnees);

      // Nombre de votes
      $collector->setNb_votes($donnees['nb_votes']);

      // Pseudo auteur
      if (isset($listeUsers[$collector->getAuthor()]))
        $collector->setPseudo_author($listeUsers[$collector->getAuthor()]['pseudo']);

      // Pseudo speaker (dont "autre" si besoin)
      if ($collector->getType_speaker() == 'other' AND !empty($collector->getSpeaker()))
        $collector->setPseudo_speaker($collector->getSpeaker());
      else
      {
        if (isset($listeUsers[$collector->getSpeaker()]))
        {
          $collector->setPseudo_speaker($listeUsers[$collector->getSpeaker()]['pseudo']);
          $collector->setAvatar_speaker($listeUsers[$collector->getSpeaker()]['avatar']);
        }
      }

      // Vote utilisateur connecté
      $reponse2 = $bdd->query('SELECT * FROM collector_users WHERE id_collector = ' . $collector->getId() . ' AND identifiant = "' . $identifiant . '"');
      $donnees2 = $reponse2->fetch();

      if ($reponse2->rowCount() > 0)
        $collector->setVote_user($donnees2['vote']);

      $reponse2->closeCursor();

      // Votes tous utilisateurs
      $collector->setVotes(getVotes($collector, $listeUsers));

      // Ajout à la liste
      array_push($listeCollectors, $collector);
    }
    $reponse->closeCursor();

    // Retour
    return $listeCollectors;
  }

  // METIER : Insertion phrases cultes
  // RETOUR : Id enregistrement créé
  function insertCollector($post, $files, $user)
  {
    $newId      = NULL;
    $control_ok = true;

    // Sauvegarde en session en cas d'erreur
    if ($post['speaker'] == 'other')
    {
      $_SESSION['save']['speaker']       = $post['speaker'];
      $_SESSION['save']['other_speaker'] = $post['other_speaker'];
    }
    else
    {
      $_SESSION['save']['speaker']       = $post['speaker'];
      $_SESSION['save']['other_speaker'] = '';
    }

    $_SESSION['save']['date_collector'] = $post['date_collector'];
    $_SESSION['save']['type_collector'] = $post['type_collector'];
    $_SESSION['save']['context']        = $post['context'];

    if ($post['type_collector'] == 'T')
      $_SESSION['save']['collector']    = $post['collector'];

    // On contrôle la date
    if ($control_ok == true)
    {
      if (validateDate($post['date_collector']) != true)
      {
        $_SESSION['alerts']['wrong_date'] = true;
        $control_ok                       = false;
      }
    }

    // Formatage du texte ou insertion image
    if ($control_ok == true)
    {
      if ($post['type_collector'] == 'T')
        $collector = deleteInvisible($post['collector']);
      elseif ($post['type_collector'] == 'I')
        $collector = uploadImage($files, rand());

      if (empty($collector))
        $control_ok = false;
    }

    if ($control_ok == true)
    {
      global $bdd;

      if ($post['speaker'] == 'other')
      {
        $collector = array('date_add'       => date('Ymd'),
                           'author'         => $user,
                           'speaker'        => $post['other_speaker'],
                           'type_speaker'   => $post['speaker'],
                           'date_collector' => formatDateForInsert($post['date_collector']),
                           'type_collector' => $post['type_collector'],
                           'collector'      => $collector,
                           'context'        => deleteInvisible($post['context'])
                          );
      }
      else
      {
        $collector = array('date_add'       => date('Ymd'),
                           'author'         => $user,
                           'speaker'        => $post['speaker'],
                           'type_speaker'   => 'user',
                           'date_collector' => formatDateForInsert($post['date_collector']),
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
      $newId = $bdd->lastInsertId();

      if ($post['type_collector'] == 'T')
        insertNotification($user, 'culte', $newId);
      elseif ($post['type_collector'] == 'I')
        insertNotification($user, 'culte_image', $newId);

      // Génération succès
      insertOrUpdateSuccesValue('listener', $user, 1);

      if ($post['speaker'] != 'other')
        insertOrUpdateSuccesValue('speaker', $post['speaker'], 1);

      // Ajout expérience
      insertExperience($user, 'add_collector');

      // Message d'alerte
      if ($post['type_collector'] == 'T')
        $_SESSION['alerts']['collector_added'] = true;
      elseif ($post['type_collector'] == 'I')
        $_SESSION['alerts']['image_collector_added'] = true;
    }

    return $newId;
  }

  // METIER : Formatage et insertion image Collector
  // RETOUR : Nom fichier avec extension
  function uploadImage($files, $name)
  {
    $newName   = '';
    $control_ok = true;

    // On vérifie la présence du dossier, sinon on le créé
    $dossier = '../../includes/images/collector';

    if (!is_dir($dossier))
      mkdir($dossier);

    // Dossier de destination
    $imageDir = $dossier . '/';

    // Contrôles fichier
    $fileDatas = controlsUploadFile($files['image'], $name, 'all');

    // Traitements fichier
    if ($fileDatas['control_ok'] == true)
    {
      // Upload fichier
      $control_ok = uploadFile($fileDatas, $imageDir);

      // Rotation de l'image
      if ($control_ok == true)
      {
        $newName   = $fileDatas['new_name'];
        $typeImage = $fileDatas['type_file'];

        if ($typeImage == 'jpg' OR $typeImage == 'jpeg')
          rotateImage($imageDir . $newName, $typeImage);
      }
    }

    return $newName;
  }

  // METIER : Suppression phrases cultes
  // RETOUR : Aucun
  function deleteCollector($post)
  {
    $idCollector = $post['id_col'];

    global $bdd;

    // Suppression image
    $req1 = $bdd->query('SELECT id, type_collector, collector FROM collector WHERE id = ' . $idCollector);
    $data1 = $req1->fetch();

    $collector     = $data1['collector'];
    $typeCollector = $data1['type_collector'];

    if (isset($collector) AND !empty($collector) AND $typeCollector == 'I')
      unlink('../../includes/images/collector/' . $data1['collector']);

    $req1->closeCursor();

    // Suppression enregistrement base
    $req2 = $bdd->exec('DELETE FROM collector WHERE id = ' . $idCollector);

    // Suppression des notifications
    if ($typeCollector == 'T')
      deleteNotification('culte', $idCollector);
    elseif ($typeCollector == 'I')
      deleteNotification('culte_image', $idCollector);

    // Message d'alerte
    if ($typeCollector == 'T')
      $_SESSION['alerts']['collector_deleted'] = true;
    elseif ($typeCollector == 'I')
      $_SESSION['alerts']['image_collector_deleted'] = true;
  }

  // METIER : Modification phrases cultes
  // RETOUR : Id collector
  function updateCollector($post, $files)
  {
    $control_ok  = true;
    $idCollector = $post['id_col'];

    global $bdd;

    // On contrôle la date
    if ($control_ok == true)
    {
      if (validateDate($post['date_collector']) != true)
      {
        $_SESSION['alerts']['wrong_date'] = true;
        $control_ok                       = false;
      }
    }

    // Suppression ancienne image ou récupération collector
    if ($control_ok == true)
    {
      if ($post['type_collector'] == 'I')
      {
        // On récupère le nom de l'image existante
        $reponse = $bdd->query('SELECT id, type_collector, collector FROM collector WHERE id = ' . $idCollector);
        $donnees = $reponse->fetch();
        $collector     = $donnees['collector'];
        $typeCollector = $donnees['type_collector'];
        $reponse->closeCursor();

        // Si on modifie l'image, on supprime l'ancienne et on insère la nouvelle
        if (!empty($files['image']['name']))
        {
          if (isset($collector) AND !empty($collector) AND $typeCollector == 'I')
            unlink('../../includes/images/collector/' . $collector);

          $collector = uploadImage($files, rand());

          if (empty($collector))
            $control_ok = false;
        }
      }
      else
        $collector = $post['collector'];
    }

    // Mise à jour en base
    if ($control_ok == true)
    {
      // Speaker
      if ($post['speaker'] == 'other')
      {
        $speaker     = $post['other_speaker'];
        $typeSpeaker = 'other';
      }
      else
      {
        // On récupère éventuellement l'identifiant si l'utilisateur est désinscrit
        if (!isset($post['speaker']))
        {
          $reponse = $bdd->query('SELECT id, speaker FROM collector WHERE id = ' . $idCollector);
          $donnees = $reponse->fetch();
          $speaker = $donnees['speaker'];
          $reponse->closeCursor();
        }
        else
          $speaker = $post['speaker'];

        // Type speaker
        $typeSpeaker = 'user';
      }

      // Modification de l'enregistrement en base
      $req = $bdd->prepare('UPDATE collector SET speaker        = :speaker,
                                                 type_speaker   = :type_speaker,
                                                 date_collector = :date_collector,
                                                 collector      = :collector,
                                                 context        = :context
                                           WHERE id             = ' . $idCollector);
      $req->execute(array(
        'speaker'        => $speaker,
        'type_speaker'   => $typeSpeaker,
        'date_collector' => formatDateForInsert($post['date_collector']),
        'collector'      => deleteInvisible($collector),
        'context'        => deleteInvisible($post['context'])
      ));
      $req->closeCursor();

      // Message d'alerte
      if ($post['type_collector'] == 'T')
        $_SESSION['alerts']['collector_updated'] = true;
      elseif ($post['type_collector'] == 'I')
        $_SESSION['alerts']['image_collector_updated'] = true;
    }

    return $idCollector;
  }

  // METIER : Liste des votes par phrase culte
  // RETOUR : Liste des votes
  function getVotes($collector, $listeUsers)
  {
    $listVotes = array();

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM collector_users WHERE id_collector = ' . $collector->getId() . ' ORDER BY vote ASC, identifiant ASC');
    while ($donnees = $reponse->fetch())
    {
      // Récupération pseudo
      if (isset($listeUsers[$donnees['identifiant']]))
        $pseudo = $listeUsers[$donnees['identifiant']]['pseudo'];
      else
        $pseudo = '';

      // Si le vote n'existe pas dans le tableau, on créé une nouvelle entrée
      if (!isset($listVotes[$donnees['vote']]))
        $listVotes[$donnees['vote']] = array();

      array_push($listVotes[$donnees['vote']], $pseudo);
    }
    $reponse->closeCursor();

    return $listVotes;
  }

  // METIER : Insertion ou mise à jour vote
  // RETOUR : Id collector
  function voteCollector($post, $user)
  {
    $idCollector   = $post['id_col'];
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
    $reponse = $bdd->query('SELECT * FROM collector_users WHERE id_collector = ' . $idCollector . ' AND identifiant = "' . $user . '"');
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
        'id_collector' => $idCollector,
        'identifiant'  => $user,
        'vote'         => $vote
        ));
      $reponse2->closeCursor();
    }

    // Génération succès (quand on vote, une seule prise en compte, et quand on retire son vote)
    $reponse3 = $bdd->query('SELECT * FROM collector WHERE id = ' . $idCollector . ' AND speaker = "' . $user . '"');

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

    return $idCollector;
  }

  // METIER : Suppression des votes si phrase culte supprimée
  // RETOUR : Aucun
  function deleteVotes($post)
  {
    $idCollector = $post['id_col'];

    global $bdd;

    $req = $bdd->exec('DELETE FROM collector_users WHERE id_collector = ' . $idCollector);
  }

  // METIER : Récupère le numéro de page pour une notification Collector
  // RETOUR : Numéro de page
  function numeroPageCollector($id)
  {
    $numPage       = 0;
    $nombreParPage = 18;
    $position      = 1;

    global $bdd;

    // On cherche la position de la phrase culte dans la table
    $reponse = $bdd->query('SELECT id, date_collector FROM collector ORDER BY date_collector DESC, id DESC');
    while ($donnees = $reponse->fetch())
    {
      if ($id == $donnees['id'])
        break;
      else
        $position++;
    }
    $reponse->closeCursor();

    $numPage = $nombrePages = ceil($position / $nombreParPage);

    return $numPage;
  }
?>
