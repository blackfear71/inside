<?php
  include_once('appel_bdd.php');
  include_once($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/classes/profile.php');
  include_once($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/classes/themes.php');
  include_once($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/classes/missions.php');

  // Contrôles Index, initialisation session
  // RETOUR : aucun
  function controlsIndex()
  {
    // Lancement de la session
  	if (empty(session_id()))
  	 session_start();

  	// Si déjà connecté
    if (isset($_SESSION['index']['connected']) AND $_SESSION['index']['connected'] == true AND $_SESSION['user']['identifiant'] != "admin")
      header('location: /inside/portail/portail/portail.php?action=goConsulter');
    elseif (isset($_SESSION['index']['connected']) AND $_SESSION['index']['connected'] == true AND $_SESSION['user']['identifiant'] == "admin")
      header('location: /inside/administration/administration.php?action=goConsulter');
    else
      $_SESSION['index']['connected'] = false;
  }

  // Contrôles Administrateur, initialisation session
  // RETOUR : aucun
  function controlsAdmin()
  {
    // Lancement de la session
    if (empty(session_id()))
      session_start();

    // Contrôle non utilisateur normal
    if (isset($_SESSION['index']['connected']) AND $_SESSION['index']['connected'] == true AND $_SESSION['user']['identifiant'] != "admin")
      header('location: /inside/portail/portail/portail.php?action=goConsulter');

    // Contrôle administrateur connecté
    if ($_SESSION['index']['connected'] == false)
      header('location: /inside/index.php');
  }

  // Contrôles Utilisateur, initialisation session, mission et thème
  // RETOUR : aucun
  function controlsUser()
  {
    // Lancement de la session
    if (empty(session_id()))
      session_start();

    // Contrôle non administrateur
  	if (isset($_SESSION['index']['connected']) AND $_SESSION['index']['connected'] == true AND $_SESSION['user']['identifiant'] == "admin")
      header('location: /inside/administration/administration.php?action=goConsulter');

    // Contrôle utilisateur connecté
  	if ($_SESSION['index']['connected'] == false)
      header('location: /inside/index.php');

    if ($_SESSION['index']['connected'] == true)
    {
      // Récupération expérience
      getExperience($_SESSION['user']['identifiant']);

      // Initialisation génération mission
      if (!isset($_SESSION['missions']))
        $_SESSION['missions'] = array();

      // Récupération des missions à générer
      $missions = getMissionsToGenerate();

      //var_dump($missions);

      // On génère les boutons de mission si besoin pour chaque mission
      foreach ($missions as $key => $mission)
      {
        if (empty($_SESSION['missions'][$key]))
        {
          if (!empty($mission) AND date("His") >= $mission->getHeure())
          {
            // Nombre de boutons à générer pour la mission en cours
            $nbButtonsToGenerate = controlMissionComplete($_SESSION['user']['identifiant'], $mission);

            if ($nbButtonsToGenerate > 0)
            {
              $missionGenerated = generateMissions($nbButtonsToGenerate, $mission, $key);
              $_SESSION['missions'][$key] = $missionGenerated;
            }
          }
        }
        else
        {
          if (date('His') < $mission->getHeure())
            unset($_SESSION['missions'][$key]);
          else
          {
            // Nombre de boutons à générer pour la mission en cours
            $nbButtonsToGenerate = controlMissionComplete($_SESSION['user']['identifiant'], $mission);

            if ($nbButtonsToGenerate != count($_SESSION['missions'][$key]))
            {
              $missionGenerated = generateMissions($nbButtonsToGenerate, $mission, $key);
              $_SESSION['missions'][$key] = $missionGenerated;
            }
          }
        }
      }

      //var_dump($_SESSION['missions']);

      // Détermination thème
      $_SESSION['theme'] = setTheme();
    }

    //var_dump($_SESSION);
  }

  // Récupération expérience utilisateur
  // RETOUR : tableau d'expérience
  function getExperience($identifiant)
  {
    global $bdd;

    $reponse = $bdd->query('SELECT id, identifiant, experience FROM users WHERE identifiant = "' . $identifiant . '"');
    $donnees = $reponse->fetch();

    $experience = $donnees['experience'];
    $niveau     = convertExperience($experience);
    $exp_min    = 10 * $niveau ** 2;
    $exp_max    = 10 * ($niveau + 1) ** 2;
    $exp_lvl    = $exp_max - $exp_min;
    $progress   = $experience - $exp_min;
    $percent    = floor($progress * 100 / $exp_lvl);

    $_SESSION['user']['experience'] = array('niveau'   => $niveau,
                                            'exp_min'  => $exp_min,
                                            'exp_max'  => $exp_max,
                                            'exp_lvl'  => $exp_lvl,
                                            'progress' => $progress,
                                            'percent'  => $percent
                                           );

    $reponse->closeCursor();
  }

  // Récupération des missions actives
  // RETOUR : Objets mission
  function getMissionsToGenerate()
  {
    $missions  = array();
    $date_jour = date('Ymd');

    global $bdd;

    $reponse = $bdd->query('SELECT * FROM missions WHERE ' . $date_jour . ' >= date_deb AND ' . $date_jour . ' <= date_fin ORDER BY date_deb ASC');
    while($donnees = $reponse->fetch())
    {
      $myMission = Mission::withData($donnees);
      array_push($missions, $myMission);
    }
    $reponse->closeCursor();

    return $missions;
  }

  // Contrôle mission déjà complétée
  // RETOUR : Nombre de missions à générer
  function controlMissionComplete($user, $mission)
  {
    $missionToGenerate = 0;
    $date_jour         = date('Ymd');

    global $bdd;

    // Objectif mission
    $reponse1 = $bdd->query('SELECT * FROM missions WHERE id = ' . $mission->getId());
    $donnees1 = $reponse1->fetch();

    $objectifMission = $donnees1['objectif'];

    $reponse1->closeCursor();

    // Objectif atteint par l'utilisateur dans la journée
    $reponse2 = $bdd->query('SELECT * FROM missions_users WHERE id_mission = ' . $mission->getId() . ' AND identifiant = "' . $user . '" AND date_mission = ' . $date_jour);
    $donnees2 = $reponse2->fetch();

    $avancementUser = $donnees2['avancement'];

    $reponse2->closeCursor();

    if ($avancementUser < $objectifMission)
      $missionToGenerate = $objectifMission - $avancementUser;

    return $missionToGenerate;
  }

  // Génération contexte mission (boutons)
  // RETOUR : Tableau contexte
  function generateMissions($nb, $mission, $key)
  {
    $missionButtons            = array();

    $listPages                 = array('/inside/portail/bugs/bugs.php',
                                       '/inside/portail/calendars/calendars.php',
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
                                       '/inside/portail/search/search.php',
                                       '/inside/profil/profil.php'
                                      );
    $listZonesCompletes        = array('header',
                                       'footer',
                                       'article'
                                      );
    $listPositionsHorizontales = array('left',
                                       'right',
                                       'middle',
                                      );
    $listPositionsVerticales   = array('top',
                                       'bottom',
                                       'middle'
                                      );
    $listPositionsArticle      = array('top_left',
                                       'top_right',
                                       'middle_left',
                                       'middle_right',
                                       'bottom_left',
                                       'bottom_right',
                                      );

    for ($i = 0; $i < $nb; $i++)
    {
      $myMissionButtons = array();

      // Id mission
      $id_mission = $mission->getId();

      // Référence mission
      $reference = $mission->getReference();

      // Référence mission remplie
      $ref_mission = $i;

      // Page
      $page = $listPages[array_rand($listPages)];

      // Zone
      $zone = $listZonesCompletes[array_rand($listZonesCompletes)];

      // Positions
      switch ($zone)
      {
        case 'article':
          $position = $listPositionsArticle[array_rand($listPositionsArticle)];
          break;

        case 'header':
        case 'nav':
        case 'footer':
          $position = $listPositionsHorizontales[array_rand($listPositionsHorizontales)];
          break;

        default:
          $position = '';
          break;
      }

      // Icônes
      switch ($position)
      {
        case 'left':
          $icone = $mission->getReference() . '_g';
          break;

        case 'middle':
          $icone = $mission->getReference() . '_m';
          break;

        case 'right':
          $icone = $mission->getReference() . '_d';
          break;

        case 'top_left':
        case 'middle_left':
        case 'bottom_left':
          $icone = $mission->getReference() . '_g';
          break;

        case 'top_right':
        case 'middle_right':
        case 'bottom_right':
          $icone = $mission->getReference() . '_d';
          break;

        default:
          $icone = '';
          break;
      }

      // Classe position
      if (!empty($zone) AND !empty($position))
      {
        if  ($zone == 'article'
        AND ($position == 'top_left'
        OR   $position == 'top_right')
        AND ($page == '/inside/portail/bugs/bugs.php'
        OR   $page == '/inside/portail/ideas/ideas.php'
        OR   $page == '/inside/portail/notifications/notifications.php'
        OR   $page == '/inside/portail/portail/portail.php'
        OR   $page == '/inside/portail/search/search.php'
        OR   $page == '/inside/profil/profil.php'))
          $classe = $zone . '_' . $position . '_mission_no_nav';
        else
          $classe = $zone . '_' . $position . '_mission';
      }
      else
        $classe = '';

      $myMissionButtons = array('id_mission'  => $id_mission,
                                'reference'   => $reference,
                                'ref_mission' => $ref_mission,
                                'key_mission' => $key,
                                'page'        => $page,
                                'zone'        => $zone,
                                'position'    => $position,
                                'icon'        => $icone,
                                'class'       => $classe
                               );

      $duplicate = controlGeneratedMission($missionButtons, $myMissionButtons);

      // Si mission non dupliquée alors on l'insère dans le tableau, sinon on revient une occurence en arrière pour la regénérer
      if ($duplicate == false)
        array_push($missionButtons, $myMissionButtons);
      else
        $i--;
    }

    return $missionButtons;
  }

  // Contrôle missions en double
  // RETOUR : booléen
  function controlGeneratedMission($tableauMissions, $mission)
  {
    $duplicated = false;

    // Modifier le compteur si de nouvelles pages sont rajoutées (actuellement 17*(3+3+6) = 204 emplacements possibles)
    if (!empty($tableauMissions) AND count($tableauMissions) <= 204)
    {
      foreach ($tableauMissions as $missionExistante)
      {
        if ($mission['id_mission'] == $missionExistante['id_mission']
        AND $mission['page']       == $missionExistante['page']
        AND $mission['zone']       == $missionExistante['zone']
        AND $mission['position']   == $missionExistante['position'])
        {
          $duplicated = true;
          break;
        }
      }
    }

    return $duplicated;
  }

  // Détermination du thème
  // RETOUR : Tableau chemins & types de thème
  function setTheme()
  {
    $theme = array();

    global $bdd;

    // Contrôle thème mission en cours
    $theme_present = false;

    $req1 = $bdd->query('SELECT * FROM themes WHERE type = "M" AND ' . date("Ymd") . ' >= date_deb AND ' . date("Ymd") . ' <= date_fin');
    $data1 = $req1->fetch();

    if ($req1->rowCount() > 0)
    {
      $theme_present = true;
      $myTheme       = Theme::withData($data1);
    }

    $req1->closeCursor();

    // Thème mission si en cours
    if ($theme_present == true)
    {
      if ($myTheme->getLogo() == "Y")
      {
        $theme = array('background' => '/inside/includes/images/themes/backgrounds/' . $myTheme->getReference() . '.png',
                       'header'     => '/inside/includes/images/themes/headers/' . $myTheme->getReference() . '_h.png',
                       'footer'     => '/inside/includes/images/themes/footers/' . $myTheme->getReference() . '_f.png',
                       'logo'       => '/inside/includes/images/themes/logos/' . $myTheme->getReference() . '_l.png'
                      );
      }
      else
      {
        $theme = array('background' => '/inside/includes/images/themes/backgrounds/' . $myTheme->getReference() . '.png',
                       'header'     => '/inside/includes/images/themes/headers/' . $myTheme->getReference() . '_h.png',
                       'footer'     => '/inside/includes/images/themes/footers/' . $myTheme->getReference() . '_f.png',
                       'logo'       => NULL
                      );
      }
    }
    // Thème personnalisé
    else
    {
      // Lecture préférence thème utilisateur
      $req2 = $bdd->query('SELECT * FROM preferences WHERE identifiant = "' . $_SESSION['user']['identifiant'] . '"');
      $data2 = $req2->fetch();
      $preferences = Preferences::withData($data2);
      $req2->closeCursor();

      if (!empty($preferences->getRef_theme()))
      {
        $req3 = $bdd->query('SELECT * FROM themes WHERE reference = "' . $preferences->getRef_theme() . '"');
        $data3 = $req3->fetch();

        if ($req3->rowCount() > 0)
        {
          $myTheme = Theme::withData($data3);

          if ($myTheme->getLogo() == "Y")
          {
            $theme = array('background' => '/inside/includes/images/themes/backgrounds/' . $myTheme->getReference() . '.png',
                           'header'     => '/inside/includes/images/themes/headers/' . $myTheme->getReference() . '_h.png',
                           'footer'     => '/inside/includes/images/themes/footers/' . $myTheme->getReference() . '_f.png',
                           'logo'       => '/inside/includes/images/themes/logos/' . $myTheme->getReference() . '_l.png'
                          );
          }
          else
          {
            $theme = array('background' => '/inside/includes/images/themes/backgrounds/' . $myTheme->getReference() . '.png',
                           'header'     => '/inside/includes/images/themes/headers/' . $myTheme->getReference() . '_h.png',
                           'footer'     => '/inside/includes/images/themes/footers/' . $myTheme->getReference() . '_f.png',
                           'logo'       => NULL
                          );
          }
        }

        $req3->closeCursor();
      }
    }

    return $theme;
  }

  // Formatage titres niveaux (succès)
  // RETOUR : titre niveau formaté
  function formatTitleLvl($lvl)
  {
    $name_lvl = "";

    switch ($lvl)
    {
      case "1";
        $name_lvl = '<div class="titre_section"><img src="/inside/includes/icons/profil/crown_grey.png" alt="crown_grey" class="logo_titre_section" /><div class="number_level">' . $lvl . '</div><div class="texte_titre_section">Seuls les plus forts y parviendront.</div></div></div>';
        break;

      case "2";
        $name_lvl = '<div class="titre_section"><img src="/inside/includes/icons/profil/crown_grey.png" alt="crown_grey" class="logo_titre_section" /><div class="number_level">' . $lvl . '</div><div class="texte_titre_section">Vous êtes encore là ?</div></div>';
        break;

      case "3";
        $name_lvl = '<div class="titre_section"><img src="/inside/includes/icons/profil/crown_grey.png" alt="crown_grey" class="logo_titre_section" /><div class="number_level">' . $lvl . '</div><div class="texte_titre_section">Votre charisme doit être impressionnant.</div></div>';
        break;

      default:
        $name_lvl = '<div class="titre_section"><img src="/inside/includes/icons/profil/crown_grey.png" alt="crown_grey" class="logo_titre_section" /><div class="number_level">' . $lvl . '</div></div>';
        break;
    }

    return $name_lvl;
  }

  // Formatage gagnants mission
  // Retour : phrase formatée
  function formatGagnants($listWinners)
  {
    switch (count($listWinners))
    {
      case 1:
        $phrase = 'Félicitations à <span class="contenu_gras">' . $listWinners[0] . '</span> pour sa victoire écrasante !';
        break;

      case 0:
        $phrase = '';
        break;

      default:
        $phrase = 'Félicitations à ';

        foreach ($listWinners as $winner)
        {
          if ($winner == end($listWinners))
          {
            $phrase = substr($phrase, 0, -2);
            $phrase .= ' et <span class="contenu_gras">' . $winner . '</span>';
          }
          else
            $phrase .= '<span class="contenu_gras">' . $winner . '</span>, ';
        }

        $phrase .= ' pour leur magnifique victoire !';
        break;
    }

    return $phrase;
  }

  // Génération notification
  // RETOUR : Aucun
  function insertNotification($author, $category, $content)
  {
    $date = date("Ymd");
    $time = date("His");

    global $bdd;

    // Stockage de l'enregistrement en table
    $req = $bdd->prepare('INSERT INTO notifications(author, date, time, category, content) VALUES(:author, :date, :time, :category, :content)');
    $req->execute(array(
      'author'   => $author,
      'date'     => $date,
      'time'     => $time,
      'category' => $category,
      'content'  => $content
        ));
    $req->closeCursor();
  }

  // Suppression notification
  // RETOUR : Aucun
  function deleteNotification($category, $content)
  {
    global $bdd;

    // Suppression de la table
    $req = $bdd->exec('DELETE FROM notifications WHERE category = "' . $category . '" AND content = "' . $content . '"');
  }

  // Contrôle notification existante
  // RETOUR : Booléen
  function controlNotification($category, $content)
  {
    $exist = false;

    global $bdd;

    if ($category == 'comments')
      $req = $bdd->query('SELECT * FROM notifications WHERE category = "' . $category . '" AND content = "' . $content . '" AND date = ' . date(Ymd));
    else
      $req = $bdd->query('SELECT * FROM notifications WHERE category = "' . $category . '" AND content = "' . $content . '"');
    $data = $req->fetch();

    if ($req->rowCount() > 0)
      $exist = true;

    $req->closeCursor();

    return $exist;
  }

  // Formatage phrases cultes
  // Retour : phrase formatée
  function formatCollector($collector)
  {
    $formatted = "";

    $search    = array("[", "]");
    $replace   = array("<strong style='color: #ff1937;'>", "</strong>");
    $formatted = str_replace($search, $replace, $collector);

    return $formatted;
  }

  // Dé-formatage phrases cultes
  // Retour : phrase dé-formatée
  function unformatCollector($collector)
  {
    $unformatted = "";

    $search    = array("[", "]");
    $replace   = array("", "");
    $unformatted = str_replace($search, $replace, $collector);

    return $unformatted;
  }

  // Formatage contexte phrases cultes
  // Retour : phrase formatée
  function formatContext($context)
  {
    $formatted = "";

    $search    = array("[", "]");
    $replace   = array("<strong style='color: #ff1937;'>", "</strong>");
    $formatted = str_replace($search, $replace, $context);

    return $formatted;
  }

  // Suppression des caractères ASCII invisibles (?)
  // Retour : phrase nettoyée
  function deleteInvisible($phrase)
  {
    $cleaned = preg_replace('[\xE2\x80\x8E]', '', $phrase);

    return $cleaned;
  }

  // Lecture liste des utilisateurs (chat)
  // Retour : Tableau d'utilisateurs
  function getUsersChat()
  {
    // Initialisation tableau d'utilisateurs
    $listeUsers = array();

    global $bdd;

    $reponse = $bdd->query('SELECT id, identifiant, pseudo, avatar FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
    while($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet User à partir des données remontées de la bdd
      $user = Profile::withData($donnees);

      // On ajoute la ligne au tableau
      array_push($listeUsers, $user);
    }
    $reponse->closeCursor();

    return $listeUsers;
  }

  // Rotation automatique des images en mode Portrait
  // RETOUR : Aucun
  function rotateImage($image, $type)
  {
    $degrees = 0;

    // Récupération des données EXIF
    $exif = exif_read_data($image);

    // Rotation
    if (!empty($exif['Orientation']))
    {
      switch ($exif['Orientation'])
      {
        case 3:
          $degrees = 180;
          break;

        case 6:
          $degrees = -90;
          break;

        case 8:
          $degrees = 90;
          break;

        case 1:
        default:
          $degrees = 0;
          break;
      }
    }

    if ($degrees != 0)
    {
      switch($type)
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

  // Génération valeur succès mission
  // RETOUR : Aucun
  function insertOrUpdateSuccesMission($reference, $identifiant)
  {
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

      default:
        break;
    }
  }

  // Génération valeur succès
  // RETOUR : Aucun
  function insertOrUpdateSuccesValue($reference, $identifiant, $incoming)
  {
    $value  = NULL;
    $action = NULL;

    global $bdd;

    // Détermination valeur à insérer
    switch ($reference)
    {
      // Valeur saisie conservée
      case "beginning":
      case "developper":
      case "padawan":
        $value = $incoming;
        break;

      // Incrémentation de la valeur précédente avec "incoming"
      case "publisher":
      case "viewer":
      case "commentator":
      case "listener":
      case "speaker":
      case "funny":
      case "self-satisfied":
      case "buyer":
      case "eater":
      case "generous":
      case "creator":
      case "applier":
      case "debugger":
      case "compiler":
      case "restaurant-finder":
      case "star-chief":
      case "cooker":
      case "recipe-master":
      case "christmas2017":
      case "christmas2017_2":
      case "golden-egg":
      case "rainbow-egg":
      case "wizard":
      case "christmas2018":
      case "christmas2018_2":
        $req0 = $bdd->query('SELECT * FROM success_users WHERE reference = "' . $reference . '" AND identifiant = "' . $identifiant . '"');
        $data0 = $req0->fetch();

        if ($req0->rowCount() > 0)
          $value = $data0['value'] + $incoming;
        else
          $value = $incoming;

        $req0->closeCursor();
        break;

      // Valeur maximale conservée
      case "greedy":
        $req0 = $bdd->query('SELECT * FROM success_users WHERE reference = "' . $reference . '" AND identifiant = "' . $identifiant . '"');
        $data0 = $req0->fetch();

        if ($req0->rowCount() > 0)
        {
          if ($incoming > $data0['value'])
            $value = $incoming;
        }
        else
          $value = $incoming;

        $req0->closeCursor();
        break;

      default:
        $value = NULL;
        break;
    }

    /****************************************/
    /*** Détermination action à effectuer ***/
    /****************************************/
    if (!is_null($value))
    {
      if ($value == 0)
        $action = 'delete';
      else
      {
        $req1 = $bdd->query('SELECT * FROM success_users WHERE reference = "' . $reference . '" AND identifiant = "' . $identifiant . '"');
        $data1 = $req1->fetch();

        if ($req1->rowCount() > 0)
          $action = 'update';
        else
          $action = 'insert';

        $req1->closeCursor();
      }
    }

    /***************************************************************/
    /*** Insertion / modification / suppression de chaque succès ***/
    /***************************************************************/
    switch ($action)
    {
      case 'insert':
        $req2 = $bdd->prepare('INSERT INTO success_users(reference, identifiant, value) VALUES(:reference, :identifiant, :value)');
        $req2->execute(array(
          'reference'   => $reference,
          'identifiant' => $identifiant,
          'value'       => $value
          ));
        $req2->closeCursor();
        break;

      case 'update':
        $req2 = $bdd->prepare('UPDATE success_users SET value = :value WHERE reference = "' . $reference . '" AND identifiant = "' . $identifiant . '"');
        $req2->execute(array(
          'value' => $value
        ));
        $req2->closeCursor();
        break;

      case 'delete':
        $req2 = $bdd->exec('DELETE FROM success_users WHERE reference = "' . $reference . '" AND identifiant = "' . $identifiant . '"');

      default:
        break;
    }
  }

  // Mise à jour expérience
  // RETOUR : Aucun
  function insertExperience($identifiant, $action)
  {
    global $bdd;

    $experience = 0;

    switch($action)
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
        break;
    }

    // Lecture expérience actuelle de l'utilisateur
    $req = $bdd->query('SELECT id, identifiant, experience FROM users WHERE identifiant = "' . $identifiant . '"');
    $data = $req->fetch();
    $current_experience = $data['experience'];
    $req->closeCursor();

    $new_experience = $current_experience + $experience;

    // Mise à jour de l'utilisateur
    $req2 = $bdd->prepare('UPDATE users SET experience = :experience WHERE identifiant = "' . $identifiant . '"');
    $req2->execute(array(
      'experience' => $new_experience
    ));
    $req2->closeCursor();
  }

  // Formatage Id type de restaurant
  // RETOUR : Id formaté
  function formatId($id)
  {
    // Transforme les caractères accentués en entités HTML
    $formatted = htmlentities($id, ENT_NOQUOTES, "utf-8");

    // Remplace les entités HTML pour avoir juste le premier caractères non accentué
    $formatted = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $formatted);

    // Remplace les ligatures tel que : œ, Æ ...
    $formatted = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $formatted);

    // Supprime tout le reste
    $formatted = preg_replace('#&[^;]+;#', '', $formatted);

    // Remplace les espaces
    $formatted = str_replace(" ", "_", $formatted);

    // Passe en minuscule
    $formatted = strtolower($formatted);

    return $formatted;
  }

  // Formatage du numéro de téléphone
  // RETOUR : Numéro formaté
  function formatPhoneNumber($phone)
  {
    $formattedPhone = substr($phone, 0, 2) . "." . substr($phone, 2, 2) . "." . substr($phone, 4, 2) . "." . substr($phone, 6, 2) . "." . substr($phone, 8, 2);

    return $formattedPhone;
  }

  // Conversion de l'expérience en niveau
  // RETOUR : Niveau
  function convertExperience($exp)
  {
    $level = floor(sqrt($exp / 10));

    return $level;
  }

  // Décode certains caractères
  // RETOUR : Chaîne décodée
  function decodeString($chaine)
  {
    $search  = array("&amp;", "&quot;", "&#039;", "&lt;", "&gt;");
    $replace = array("et", "", "", "", "");
    $chaine  = str_replace($search, $replace, $chaine);

    return $chaine;
  }
?>
