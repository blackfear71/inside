<?php
  include_once('../../includes/classes/profile.php');
  include_once('../../includes/classes/success.php');
  include_once('../../includes/libraries/php/imagethumb.php');

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Liste des utilisateurs
  function getUsers()
  {
    // Récupération de la liste des utilisateurs
    $listeUsers = physiqueUsers();

    return $listeUsers;
  }

  // METIER : Lecture liste des succès
  // RETOUR : Liste des succès
  function getSuccess()
  {
    $listSuccess = physiqueListeSuccess();

    return $listSuccess;
  }

  // METIER : Insertion nouveau succès
  // RETOUR : Aucun
  function insertSuccess($post, $files)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $reference    = $post['reference'];
    $level        = $post['level'];
    $orderSuccess = $post['order_success'];
    $defined      = 'N';
    $title        = $post['title'];
    $description  = $post['description'];
    $limitSuccess = $post['limit_success'];
    $explanation  = $post['explanation'];

    if (isset($post['unicity']))
      $unicity     = 'Y';
    else
      $unicity     = 'N';

    // Sauvegarde en session en cas d'erreur
    $_SESSION['save']['reference_success']   = $reference;
    $_SESSION['save']['level']               = $level;
    $_SESSION['save']['unicity']             = $unicity;
    $_SESSION['save']['order_success']       = $orderSuccess;
    $_SESSION['save']['title_success']       = $title;
    $_SESSION['save']['description_success'] = $description;
    $_SESSION['save']['limit_success']       = $limitSuccess;
    $_SESSION['save']['explanation_success'] = $explanation;

    // Contrôle référence unique
    $control_ok = controleReferenceUnique($reference);

    // Contrôle niveau numérique et positif
    if ($control_ok == true)
      $control_ok = controleNumerique($level, 'level_not_numeric');

    // Contrôle ordonnancement numérique et positif
    if ($control_ok == true)
      $control_ok = controleNumerique($orderSuccess, 'order_not_numeric');

    // Contrôle ordonnancement unique
    if ($control_ok == true)
      $control_ok = controleOrdonnancementUnique($level, $orderSuccess);

    // Contrôle condition numérique et positif
    if ($control_ok == true)
      $control_ok = controleNumerique($limitSuccess, 'limit_not_numeric');

    // Vérification des dossiers et contrôle des fichiers
    if ($control_ok == true)
    {
      // On vérifie la présence du dossier, sinon on le créé
      $dossier = '../../includes/images/profil';

      if (!is_dir($dossier))
         mkdir($dossier);

      // On vérifie la présence du dossier des succès, sinon on le créé
      $dossierSuccess = $dossier . '/success';

      if (!is_dir($dossierSuccess))
         mkdir($dossierSuccess);

      // Dossier de destination
      $successDir = $dossierSuccess . '/';

      // Contrôles communs d'un fichier
      $fileDatas  = controlsUploadFile($files['success'], $reference, 'png');

      // Récupération contrôles
      $control_ok = controleFichier($fileDatas);
    }

    // Upload fichier
    if ($control_ok == true)
      $control_ok = uploadFile($files['success'], $fileDatas, $successDir);

    // Création miniature et insertion en base
    if ($control_ok == true)
    {
      // Créé une miniature de la source vers la destination en la rognant avec une hauteur/largeur max de 500px (cf fonction imagethumb.php)
      imagethumb($successDir . $fileDatas['new_name'], $successDir . $fileDatas['new_name'], 500, FALSE, TRUE);

      // Insertion de l'enregistrement en base
      $success = array('reference'     => $reference,
                       'level'         => $level,
                       'order_success' => $orderSuccess,
                       'defined'       => $defined,
                       'unicity'       => $unicity,
                       'title'         => $title,
                       'description'   => $description,
                       'limit_success' => $limitSuccess,
                       'explanation'   => $explanation
                      );

      physiqueInsertionSuccess($success);

      // Message d'alerte
      $_SESSION['alerts']['success_added'] = true;
    }
  }

  // METIER : Suppression succès
  // RETOUR : Aucun
  function deleteSuccess($post)
  {
    // Récupération des données du succès
    $success = physiqueSuccess($post['id_success']);

    // Suppression de l'image
    unlink('../../includes/images/profil/success/' . $success->getReference() . '.png');

    // Suppression des données utilisateurs
    physiqueDeleteSuccessUsers($success->getReference());

    // Suppression du succès de la base
    physiqueDeleteSuccess($success->getReference());

    // Message d'alerte
    $_SESSION['alerts']['success_deleted'] = true;
  }

  // METIER : Modification succès
  // RETOUR : Aucun
  function updateSuccess($post)
  {
    // Initialisations
    $control_ok = true;
    $erreur     = NULL;

    // Récupération des données
    $updateSuccess = array();

    foreach ($post['id'] as $id)
    {
      $myUpdate = array('id'            => $post['id'][$id],
                        'level'         => $post['level'][$id],
                        'order_success' => $post['order_success'][$id],
                        'defined'       => $post['defined'][$id],
                        'unicity'       => $post['unicity'][$id],
                        'title'         => $post['title'][$id],
                        'description'   => $post['description'][$id],
                        'limit_success' => $post['limit_success'][$id],
                        'explanation'   => $post['explanation'][$id],
                       );

      array_push($updateSuccess, $myUpdate);
    }

    // Sauvegarde en session en cas d'erreur
    $_SESSION['save']['save_success'] = $post;

    // Boucle de traitement des succès
    foreach ($updateSuccess as $success)
    {
      // Contrôle niveau numérique et positif
      if ($control_ok == true)
        $control_ok = controleNumerique($success['level'], 'level_not_numeric');

      // Contrôle ordonnancement numérique et positif
      if ($control_ok == true)
        $control_ok = controleNumerique($success['order_success'], 'order_not_numeric');

      // Contrôle doublon saisie
      if ($control_ok == true)
        $control_ok = controleDoublons($updateSuccess, $success);

      // Contrôle condition numérique et positif
      if ($control_ok == true)
        $control_ok = controleNumerique($success['limit_success'], 'limit_not_numeric');

      // Arrêt de la boucle en cas d'erreur
      if ($control_ok == false)
      {
        $erreur = true;
        break;
      }
    }

    // Mise à jour des succès
    if ($control_ok == true)
    {
      foreach ($updateSuccess as $success)
      {
        physiqueUpdateSuccess($success);
      }

      $_SESSION['alerts']['success_updated'] = true;
    }

    // Retour
    return $erreur;
  }

  // METIER : Initialisation champs erreur modification succès
  // RETOUR : Tableau sauvegardé et trié
  function initModErrSucces($listSuccess, $sessionListSuccess)
  {
    // Récupération des données modifiées
    foreach ($listSuccess as $success)
    {
      $success->setLevel($sessionListSuccess['level'][$success->getId()]);
      $success->setOrder_success($sessionListSuccess['order_success'][$success->getId()]);
      $success->setDefined($sessionListSuccess['defined'][$success->getId()]);
      $success->setUnicity($sessionListSuccess['unicity'][$success->getId()]);
      $success->setTitle($sessionListSuccess['title'][$success->getId()]);
      $success->setDescription($sessionListSuccess['description'][$success->getId()]);
      $success->setLimit_success($sessionListSuccess['limit_success'][$success->getId()]);
      $success->setExplanation($sessionListSuccess['explanation'][$success->getId()]);
    }

    // Retour
    return $listSuccess;
  }

  // METIER : Purge tous les succès
  // RETOUR : Aucun
  function purgeSuccess()
  {
    // Suppression des succès (sauf exceptions)
    physiqueDeleteSuccessAdmin();

    // Rénumérotation des enregistrements restants
    physiqueRenumerotationSuccess();

    // Message d'alerte
    $_SESSION['alerts']['success_purged'] = true;
  }

  // METIER : Initialisation des succès
  // RETOUR : Aucun
  function initializeSuccess($listSuccess, $listUsers)
  {
    // Détermination de chaque succès pour chaque utilisateur
    if (!empty($listSuccess) AND !empty($listUsers))
    {
      foreach ($listUsers as $user)
      {
        // Boucle de traitement sur les succès
        foreach ($listSuccess as $success)
        {
          // Initialisations
          $value          = NULL;
          $action         = NULL;
          $listConditions = array();

          // Détermination valeur à insérer
          switch ($success->getReference())
          {
            // J'étais là
            case 'beginning':
            // Je l'ai fait !
            case 'developper':
              $listConditions = array(array('operator' => '',
                                            'column'   => 'reference',
                                            'test'     => '=',
                                            'value'    => $success->getReference()),
                                      array('operator' => 'AND',
                                            'column'   => 'identifiant',
                                            'test'     => '=',
                                            'value'    => $user->getIdentifiant()));

              physiqueValueSuccess('success_users', $listConditions, 'value');
              break;

            // Cinéphile amateur
            case 'publisher':
              $listConditions = array(array('operator' => '',
                                            'column'   => 'identifiant_add',
                                            'test'     => '=',
                                            'value'    => $user->getIdentifiant()),
                                      array('operator' => 'AND',
                                            'column'   => 'to_delete',
                                            'test'     => '!=',
                                            'value'    => 'Y'));

              $value = physiqueCountSuccess('movie_house', $listConditions);
              break;

            // Cinéphile professionnel
            case 'viewer':
              $listConditions = array(array('operator' => '',
                                            'column'   => 'identifiant',
                                            'test'     => '=',
                                            'value'    => $user->getIdentifiant()),
                                      array('operator' => 'AND',
                                            'column'   => 'participation',
                                            'test'     => '=',
                                            'value'    => 'S'));

              $value = physiqueCountSuccess('movie_house_users', $listConditions);
              break;

            // Commentateur sportif
            case 'commentator':
              $listConditions = array(array('operator' => '',
                                            'column'   => 'author',
                                            'test'     => '=',
                                            'value'    => $user->getIdentifiant()));

              $value = physiqueCountSuccess('movie_house_comments', $listConditions);
              break;

            // Expert acoustique
            case 'listener':
              $listConditions = array(array('operator' => '',
                                            'column'   => 'author',
                                            'test'     => '=',
                                            'value'    => $user->getIdentifiant()));

              $value = physiqueCountSuccess('collector', $listConditions);
              break;

            // Dommage collatéral
            case 'speaker':
              $listConditions = array(array('operator' => '',
                                            'column'   => 'speaker',
                                            'test'     => '=',
                                            'value'    => $user->getIdentifiant()));

              $value = physiqueCountSuccess('collector', $listConditions);
              break;

            // Rigolo compulsif
            case 'funny':
              $listConditions = array(array('operator' => '',
                                            'column'   => 'identifiant',
                                            'test'     => '=',
                                            'value'    => $user->getIdentifiant()));

              $value = physiqueCountSuccess('collector_users', $listConditions);
              break;

            // Auto-satisfait
            case 'self-satisfied':
              $value = physiqueSelfSatisfiedSuccess($user->getIdentifiant());
              break;

            // Désigné volontaire
            case 'buyer':
              $value = physiqueBuyerSuccess($user->getIdentifiant());
              break;

            // Profiteur occasionnel
            case 'eater':
              $listConditions = array(array('operator' => '',
                                            'column'   => 'identifiant',
                                            'test'     => '=',
                                            'value'    => $user->getIdentifiant()));

              $value = physiqueSumSuccess('expense_center_users', $listConditions, 'parts');
              break;

            // Mer il et fou !
            case 'generous':
              $value = physiqueGenerousSuccess($user->getIdentifiant());
              break;

            // Economie de marché
            case 'greedy':
              // Récupération du bilan de l'utilisateur
              $bilan = physiqueBilanUser($user->getIdentifiant());

              $listConditions = array(array('operator' => '',
                                            'column'   => 'reference',
                                            'test'     => '=',
                                            'value'    => $success->getReference()),
                                      array('operator' => 'AND',
                                            'column'   => 'identifiant',
                                            'test'     => '=',
                                            'value'    => $user->getIdentifiant()));

              $value = physiqueValueSuccess('success_users', $listConditions, 'value');

              if (is_null($value) OR $bilan > $value)
                $value = $bilan;
              break;

            // Génie créatif
            case 'creator':
              $listConditions = array(array('operator' => '',
                                            'column'   => 'author',
                                            'test'     => '=',
                                            'value'    => $user->getIdentifiant()));

              $value = physiqueCountSuccess('ideas', $listConditions);
              break;

            // Top développeur
            case 'applier':
              $listConditions = array(array('operator' => '',
                                            'column'   => 'developper',
                                            'test'     => '=',
                                            'value'    => $user->getIdentifiant()),
                                      array('operator' => 'AND',
                                            'column'   => 'status',
                                            'test'     => '=',
                                            'value'    => 'D'));

              $value = physiqueCountSuccess('ideas', $listConditions);
              break;

            // Débugger aguerri
            case 'debugger':
              $listConditions = array(array('operator' => '',
                                            'column'   => 'author',
                                            'test'     => '=',
                                            'value'    => $user->getIdentifiant()));

              $value = physiqueCountSuccess('bugs', $listConditions);
              break;

            // Compilateur intégré
            case 'compiler':
              $listConditions = array(array('operator' => '',
                                            'column'   => 'author',
                                            'test'     => '=',
                                            'value'    => $user->getIdentifiant()),
                                      array('operator' => 'AND',
                                            'column'   => 'resolved',
                                            'test'     => '=',
                                            'value'    => 'Y'));

              $value = physiqueCountSuccess('bugs', $listConditions);
              break;

            // Véritable Jedi
            case 'padawan':
              // Récupération date de sortie Star Wars VIII
              $dateSortieSW8 = physiqueDateSortieFilm(16);

              if (date('Ymd') >= $dateSortieSW8)
              {
                $listConditions = array(array('operator' => '',
                                              'column'   => 'id_film',
                                              'test'     => '=',
                                              'value'    => 16),
                                        array('operator' => 'AND',
                                              'column'   => 'identifiant',
                                              'test'     => '=',
                                              'value'    => $user->getIdentifiant()),
                                        array('operator' => 'AND',
                                              'column'   => 'participation',
                                              'test'     => '=',
                                              'value'    => 'S'));

                $isSeen = physiqueCountSuccess('movie_house_users', $listConditions);

                if ($isSeen > 0)
                  $value = 1;
              }
              break;

            // Radar à bouffe
            case 'restaurant-finder':
              $value = 0;
              break;

            // Chef étoilé
            case 'star-chief':
              $listConditions = array(array('operator' => '',
                                            'column'   => 'caller',
                                            'test'     => '=',
                                            'value'    => $user->getIdentifiant()));

              $value = physiqueCountSuccess('food_advisor_choices', $listConditions);
              break;

            // Cuisto expérimental
            case 'cooker':
              $listConditions = array(array('operator' => '',
                                            'column'   => 'identifiant',
                                            'test'     => '=',
                                            'value'    => $user->getIdentifiant()),
                                      array('operator' => 'AND',
                                            'column'   => 'cooked',
                                            'test'     => '=',
                                            'value'    => 'Y'));

              $value = physiqueCountSuccess('cooking_box', $listConditions);
              break;

            // Maître pâtissier
            case 'recipe-master':
              $listConditions = array(array('operator' => '',
                                            'column'   => 'identifiant',
                                            'test'     => '=',
                                            'value'    => $user->getIdentifiant()),
                                      array('operator' => 'AND',
                                            'column'   => 'name',
                                            'test'     => '!=',
                                            'value'    => ''),
                                      array('operator' => 'AND',
                                            'column'   => 'picture',
                                            'test'     => '!=',
                                            'value'    => ''));

              $value = physiqueCountSuccess('cooking_box', $listConditions);
              break;

            // Niveaux
            case 'level_1':
            case 'level_5':
            case 'level_10':
              $listConditions = array(array('operator' => '',
                                            'column'   => 'identifiant',
                                            'test'     => '=',
                                            'value'    => $user->getIdentifiant()));

              $experience = physiqueValueSuccess('users', $listConditions, 'experience');

              if ($experience > 0)
                $value = convertExperience($experience);
              break;

            // Lutin de Noël
            case 'christmas2017':
            // Je suis ton Père Noël !
            case 'christmas2017_2':
            // Un coeur en or
            case 'golden-egg':
            // Mettre tous ses oeufs dans le même panier
            case 'rainbow-egg':
            // Apprenti sorcier
            case 'wizard':
            // Le plein de cadeaux !
            case 'christmas2018':
            // C'est tout ce que j'ai ?!
            case 'christmas2018_2':
            // Première étoile
            case 'christmas2019':
              // Récupération des données de la mission
              if ($success->getReference() == 'christmas2017' OR $success->getReference() == 'christmas2017_2')
                $reference = 'noel_2017';
              elseif ($success->getReference() == 'golden-egg' OR $success->getReference() == 'rainbow-egg')
                $reference = 'paques_2018';
              elseif ($success->getReference() == 'wizard')
                $reference = 'halloween_2018';
              elseif ($success->getReference() == 'christmas2018' OR $success->getReference() == 'christmas2018_2')
                $reference = 'noel_2018';
              elseif ($success->getReference() == 'christmas2019')
                $reference = 'noel_2019';

              $mission = physiqueDonneesMission($reference);

              if (date('Ymd') > $mission->getDate_fin())
              {
                $listConditions = array(array('operator' => '',
                                              'column'   => 'id_mission',
                                              'test'     => '=',
                                              'value'    => $mission->getId()),
                                        array('operator' => 'AND',
                                              'column'   => 'identifiant',
                                              'test'     => '=',
                                              'value'    => $user->getIdentifiant()));

                $value = physiqueSumSuccess('missions_users', $listConditions, 'avancement');
              }
              break;

            default:
              break;
          }

          // Détermination action à effectuer
          if (!is_null($value) AND $value != 0)
          {
            $listConditions = array(array('operator' => '',
                                          'column'   => 'reference',
                                          'test'     => '=',
                                          'value'    => $success->getReference()),
                                    array('operator' => 'AND',
                                          'column'   => 'identifiant',
                                          'test'     => '=',
                                          'value'    => $user->getIdentifiant()));

            $oldValue = physiqueValueSuccess('success_users', $listConditions, 'value');

            // Mise à jour seulement si la nouvelle valeur est supérieure à l'ancienne
            if (!is_null($oldValue))
            {
              if ($value > $oldValue)
                $action = 'update';
            }
            else
              $action = 'insert';
          }

          // Insertion / modification de chaque succès
          switch ($action)
          {
            case 'insert':
              $successUser = array('reference'   => $success->getReference(),
                                   'identifiant' => $user->getIdentifiant(),
                                   'value'       => $value);

              physiqueInsertionSuccessUser($successUser);
              break;

            case 'update':
              physiqueUpdateSuccessUser($success->getReference(), $user->getIdentifiant(), $value);
              break;

            default:
              break;
          }

          // Purge éventuelle des succès à 0
          physiqueDeleteSuccessNoValue();
        }
      }
    }

    // Message d'alerte
    $_SESSION['alerts']['success_initialized'] = true;
  }
?>
