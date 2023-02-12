<?php
    class Restaurant
    {
        private $id;
        private $team;
        private $name;
        private $picture;
        private $types;
        private $location;
        private $phone;
        private $opened;
        private $min_price;
        private $max_price;
        private $website;
        private $plan;
        private $lafourchette;
        private $description;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->id           = 0;
            $this->team         = '';
            $this->name         = '';
            $this->picture      = '';
            $this->types        = '';
            $this->location     = '';
            $this->phone        = '';
            $this->opened       = '';
            $this->min_price    = '';
            $this->max_price    = '';
            $this->website      = '';
            $this->plan         = '';
            $this->lafourchette = '';
            $this->description  = '';
        }

        // Constructeur de l'objet Restaurant en fonction des données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $restaurant = new self();
            $restaurant->fill($data);

            return $restaurant;
        }

        protected function fill($data)
        {
            if (isset($data['id']))
                $this->id           = $data['id'];

            if (isset($data['team']))
                $this->team         = $data['team'];

            if (isset($data['name']))
                $this->name         = $data['name'];

            if (isset($data['picture']))
                $this->picture      = $data['picture'];

            if (isset($data['types']))
                $this->types        = $data['types'];

            if (isset($data['location']))
                $this->location     = $data['location'];

            if (isset($data['phone']))
                $this->phone        = $data['phone'];

            if (isset($data['opened']))
                $this->opened       = $data['opened'];

            if (isset($data['min_price']))
                $this->min_price    = $data['min_price'];

            if (isset($data['max_price']))
                $this->max_price    = $data['max_price'];

            if (isset($data['website']))
                $this->website      = $data['website'];

            if (isset($data['plan']))
                $this->plan         = $data['plan'];

            if (isset($data['lafourchette']))
                $this->lafourchette = $data['lafourchette'];

            if (isset($data['description']))
                $this->description  = $data['description'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            //$data->setTeam(htmlspecialchars($data->getTeam()));
            $data->setName(htmlspecialchars($data->getName()));
            $data->setPicture(htmlspecialchars($data->getPicture()));
            $data->setTypes(htmlspecialchars($data->getTypes()));
            $data->setLocation(htmlspecialchars($data->getLocation()));
            $data->setPhone(htmlspecialchars($data->getPhone()));
            $data->setOpened(htmlspecialchars($data->getOpened()));
            $data->setMin_price(htmlspecialchars($data->getMin_price()));
            $data->setMax_price(htmlspecialchars($data->getMax_price()));
            $data->setWebsite(htmlspecialchars($data->getWebsite()));
            $data->setPlan(htmlspecialchars($data->getPlan()));
            $data->setLafourchette(htmlspecialchars($data->getLafourchette()));
            $data->setDescription(htmlspecialchars($data->getDescription()));
        }

        // Getters et Setters pour l'objet Restaurant
        // id
        public function setId($id)
        {
            $this->id = $id;
        }

        public function getId()
        {
            return $this->id;
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

        // Nom du restaurant
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

        // Types de restaurant
        public function setTypes($types)
        {
            $this->types = $types;
        }

        public function getTypes()
        {
            return $this->types;
        }

        // Lieu
        public function setLocation($location)
        {
            $this->location = $location;
        }

        public function getLocation()
        {
            return $this->location;
        }

        // Numéro de téléphone
        public function setPhone($phone)
        {
            $this->phone = $phone;
        }

        public function getPhone()
        {
            return $this->phone;
        }

        // Jours d'ouverture
        public function setOpened($opened)
        {
            $this->opened = $opened;
        }

        public function getOpened()
        {
            return $this->opened;
        }

        // Prix minimum
        public function setMin_price($min_price)
        {
            $this->min_price = $min_price;
        }

        public function getMin_price()
        {
            return $this->min_price;
        }

        // Prix maximum
        public function setMax_price($max_price)
        {
            $this->max_price = $max_price;
        }

        public function getMax_price()
        {
            return $this->max_price;
        }

        // Site web
        public function setWebsite($website)
        {
            $this->website = $website;
        }

        public function getWebsite()
        {
            return $this->website;
        }

        // Plan
        public function setPlan($plan)
        {
            $this->plan = $plan;
        }

        public function getPlan()
        {
            return $this->plan;
        }

        // LaFourchette
        public function setLafourchette($lafourchette)
        {
            $this->lafourchette = $lafourchette;
        }

        public function getLafourchette()
        {
            return $this->lafourchette;
        }

        // Description
        public function setDescription($description)
        {
            $this->description = $description;
        }

        public function getDescription()
        {
            return $this->description;
        }
    }

    class Proposition
    {
        private $id;
        private $id_restaurant;
        private $team;
        private $name;
        private $picture;
        private $location;
        private $nb_participants;
        private $classement;
        private $determined;
        private $date;
        private $caller;
        private $pseudo;
        private $avatar;
        private $reserved;
        private $types;
        private $phone;
        private $website;
        private $plan;
        private $lafourchette;
        private $opened;
        private $min_price;
        private $max_price;
        private $description;
        private $details;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->id              = 0;
            $this->id_restaurant   = '';
            $this->team            = '';
            $this->name            = '';
            $this->picture         = '';
            $this->location        = '';
            $this->nb_participants = 0;
            $this->classement      = 0;
            $this->determined      = 'N';
            $this->date            = '';
            $this->caller          = '';
            $this->pseudo          = '';
            $this->avatar          = '';
            $this->reserved        = '';
            $this->types           = '';
            $this->phone           = '';
            $this->website         = '';
            $this->plan            = '';
            $this->lafourchette    = '';
            $this->opened          = 'Y;Y;Y;Y;Y;';
            $this->min_price       = '';
            $this->max_price       = '';
            $this->description     = '';
            $this->details         = array();
        }

        // Constructeur de l'objet Proposition en fonction des données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $proposition = new self();
            $proposition->fill($data);

            return $proposition;
        }

        protected function fill($data)
        {
            if (isset($data['id']))
                $this->id            = $data['id'];

            if (isset($data['id_restaurant']))
                $this->id_restaurant = $data['id_restaurant'];

            if (isset($data['team']))
                $this->team          = $data['team'];

            if (isset($data['date']))
                $this->date          = $data['date'];

            if (isset($data['caller']))
                $this->caller        = $data['caller'];

            if (isset($data['reserved']))
                $this->reserved      = $data['reserved'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            //$data->setTeam(htmlspecialchars($data->getTeam()));
            $data->setName(htmlspecialchars($data->getName()));
            $data->setPicture(htmlspecialchars($data->getPicture()));
            $data->setLocation(htmlspecialchars($data->getLocation()));
            $data->setNb_participants(htmlspecialchars($data->getNb_participants()));
            $data->setClassement(htmlspecialchars($data->getClassement()));
            $data->setDetermined(htmlspecialchars($data->getDetermined()));
            $data->setDate(htmlspecialchars($data->getDate()));
            $data->setCaller(htmlspecialchars($data->getCaller()));
            $data->setPseudo(htmlspecialchars($data->getPseudo()));
            $data->setAvatar(htmlspecialchars($data->getAvatar()));
            $data->setReserved(htmlspecialchars($data->getReserved()));
            $data->setTypes(htmlspecialchars($data->getTypes()));
            $data->setPhone(htmlspecialchars($data->getPhone()));
            $data->setWebsite(htmlspecialchars($data->getWebsite()));
            $data->setPlan(htmlspecialchars($data->getPlan()));
            $data->setLafourchette(htmlspecialchars($data->getLafourchette()));
            $data->setOpened(htmlspecialchars($data->getOpened()));
            $data->setMin_price(htmlspecialchars($data->getMin_price()));
            $data->setMax_price(htmlspecialchars($data->getMax_price()));
            $data->setDescription(htmlspecialchars($data->getDescription()));

            foreach ($data->getDetails() as $detailsUser)
            {
                DetailsProposition::secureData($detailsUser);
            }
        }

        // Getters et Setters pour l'objet Proposition
        // id
        public function setId($id)
        {
            $this->id = $id;
        }

        public function getId()
        {
            return $this->id;
        }

        // id restaurant
        public function setId_restaurant($id_restaurant)
        {
            $this->id_restaurant = $id_restaurant;
        }

        public function getId_restaurant()
        {
            return $this->id_restaurant;
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

        // Restaurant
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

        // Lieu
        public function setLocation($location)
        {
            $this->location = $location;
        }

        public function getLocation()
        {
            return $this->location;
        }

        // Nombre de participants
        public function setNb_participants($nb_participants)
        {
            $this->nb_participants = $nb_participants;
        }

        public function getNb_participants()
        {
            return $this->nb_participants;
        }

        // Classement
        public function setClassement($classement)
        {
            $this->classement = $classement;
        }

        public function getClassement()
        {
            return $this->classement;
        }

        // Proposition déterminée
        public function setDetermined($determined)
        {
            $this->determined = $determined;
        }

        public function getDetermined()
        {
            return $this->determined;
        }

        // Date
        public function setDate($date)
        {
            $this->date = $date;
        }

        public function getDate()
        {
            return $this->date;
        }

        // Participant qui appelle
        public function setCaller($caller)
        {
            $this->caller = $caller;
        }

        public function getCaller()
        {
            return $this->caller;
        }

        // Pseudo de celui qui appelle
        public function setPseudo($pseudo)
        {
            $this->pseudo = $pseudo;
        }

        public function getPseudo()
        {
            return $this->pseudo;
        }

        // Avatar de celui qui appelle
        public function setAvatar($avatar)
        {
            $this->avatar = $avatar;
        }

        public function getAvatar()
        {
            return $this->avatar;
        }

        // Indicateur réservation
        public function setReserved($reserved)
        {
            $this->reserved = $reserved;
        }

        public function getReserved()
        {
            return $this->reserved;
        }

        // Types de restaurant
        public function setTypes($types)
        {
            $this->types = $types;
        }

        public function getTypes()
        {
            return $this->types;
        }

        // Numéro de téléphone
        public function setPhone($phone)
        {
            $this->phone = $phone;
        }

        public function getPhone()
        {
            return $this->phone;
        }

        // Site web
        public function setWebsite($website)
        {
            $this->website = $website;
        }

        public function getWebsite()
        {
            return $this->website;
        }

        // Plan
        public function setPlan($plan)
        {
            $this->plan = $plan;
        }

        public function getPlan()
        {
            return $this->plan;
        }

        // LaFourchette
        public function setLafourchette($lafourchette)
        {
            $this->lafourchette = $lafourchette;
        }

        public function getLafourchette()
        {
            return $this->lafourchette;
        }

        // Jours d'ouverture
        public function setOpened($opened)
        {
            $this->opened = $opened;
        }

        public function getOpened()
        {
            return $this->opened;
        }

        // Prix minimum
        public function setMin_price($min_price)
        {
            $this->min_price = $min_price;
        }

        public function getMin_price()
        {
            return $this->min_price;
        }

        // Prix maximum
        public function setMax_price($max_price)
        {
            $this->max_price = $max_price;
        }

        public function getMax_price()
        {
            return $this->max_price;
        }

        // Description du restaurant
        public function setDescription($description)
        {
            $this->description = $description;
        }

        public function getDescription()
        {
            return $this->description;
        }

        // Détails
        public function setDetails($details)
        {
            $this->details = $details;
        }

        public function getDetails()
        {
            return $this->details;
        }
    }

    class DetailsProposition
    {
        private $identifiant;
        private $pseudo;
        private $avatar;
        private $transports;
        private $horaire;
        private $menu;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->identifiant = '';
            $this->pseudo      = '';
            $this->avatar      = '';
            $this->transports  = '';
            $this->horaire     = '';
            $this->menu        = '';
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $data->setIdentifiant(htmlspecialchars($data->getIdentifiant()));
            $data->setPseudo(htmlspecialchars($data->getPseudo()));
            $data->setAvatar(htmlspecialchars($data->getAvatar()));
            $data->setTransports(htmlspecialchars($data->getTransports()));
            $data->setHoraire(htmlspecialchars($data->getHoraire()));
            $data->setMenu(htmlspecialchars($data->getMenu()));
        }

        // Getters et Setters pour l'objet Proposition
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

        // Avatar
        public function setAvatar($avatar)
        {
            $this->avatar = $avatar;
        }

        public function getAvatar()
        {
            return $this->avatar;
        }

        // Tranports
        public function setTransports($transports)
        {
            $this->transports = $transports;
        }

        public function getTransports()
        {
            return $this->transports;
        }

        // Horaire
        public function setHoraire($horaire)
        {
            $this->horaire = $horaire;
        }

        public function getHoraire()
        {
            return $this->horaire;
        }

        // Menu
        public function setMenu($menu)
        {
            $this->menu = $menu;
        }

        public function getMenu()
        {
            return $this->menu;
        }
    }

    class Choix
    {
        private $id;
        private $id_restaurant;
        private $team;
        private $identifiant;
        private $date;
        private $time;
        private $transports;
        private $menu;
        private $name;
        private $picture;
        private $location;
        private $opened;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->id            = 0;
            $this->id_restaurant = '';
            $this->team          = '';
            $this->identifiant   = '';
            $this->date          = '';
            $this->time          = '';
            $this->transports    = '';
            $this->menu          = '';
            $this->name          = '';
            $this->picture       = '';
            $this->location      = '';
            $this->opened        = '';
        }

        // Constructeur de l'objet Choix en fonction des données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $choix = new self();
            $choix->fill($data);

            return $choix;
        }

        protected function fill($data)
        {
            if (isset($data['id']))
                $this->id            = $data['id'];

            if (isset($data['id_restaurant']))
                $this->id_restaurant = $data['id_restaurant'];

            if (isset($data['team']))
                $this->team          = $data['team'];

            if (isset($data['identifiant']))
                $this->identifiant   = $data['identifiant'];

            if (isset($data['date']))
                $this->date          = $data['date'];

            if (isset($data['time']))
                $this->time          = $data['time'];

            if (isset($data['transports']))
                $this->transports    = $data['transports'];

            if (isset($data['menu']))
                $this->menu          = $data['menu'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            //$data->setTeam(htmlspecialchars($data->getTeam()));
            $data->setIdentifiant(htmlspecialchars($data->getIdentifiant()));
            $data->setDate(htmlspecialchars($data->getDate()));
            $data->setTime(htmlspecialchars($data->getTime()));
            $data->setTransports(htmlspecialchars($data->getTransports()));
            $data->setMenu(htmlspecialchars($data->getMenu()));
            $data->setName(htmlspecialchars($data->getName()));
            $data->setPicture(htmlspecialchars($data->getPicture()));
            $data->setLocation(htmlspecialchars($data->getLocation()));
            $data->setOpened(htmlspecialchars($data->getOpened()));
        }

        // Getters et Setters pour l'objet Choix
        // id
        public function setId($id)
        {
            $this->id = $id;
        }

        public function getId()
        {
            return $this->id;
        }

        // id restaurant
        public function setId_restaurant($id_restaurant)
        {
            $this->id_restaurant = $id_restaurant;
        }

        public function getId_restaurant()
        {
            return $this->id_restaurant;
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

        // Identifiant
        public function setIdentifiant($identifiant)
        {
            $this->identifiant = $identifiant;
        }

        public function getIdentifiant()
        {
            return $this->identifiant;
        }

        // Date
        public function setDate($date)
        {
            $this->date = $date;
        }

        public function getDate()
        {
            return $this->date;
        }

        // Heure
        public function setTime($time)
        {
            $this->time = $time;
        }

        public function getTime()
        {
            return $this->time;
        }

        // Transports
        public function setTransports($transports)
        {
            $this->transports = $transports;
        }

        public function getTransports()
        {
            return $this->transports;
        }

        // Menu
        public function setMenu($menu)
        {
            $this->menu = $menu;
        }

        public function getMenu()
        {
            return $this->menu;
        }

        // Restaurant
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

        // Lieu
        public function setLocation($location)
        {
            $this->location = $location;
        }

        public function getLocation()
        {
            return $this->location;
        }

        // Jours d'ouverture
        public function setOpened($opened)
        {
            $this->opened = $opened;
        }

        public function getOpened()
        {
            return $this->opened;
        }
    }
?>