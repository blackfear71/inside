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
      // Id mission
      $id_mission = $mission->getId();

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
                                'ref_mission' => $ref_mission,
                                'key_mission' => $key,
                                'page'        => $page,
                                'zone'        => $zone,
                                'position'    => $position,
                                'icon'        => $icone,
                                'class'       => $classe
                               );

      array_push($missionButtons, $myMissionButtons);
    }

    return $missionButtons;
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
        $theme = array('background' => '/inside/includes/themes/backgrounds/' . $myTheme->getReference() . '.png',
                       'header'     => '/inside/includes/themes/headers/' . $myTheme->getReference() . '_h.png',
                       'footer'     => '/inside/includes/themes/footers/' . $myTheme->getReference() . '_f.png',
                      );
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
        $theme = array('background' => '/inside/includes/themes/backgrounds/' . $myTheme->getReference() . '.png',
                       'header'     => '/inside/includes/themes/headers/' . $myTheme->getReference() . '_h.png',
                       'footer'     => '/inside/includes/themes/footers/' . $myTheme->getReference() . '_f.png',
                      );
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
?>
