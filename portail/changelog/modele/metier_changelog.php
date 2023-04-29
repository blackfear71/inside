<?php
    include_once('../../includes/classes/changelog.php');

    // METIER : Contrôle année existante (pour les onglets)
    // RETOUR : Booléen
    function controlYear($annee)
    {
        // Initialisations
        $anneeExistante = false;

        // Vérification année présente en base
        if (isset($annee) AND is_numeric($annee))
            $anneeExistante = physiqueAnneeExistante($annee);

        // Retour
        return $anneeExistante;
    }

    // METIER : Lecture années distinctes pour les onglets
    // RETOUR : Liste des années existantes
    function getOnglets()
    {
        // Récupération de la liste des années existantes
        $onglets = physiqueOnglets();

        // Retour
        return $onglets;
    }

    // METIER : Récupération des catégories pour les logs
    // RETOUR : Liste des catégories
    function getCategories()
    {
        // Tableau des catégories
        $listCategories = array(
            'admin'            => 'ADMINISTRATION',
            'other'            => 'AUTRE',
            'calendars'        => 'CALENDARS',
            'collector'        => 'COLLECTOR ROOM',
            'cooking_box'      => 'COOKING BOX',
            'bugs'             => 'DEMANDES D\'ÉVOLUTION',
            'expense_center'   => 'EXPENSE CENTER',
            'general'          => 'GÉNÉRAL',
            'chat'             => 'INSIDE ROOM',
            'change_log'       => 'JOURNAL DES MODIFICATIONS',
            'food_advisor'     => 'LES ENFANTS ! À TABLE !',
            'petits_pedestres' => 'LES PETITS PÉDESTRES',
            'missions'         => 'MISSIONS : INSIDER',
            'movie_house'      => 'MOVIE HOUSE',
            'notifications'    => 'NOTIFICATIONS',
            'portail'          => 'PORTAIL',
            'profile'          => 'PROFIL',
            'search'           => 'RECHERCHE',
            'cron'             => 'TÂCHES CRON',
            'technical'        => 'TECHNIQUE',
            'ideas'            => '#THEBOX'
        );

        // Retour
        return $listCategories;
    }

    // METIER : Lecture de la liste des logs
    // RETOUR : Liste des logs
    function getLogs($annee, $categories)
    {
        // Récupération de la liste de logs de l'année
        $listeLogs = physiqueChangelog($annee);

        // Traitement des logs
        if (!empty($listeLogs))
        {
            foreach ($listeLogs as $log)
            {
                // Extraction des logs
                $extractLogs = array_filter(explode(';', $log->getLogs()));

                // Tri des logs par catégories
                $sortedLogs = array();

                foreach ($categories as $categorie => $labelCategorie)
                {
                    foreach ($extractLogs as $keyExtract => $extractedLog)
                    {
                        list($entryExtracted, $categoryExtracted) = explode('@', $extractedLog);

                        if ($categoryExtracted == $categorie)
                        {
                            if (!isset($sortedLogs[$categorie]))
                                $sortedLogs[$categorie] = array();

                            array_push($sortedLogs[$categorie], $entryExtracted);
                            unset($extractLogs[$keyExtract]);
                        }
                    }
                }

                // Sécurité si besoin (logs restants sans catégorie ajoutés à une catégorie "Autre")
                if (!empty($extractLogs))
                {
                    if (!isset($sortedLogs['other']))
                        $sortedLogs['other'] = array();

                    foreach ($extractLogs as $keyExtract => $extractedLog)
                    {
                        if (!empty($extractedLog))
                        {
                            list($entryExtracted, $categoryExtracted) = explode('@', $extractedLog);
                            array_push($sortedLogs['other'], $entryExtracted);
                        }
                    }
                }

                // Remplacement des logs récupérés par les logs triés
                $log->setLogs($sortedLogs);
            }
        }

        // Retour
        return $listeLogs;
    }

    // METIER : Récupération de l'histoire du site
    // RETOUR : Liste des dates du site
    function getHistory()
    {
        // Initialisations
        $stories = array();

        // Introduction
        $story = array(
            'Inside c\'est avant tout votre histoire. C\'est pour vous que le site a été façonné mais c\'est par vous qu\'il s\'est accompli. De son origine à son état actuel, le site
            a évolué en fonction de la technique et des besoins de chacun. Plus que jamais cette histoire est une de celles qui nous rassemble et ne se termine jamais. En tant que développeur, je
            peux en attester, il y a toujours à faire !',
            'De la part de Pierre, créateur du site.'
        );

        array_push($stories, $story);

        // Création de ReferenceGuide
        $story = array(
            '5 Novembre 2016',
            'Notre histoire ne commence pas par celle d\'Inside mais par celle d\'un autre, oublié de tous. ReferenceGuide. Vous l\'aviez oublié n\'est-ce pas ? Mais comment pourrais-je vous en
            vouloir ? Avouons-le, c\'était un échec. Mais je dirais un échec nécessaire. Tout partait d\'une bonne intention, une plateforme de partage des connaissances utiles pour les jeunes
            développeurs, un regroupement de documentations.',
            'Les contraintes étant trop fortes, ce projet a eu du mal à avancer. Mes connaissances en web étaient certaines mais leur précision beaucoup moins. Pourtant j\'y voyais à ce titre
            l\'occasion de progresser par moi-même. Les bases étaient donc posées mais encore balbutiantes.'
        );

        array_push($stories, $story);

        // Création d'Inside
        $story = array(
            '9 Mars 2017',
            'Date perdue puis retrouvée parmi les tréfonds d\'archives conservées précieusement, elle marque le début de l\'aventure Inside. En parallèle de l\'exploration des idées
            initialement prévues pour ReferenceGuide, d\'autres projets sont nés, à base de VBA principalement.',
            'Un en particulier a rapidement retenu mon attention. Un Excel avait été mis en
            place pour faciliter l\'organisation de sorties cinéma. Inside étant à la base prévu comme une refonte graphique du premier site, il a vu débarquer en une nuit seulement un
            nouvel outil qui permettait de faire ce que faisaient ces macros et même plus ! La révolution était en marche.'
        );

        array_push($stories, $story);

        // Création du logo
        $story = array(
            '3 Avril 2017',
            'Les idées fusent, l\'excitation est certaine et la créativité s\'en retrouve décuplée. Tout s\'enchaîne très vite, une petite retouche par-ci, une amélioration par-là... Le temps
            passe et son cours change progressivement. Malgré le design douteux de l\'époque, petit site grandit pour devenir une grande plateforme. L\'essence même du site incarnée par son nom
            est au cœur de tous les développements. Rien ne se fait sans penser à la notion de partage, on progresse pour toujours plus attirer le regard et apporter de l\'aide là où le besoin se
            fait ressentir.',
            'Mais il faut fédérer encore plus. Trouver quelque chose qui impacte les esprits durablement. De gribouillis en gribouillis sur des post-it, on progresse avec assurance
            vers ce qui va devenir votre logo préféré ! Le dessin est réalisé le jour même sur support numérique, incorporé sur le site et dans vos mémoires pour toujours.'
        );

        array_push($stories, $story);

        // Partage sur GitHub, Sacha rejoint les développeurs
        $story = array(
            '10 Juin 2017',
            'Le travail avance bien, il y a beaucoup de remises en cause, la section permettant de partager des connaissances sur les outils de travail disparait pour des raisons évidentes et
            l\'imaginaire s\'occupe d\'ouvrir de nouvelles voies. Bientôt vous pourrez dire adieu à tous ces Excel pour gérer vos dépenses, finis les mails aléatoires pour vous demander votre avis
            sur de nouveaux calendriers, victoire sur le spam ! Tout avance très vite, trop vite. La charge de travail est grande pour un simple humain tel que moi, il faut partager avec qui le veut.',
            'Et pour cela un des membres souhaite rejoindre l\'équipe de développements, car une équipe de 1 ça commence à sentir le cramé. Ce jour-là, pour partager toujours plus et avancer
            toujours plus loin s\'ouvre un répertoire GitHub de partage du code. Il est récupéré et du nouveau apparait. Une section dédiée à ceux qui souhaitaient organiser des sorties courses à
            pied voit le jour. Mais pas seulement. Après quelques temps ce projet arrive en ligne et là une infinité de possibilités s\'est ouverte. Tous les projets étaient maintenant réalisables
            beaucoup plus efficacement, proprement et rapidement grâce à un projet en particulier. J\'ai nommé Sacha. En plus d\'apporter son aide il a mis en place la nouvelle et toujours actuelle architecture
            du site MVC. Plus qu\'une évolution, Inside est totalement refondu pour correspondre à ce standard. La suite, vous la connaissez.'
        );

        array_push($stories, $story);

        // Arrivée du JavaScript
        $story = array(
            '25 Mars 2018',
            'On fait un bond dans le temps vers la prochaine avancée majeure du site : l\'arrivée tant attendue du JavaScript. Il ne manquait plus que cette facette pour avoir un site complet
            au niveau des technologies. Pour tout avouer, j\'en avais presque peur. Trop grand, trop aléatoire, trop peu de connaissances. Mais je n\'ai rien lâché et ainsi sont apparues les
            premières vraies fonctions JS du site incarnées par le chat INSIDE Room. Ce jour j\'ai su que rien n\'était inatteignable et que tout méritait de s\'apprivoiser.'
        );

        array_push($stories, $story);

        // Développement sur mobile
        $story = array(
            '4 Mai 2020',
            'Le temps passe, les nouveautés s\'accumulent et Inside devient de plus en plus complet. Certes il n\'a pas vocation à s\'arrêter mais malheureusement une contrainte de sécurité
            impose son arrêt immédiat en tant que tel. La version web n\'étant plus tolérée dans son usage classique, il faut se démener pour trouver une solution. Enfin un vrai nom de
            domaine ? Détourner l\'attention l\'aurait quand même finalement attirée. Utiliser le site tel quel sur mobile ? Pas pratique, moche et lent... Baisser les bras ? Vous connaissez
            ma détermination, la défaite n\'est pas une option envisageable.',
            'Alors on expérimente, on prend son temps pour poser les bases d\'un futur sans contraintes, un avenir radieux et toujours bienveillant. Bienvenue sur la version mobile ! Ce jour
            marque une nouvelle révolution sur Inside et cela ne fait que commencer...'
        );

        array_push($stories, $story);

        // Nom de domaine et certificat SSL
        $story = array(
            '16 et 20 Avril 2022',
            'Deux dates importantes qui marquent un nouveau pas en avant pour Inside. L\'arrivée tant désirée d\'un nom de domaine (contraintes techniques oblige) facilite la vie de tout le monde
            (et surtout des développeurs !). Et quitte à ne pas se lasser on va pousser le vice un peu plus loin en mettant en place le HTTPS via un certificat SSL hyper simple à installer.
            Vraiment super simple pour n\'importe quel débutant réseau très expérimenté.'
        );

        array_push($stories, $story);

        // PHP 7.4
        $story = array(
            '20 Septembre 2022',
            'Horreur ! Le site ne marche presque plus ! Mais que se passe-t-il ??? Alors rassurons-nous ce n\'est pas la fin, seulement le serveur qui devenait petit à petit obsolète... Les contraintes
            techniques initiales avaient poussé à utiliser une version 5 de PHP qui venait d\'arriver au terme de son support. Heureusement votre serviteur s\'est démené pour mettre à jour la version de PHP en 7.4,
            ce qui devrait garantir la pérénité du site pendant de nombreuses années encore ! Profitez-en !'
        );

        array_push($stories, $story);

        // A vous de jouer
        $story = array(
            date('j') . ' ' . formatMonthForDisplay(date('m')) . ' ' . date('Y'),
            'Ce site est le vôtre, vous êtes le bienvenue pour le faire perdurer dans le temps. Soyez acteur d\'Inside et vous remporterez plus qu\'un succès !'
        );

        array_push($stories, $story);

        // Retour
        return $stories;
    }
?>