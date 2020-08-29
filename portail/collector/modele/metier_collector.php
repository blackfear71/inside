<?php
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
    AND (!isset($_SESSION['alerts']['wrong_file'])      OR $_SESSION['alerts']['wrong_file']      != true)
    AND (!isset($_SESSION['alerts']['empty_collector']) OR $_SESSION['alerts']['empty_collector'] != true))
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
    // Récupération de la liste des utilisateurs (sauf ceux en cours d'inscription)
    $listeUsers = physiqueUsers();

    // Retour
    return $listeUsers;
  }

  // METIER : Calcule le pourcentage de votes nécessaire pour devenir top culte
  // RETOUR : Minimum pour être top culte
  function getMinGolden($listeUsers)
  {
    // Récupération du nombre d'utilisateurs inscrits
    $nombreUsers = count($listeUsers);

    // Calcul du minimum pour être culte
    $minGolden = floor(($nombreUsers * 75) / 100);

    // Retour
    return $minGolden;
  }

  // METIER : Lecture nombre de pages en fonction du filtre
  // RETOUR : Nombre de pages
  function getPages($filtre, $identifiant, $minGolden)
  {
    // Initialisations
    $nombreParPage = 18;

    // Calcul du nombre total de phrases cultes pour chaque filtre
    $nombreCollectors = physiqueCalculNombreCollector($filtre, $identifiant, $minGolden);

    // Calcul du nombre de pages
    $nombrePages = ceil($nombreCollectors / $nombreParPage);

    // Retour
    return $nombrePages;
  }

  // METIER : Lecture des phrases cultes
  // RETOUR : Liste phrases cultes
  function getCollectors($listeUsers, $nombrePages, $minGolden, $page, $identifiant, $tri, $filtre)
  {
    // Initialisations
    $nombreParPage = 18;

    // Vérification dépassement dernière page
    if ($page > $nombrePages)
      $page = $nombrePages;

    // Calcul première entrée
    $premiereEntree = ($page - 1) * $nombreParPage;

    // Lecture des enregistrements en fonction du filtre et du tri
    $listeCollectors = physiqueCollectors($tri, $filtre, $nombreParPage, $premiereEntree, $identifiant, $minGolden);

    // Récupération des données complémentaires
    foreach ($listeCollectors as $collector)
    {
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

      // Vote de l'utilisateur connecté
      $voteExistant = physiqueVoteUser($collector->getId(), $identifiant);
      $collector->setVote_user($voteExistant['vote']);

      // Votes tous utilisateurs
      $collector->setVotes(physiqueVotesUsers($collector->getId(), $listeUsers));
    }

    // Retour
    return $listeCollectors;
  }

  // METIER : Insertion phrases / images cultes
  // RETOUR : Id collector
  function insertCollector($post, $files, $identifiant)
  {
    // Initialisations
    $idCollector = NULL;
    $control_ok  = true;

    // Récupération des données
    if ($post['speaker'] == 'other')
    {
      $speaker     = $post['other_speaker'];
      $typeSpeaker = $post['speaker'];
    }
    else
    {
      $speaker     = $post['speaker'];
      $typeSpeaker = 'user';
    }

    $dateCollector     = formatDateForInsert($post['date_collector']);
    $typeCollector     = $post['type_collector'];
    $contexteCollector = deleteInvisible($post['context']);

    if ($typeCollector == 'T')
      $collector = $post['collector'];

    // Sauvegarde en session en cas d'erreur
    if ($typeSpeaker == 'other')
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

    if ($typeCollector == 'T')
      $_SESSION['save']['collector'] = $post['collector'];

    // Contrôle date de saisie
    $control_ok = controleFormatDate($post['date_collector']);

    // Formatage du texte ou insertion image
    if ($control_ok == true)
    {
      if ($typeCollector == 'I')
        $contenuCollector = uploadImage($files, rand());
      else
        $contenuCollector = deleteInvisible($post['collector']);
    }

    // Contrôle saisie non vide
    if ($control_ok == true)
      $control_ok = controleCollector($contenuCollector);

    // Insertion de l'enregistrement en base
    if ($control_ok == true)
    {
      $collector = array('date_add'       => date('Ymd'),
                         'author'         => $identifiant,
                         'speaker'        => $speaker,
                         'type_speaker'   => $typeSpeaker,
                         'date_collector' => $dateCollector,
                         'type_collector' => $typeCollector,
                         'collector'      => $contenuCollector,
                         'context'        => $contexteCollector
                        );

      $idCollector = physiqueInsertionCollector($collector);

      // Insertion notification
      if ($post['type_collector'] == 'I')
        insertNotification($identifiant, 'culte_image', $idCollector);
      else
        insertNotification($identifiant, 'culte', $idCollector);

      // Génération succès
      insertOrUpdateSuccesValue('listener', $identifiant, 1);

      if ($typeSpeaker != 'other')
        insertOrUpdateSuccesValue('speaker', $post['speaker'], 1);

      // Ajout expérience
      insertExperience($identifiant, 'add_collector');

      // Message d'alerte
      if ($post['type_collector'] == 'I')
        $_SESSION['alerts']['image_collector_added'] = true;
      else
        $_SESSION['alerts']['collector_added'] = true;
    }

    // Retour
    return $idCollector;
  }

  // METIER : Modification phrases / images cultes
  // RETOUR : Id collector
  function updateCollector($post, $files)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $idCollector       = $post['id_col'];
    $dateCollector     = formatDateForInsert($post['date_collector']);
    $typeCollector     = $post['type_collector'];
    $contexteCollector = deleteInvisible($post['context']);

    // Contrôle date de saisie
    $control_ok = controleFormatDate($post['date_collector']);

    // Récupération des données complémentaires
    if ($control_ok == true)
    {
      // Lecture des données de la phrase / image culte
      $oldCollector = physiqueCollector($idCollector);

      // Récupération du speaker
      if ($post['speaker'] == 'other')
      {
        $speaker     = $post['other_speaker'];
        $typeSpeaker = $post['speaker'];
      }
      else
      {
        // On récupère éventuellement l'identifiant existant si l'utilisateur est désinscrit
        if (!isset($post['speaker']))
          $speaker = $oldCollector->getSpeaker();
        else
          $speaker = $post['speaker'];

        $typeSpeaker = 'user';
      }

      // Suppression ancienne image / insertion de la nouvelle ou récupération phrase culte
      if ($typeCollector == 'I')
      {
        if (!empty($files['image']['name']))
        {
          if (!empty($oldCollector->getCollector()))
            unlink('../../includes/images/collector/' . $oldCollector->getCollector());

          $contenuCollector = uploadImage($files, rand());

          // Contrôle saisie non vide
          if ($control_ok == true)
            $control_ok = controleCollector($contenuCollector);
        }
        else
          $contenuCollector = $oldCollector->getCollector();
      }
      else
        $contenuCollector = deleteInvisible($post['collector']);
    }

    // Modification de l'enregistrement en base
    if ($control_ok == true)
    {
      $collector = array('speaker'        => $speaker,
                         'type_speaker'   => $typeSpeaker,
                         'date_collector' => $dateCollector,
                         'collector'      => $contenuCollector,
                         'context'        => $contexteCollector
                        );

      physiqueUpdateCollector($oldCollector->getId(), $collector);

      // Message d'alerte
      if ($post['type_collector'] == 'I')
        $_SESSION['alerts']['image_collector_updated'] = true;
      else
        $_SESSION['alerts']['collector_updated'] = true;
    }

    // Retour
    return $idCollector;
  }

  // METIER : Formatage et insertion image Collector
  // RETOUR : Nom fichier avec extension
  function uploadImage($files, $name)
  {
    // Initialisations
    $newName    = '';
    $control_ok = true;

    // On vérifie la présence du dossier, sinon on le créé
    $dossier = '../../includes/images/collector';

    if (!is_dir($dossier))
      mkdir($dossier);

    // Dossier de destination
    $imageDir = $dossier . '/';

    // Contrôles fichier
    $fileDatas = controlsUploadFile($files['image'], $name, 'all');

    // Récupération contrôles
    $control_ok = controleFichier($fileDatas);

    // Upload fichier
    if ($control_ok == true)
      $control_ok = uploadFile($fileDatas, $imageDir);

    // Rotation de l'image
    if ($control_ok == true)
    {
      $newName   = $fileDatas['new_name'];
      $typeImage = $fileDatas['type_file'];

      if ($typeImage == 'jpg' OR $typeImage == 'jpeg')
        rotateImage($imageDir . $newName, $typeImage);
    }

    // Retour
    return $newName;
  }

  // METIER : Récupère le numéro de page lors de l'ajout ou de la modification
  // RETOUR : Numéro de page
  function numeroPageCollector($idCollector)
  {
    // Initialisations
    $nombreParPage = 18;

    // Recherche de la position de la phrase culte dans la table
    $positionCollector = physiquePositionCollector($idCollector);

    // Calcul du numéro de page
    $numeroPage = ceil($positionCollector / $nombreParPage);

    // Retour
    return $numeroPage;
  }

  // METIER : Suppression phrases / images cultes
  // RETOUR : Aucun
  function deleteCollector($post)
  {
    // Récupération des données
    $idCollector = $post['id_col'];

    // Lecture des données de la phrase / image culte
    $collector = physiqueCollector($idCollector);

    // Suppression image
    if (!empty($collector->getCollector()) AND $collector->getType_collector() == 'I')
      unlink('../../includes/images/collector/' . $collector->getCollector());

    // Suppression de l'enregistrement en base
    physiqueDeleteCollector($idCollector);

    // Suppression des notifications
    if ($collector->getType_collector() == 'I')
      deleteNotification('culte_image', $idCollector);
    else
      deleteNotification('culte', $idCollector);

    // Message d'alerte
    if ($collector->getType_collector() == 'I')
      $_SESSION['alerts']['image_collector_deleted'] = true;
    else
      $_SESSION['alerts']['collector_deleted'] = true;
  }

  // METIER : Suppression des votes si phrase culte supprimée
  // RETOUR : Aucun
  function deleteVotes($post)
  {
    // Récupération des données
    $idCollector = $post['id_col'];

    // Suppression des votes
    physiqueDeleteVotes($idCollector);
  }
  
  // METIER : Insertion ou mise à jour vote
  // RETOUR : Id collector
  function voteCollector($post, $identifiant)
  {
    // Récupération des données
    $idCollector = $post['id_col'];

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

    // Lecture vote existant
    $voteExistant = physiqueVoteUser($idCollector, $identifiant);

    // Traitement du vote
    if ($voteExistant['vote'] > 0)
    {
      // Suppression (vote = 0) ou mise à jour (vote != 0)
      if ($vote == 0)
        physiqueDeleteVote($voteExistant['id_vote']);
      else
        physiqueUpdateVote($voteExistant['id_vote'], $vote);
    }
    else
    {
      // Insertion (vote != 0)
      if ($vote > 0)
      {
        $voteCollector = array('id_collector' => $idCollector,
                               'identifiant'  => $identifiant,
                               'vote'         => $vote
                              );

        physiqueInsertionVote($voteCollector);
      }
    }

    // Génération succès (quand on vote, une seule prise en compte, et quand on retire son vote)
    $selfSatisfied = physiqueCollectorUser($idCollector, $identifiant);

    if ($voteExistant['vote'] > 0)
    {
      // Suppression (vote = 0)
      if ($vote == 0)
      {
        insertOrUpdateSuccesValue('funny', $identifiant, -1);

        if ($selfSatisfied == true)
          insertOrUpdateSuccesValue('self-satisfied', $identifiant, -1);
      }
    }
    else
    {
      // Insertion (vote != 0)
      if ($vote > 0)
      {
        insertOrUpdateSuccesValue('funny', $identifiant, 1);

        if ($selfSatisfied == true)
          insertOrUpdateSuccesValue('self-satisfied', $identifiant, 1);
      }
    }

    // Retour
    return $idCollector;
  }
?>
