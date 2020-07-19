<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/notifications.php');

  // METIER : Lecture nombre de pages pour la section "Toutes" des notifications
  // RETOUR : Nombre de pages
  function getPages($view, $user)
  {
    $nombrePages         = 0;
    $nombreNotifications = 0;
    $nombreParPage       = 20;

    global $bdd;

    if ($view == 'me')
      $req = $bdd->query('SELECT COUNT(id) AS nb_notif FROM notifications WHERE author = "' . $user . '" OR category = "' . $user . '"');
    elseif ($view == 'week')
    {
      $dateMoins7 = date('Ymd', strtotime(date('Ymd') . ' - 7 days'));
      $req = $bdd->query('SELECT COUNT(id) AS nb_notif FROM notifications WHERE date <= ' . date('Ymd') . ' AND date > ' . $dateMoins7);
    }
    else
      $req = $bdd->query('SELECT COUNT(id) AS nb_notif FROM notifications');

    $data = $req->fetch();

    $nombreNotifications = $data['nb_notif'];

    $req->closeCursor();

    $nombrePages = ceil($nombreNotifications / $nombreParPage);

    return $nombrePages;
  }

  // METIER : Lecture des notifications en fonction de la vue
  // RETOUR : Liste des notifications
  function getNotifications($view, $user, $nombrePages, $page)
  {
    $listeNotifications = array();

    global $bdd;

    // Récupération des notifications en fonction de la vue
    switch ($view)
    {
      case 'me':
        // Pagination
        $nombreParPage = 20;

        // Contrôle dernière page
        if ($page > $nombrePages)
          $page = $nombrePages;

        // Calcul première entrée
        $premiereEntree = ($page - 1) * $nombreParPage;

        $reponse = $bdd->query('SELECT * FROM notifications WHERE author = "' . $user . '" OR category = "' . $user . '" ORDER BY date DESC, time DESC, id DESC LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
        break;

      case 'today':
        $reponse = $bdd->query('SELECT * FROM notifications WHERE date = ' . date('Ymd') . ' ORDER BY time DESC, id DESC');
        break;

      case 'week':
        // Pagination
        $nombreParPage = 20;

        // Contrôle dernière page
        if ($page > $nombrePages)
          $page = $nombrePages;

        // Calcul première entrée
        $premiereEntree = ($page - 1) * $nombreParPage;

        $dateMoins7 = date('Ymd', strtotime(date('Ymd') . ' - 7 days'));
        $reponse = $bdd->query('SELECT * FROM notifications WHERE date <= ' . date('Ymd') . ' AND date > ' . $dateMoins7 . ' ORDER BY date DESC, id DESC LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
        break;

      case 'all':
      default:
        // Pagination
        $nombreParPage = 20;

        // Contrôle dernière page
        if ($page > $nombrePages)
          $page = $nombrePages;

        // Calcul première entrée
        $premiereEntree = ($page - 1) * $nombreParPage;

        $reponse = $bdd->query('SELECT * FROM notifications ORDER BY date DESC, time DESC, id DESC LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
        break;
    }

    while ($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet Notification à partir des données remontées de la bdd
      $notification = Notification::withData($donnees);
      array_push($listeNotifications, $notification);
    }

    $reponse->closeCursor();

    return $listeNotifications;
  }

  // METIER : Formatage des notifications (icône, phrase & lien)
  // RETOUR : Notifications formatées
  function formatNotifications($notifications)
  {
    global $bdd;

    foreach ($notifications as $key => $notification)
    {
      $icone  = '';
      $phrase = '';
      $lien   = '';

      // Paramétrage des icônes, phrases et liens en fonction de la catégorie
      switch ($notification->getCategory())
      {
        case 'film':
          // Recherche du titre du film
          $reponse = $bdd->query('SELECT id, film, to_delete FROM movie_house WHERE id = ' . $notification->getContent());
          $donnees = $reponse->fetch();
          $titreFilm = $donnees['film'];
          $toDelete  = $donnees['to_delete'];
          $reponse->closeCursor();

          if ($toDelete != 'Y')
          {
            $icone  = 'movie_house';
            $phrase = 'Le film <strong>' . $titreFilm . '</strong> vient d\'être ajouté ! Allez vite le voir &nbsp;<img src="../../includes/icons/common/smileys/1.png" alt="smiley_1" class="smiley" />';
            $lien   = '/inside/portail/moviehouse/details.php?id_film=' . $notification->getContent() . '&action=goConsulter';
          }
          break;

        case 'doodle':
          // Recherche du titre du film et du Doodle
          $reponse = $bdd->query('SELECT id, film, doodle, to_delete FROM movie_house WHERE id = ' . $notification->getContent());
          $donnees = $reponse->fetch();
          $titreFilm = $donnees['film'];
          $doodle    = $donnees['doodle'];
          $toDelete  = $donnees['to_delete'];
          $reponse->closeCursor();

          if ($toDelete != 'Y')
          {
            $icone  = 'doodle';
            $phrase = 'Un Doodle vient d\'être mis en place pour le film <strong>' . $titreFilm . '</strong>. N\'oubliez pas d\'y répondre si vous êtes intéressé(e) !';
            $lien   = $doodle;
          }
          break;

        case 'cinema':
          // Recherche du titre du film
          $reponse = $bdd->query('SELECT id, film, to_delete FROM movie_house WHERE id = ' . $notification->getContent());
          $donnees = $reponse->fetch();
          $titreFilm = $donnees['film'];
          $toDelete  = $donnees['to_delete'];
          $reponse->closeCursor();

          if ($toDelete != 'Y')
          {
            $icone  = 'way_out';
            $phrase = 'Une sortie cinéma a été programmée <u>aujourd\'hui</u> pour le film <strong>' . $titreFilm . '</strong>.';
            $lien   = '/inside/portail/moviehouse/details.php?id_film=' . $notification->getContent() . '&action=goConsulter';
          }
          break;

        case 'comments':
          // Recherche du titre du film
          $reponse = $bdd->query('SELECT id, film FROM movie_house WHERE id = ' . $notification->getContent());
          $donnees = $reponse->fetch();
          $titreFilm = $donnees['film'];
          $reponse->closeCursor();

          $icone  = 'comments';
          $phrase = 'Des commentaires ont été publiés pour le film <strong>' . $titreFilm . '</strong>, n\'oubliez pas de les suivre dans la journée !';
          $lien   = '/inside/portail/moviehouse/details.php?id_film=' . $notification->getContent() . '&action=goConsulter&anchor=comments';
          break;

        case 'calendrier':
          // Recherche mois et année
          $reponse = $bdd->query('SELECT * FROM calendars WHERE id = ' . $notification->getContent());
          $donnees = $reponse->fetch();
          $mois     = formatMonthForDisplay($donnees['month']);
          $annee    = $donnees['year'];
          $toDelete = $donnees['to_delete'];
          $reponse->closeCursor();

          if ($toDelete != 'Y')
          {
            $icone = 'calendars';

            if (strtolower(substr($mois, 0, 1)) == 'a' OR strtolower(substr($mois, 0, 1)) == 'o')
              $phrase = 'Un calendrier vient d\'être mis en ligne pour le mois d\'<strong>' . $mois . ' ' . $annee . '</strong>.';
            else
              $phrase = 'Un calendrier vient d\'être mis en ligne pour le mois de <strong>' . $mois . ' ' . $annee . '</strong>.';

            $lien = '/inside/portail/calendars/calendars.php?year=' . $annee . '&action=goConsulter';
          }
          break;

        case 'annexe':
          // Recherche titre
          $reponse = $bdd->query('SELECT * FROM calendars_annexes WHERE id = ' . $notification->getContent());
          $donnees = $reponse->fetch();
          $titre    = $donnees['title'];
          $toDelete = $donnees['to_delete'];
          $reponse->closeCursor();

          if ($toDelete != 'Y')
          {
            $icone  = 'calendars';
            $phrase = 'Une annexe vient d\'être mise en ligne (<strong>' . $titre . '</strong>).';
            $lien   = '/inside/portail/calendars/calendars.php?action=goConsulterAnnexes';
          }
          break;

        case 'culte':
          // Recherche auteur et coupable ;)
          $reponse1 = $bdd->query('SELECT * FROM collector WHERE id = ' . $notification->getContent());
          $donnees1 = $reponse1->fetch();

            $reponse2 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $donnees1['author'] . '"');
            $donnees2 = $reponse2->fetch();

            if ($reponse2->rowCount() > 0)
              $author = htmlspecialchars($donnees2['pseudo']);
            else
              $author = formatUnknownUser('', false, true);

            $reponse2->closeCursor();

            // Si speaker autre que "Autre"
            if ($donnees1['type_speaker'] != 'other')
            {
              $reponse3 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $donnees1['speaker'] . '"');
              $donnees3 = $reponse3->fetch();

              if ($reponse3->rowCount() > 0)
                $speaker = htmlspecialchars($donnees3['pseudo']);
              else
                $speaker = formatUnknownUser('', false, true);

              $reponse3->closeCursor();
            }
            else
              $speaker = $donnees1['speaker'];

          $reponse1->closeCursor();

          // Recherche du numéro de page
          $numeroPage = numeroPageCollector($notification->getContent());

          $icone  = 'collector';
          $phrase = '<strong>' . $speaker . '</strong> en a encore dit une belle ! Merci <strong>' . $author . '</strong> &nbsp;<img src="../../includes/icons/common/smileys/2.png" alt="smiley_2" class="smiley" />';
          $lien   = '/inside/portail/collector/collector.php?action=goConsulter&page=' . $numeroPage . '&sort=dateDesc&filter=none&anchor=' . $notification->getContent();
          break;

        case 'culte_image':
          // Recherche auteur et coupable ;)
          $reponse1 = $bdd->query('SELECT * FROM collector WHERE id = ' . $notification->getContent());
          $donnees1 = $reponse1->fetch();

            $reponse2 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $donnees1['author'] . '"');
            $donnees2 = $reponse2->fetch();

            if ($reponse2->rowCount() > 0)
              $author = htmlspecialchars($donnees2['pseudo']);
            else
              $author = formatUnknownUser('', false, true);

            $reponse2->closeCursor();

            // Si speaker autre que "Autre"
            if ($donnees1['type_speaker'] != 'other')
            {
              $reponse3 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $donnees1['speaker'] . '"');
              $donnees3 = $reponse3->fetch();

              if ($reponse3->rowCount() > 0)
                $speaker = htmlspecialchars($donnees3['pseudo']);
              else
                $speaker = formatUnknownUser('', false, true);

              $reponse3->closeCursor();
            }
            else
              $speaker = $donnees1['speaker'];

          $reponse1->closeCursor();

          // Recherche du numéro de page
          $numeroPage = numeroPageCollector($notification->getContent());

          $icone  = 'collector';
          $phrase = 'Regarde ce qu\'a fait <strong>' . $speaker . '</strong> ! Merci <strong>' . $author . '</strong> pour ce moment &nbsp;<img src="../../includes/icons/common/smileys/1.png" alt="smiley_2" class="smiley" />';
          $lien   = '/inside/portail/collector/collector.php?action=goConsulter&page=' . $numeroPage . '&sort=dateDesc&filter=none&anchor=' . $notification->getContent();
          break;

        case 'depense':
          list($user1, $user2) = explode(';', $notification->getContent());

          // Recherche pseudo + généreux
          $reponse1 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $user1 . '"');
          $donnees1 = $reponse1->fetch();

          if ($reponse1->rowCount() > 0)
            $genereux = htmlspecialchars($donnees1['pseudo']);
          else
            $genereux = formatUnknownUser('', false, true);

          $reponse1->closeCursor();

          // Recherche pseudo + radin
          $reponse2 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $user2 . '"');
          $donnees2 = $reponse2->fetch();

          if ($reponse2->rowCount() > 0)
            $radin = htmlspecialchars($donnees2['pseudo']);
          else
            $radin = formatUnknownUser('', false, true);

          $reponse2->closeCursor();

          $icone  = 'expense_center';
          $phrase = 'La semaine dernière, <strong>' . $genereux . '</strong> a été le plus généreux, tandis que <strong>' . $radin . '</strong> a carrément été le plus radin...';
          $lien   = '';
          break;

        case 'inscrit':
          // Recherche pseudo
          $reponse = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $notification->getContent() . '"');
          $donnees = $reponse->fetch();

          if ($reponse->rowCount() > 0)
            $inscrit = htmlspecialchars($donnees['pseudo']);
          else
            $inscrit = formatUnknownUser('', false, true);

          $reponse->closeCursor();

          $icone  = 'inside';
          $phrase = '<strong>' . $inscrit . '</strong> vient de s\'inscrire, souhaitez-lui la bienvenue sur Inside !';
          $lien   = '';
          break;

        case 'idee':
          // Recherche idée
          $reponse1 = $bdd->query('SELECT id, subject, author, status FROM ideas WHERE id = "' . $notification->getContent() . '"');
          $donnees1 = $reponse1->fetch();

          $sujet = $donnees1['subject'];

          switch ($donnees1['status'])
          {
            // Ouverte
            case 'O':
            // Prise en charge
            case 'C':
            // En progrès
            case 'P':
              $view = 'inprogress';
              break;

            // Terminée
            case 'D':
            // Rejetée
            case 'R':
              $view = 'done';
              break;

            default:
              $view = 'all';
              break;
          }

          // Recherche pseudo
          $reponse2 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $donnees1['author'] . '"');
          $donnees2 = $reponse2->fetch();

          if ($reponse2->rowCount() > 0)
            $auteur = htmlspecialchars($donnees2['pseudo']);
          else
            $auteur = formatUnknownUser('', false, true);

          $reponse2->closeCursor();

          $reponse1->closeCursor();

          // Recherche du numéro de page
          $numeroPage = numPageIdea($notification->getContent(), $view);

          $icone  = 'ideas';
          $phrase = 'Une nouvelle idée <strong>' . $sujet . '</strong> vient tout juste d\'être publiée par <strong>' . $auteur . '</strong> !';
          $lien   = '/inside/portail/ideas/ideas.php?view=' . $view . '&page=' . $numeroPage . '&action=goConsulter&anchor=' . $notification->getContent();
          break;

        case 'start_mission':
          // Recherche données mission
          $reponse = $bdd->query('SELECT id, mission, date_deb, date_fin, heure FROM missions WHERE id = "' . $notification->getContent() . '"');
          $donnees = $reponse->fetch();

          $idMission = $donnees['id'];
          $mission   = $donnees['mission'];
          $dateDeb   = $donnees['date_deb'];
          $dateFin   = $donnees['date_fin'];
          $heureDeb  = $donnees['heure'];

          $reponse->closeCursor();

          $icone  = 'missions';
          $phrase = 'La mission <strong>' . $mission . '</strong> se lance à ' . formatTimeForDisplayLight($heureDeb) . ', n\'oubliez pas de participer tous les jours jusqu\'au <strong>' . formatDateForDisplay($dateFin) . '</strong>.';

          // Premier jour, avant l'heure
          if (date('Ymd') == $dateDeb AND date('His') < $heureDeb)
            $lien = '/inside/portail/missions/missions.php?action=goConsulter';
          // Premier jour, après l'heure
          elseif (date('Ymd') == $dateDeb AND date('His') >= $heureDeb)
            $lien = '/inside/portail/missions/details.php?id_mission=' . $idMission . '&action=goConsulter';
          // Autre jour
          else
            $lien = '/inside/portail/missions/details.php?id_mission=' . $idMission . '&action=goConsulter';

          break;

        case 'end_mission':
          // Recherche données mission
          $reponse = $bdd->query('SELECT id, mission FROM missions WHERE id = "' . $notification->getContent() . '"');
          $donnees = $reponse->fetch();

          $idMission = $donnees['id'];
          $mission   = $donnees['mission'];

          $reponse->closeCursor();

          $icone  = 'missions';
          $phrase = 'La mission <strong>' . $mission . '</strong> se termine aujourd\'hui ! Trouvez vite les derniers objectifs !';
          $lien   = '/inside/portail/missions/details.php?id_mission=' . $idMission . '&action=goConsulter';
          break;

        case 'one_mission':
          // Recherche données mission
          $reponse = $bdd->query('SELECT id, mission, date_deb, heure FROM missions WHERE id = "' . $notification->getContent() . '"');
          $donnees = $reponse->fetch();

          $idMission = $donnees['id'];
          $mission   = $donnees['mission'];
          $dateDeb   = $donnees['date_deb'];
          $heureDeb  = $donnees['heure'];

          $reponse->closeCursor();

          $icone  = 'missions';
          $phrase = 'La mission <strong>' . $mission . '</strong> se déroule aujourd\'hui uniquement à partir de ' . formatTimeForDisplayLight($heureDeb) . ' ! Trouvez vite les objectifs !';

          // Mission de 1 jour (avant l'heure)
          if (date('Ymd') <= $dateDeb AND date('His') < $heureDeb)
            $lien = '/inside/portail/missions/missions.php?action=goConsulter';
          // Mission de 1 jour (après l'heure)
          else
            $lien = '/inside/portail/missions/details.php?id_mission=' . $idMission . '&action=goConsulter';

          break;

        case 'recipe':
          // Recherche données recette
          $reponse = $bdd->query('SELECT id, year FROM cooking_box WHERE id = "' . $notification->getContent() . '"');
          $donnees = $reponse->fetch();

          $idRecette = $donnees['id'];
          $year      = $donnees['year'];

          $reponse->closeCursor();

          $icone  = 'cooking_box';
          $phrase = 'Une <strong>nouvelle recette</strong> vient d\'être ajoutée, allez vite la consulter !';
          $lien   = '/inside/portail/cookingbox/cookingbox.php?year=' . $year . '&action=goConsulter&anchor=' . $idRecette;
          break;

        case 'changelog':
          // Récupération données journal
          list($week, $year) = explode(';', $notification->getContent());

          $icone  = 'inside';
          $phrase = 'Un <strong>nouveau journal</strong> vient d\'être ajouté pour la <strong>semaine ' . formatWeekForDisplay($week) . '</strong> (' . $year . '), allez vite voir comment le site a évolué !';
          $lien   = '/inside/portail/changelog/changelog.php?year=' . $year . '&action=goConsulter&anchor=' . $week;
          break;

        default:
          $icone  = 'inside';
          $phrase = $notification->getContent();
          $lien   = '';
          break;
      }

      $notification->setIcon($icone);
      $notification->setSentence($phrase);
      $notification->setLink($lien);

      // Si jamais la notification n'est pas générée, on ne l'affiche pas (exemple : film à supprimer)
      if (empty($notification->getIcon()) AND empty($notification->getSentence()) AND empty($notification->getLink()))
        unset($notifications[$key]);
    }

    return $notifications;
  }

  // METIER : Récupère le numéro de page pour une notification Collector
  // RETOUR : Numéro de page
  function numeroPageCollector($id)
  {
    $numPage       = 0;
    $nombreParPage = 18;
    $position      = 1;

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

    $numPage = ceil($position / $nombreParPage);

    return $numPage;
  }

  // METIER : Récupère le numéro de page pour une notification #TheBox
  // RETOUR : Numéro de page
  function numPageIdea($id, $view)
  {
    $numPage       = 0;
    $nombreParPage = 18;
    $position      = 1;

    global $bdd;

    // On cherche la position de l'idée dans la table en fonction de la vue
    switch ($view)
    {
      case 'done':
        $reponse = $bdd->query('SELECT id, date
                                FROM ideas
                                WHERE status = "D" OR status = "R"
                                ORDER BY date DESC, id DESC'
                              );
        break;

      case 'inprogress':
        $reponse = $bdd->query('SELECT id, date
                                FROM ideas
                                WHERE status = "O" OR status = "C" OR status = "P"
                                ORDER BY date DESC, id DESC'
                              );
        break;

      case 'mine':
        $reponse = $bdd->query('SELECT id, date
                                FROM ideas
                                WHERE (status = "O" OR status = "C" OR status = "P") AND developper = "' . $_SESSION['user']['identifiant'] . '"
                                ORDER BY date DESC, id DESC'
                              );
        break;

      case 'all':
      default:
        $reponse = $bdd->query('SELECT id, date
                                FROM ideas
                                ORDER BY date DESC, id DESC'
                              );
        break;
    }

    while ($donnees = $reponse->fetch())
    {
      if ($id == $donnees['id'])
        break;
      else
        $position++;
    }
    $reponse->closeCursor();

    $numPage = ceil($position / $nombreParPage);

    return $numPage;
  }
?>
