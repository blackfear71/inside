<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/profile.php');
  include_once('../../includes/classes/news.php');
  include_once('../../includes/classes/notifications.php');
  include_once('../../includes/classes/missions.php');

  // METIER : Lecture des données préférences
  // RETOUR : Objet Preferences
  function getPreferences($user)
  {
    global $bdd;

    // Lecture des préférences
    $reponse = $bdd->query('SELECT * FROM preferences WHERE identifiant = "' . $user . '"');
    $donnees = $reponse->fetch();

    // Instanciation d'un objet Profil à partir des données remontées de la bdd
    $preferences = Preferences::withData($donnees);

    $reponse->closeCursor();

    return $preferences;
  }

  // METIER : Récupérations des news
  // RETOUR : Objets news
  function getNews($user)
  {
    $tabNews = array();

    global $bdd;

    // Message début semaine
    if (date("N") == 1 AND date("H") <= 12)
    {
      $myNews = new News();

      $myNews->setTitle("Une nouvelle ère commence...");
      $myNews->setContent("...et toute l'équipe Inside vous souhaite de passer une agréable semaine !");
      $myNews->setDetails("Maintenant au boulot.");
      $myNews->setLogo("inside");
      $myNews->setLink("");

      array_push($tabNews, $myNews);
    }

    // Message fin de semaine
    if (date("N") == 5 AND date("H") >= 14)
    {
      $myNews = new News();

      $myNews->setTitle("C'est bientôt la fin, courage !");
      $myNews->setContent("Bon week-end à tous et à la semaine prochaine.");
      $myNews->setDetails("");
      $myNews->setLogo("inside");
      $myNews->setLink("");

      array_push($tabNews, $myNews);
    }

    // Anniversaires
    $req0 = $bdd->query('SELECT id, identifiant, pseudo, anniversary FROM users WHERE SUBSTR(anniversary, 5, 4) = "' . date("md") . '" ORDER BY identifiant ASC');
    while ($data0 = $req0->fetch())
    {
      $myNews = new News();

      $myNews->setTitle("Joyeux anniversaire !");
      $myNews->setContent("C'est l'anniversaire de <strong>" . htmlspecialchars($data0['pseudo']) . "</strong> aujourd'hui, souhaitez-lui de passer une excellente journée !");
      $myNews->setDetails("Vous n'avez pas oublié les cadeaux au moins ?");
      $myNews->setLogo("anniversary");
      $myNews->setLink("");

      array_push($tabNews, $myNews);
    }
    $req0->closeCursor();

    // Vote repas
    if (date("H") < 13 AND date("N") <= 5)
    {
      $myNews   = new News();
      $reserved = false;

      $myNews->setTitle("Où aller manger à midi ?");
      $myNews->setDetails("");
      $myNews->setLogo("food_advisor");
      $myNews->setLink("/inside/portail/foodadvisor/foodadvisor.php?action=goConsulter");

      // Contrôle si déjà réservé
      $req1 = $bdd->query('SELECT * FROM food_advisor_choices WHERE date = "' . date("Ymd") . '" AND reserved = "Y"');
      $data1 = $req1->fetch();

      if ($req1->rowCount() > 0)
      {
        $id_restaurant = $data1['id_restaurant'];
        $reserved      = true;

        // Lecture du nom du restaurant
        $req2 = $bdd->query('SELECT * FROM food_advisor_restaurants WHERE id = ' . $id_restaurant);
        $data2 = $req2->fetch();
        $myNews->setContent("Le restaurant a été reservé ! Rendez-vous à <strong>" . htmlspecialchars($data2['name']) . "</strong> !");
        $req2->closeCursor();
      }

      $req1->closeCursor();

      // Contrôle si vote effectué
      if ($reserved == false)
      {
        $req2 = $bdd->query('SELECT * FROM food_advisor_users WHERE date = "' . date("Ymd") . '" AND identifiant = "' . $user . '"');

        $myNews->setTitle("Où aller manger à midi ?");
        $myNews->setDetails("");
        $myNews->setLogo("food_advisor");
        $myNews->setLink("/inside/portail/foodadvisor/foodadvisor.php?action=goConsulter");

        if ($req2->rowCount() > 0)
          $myNews->setContent("Vous avez déjà voté, allez voir le resultat en cliquant sur ce lien.");
        else
          $myNews->setContent("Vous n'avez pas encore voté aujourd'hui, allez tout de suite le faire !");

        $req2->closeCursor();
      }

      array_push($tabNews, $myNews);
    }

    // Gâteau de la semaine
    $myNews = new News();

    $myNews->setTitle("La douceur de la semaine");
    $myNews->setLogo("cooking_box");
    $myNews->setLink("/inside/portail/cookingbox/cookingbox.php?year=" . date('Y') . "&action=goConsulter");

    $req3 = $bdd->query('SELECT * FROM cooking_box WHERE week = "' . date('W') . '" AND year = "' . date('Y') . '"');
    $data3 = $req3->fetch();

    if ($req3->rowCount() > 0)
    {
      // Pseudo
      $req4 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $data3['identifiant'] . '"');
      $data4 = $req4->fetch();
      $pseudo = $data4['pseudo'];
      $req4->closeCursor();

      if ($data3['cooked'] == "Y")
      {
        $myNews->setContent("Le gâteau a été fait par <strong>" . htmlspecialchars(formatUnknownUser($pseudo, false, false)) . "</strong>, c'était très bon !");
        $myNews->setDetails("A la semaine prochaine pour de nouvelles expériences...");
      }
      else
      {
        $myNews->setContent("Cette semaine, c'est à <strong>" . htmlspecialchars(formatUnknownUser($pseudo, false, false)) . "</strong> de faire le gâteau !");
        $myNews->setDetails("Spécialité culinaire en préparation...");
      }
    }
    else
    {
      $myNews->setContent("Personne n'a encore été désigné pour faire le gâteau !");
      $myNews->setDetails("Dépêchez-vous de le dénoncer...");
    }

    $req3->closeCursor();

    array_push($tabNews, $myNews);

    // Dernière phrase culte ajoutée
    $myNews = new News();

    $req5 = $bdd->query('SELECT * FROM collector WHERE type_collector = "T" ORDER BY date_add DESC, id DESC LIMIT 1');
    $data5 = $req5->fetch();

    $num_page = numPageCollector($data5['id']);

    $myNews->setTitle("La der des ders");
    $myNews->setLogo("collector");
    $myNews->setLink("/inside/portail/collector/collector.php?action=goConsulter&page=" . $num_page . "&sort=dateDesc&filter=none&anchor=" . $data5['id']);

    if ($data5['type_speaker'] == "other")
      $myNews->setDetails("Par " . htmlspecialchars(formatUnknownUser($data5['speaker'], false, false)));
    else
    {
      $reponse = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $data5['speaker'] . '"');
      $donnees = $reponse->fetch();
      $myNews->setDetails("Par " . htmlspecialchars(formatUnknownUser($donnees['pseudo'], false, false)));
      $reponse->closeCursor();
    }

    if (strlen($data5['collector']) > 90)
      $myNews->setContent(htmlspecialchars(nl2br(substr(unformatCollector($data5['collector']), 0, 90) . "...")));
    else
      $myNews->setContent(htmlspecialchars(nl2br(unformatCollector($data5['collector']))));

    $req5->closeCursor();

    array_push($tabNews, $myNews);

    // Dernier film ajouté
    $myNews = new News();

    $req6 = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" ORDER BY date_add DESC, id DESC LIMIT 1');
    $data6 = $req6->fetch();

    $myNews->setTitle("Le dernier de la collection");
    $myNews->setContent($data6['film']);
    $myNews->setDetails("");
    $myNews->setLogo("movie_house");
    $myNews->setLink("/inside/portail/moviehouse/details.php?id_film=" . $data6['id'] . "&action=goConsulter");

    $req6->closeCursor();

    array_push($tabNews, $myNews);

    // Prochaine sortie cinéma
    $req7 = $bdd->query('SELECT * FROM movie_house WHERE to_delete != "Y" AND date_doodle >= "' . date("Ymd") . '" ORDER BY date_doodle ASC, id ASC LIMIT 1');
    $data7 = $req7->fetch();

    if ($req7->rowCount() > 0)
    {
      $myNews = new News();

      $myNews->setTitle("On y court !");
      $myNews->setContent($data7['film']);
      $myNews->setDetails("Rendez-vous le " . formatDateForDisplay($data7['date_doodle']) . " au cinéma !");
      $myNews->setLogo("movie_house");
      $myNews->setLink("/inside/portail/moviehouse/details.php?id_film=" . $data7['id'] . "&action=goConsulter");

      array_push($tabNews, $myNews);
    }

    $req7->closeCursor();

    // Messages missions
    $missions = getMissions();

    if (!empty($missions))
    {
      $gagnantsMissions = getWinners($missions);
      $newsMissions     = formatNewsMissions($missions, $gagnantsMissions);

      if (!empty($newsMissions))
      {
        foreach ($newsMissions as $newsMission)
        {
          $myNews = new News();
          $myNews = $newsMission;
          array_push($tabNews, $myNews);
        }
      }
    }

    return $tabNews;
  }

  // METIER : Récupère le numéro de page pour un lien News
  // RETOUR : Numéro de page
  function numPageCollector($id)
  {
    $numPage     = 0;
    $nb_par_page = 18;
    $position    = 1;

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

    $numPage = $nb_pages = ceil($position / $nb_par_page);

    return $numPage;
  }

  // METIER : Récupération liens portail
  // RETOUR : Tableau de liens
  function getPortail($preferences)
  {
    // Préférence MovieHouse
    switch ($preferences->getView_movie_house())
    {
      case "C":
        $view_movie_house = "cards";
        break;

      case "H":
      default:
        $view_movie_house = "home";
        break;
    }

    // Tableau des catégories
    $liste_categories = array(array('categorie' => 'MOVIE<br />HOUSE',
                                    'lien'      => '../moviehouse/moviehouse.php?view=' . $view_movie_house . '&year=' . date("Y") . '&action=goConsulter',
                                    'title'     => 'Movie House',
                                    'image'     => '../../includes/icons/common/movie_house.png',
                                    'alt'       => 'movie_house'),
                              array('categorie' => 'LES ENFANTS !<br />À TABLE !',
                                    'lien'      => '../foodadvisor/foodadvisor.php?action=goConsulter',
                                    'title'     => 'Les enfants ! À table !',
                                    'image'     => '../../includes/icons/common/food_advisor.png',
                                    'alt'       => 'food_advisor'),
                              array('categorie' => 'COOKING<br />BOX',
                                    'lien'      => '../cookingbox/cookingbox.php?year=' . date("Y") . '&action=goConsulter',
                                    'title'     => 'Cooking Box',
                                    'image'     => '../../includes/icons/common/cooking_box.png',
                                    'alt'       => 'cooking_box'),
                              array('categorie' => 'EXPENSE<br />CENTER',
                                    'lien'      => '../expensecenter/expensecenter.php?year=' . date("Y") . '&action=goConsulter',
                                    'title'     => 'Expense Center',
                                    'image'     => '../../includes/icons/common/expense_center.png',
                                    'alt'       => 'expense_center'),
                              array('categorie' => 'COLLECTOR<br />ROOM',
                                    'lien'      => '../collector/collector.php?action=goConsulter&page=1&sort=dateDesc&filter=none',
                                    'title'     => 'Collector Room',
                                    'image'     => '../../includes/icons/common/collector.png',
                                    'alt'       => 'collector'),
                              array('categorie' => 'CALENDARS',
                                    'lien'      => '../calendars/calendars.php?year=' . date("Y") . '&action=goConsulter',
                                    'title'     => 'Calendars',
                                    'image'     => '../../includes/icons/common/calendars.png',
                                    'alt'       => 'calendars'),
                              array('categorie' => 'LES PETITS<br />PÉDESTRES',
                                    'lien'      => '../petitspedestres/parcours.php?action=liste',
                                    'title'     => 'Les Petits Pédestres',
                                    'image'     => '../../includes/icons/common/petits_pedestres.png',
                                    'alt'       => 'petits_pedestres'),
                              array('categorie' => 'MISSIONS :<br />INSIDER',
                                    'lien'      => '../missions/missions.php?action=goConsulter',
                                    'title'     => 'Missions : Insider',
                                    'image'     => '../../includes/icons/common/missions.png',
                                    'alt'       => 'missions')/*,
                              array('categorie' => 'EVENT<br />MANAGER',
                                    'lien'      => '../eventmanager/eventmanager.php?action=goConsulter',
                                    'title'     => 'Event Manager',
                                    'image'     => '../../includes/icons/common/event_manager.png',
                                    'alt'       => 'event_manager')*/
                             );

    return $liste_categories;
  }

  // METIER : Récupération missions actives + 3 jours pour les résultats
  // RETOUR : Objet mission
  function getMissions()
  {
    $missions  = array();
    $date_jour = date('Ymd');

    global $bdd;

    $date_jour_moins_3 = date("Ymd", strtotime(date("Ymd") . ' - 3 days'));

    $reponse = $bdd->query('SELECT * FROM missions WHERE date_deb <= ' . $date_jour . ' AND date_fin >= ' . $date_jour_moins_3 . ' ORDER BY date_deb ASC');
    while ($donnees = $reponse->fetch())
    {
      $myMission = Mission::withData($donnees);
      array_push($missions, $myMission);
    }
    $reponse->closeCursor();

    return $missions;
  }

  // METIER : Récupération liste des gagnants
  // RETOUR : Tableau gagnants
  function getWinners($missions)
  {
    $gagnants = array();

    foreach ($missions as $mission)
    {
      if (date('Ymd') > $mission->getDate_fin())
      {
        // Récupération des participants
        $participants = array();

        global $bdd;

        $reponse1 = $bdd->query('SELECT DISTINCT identifiant FROM missions_users WHERE id_mission = ' . $mission->getId() . ' ORDER BY identifiant ASC');
        while ($donnees1 = $reponse1->fetch())
        {
          $reponse2 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $donnees1['identifiant'] . '"');
          $donnees2 = $reponse2->fetch();

          $myParticipant = Profile::withData($donnees2);

          $reponse2->closeCursor();

          array_push($participants, $myParticipant);
        }
        $reponse1->closeCursor();

        // Récupération du classement
        $ranking = array();

        foreach ($participants as $user)
        {
          $totalMission = 0;
          $initRankUser = 0;

          // Nombre total d'objectifs sur la mission
          $reponse = $bdd->query('SELECT * FROM missions_users WHERE id_mission = ' . $mission->getId() . ' AND identifiant = "' . $user->getIdentifiant() . '"');
          while ($donnees = $reponse->fetch())
          {
            $totalMission += $donnees['avancement'];
          }
          $reponse->closeCursor();

          $myRanking = array('id_mission'  => $mission->getId(),
                             'identifiant' => $user->getIdentifiant(),
                             'pseudo'      => $user->getPseudo(),
                             'total'       => $totalMission,
                             'rank'        => $initRankUser
                           );

          array_push($ranking, $myRanking);
        }

        // Tri classement et extraction gagnants
        if (!empty($ranking))
        {
          unset($tri_rank);
          unset($tri_alpha);

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

          // On ne garde que les gagnants
          foreach ($ranking as &$rankUser)
          {
            if ($rankUser['rank'] != 1)
              unset($rankUser);
          }

          unset($rankUser);

          array_push($gagnants, $ranking);
        }
      }
    }

    return $gagnants;
  }

  // METIER : Récupère un tableau d'objets News des missions
  // RETOUR : Tableau d'objets News missions
  function formatNewsMissions($missions, $gagnants)
  {
    $messagesMissions = array();

    if (isset($missions) AND !empty($missions))
    {
      foreach ($missions as $keyMission => $mission)
      {
        $myMessage = new News();

        $myMessage->setTitle($mission->getMission());
        $myMessage->setLogo("missions");

        // Association message mission à sa session
        foreach ($_SESSION['missions'] as $key_session => $ligneCurrentMission)
        {
          foreach ($ligneCurrentMission as $ligneMission)
          {
            if ($mission->getId() == $ligneMission['id_mission'])
            {
              $id_current_mission  = $ligneMission['id_mission'];
              $key_current_mission = $key_session;
            }
            break;
          }

          if (isset($id_current_mission) AND isset($key_current_mission))
            break;
        }

        // Génération des messages
        // Mission > 1 jour (heure OK)
        if (isset($id_current_mission) AND $mission->getId() == $id_current_mission AND isset($_SESSION['missions'][$key_current_mission]) AND !empty($_SESSION['missions'][$key_current_mission]) AND $mission->getDate_deb() != $mission->getDate_fin() AND date('His') >= $mission->getHeure())
        {
          $nbRestants = count($_SESSION['missions'][$key_current_mission]);
          $content    = "";

          $myMessage->setLink('/inside/portail/missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter');

          if (date('Ymd') == $mission->getDate_deb())
            $content .= '<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> commence aujourd\'hui, trouve vite les objectifs avant les autres !</div>';

          if ($nbRestants == 1)
            $content .= '<div class="contenu_paragraphe">Il reste encore ' . $nbRestants . ' objectif à trouver aujourd\'hui pour terminer la mission <span class="contenu_gras">' . $mission->getMission() . '</span>.</div>';
          else
            $content .= '<div class="contenu_paragraphe">Il reste encore ' . $nbRestants . ' objectifs à trouver aujourd\'hui pour terminer la mission <span class="contenu_gras">' . $mission->getMission() . '</span>.</div>';

          if (date('Ymd') == $mission->getDate_fin())
            $content .= '<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> se termine aujourd\'hui, trouve vite les derniers objectifs !</div>';

          $myMessage->setContent($content);
        }
        // Mission > 1 jour (heure KO), 1er jour
        elseif ((!isset($key_current_mission) OR empty($_SESSION['missions'][$key_current_mission])) AND date('Ymd') == $mission->getDate_deb() AND date('His') < $mission->getHeure())
        {
          $myMessage->setLink('/inside/portail/missions/missions.php?action=goConsulter');
          $myMessage->setContent('<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> commence à ' . formatTimeForDisplayLight($mission->getHeure()) . ', reviens un peu plus tard pour continuer...</div>');
        }
        // Mission > 1 jour (heure KO), autre jour
        elseif ((!isset($key_current_mission) OR empty($_SESSION['missions'][$key_current_mission])) AND date('Ymd') < $mission->getDate_fin() AND date('His') < $mission->getHeure())
        {
          $myMessage->setLink('/inside/portail/missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter');
          $myMessage->setContent('<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> commence à ' . formatTimeForDisplayLight($mission->getHeure()) . ', reviens un peu plus tard pour continuer...</div>');
        }
        // Mission > 1 jour (terminée)
        elseif ((!isset($key_current_mission) OR empty($_SESSION['missions'][$key_current_mission])) AND date('Ymd') < $mission->getDate_fin() AND date('His') >= $mission->getHeure())
        {
          $myMessage->setLink('/inside/portail/missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter');
          $myMessage->setContent('<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> est terminée pour aujourd\'hui ! Reviens demain pour continuer...</div>');
        }
        // Mission > 1 jour (terminée, jour de fin)
        elseif ((!isset($key_current_mission) OR empty($_SESSION['missions'][$key_current_mission])) AND date('Ymd') == $mission->getDate_fin() AND date('His') >= $mission->getHeure())
        {
          $myMessage->setLink('/inside/portail/missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter');
          $myMessage->setContent('<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> se termine aujourd\'hui. Tu as trouvé tous les objectifs, reviens demain pour voir les scores !</div>');
        }
        // Mission > 1 jour (heure KO, jour de fin)
        elseif ((!isset($key_current_mission) OR empty($_SESSION['missions'][$key_current_mission])) AND date('Ymd') == $mission->getDate_fin() AND $mission->getDate_deb() != $mission->getDate_fin() AND date('His') < $mission->getHeure())
        {
          $myMessage->setLink('/inside/portail/missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter');
          $myMessage->setContent('<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> se termine aujourd\'hui. Trouve les derniers objectifs à partir de ' . formatTimeForDisplayLight($mission->getHeure()) . '.</div>');
        }
        // Mission > 1 jour (terminée, de jour de fin + 1 jours à + 3 jours)
        elseif ((!isset($key_current_mission) OR empty($_SESSION['missions'][$key_current_mission])) AND (date('Ymd') >= date('Ymd', strtotime($mission->getDate_fin() . ' + 1 day'))) AND (date('Ymd') <= date('Ymd', strtotime($mission->getDate_fin() . ' + 3 days'))))
        {
          $myMessage->setLink('/inside/portail/missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter');

          $content = '<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> est terminée. Va voir les résultats en cliquant sur ce message.</div>';

          // Noms des gagnants
          if (!empty($gagnants))
          {
            $liste_gagnants = array();

            foreach ($gagnants as $missionGagnants)
            {
              foreach ($missionGagnants as $gagnant)
              {
                if ($gagnant['id_mission'] == $mission->getId() AND $gagnant['rank'] <= 3)
                  array_push($liste_gagnants, $gagnant['pseudo']);
              }
            }

            $content .= '<div class="contenu_paragraphe">' . formatGagnants($liste_gagnants) . '</div>';
          }

          $myMessage->setContent($content);
        }
        // Mission 1 jour (heure OK)
        elseif (isset($key_current_mission) AND isset($_SESSION['missions'][$key_current_mission]) AND !empty($_SESSION['missions'][$key_current_mission]) AND $mission->getDate_deb() == $mission->getDate_fin() AND date('His') >= $mission->getHeure())
        {
          $nbRestants = count($_SESSION['missions'][$key_current_mission]);

          $myMessage->setLink('/inside/portail/missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter');

          if ($nbRestants == 1)
            $myMessage->setContent('<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> ne dure qu\'une journée et il reste encore ' . $nbRestants . ' objectif à trouver !</div>');
          else
            $myMessage->setContent('<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> ne dure qu\'une journée et il reste encore ' . $nbRestants . ' objectifs à trouver !</div>');
        }
        // Mission 1 jour (heure KO)
        elseif ((!isset($key_current_mission) OR empty($_SESSION['missions'][$key_current_mission])) AND $mission->getDate_deb() == $mission->getDate_fin() AND date('His') < $mission->getHeure())
        {
          $myMessage->setLink('/inside/portail/missions/missions.php?action=goConsulter');
          $myMessage->setContent('<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> commence à ' . formatTimeForDisplayLight($mission->getHeure()) . ', reviens un peu plus tard pour continuer...</div>');
        }

        unset($id_current_mission);
        unset($key_current_mission);

        array_push($messagesMissions, $myMessage);
      }
    }

    return $messagesMissions;
  }
?>
