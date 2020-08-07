<?php
  include_once('../../includes/classes/missions.php');
  include_once('../../includes/classes/profile.php');

  // METIER : Initialise les données de sauvegarde en session
  // RETOUR : Erreur
  function initializeSaveSession()
  {
    // Initialisations
    $erreurMission = false;

    // On supprime la session s'il n'y a pas d'erreur
  	if ((!isset($_SESSION['alerts']['already_ref_mission'])   OR $_SESSION['alerts']['already_ref_mission']   != true)
    AND (!isset($_SESSION['alerts']['objective_not_numeric']) OR $_SESSION['alerts']['objective_not_numeric'] != true)
    AND (!isset($_SESSION['alerts']['wrong_date'])            OR $_SESSION['alerts']['wrong_date']            != true)
    AND (!isset($_SESSION['alerts']['date_less'])             OR $_SESSION['alerts']['date_less']             != true)
    AND (!isset($_SESSION['alerts']['missing_mission_file'])  OR $_SESSION['alerts']['missing_mission_file']  != true)
    AND (!isset($_SESSION['alerts']['file_too_big'])          OR $_SESSION['alerts']['file_too_big']          != true)
    AND (!isset($_SESSION['alerts']['temp_not_found'])        OR $_SESSION['alerts']['temp_not_found']        != true)
    AND (!isset($_SESSION['alerts']['wrong_file_type'])       OR $_SESSION['alerts']['wrong_file_type']       != true)
    AND (!isset($_SESSION['alerts']['wrong_file'])            OR $_SESSION['alerts']['wrong_file']            != true))
      unset($_SESSION['save']);
    else
      $erreurMission = true;

    // Retour
    return $erreurMission;
  }

  // METIER : Récupération des missions
  // RETOUR : Objets mission
  function getMissions()
  {
    // Récupération de la liste des missions
    $missions = physiqueListeMissions();

    // Traitement des missions
    foreach ($missions as $mission)
    {
      // Affectation du statut
      if (date('Ymd') < $mission->getDate_deb() OR (date('Ymd') == $mission->getDate_deb() AND date('His') < $mission->getHeure()))
        $mission->setStatut('V');
      elseif (((date('Ymd') == $mission->getDate_deb() AND date('His') >= $mission->getHeure()) OR date('Ymd') > $mission->getDate_deb()) AND date('Ymd') <= $mission->getDate_fin())
        $mission->setStatut('C');
      elseif (date('Ymd') > $mission->getDate_fin())
        $mission->setStatut('A');

      // Récupération du tri sur statut puis date de début
      $triStatut[]  = $mission->getStatut();
      $triDateDeb[] = $mission->getDate_deb();
    }

    // Tri (V : à venir, C : en cours, A : ancienne)
    array_multisort($triStatut, SORT_DESC, $triDateDeb, SORT_DESC, $missions);

    // Retour
    return $missions;
  }

  // METIER : Initialisation ajout mission
  // RETOUR : Objets mission
  function initialisationAjoutMission()
  {
    // Instanciation d'un objet Mission vide
    $mission = new Mission();

    // Retour
    return $mission;
  }

  // METIER : Récupération mission spécifique pour modification
  // RETOUR : Objet mission
  function initialisationModificationMission($idMission)
  {
    // Lecture des détails de la mission
    $mission = physiqueMission($idMission);

    // Retour
    return $mission;
  }

  // METIER : Initialisation mission en cas d'erreur de saisie (ajout et modification)
  // RETOUR : Objet mission
  function initialisationErreurMission($saveMission, $idMission)
  {
    // Instanciation d'un objet Mission vide
    $mission = new Mission();

    // Définition de l'id en cas de modification
    if (!empty($idMission))
      $mission->setId($idMission);

    // Définition des données à partir de la sauvegarde
    $mission->setMission($saveMission['mission']);
    $mission->setDate_deb($saveMission['date_deb']);
    $mission->setDate_fin($saveMission['date_fin']);
    $mission->setHeure($saveMission['heures'] . $saveMission['minutes'] . '00');
    $mission->setDescription($saveMission['description']);
    $mission->setReference($saveMission['reference']);
    $mission->setObjectif($saveMission['objectif']);
    $mission->setExplications($saveMission['explications']);
    $mission->setConclusion($saveMission['conclusion']);

    // Retour
    return $mission;
  }

  // METIER : Récupération des participants d'une mission
  // RETOUR : Liste des participants
  function getParticipants($idMission)
  {
    // Récupération de la liste des participants de la mission
    $listeUsers = physiqueUsersMission($idMission);

    // Traitement s'il y a des participants
    if (!empty($listeUsers))
    {
      // Récupération des données complémentaires des participants
      foreach ($listeUsers as $user)
      {
        // Pseudo
        $user->setPseudo(physiquePseudoUser($user->getIdentifiant()));

        // Total de la mission
        $user->setTotal(physiqueTotalUser($idMission, $user->getIdentifiant()));

        // Récupération du tri sur avancement puis identifiant
        $triTotal[]       = $user->getTotal();
        $triIdentifiant[] = $user->getIdentifiant();
      }

      // Tri
      array_multisort($triTotal, SORT_DESC, $triIdentifiant, SORT_ASC, $listeUsers);

      // Affectation du rang
      $prevTotal   = $listeUsers[0]->getTotal();
      $currentRank = 1;

      foreach ($listeUsers as $user)
      {
        $currentTotal = $user->getTotal();

        if ($currentTotal != $prevTotal)
        {
          $currentRank += 1;
          $prevTotal    = $user->getTotal();
        }

        $user->setRank($currentRank);
      }
    }

    // Retour
    return $listeUsers;
  }

  // METIER : Insertion d'une nouvelle mission
  // RETOUR : Erreur éventuelle
  function insertMission($post, $files)
  {
    // Initialisations
    $control_ok = true;
    $erreur     = NULL;

    // Récupération des données
    $mission      = $post['mission'];
    $dateDeb      = $post['date_deb'];
    $dateFin      = $post['date_fin'];
    $heures       = $post['heures'];
    $minutes      = $post['minutes'];
    $description  = $post['description'];
    $reference    = $post['reference'];
    $objectif     = $post['objectif'];
    $explications = $post['explications'];
    $conclusion   = $post['conclusion'];

    // Sauvegarde en session en cas d'erreur
    $_SESSION['save']['new_mission'] = array('post' => $post, 'files' => $files);

    // Remplacement des caractères spéciaux pour la référence
    $search    = array(' ', 'é', 'è', 'ê', 'ë', 'à', 'â', 'ç', 'ô', 'û');
    $replace   = array('_', 'e', 'e', 'e', 'e', 'a', 'a', 'c', 'o', 'u');
    $reference = str_replace($search, $replace, $reference);

    // Formatage heure
    $heure = $heures . $minutes . '00';

    // Contrôle référence unique
    $control_ok = controleReferenceUnique($reference);

    // Contrôle format date début
    if ($control_ok == true)
      $control_ok = controleFormatDate($dateDeb);

    // Formatage de la date de début pour insertion
    if ($control_ok == true)
      $dateDeb = formatDateForInsert($dateDeb);

    // Contrôle format date fin
    if ($control_ok == true)
      $control_ok = controleFormatDate($dateFin);

    // Formatage de la date de fin pour insertion
    if ($control_ok == true)
      $dateFin = formatDateForInsert($dateFin);

    // Contrôle date début <= date fin
    if ($control_ok == true)
      $control_ok = controleOrdreDates($dateDeb, $dateFin);

    // Contrôle objectif numérique
    if ($control_ok == true)
      $control_ok = controleObjectifNumerique($objectif);

    // Contrôle images présentes
    if ($control_ok == true)
    {
      foreach ($files as $file)
      {
        $control_ok = controlePresenceFichier($file['name']);
      }
    }

    // Vérification des dossiers et contrôle des fichiers
    if ($control_ok == true)
    {
      // On vérifie la présence du dossier des images, sinon on le créé
      $dossier = '../../includes/images/missions';

      if (!is_dir($dossier))
        mkdir($dossier);

      // On vérifie la présence du dossier des bannières, sinon on le créé
      $dossierImages = $dossier . '/banners';

      if (!is_dir($dossierImages))
        mkdir($dossierImages);

      // On vérifie la présence du dossier des boutons, sinon on le créé
      $dossierIcones = $dossier . '/buttons';

      if (!is_dir($dossierIcones))
        mkdir($dossierIcones);

      // Contrôle des fichiers
      foreach ($files as $keyFile => $file)
      {
        // Nom du fichier
        switch ($keyFile)
        {
          case 'mission_icone_g':
            $name = $reference . '_g';
            break;

          case 'mission_icone_m':
            $name = $reference . '_m';
            break;

          case 'mission_icone_d':
            $name = $reference . '_d';
            break;

          case 'mission_image':
          default:
            $name = $reference;
            break;
        }

        // Contrôles communs d'un fichier
        $fileDatas  = controlsUploadFile($file, $name, 'png');

        // Récupération contrôles
        $control_ok = controleFichier($fileDatas);

        // Arrêt de la boucle en cas d'erreur
        if ($control_ok == false)
          break;
      }
    }

    // Insertion des images dans les dossiers
    if ($control_ok == true)
    {
      // Insertion des fichiers
      foreach ($files as $keyFile => $file)
      {
        // Dossier de destination
        if ($keyFile == 'mission_image')
          $destDir = $dossierImages . '/';
        else
          $destDir = $dossierIcones . '/';

        // Nouveau nom
        switch ($keyFile)
        {
          case 'mission_icone_g':
            $newName = $reference . '_g.png';
            break;

          case 'mission_icone_m':
            $newName = $reference . '_m.png';
            break;

          case 'mission_icone_d':
            $newName = $reference . '_d.png';
            break;

          case 'mission_image':
          default:
            $newName = $reference . '.png';
            break;
        }

        // Données à envoyer pour l'upload
        $fileDatas = array('control_ok' => true,
                           'new_name'   => $newName,
                           'tmp_file'   => $file['tmp_name'],
                           'type_file'  => $file['type']
                          );

        // Upload fichier
        $control_ok = uploadFile($fileDatas, $destDir);

        // Arrêt de la boucle en cas d'erreur
        if ($control_ok == false)
          break;
      }
    }

    // Insertion de l'enregistrement en base
    if ($control_ok == true)
    {
      $mission = array('mission'      => $mission,
                       'reference'    => $reference,
                       'date_deb'     => $dateDeb,
                       'date_fin'     => $dateFin,
                       'heure'        => $heure,
                       'objectif'     => $objectif,
                       'description'  => $description,
                       'explications' => $explications,
                       'conclusion'   => $conclusion
                      );

      physiqueInsertionMission($mission);

      // Message d'alerte
      $_SESSION['alerts']['mission_added'] = true;
    }

    // Positionnement erreur
    if ($control_ok != true)
      $erreur = true;

    // Retour
    return $erreur;
  }

  // METIER : Modification d'une mission existante
  // RETOUR : Id mission
  function updateMission($post, $files)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $idMission    = $post['id_mission'];
    $mission      = $post['mission'];
    $dateDeb      = $post['date_deb'];
    $dateFin      = $post['date_fin'];
    $heures       = $post['heures'];
    $minutes      = $post['minutes'];
    $description  = $post['description'];
    $reference    = $post['reference'];
    $objectif     = $post['objectif'];
    $explications = $post['explications'];
    $conclusion   = $post['conclusion'];

    // Sauvegarde en session en cas d'erreur
    $_SESSION['save']['old_mission'] = array('post' => $post, 'files' => $files);

    // Remplacement des caractères spéciaux pour la référence
    $search    = array(' ', 'é', 'è', 'ê', 'ë', 'à', 'â', 'ç', 'ô', 'û');
    $replace   = array('_', 'e', 'e', 'e', 'e', 'a', 'a', 'c', 'o', 'u');
    $reference = str_replace($search, $replace, $reference);

    // Formatage heure
    $heure = $heures . $minutes . '00';

    // Contrôle format date début
    if ($control_ok == true)
      $control_ok = controleFormatDate($dateDeb);

    // Formatage de la date de début pour insertion
    if ($control_ok == true)
      $dateDeb = formatDateForInsert($dateDeb);

    // Contrôle format date fin
    if ($control_ok == true)
      $control_ok = controleFormatDate($dateFin);

    // Formatage de la date de fin pour insertion
    if ($control_ok == true)
      $dateFin = formatDateForInsert($dateFin);

    // Contrôle date début <= date fin
    if ($control_ok == true)
      $control_ok = controleOrdreDates($dateDeb, $dateFin);

    // Contrôle objectif numérique
    if ($control_ok == true)
      $control_ok = controleObjectifNumerique($objectif);

    // Contrôle images présentes, si présentes alors on modifie l'image
    if ($control_ok == true)
    {
      // Chemins
      $dossierImages = '../../includes/images/missions/banners';
      $dossierIcones = '../../includes/images/missions/buttons';

      // Contrôle des fichiers
      foreach ($files as $keyFile => $file)
      {
        if (!empty($file['name']))
        {
          // Nom du fichier
          switch ($keyFile)
          {
            case 'mission_icone_g':
              $name = $reference . '_g';
              break;

            case 'mission_icone_m':
              $name = $reference . '_m';
              break;

            case 'mission_icone_d':
              $name = $reference . '_d';
              break;

            case 'mission_image':
            default:
              $name = $reference;
              break;
          }

          // Contrôles communs d'un fichier
          $fileDatas  = controlsUploadFile($file, $name, 'png');

          // Récupération contrôles
          $control_ok = controleFichier($fileDatas);

          // Arrêt de la boucle en cas d'erreur
          if ($control_ok == false)
            break;
        }
      }
    }

    // Insertion des images dans les dossiers
    if ($control_ok == true)
    {
      // Insertion des fichiers
      foreach ($files as $keyFile => $file)
      {
        if (!empty($file['name']))
        {
          // Dossier de destination
          if ($keyFile == 'mission_image')
            $destDir = $dossierImages . '/';
          else
            $destDir = $dossierIcones . '/';

          // Nouveau nom
          switch ($keyFile)
          {
            case 'mission_icone_g':
              $newName = $reference . '_g.png';
              break;

            case 'mission_icone_m':
              $newName = $reference . '_m.png';
              break;

            case 'mission_icone_d':
              $newName = $reference . '_d.png';
              break;

            case 'mission_image':
            default:
              $newName = $reference . '.png';
              break;
          }

          // Suppression de l'ancienne image
          unlink($destDir . $newName);

          // Données à envoyer pour l'upload
          $fileDatas = array('control_ok' => true,
                             'new_name'   => $newName,
                             'tmp_file'   => $file['tmp_name'],
                             'type_file'  => $file['type']
                            );

          // Upload fichier
          $control_ok = uploadFile($fileDatas, $destDir);

          // Arrêt de la boucle en cas d'erreur
          if ($control_ok == false)
            break;
        }
      }
    }

    // Modification de l'enregistrement en base
    if ($control_ok == true)
    {
      $mission = array('mission'      => $mission,
                       'date_deb'     => $dateDeb,
                       'date_fin'     => $dateFin,
                       'heure'        => $heure,
                       'objectif'     => $objectif,
                       'description'  => $description,
                       'explications' => $explications,
                       'conclusion'   => $conclusion
                      );

      physiqueUpdateMission($idMission, $mission);

      // Message d'alerte
      $_SESSION['alerts']['mission_updated'] = true;
    }

    return $idMission;
  }

  // METIER : Suppression d'une mission
  // RETOUR : Aucun
  function deleteMission($post)
  {
    // Récupération des données
    $idMission = $post['id_mission'];

    // Récupération des données de la mission
    $mission = physiqueMission($idMission);

    // Suppression des images
    if (!empty($mission->getReference()))
    {
      unlink('../../includes/images/missions/banners/' . $mission->getReference() . '.png');
      unlink('../../includes/images/missions/buttons/' . $mission->getReference() . '_g.png');
      unlink('../../includes/images/missions/buttons/' . $mission->getReference() . '_m.png');
      unlink('../../includes/images/missions/buttons/' . $mission->getReference() . '_d.png');
    }

    // Suppression de l'enregistrement en base
    physiqueDeleteMission($idMission);

    // Suppression des participations
    physiqueDeleteMissionUsers($idMission);

    // Suppression des notifications
    deleteNotification('start_mission', $idMission);
    deleteNotification('end_mission', $idMission);
    deleteNotification('one_mission', $idMission);

    // Message d'alerte
    $_SESSION['alerts']['mission_deleted'] = true;
  }
?>
