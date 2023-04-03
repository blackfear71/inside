<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/classes/alerts.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/classes/missions.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/classes/success.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/classes/teams.php');
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
        {
            header('location: /inside/index.php?action=goConsulter');
            exit;
        }

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
        {
            header('location: /inside/index.php?action=goConsulter');
            exit;
        }
        else
        {
            // Contrôle utilisateur inscrit
            $isUserInscrit = isUserInscrit($_SESSION['user']['identifiant']);

            // Déconnexion si non inscrit
            if ($isUserInscrit == false)
                header('location: /inside/includes/functions/script_commun.php?function=disconnectUser');
            else
            {
                // Contrôle page accessible mobile
                $isAccessibleMobile = isAccessibleMobile($_SERVER['PHP_SELF']);

                // Redirection si non accessible
                if ($isAccessibleMobile == false)
                    header('location: /inside/portail/portail/portail.php?action=goConsulter');
                else
                {
                    // Contrôle changement d'équipe
                    getEquipeUser($_SESSION['user']);

                    // Récupération expérience
                    getExperience($_SESSION['user']['identifiant']);

                    // Récupération des missions
                    generateMissions();

                    // Détermination du thème
                    getTheme($_SESSION['user']['identifiant']);
                }
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
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

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

    // METIER : Contrôle si l'utilisateur est bien inscrit
    // RETOUR : Booléen
    function isUserInscrit($identifiant)
    {
        // Vérification utilisateur inscrit
        $isUserInscrit = physiqueUserInscrit($identifiant);

        // Retour
        return $isUserInscrit;
    }

    // METIER : Contrôle si la page courante est accessible sur mobile
    // RETOUR : Booléen
    function isAccessibleMobile($path)
    {
        // Initialisations
        $isAccessibleMobile = true;

        // Vérification section accessible sur mobile
        if ($_SESSION['index']['plateforme'] == 'mobile')
        {
            if ($path != '/inside/portail/bugs/bugs.php'
            AND $path != '/inside/portail/calendars/calendars.php'
            AND $path != '/inside/portail/calendars/calendars_generator.php'
            AND $path != '/inside/portail/changelog/changelog.php'
            AND $path != '/inside/portail/collector/collector.php'
            AND $path != '/inside/portail/cookingbox/cookingbox.php'
            AND $path != '/inside/portail/expensecenter/expensecenter.php'
            AND $path != '/inside/portail/foodadvisor/foodadvisor.php'
            AND $path != '/inside/portail/foodadvisor/restaurants.php'
            AND $path != '/inside/portail/ideas/ideas.php'
            AND $path != '/inside/portail/missions/missions.php'
            AND $path != '/inside/portail/missions/details.php'
            AND $path != '/inside/portail/moviehouse/details.php'
            AND $path != '/inside/portail/moviehouse/mailing.php'
            AND $path != '/inside/portail/moviehouse/moviehouse.php'
            AND $path != '/inside/portail/notifications/notifications.php'
            AND $path != '/inside/portail/petitspedestres/details.php'
            AND $path != '/inside/portail/petitspedestres/petitspedestres.php'
            AND $path != '/inside/portail/portail/portail.php'
            AND $path != '/inside/portail/search/search.php'
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
                        $ligneMessage = array(
                            'logo'  => $messageAlerte->getType(),
                            'texte' => $messageAlerte->getMessage()
                        );
                    }
                    else
                    {
                        $ligneMessage = array(
                            'logo'  => 'question',
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

    // METIER : Récupération de l'équipe d'un utilisateur si besoin
    // RETOUR : Aucun
    function getEquipeUser($sessionUser)
    {
        // Récupération des données
        $identifiant    = $sessionUser['identifiant'];
        $equipeCourante = $sessionUser['equipe'];

        // Lecture des données utilisateur
        $equipeUser = physiqueEquipeUser($identifiant);

        // Si l'équipe n'est pas à jour, on va récupérer la nouvelle pour la remplacer
        if ($equipeUser != $equipeCourante)
        {
            // Lecture des données de l'équipe
            $equipe = physiqueDonneesEquipe($equipeUser);

            // Mise en session des données
            $_SESSION['user']['equipe']         = $equipe->getReference();
            $_SESSION['user']['libelle_equipe'] = $equipe->getTeam();
        }
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
        $_SESSION['user']['experience'] = array(
            'niveau'   => $niveau,
            'exp_min'  => $expMin,
            'exp_max'  => $expMax,
            'exp_lvl'  => $expLvl,
            'progress' => $progress,
            'percent'  => $percent
        );
    }

    // METIER : Récupération du contenu Celsius
    // RETOUR : Contenu Celsius
    function getContenuCelsius($celsius)
    {
        // Sélection du contenu Celsius à afficher
        switch ($celsius)
        {
            case 'bugs':
                $contenuCelsius = 'Le site ne présente aucun bug. Si toutefois tu penses être tombé sur ce qui prétend en être un, tu peux le
                                   <strong>signaler</strong> sur cette page. Ce que j\'appellerai désormais "évolution" sera traitée dans les plus 
                                   brefs délais par une équipe exceptionnelle, toujours à ton écoute pour te servir au mieux.';
                break;

            case 'calendars':
                $contenuCelsius = 'Tu trouveras ici les derniers <strong>calendriers</strong> pour les afficher au mur. Si tu souhaites pouvoir les
                                   créer par toi-même, tu peux contacter l\'administrateur pour qu\'il t\'autorise à le faire.';
                break;

            case 'calendars_generator':
                $contenuCelsius = 'Si tu penses avoir besoin d\'un calendrier pour ton équipe, tu es au bon endroit ! Il te suffit de mettre ta
                                   plus belle <strong>photo</strong> du moment et le tour est joué. Tu peux les compléter avec des
                                   <strong>étiquettes</strong> qui permettront de savoir qui est le Scrum Master ou qui doit faire un gâteau cette
                                   semaine !';
                break;

            case 'changelog':
                $contenuCelsius = 'Tu veux être au courant des toutes <strong>dernières nouveautés</strong> ? Tu veux tout savoir de
                                   l\'<strong>histoire</strong> de ton site préféré ? Alors c\'est ici que ça se passe ! Passionné par la technique ou
                                   simple curieux, viens voir ce qui t\'attend et peut-être qu\'un jour ce sera à toi de compléter ces journaux...';
                break;

            case 'collector':
                $contenuCelsius = 'Si tu as l\'oreille qui traîne ou le regard perçant, peut-être que tu as vu un de tes compères faire une
                                   <strong>bêtise</strong>... encore une fois ! Alors n\'attends plus, montres-nous tout ça dès maintenant et que tu
                                   sois le chasseur ou la proie, on se retrouve toujours tous ici...';
                break;

            case 'cookingbox':
                $contenuCelsius = 'S\'il y a bien un endroit qui peut tous nous réunir, c\'est ici. Toutes tes créations et celles de tes meilleurs
                                   amis vont se retrouver à la vue de tous avant de terminer leur incroyable voyage dans ton estomac... en espérant
                                   que le goût soit à la hauteur du visuel. Partages également tes meilleurs secrets de <strong>recettes</strong> avec
                                   les autres !';
                break;

            case 'expensecenter':
                $contenuCelsius = 'Tu peux saisir ici une <strong>dépense en commun</strong> ou bien une <strong>dépense en montants</strong> pour
                                   répartir par exemple une commande sur chacun en fonction de ce qu\'il a pris. Ça peut même tenir compte des frais
                                   de livraison et des réductions ! Trop bien !';
                break;

            case 'foodadvisor':
                $contenuCelsius = 'Dans cette section, tu peux <strong>voter</strong> à partir de la liste des restaurants ou bien aller encore plus
                                   vite grâce à la toute nouvelle barre de recherche instantanée ! Ajoute tes choix <strong>avant 13h</strong> et
                                   n\'oublie pas non plus de remplir le <strong>résumé de la semaine</strong> ! Tu peux aussi voter à l\'avance pour
                                   les jours suivants et consulter tous tes choix des semaines précédentes.';
                break;

            case 'ideas':
                $contenuCelsius = 'Si tu as des <strong>idées</strong> et que tu veux en faire part aux autres, n\'hésite pas à en proposer de nouvelles.
                                   N\'importe qui peut les prendre en charge afin de devenir un super développeur Inside ! Le cycle de vie te montre
                                   comment seront traitées les idées en cas de prise en charge. Tu peux contacter l\'administrateur pour plus de
                                   précisions sur la mise en place du projet chez toi.';
                break;

            case 'missions':
                $contenuCelsius = 'Bonjour agent <strong>' . $_SESSION['user']['pseudo'] . '</strong>, ta <strong>mission</strong> si tu l\'acceptes, ou
                                   si tu le veux bien, ou si tu as le temps, ou si c\'est possible, est de vaincre tes adversaires. Allez on se bouge et
                                   on trouve tous les petits indices cachés partout avant les autres. L\'amitié a ses limites.';
                break;

            case 'moviehouse':
                $contenuCelsius = 'Parce que la culture n\'a pas de prix, parce que ce lieu est ma toute première maison, c\'est ici que se réunissent
                                   tous les (plus grands) <strong>films</strong> à voir ou à revoir. Mets une préférence sur chacun et peut-être que tu
                                   parviendras à organiser une <strong>sortie</strong> et partager un bon moment !';
                break;

            case 'notifications':
                $contenuCelsius = 'L\'essentiel de ce qui s\'est passé <strong>ces derniers temps</strong> est là, si tu as raté quelque chose, il est
                                   peut-être le moment de se rattraper ! Tu peux régler le filtre par défaut dans tes préférences si tu aimes voir plus
                                   ou moins de choses.';
                break;

            case 'petitspedestres':
                $contenuCelsius = 'Quelle idée d\'aller <strong>courir</strong> ? Pourquoi se faire du mal comme ça ? Pour être en bonne santé, se sentir
                                   en forme ? Quel intérêt ? C\'est peut-être pas si mal en fait... un jour je serai grand et j\'irai loin ! Un pied
                                   devant l\'autre on progresse sur ses meilleurs chemins.';
                break;

            case 'portail':
                $contenuCelsius = 'Bonjour <strong>' . $_SESSION['user']['pseudo'] . '</strong> et bienvenue sur la version ' . $_SESSION['index']['plateforme'] . '
                                   du site. Je suis <strong>Celsius</strong> et je vais te guider à travers les différentes sections. Ici tu peux
                                   accéder à l\'ensemble des applications du site comme celle pour voter pour le repas du midi et celle pour
                                   répartir les dépenses. Viens choisir ton prochain film, te moquer des bêtises que disent les autres ou
                                   bien échanger tes meilleures recettes. Tu peux aussi télécharger les derniers calendriers ou participer à des
                                   mini-jeux. Si tu es plus à l\'aise sur ton téléphone, sache que la <strong>version mobile</strong> est également
                                   disponible avec les mêmes fonctionnalités !';
                break;

            case 'profil':
                $contenuCelsius = 'Ici sont exposées toutes tes <strong>contributions</strong> à l\'équipe de joyeux Insiders ! Tu peux voir l\'état de
                                   ton <strong>compte</strong> en quelques infos essentielles et accéder aux autres paramètres qui te permettront de
                                   personnaliser ton expérience.';
                break;

            case 'restaurants':
                $contenuCelsius = 'Tu peux ajouter tes <strong>restaurants</strong> favoris ici et en faire profiter à tout le monde. Il faut en rajouter
                                   un maximum pour que le choix soit le plus grand possible ! Tu peux aussi <strong>voter</strong> directement sur cette
                                   liste en fonction des jours d\'ouverture qui ont été saisis, c\'est pratique.';
                break;

            case 'search':
                $contenuCelsius = 'Tu <strong>cherches</strong> quelque chose ? Tu es au bon endroit mon ami ! On a de tout ici : des bons films, des bons
                                   restos, des bonnes balades à pied et des super missions ! Fais ton choix, mais fais-le bien car il n\'y aura pas de
                                   retour en arrière. Ou pas, je ne sais pas encore.';
                break;

            case 'settings':
                $contenuCelsius = 'Un petit <strong>réglage</strong> par-ci, un tour de vis par-là... Allez, on règle tout ça pour que ce soit comme tu
                                   veux et transformer cet espace en ton espace personnel ! Tu peux changer de mot de passe, changer d\'équipe et
                                   paramétrer quelques options selon tes goûts. Si tu souhaites te désinscrire, sache déjà que j\'en suis très attristé
                                   mais que si le cas arrivait, aucune de tes données ne seraient conservées. Mais on sait tous que tu ne vas pas le
                                   faire hein ?';
                break;

            case 'success':
            case 'ranking':
                $contenuCelsius = 'Tu veux faire le malin et <strong>crâner</strong> devant tes copains ? Ok, y\'a moyen... Mais fais attention ! On ne
                                   sait jamais qui peut te dépasser à ce petit jeu-là, rien n\'est jamais vraiment défini à l\'avance. Regarde bien où
                                   en est ton voisin. Et nargue-le pour voir.';
                break;

            case 'themes':
                $contenuCelsius = 'On peut changer sa <strong>police de caractères</strong>, c\'est cool nan ? Et puis il y a les <strong>thèmes</strong>
                                   aussi qui sont toujours sympas. Tu peux choisir entre plusieurs selon ton niveau et aussi ceux des missions passées.
                                   D\'autres viendront s\'ajouter avec le temps !';
                break;

            default:
                $contenuCelsius = '';
                break;
        }

        // Retour
        return $contenuCelsius;
    }

    // METIER : Génération des missions
    // RETOUR : Aucun
    function generateMissions()
    {
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
        $listePages = array(
            '/inside/portail/bugs/bugs.php',
            '/inside/portail/calendars/calendars.php',
            '/inside/portail/changelog/changelog.php',
            '/inside/portail/collector/collector.php',
            '/inside/portail/cookingbox/cookingbox.php',
            //'/inside/portail/eventmanager/eventmanager.php',
            '/inside/portail/expensecenter/expensecenter.php',
            '/inside/portail/foodadvisor/foodadvisor.php',
            '/inside/portail/foodadvisor/restaurants.php',
            '/inside/portail/ideas/ideas.php',
            '/inside/portail/missions/details.php',
            '/inside/portail/missions/missions.php',
            '/inside/portail/moviehouse/details.php',
            '/inside/portail/moviehouse/mailing.php',
            '/inside/portail/moviehouse/moviehouse.php',
            '/inside/portail/notifications/notifications.php',
            '/inside/portail/petitspedestres/details.php',
            '/inside/portail/petitspedestres/petitspedestres.php',
            '/inside/portail/portail/portail.php',
            '/inside/portail/profil/profil.php',
            '/inside/portail/search/search.php'
        );

        // Définition des zones disponibles
        $listeZonesCompletes = array(
            'header',
            'footer',
            'article'
        );

        // Définition des positions horizontales disponibles (zones header et footer)
        $listePositionsHorizontales = array(
            'left',
            'right',
            'middle',
        );

        // Définition des positions verticales disponibles (zone article)
        $listePositionsArticle = array(
            'top_left',
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
            $boutonMission = array(
                'id_mission'  => $idMission,
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
    // RETOUR : Aucun
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
                $tableauTheme = array(
                    'background' => '/inside/includes/images/themes/backgrounds/' . $themeMissionActive->getReference() . '.png',
                    'header'     => '/inside/includes/images/themes/headers/' . $themeMissionActive->getReference() . '_h.png',
                    'footer'     => '/inside/includes/images/themes/footers/' . $themeMissionActive->getReference() . '_f.png',
                    'logo'       => '/inside/includes/images/themes/logos/' . $themeMissionActive->getReference() . '_l.png'
                );
            }
            else
            {
                $tableauTheme = array(
                    'background' => '/inside/includes/images/themes/backgrounds/' . $themeMissionActive->getReference() . '.png',
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
                        $tableauTheme = array(
                            'background' => '/inside/includes/images/themes/backgrounds/' . $themePersonnalise->getReference() . '.png',
                            'header'     => '/inside/includes/images/themes/headers/' . $themePersonnalise->getReference() . '_h.png',
                            'footer'     => '/inside/includes/images/themes/footers/' . $themePersonnalise->getReference() . '_f.png',
                            'logo'       => '/inside/includes/images/themes/logos/' . $themePersonnalise->getReference() . '_l.png'
                        );
                    }
                    else
                    {
                        $tableauTheme = array(
                            'background' => '/inside/includes/images/themes/backgrounds/' . $themePersonnalise->getReference() . '.png',
                            'header'     => '/inside/includes/images/themes/headers/' . $themePersonnalise->getReference() . '_h.png',
                            'footer'     => '/inside/includes/images/themes/footers/' . $themePersonnalise->getReference() . '_f.png',
                            'logo'       => NULL
                        );
                    }
                }
            }
        }

        // Mise en session des données
        $_SESSION['theme'] = $tableauTheme;
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
    function insertNotification($category, $equipe, $content, $author)
    {
        // Insertion de l'enregistrement en base
        $notification = array(
            'author'    => $author,
            'team'      => $equipe,
            'date'      => date('Ymd'),
            'time'      => date('His'),
            'category'  => $category,
            'content'   => $content,
            'to_delete' => 'N'
        );

        physiqueInsertionNotification($notification);
    }

    // METIER : Mise à jour d'une notification
    // RETOUR : Aucun
    function updateNotification($category, $equipe, $content, $status)
    {
        // Suppression de l'enregistrement en base
        physiqueUpdateNotification($category, $equipe, $content, $status);
    }

    // METIER : Suppression d'une notification
    // RETOUR : Aucun
    function deleteNotification($category, $equipe, $content)
    {
        // Suppression de l'enregistrement en base
        physiqueDeleteNotification($category, $equipe, $content);
    }

    // METIER : Contrôle notification existante
    // RETOUR : Booléen
    function controlNotification($category, $content, $equipe)
    {
        // Vérificartion notification existante
        $notificationExistante = physiqueNotificationExistante($category, $content, $equipe);

        // Retour
        return $notificationExistante;
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
            case 'explorer':
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

            // Incrémentation de la valeur précédente avec la valeur en entrée pour les missions (cas incoming <= 1)
            // Dans le cas particulier des missions, le succès ne peut pas être débloqué directement, on doit attendre le lendemain de la date de fin, on ne peut donc jamais l'afficher
            case 'christmas2017':
            case 'christmas2017_2':
            case 'golden-egg':
            case 'rainbow-egg':
            case 'wizard':
            case 'christmas2018':
            case 'christmas2018_2':
            case 'christmas2019':
            case 'delivery':
                // Récupération de l'ancienne valeur du succès de l'utilisateur
                $ancienneValeur = physiqueAncienneValeurSucces($reference, $identifiant);

                // Récupération de la nouvelle valeur
                if (!empty($ancienneValeur))
                    $value = $ancienneValeur + $incoming;
                else
                    $value = $incoming;
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
                $succesUser = array(
                    'reference'   => $reference,
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
        // Récupération des succès liés à la mission
        $listeSuccesMission = physiqueSuccesMission($reference);

        // Insertion des succès pour chaque mission concernée
        if (!empty($listeSuccesMission))
        {
            foreach ($listeSuccesMission as $succes)
            {
                insertOrUpdateSuccesValue($succes->getReference(), $identifiant, 1);
            }
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
            case 'add_parcours':
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
        $formattedAvatar = array(
            'path'  => $path,
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
            $chaine = mb_substr($chaine, 0, $limite) . '...';

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
    function controlsUploadFile($file, $name, $type)
    {
        // Initialisations
        $output     = array(
            'control_ok' => false,
            'new_name'   => '',
            'tmp_file'   => '',
            'type_file'  => ''
        );
        $control_ok = true;

        // Contrôles de l'image si elle est bien renseignée
        if (!empty($file['name']))
        {
            // Récupération des données
            $nameFile  = $file['name'];
            $typeFile  = $file['type'];
            $tmpFile   = $file['tmp_name'];
            $errorFile = $file['error'];
            $sizeFile  = $file['size'];

            // Définition de la limite de taille maximale fichier (15 Mo)
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
                switch ($type)
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

                    case 'pdf':
                        if (!strstr($typeFile, 'pdf'))
                        {
                            $_SESSION['alerts']['wrong_file_type'] = true;
                            $control_ok                            = false;
                        }
                        break;

                    case 'all':
                    default:
                        if (!strstr($typeFile, 'jpg') && !strstr($typeFile, 'jpeg') && !strstr($typeFile, 'bmp') && !strstr($typeFile, 'gif') && !strstr($typeFile, 'png') && !strstr($typeFile, 'pdf'))
                        {
                            $_SESSION['alerts']['wrong_file_type'] = true;
                            $control_ok                            = false;
                        }
                        break;
                }
            }

            // Récupération des informations du fichier
            if ($control_ok == true)
            {
                $typeImage = pathinfo($nameFile, PATHINFO_EXTENSION);
                $newName   = $name . '.' . $typeImage;
                $output    = array(
                    'control_ok' => true,
                    'new_name'   => $newName,
                    'tmp_file'   => $tmpFile,
                    'type_file'  => $typeImage
                );
            }
        }

        // Retour
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

    // METIER : Extraction de la base de données
    // RETOUR : Contenu du fichier à générer
    function extractBdd()
    {
        // Initialisations
        $contenu = '';
        $semaine = array('Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim');
        $mois    = array('Décembre', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre');

        // Entête du fichier
        $contenu .= '-- Inside SQL Dump
--
-- Généré le :  ' . $semaine[date('w')] . ' ' . date('d') . ' ' . $mois[date('n')] . ' ' . date('Y') . ' à ' . date('H:i') . '

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `inside`
--

';

        // Récupération de la liste des tables à extraire
        $tables = physiqueTablesBdd();

        // Traitement d'extraction de chaque table
        foreach ($tables as $table)
        {
            // Comptage du nomnbre de colonnes et de lignes
            $dimensionsTable = physiqueDimensionsTable($table);

            // Entête de la table
            $contenu .= '-- --------------------------------------------------------

--
-- Structure de la table `' . $table . '`
--';

            // Initialisation du contenu de la table (CREATE TABLE)
            $contenu .= physiqueCreateTable($table);

            // Description de la table
            $contenu .= '--
-- Contenu de la table `' . $table . '`
--
';

            // Récupération du contenu de chaque table
            $contenu .= physiqueContenuTable($table, $dimensionsTable);
        }

        // Fin de la table
        $contenu .= "
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
";

        // Retour
        return $contenu;
    }
?>