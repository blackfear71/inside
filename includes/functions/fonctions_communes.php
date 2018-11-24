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
                                       //'/inside/portail/eventmanager/eventmanager.php',
                                       '/inside/portail/expensecenter/expensecenter.php',
                                       '/inside/portail/moviehouse/details.php',
                                       '/inside/portail/moviehouse/mailing.php',
                                       '/inside/portail/moviehouse/moviehouse.php',
                                       '/inside/portail/moviehouse/saisie.php',
                                       '/inside/portail/notifications/notifications.php',
                                       '/inside/portail/petitspedestres/parcours.php',
                                       '/inside/portail/portail/portail.php',
                                       '/inside/portail/missions/missions.php',
                                       '/inside/portail/missions/details.php',
                                       '/inside/portail/search/search.php',
                                       '/inside/profil/profil.php'
                                      );
    $listZonesCompletes        = array('header',
                                       'nav',
                                       'aside',
                                       'footer',
                                       //'article'
                                      );
    $listZonesPartielles       = array('header',
                                       'aside',
                                       'footer',
                                       //'article'
                                      );
    $listPositionsHorizontales = array('left',
                                       'right',
                                       'middle',
                                      );
    $listPositionsVerticales   = array('top',
                                       'bottom',
                                       'middle'
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
      switch ($page)
      {
        // Cas avec <nav>
        case '/inside/portail/calendars/calendars.php':
        case '/inside/portail/collector/collector.php':
        //case '/inside/portail/eventmanager/eventmanager.php':
        case '/inside/portail/expensecenter/expensecenter.php':
        case '/inside/portail/moviehouse/details.php':
        case '/inside/portail/moviehouse/mailing.php':
        case '/inside/portail/moviehouse/moviehouse.php':
        case '/inside/portail/moviehouse/saisie.php':
        case '/inside/portail/petitspedestres/parcours.php':
        case '/inside/portail/missions/missions.php':
        case '/inside/portail/missions/details.php':
          $zone = $listZonesCompletes[array_rand($listZonesCompletes)];
          break;

        // Cas sans <nav>
        case '/inside/portail/bugs/bugs.php':
        case '/inside/portail/notifications/notifications.php':
        case '/inside/portail/portail/portail.php':
        case '/inside/profil/profil.php':
        case '/inside/portail/search/search.php':
        default:
          $zone = $listZonesPartielles[array_rand($listZonesPartielles)];
          break;
      }

      // Position
      switch ($zone)
      {
        case 'header':
        case 'nav':
        case 'footer':
          $position = $listPositionsHorizontales[array_rand($listPositionsHorizontales)];
          break;

        case 'aside':
        default:
          $position = $listPositionsVerticales[array_rand($listPositionsVerticales)];
          break;
      }

      // Icônes
      if ($position == 'left' OR $position == 'top' OR $position =='bottom' OR ($position == 'middle' AND $zone == 'aside'))
        $icone = $mission->getReference() . '_g';
      elseif ($position == 'right')
        $icone = $mission->getReference() . '_d';
      elseif ($position == 'middle' AND $zone != 'aside')
        $icone = $mission->getReference() . '_m';

      // Classe position
      $classe = $zone . '_' . $position . '_mission';

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

    // Modifier le compteur si de nouvelles pages sont rajoutées (actuellement 5*3*3*3 + 10*4*3*3 = 495 emplacements possibles)
    if (!empty($tableauMissions) AND count($tableauMissions) <= 495)
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

    // Lecture préférence thème utilisateur
    $req1 = $bdd->query('SELECT * FROM preferences WHERE identifiant = "' . $_SESSION['user']['identifiant'] . '"');
    $data1 = $req1->fetch();

    $preferences = Preferences::withData($data1);

    $req1->closeCursor();

    // Thème automatique
    if (empty($preferences->getRef_theme()))
    {
      $theme_present = false;

      // Détermination thème automatique présent
      $req2 = $bdd->query('SELECT * FROM themes WHERE ' . date("Ymd") . ' >= date_deb AND ' . date("Ymd") . ' <= date_fin');
      $data2 = $req2->fetch();

      if ($req2->rowCount() > 0)
      {
        $theme_present = true;
        $myTheme       = Theme::withData($data2);
      }

      $req2->closeCursor();

      // Thème présent
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
      // Thème par défaut
      else
        $theme = array();
    }
    // Thème utilisateur
    else
    {
      $req2 = $bdd->query('SELECT * FROM themes WHERE reference = "' . $preferences->getRef_theme() . '"');
      $data2 = $req2->fetch();

      $myTheme = Theme::withData($data2);

      $req2->closeCursor();

      if (!empty($myTheme->getReference()))
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
        $name_lvl = '<div class="level_succes">Niveau ' . $lvl . ' : <span class="name_lvl">Seuls les plus forts y parviendront.</span></div>';
        break;

      case "2";
        $name_lvl = '<div class="level_succes">Niveau ' . $lvl . ' : <span class="name_lvl">Vous êtes encore là ?</span></div>';
        break;

      case "3";
        $name_lvl = '<div class="level_succes">Niveau ' . $lvl . ' : <span class="name_lvl">Votre charisme doit être impressionnant.</span></div>';
        break;

      default:
        $name_lvl = '<div class="level_succes">Niveau ' . $lvl . '</div>';
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
        $phrase = "Félicitations à <strong>" . $listWinners[0] . "</strong> pour sa victoire écrasante !";
        break;

      case 0:
        $phrase = "";
        break;

      default:
        $phrase = "Félicitations à ";

        foreach ($listWinners as $winner)
        {
          if ($winner == end($listWinners))
          {
            $phrase = substr($phrase, 0, -2);
            $phrase .= " et <strong>" . $winner . "</strong>";
          }
          else
            $phrase .= "<strong>" . $winner . "</strong>, ";
        }

        $phrase .= " pour leur magnifique victoire !";
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
      echo 'test';

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

      // Incrémentation de la valeur précédente
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
      case "christmas2017":
      case "christmas2017_2":
      case "golden-egg":
      case "rainbow-egg":
      case "wizard":
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
?>
