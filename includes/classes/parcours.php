<?php
    class TableauDeBord
    {
        private $distanceMoyenne;
        private $tempsMoyen;
        private $vitesseMoyenne;
        private $cardioMoyen;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->distanceMoyenne = '';
            $this->tempsMoyen      = '';
            $this->vitesseMoyenne  = '';
            $this->cardioMoyen     = '';
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $tableauDeBord = new self();
            $tableauDeBord->fillSecureData($data);

            return $tableauDeBord;
        }

        protected function fillSecureData($data)
        {
            $this->distanceMoyenne = htmlspecialchars($data->getDistanceMoyenne());
            $this->tempsMoyen      = htmlspecialchars($data->getTempsMoyen());
            $this->vitesseMoyenne  = htmlspecialchars($data->getVitesseMoyenne());
            $this->cardioMoyen     = htmlspecialchars($data->getCardioMoyen());
        }

        // Getters et Setters pour l'objet TableauDeBord
        // Distance moyenne
        public function setDistanceMoyenne($distanceMoyenne)
        {
            $this->distanceMoyenne = $distanceMoyenne;
        }

        public function getDistanceMoyenne()
        {
            return $this->distanceMoyenne;
        }

        // Temps moyen
        public function setTempsMoyen($tempsMoyen)
        {
            $this->tempsMoyen = $tempsMoyen;
        }

        public function getTempsMoyen()
        {
            return $this->tempsMoyen;
        }

        // Vitesse moyenne
        public function setVitesseMoyenne($vitesseMoyenne)
        {
            $this->vitesseMoyenne = $vitesseMoyenne;
        }

        public function getVitesseMoyenne()
        {
            return $this->vitesseMoyenne;
        }

        // Cardio moyen
        public function setCardioMoyen($cardioMoyen)
        {
            $this->cardioMoyen = $cardioMoyen;
        }

        public function getCardioMoyen()
        {
            return $this->cardioMoyen;
        }
    }

    class Parcours
    {
        private $id;
        private $to_delete;
        private $team;
        private $identifiant_add;
        private $pseudo_add;
        private $identifiant_del;
        private $pseudo_del;
        private $name;
        private $distance;
        private $location;
        private $picture;
        private $document;
        private $type;
        private $runs;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->id              = 0;
            $this->to_delete       = '';
            $this->team            = '';
            $this->identifiant_add = '';
            $this->pseudo_add      = '';
            $this->identifiant_del = '';
            $this->pseudo_del      = '';
            $this->name            = '';
            $this->distance        = '';
            $this->location        = '';
            $this->picture         = '';
            $this->document        = '';
            $this->type            = '';
            $this->runs            = 0;
        }

        // Constructeur de l'objet Parcours en fonction de données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $parcours = new self();
            $parcours->fillWithData($data);

            return $parcours;
        }

        protected function fillWithData($data)
        {
            if (isset($data['id']))
                $this->id              = $data['id'];

            if (isset($data['to_delete']))
                $this->to_delete       = $data['to_delete'];

            if (isset($data['team']))
                $this->team            = $data['team'];

            if (isset($data['identifiant_add']))
                $this->identifiant_add = $data['identifiant_add'];
                
            if (isset($data['identifiant_del']))
                $this->identifiant_del = $data['identifiant_del'];

            if (isset($data['name']))
                $this->name            = $data['name'];

            if (isset($data['distance']))
                $this->distance        = $data['distance'];

            if (isset($data['location']))
                $this->location        = $data['location'];

            if (isset($data['picture']))
                $this->picture         = $data['picture'];

            if (isset($data['document']))
                $this->document        = $data['document'];
                
            if (isset($data['type']))
                $this->type            = $data['type'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $parcours = new self();
            $parcours->fillSecureData($data);

            return $parcours;
        }

        protected function fillSecureData($data)
        {
            $this->id              = $data->getId();
            $this->to_delete       = htmlspecialchars($data->getTo_delete());
            $this->team            = $data->getTeam();
            $this->identifiant_add = htmlspecialchars($data->getIdentifiant_add());
            $this->pseudo_add      = htmlspecialchars($data->getPseudo_add());
            $this->identifiant_del = htmlspecialchars($data->getIdentifiant_del());
            $this->pseudo_del      = htmlspecialchars($data->getPseudo_del());
            $this->name            = htmlspecialchars($data->getName());
            $this->distance        = htmlspecialchars($data->getDistance());
            $this->location        = htmlspecialchars($data->getLocation());
            $this->picture         = htmlspecialchars($data->getPicture());
            $this->document        = htmlspecialchars($data->getDocument());
            $this->type            = htmlspecialchars($data->getType());
            $this->runs            = htmlspecialchars($data->getRuns());
        }

        // Getters et Setters pour l'objet Parcours
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

        // Identifiant ajout
        public function setIdentifiant_add($identifiant_add)
        {
            $this->identifiant_add = $identifiant_add;
        }

        public function getIdentifiant_add()
        {
            return $this->identifiant_add;
        }

        // Pseudo ajout
        public function setPseudo_add($pseudo_add)
        {
            $this->pseudo_add = $pseudo_add;
        }

        public function getPseudo_add()
        {
            return $this->pseudo_add;
        }

        // Identifiant suppression
        public function setIdentifiant_del($identifiant_del)
        {
            $this->identifiant_del = $identifiant_del;
        }

        public function getIdentifiant_del()
        {
            return $this->identifiant_del;
        }

        // Pseudo suppression
        public function setPseudo_del($pseudo_del)
        {
            $this->pseudo_del = $pseudo_del;
        }

        public function getPseudo_del()
        {
            return $this->pseudo_del;
        }

        // Nom
        public function setName($name)
        {
            $this->name = $name;
        }

        public function getName()
        {
            return $this->name;
        }

        // Distance
        public function setDistance($distance)
        {
            $this->distance = $distance;
        }

        public function getDistance()
        {
            return $this->distance;
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

        // Image
        public function setPicture($picture)
        {
            $this->picture = $picture;
        }

        public function getPicture()
        {
            return $this->picture;
        }

        // Document
        public function setDocument($document)
        {
            $this->document = $document;
        }

        public function getDocument()
        {
            return $this->document;
        }

        // Type de document
        public function setType($type)
        {
            $this->type = $type;
        }

        public function getType()
        {
            return $this->type;
        }

        // Nombre de courses réalisées
        public function setRuns($runs)
        {
            $this->runs = $runs;
        }

        public function getRuns()
        {
            return $this->runs;
        }
    }

    class ParticipationCourse
    {
        private $id;
        private $id_parcours;
        private $nom_parcours;
        private $identifiant;
        private $pseudo;
        private $avatar;
        private $date;
        private $distance;
        private $time;
        private $speed;
        private $cardio;
        private $competition;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->id           = 0;
            $this->id_parcours  = 0;
            $this->nom_parcours = '';
            $this->identifiant  = '';
            $this->pseudo       = '';
            $this->avatar       = '';
            $this->date         = '';
            $this->distance     = '';
            $this->time         = '';
            $this->speed        = '';
            $this->cardio       = '';
            $this->competition  = '';
        }

        // Constructeur de l'objet ParticipationCourse en fonction de données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $participationCourse = new self();
            $participationCourse->fillWithData($data);

            return $participationCourse;
        }

        protected function fillWithData($data)
        {
            if (isset($data['id']))
                $this->id          = $data['id'];

            if (isset($data['id_parcours']))
                $this->id_parcours = $data['id_parcours'];

            if (isset($data['identifiant']))
                $this->identifiant = $data['identifiant'];

            if (isset($data['date']))
                $this->date        = $data['date'];

            if (isset($data['distance']))
                $this->distance    = $data['distance'];

            if (isset($data['time']))
                $this->time        = $data['time'];

            if (isset($data['speed']))
                $this->speed       = $data['speed'];

            if (isset($data['cardio']))
                $this->cardio      = $data['cardio'];

            if (isset($data['competition']))
                $this->competition = $data['competition'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $participationCourse = new self();
            $participationCourse->fillSecureData($data);

            return $participationCourse;
        }

        protected function fillSecureData($data)
        {
            $this->id           = $data->getId();
            $this->id_parcours  = $data->getId_parcours();
            $this->nom_parcours = htmlspecialchars($data->getNom_parcours());
            $this->identifiant  = htmlspecialchars($data->getIdentifiant());
            $this->pseudo       = htmlspecialchars($data->getPseudo());
            $this->avatar       = htmlspecialchars($data->getAvatar());
            $this->date         = htmlspecialchars($data->getDate());
            $this->distance     = htmlspecialchars($data->getDistance());
            $this->time         = htmlspecialchars($data->getTime());
            $this->speed        = htmlspecialchars($data->getSpeed());
            $this->cardio       = htmlspecialchars($data->getCardio());
            $this->competition  = htmlspecialchars($data->getCompetition());
        }

        // Getters et Setters pour l'objet ParticipationCourse
        // id
        public function setId($id)
        {
            $this->id = $id;
        }

        public function getId()
        {
            return $this->id;
        }

        // id parcours
        public function setId_parcours($id_parcours)
        {
            $this->id_parcours = $id_parcours;
        }

        public function getId_parcours()
        {
            return $this->id_parcours;
        }

        // Nom du parcours
        public function setNom_parcours($nom_parcours)
        {
            $this->nom_parcours = $nom_parcours;
        }

        public function getNom_parcours()
        {
            return $this->nom_parcours;
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
        
        // Date
        public function setDate($date)
        {
            $this->date = $date;
        }

        public function getDate()
        {
            return $this->date;
        }

        // Distance
        public function setDistance($distance)
        {
            $this->distance = $distance;
        }

        public function getDistance()
        {
            return $this->distance;
        }

        // Temps
        public function setTime($time)
        {
            $this->time = $time;
        }

        public function getTime()
        {
            return $this->time;
        }

        // Vitesse
        public function setSpeed($speed)
        {
            $this->speed = $speed;
        }

        public function getSpeed()
        {
            return $this->speed;
        }

        // Cardio
        public function setCardio($cardio)
        {
            $this->cardio = $cardio;
        }

        public function getCardio()
        {
            return $this->cardio;
        }

        // Compétition
        public function setCompetition($competition)
        {
            $this->competition = $competition;
        }

        public function getCompetition()
        {
            return $this->competition;
        }
    }
?>