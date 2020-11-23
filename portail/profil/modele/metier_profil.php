<?php
  include_once('../../includes/classes/profile.php');
  include_once('../../includes/classes/success.php');
  include_once('../../includes/libraries/php/imagethumb.php');

  // METIER : Lecture des données profil
  // RETOUR : Objet Profile
  function getProfile($identifiant)
  {
    // Récupération des données du profil
    $profil = physiqueProfil($identifiant);

    // Retour
    return $profil;
  }

  // METIER : Lecture des données statistiques profil
  // RETOUR : Objet Statistiques
  function getStatistiques($identifiant)
  {
    // Films ajoutés
    $nombreFilms = physiqueFilmsAjoutesUser($identifiant);

    // Commentaires films
    $nombreComments = physiqueCommentairesFilmsUser($identifiant);

    // Réservations de restaurants
    $nombreReservations = physiqueReservationsUser($identifiant);

    // Gâteaux de la semaine
    $nombreGateauxSemaine = physiqueGateauxSemaineUser($identifiant);

    // Recettes partagées
    $nombreRecettes = physiqueRecettesUser($identifiant);

    // Bilan des dépenses
    $bilanUser = physiqueBilanDepensesUser($identifiant);

    // Phrases et images cultes ajoutées
    $nombreCollector = physiqueCollectorAjoutesUser($identifiant);

    // Nombre d'idées publiées
    $nombreTheBox = physiqueTheBoxUser($identifiant);

    // Bugs soumis
    $nombreBugsSoumis = physiqueBugsEvolutionsSoumisUser($identifiant, 'B');

    // Evolutions soumises
    $nombreEvolutionsSoumises = physiqueBugsEvolutionsSoumisUser($identifiant, 'E');

    // Génération d'un objet StatistiquesProfil
    $statistiques = array('nb_films_ajoutes' => $nombreFilms,
                          'nb_comments'      => $nombreComments,
                          'nb_collectors'    => $nombreCollector,
                          'nb_reservations'  => $nombreReservations,
                          'nb_gateaux'       => $nombreGateauxSemaine,
                          'nb_recettes'      => $nombreRecettes,
                          'expenses'         => $bilanUser,
                          'nb_ideas'         => $nombreTheBox,
                          'nb_bugs'          => $nombreBugsSoumis,
                          'nb_evolutions'    => $nombreEvolutionsSoumises
                         );

    $tableauStatistiques = StatistiquesProfil::withData($statistiques);

    // Retour
    return $tableauStatistiques;
  }

  // METIER : Lecture des données préférences
  // RETOUR : Objet Preferences
  function getPreferences($identifiant)
  {
    // Récupération des données préférences
    $preferences = physiquePreferences($identifiant);

    // Retour
    return $preferences;
  }

  // METIER : Récupération des données de progression
  // RETOUR : Tableau des données de progression
  function getProgress($experience)
  {
    // Calcul de la progression
    $niveau   = convertExperience($experience);
    $expMin   = 10 * $niveau ** 2;
    $expMax   = 10 * ($niveau + 1) ** 2;
    $expLvl   = $expMax - $expMin;
    $progress = $experience - $expMin;
    $percent  = floor($progress * 100 / $expLvl);

    // Génération d'un objet Progression
    $progression = new Progression();

    $progression->setNiveau($niveau);
    $progression->setExperience_min($expMin);
    $progression->setExperience_max($expMax);
    $progression->setExperience_niveau($expLvl);
    $progression->setProgression($progress);
    $progression->setPourcentage($percent);

    // Retour
    return $progression;
  }

  // METIER : Mise à jour de l'avatar (base + fichier)
  // RETOUR : Aucun
  function updateAvatar($identifiant, $files)
  {
    // Initialisations
    $control_ok = true;
    $avatar     = rand();

    // Dossier de destination
    $dossier = '../../includes/images/profil/avatars';

    // Contrôles fichier
    $fileDatas = controlsUploadFile($files['avatar'], $avatar, 'all');

    // Récupération contrôles
    $control_ok = controleFichier($fileDatas);

    // Upload fichier
    if ($control_ok == true)
      $control_ok = uploadFile($fileDatas, $dossier);

    if ($control_ok == true)
    {
      $newName = $fileDatas['new_name'];

      // Créé une miniature de la source vers la destination en la rognant avec une hauteur/largeur max de 400px (cf fonction imagethumb.php)
      imagethumb($dossier . '/' . $newName, $dossier . '/' . $newName, 400, FALSE, TRUE);

      // Suppression de l'ancien avatar si présent
      $oldAvatar = physiqueAvatarUser($identifiant);

      if (!empty($oldAvatar))
        unlink($dossier . '/' . $oldAvatar . '');

      // Modification de l'enregistrement en base
      physiqueUpdateAvatarUser($identifiant, $newName);

      // Mise à jour de la session
      $_SESSION['user']['avatar'] = $newName;

      // Message d'alerte
      $_SESSION['alerts']['avatar_updated'] = true;
    }
  }

  // METIER : Suppression de l'avatar
  // RETOUR : Aucun
  function deleteAvatar($identifiant)
  {
    // Dossier de destination
    $dossier = '../../includes/images/profil/avatars/';

    // Suppression de l'ancien avatar si présent
    $oldAvatar = physiqueAvatarUser($identifiant);

    if (!empty($oldAvatar))
      unlink($dossier . $oldAvatar . '');

    // Modification de l'enregistrement en base
    physiqueUpdateAvatarUser($identifiant, '');

    // Mise à jour de la session
    $_SESSION['user']['avatar'] = '';

    // Message d'alerte
    $_SESSION['alerts']['avatar_deleted'] = true;
  }

  // METIER : Mise à jour des informations
  // RETOUR : Aucun
  function updateInfos($identifiant, $post, $isMobile)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $email = $post['email'];

    if (isset($post['pseudo']) AND !empty($post['pseudo']))
      $pseudo = trim($post['pseudo']);
    else
      $pseudo = $_SESSION['user']['pseudo'];

    if (isset($post['anniversaire']) AND !empty($post['anniversaire']))
    {
      if ($isMobile == true)
        $anniversary = formatDateForInsertMobile($post['anniversaire']);
      else
        $anniversary = formatDateForInsert($post['anniversaire']);
    }
    else
      $anniversary = '';

    // Contrôles date d'anniversaire
    if (isset($post['anniversaire']) AND !empty($post['anniversaire']))
    {
      // Contrôle format date
      $control_ok = controleFormatDate($post['anniversaire'], $isMobile);

      // Contrôle date dans le futur
      if ($control_ok == true)
        $control_ok = controleDateFutur($anniversary);
    }

    // Modification de l'enregistrement en base
    if ($control_ok == true)
    {
      $user = array('pseudo'      => $pseudo,
                    'email'       => $email,
                    'anniversary' => $anniversary
                   );

      physiqueUpdateUser($user, $identifiant);

      // Mise à jour de la session
      $_SESSION['user']['pseudo'] = htmlspecialchars($pseudo);

      // Message d'alerte
      $_SESSION['alerts']['infos_updated'] = true;
    }
  }

  // METIER : Mise à jour des préférences
  // RETOUR : Aucun
  function updatePreferences($identifiant, $post)
  {
    // Initialisations
    $categoriesMovieHouse = '';

    // Récupération des données
    $viewNotifications = $post['notifications_view'];
		$viewMovieHouse    = $post['movie_house_view'];
    $viewTheBox        = $post['the_box_view'];
    $initChat          = $post['inside_room_view'];
    $celsius           = $post['celsius_view'];

    if (isset($post['films_semaine']))
			$categoriesMovieHouse .= 'Y;';
		else
			$categoriesMovieHouse .= 'N;';

		if (isset($post['films_waited']))
			$categoriesMovieHouse .= 'Y;';
		else
			$categoriesMovieHouse .= 'N;';

		if (isset($post['films_way_out']))
			$categoriesMovieHouse .= 'Y;';
		else
			$categoriesMovieHouse .= 'N;';

    // Réinitialisation des cookies de position Celsius
    if ($celsius == 'N')
    {
      setcookie('celsius[positionX]', null, -1, '/');
      setcookie('celsius[positionY]', null, -1, '/');
    }

    // Modification de l'enregistrement en base
    $preferences = array('init_chat'              => $initChat,
                         'celsius'                => $celsius,
                         'view_movie_house'       => $viewMovieHouse,
                         'categories_movie_house' => $categoriesMovieHouse,
                         'view_the_box'           => $viewTheBox,
                         'view_notifications'     => $viewNotifications
                        );

    physiqueUpdatePreferences($preferences, $identifiant);

    // Mise à jour de la session
    $_SESSION['user']['celsius']            = $celsius;
    $_SESSION['user']['view_movie_house']   = $viewMovieHouse;
    $_SESSION['user']['view_the_box']       = $viewTheBox;
    $_SESSION['user']['view_notifications'] = $viewNotifications;

    // Message d'alerte
    $_SESSION['alerts']['preferences_updated'] = true;
  }

  // METIER : Mise à jour du mot de passe
  // RETOUR : Aucun
  function updatePassword($identifiant, $post)
  {
    // Initialisations
    $control_ok = true;

    // Si on a saisi toutes les données
    if (!empty($post['old_password'])
    AND !empty($post['new_password'])
    AND !empty($post['confirm_new_password']))
    {
      // Récupération des données du mot de passe
      $crypt = physiqueDonneesPasswordUser($identifiant);

      // Cryptage ancien mot de passe saisi
      $oldPassword = htmlspecialchars(hash('sha1', $post['old_password'] . $crypt['salt']));

      // Contrôle correspondance ancien mot de passe
      $control_ok = controleCorrespondancePassword($oldPassword, $crypt['password']);

      // Contrôle correspondance nouveau mot de passe
      if ($control_ok == true)
      {
        $salt               = rand();
        $newPassword        = htmlspecialchars(hash('sha1', $post['new_password'] . $salt));
        $confirmNewPassword = htmlspecialchars(hash('sha1', $post['confirm_new_password'] . $salt));

        $control_ok = controleCorrespondancePassword($confirmNewPassword, $newPassword);
      }

      // Modification de l'enregistrement en base
      if ($control_ok == true)
      {
        physiqueUpdatePasswordUser($salt, $newPassword, $identifiant);

        // Réinitialisation des cookies de connexion
        setcookie('index[identifiant]', null, -1, '/');
        setcookie('index[password]', null, -1, '/');

        // Définition des nouveaux cookies de connexion
        setCookie('index[identifiant]', $identifiant, time() + 60 * 60 * 24 * 365, '/');
        setCookie('index[password]', $newPassword, time() + 60 * 60 * 24 * 365, '/');

        // Message d'alerte
        $_SESSION['alerts']['password_updated'] = true;
      }
    }
  }

  // METIER : Mise à jour du statut par l'utilisateur (désinscription, mot de passe)
  // RETOUR : Aucun
  function updateStatus($identifiant, $status)
  {
    // Modification de l'enregistrement en base
    physiqueUpdateStatusUser($identifiant, $status);

    // Message d'alerte
    switch ($status)
    {
      case 'D':
        $_SESSION['alerts']['ask_desinscription'] = true;
        break;

      case 'N':
        $_SESSION['alerts']['cancel_status'] = true;
        break;

      default:
        break;
    }
  }

  // METIER : Lecture liste des utilisateurs
  // RETOUR : Liste des utilisateurs
  function getUsers()
  {
    // Récupération liste des utilisateurs
    $listeUsers = physiqueUsers();

    // Récupération des données complémentaires
    foreach ($listeUsers as $user)
    {
      // Récupération du niveau
      $level = convertExperience($user->getExperience());
      $user->setLevel($level);
    }

    // Tri sur expérience puis identifiant
    if (!empty($listeUsers))
    {
      foreach ($listeUsers as $user)
      {
        $triExp[] = $user->getExperience();
        $triId[]  = $user->getIdentifiant();
      }

      array_multisort($triExp, SORT_DESC, $triId, SORT_ASC, $listeUsers);
    }

    // Retour
    return $listeUsers;
  }

  // METIER : Lecture liste des succès
  // RETOUR : Liste des succès et déblocages
  function getSuccess($identifiant, $listeUsers)
  {
    // Initialisations
    $listeSuccess = array();

    // Création tableau de correspondance identifiant / pseudo / avatar
    $tableauUsers = array();

    foreach ($listeUsers as $user)
    {
      $tableauUsers[$user->getIdentifiant()] = array('pseudo' => htmlspecialchars($user->getPseudo()),
                                                     'avatar' => htmlspecialchars($user->getAvatar())
                                                    );
    }

    // Récupération de la liste des succès
    $listeSuccess = physiqueListeSuccess();

    // Récupération des classements des succès
    foreach ($listeSuccess as $success)
    {
      // Récupération valeur succès
      $valueSuccess = physiqueSuccessUser($success->getReference(), $identifiant);

      if ($valueSuccess != NULL)
      {
        // Contrôle pour les missions que la date de fin soit passée
        $missionTermineeOuAutre = controleMissionTermineeOuAutre($success->getReference());

        if ($missionTermineeOuAutre == true)
          $success->setValue_user($valueSuccess);
      }

      // Récupération du classement des utilisateurs
      if ($success->getDefined() == 'Y' AND $success->getUnicity() != 'Y')
      {
        // Contrôle pour les missions que la date de fin soit passée
        $missionTermineeOuAutre = controleMissionTermineeOuAutre($success->getReference());

        // Récupération de l'avancement des utilisateurs
        $listeRangSuccess = physiqueSuccessUsers($success->getReference(), $success->getLimit_success(), $missionTermineeOuAutre, $tableauUsers);

        // Filtrage du tableau
        if (!empty($listeRangSuccess))
        {
          // Affectation du rang et suppression si rang > 3 (médaille de bronze)
          $rangPrecedent = $listeRangSuccess[0]->getValue();
          $rangCourant   = 1;

          foreach ($listeRangSuccess as $key => $rangSuccessUser)
          {
            $currentTotal = $rangSuccessUser->getValue();

            if ($currentTotal != $rangPrecedent)
            {
              $rangCourant += 1;
              $rangPrecedent = $rangSuccessUser->getValue();
            }

            // Suppression des rangs > 3 sinon on enregistre le rang
            if ($rangCourant > 3)
              unset($listeRangSuccess[$key]);
            else
              $rangSuccessUser->setRank($rangCourant);
          }
        }

        // Récupération du classement
        $success->setClassement($listeRangSuccess);
      }
    }

    // Retour
    return $listeSuccess;
  }

  // METIER : Conversion de la liste d'objets des succès en tableau simple pour JSON
  // RETOUR : Tableau des succès
  function convertForJsonListeSucces($listeSucces)
  {
    // Initialisations
    $listeSuccesAConvertir = array();

    // Conversion de la liste d'objets en tableau pour envoyer au Javascript
    foreach ($listeSucces as $succes)
    {
      if ($succes->getDefined() == 'Y' AND $succes->getValue_user() >= $succes->getLimit_success())
      {
        $succesAConvertir = array('id'            => $succes->getId(),
                                  'reference'     => $succes->getReference(),
                                  'title'         => $succes->getTitle(),
                                  'description'   => $succes->getDescription(),
                                  'limit_success' => $succes->getLimit_success(),
                                  'explanation'   => $succes->getExplanation()
                                 );

        $listeSuccesAConvertir[$succes->getId()] = $succesAConvertir;
      }
    }

    // Retour
    return $listeSuccesAConvertir;
  }

  // METIER : Lecture des thèmes existants par type
  // RETOUR : Liste des thèmes
  function getThemes($type, $experience)
  {
    // Initialisations
    $niveau = '';

    // Récupération du niveau
    if ($type == 'U')
      $niveau = convertExperience($experience);

    // Récupération de la liste des thèmes
    $listeThemes = physiqueThemes($type, $niveau);

    // Retour
    return $listeThemes;
  }

  // METIER : Détermine si on a un thème de mission en cours
  // RETOUR : Booléen
  function getThemeMission()
  {
    // Détermination si thème mission en cours
    $isThemeMission = physiqueThemeMission();

    // Retour
    return $isThemeMission;
  }

  // METIER : Mise à jour de la préférence thème utilisateur
  // RETOUR : Aucun
  function updateTheme($identifiant, $post)
  {
    // Récupération des données
    $idTheme = $post['id_theme'];

    // Lecture de la référence du thème
    $referenceTheme = physiqueReferenceTheme($idTheme);

    // Modification de l'enregistrement en base
    physiqueUpdateTheme($identifiant, $referenceTheme);

    // Message d'alerte
    $_SESSION['alerts']['theme_updated'] = true;
  }

  // METIER : Supprime la préférence thème utilisateur
  // RETOUR : Aucun
  function deleteTheme($identifiant)
  {
    // Modification de l'enregistrement en base
    physiqueUpdateTheme($identifiant, '');

    // Message d'alerte
    $_SESSION['alerts']['theme_deleted'] = true;
  }
?>
