<?php
  include_once('../../includes/classes/collectors.php');
  include_once('../../includes/classes/gateaux.php');
  include_once('../../includes/classes/movies.php');
  include_once('../../includes/classes/missions.php');
  include_once('../../includes/classes/news.php');
  include_once('../../includes/classes/notifications.php');
  include_once('../../includes/classes/profile.php');

  // METIER : Lecture des données préférences
  // RETOUR : Objet Preferences
  function getPreferences($identifiant)
  {
    // Lescture des préférences utilisateur
    $preferences = physiquePreferences($identifiant);

    // Retour
    return $preferences;
  }

  // METIER : Récupération liens portail
  // RETOUR : Tableau de liens
  function getPortail($preferences)
  {
    // Préférence MovieHouse
    switch ($preferences->getView_movie_house())
    {
      case 'C':
        $viewMovieHouse = 'cards';
        break;

      case 'H':
      default:
        $viewMovieHouse = 'home';
        break;
    }

    // Tableau des catégories
    $listeCategories = array(array('categorie' => 'MOVIE<br />HOUSE',
                                   'lien'      => '../moviehouse/moviehouse.php?view=' . $viewMovieHouse . '&year=' . date('Y') . '&action=goConsulter',
                                   'title'     => 'Movie House',
                                   'image'     => '../../includes/icons/common/movie_house.png',
                                   'alt'       => 'movie_house',
                                   'mobile'    => 'N'),
                             array('categorie' => 'LES ENFANTS !<br />À TABLE !',
                                   'lien'      => '../foodadvisor/foodadvisor.php?action=goConsulter',
                                   'title'     => 'Les enfants ! À table !',
                                   'image'     => '../../includes/icons/common/food_advisor.png',
                                   'alt'       => 'food_advisor',
                                   'mobile'    => 'Y'),
                             array('categorie' => 'COOKING<br />BOX',
                                   'lien'      => '../cookingbox/cookingbox.php?year=' . date('Y') . '&action=goConsulter',
                                   'title'     => 'Cooking Box',
                                   'image'     => '../../includes/icons/common/cooking_box.png',
                                   'alt'       => 'cooking_box',
                                   'mobile'    => 'N'),
                             array('categorie' => 'EXPENSE<br />CENTER',
                                   'lien'      => '../expensecenter/expensecenter.php?year=' . date('Y') . '&action=goConsulter',
                                   'title'     => 'Expense Center',
                                   'image'     => '../../includes/icons/common/expense_center.png',
                                   'alt'       => 'expense_center',
                                   'mobile'    => 'Y'),
                             array('categorie' => 'COLLECTOR<br />ROOM',
                                   'lien'      => '../collector/collector.php?action=goConsulter&page=1&sort=dateDesc&filter=none',
                                   'title'     => 'Collector Room',
                                   'image'     => '../../includes/icons/common/collector.png',
                                   'alt'       => 'collector',
                                   'mobile'    => 'N'),
                             array('categorie' => 'CALENDARS',
                                   'lien'      => '../calendars/calendars.php?year=' . date('Y') . '&action=goConsulter',
                                   'title'     => 'Calendars',
                                   'image'     => '../../includes/icons/common/calendars.png',
                                   'alt'       => 'calendars',
                                   'mobile'    => 'N'),
                             array('categorie' => 'LES PETITS<br />PÉDESTRES',
                                   'lien'      => '../petitspedestres/parcours.php?action=liste',
                                   'title'     => 'Les Petits Pédestres',
                                   'image'     => '../../includes/icons/common/petits_pedestres.png',
                                   'alt'       => 'petits_pedestres',
                                   'mobile'    => 'N'),
                             array('categorie' => 'MISSIONS :<br />INSIDER',
                                   'lien'      => '../missions/missions.php?action=goConsulter',
                                   'title'     => 'Missions : Insider',
                                   'image'     => '../../includes/icons/common/missions.png',
                                   'alt'       => 'missions',
                                   'mobile'    => 'N')/*,
                             array('categorie' => 'EVENT<br />MANAGER',
                                   'lien'      => '../eventmanager/eventmanager.php?action=goConsulter',
                                   'title'     => 'Event Manager',
                                   'image'     => '../../includes/icons/common/event_manager.png',
                                   'alt'       => 'event_manager',
                                   'mobile'    => 'N')*/
                           );

    // Retour
    return $listeCategories;
  }

  // METIER : Récupérations des news
  // RETOUR : Objets news
  function getNews($identifiant)
  {
    // Initialisations
    $tableauNews = array();

    /*************************/
    /* Message début semaine */
    /*************************/
    if (date('N') == 1 AND date('H') <= 12)
    {
      $news = new News();

      $news->setTitle('Une nouvelle ère commence...');
      $news->setContent('...et toute l\'équipe Inside vous souhaite de passer une agréable semaine !');
      $news->setDetails('Maintenant au boulot.');
      $news->setLogo('inside');
      $news->setLink('');

      array_push($tableauNews, $news);
    }

    /**************************/
    /* Message fin de semaine */
    /**************************/
    if (date('N') == 5 AND date('H') >= 14)
    {
      $news = new News();

      $news->setTitle('C\'est bientôt la fin, courage !');
      $news->setContent('Bon week-end à tous et à la semaine prochaine.');
      $news->setDetails('');
      $news->setLogo('inside');
      $news->setLink('');

      array_push($tableauNews, $news);
    }

    /*****************/
    /* Anniversaires */
    /*****************/
    $anniversaires = physiqueNewsAnniversaires();

    foreach ($anniversaires as $pseudoAnniversaire)
    {
      $news = new News();

      $news->setTitle('Joyeux anniversaire !');
      $news->setContent('C\'est l\'anniversaire de <strong>' . htmlspecialchars($pseudoAnniversaire) . '</strong> aujourd\'hui, souhaitez-lui de passer une excellente journée !');
      $news->setDetails('Vous n\'avez pas oublié les cadeaux au moins ?');
      $news->setLogo('anniversary');
      $news->setLink('');

      array_push($tableauNews, $news);
    }

    /**************/
    /* Vote repas */
    /**************/
    if (date('H') < 13 AND date('N') <= 5)
    {
      $news = new News();

      $news->setTitle('Où aller manger à midi ?');
      $news->setDetails('');
      $news->setLogo('food_advisor');
      $news->setLink('/inside/portail/foodadvisor/foodadvisor.php?action=goConsulter');

      // Récupération Id restaurant réservé
      $idRestaurant = physiqueRestaurantReserved();

      if (!empty($idRestaurant))
      {
        // Lecture du nom du restaurant
        $nomRestaurant = physiqueNomRestaurant($idRestaurant);

        $news->setContent('Le restaurant a été reservé ! Rendez-vous à <strong>' . htmlspecialchars($nomRestaurant) . '</strong> !');
      }
      else
      {
        // Contrôle vote effectué
        $voted = physiqueVoteUser($identifiant);

        if ($voted == true)
          $news->setContent('Vous avez déjà voté, allez voir le resultat en cliquant sur ce lien.');
        else
          $news->setContent('Vous n\'avez pas encore voté aujourd\'hui, allez tout de suite le faire !');
      }

      array_push($tableauNews, $news);
    }

    /************************/
    /* Gâteau de la semaine */
    /************************/
    $news = new News();

    $news->setTitle('La douceur de la semaine');
    $news->setLogo('cooking_box');
    $news->setLink('/inside/portail/cookingbox/cookingbox.php?year=' . date('Y') . '&action=goConsulter');

    // Vérification gâteau de la semaine présent
    $gateauSemainePresent = physiqueGateauSemainePresent();

    if ($gateauSemainePresent == true)
    {
      // Récupération des données du gâteau de la semaine
      $gateauSemaine = physiqueGateauSemaine();

      // Récupération du pseudo
      $pseudoGateau = physiquePseudoUser($gateauSemaine->getIdentifiant());

      if ($gateauSemaine->getCooked() == 'Y')
      {
        $news->setContent('Le gâteau a été fait par <strong>' . htmlspecialchars(formatUnknownUser($pseudoGateau, false, false)) . '</strong>, c\'était très bon !');
        $news->setDetails('A la semaine prochaine pour de nouvelles expériences...');
      }
      else
      {
        $news->setContent('Cette semaine, c\'est à <strong>' . htmlspecialchars(formatUnknownUser($pseudoGateau, false, false)) . '</strong> de faire le gâteau !');
        $news->setDetails('Spécialité culinaire en préparation...');
      }
    }
    else
    {
      $news->setContent('Personne n\'a encore été désigné pour faire le gâteau !');
      $news->setDetails('Dépêchez-vous de le dénoncer...');
    }

    array_push($tableauNews, $news);

    /*********************************/
    /* Dernière phrase culte ajoutée */
    /*********************************/
    $collector = physiqueDernierCollector();

    // Numéro de page de la phrase culte
    $numeroPage = numeroPageCollector($collector->getId());

    $news = new News();

    $news->setTitle('La der des ders');
    $news->setLogo('collector');
    $news->setLink('/inside/portail/collector/collector.php?action=goConsulter&page=' . $numeroPage . '&sort=dateDesc&filter=none&anchor=' . $collector->getId());

    // Recherche pseudo speaker
    if ($collector->getType_speaker() == 'other')
      $news->setDetails('Par ' . htmlspecialchars(formatUnknownUser($collector->getSpeaker(), false, false)));
    else
    {
      $speaker = physiquePseudoUser($collector->getSpeaker());

      $news->setDetails('Par ' . htmlspecialchars(formatUnknownUser($speaker, false, false)));
    }

    if (strlen($collector->getCollector()) > 90)
      $news->setContent(nl2br(htmlspecialchars(substr(unformatCollector($collector->getCollector()), 0, 90) . '...')));
    else
      $news->setContent(nl2br(htmlspecialchars(unformatCollector($collector->getCollector()))));

    array_push($tableauNews, $news);

    /***********************/
    /* Dernier film ajouté */
    /***********************/
    $movie = physiqueDernierFilm();

    $news = new News();

    $news->setTitle('Le dernier de la collection');
    $news->setContent($movie->getFilm());
    $news->setDetails('');
    $news->setLogo('movie_house');
    $news->setLink('/inside/portail/moviehouse/details.php?id_film=' . $movie->getId() . '&action=goConsulter');

    array_push($tableauNews, $news);

    /***************************/
    /* Prochaine sortie cinéma */
    /***************************/
    $film = physiqueSortieFilm();

    if (!empty($film))
    {
      $news = new News();

      $news->setTitle('On y court !');
      $news->setContent($film->getFilm());
      $news->setDetails('Rendez-vous le ' . formatDateForDisplay($film->getDate_doodle()) . ' au cinéma !');
      $news->setLogo('movie_house');
      $news->setLink('/inside/portail/moviehouse/details.php?id_film=' . $film->getId() . '&action=goConsulter');

      array_push($tableauNews, $news);
    }

    /*********************/
    /* Messages missions */
    /*********************/
    $dateJour       = date('Ymd');
    $dateJourMoins3 = date('Ymd', strtotime(date('Ymd') . ' - 3 days'));

    // Récupération missions actives + missions terminées depuis moins de 3 jours pour les résultats
    $missions = physiqueMissions($dateJour, $dateJourMoins3);

    if (!empty($missions))
    {
      // Récupération liste des gagnants
      $gagnantsMissions = getWinners($missions);

      // Formate les missions pour les news
      $newsMissions = formatNewsMissions($missions, $gagnantsMissions);

      if (!empty($newsMissions))
      {
        foreach ($newsMissions as $newsMission)
        {
          $news = new News();
          $news = $newsMission;

          array_push($tableauNews, $news);
        }
      }
    }

    // Retour
    return $tableauNews;
  }

  // METIER : Récupère le numéro de page pour un lien News
  // RETOUR : Numéro de page
  function numeroPageCollector($idCollector)
  {
    // Initialisations
    $numeroPage    = 0;
    $nombreParPage = 18;

    // Calcul de la position en base
    $position = physiquePositionCollector($idCollector);

    // Calcul du numéro de page
    $numeroPage = ceil($position / $nombreParPage);

    // Retour
    return $numeroPage;
  }

  // METIER : Récupération liste des gagnants des missions
  // RETOUR : Tableau des gagnants
  function getWinners($missions)
  {
    // Initialisations
    $gagnants = array();

    // On parcourt les missions
    foreach ($missions as $mission)
    {
      // Si la mission est terminée
      if (date('Ymd') > $mission->getDate_fin())
      {
        // Récupération de la liste des participants de la mission
        $listeUsers = physiqueUsersMission($mission->getId());

        // Traitement s'il y a des participants
        if (!empty($listeUsers))
        {
          // Récupération des données complémentaires des participants
          foreach ($listeUsers as &$user)
          {
            // Pseudo
            $user['pseudo'] = physiquePseudoUser($user['identifiant']);

            // Total de la mission
            $user['total'] = physiqueTotalUser($mission->getId(), $user['identifiant']);

            // Récupération du tri sur avancement puis identifiant
            $triTotal[]       = $user['total'];
            $triIdentifiant[] = $user['identifiant'];
          }

          unset($user);

          // Tri
          array_multisort($triTotal, SORT_DESC, $triIdentifiant, SORT_ASC, $listeUsers);

          unset($triTotal);
          unset($triIdentifiant);

          // Affectation du rang
          $prevTotal   = $listeUsers[0]['total'];
          $currentRank = 1;

          foreach ($listeUsers as &$user)
          {
            $currentTotal = $user['total'];

            if ($currentTotal != $prevTotal)
            {
              $currentRank += 1;
              $prevTotal    = $user['total'];
            }

            $user['rank'] = $currentRank;
          }

          unset($user);

          // On ne garde que les gagnants
          foreach ($listeUsers as &$user)
          {
            if ($user['rank'] != 1)
              unset($user);
          }

          unset($user);

          // On ajoute la ligne au tableau
          $gagnants[$mission->getId()] = $listeUsers;
        }
      }
    }

    // Retour
    return $gagnants;
  }

  // METIER : Formate les news des missions
  // RETOUR : Tableau d'objets News missions
  function formatNewsMissions($missions, $gagnants)
  {
    // Initialisations
    $messagesMissions = array();

    // Construction des messages pour chaque mission
    if (isset($missions) AND !empty($missions))
    {
      foreach ($missions as $keyMission => $mission)
      {
        $message = new News();

        $message->setTitle($mission->getMission());
        $message->setLogo('missions');

        // Association message mission à sa session (pour les missions en cours)
        foreach ($_SESSION['missions'] as $key_session => $ligneCurrentMission)
        {
          foreach ($ligneCurrentMission as $ligneMission)
          {
            if ($mission->getId() == $ligneMission['id_mission'])
            {
              $idCurrentMission  = $ligneMission['id_mission'];
              $keyCurrentMission = $key_session;
            }
            break;
          }

          if (isset($idCurrentMission) AND isset($keyCurrentMission))
            break;
        }

        // Mission > 1 jour (heure OK)
        if (isset($idCurrentMission)                           AND $mission->getId() == $idCurrentMission
        AND isset($_SESSION['missions'][$keyCurrentMission])   AND !empty($_SESSION['missions'][$keyCurrentMission])
        AND $mission->getDate_deb() != $mission->getDate_fin() AND date('His') >= $mission->getHeure())
        {
          $nbRestants = count($_SESSION['missions'][$keyCurrentMission]);
          $content    = '';

          $message->setLink('/inside/portail/missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter');

          if (date('Ymd') == $mission->getDate_deb())
            $content .= '<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> commence aujourd\'hui, trouve vite les objectifs avant les autres !</div>';

          if ($nbRestants == 1)
            $content .= '<div class="contenu_paragraphe">Il reste encore ' . $nbRestants . ' objectif à trouver aujourd\'hui pour terminer la mission <span class="contenu_gras">' . $mission->getMission() . '</span>.</div>';
          else
            $content .= '<div class="contenu_paragraphe">Il reste encore ' . $nbRestants . ' objectifs à trouver aujourd\'hui pour terminer la mission <span class="contenu_gras">' . $mission->getMission() . '</span>.</div>';

          if (date('Ymd') == $mission->getDate_fin())
            $content .= '<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> se termine aujourd\'hui, trouve vite les derniers objectifs !</div>';

          $message->setContent($content);
        }
        // Mission > 1 jour (heure KO), 1er jour
        elseif ((!isset($keyCurrentMission)             OR  empty($_SESSION['missions'][$keyCurrentMission]))
        AND      date('Ymd') == $mission->getDate_deb() AND date('His') < $mission->getHeure())
        {
          $message->setLink('/inside/portail/missions/missions.php?action=goConsulter');
          $message->setContent('<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> commence à ' . formatTimeForDisplayLight($mission->getHeure()) . ', reviens un peu plus tard pour continuer...</div>');
        }
        // Mission > 1 jour (heure KO), autre jour
        elseif ((!isset($keyCurrentMission)            OR  empty($_SESSION['missions'][$keyCurrentMission]))
        AND      date('Ymd') < $mission->getDate_fin() AND date('His') < $mission->getHeure())
        {
          $message->setLink('/inside/portail/missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter');
          $message->setContent('<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> commence à ' . formatTimeForDisplayLight($mission->getHeure()) . ', reviens un peu plus tard pour continuer...</div>');
        }
        // Mission > 1 jour (terminée)
        elseif ((!isset($keyCurrentMission)            OR  empty($_SESSION['missions'][$keyCurrentMission]))
        AND      date('Ymd') < $mission->getDate_fin() AND date('His') >= $mission->getHeure())
        {
          $message->setLink('/inside/portail/missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter');
          $message->setContent('<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> est terminée pour aujourd\'hui ! Reviens demain pour continuer...</div>');
        }
        // Mission > 1 jour (terminée, jour de fin)
        elseif ((!isset($keyCurrentMission)             OR  empty($_SESSION['missions'][$keyCurrentMission]))
        AND      date('Ymd') == $mission->getDate_fin() AND date('His') >= $mission->getHeure())
        {
          $message->setLink('/inside/portail/missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter');
          $message->setContent('<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> se termine aujourd\'hui. Tu as trouvé tous les objectifs, reviens demain pour voir les scores !</div>');
        }
        // Mission > 1 jour (heure KO, jour de fin)
        elseif ((!isset($keyCurrentMission)             OR  empty($_SESSION['missions'][$keyCurrentMission]))
        AND      date('Ymd') == $mission->getDate_fin() AND $mission->getDate_deb() != $mission->getDate_fin() AND date('His') < $mission->getHeure())
        {
          $message->setLink('/inside/portail/missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter');
          $message->setContent('<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> se termine aujourd\'hui. Trouve les derniers objectifs à partir de ' . formatTimeForDisplayLight($mission->getHeure()) . '.</div>');
        }
        // Mission > 1 jour (terminée, de jour de fin + 1 jours à + 3 jours)
        elseif ((!isset($keyCurrentMission) OR empty($_SESSION['missions'][$keyCurrentMission]))
        AND     (date('Ymd') >= date('Ymd', strtotime($mission->getDate_fin() . ' + 1 day')))
        AND     (date('Ymd') <= date('Ymd', strtotime($mission->getDate_fin() . ' + 3 days'))))
        {
          $message->setLink('/inside/portail/missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter');

          $content = '<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> est terminée. Va voir les résultats en cliquant sur ce message.</div>';

          // Noms des gagnants
          if (!empty($gagnants))
          {
            $listeGagnants = array();

            foreach ($gagnants[$mission->getId()] as $participant)
            {
              if ($participant['rank'] <= 3)
                array_push($listeGagnants, $participant['pseudo']);
            }

            $content .= '<div class="contenu_paragraphe">' . formatGagnants($listeGagnants) . '</div>';
          }

          $message->setContent($content);
        }
        // Mission 1 jour (heure OK)
        elseif (isset($keyCurrentMission)
        AND     isset($_SESSION['missions'][$keyCurrentMission])   AND !empty($_SESSION['missions'][$keyCurrentMission])
        AND     $mission->getDate_deb() == $mission->getDate_fin() AND date('His') >= $mission->getHeure())
        {
          $nbRestants = count($_SESSION['missions'][$keyCurrentMission]);

          $message->setLink('/inside/portail/missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter');

          if ($nbRestants == 1)
            $message->setContent('<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> ne dure qu\'une journée et il reste encore ' . $nbRestants . ' objectif à trouver !</div>');
          else
            $message->setContent('<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> ne dure qu\'une journée et il reste encore ' . $nbRestants . ' objectifs à trouver !</div>');
        }
        // Mission 1 jour (heure KO)
        elseif ((!isset($keyCurrentMission)                         OR  empty($_SESSION['missions'][$keyCurrentMission]))
        AND      $mission->getDate_deb() == $mission->getDate_fin() AND date('His') < $mission->getHeure())
        {
          $message->setLink('/inside/portail/missions/missions.php?action=goConsulter');
          $message->setContent('<div class="contenu_paragraphe">La mission <span class="contenu_gras">' . $mission->getMission() . '</span> commence à ' . formatTimeForDisplayLight($mission->getHeure()) . ', reviens un peu plus tard pour continuer...</div>');
        }

        unset($idCurrentMission);
        unset($keyCurrentMission);

        array_push($messagesMissions, $message);
      }
    }

    // Retour
    return $messagesMissions;
  }
?>
