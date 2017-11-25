<?php
  include_once('../../includes/appel_bdd.php');
  include_once('../../includes/classes/notifications.php');

  // METIER : Lecture nombre de pages pour la section "Toutes" des notifications
  // RETOUR : Nombre de pages
  function getPages($view, $user)
  {
    $nb_pages    = 0;
    $nb_notif    = 0;
    $nb_par_page = 20;

    global $bdd;

    if ($view == "me")
      $req = $bdd->query('SELECT COUNT(id) AS nb_notif FROM notifications WHERE author = "' . $user . '" OR category = "' . $user . '"');
    elseif ($view == "week")
    {
      $date_moins_7 = date("Ymd") - 7;
      $req = $bdd->query('SELECT COUNT(id) AS nb_notif FROM notifications WHERE date <= ' . date("Ymd") . ' AND date > ' . $date_moins_7);
    }
    else
      $req = $bdd->query('SELECT COUNT(id) AS nb_notif FROM notifications');


    $data = $req->fetch();

    $nb_notif = $data['nb_notif'];

    $req->closeCursor();

    $nb_pages = ceil($nb_notif / $nb_par_page);

    return $nb_pages;
  }

  // METIER : Lecture des notifications en fonction de la vue
  // RETOUR : Liste des notifications
  function getNotifications($view, $user, $nb_pages, $page)
  {
    $notifs = array();

    global $bdd;

    switch ($view)
    {
      case "me":
        // Pagination
        $nb_par_page = 20;

        // Contrôle dernière page
        if ($page > $nb_pages)
          $page = $nb_pages;

        // Calcul première entrée
        $premiere_entree = ($page - 1) * $nb_par_page;

        $reponse = $bdd->query('SELECT * FROM notifications WHERE author = "' . $user . '" OR category = "' . $user . '" ORDER BY date DESC, time DESC, id DESC LIMIT ' . $premiere_entree . ', ' . $nb_par_page);
        break;

      case "today":
        $reponse = $bdd->query('SELECT * FROM notifications WHERE date = ' . date("Ymd") . ' ORDER BY time DESC, id DESC');
        break;

      case "week":
        // Pagination
        $nb_par_page = 20;

        // Contrôle dernière page
        if ($page > $nb_pages)
          $page = $nb_pages;

        // Calcul première entrée
        $premiere_entree = ($page - 1) * $nb_par_page;

        $date_moins_7 = date("Ymd") - 7;
        $reponse = $bdd->query('SELECT * FROM notifications WHERE date <= ' . date("Ymd") . ' AND date > ' . $date_moins_7 . ' ORDER BY date DESC, id DESC LIMIT ' . $premiere_entree . ', ' . $nb_par_page);
        break;

      case "all":
      default:
        // Pagination
        $nb_par_page = 20;

        // Contrôle dernière page
        if ($page > $nb_pages)
          $page = $nb_pages;

        // Calcul première entrée
        $premiere_entree = ($page - 1) * $nb_par_page;

        $reponse = $bdd->query('SELECT * FROM notifications ORDER BY date DESC, time DESC, id DESC LIMIT ' . $premiere_entree . ', ' . $nb_par_page);
        break;
    }

    while($donnees = $reponse->fetch())
    {
      // Instanciation d'un objet Notification à partir des données remontées de la bdd
      $myNotif = Notification::withData($donnees);
      array_push($notifs, $myNotif);
    }

    $reponse->closeCursor();

    return $notifs;
  }

  // METIER : Formatage des notifications (icône, phrase & lien)
  // RETOUR : Notifications formatées
  function formatNotifications($notifications)
  {
    global $bdd;

    foreach ($notifications as $notification)
    {
      $icone  = "";
      $phrase = "";
      $lien   = "";

      // Paramétrage des icônes, phrases et liens en fonction de la catégorie
      switch ($notification->getCategory())
      {
        case "film":
          // Recherche du titre du film
          $reponse = $bdd->query('SELECT id, film FROM movie_house WHERE id = ' . $notification->getContent());
          $donnees = $reponse->fetch();
          $titre_film = $donnees['film'];
          $reponse->closeCursor();

          $icone  = "movie_house";
          $phrase = "Le film <strong>" . $titre_film . "</strong> vient d'être ajouté ! Allez vite le voir &nbsp;<img src='../../includes/icons/smileys/1.png' alt='smiley_1' class='smiley' />";
          $lien   = "/inside/portail/moviehouse/details.php?id_film=" . $notification->getContent() . "&action=goConsulter";
          break;

        case "doodle":
          // Recherche du titre du film et du Doodle
          $reponse = $bdd->query('SELECT id, film, doodle FROM movie_house WHERE id = ' . $notification->getContent());
          $donnees = $reponse->fetch();
          $titre_film = $donnees['film'];
          $doodle     = $donnees['doodle'];
          $reponse->closeCursor();

          $icone  = "doodle";
          $phrase = "Un Doodle vient d'être mis en place pour le film <strong>" . $titre_film . "</strong>. N'oubliez pas d'y répondre si vous êtes intéressé(e) !";
          $lien   = $doodle;
          break;

        case "cinema":
          // Recherche du titre du film
          $reponse = $bdd->query('SELECT id, film FROM movie_house WHERE id = ' . $notification->getContent());
          $donnees = $reponse->fetch();
          $titre_film = $donnees['film'];
          $reponse->closeCursor();

          $icone  = "way_out";
          $phrase = "Une sortie cinéma a été programmée <u>aujourd'hui</u> pour le film <strong>" . $titre_film . "</strong>.";
          $lien   = "/inside/portail/moviehouse/details.php?id_film=" . $notification->getContent() . "&action=goConsulter";
          break;

        case 'comments':
          // Recherche du titre du film
          $reponse = $bdd->query('SELECT id, film FROM movie_house WHERE id = ' . $notification->getContent());
          $donnees = $reponse->fetch();
          $titre_film = $donnees['film'];
          $reponse->closeCursor();

          $icone  = "comments";
          $phrase = "Des commentaires ont été publiés pour le film <strong>" . $titre_film . "</strong>, n'oubliez pas de les suivre dans la journée !";
          $lien   = "/inside/portail/moviehouse/details.php?id_film=" . $notification->getContent() . "&action=goConsulter#comments";
          break;

        case "mail_1":
          $icone  = "mailing";
          $phrase = "";
          $lien   = "";
          break;

        case "mail_2":
          $icone  = "mailing";
          $phrase = "";
          $lien   = "";
          break;

        case "mail_3":
          $icone  = "mailing";
          $phrase = "";
          $lien   = "";
          break;

        case "calendrier":
          // Recherche mois et année
          $reponse = $bdd->query('SELECT * FROM calendars WHERE id = ' . $notification->getContent());
          $donnees = $reponse->fetch();
          $mois  = formatMonthForDisplay($donnees['month']);
          $annee = $donnees['year'];
          $reponse->closeCursor();

          $icone  = "calendars";
          $phrase = "Un calendrier vient d'être mis en ligne pour le mois de <strong>" . $mois . " " . $annee . "</strong>.";
          $lien   = "/inside/portail/calendars/calendars.php?year=" . $annee . "&action=goConsulter";
          break;

        case "culte":
          // Recherche auteur et coupable ;)
          $reponse1 = $bdd->query('SELECT * FROM collector WHERE id = ' . $notification->getContent());
          $donnees1 = $reponse1->fetch();

            $reponse2 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $donnees1['author'] . '"');
            $donnees2 = $reponse2->fetch();
            if ($reponse2->rowCount() > 0)
              $author = $donnees2['pseudo'];
            else
              $author = '<i>un ancien utilisateur</i>';
            $reponse2->closeCursor();

            // Si speaker autre que "Autre"
            if ($donnees1['type_speaker'] != "other")
            {
              $reponse3 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $donnees1['speaker'] . '"');
              $donnees3 = $reponse3->fetch();
              if ($reponse3->rowCount() > 0)
                $speaker = $donnees3['pseudo'];
              else
                $speaker = '<i>un ancien utilisateur</i>';
              $reponse3->closeCursor();
            }
            else
              $speaker = $donnees1['speaker'];

          $reponse1->closeCursor();

          $icone  = "collector";
          $phrase = "<strong>" . $speaker . "</strong> en a encore dit une belle ! Merci <strong>" . $author . "</strong> &nbsp;<img src='../../includes/icons/smileys/2.png' alt='smiley_2' class='smiley' />";
          $lien   = "/inside/portail/collector/collector.php?action=goConsulter&page=1";
          break;

        case "depense":
          list($user1, $user2) = explode(";", $notification->getContent());

          // Recherche pseudo + généreux
          $reponse1 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $user1 . '"');
          $donnees1 = $reponse1->fetch();
          if ($reponse1->rowCount() > 0)
            $genereux = $donnees1['pseudo'];
          else
            $genereux = '<i>un ancien utilisateur</i>';
          $reponse1->closeCursor();

          // Recherche pseudo + radin
          $reponse2 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $user2 . '"');
          $donnees2 = $reponse2->fetch();
          if ($reponse2->rowCount() > 0)
            $radin = $donnees2['pseudo'];
          else
            $radin = '<i>un ancien utilisateur</i>';
          $reponse2->closeCursor();

          $icone  = "expense_center";
          $phrase = "La semaine dernière, <strong>" . $genereux . "</strong> a été le plus généreux, tandis que <strong>" . $radin . "</strong> a carrément été le plus radin...";
          $lien   = "";
          break;

        case "inscrit":
          // Recherche pseudo
          $reponse = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $notification->getContent() . '"');
          $donnees = $reponse->fetch();
          if ($reponse->rowCount() > 0)
            $inscrit = $donnees['pseudo'];
          else
            $inscrit = '<i>un ancien utilisateur</i>';
          $reponse->closeCursor();

          $icone  = "inside";
          $phrase = "<strong>" . $inscrit . "</strong> vient de s'inscrire, souhaitez-lui la bienvenue sur Inside !";
          $lien   = "";
          break;

        case "idee":
          // Recherche idée
          $reponse1 = $bdd->query('SELECT id, subject, author, status FROM ideas WHERE id = "' . $notification->getContent() . '"');
          $donnees1 = $reponse1->fetch();

            $sujet = $donnees1['subject'];
            switch ($donnees1['status'])
            {
              // Ouverte
              case "O":
              // Prise en charge
              case "C":
              // En progrès
              case "P":
                $view = 'inprogress';
                break;

              // Terminée
              case "D":
              // Rejetée
              case "R":
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
              $auteur = $donnees2['pseudo'];
            else
              $auteur = '<i>un ancien utilisateur</i>';
            $reponse2->closeCursor();

          $reponse1->closeCursor();

          $icone  = "ideas";
          $phrase = "Une nouvelle idée <strong>" . $sujet . "</strong> vient tout juste d'être publiée par <strong>" . $auteur . "</strong> !";
          $lien   = "/inside/portail/ideas/ideas.php?view=" . $view . "&action=goConsulter";
          break;

        case "succes":
          list($user, $success) = explode(";", $notification->getContent());

          // Recherche pseudo
          $reponse1 = $bdd->query('SELECT id, identifiant, pseudo FROM users WHERE identifiant = "' . $user . '"');
          $donnees1 = $reponse1->fetch();
          if ($reponse1->rowCount() > 0)
            $pseudo = $donnees1['pseudo'];
          else
            $pseudo = '<i>un ancien utilisateur</i>';
          $reponse1->closeCursor();

          // Recherche succès
          $reponse2 = $bdd->query('SELECT id, level, title FROM success WHERE id = ' . $success);
          $donnees2 = $reponse2->fetch();
          $succes = $donnees2['title'];
          $level  = $donnees2['level'];
          $reponse2->closeCursor();

          $icone  = "success";
          switch ($level)
          {
            case "3":
              $phrase = "Une vie normale ne suffit plus pour <strong>" . $pseudo . "</strong>. Bien joué pour <strong>" . $succes . "</strong>.";
              break;

            case "2":
              $phrase = "Mais qui va bien pouvoir détrôner <strong>" . $pseudo . "</strong> sur <strong>" . $succes . "</strong> ?";
              break;

            case "1":
            default:
              $phrase = "<strong>" . $pseudo . "</strong> se la pète un max avec son succès <strong>" . $succes . "</strong> ! Tu as trop le seum ma parole !";
              break;
          }
          $lien   = "";
          break;

        case "start_mission":
          // Recherche données mission
          $reponse = $bdd->query('SELECT id, mission, date_fin FROM missions WHERE id = "' . $notification->getContent() . '"');
          $donnees = $reponse->fetch();

          $id_mission = $donnees['id'];
          $mission    = $donnees['mission'];
          $date_fin   = $donnees['date_fin'];

          $reponse->closeCursor();

          $icone  = "missions";
          $phrase = "La mission <strong>" . $mission . "</strong> est lancée, n'oubliez pas de participer tous les jours jusqu'au <strong>" . formatDateForDisplay($date_fin) . "</strong>.";
          $lien   = "/inside/portail/missions/details.php?id_mission=" . $id_mission . "&action=goConsulter";
          break;

        case "end_mission":
          // Recherche données mission
          $reponse = $bdd->query('SELECT id, mission FROM missions WHERE id = "' . $notification->getContent() . '"');
          $donnees = $reponse->fetch();

          $id_mission = $donnees['id'];
          $mission    = $donnees['mission'];

          $reponse->closeCursor();

          $icone  = "missions";
          $phrase = "La mission <strong>" . $mission . "</strong> se termine aujourd'hui ! Trouvez vite les derniers objectifs !";
          $lien   = "/inside/portail/missions/details.php?id_mission=" . $id_mission . "&action=goConsulter";
          break;

        case "one_mission":
          // Recherche données mission
          $reponse = $bdd->query('SELECT id, mission FROM missions WHERE id = "' . $notification->getContent() . '"');
          $donnees = $reponse->fetch();

          $id_mission = $donnees['id'];
          $mission    = $donnees['mission'];

          $reponse->closeCursor();

          $icone  = "missions";
          $phrase = "La mission <strong>" . $mission . "</strong> se déroule aujourd'hui uniquement ! Trouvez vite les objectifs !";
          $lien   = "/inside/portail/missions/details.php?id_mission=" . $id_mission . "&action=goConsulter";
          break;

        default:
          $icone  = "inside";
          $phrase = $notification->getContent();
          $lien   = "";
          break;
      }

      $notification->setIcon($icone);
      $notification->setSentence($phrase);
      $notification->setLink($lien);
    }

    return $notifications;
  }
?>
