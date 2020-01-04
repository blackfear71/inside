<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/missions.php');
  include_once('../../includes/classes/profile.php');

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
      $dossier = "../../includes/images/missions";

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
        $maxsize = 15728640; // 15 Mo

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
          $dossier_images = "../../includes/images/missions/banners";
          $dossier_icones = "../../includes/images/missions/buttons";

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
          $maxsize = 15728640; // 15 Mo

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
    unlink ("../../includes/images/missions/banners/" . $reference . ".png");
    unlink ("../../includes/images/missions/buttons/" . $reference . "_g.png");
    unlink ("../../includes/images/missions/buttons/" . $reference . "_m.png");
    unlink ("../../includes/images/missions/buttons/" . $reference . "_d.png");

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
?>
