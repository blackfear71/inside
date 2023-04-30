<?php
    class Calendrier
    {
        private $id;
        private $to_delete;
        private $team;
        private $month;
        private $year;
        private $title;
        private $calendar;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->id        = 0;
            $this->to_delete = '';
            $this->team      = '';
            $this->month     = '';
            $this->year      = '';
            $this->title     = '';
            $this->calendar  = '';
        }

        // Constructeur de l'objet Calendrier en fonction des données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $calendar = new self();
            $calendar->fillWithData($data);

            return $calendar;
        }

        protected function fillWithData($data)
        {
            if (isset($data['id']))
                $this->id        = $data['id'];

            if (isset($data['to_delete']))
                $this->to_delete = $data['to_delete'];

            if (isset($data['team']))
                $this->team      = $data['team'];

            if (isset($data['month']))
                $this->month     = $data['month'];

            if (isset($data['year']))
                $this->year      = $data['year'];

            if (isset($data['calendar']))
                $this->calendar  = $data['calendar'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $calendar = new self();
            $calendar->fillSecureData($data);

            return $calendar;
        }

        protected function fillSecureData($data)
        {
            $this->id        = $data->getId();
            $this->to_delete = htmlspecialchars($data->getTo_delete());
            $this->team      = $data->getTeam();
            $this->month     = htmlspecialchars($data->getMonth());
            $this->year      = htmlspecialchars($data->getYear());
            $this->title     = htmlspecialchars($data->getTitle());
            $this->calendar  = htmlspecialchars($data->getCalendar());
        }

        // Getters et Setters pour l'objet Calendrier
        // id
        public function setId($id)
        {
            $this->id = $id;
        }

        public function getId()
        {
            return $this->id;
        }

        // Indicateur suppression
        public function setTo_delete($to_delete)
        {
            $this->to_delete = $to_delete;
        }

        public function getTo_delete()
        {
            return $this->to_delete;
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

        // Mois
        public function setMonth($month)
        {
            $this->month = $month;
        }

        public function getMonth()
        {
            return $this->month;
        }

        // Année
        public function setYear($year)
        {
            $this->year = $year;
        }

        public function getYear()
        {
            return $this->year;
        }

        // Calendrier
        public function setCalendar($calendar)
        {
            $this->calendar = $calendar;
        }

        public function getCalendar()
        {
            return $this->calendar;
        }

        // Titre
        public function setTitle($title)
        {
            $this->title = $title;
        }

        public function getTitle()
        {
            return $this->title;
        }
    }

    class Annexe
    {
        private $id;
        private $to_delete;
        private $team;
        private $annexe;
        private $title;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->id        = 0;
            $this->to_delete = '';
            $this->team      = '';
            $this->annexe    = '';
            $this->title     = '';
        }

        // Constructeur de l'objet Annexe en fonction des données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $annexe = new self();
            $annexe->fillWithData($data);

            return $annexe;
        }

        protected function fillWithData($data)
        {
            if (isset($data['id']))
                $this->id        = $data['id'];

            if (isset($data['to_delete']))
                $this->to_delete = $data['to_delete'];

            if (isset($data['team']))
                $this->team      = $data['team'];

            if (isset($data['annexe']))
                $this->annexe    = $data['annexe'];

            if (isset($data['title']))
                $this->title     = $data['title'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $annexe = new self();
            $annexe->fillSecureData($data);

            return $annexe;
        }

        protected function fillSecureData($data)
        {
            $this->id        = $data->getId();
            $this->to_delete = htmlspecialchars($data->getTo_delete());
            $this->team      = $data->getTeam();
            $this->annexe    = htmlspecialchars($data->getAnnexe());
            $this->title     = htmlspecialchars($data->getTitle());
        }

        // Getters et Setters pour l'objet Annexe
        // id
        public function setId($id)
        {
            $this->id = $id;
        }

        public function getId()
        {
            return $this->id;
        }

        // Indicateur suppression
        public function setTo_delete($to_delete)
        {
            $this->to_delete = $to_delete;
        }

        public function getTo_delete()
        {
            return $this->to_delete;
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

        // Annexe
        public function setAnnexe($annexe)
        {
            $this->annexe = $annexe;
        }

        public function getAnnexe()
        {
            return $this->annexe;
        }

        // Titre
        public function setTitle($title)
        {
            $this->title = $title;
        }

        public function getTitle()
        {
            return $this->title;
        }
    }

    class AutorisationCalendriers
    {
        private $identifiant;
        private $pseudo;
        private $team;
        private $manage_calendars;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->identifiant      = '';
            $this->pseudo           = '';
            $this->team             = '';
            $this->manage_calendars = '';
        }

        // Constructeur de l'objet AutorisationCalendriers en fonction des données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $autorisation = new self();
            $autorisation->fillWithData($data);

            return $autorisation;
        }

        protected function fillWithData($data)
        {
            if (isset($data['identifiant']))
                $this->identifiant      = $data['identifiant'];

            if (isset($data['pseudo']))
                $this->pseudo           = $data['pseudo'];

            if (isset($data['team']))
                $this->team             = $data['team'];

            if (isset($data['manage_calendars']))
                $this->manage_calendars = $data['manage_calendars'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $autorisationCalendriers = new self();
            $autorisationCalendriers->fillSecureData($data);

            return $autorisationCalendriers;
        }

        protected function fillSecureData($data)
        {
            $this->identifiant      = htmlspecialchars($data->getIdentifiant());
            $this->pseudo           = htmlspecialchars($data->getPseudo());
            $this->team             = $data->getTeam();
            $this->manage_calendars = htmlspecialchars($data->getManage_calendars());
        }

        // Getters et Setters pour l'objet AutorisationCalendriers
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

        // Equipe
        public function setTeam($team)
        {
            $this->team = $team;
        }

        public function getTeam()
        {
            return $this->team;
        }

        // Autorisation gestion calendriers
        public function setManage_calendars($manage_calendars)
        {
            $this->manage_calendars = $manage_calendars;
        }

        public function getManage_calendars()
        {
            return $this->manage_calendars;
        }
    }

    class CalendarParameters
    {
        private $month;
        private $year;
        private $picture;
        private $holidays;
        private $vacations;
        private $color;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->month     = '';
            $this->year      = '';
            $this->picture   = '';
            $this->holidays  = 'Y';
            $this->vacations = 'b';
            $this->color     = 'R';
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $calendarParameters = new self();
            $calendarParameters->fillSecureData($data);

            return $calendarParameters;
        }

        protected function fillSecureData($data)
        {
            $this->month     = htmlspecialchars($data->getMonth());
            $this->year      = htmlspecialchars($data->getYear());
            $this->picture   = htmlspecialchars($data->getPicture());
            $this->holidays  = htmlspecialchars($data->getHolidays());
            $this->vacations = htmlspecialchars($data->getVacations());
            $this->color     = htmlspecialchars($data->getColor());
        }

        // Getters et Setters pour l'objet CalendarParameters
        // Mois
        public function setMonth($month)
        {
            $this->month = $month;
        }

        public function getMonth()
        {
            return $this->month;
        }

        // Année
        public function setYear($year)
        {
            $this->year = $year;
        }

        public function getYear()
        {
            return $this->year;
        }

        // Image
        public function setPicture($picture)
        {
            $this->picture = $picture;
        }

        public function getPicture()
        {
            return $this->picture;
        }

        // Jours fériés
        public function setHolidays($holidays)
        {
            $this->holidays = $holidays;
        }

        public function getHolidays()
        {
            return $this->holidays;
        }

        // Vacances scolaires
        public function setVacations($vacations)
        {
            $this->vacations = $vacations;
        }

        public function getVacations()
        {
            return $this->vacations;
        }

        // Couleur
        public function setColor($color)
        {
            $this->color = $color;
        }

        public function getColor()
        {
            return $this->color;
        }
    }

    class AnnexeParameters
    {
        private $name;
        private $picture;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->name    = '';
            $this->picture = '';
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $annexeParameters = new self();
            $annexeParameters->fillSecureData($data);

            return $annexeParameters;
        }

        protected function fillSecureData($data)
        {
            $this->name    = htmlspecialchars($data->getName());
            $this->picture = htmlspecialchars($data->getPicture());
        }

        // Getters et Setters pour l'objet AnnexeParameters
        // Nom de l'image
        public function setName($name)
        {
            $this->name = $name;
        }

        public function getName()
        {
            return $this->name;
        }

        // Image
        public function setPicture($picture)
        {
            $this->picture = $picture;
        }

        public function getPicture()
        {
            return $this->picture;
        }
    }
?>