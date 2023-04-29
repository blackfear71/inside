<?php
    include_once('../../includes/classes/calendars.php');
    include_once('../../includes/classes/collectors.php');
    include_once('../../includes/classes/gateaux.php');
    include_once('../../includes/classes/ideas.php');
    include_once('../../includes/classes/movies.php');
    include_once('../../includes/classes/notifications.php');
    include_once('../../includes/classes/parcours.php');

    // METIER : Récupération du détail des notifications
    // RETOUR : Tableau détails notifications
    function countNotifications($sessionUser)
    {
        // Récupération des données
        $equipe = $sessionUser['equipe'];

        // Récupération des notifications du jour
        $nombreNotificationsJour = physiqueNombreNotificationsDates($equipe, date('Ymd'), date('Ymd'));

        // Calcul des dates de la semaine
        $nombreJoursLundi    = 1 - date('N');
        $nombreJoursDimanche = 7 - date('N');
        $lundi               = date('Ymd', strtotime('+' . $nombreJoursLundi . ' days'));
        $aujourdhui          = date('Ymd', strtotime('+' . $nombreJoursDimanche . ' days'));

        // Récupération des notifications du jour
        $nombreNotificationsSemaine = physiqueNombreNotificationsDates($equipe, $lundi, $aujourdhui);

        // Concaténation des données
        $nombresNotifications = array(
            'nombreNotificationsJour'    => $nombreNotificationsJour,
            'nombreNotificationsSemaine' => $nombreNotificationsSemaine
        );

        // Retour
        return $nombresNotifications;
    }

    // METIER : Lecture nombre de pages en fonction de la vue
    // RETOUR : Nombre de pages
    function getPages($view, $sessionUser)
    {
        // Initialisations
        $nombreParPage = 20;

        // Récupération des données
        $identifiant = $sessionUser['identifiant'];
        $equipe      = $sessionUser['equipe'];

        // Calcul de la date du jour - 7 jours
        if ($view == 'week')
            $dateMoins7 = date('Ymd', strtotime(date('Ymd') . ' - 7 days'));
        else
            $dateMoins7 = '';

        // Lecture du nombre total de notifications en fonction de la vue
        $nombreNotifications = physiqueNombreNotifications($view, $identifiant, $equipe, $dateMoins7);

        // Calcul du nombre de pages
        $nombrePages = ceil($nombreNotifications / $nombreParPage);

        // Retour
        return $nombrePages;
    }

    // METIER : Lecture des notifications en fonction de la vue
    // RETOUR : Liste des notifications
    function getNotifications($view, $sessionUser, $nombrePages, $page)
    {
        // Récupération des données
        $identifiant = $sessionUser['identifiant'];
        $equipe      = $sessionUser['equipe'];

        // Détermination des critères de recherche
        if ($view != 'today')
        {
            $nombreParPage = 20;

            // Vérification dernière page
            if ($page > $nombrePages)
                $page = $nombrePages;

            // Calcul première entrée
            $premiereEntree = ($page - 1) * $nombreParPage;

            // Calcul de la date du jour - 7 jours
            if ($view == 'week')
                $dateMoins7 = date('Ymd', strtotime(date('Ymd') . ' - 7 days'));
            else
                $dateMoins7 = '';
        }
        else
        {
            $nombreParPage  = '';
            $premiereEntree = '';
            $dateMoins7     = '';
        }

        // Récupération de la liste des notifications
        $listeNotifications = physiqueNotifications($view, $identifiant, $equipe, $dateMoins7, $premiereEntree, $nombreParPage);

        // Retour
        return $listeNotifications;
    }

    // METIER : Lecture des utilisateurs
    // RETOUR : Liste des utilisateurs
    function getUsers($equipe)
    {
        // Lecture des utilisateurs
        $listeUsers = physiqueUsers($equipe);

        // Retour
        return $listeUsers;
    }

    // METIER : Formatage des notifications (icône, phrase & lien)
    // RETOUR : Notifications formatées
    function formatNotifications($notifications, $listeUsers, $sessionUser)
    {
        // Récupération des données
        $identifiant = $sessionUser['identifiant'];
        $equipe      = $sessionUser['equipe'];

        // Traitement de toutes les catégories de notifications
        foreach ($notifications as $key => $notification)
        {
            // Initialisations
            $icone  = '';
            $phrase = '';
            $lien   = '';

            // Recherche des icônes, phrases et liens en fonction de la catégorie
            switch ($notification->getCategory())
            {
                case 'film':
                    // Lecture des données du film
                    $film = physiqueFilm($notification->getContent());

                    // Formatage de la notification
                    if ($film->getTo_delete() != 'Y')
                    {
                        $icone  = 'movie_house';
                        $phrase = 'Le film <strong>' . htmlspecialchars($film->getFilm()) . '</strong> vient d\'être ajouté ! Allez vite le voir &nbsp;<img src="../../includes/icons/common/smileys/1.png" alt="smiley_1" class="smiley" />';
                        $lien   = '/inside/portail/moviehouse/details.php?id_film=' . $film->getId() . '&action=goConsulter';
                    }
                    break;

                case 'doodle':
                    // Lecture des données du film
                    $film = physiqueFilm($notification->getContent());

                    // Formatage de la notification
                    if ($film->getTo_delete() != 'Y')
                    {
                        $icone  = 'doodle';
                        $phrase = 'Un Doodle vient d\'être mis en place pour le film <strong>' . htmlspecialchars($film->getFilm()) . '</strong>. N\'oubliez pas d\'y répondre si vous êtes intéressé(e) !';
                        $lien   = $film->getDoodle();
                    }
                    break;

                case 'cinema':
                    // Lecture des données du film
                    $film = physiqueFilm($notification->getContent());

                    // Formatage de la notification
                    if ($film->getTo_delete() != 'Y')
                    {
                        $icone  = 'way_out';
                        $phrase = 'Une sortie cinéma a été programmée <u>aujourd\'hui</u> pour le film <strong>' . htmlspecialchars($film->getFilm()) . '</strong>.';
                        $lien   = '/inside/portail/moviehouse/details.php?id_film=' . $film->getId() . '&action=goConsulter';
                    }
                    break;

                case 'comments':
                    // Lecture des données du film
                    $film = physiqueFilm($notification->getContent());

                    // Formatage de la notification
                    if ($film->getTo_delete() != 'Y')
                    {
                        $icone  = 'comments';
                        $phrase = 'Des commentaires ont été publiés pour le film <strong>' . htmlspecialchars($film->getFilm()) . '</strong>, n\'oubliez pas de les suivre dans la journée !';
                        $lien   = '/inside/portail/moviehouse/details.php?id_film=' . $film->getId() . '&action=goConsulter&anchor=comments';
                    }
                    break;

                case 'calendrier':
                    // Lecture des données du calendrier
                    $calendrier = physiqueCalendrier($notification->getContent());

                    // Formatage de la notification
                    if ($calendrier->getTo_delete() != 'Y')
                    {
                        $mois  = formatMonthForDisplay($calendrier->getMonth());
                        $annee = $calendrier->getYear();
                        $icone = 'calendars';

                        if (strtolower(substr($mois, 0, 1)) == 'a' OR strtolower(substr($mois, 0, 1)) == 'o')
                            $phrase = 'Un calendrier vient d\'être mis en ligne pour le mois d\'<strong>' . $mois . ' ' . $annee . '</strong>.';
                        else
                            $phrase = 'Un calendrier vient d\'être mis en ligne pour le mois de <strong>' . $mois . ' ' . $annee . '</strong>.';

                        $lien = '/inside/portail/calendars/calendars.php?year=' . $annee . '&action=goConsulter';
                    }
                    break;

                case 'annexe':
                    // Lecture des données de l'annexe
                    $annexe = physiqueAnnexe($notification->getContent());

                    // Formatage de la notification
                    if ($annexe->getTo_delete() != 'Y')
                    {
                        $icone  = 'calendars';
                        $phrase = 'Une annexe vient d\'être mise en ligne (<strong>' . htmlspecialchars($annexe->getTitle()) . '</strong>).';
                        $lien   = '/inside/portail/calendars/calendars.php?action=goConsulterAnnexes';
                    }
                    break;

                case 'culte':
                    // Lecture des données de la phrase culte
                    $collector = physiqueCollector($notification->getContent());

                    // Récupération pseudo auteur
                    if (isset($listeUsers[$collector->getAuthor()]) AND !empty($listeUsers[$collector->getAuthor()]))
                        $author = htmlspecialchars($listeUsers[$collector->getAuthor()]);
                    else
                        $author = formatUnknownUser('', false, true);

                    // Récupération pseudo speaker si différent de "Autre"
                    if ($collector->getType_speaker() != 'other')
                    {
                        if (isset($listeUsers[$collector->getSpeaker()]) AND !empty($listeUsers[$collector->getSpeaker()]))
                            $speaker = htmlspecialchars($listeUsers[$collector->getSpeaker()]);
                        else
                            $speaker = formatUnknownUser('', false, true);
                    }
                    else
                        $speaker = htmlspecialchars($collector->getSpeaker());

                    // Recherche du numéro de page pour redirection
                    $numeroPage = getNumeroPageCollector($notification->getContent(), $equipe);

                    // Formatage de la notification
                    $icone  = 'collector';
                    $phrase = '<strong>' . $speaker . '</strong> en a encore dit une belle ! Merci <strong>' . $author . '</strong> &nbsp;<img src="../../includes/icons/common/smileys/2.png" alt="smiley_2" class="smiley" />';
                    $lien   = '/inside/portail/collector/collector.php?sort=dateDesc&filter=none&action=goConsulter&page=' . $numeroPage . '&anchor=' . $notification->getContent();
                    break;

                case 'culte_image':
                    // Lecture des données de la phrase culte
                    $collector = physiqueCollector($notification->getContent());

                    // Récupération pseudo auteur
                    if (isset($listeUsers[$collector->getAuthor()]) AND !empty($listeUsers[$collector->getAuthor()]))
                        $author = htmlspecialchars($listeUsers[$collector->getAuthor()]);
                    else
                        $author = formatUnknownUser('', false, true);

                    // Récupération pseudo speaker si différent de "Autre"
                    if ($collector->getType_speaker() != 'other')
                    {
                        if (isset($listeUsers[$collector->getSpeaker()]) AND !empty($listeUsers[$collector->getSpeaker()]))
                            $speaker = htmlspecialchars($listeUsers[$collector->getSpeaker()]);
                        else
                            $speaker = formatUnknownUser('', false, true);
                    }
                    else
                        $speaker = htmlspecialchars($collector->getSpeaker());

                    // Recherche du numéro de page pour redirection
                    $numeroPage = getNumeroPageCollector($collector->getId(), $equipe);

                    // Formatage de la notification
                    $icone  = 'collector';
                    $phrase = 'Regarde ce qu\'a fait <strong>' . $speaker . '</strong> ! Merci <strong>' . $author . '</strong> pour ce moment &nbsp;<img src="../../includes/icons/common/smileys/1.png" alt="smiley_2" class="smiley" />';
                    $lien   = '/inside/portail/collector/collector.php?sort=dateDesc&filter=none&action=goConsulter&page=' . $numeroPage . '&anchor=' . $collector->getId();
                    break;

                case 'depense':
                    // Extraction des identifiants
                    list($identifiant_1, $identifiant_2) = explode(';', $notification->getContent());

                    // Récupération pseudo du plus généreux
                    if (isset($listeUsers[$identifiant_1]) AND !empty($listeUsers[$identifiant_1]))
                        $genereux = htmlspecialchars($listeUsers[$identifiant_1]);
                    else
                        $genereux = formatUnknownUser('', false, true);

                    // Récupération pseudo du plus radin
                    if (isset($listeUsers[$identifiant_2]) AND !empty($listeUsers[$identifiant_2]))
                        $radin = htmlspecialchars($listeUsers[$identifiant_2]);
                    else
                        $radin = formatUnknownUser('', false, true);

                    // Formatage de la notification
                    $icone  = 'expense_center';
                    $phrase = 'La semaine dernière, <strong>' . $genereux . '</strong> a été le plus généreux, tandis que <strong>' . $radin . '</strong> a carrément été le plus radin...';
                    $lien   = '';
                    break;

                case 'inscrit':
                    // Récupération pseudo nouvel inscrit
                    if (isset($listeUsers[$notification->getContent()]) AND !empty($listeUsers[$notification->getContent()]))
                        $inscrit = htmlspecialchars($listeUsers[$notification->getContent()]);
                    else
                        $inscrit = formatUnknownUser('', false, true);

                    // Formatage de la notification
                    $icone  = 'inside';
                    $phrase = '<strong>' . $inscrit . '</strong> vient de s\'inscrire, souhaitez-lui la bienvenue sur Inside !';
                    $lien   = '';
                    break;

                case 'idee':
                    // Lecture des données de l'idée
                    $idee = physiqueIdee($notification->getContent());

                    // Récupération de la vue
                    switch ($idee->getStatus())
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

                    // Récupération pseudo
                    if (isset($listeUsers[$idee->getAuthor()]) AND !empty($listeUsers[$idee->getAuthor()]))
                        $auteur = htmlspecialchars($listeUsers[$idee->getAuthor()]);
                    else
                        $auteur = formatUnknownUser('', false, true);

                    // Recherche du numéro de page pour redirection
                    $numeroPage = getNumeroPageIdee($idee->getId(), $view, $equipe, $identifiant);

                    // Formatage de la notification
                    $icone  = 'ideas';
                    $phrase = 'Une nouvelle idée <strong>' . htmlspecialchars($idee->getSubject()) . '</strong> vient tout juste d\'être publiée par <strong>' . $auteur . '</strong> !';
                    $lien   = '/inside/portail/ideas/ideas.php?view=' . $view . '&page=' . $numeroPage . '&action=goConsulter&anchor=' . $idee->getId();
                    break;

                case 'start_mission':
                    // Lecture des données de la mission
                    $mission = physiqueMission($notification->getContent());

                    // Formatage de la notification
                    $icone  = 'missions';
                    $phrase = 'La mission <strong>' . htmlspecialchars($mission->getMission()) . '</strong> se lance à ' . formatTimeForDisplayLight($mission->getHeure()) . ', n\'oubliez pas de participer tous les jours jusqu\'au <strong>' . formatDateForDisplay($mission->getDate_fin()) . '</strong>.';

                    // Premier jour, avant l'heure
                    if (date('Ymd') == $mission->getDate_deb() AND date('His') < $mission->getHeure())
                        $lien = '/inside/portail/missions/missions.php?action=goConsulter';
                    // Premier jour, après l'heure
                    elseif (date('Ymd') == $mission->getDate_deb() AND date('His') >= $mission->getHeure())
                        $lien = '/inside/portail/missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter';
                    // Autre jour
                    else
                        $lien = '/inside/portail/missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter';
                    break;

                case 'end_mission':
                    // Lecture des données de la mission
                    $mission = physiqueMission($notification->getContent());

                    // Formatage de la notification
                    $icone  = 'missions';
                    $phrase = 'La mission <strong>' . htmlspecialchars($mission->getMission()) . '</strong> se termine aujourd\'hui ! Trouvez vite les derniers objectifs !';
                    $lien   = '/inside/portail/missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter';
                    break;

                case 'one_mission':
                    // Lecture des données de la mission
                    $mission = physiqueMission($notification->getContent());

                    // Formatage de la notification
                    $icone  = 'missions';
                    $phrase = 'La mission <strong>' . htmlspecialchars($mission->getMission()) . '</strong> se déroule aujourd\'hui uniquement à partir de ' . formatTimeForDisplayLight($mission->getHeure()) . ' ! Trouvez vite les objectifs !';

                    // Mission de 1 jour (avant l'heure)
                    if (date('Ymd') <= $mission->getDate_deb() AND date('His') < $mission->getHeure())
                        $lien = '/inside/portail/missions/missions.php?action=goConsulter';
                    // Mission de 1 jour (après l'heure)
                    else
                        $lien = '/inside/portail/missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter';
                    break;

                case 'recipe':
                    // Lecture des données de la recette
                    $recette = physiqueRecette($notification->getContent());

                    // Formatage de la notification
                    $icone  = 'cooking_box';
                    $phrase = 'Une <strong>nouvelle recette</strong> vient d\'être ajoutée, allez vite la consulter !';
                    $lien   = '/inside/portail/cookingbox/cookingbox.php?year=' . $recette->getYear() . '&action=goConsulter&anchor=' . $recette->getId();
                    break;

                case 'parcours':
                    // Lecture des données du parcours
                    $parcours = physiqueParcours($notification->getContent());

                    // Formatage de la notification
                    $icone  = 'petits_pedestres';
                    $phrase = 'Un <strong>nouveau parcours</strong> vient d\'être ajouté, à vos baskets !';
                    $lien   = '/inside/portail/petitspedestres/details.php?id_parcours=' . $parcours->getId() . '&action=goConsulter';
                    break;

                case 'changelog':
                    // Extraction des données du journal
                    list($semaine, $annee) = explode(';', $notification->getContent());

                    // Formatage de la notification
                    $icone  = 'inside';
                    $phrase = 'Un <strong>nouveau journal</strong> vient d\'être ajouté pour la <strong>semaine ' . formatWeekForDisplay($semaine) . '</strong> (' . $annee . '), allez vite voir comment le site a évolué !';
                    $lien   = '/inside/portail/changelog/changelog.php?year=' . $annee . '&action=goConsulter&anchor=' . $semaine;
                    break;

                default:
                    // Formatage de la notification par défaut
                    $icone  = 'inside';
                    $phrase = $notification->getContent();
                    $lien   = '';
                    break;
            }

            // Ajout des données complémentaires
            $notification->setIcon($icone);
            $notification->setSentence($phrase);
            $notification->setLink($lien);

            // Si la notification n'est pas générée, on ne l'affiche pas (exemple : film à supprimer)
            if (empty($notification->getIcon()) AND empty($notification->getSentence()) AND empty($notification->getLink()))
                unset($notifications[$key]);
        }

        // Retour
        return $notifications;
    }

    // METIER : Récupère le numéro de page pour une notification Collector
    // RETOUR : Numéro de page
    function getNumeroPageCollector($idCollector, $equipe)
    {
        // Initialisations
        $nombreParPage = 18;

        // Recherche de la position de la phrase culte dans la table
        $positionCollector = physiquePositionCollector($idCollector, $equipe);

        // Calcul du numéro de page
        $numeroPage = ceil($positionCollector / $nombreParPage);

        // Retour
        return $numeroPage;
    }

    // METIER : Récupère le numéro de page pour une notification #TheBox
    // RETOUR : Numéro de page
    function getNumeroPageIdee($idIdee, $view, $equipe, $identifiant)
    {
        // Initialisations
        $nombreParPage = 18;

        // Recherche de la position de l'idée dans la table en fonction de la vue
        $positionIdee = physiquePositionIdee($view, $idIdee, $equipe, $identifiant);

        // Calcul du numéro de page de l'idée
        $numeroPage = ceil($positionIdee / $nombreParPage);

        // Retour
        return $numeroPage;
    }
?>