<?php
    class Profile
    {
        private $id;
        private $identifiant;
        private $team;
        private $new_team;
        private $ping;
        private $connected;
        private $status;
        private $pseudo;
        private $avatar;
        private $email;
        private $anniversary;
        private $experience;
        private $level;
        private $expenses;
        private $beginner;
        private $developper;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->id          = 0;
            $this->identifiant = '';
            $this->team        = '';
            $this->new_team    = '';
            $this->ping        = '';
            $this->connected   = '';
            $this->status      = '';
            $this->pseudo      = '';
            $this->avatar      = '';
            $this->email       = '';
            $this->experience  = '';
            $this->level       = '';
            $this->anniversary = '';
            $this->expenses    = '';
            $this->beginner    = 0;
            $this->developper  = 0;
        }

        // Constructeur de l'objet Profile en fonction des données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $profile = new self();
            $profile->fillWithData($data);

            return $profile;
        }

        protected function fillWithData($data)
        {
            if (isset($data['id']))
                $this->id          = $data['id'];

            if (isset($data['identifiant']))
                $this->identifiant = $data['identifiant'];

            if (isset($data['team']))
                $this->team        = $data['team'];

            if (isset($data['new_team']))
                $this->new_team    = $data['new_team'];

            if (isset($data['ping']))
                $this->ping        = $data['ping'];

            if (isset($data['status']))
                $this->status      = $data['status'];

            if (isset($data['pseudo']))
                $this->pseudo      = $data['pseudo'];

            if (isset($data['avatar']))
                $this->avatar      = $data['avatar'];

            if (isset($data['email']))
                $this->email       = $data['email'];

            if (isset($data['anniversary']))
                $this->anniversary = $data['anniversary'];

            if (isset($data['experience']))
                $this->experience  = $data['experience'];

            if (isset($data['expenses']))
                $this->expenses    = $data['expenses'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $profile = new self();
            $profile->fillSecureData($data);

            return $profile;
        }

        protected function fillSecureData($data)
        {
            $this->id          = $data->getId();
            $this->identifiant = htmlspecialchars($data->getIdentifiant());
            $this->team        = $data->getTeam();
            $this->new_team    = $data->getNew_team();
            $this->ping        = htmlspecialchars($data->getPing());
            $this->connected   = htmlspecialchars($data->getConnected());
            $this->status      = htmlspecialchars($data->getStatus());
            $this->pseudo      = htmlspecialchars($data->getPseudo());
            $this->avatar      = htmlspecialchars($data->getAvatar());
            $this->email       = htmlspecialchars($data->getEmail());
            $this->experience  = htmlspecialchars($data->getExperience());
            $this->level       = htmlspecialchars($data->getLevel());
            $this->anniversary = htmlspecialchars($data->getAnniversary());
            $this->expenses    = htmlspecialchars($data->getExpenses());
            $this->beginner    = htmlspecialchars($data->getBeginner());
            $this->developper  = htmlspecialchars($data->getDevelopper());
        }

        // Getters et Setters pour l'objet Profile
        // id
        public function setId($id)
        {
            $this->id = $id;
        }

        public function getId()
        {
            return $this->id;
        }

        // Identifiant
        public function setIdentifiant($identifiant)
        {
            $this->identifiant = $identifiant;
        }

        public function getIdentifiant()
        {
            return $this->identifiant;
        }

        // Equipe
        public function setTeam($team)
        {
            $this->team = $team;
        }

        public function getTeam()
        {
            return $this->team;
        }

        // Nouvelle équipe
        public function setNew_team($new_team)
        {
            $this->new_team = $new_team;
        }

        public function getNew_team()
        {
            return $this->new_team;
        }

        // Ping de connexion
        public function setPing($ping)
        {
            $this->ping = $ping;
        }

        public function getPing()
        {
            return $this->ping;
        }

        // Statut de connexion
        public function setConnected($connected)
        {
            $this->connected = $connected;
        }

        public function getConnected()
        {
            return $this->connected;
        }

        // Top statut inscription
        public function setStatus($status)
        {
            $this->status = $status;
        }

        public function getStatus()
        {
            return $this->status;
        }

        // Pseudo
        public function setPseudo($pseudo)
        {
            $this->pseudo = $pseudo;
        }

        public function getPseudo()
        {
            return $this->pseudo;
        }

        // Avatar
        public function setAvatar($avatar)
        {
            $this->avatar = $avatar;
        }

        public function getAvatar()
        {
            return $this->avatar;
        }

        // Email
        public function setEmail($email)
        {
            $this->email = $email;
        }

        public function getEmail()
        {
            return $this->email;
        }

        // Anniversaire
        public function setAnniversary($anniversary)
        {
            $this->anniversary = $anniversary;
        }

        public function getAnniversary()
        {
            return $this->anniversary;
        }

        // Expérience
        public function setExperience($experience)
        {
            $this->experience = $experience;
        }

        public function getExperience()
        {
            return $this->experience;
        }

        // Niveau
        public function setLevel($level)
        {
            $this->level = $level;
        }

        public function getLevel()
        {
            return $this->level;
        }

        // Dépenses (total)
        public function setExpenses($expenses)
        {
            $this->expenses = $expenses;
        }

        public function getExpenses()
        {
            return $this->expenses;
        }

        // Succès Beginner
        public function setBeginner($beginner)
        {
            $this->beginner = $beginner;
        }

        public function getBeginner()
        {
            return $this->beginner;
        }

        // Succès Developper
        public function setDevelopper($developper)
        {
            $this->developper = $developper;
        }

        public function getDevelopper()
        {
            return $this->developper;
        }
    }

    class Progression
    {
        private $niveau;
        private $experience_min;
        private $experience_max;
        private $experience_niveau;
        private $progression;
        private $pourcentage;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->niveau            = 0;
            $this->experience_min    = 0;
            $this->experience_max    = 0;
            $this->experience_niveau = 0;
            $this->progression       = 0;
            $this->pourcentage       = 0;
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $progression = new self();
            $progression->fillSecureData($data);

            return $progression;
        }

        protected function fillSecureData($data)
        {
            $this->niveau            = htmlspecialchars($data->getNiveau());
            $this->experience_min    = htmlspecialchars($data->getExperience_min());
            $this->experience_max    = htmlspecialchars($data->getExperience_max());
            $this->experience_niveau = htmlspecialchars($data->getExperience_niveau());
            $this->progression       = htmlspecialchars($data->getProgression());
            $this->pourcentage       = htmlspecialchars($data->getPourcentage());
        }

        // Getters et Setters pour l'objet Progression
        // Identifiant
        public function setNiveau($niveau)
        {
            $this->niveau = $niveau;
        }

        public function getNiveau()
        {
            return $this->niveau;
        }

        // Pseudo
        public function setExperience_min($experience_min)
        {
            $this->experience_min = $experience_min;
        }

        public function getExperience_min()
        {
            return $this->experience_min;
        }

        // Nombre de films ajoutés Movie House
        public function setExperience_max($experience_max)
        {
            $this->experience_max = $experience_max;
        }

        public function getExperience_max()
        {
            return $this->experience_max;
        }

        // Nombre de commentaires Movie House
        public function setExperience_niveau($experience_niveau)
        {
            $this->experience_niveau = $experience_niveau;
        }

        public function getExperience_niveau()
        {
            return $this->experience_niveau;
        }

        // Nombre de phrases cultes ajoutées
        public function setProgression($progression)
        {
            $this->progression = $progression;
        }

        public function getProgression()
        {
            return $this->progression;
        }

        // Bilan des dépenses
        public function setPourcentage($pourcentage)
        {
            $this->pourcentage = $pourcentage;
        }

        public function getPourcentage()
        {
            return $this->pourcentage;
        }
    }

    class StatistiquesProfil
    {
        private $nb_films_ajoutes;
        private $nb_comments;
        private $nb_reservations;
        private $nb_gateaux;
        private $nb_recettes;
        private $expenses;
        private $nb_collectors;
        private $nb_ideas;
        private $nb_bugs;
        private $nb_evolutions;
        private $nb_parcours;
        private $nb_participations;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->nb_films_ajoutes  = 0;
            $this->nb_comments       = 0;
            $this->nb_reservations   = 0;
            $this->nb_gateaux        = 0;
            $this->nb_recettes       = 0;
            $this->expenses          = 0;
            $this->nb_collectors     = 0;
            $this->nb_ideas          = 0;
            $this->nb_bugs           = 0;
            $this->nb_evolutions     = 0;
            $this->nb_parcours       = 0;
            $this->nb_participations = 0;
        }

        // Constructeur de l'objet StatistiquesProfil en fonction des données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $statistiques = new self();
            $statistiques->fillWithData($data);

            return $statistiques;
        }

        protected function fillWithData($data)
        {
            if (isset($data['nb_films_ajoutes']))
                $this->nb_films_ajoutes  = $data['nb_films_ajoutes'];

            if (isset($data['nb_comments']))
                $this->nb_comments       = $data['nb_comments'];

            if (isset($data['nb_reservations']))
                $this->nb_reservations   = $data['nb_reservations'];

            if (isset($data['nb_gateaux']))
                $this->nb_gateaux        = $data['nb_gateaux'];

            if (isset($data['nb_recettes']))
                $this->nb_recettes       = $data['nb_recettes'];

            if (isset($data['expenses']))
                $this->expenses          = $data['expenses'];

            if (isset($data['nb_collectors']))
                $this->nb_collectors     = $data['nb_collectors'];

            if (isset($data['nb_ideas']))
                $this->nb_ideas          = $data['nb_ideas'];

            if (isset($data['nb_bugs']))
                $this->nb_bugs           = $data['nb_bugs'];

            if (isset($data['nb_evolutions']))
                $this->nb_evolutions     = $data['nb_evolutions'];

            if (isset($data['nb_parcours']))
                $this->nb_parcours       = $data['nb_parcours'];

            if (isset($data['nb_participations']))
                $this->nb_participations = $data['nb_participations'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $statistiques = new self();
            $statistiques->fillSecureData($data);

            return $statistiques;
        }

        protected function fillSecureData($data)
        {
            $this->nb_films_ajoutes  = htmlspecialchars($data->getNb_films_ajoutes());
            $this->nb_comments       = htmlspecialchars($data->getNb_comments());
            $this->nb_reservations   = htmlspecialchars($data->getNb_reservations());
            $this->nb_gateaux        = htmlspecialchars($data->getNb_gateaux());
            $this->nb_recettes       = htmlspecialchars($data->getNb_recettes());
            $this->expenses          = htmlspecialchars($data->getExpenses());
            $this->nb_collectors     = htmlspecialchars($data->getNb_collectors());
            $this->nb_ideas          = htmlspecialchars($data->getNb_ideas());
            $this->nb_bugs           = htmlspecialchars($data->getNb_bugs());
            $this->nb_evolutions     = htmlspecialchars($data->getNb_evolutions());
            $this->nb_parcours       = htmlspecialchars($data->getNb_parcours());
            $this->nb_participations = htmlspecialchars($data->getNb_participations());
        }

        // Getters et Setters pour l'objet StatistiquesProfil
        // Nombre de films ajoutés Movie House
        public function setNb_films_ajoutes($nb_films_ajoutes)
        {
            $this->nb_films_ajoutes = $nb_films_ajoutes;
        }

        public function getNb_films_ajoutes()
        {
            return $this->nb_films_ajoutes;
        }

        // Nombre de commentaires Movie House
        public function setNb_comments($nb_comments)
        {
            $this->nb_comments = $nb_comments;
        }

        public function getNb_comments()
        {
            return $this->nb_comments;
        }

        // Nombre de réservations Food Advisor
        public function setNb_reservations($nb_reservations)
        {
            $this->nb_reservations = $nb_reservations;
        }

        public function getNb_reservations()
        {
            return $this->nb_reservations;
        }

        // Nombre de gâteaux faits
        public function setNb_gateaux($nb_gateaux)
        {
            $this->nb_gateaux = $nb_gateaux;
        }

        public function getNb_gateaux()
        {
            return $this->nb_gateaux;
        }

        // Nombre de recettes saisies
        public function setNb_recettes($nb_recettes)
        {
            $this->nb_recettes = $nb_recettes;
        }

        public function getNb_recettes()
        {
            return $this->nb_recettes;
        }

        // Solde des dépenses
        public function setExpenses($expenses)
        {
            $this->expenses = $expenses;
        }

        public function getExpenses()
        {
            return $this->expenses;
        }

        // Nombre de phrases cultes soumises
        public function setNb_collectors($nb_collectors)
        {
            $this->nb_collectors = $nb_collectors;
        }

        public function getNb_collectors()
        {
            return $this->nb_collectors;
        }

        // Nombre d'idées soumises
        public function setNb_ideas($nb_ideas)
        {
            $this->nb_ideas = $nb_ideas;
        }

        public function getNb_ideas()
        {
            return $this->nb_ideas;
        }

        // Nombre de bugs rapportés
        public function setNb_bugs($nb_bugs)
        {
            $this->nb_bugs = $nb_bugs;
        }

        public function getNb_bugs()
        {
            return $this->nb_bugs;
        }

        // Nombre d'évolutions proposées
        public function setNb_evolutions($nb_evolutions)
        {
            $this->nb_evolutions = $nb_evolutions;
        }

        public function getNb_evolutions()
        {
            return $this->nb_evolutions;
        }

        // Nombre de parcours ajoutés
        public function setNb_parcours($nb_parcours)
        {
            $this->nb_parcours = $nb_parcours;
        }

        public function getNb_parcours()
        {
            return $this->nb_parcours;
        }

        // Nombre de participations aux parcours
        public function setNb_participations($nb_participations)
        {
            $this->nb_participations = $nb_participations;
        }

        public function getNb_participations()
        {
            return $this->nb_participations;
        }
    }

    class Preferences
    {
        private $id;
        private $ref_theme;
        private $font;
        private $init_chat;
        private $celsius;
        private $view_movie_house;
        private $categories_movie_house;
        private $view_the_box;
        private $view_notifications;
        private $manage_calendars;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->id                     = 0;
            $this->ref_theme              = '';
            $this->font                   = '';
            $this->init_chat              = '';
            $this->celsius                = '';
            $this->view_movie_house       = '';
            $this->categories_movie_house = '';
            $this->view_the_box           = '';
            $this->view_notifications     = '';
            $this->manage_calendars       = '';
        }

        // Constructeur de l'objet Preferences en fonction des données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $preferences = new self();
            $preferences->fillWithData($data);

            return $preferences;
        }

        protected function fillWithData($data)
        {
            if (isset($data['id']))
                $this->id                     = $data['id'];

            if (isset($data['ref_theme']))
                $this->ref_theme              = $data['ref_theme'];

            if (isset($data['font']))
                $this->font                   = $data['font'];

            if (isset($data['init_chat']))
                $this->init_chat              = $data['init_chat'];

            if (isset($data['celsius']))
                $this->celsius                = $data['celsius'];

            if (isset($data['view_movie_house']))
                $this->view_movie_house       = $data['view_movie_house'];

            if (isset($data['categories_movie_house']))
                $this->categories_movie_house = $data['categories_movie_house'];

            if (isset($data['view_the_box']))
                $this->view_the_box           = $data['view_the_box'];

            if (isset($data['view_notifications']))
                $this->view_notifications     = $data['view_notifications'];

            if (isset($data['manage_calendars']))
                $this->manage_calendars       = $data['manage_calendars'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $preferences = new self();
            $preferences->fillSecureData($data);

            return $preferences;
        }

        protected function fillSecureData($data)
        {
            $this->id                     = $data->getId();
            $this->ref_theme              = htmlspecialchars($data->getRef_theme());
            $this->font                   = htmlspecialchars($data->getFont());
            $this->init_chat              = htmlspecialchars($data->getInit_chat());
            $this->celsius                = htmlspecialchars($data->getCelsius());
            $this->view_movie_house       = htmlspecialchars($data->getView_movie_house());
            $this->categories_movie_house = htmlspecialchars($data->getCategories_movie_house());
            $this->view_the_box           = htmlspecialchars($data->getView_the_box());
            $this->view_notifications     = htmlspecialchars($data->getView_notifications());
            $this->manage_calendars       = htmlspecialchars($data->getManage_calendars());
        }

        // Getters et Setters pour l'objet Preferences
        // id
        public function setId($id)
        {
            $this->id = $id;
        }

        public function getId()
        {
            return $this->id;
        }

        // Référence thème
        public function setRef_theme($ref_theme)
        {
            $this->ref_theme = $ref_theme;
        }

        public function getRef_theme()
        {
            return $this->ref_theme;
        }

        // Police de caractères
        public function setFont($font)
        {
            $this->font = $font;
        }

        public function getFont()
        {
            return $this->font;
        }

        // Initialisation chat
        public function setInit_chat($init_chat)
        {
            $this->init_chat = $init_chat;
        }

        public function getInit_chat()
        {
            return $this->init_chat;
        }

        // Affichage Celsius
        public function setCelsius($celsius)
        {
            $this->celsius = $celsius;
        }

        public function getCelsius()
        {
            return $this->celsius;
        }

        // Préférence vue par défaut Movie House
        public function setView_movie_house($view_movie_house)
        {
            $this->view_movie_house = $view_movie_house;
        }

        public function getView_movie_house()
        {
            return $this->view_movie_house;
        }

        // Préférence catégories affichéees Movie House
        public function setCategories_movie_house($categories_movie_house)
        {
            $this->categories_movie_house = $categories_movie_house;
        }

        public function getCategories_movie_house()
        {
            return $this->categories_movie_house;
        }

        // Préférence vue par défaut #TheBox
        public function setView_the_box($view_the_box)
        {
            $this->view_the_box = $view_the_box;
        }

        public function getView_the_box()
        {
            return $this->view_the_box;
        }

        // Préférence vue par défaut Notifications
        public function setView_notifications($view_notifications)
        {
            $this->view_notifications = $view_notifications;
        }

        public function getView_notifications()
        {
            return $this->view_notifications;
        }

        // Préférence (admin) gestion des calendriers
        public function setManage_calendars($manage_calendars)
        {
            $this->manage_calendars = $manage_calendars;
        }

        public function getManage_calendars()
        {
            return $this->manage_calendars;
        }
    }

    class StatistiquesAdmin
    {
        private $identifiant;
        private $pseudo;
        private $nb_films_ajoutes;
        private $nb_films_comments;
        private $nb_restaurants_ajoutes;
        private $nb_reservations;
        private $nb_gateaux_semaine;
        private $nb_recettes;
        private $expenses;
        private $nb_collectors;
        private $nb_parcours_ajoutes;
        private $nb_parcours_participations;
        private $nb_bugs_soumis;
        private $nb_bugs_resolus;
        private $nb_bugs_rejetes;
        private $nb_idees_soumises;
        private $nb_idees_en_charge;
        private $nb_idees_terminees;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->identifiant                = '';
            $this->pseudo                     = '';
            $this->nb_films_ajoutes           = 0;
            $this->nb_films_comments          = 0;
            $this->nb_restaurants_ajoutes     = 0;
            $this->nb_reservations            = 0;
            $this->nb_gateaux_semaine         = 0;
            $this->nb_recettes                = 0;
            $this->expenses                   = 0;
            $this->nb_collectors              = 0;
            $this->nb_parcours_ajoutes        = 0;
            $this->nb_parcours_participations = 0;
            $this->nb_bugs_soumis             = 0;
            $this->nb_bugs_resolus            = 0;
            $this->nb_bugs_rejetes            = 0;
            $this->nb_idees_soumises          = 0;
            $this->nb_idees_en_charge         = 0;
            $this->nb_idees_terminees         = 0;
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $statistiquesAdmin = new self();
            $statistiquesAdmin->fillSecureData($data);

            return $statistiquesAdmin;
        }

        protected function fillSecureData($data)
        {
            $this->identifiant                = htmlspecialchars($data->getIdentifiant());
            $this->pseudo                     = htmlspecialchars($data->getPseudo());
            $this->nb_films_ajoutes           = htmlspecialchars($data->getNb_films_ajoutes());
            $this->nb_films_comments          = htmlspecialchars($data->getNb_films_comments());
            $this->nb_restaurants_ajoutes     = htmlspecialchars($data->getNb_restaurants_ajoutes());
            $this->nb_reservations            = htmlspecialchars($data->getNb_reservations());
            $this->nb_gateaux_semaine         = htmlspecialchars($data->getNb_gateaux_semaine());
            $this->nb_recettes                = htmlspecialchars($data->getNb_recettes());
            $this->expenses                   = htmlspecialchars($data->getExpenses());
            $this->nb_collectors              = htmlspecialchars($data->getNb_collectors());
            $this->nb_parcours_ajoutes        = htmlspecialchars($data->getNb_parcours_ajoutes());
            $this->nb_parcours_participations = htmlspecialchars($data->getNb_parcours_participations());
            $this->nb_bugs_soumis             = htmlspecialchars($data->getNb_bugs_soumis());
            $this->nb_bugs_resolus            = htmlspecialchars($data->getNb_bugs_resolus());
            $this->nb_bugs_rejetes            = htmlspecialchars($data->getNb_bugs_rejetes());
            $this->nb_idees_soumises          = htmlspecialchars($data->getNb_idees_soumises());
            $this->nb_idees_en_charge         = htmlspecialchars($data->getNb_idees_en_charge());
            $this->nb_idees_terminees         = htmlspecialchars($data->getNb_idees_terminees());
        }

        // Getters et Setters pour l'objet StatistiquesAdmin
        // Identifiant
        public function setIdentifiant($identifiant)
        {
            $this->identifiant = $identifiant;
        }

        public function getIdentifiant()
        {
            return $this->identifiant;
        }

        // Pseudo
        public function setPseudo($pseudo)
        {
            $this->pseudo = $pseudo;
        }

        public function getPseudo()
        {
            return $this->pseudo;
        }

        // Nombre de films ajoutés
        public function setNb_films_ajoutes($nb_films_ajoutes)
        {
            $this->nb_films_ajoutes = $nb_films_ajoutes;
        }

        public function getNb_films_ajoutes()
        {
            return $this->nb_films_ajoutes;
        }

        // Nombre de commentaires des films
        public function setNb_films_comments($nb_films_comments)
        {
            $this->nb_films_comments = $nb_films_comments;
        }

        public function getNb_films_comments()
        {
            return $this->nb_films_comments;
        }

        // Nombre de restaurants ajoutés
        public function setNb_restaurants_ajoutes($nb_restaurants_ajoutes)
        {
            $this->nb_restaurants_ajoutes = $nb_restaurants_ajoutes;
        }

        public function getNb_restaurants_ajoutes()
        {
            return $this->nb_restaurants_ajoutes;
        }

        // Nombre de réservations de restaurants
        public function setNb_reservations($nb_reservations)
        {
            $this->nb_reservations = $nb_reservations;
        }

        public function getNb_reservations()
        {
            return $this->nb_reservations;
        }

        // Nombre de gâteaux de la semaine
        public function setNb_gateaux_semaine($nb_gateaux_semaine)
        {
            $this->nb_gateaux_semaine = $nb_gateaux_semaine;
        }

        public function getNb_gateaux_semaine()
        {
            return $this->nb_gateaux_semaine;
        }

        // Nombre de recettes partagées
        public function setNb_recettes($nb_recettes)
        {
            $this->nb_recettes = $nb_recettes;
        }

        public function getNb_recettes()
        {
            return $this->nb_recettes;
        }

        // Bilan des dépenses
        public function setExpenses($expenses)
        {
            $this->expenses = $expenses;
        }

        public function getExpenses()
        {
            return $this->expenses;
        }

        // Nombre de phrases cultes ajoutées
        public function setNb_collectors($nb_collectors)
        {
            $this->nb_collectors = $nb_collectors;
        }

        public function getNb_collectors()
        {
            return $this->nb_collectors;
        }

        // Nombre de parcours ajoutées
        public function setNb_parcours_ajoutes($nb_parcours_ajoutes)
        {
            $this->nb_parcours_ajoutes = $nb_parcours_ajoutes;
        }

        public function getNb_parcours_ajoutes()
        {
            return $this->nb_parcours_ajoutes;
        }

        // Nombre de participations aux parcours
        public function setNb_parcours_participations($nb_parcours_participations)
        {
            $this->nb_parcours_participations = $nb_parcours_participations;
        }

        public function getNb_parcours_participations()
        {
            return $this->nb_parcours_participations;
        }
        
        // Nombre de bugs soumis
        public function setNb_bugs_soumis($nb_bugs_soumis)
        {
            $this->nb_bugs_soumis = $nb_bugs_soumis;
        }

        public function getNb_bugs_soumis()
        {
            return $this->nb_bugs_soumis;
        }

        // Nombre de bugs résolus
        public function setNb_bugs_resolus($nb_bugs_resolus)
        {
            $this->nb_bugs_resolus = $nb_bugs_resolus;
        }

        public function getNb_bugs_resolus()
        {
            return $this->nb_bugs_resolus;
        }

        // Nombre de bugs rejetés
        public function setNb_bugs_rejetes($nb_bugs_rejetes)
        {
            $this->nb_bugs_rejetes = $nb_bugs_rejetes;
        }

        public function getNb_bugs_rejetes()
        {
            return $this->nb_bugs_rejetes;
        }

        // Nombre d'idées soumises
        public function setNb_idees_soumises($nb_idees_soumises)
        {
            $this->nb_idees_soumises = $nb_idees_soumises;
        }

        public function getNb_idees_soumises()
        {
            return $this->nb_idees_soumises;
        }

        // Nombre d'idées en charge
        public function setNb_idees_en_charge($nb_idees_en_charge)
        {
            $this->nb_idees_en_charge = $nb_idees_en_charge;
        }

        public function getNb_idees_en_charge()
        {
            return $this->nb_idees_en_charge;
        }

        // Nombre d'idées en charge
        public function setNb_idees_terminees($nb_idees_terminees)
        {
            $this->nb_idees_terminees = $nb_idees_terminees;
        }

        public function getNb_idees_terminees()
        {
            return $this->nb_idees_terminees;
        }
    }

    class TotalStatistiquesAdmin
    {
        private $nb_films_ajoutes_total;
        private $nb_films_comments_total;
        private $nb_restaurants_ajoutes_total;
        private $nb_reservations_total;
        private $nb_gateaux_semaine_total;
        private $nb_recettes_total;
        private $expenses_no_parts;
        private $expenses_total;
        private $nb_collectors_total;
        private $nb_parcours_ajoutes_total;
        private $nb_parcours_participations_total;
        private $nb_bugs_soumis_total;
        private $nb_bugs_resolus_total;
        private $nb_bugs_rejetes_total;
        private $nb_idees_soumises_total;
        private $nb_idees_en_charge_total;
        private $nb_idees_terminees_total;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->nb_films_ajoutes_total           = 0;
            $this->nb_films_comments_total          = 0;
            $this->nb_restaurants_ajoutes_total     = 0;
            $this->nb_reservations_total            = 0;
            $this->nb_gateaux_semaine_total         = 0;
            $this->nb_recettes_total                = 0;
            $this->expenses_no_parts                = 0;
            $this->expenses_total                   = 0;
            $this->nb_collectors_total              = 0;
            $this->nb_parcours_ajoutes_total        = 0;
            $this->nb_parcours_participations_total = 0;
            $this->nb_bugs_soumis_total             = 0;
            $this->nb_bugs_resolus_total            = 0;
            $this->nb_bugs_rejetes_total            = 0;
            $this->nb_idees_soumises_total          = 0;
            $this->nb_idees_en_charge_total         = 0;
            $this->nb_idees_terminees_total         = 0;
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $totalStatistiquesAdmin = new self();
            $totalStatistiquesAdmin->fillSecureData($data);

            return $totalStatistiquesAdmin;
        }

        protected function fillSecureData($data)
        {
            $this->nb_films_ajoutes_total           = htmlspecialchars($data->getNb_films_ajoutes_total());
            $this->nb_films_comments_total          = htmlspecialchars($data->getNb_films_comments_total());
            $this->nb_restaurants_ajoutes_total     = htmlspecialchars($data->getNb_restaurants_ajoutes_total());
            $this->nb_reservations_total            = htmlspecialchars($data->getNb_reservations_total());
            $this->nb_gateaux_semaine_total         = htmlspecialchars($data->getNb_gateaux_semaine_total());
            $this->nb_recettes_total                = htmlspecialchars($data->getNb_recettes_total());
            $this->expenses_no_parts                = htmlspecialchars($data->getExpenses_no_parts());
            $this->expenses_total                   = htmlspecialchars($data->getExpenses_total());
            $this->nb_collectors_total              = htmlspecialchars($data->getNb_collectors_total());
            $this->nb_parcours_ajoutes_total        = htmlspecialchars($data->getNb_parcours_ajoutes_total());
            $this->nb_parcours_participations_total = htmlspecialchars($data->getNb_parcours_participations_total());
            $this->nb_bugs_soumis_total             = htmlspecialchars($data->getNb_bugs_soumis_total());
            $this->nb_bugs_resolus_total            = htmlspecialchars($data->getNb_bugs_resolus_total());
            $this->nb_bugs_rejetes_total            = htmlspecialchars($data->getNb_bugs_rejetes_total());
            $this->nb_idees_soumises_total          = htmlspecialchars($data->getNb_idees_soumises_total());
            $this->nb_idees_en_charge_total         = htmlspecialchars($data->getNb_idees_en_charge_total());
            $this->nb_idees_terminees_total         = htmlspecialchars($data->getNb_idees_terminees_total());
        }

        // Getters et Setters pour l'objet TotalStatistiquesAdmin
        // Nombre de films ajoutés
        public function setNb_films_ajoutes_total($nb_films_ajoutes_total)
        {
            $this->nb_films_ajoutes_total = $nb_films_ajoutes_total;
        }

        public function getNb_films_ajoutes_total()
        {
            return $this->nb_films_ajoutes_total;
        }

        // Nombre de commentaires des films
        public function setNb_films_comments_total($nb_films_comments_total)
        {
            $this->nb_films_comments_total = $nb_films_comments_total;
        }

        public function getNb_films_comments_total()
        {
            return $this->nb_films_comments_total;
        }

        // Nombre de restaurants ajoutés
        public function setNb_restaurants_ajoutes_total($nb_restaurants_ajoutes_total)
        {
            $this->nb_restaurants_ajoutes_total = $nb_restaurants_ajoutes_total;
        }

        public function getNb_restaurants_ajoutes_total()
        {
            return $this->nb_restaurants_ajoutes_total;
        }
        
        // Nombre de réservations de restaurants
        public function setNb_reservations_total($nb_reservations_total)
        {
            $this->nb_reservations_total = $nb_reservations_total;
        }

        public function getNb_reservations_total()
        {
            return $this->nb_reservations_total;
        }

        // Nombre de gâteaux de la semaine
        public function setNb_gateaux_semaine_total($nb_gateaux_semaine_total)
        {
            $this->nb_gateaux_semaine_total = $nb_gateaux_semaine_total;
        }

        public function getNb_gateaux_semaine_total()
        {
            return $this->nb_gateaux_semaine_total;
        }

        // Nombre de recettes partagées
        public function setNb_recettes_total($nb_recettes_total)
        {
            $this->nb_recettes_total = $nb_recettes_total;
        }

        public function getNb_recettes_total()
        {
            return $this->nb_recettes_total;
        }

        // Bilan des dépenses sans parts
        public function setExpenses_no_parts($expenses_no_parts)
        {
            $this->expenses_no_parts = $expenses_no_parts;
        }

        public function getExpenses_no_parts()
        {
            return $this->expenses_no_parts;
        }

        // Bilan des dépenses
        public function setExpenses_total($expenses_total)
        {
            $this->expenses_total = $expenses_total;
        }

        public function getExpenses_total()
        {
            return $this->expenses_total;
        }

        // Nombre de phrases cultes ajoutées
        public function setNb_collectors_total($nb_collectors_total)
        {
            $this->nb_collectors_total = $nb_collectors_total;
        }

        public function getNb_collectors_total()
        {
            return $this->nb_collectors_total;
        }

        // Nombre de parcours ajoutées
        public function setNb_parcours_ajoutes_total($nb_parcours_ajoutes_total)
        {
            $this->nb_parcours_ajoutes_total = $nb_parcours_ajoutes_total;
        }

        public function getNb_parcours_ajoutes_total()
        {
            return $this->nb_parcours_ajoutes_total;
        }

        // Nombre de participations aux parcours
        public function setNb_parcours_participations_total($nb_parcours_participations_total)
        {
            $this->nb_parcours_participations_total = $nb_parcours_participations_total;
        }

        public function getNb_parcours_participations_total()
        {
            return $this->nb_parcours_participations_total;
        }

        // Nombre de bugs soumis
        public function setNb_bugs_soumis_total($nb_bugs_soumis_total)
        {
            $this->nb_bugs_soumis_total = $nb_bugs_soumis_total;
        }

        public function getNb_bugs_soumis_total()
        {
            return $this->nb_bugs_soumis_total;
        }

        // Nombre de bugs résolus
        public function setNb_bugs_resolus_total($nb_bugs_resolus_total)
        {
            $this->nb_bugs_resolus_total = $nb_bugs_resolus_total;
        }

        public function getNb_bugs_resolus_total()
        {
            return $this->nb_bugs_resolus_total;
        }

        // Nombre de bugs rejetés
        public function setNb_bugs_rejetes_total($nb_bugs_rejetes_total)
        {
            $this->nb_bugs_rejetes_total = $nb_bugs_rejetes_total;
        }

        public function getNb_bugs_rejetes_total()
        {
            return $this->nb_bugs_rejetes_total;
        }

        // Nombre d'idées soumises
        public function setNb_idees_soumises_total($nb_idees_soumises_total)
        {
            $this->nb_idees_soumises_total = $nb_idees_soumises_total;
        }

        public function getNb_idees_soumises_total()
        {
            return $this->nb_idees_soumises_total;
        }

        // Nombre d'idées en charge
        public function setNb_idees_en_charge_total($nb_idees_en_charge_total)
        {
            $this->nb_idees_en_charge_total = $nb_idees_en_charge_total;
        }

        public function getNb_idees_en_charge_total()
        {
            return $this->nb_idees_en_charge_total;
        }

        // Nombre d'idées en charge
        public function setNb_idees_terminees_total($nb_idees_terminees_total)
        {
            $this->nb_idees_terminees_total = $nb_idees_terminees_total;
        }

        public function getNb_idees_terminees_total()
        {
            return $this->nb_idees_terminees_total;
        }
    }
?>