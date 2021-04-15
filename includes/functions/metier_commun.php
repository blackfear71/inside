<?php
  include_once('appel_bdd.php');
  include_once($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/classes/alerts.php');
  include_once($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/classes/missions.php');
  include_once($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/classes/profile.php');
  include_once($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/classes/success.php');
  include_once($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/classes/themes.php');

  // METIER : Contrôles Index, initialisation session
  // RETOUR : Aucun
  function controlsIndex()
  {
    // Lancement de la session
    if (empty(session_id()))
      session_start();

  	// Redirection si déjà connecté
    if (isset($_SESSION['index']['connected']) AND $_SESSION['index']['connected'] == true AND $_SESSION['user']['identifiant'] != 'admin')
      header('location: /inside/portail/portail/portail.php?action=goConsulter');
    elseif (isset($_SESSION['index']['connected']) AND $_SESSION['index']['connected'] == true AND $_SESSION['user']['identifiant'] == 'admin')
      header('location: /inside/administration/portail/portail.php?action=goConsulter');
    else
      $_SESSION['index']['connected'] = false;

    // Récupération de la plateforme en session
    if (!isset($_SESSION['index']['plateforme']))
      $_SESSION['index']['plateforme'] = getPlateforme();
  }

  // METIER : Contrôles Administrateur, initialisation session
  // RETOUR : Aucun
  function controlsAdmin()
  {
    // Lancement de la session
    if (empty(session_id()))
      session_start();

    // Contrôle non utilisateur normal
    if (isset($_SESSION['index']['connected']) AND $_SESSION['index']['connected'] == true AND $_SESSION['user']['identifiant'] != 'admin')
      header('location: /inside/portail/portail/portail.php?action=goConsulter');

    // Contrôle administrateur connecté
    if ($_SESSION['index']['connected'] == false)
      header('location: /inside/index.php?action=goConsulter');

    // Récupération de la plateforme en session
    $_SESSION['index']['plateforme'] = 'web';
  }

  // METIER : Contrôles CRON, initialisation session
  // RETOUR : Aucun
  function controlsCron()
  {
    // Lancement de la session
    if (empty(session_id()))
      session_start();
  }

  // METIER : Contrôles Utilisateur, initialisation session, mission et thème
  // RETOUR : Aucun
  function controlsUser()
  {
    // Lancement de la session
    if (empty(session_id()))
      session_start();

    // Contrôle non administrateur
  	if (isset($_SESSION['index']['connected']) AND $_SESSION['index']['connected'] == true AND $_SESSION['user']['identifiant'] == 'admin')
      header('location: /inside/administration/portail/portail.php?action=goConsulter');

    // Contrôle utilisateur connecté
  	if ($_SESSION['index']['connected'] == false)
      header('location: /inside/index.php?action=goConsulter');
    else
    {
      // Contrôle page accessible mobile
      $isAccessibleMobile = isAccessibleMobile($_SERVER['PHP_SELF']);

      // Redirection si non accessible
      if ($isAccessibleMobile == false)
        header('location: /inside/portail/portail/portail.php?action=goConsulter');
      else
      {
        // Récupération expérience
        getExperience($_SESSION['user']['identifiant']);

        // Initialisation génération mission
        if (!isset($_SESSION['missions']))
          $_SESSION['missions'] = array();

        // Récupération des missions à générer
        $missions = getMissionsToGenerate();

        // On génère les boutons de mission si besoin pour chaque mission
        foreach ($missions as $key => $mission)
        {
          if (empty($_SESSION['missions'][$key]))
          {
            if (!empty($mission) AND date('His') >= $mission->getHeure())
            {
              // Nombre de boutons à générer pour la mission en cours
              $numberButtonsToGenerate = controlMissionComplete($_SESSION['user']['identifiant'], $mission);

              // Génération des boutons de mission en session
              if ($numberButtonsToGenerate > 0)
                $_SESSION['missions'][$key] = generateMission($numberButtonsToGenerate, $mission, $key);
            }
          }
          else
          {
            if (date('His') < $mission->getHeure())
              unset($_SESSION['missions'][$key]);
            else
            {
              // Nombre de boutons à générer pour la mission en cours
              $numberButtonsToGenerate = controlMissionComplete($_SESSION['user']['identifiant'], $mission);

              // Génération des boutons de mission en session (si le compte n'est pas bon)
              if ($numberButtonsToGenerate != count($_SESSION['missions'][$key]))
                $_SESSION['missions'][$key] = generateMission($numberButtonsToGenerate, $mission, $key);
            }
          }
        }

        // Détermination du thème
        $_SESSION['theme'] = getTheme($_SESSION['user']['identifiant']);
      }
    }
  }

  // METIER : Récupération de la plateforme
  // RETOUR : Plateforme
  function getPlateforme()
  {
    // Initialisations
    $plateforme = 'web';

    // Récupération des données
    $userAgent  = $_SERVER['HTTP_USER_AGENT'];

    // Recherche si plateforme mobile
    if (preg_match('/iphone/i', $userAgent)
    OR  preg_match('/android/i', $userAgent)
    OR  preg_match('/blackberry/i', $userAgent)
    OR  preg_match('/symb/i', $userAgent)
    OR  preg_match('/ipad/i', $userAgent)
    OR  preg_match('/ipod/i', $userAgent)
    OR  preg_match('/phone/i', $userAgent))
      $plateforme = 'mobile';

    // Retour
    return $plateforme;
  }

  // METIER : Contrôle si la page courante est accessible sur mobile
  // RETOUR : Booléen
  function isAccessibleMobile($path)
  {
    // Initialisations
    $isAccessibleMobile = true;

    // Contrôle section accessible sur mobile
    if ($_SESSION['index']['plateforme'] == 'mobile')
    {
      if ($path != '/inside/portail/collector/collector.php'
      AND $path != '/inside/portail/expensecenter/expensecenter.php'
      AND $path != '/inside/portail/foodadvisor/foodadvisor.php'
      AND $path != '/inside/portail/foodadvisor/restaurants.php'
      AND $path != '/inside/portail/portail/portail.php'
      AND $path != '/inside/portail/profil/profil.php')
        $isAccessibleMobile = false;
    }

    // Retour
    return $isAccessibleMobile;
  }

  // METIER : Récupération des alertes à afficher
  // RETOUR : Liste des alertes
  function getAlertesInside()
  {
    // Initialisations
    $messages = array();

    // Récupération à partir de la session
    if (isset($_SESSION['alerts'])AND !empty($_SESSION['alerts']))
    {
      // Boucle de lecture des messages d'alerte
      foreach ($_SESSION['alerts'] as $keyAlerte => $alerte)
      {
        if ($alerte == true)
        {
          // Récupération de l'alerte
          $messageAlerte = physiqueAlerte($keyAlerte);

          // Création du message
          if (!empty($messageAlerte))
          {
            $ligneMessage = array('logo'  => $messageAlerte->getType(),
                                  'texte' => $messageAlerte->getMessage()
                                 );
          }
          else
          {
            $ligneMessage = array('logo'  => 'question',
                                  'texte' => 'Message d\'alerte non défini pour : ' . $keyAlerte
                                 );
          }

          // On ajoute la ligne au tableau
          array_push($messages, $ligneMessage);

          // Réinitialisation de l'alerte
          unset($_SESSION['alerts'][$keyAlerte]);
        }
        else
        {
          // Suppression des alertes non à TRUE
          unset($_SESSION['alerts'][$keyAlerte]);
        }
      }
    }

    // Retour
    return $messages;
  }

  // METIER : Récupération des données des succès débloqués
  // RETOUR : Liste des alertes
  function getSuccesDebloques($referenceSucces)
  {
    // Récupération des données du succès
    $succes = physiqueSucces($referenceSucces);

    // Retour
    return $succes;
  }

  // METIER : Récupération de l'expérience d'un utilisateur
  // RETOUR : Aucun
  function getExperience($identifiant)
  {
    // Récupération de l'expérience de l'utilisateur
    $experience = physiqueExperienceUser($identifiant);

    // Conversion de l'expérience
    $niveau   = convertExperience($experience);
    $expMin   = 10 * $niveau ** 2;
    $expMax   = 10 * ($niveau + 1) ** 2;
    $expLvl   = $expMax - $expMin;
    $progress = $experience - $expMin;
    $percent  = floor($progress * 100 / $expLvl);

    // Mise en session des données
    $_SESSION['user']['experience'] = array('niveau'   => $niveau,
                                            'exp_min'  => $expMin,
                                            'exp_max'  => $expMax,
                                            'exp_lvl'  => $expLvl,
                                            'progress' => $progress,
                                            'percent'  => $percent
                                           );
  }

  // METIER : Récupération des missions actives à générer
  // RETOUR : Liste des missions
  function getMissionsToGenerate()
  {
    // Récupération des missions actives
    $listeMissions = physiqueMissionsActives();

    // Retour
    return $listeMissions;
  }

  // METIER : Contrôle mission déjà complétée
  // RETOUR : Nombre de boutons d'une mission à générer
  function controlMissionComplete($identifiant, $mission)
  {
    // Initialisations
    $missionToGenerate = 0;

    // Récupération des données
    $idMission       = $mission->getId();
    $objectifMission = $mission->getObjectif();

    // Récupération de l'avancement du jour de l'utilisateur
    $avancementUser = physiqueAvancementMissionUser($idMission, $identifiant);

    // Calcul du nombre de boutons à générer
    if ($avancementUser < $objectifMission)
      $missionToGenerate = $objectifMission - $avancementUser;

    // Retour
    return $missionToGenerate;
  }

  // METIER : Génération des boutons d'une mission
  // RETOUR : Liste des boutons
  function generateMission($nombreBoutons, $mission, $keyMission)
  {
    // Initialisations
    $listeBoutonsMission = array();

    // Récupération des données
    $idMission        = $mission->getId();
    $referenceMission = $mission->getReference();

    // Définition des pages disponibles
    $listePages = array('/inside/portail/bugs/bugs.php',
                        '/inside/portail/calendars/calendars.php',
                        '/inside/portail/changelog/changelog.php',
                        '/inside/portail/collector/collector.php',
                        '/inside/portail/cookingbox/cookingbox.php',
                        //'/inside/portail/eventmanager/eventmanager.php',
                        '/inside/portail/expensecenter/expensecenter.php',
                        '/inside/portail/foodadvisor/foodadvisor.php',
                        '/inside/portail/foodadvisor/restaurants.php',
                        '/inside/portail/ideas/ideas.php',
                        '/inside/portail/missions/missions.php',
                        '/inside/portail/missions/details.php',
                        '/inside/portail/moviehouse/details.php',
                        '/inside/portail/moviehouse/mailing.php',
                        '/inside/portail/moviehouse/moviehouse.php',
                        '/inside/portail/notifications/notifications.php',
                        '/inside/portail/petitspedestres/parcours.php',
                        '/inside/portail/portail/portail.php',
                        '/inside/portail/profil/profil.php',
                        '/inside/portail/search/search.php'
                       );

    // Définition des zones disponibles
    $listeZonesCompletes = array('header',
                                 'footer',
                                 'article'
                                );

    // Définition des positions horizontales disponibles (zones header et footer)
    $listePositionsHorizontales = array('left',
                                        'right',
                                        'middle',
                                       );

    // Définition des positions verticales disponibles (zone article)
    $listePositionsArticle = array('top_left',
                                   'top_right',
                                   'middle_left',
                                   'middle_right',
                                   'bottom_left',
                                   'bottom_right',
                                  );

    // Calcul du nombre d'emplacements maximum possibles (nombre de pages x (3 emplacements du header + 3 emplacements du footer + 6 emplacements de l'article)
    $nombreEmplacementsMaximum = count($listePages) * (2 * count($listePositionsHorizontales) + 1 * count($listePositionsArticle));

    // Génération des boutons autant que nécessaires à la mission
    for ($i = 0; $i < $nombreBoutons; $i++)
    {
      // Détermination des données à générer
      $referenceBouton = $i;
      $page            = $listePages[array_rand($listePages)];
      $zone            = $listeZonesCompletes[array_rand($listeZonesCompletes)];

      // Détermination de la position en fonction de la zone
      switch ($zone)
      {
        case 'article':
          $position = $listePositionsArticle[array_rand($listePositionsArticle)];
          break;

        case 'header':
        case 'nav':
        case 'footer':
          $position = $listePositionsHorizontales[array_rand($listePositionsHorizontales)];
          break;

        default:
          $position = '';
          break;
      }

      // Détermination de l'icône en fonction de la position
      switch ($position)
      {
        case 'left':
        case 'top_left':
        case 'middle_left':
        case 'bottom_left':
          $icone = $referenceMission . '_g';
          break;

        case 'middle':
          $icone = $referenceMission . '_m';
          break;

        case 'right':
        case 'top_right':
        case 'middle_right':
        case 'bottom_right':
          $icone = $referenceMission . '_d';
          break;

        default:
          $icone = '';
          break;
      }

      // Détermination de la classe CSS en fonction de la zone et de la position
      if (!empty($zone) AND !empty($position))
      {
        // Cas des pages sans onglets
        if  ($zone == 'article' AND ($position == 'top_left' OR $position == 'top_right')
        AND ($page == '/inside/portail/bugs/bugs.php'
        OR   $page == '/inside/portail/changelog/changelog.php'
        OR   $page == '/inside/portail/ideas/ideas.php'
        OR   $page == '/inside/portail/notifications/notifications.php'
        OR   $page == '/inside/portail/portail/portail.php'
        OR   $page == '/inside/portail/profil/profil.php'
        OR   $page == '/inside/portail/search/search.php'))
          $classe = $zone . '_' . $position . '_mission_no_nav';
        else
          $classe = $zone . '_' . $position . '_mission';
      }
      else
        $classe = '';

      // Création du bouton à partir des données
      $boutonMission = array('id_mission'  => $idMission,
                             'reference'   => $referenceMission,
                             'ref_mission' => $referenceBouton,
                             'key_mission' => $keyMission,
                             'page'        => $page,
                             'zone'        => $zone,
                             'position'    => $position,
                             'icon'        => $icone,
                             'class'       => $classe
                             );

      // Vérification boutons dupliqués dans la liste
      $doublons = controlGeneratedMission($listeBoutonsMission, $boutonMission, $nombreEmplacementsMaximum);

      // Si boutons non dupliqués alors insertion dans le tableau, sinon retour en arrière d'une occurence pour la regénérer et la contrôler
      if ($doublons == false)
        array_push($listeBoutonsMission, $boutonMission);
      else
        $i--;
    }

    // Retour
    return $listeBoutonsMission;
  }

  // METIER : Contrôle boutons en double
  // RETOUR : Booléen
  function controlGeneratedMission($listeBoutonsMission, $boutonMission, $nombreEmplacementsMaximum)
  {
    // Initialisations
    $doublons = false;

    // On ne fait cette vérification que tant que le nombre de boutons générés ne dépasse pas le nombre maximal d'emplacements possibles
    if (!empty($listeBoutonsMission) AND count($listeBoutonsMission) <= $nombreEmplacementsMaximum)
    {
      // Comparaison entre les boutons existants et le bouton en cours de création
      foreach ($listeBoutonsMission as $missionExistante)
      {
        if ($boutonMission['id_mission'] == $missionExistante['id_mission']
        AND $boutonMission['page']       == $missionExistante['page']
        AND $boutonMission['zone']       == $missionExistante['zone']
        AND $boutonMission['position']   == $missionExistante['position'])
        {
          $doublons = true;
          break;
        }
      }
    }

    // Retour
    return $doublons;
  }

  // METIER : Détermination du thème en fonction du type
  // RETOUR : Tableau du thème
  function getTheme($identifiant)
  {
    // Initialisations
    $tableauTheme = array();

    // Vérification si thème de mission activé
    $themeMissionActive = physiqueThemeMissionActive();

    // Récupération du thème en fonction du type
    if (!empty($themeMissionActive))
    {
      // Thème de mission en cours
      if ($themeMissionActive->getLogo() == 'Y')
      {
        $tableauTheme = array('background' => '/inside/includes/images/themes/backgrounds/' . $themeMissionActive->getReference() . '.png',
                              'header'     => '/inside/includes/images/themes/headers/' . $themeMissionActive->getReference() . '_h.png',
                              'footer'     => '/inside/includes/images/themes/footers/' . $themeMissionActive->getReference() . '_f.png',
                              'logo'       => '/inside/includes/images/themes/logos/' . $themeMissionActive->getReference() . '_l.png'
                             );
      }
      else
      {
        $tableauTheme = array('background' => '/inside/includes/images/themes/backgrounds/' . $themeMissionActive->getReference() . '.png',
                              'header'     => '/inside/includes/images/themes/headers/' . $themeMissionActive->getReference() . '_h.png',
                              'footer'     => '/inside/includes/images/themes/footers/' . $themeMissionActive->getReference() . '_f.png',
                              'logo'       => NULL
                             );
      }
    }
    else
    {
      // Lecture préférence thème utilisateur
      $referenceTheme = physiquePreferenceTheme($identifiant);

      // Thème personnalisé
      if (!empty($referenceTheme))
      {
        // Récupération des données du thème
        $themePersonnalise = physiqueThemePersonnalise($referenceTheme);

        // Thème personnalisé
        if (!empty($themePersonnalise))
        {
          if ($themePersonnalise->getLogo() == 'Y')
          {
            $tableauTheme = array('background' => '/inside/includes/images/themes/backgrounds/' . $themePersonnalise->getReference() . '.png',
                                  'header'     => '/inside/includes/images/themes/headers/' . $themePersonnalise->getReference() . '_h.png',
                                  'footer'     => '/inside/includes/images/themes/footers/' . $themePersonnalise->getReference() . '_f.png',
                                  'logo'       => '/inside/includes/images/themes/logos/' . $themePersonnalise->getReference() . '_l.png'
                                 );
          }
          else
          {
            $tableauTheme = array('background' => '/inside/includes/images/themes/backgrounds/' . $themePersonnalise->getReference() . '.png',
                                  'header'     => '/inside/includes/images/themes/headers/' . $themePersonnalise->getReference() . '_h.png',
                                  'footer'     => '/inside/includes/images/themes/footers/' . $themePersonnalise->getReference() . '_f.png',
                                  'logo'       => NULL
                                 );
          }
        }
      }
    }

    // Retour
    return $tableauTheme;
  }

  // METIER : Formatage titres niveaux pour les succès
  // RETOUR : Titre du niveau formaté
  function formatLevelTitle($level)
  {
    // Formatage du titre en fonction du niveau
    switch ($level)
    {
      case '1';
        $nameLevel = '<div class="titre_section">';
          $nameLevel .= '<img src="/inside/includes/icons/profil/crown_grey.png" alt="crown_grey" class="logo_titre_section" />';
          $nameLevel .= '<div class="number_level">' . $level . '</div>';
          $nameLevel .= '<div class="texte_titre_section">Seuls les plus forts y parviendront.</div>';
        $nameLevel .= '</div>';
        break;

      case '2';
        $nameLevel = '<div class="titre_section">';
          $nameLevel .= '<img src="/inside/includes/icons/profil/crown_grey.png" alt="crown_grey" class="logo_titre_section" />';
          $nameLevel .= '<div class="number_level">' . $level . '</div>';
          $nameLevel .= '<div class="texte_titre_section">Vous êtes encore là ?</div>';
        $nameLevel .= '</div>';
        break;

      case '3';
        $nameLevel = '<div class="titre_section">';
          $nameLevel .= '<img src="/inside/includes/icons/profil/crown_grey.png" alt="crown_grey" class="logo_titre_section" />';
          $nameLevel .= '<div class="number_level">' . $level . '</div>';
          $nameLevel .= '<div class="texte_titre_section">Votre charisme doit être impressionnant.</div>';
        $nameLevel .= '</div>';
        break;

      default:
        $nameLevel = '<div class="titre_section">';
          $nameLevel .= '<img src="/inside/includes/icons/profil/crown_grey.png" alt="crown_grey" class="logo_titre_section" />';
          $nameLevel .= '<div class="number_level">' . $level . '</div>';
          $nameLevel .= '<div class="texte_titre_section">Niveau ' . $level . '</div>';
        $nameLevel .= '</div>';
        break;
    }

    // Retour
    return $nameLevel;
  }

  // METIER : Génération d'une notification
  // RETOUR : Aucun
  function insertNotification($author, $category, $content)
  {
    // Insertion de l'enregistrement en base
    $notification = array('author'   => $author,
                          'date'     => date('Ymd'),
                          'time'     => date('His'),
                          'category' => $category,
                          'content'  => $content
                         );

    physiqueInsertionNotification($notification);
  }

  // METIER : Suppression d'une notification
  // RETOUR : Aucun
  function deleteNotification($category, $content)
  {
    // Suppression de l'enregistrement en base
    physiqueDeleteNotification($category, $content);
  }

  // METIER : Contrôle notification existante
  // RETOUR : Booléen
  function controlNotification($category, $content)
  {
    // Vérificartion notification existante
    $notificationExistante = physiqueNotificationExistante($category, $content);

    // Retour
    return $notificationExistante;
  }

  // METIER : Lecture liste des utilisateurs pour le chat
  // RETOUR : Liste des utilisateurs
  function getUsersChat()
  {
    // Récupération de la liste des utilisateurs
    $listeUsers = physiqueUsersChat();

    // Traitement de sécurité
    foreach ($listeUsers as $user)
    {
      Profile::secureData($user);
    }

    // Retour
    return $listeUsers;
  }

  // METIER : Rotation automatique des images en mode portrait
  // RETOUR : Aucun
  function rotateImage($image, $type)
  {
    // Initialisations
    $degrees = 0;

    // Récupération des données EXIF
    $exif = exif_read_data($image);

    // Détermination de l'angle
    if (!empty($exif['Orientation']))
    {
      switch ($exif['Orientation'])
      {
        case 3:
        case 6:
        case 8:
          $degrees = 360;
          break;

        case 1:
        default:
          $degrees = 0;
          break;
      }
    }

    // Rotation
    if ($degrees != 0)
    {
      switch ($type)
      {
        case 'jpeg':
        case 'jpg':
          $source = imagecreatefromjpeg($image);
          $rotate = imagerotate($source, $degrees, 0);
          imagejpeg($rotate, $image);
          break;

        case 'png':
          $source = imagecreatefrompng($image);
          $rotate = imagerotate($source, $degrees, 0);
          imagepng($rotate, $image);
          break;

        case 'gif':
          $source = imagecreatefromgif($image);
          $rotate = imagerotate($source, $degrees, 0);
          imagegif($rotate, $image);
          break;

        case 'bmp':
          $source = imagecreatefrombmp($image);
          $rotate = imagerotate($source, $degrees, 0);
          imagebmp($rotate, $image);
          break;

        default:
          break;
      }
    }
  }

  // METIER : Génération de la valeur d'un succès
  // RETOUR : Aucun
  function insertOrUpdateSuccesValue($reference, $identifiant, $incoming)
  {
    // Initialisations
    $value  = NULL;
    $action = NULL;

    // Détermination de la valeur à insérer en fonction de la référence
    switch ($reference)
    {
      // Valeur en entrée conservée
      case 'beginning':
      case 'developper':
      case 'padawan':
      case 'level_1':
      case 'level_5':
      case 'level_10':
        // Récupération de l'ancienne valeur du succès de l'utilisateur
        $ancienneValeur = physiqueAncienneValeurSucces($reference, $identifiant);

        // Récupération de la nouvelle valeur
        $value = $incoming;

        // Vérification si succès débloqué (sauf pour l'admin)
        if (isset($_SESSION['user']['identifiant']) AND $_SESSION['user']['identifiant'] != 'admin')
        {
          $alreadyUnlocked = false;

          // Récupération de la valeur limite à atteindre pour le succès
          $limite = physiqueLimiteSucces($reference);

          // Comparaison entre les 2 valeurs
          if (!empty($ancienneValeur) AND $ancienneValeur >= $limite)
            $alreadyUnlocked = true;

          // Ajout à la session d'affichage des succès si limite atteinte
          if ($alreadyUnlocked == false AND $value == $limite)
            $_SESSION['success'][$reference] = true;
        }
        break;

      // Incrémentation de la valeur précédente avec la valeur en entrée (cas incoming <= 1)
      case 'publisher':
      case 'viewer':
      case 'commentator':
      case 'listener':
      case 'speaker':
      case 'funny':
      case 'self-satisfied':
      case 'buyer':
      case 'generous':
      case 'creator':
      case 'applier':
      case 'debugger':
      case 'compiler':
      case 'restaurant-finder':
      case 'star-chief':
      case 'cooker':
      case 'recipe-master':
      case 'christmas2017':
      case 'christmas2017_2':
      case 'golden-egg':
      case 'rainbow-egg':
      case 'wizard':
      case 'christmas2018':
      case 'christmas2018_2':
      case 'christmas2019':
        // Récupération de l'ancienne valeur du succès de l'utilisateur
        $ancienneValeur = physiqueAncienneValeurSucces($reference, $identifiant);

        // Récupération de la nouvelle valeur
        if (!empty($ancienneValeur))
          $value = $ancienneValeur + $incoming;
        else
          $value = $incoming;

        // Vérification si succès débloqué (sauf pour l'admin)
        if (isset($_SESSION['user']['identifiant']) AND $_SESSION['user']['identifiant'] != 'admin')
        {
          // Récupération de la valeur limite à atteindre pour le succès
          $limite = physiqueLimiteSucces($reference);

          // Ajout à la session d'affichage des succès si limite atteinte
          if ($value == $limite)
            $_SESSION['success'][$reference] = true;
        }
        break;

      // Incrémentation de la valeur précédente avec la valeur en entrée (cas incoming > 1)
      case 'eater':
        // Récupération de l'ancienne valeur du succès de l'utilisateur
        $ancienneValeur = physiqueAncienneValeurSucces($reference, $identifiant);

        // Récupération de la nouvelle valeur
        if (!empty($ancienneValeur))
          $value = $ancienneValeur + $incoming;
        else
          $value = $incoming;

        // Vérification si succès débloqué (sauf pour l'admin)
        if (isset($_SESSION['user']['identifiant']) AND $_SESSION['user']['identifiant'] != 'admin')
        {
          $alreadyUnlocked = false;

          // Récupération de la valeur limite à atteindre pour le succès
          $limite = physiqueLimiteSucces($reference);

          // Comparaison entre les 2 valeurs
          if (!empty($ancienneValeur) AND $ancienneValeur >= $limite)
            $alreadyUnlocked = true;

          // Ajout à la session d'affichage des succès si limite atteinte
          if ($alreadyUnlocked == false AND $value >= $limite)
            $_SESSION['success'][$reference] = true;
        }
        break;

      // Valeur maximale conservée
      case 'greedy':
        // Récupération de l'ancienne valeur du succès de l'utilisateur
        $ancienneValeur = physiqueAncienneValeurSucces($reference, $identifiant);

        // Récupération de la nouvelle valeur
        if (!empty($ancienneValeur))
        {
          if ($incoming > $ancienneValeur)
            $value = $incoming;
        }
        else
          $value = $incoming;

        // Vérification si succès débloqué (sauf pour l'admin)
        if (isset($_SESSION['user']['identifiant']) AND $_SESSION['user']['identifiant'] != 'admin')
        {
          // Récupération de la valeur limite à atteindre pour le succès
          $limite = physiqueLimiteSucces($reference);

          if (!empty($ancienneValeur))
          {
            // Ajout à la session d'affichage des succès si limite atteinte
            if ($ancienneValeur < $limite AND !empty($value) AND $value >= $limite)
              $_SESSION['success'][$reference] = true;
          }
          else
          {
            // Ajout à la session d'affichage des succès si limite atteinte
            if ($value >= $limite)
              $_SESSION['success'][$reference] = true;
          }
        }
        break;

      default:
        // Valeur par défaut
        $value = NULL;
        break;
    }

    // Détermination de l'action à effectuer en fonction de la valeur
    if (!is_null($value))
    {
      if ($value == 0)
        $action = 'delete';
      else
      {
        // Vérification de l'exitence d'une valeur du succès pour l'utilisateur
        if (!empty($ancienneValeur))
          $action = 'update';
        else
          $action = 'insert';
      }
    }

    // Traitement de l'enregistrement
    switch ($action)
    {
      case 'insert':
        // Insertion de l'enregistrement en base
        $succesUser = array('reference'   => $reference,
                            'identifiant' => $identifiant,
                            'value'       => $value
                           );

        physiqueInsertionSuccesUser($succesUser);
        break;

      case 'update':
        // Modification de l'enregistrement en base
        physiqueUpdateSuccesUser($reference, $identifiant, $value);
        break;

      case 'delete':
        // Suppression de l'enregistrement en base
        physiqueDeleteSuccesUser($reference, $identifiant);
        break;

      default:
        break;
    }
  }

  // METIER : Génération des succès pour le niveau de l'utilisateur
  // RETOUR : Aucun
  function insertOrUpdateSuccesLevel($identifiant, $experience)
  {
    // Récupération du niveau
    $level = convertExperience($experience);

    // Insertion des succès pour chaque niveau
    insertOrUpdateSuccesValue('level_1', $identifiant, $level);
    insertOrUpdateSuccesValue('level_5', $identifiant, $level);
    insertOrUpdateSuccesValue('level_10', $identifiant, $level);
  }

  // METIER : Génération des succès pour une mission
  // RETOUR : Aucun
  function insertOrUpdateSuccesMission($reference, $identifiant)
  {
    // Insertion des succès pour chaque mission concernée
    switch ($reference)
    {
      case 'noel_2017':
        insertOrUpdateSuccesValue('christmas2017', $identifiant, 1);
        insertOrUpdateSuccesValue('christmas2017_2', $identifiant, 1);
        break;

      case 'paques_2018':
        insertOrUpdateSuccesValue('golden-egg', $identifiant, 1);
        insertOrUpdateSuccesValue('rainbow-egg', $identifiant, 1);
        break;

      case 'halloween_2018':
        insertOrUpdateSuccesValue('wizard', $identifiant, 1);
        break;

      case 'noel_2018':
        insertOrUpdateSuccesValue('christmas2018', $identifiant, 1);
        insertOrUpdateSuccesValue('christmas2018_2', $identifiant, 1);
        break;

      case 'noel_2019':
        insertOrUpdateSuccesValue('christmas2019', $identifiant, 1);
        break;

      default:
        break;
    }
  }

  // METIER : Mise à jour expérience
  // RETOUR : Aucun
  function insertExperience($identifiant, $action)
  {
    // Détermination de la quantité d'expérience à attribuer
    switch ($action)
    {
      case 'add_expense':
        $experience = 5;
        break;

      case 'add_film':
      case 'add_idea':
      case 'add_restaurant':
      case 'all_missions':
        $experience = 10;
        break;

      case 'add_collector':
      case 'add_bug':
      case 'add_recipe':
        $experience = 15;
        break;

      case 'winner_mission_3':
        $experience = 30;
        break;

      case 'winner_mission_2':
        $experience = 50;
        break;

      case 'winner_mission_1':
        $experience = 100;
        break;

      default:
        $experience = 0;
        break;
    }

    // Ajout de l'expérience
    if ($experience > 0)
    {
      // Récupération de l'expérience actuelle de l'utilisateur
      $ancienneExperience = physiqueExperienceUser($identifiant);

      // Calcul de la nouvelle expérience
      $nouvelleExperience = $ancienneExperience + $experience;

      // Modification de l'enregistrement en base
      physiqueUpdateExperienceUser($identifiant, $nouvelleExperience);

      // Insertion des succès des niveaux
      insertOrUpdateSuccesLevel($identifiant, $nouvelleExperience);
    }
  }

  // METIER : Conversion de l'expérience en niveau
  // RETOUR : Niveau
  function convertExperience($experience)
  {
    // Formatage du niveau
    $niveau = floor(sqrt($experience / 10));

    // Retour
    return $niveau;
  }

  // METIER : Remplacement chaîne de caractères
  // RETOUR : Chaîne formatée
	function formatExplanation($string, $replace, $search)
	{
    // Remplacement d'une chaîne de caractères par une autre
		$explanations = str_replace($search, $replace, $string);

    // Retour
		return $explanations;
	}

  // METIER : Génère le chemin vers l'avatar
  // RETOUR : Chemin & titre image
  function formatAvatar($avatar, $pseudo, $niveau, $alt)
  {
    // Niveau du chemin à parcourir
    switch ($niveau)
    {
      case 1:
        $level = '..';
        break;

      case 2:
        $level = '../..';
        break;

      case 0:
      default:
        $level = '/inside';
        break;
    }

    // Création du chemin
    if (isset($avatar) AND !empty($avatar))
      $path = $level . '/includes/images/profil/avatars/' . $avatar;
    else
      $path = $level . '/includes/icons/common/default.png';

    // Formatage du pseudo
    $pseudo = formatUnknownUser($pseudo, true, false);

    // Création du tableau des données
    $formattedAvatar = array('path'  => $path,
                             'alt'   => $alt,
                             'title' => $pseudo
                            );

    // Retour
    return $formattedAvatar;
  }

  // METIER : Formate une chaîne de caractères en longueur
  // RETOUR : Chaîne formatée
  function formatString($chaine, $limite)
  {
    // Formatage si dépassement du nombre de caractères voulu
    if (strlen($chaine) > $limite)
      $chaine = substr($chaine, 0, $limite) . '...';

    // Retour
    return $chaine;
  }

  // METIER : Formate le pseudo d'un utilisateur désinscrit
  // RETOUR : Pseudo ancien utilisateur
  function formatUnknownUser($pseudo, $majuscule, $italique)
  {
    if (!isset($pseudo) OR empty($pseudo))
    {
      // Passage en majuscule si demandé
      if ($majuscule == true)
      {
        // Passage en italique si demandé
        if ($italique == true)
          $pseudo = '<i>Un ancien utilisateur</i>';
        else
          $pseudo = 'Un ancien utilisateur';
      }
      else
      {
        // Passage en italique si demandé
        if ($italique == true)
          $pseudo = '<i>un ancien utilisateur</i>';
        else
          $pseudo = 'un ancien utilisateur';
      }
    }

    // Retour
    return $pseudo;
  }













  // METIER : Contrôle une image avant de la télécharger
  // RETOUR : Booléen
  function controlsUploadFile($file, $name, $types)
  {
    $control_ok = true;

    $output = array('control_ok' => false,
                    'new_name'   => '',
                    'tmp_file'   => '',
                    'type_file'  => ''
                   );

    // Si on a bien une image
    if (!empty($file['name']))
    {
      // Données du fichier
      $nameFile  = $file['name'];
      $typeFile  = $file['type'];
      $tmpFile   = $file['tmp_name'];
      $errorFile = $file['error'];
      $sizeFile  = $file['size'];

      // Limite taille maximale fichier (15 Mo)
      $maxSize = 15728640;

      // Contrôle taille fichier
      if ($errorFile == 2 OR $sizeFile > $maxSize)
      {
        $_SESSION['alerts']['file_too_big'] = true;
        $control_ok                         = false;
      }

      // Contrôle fichier temporaire existant
      if ($control_ok == true)
      {
        if (!is_uploaded_file($tmpFile))
        {
          $_SESSION['alerts']['temp_not_found'] = true;
          $control_ok                           = false;
        }
      }

      // Contrôle type de fichier
      if ($control_ok == true)
      {
        switch ($types)
        {
          case 'jpg':
          case 'jpeg':
            if (!strstr($typeFile, 'jpg') && !strstr($typeFile, 'jpeg'))
            {
              $_SESSION['alerts']['wrong_file_type'] = true;
              $control_ok                            = false;
            }
            break;

          case 'png':
            if (!strstr($typeFile, 'png'))
            {
              $_SESSION['alerts']['wrong_file_type'] = true;
              $control_ok                            = false;
            }
            break;

          case 'all':
          default:
            if (!strstr($typeFile, 'jpg') && !strstr($typeFile, 'jpeg') && !strstr($typeFile, 'bmp') && !strstr($typeFile, 'gif') && !strstr($typeFile, 'png'))
            {
              $_SESSION['alerts']['wrong_file_type'] = true;
              $control_ok                            = false;
            }
            break;
        }
      }

      // Récupération infos
      if ($control_ok == true)
      {
        $typeImage = pathinfo($nameFile, PATHINFO_EXTENSION);
        $newName   = $name . '.' . $typeImage;

        $output = array('control_ok' => true,
                        'new_name'   => $newName,
                        'tmp_file'   => $tmpFile,
                        'type_file'  => $typeImage
                       );
      }
    }

    return $output;
  }

  // METIER : Contrôle d'un fichier après contrôles communs
  // RETOUR : Booléen
  function controleFichier($fileDatas)
  {
    // Initialisations
    $control_ok = true;

    // Contrôle
    if ($fileDatas['control_ok'] == false)
      $control_ok = false;

    // Retour
    return $control_ok;
  }

  // METIER : Télécharge une image sur le serveur
  // RETOUR : Booléen
  function uploadFile($fileDatas, $folder)
  {
    // Initialisations
    $control_ok = true;

    // On vérifie la présence du dossier, sinon on le créé de manière récursive
    if (!is_dir($folder))
      mkdir($folder, 0777, true);

    // Dossier de destination
    $dir = $folder . '/';

    // Récupération des données et téléchargement
    $tmpFile = $fileDatas['tmp_file'];
    $name    = $fileDatas['new_name'];

    if (!move_uploaded_file($tmpFile, $dir . $name))
    {
      $_SESSION['alerts']['wrong_file'] = true;
      $control_ok                       = false;
    }

    // Retour
    return $control_ok;
  }
?>
