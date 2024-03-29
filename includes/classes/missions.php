<?php
    class Mission
    {
        private $id;
        private $mission;
        private $reference;
        private $date_deb;
        private $date_fin;
        private $heure;
        private $objectif;
        private $description;
        private $explications;
        private $conclusion;
        private $statut;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->id           = 0;
            $this->mission      = '';
            $this->reference    = '';
            $this->date_deb     = '';
            $this->date_fin     = '';
            $this->heure        = '';
            $this->objectif     = '';
            $this->description  = '';
            $this->explications = '';
            $this->conclusion   = '';
            $this->statut       = '';
        }

        // Constructeur de l'objet Mission en fonction des données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $mission = new self();
            $mission->fillWithData($data);

            return $mission;
        }

        protected function fillWithData($data)
        {
            if (isset($data['id']))
                $this->id           = $data['id'];

            if (isset($data['mission']))
                $this->mission      = $data['mission'];

            if (isset($data['reference']))
                $this->reference    = $data['reference'];

            if (isset($data['date_deb']))
                $this->date_deb     = $data['date_deb'];

            if (isset($data['date_fin']))
                $this->date_fin     = $data['date_fin'];

            if (isset($data['heure']))
                $this->heure        = $data['heure'];

            if (isset($data['objectif']))
                $this->objectif     = $data['objectif'];

            if (isset($data['description']))
                $this->description  = $data['description'];

            if (isset($data['explications']))
                $this->explications = $data['explications'];

            if (isset($data['conclusion']))
                $this->conclusion   = $data['conclusion'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $mission = new self();
            $mission->fillSecureData($data);

            return $mission;
        }

        protected function fillSecureData($data)
        {
            $this->id           = $data->getId();
            $this->mission      = htmlspecialchars($data->getMission());
            $this->reference    = htmlspecialchars($data->getReference());
            $this->date_deb     = htmlspecialchars($data->getDate_deb());
            $this->date_fin     = htmlspecialchars($data->getDate_fin());
            $this->heure        = htmlspecialchars($data->getHeure());
            $this->objectif     = htmlspecialchars($data->getObjectif());
            $this->description  = htmlspecialchars($data->getDescription());
            $this->explications = htmlspecialchars($data->getExplications());
            $this->conclusion   = htmlspecialchars($data->getConclusion());
            $this->statut       = htmlspecialchars($data->getStatut());
        }

        // Getters et Setters pour l'objet Mission
        // id
        public function setId($id)
        {
            $this->id = $id;
        }

        public function getId()
        {
            return $this->id;
        }

        // Mission
        public function setMission($mission)
        {
            $this->mission = $mission;
        }

        public function getMission()
        {
            return $this->mission;
        }

        // Référence
        public function setReference($reference)
        {
            $this->reference = $reference;
        }

        public function getReference()
        {
            return $this->reference;
        }

        // Date début
        public function setDate_deb($date_deb)
        {
            $this->date_deb = $date_deb;
        }

        public function getDate_deb()
        {
            return $this->date_deb;
        }

        // Date fin
        public function setDate_fin($date_fin)
        {
            $this->date_fin = $date_fin;
        }

        public function getDate_fin()
        {
            return $this->date_fin;
        }

        // Heure
        public function setHeure($heure)
        {
            $this->heure = $heure;
        }

        public function getHeure()
        {
            return $this->heure;
        }

        // Objectif
        public function setObjectif($objectif)
        {
            $this->objectif = $objectif;
        }

        public function getObjectif()
        {
            return $this->objectif;
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

        // Explications
        public function setExplications($explications)
        {
            $this->explications = $explications;
        }

        public function getExplications()
        {
            return $this->explications;
        }

        // Conclusion
        public function setConclusion($conclusion)
        {
            $this->conclusion = $conclusion;
        }

        public function getConclusion()
        {
            return $this->conclusion;
        }

        // Statut mission
        public function setStatut($statut)
        {
            $this->statut = $statut;
        }

        public function getStatut()
        {
            return $this->statut;
        }
    }

    class ParticipantMission
    {
        private $identifiant;
        private $team;
        private $pseudo;
        private $avatar;
        private $total;
        private $rank;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->identifiant = '';
            $this->team        = '';
            $this->pseudo      = '';
            $this->avatar      = '';
            $this->total       = 0;
            $this->rank        = '';
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $participantMission = new self();
            $participantMission->fillSecureData($data);

            return $participantMission;
        }

        protected function fillSecureData($data)
        {
            $this->identifiant = htmlspecialchars($data->getIdentifiant());
            $this->team        = $data->getTeam();
            $this->pseudo      = htmlspecialchars($data->getPseudo());
            $this->avatar      = htmlspecialchars($data->getAvatar());
            $this->total       = htmlspecialchars($data->getTotal());
            $this->rank        = htmlspecialchars($data->getRank());
        }

        // Getters et Setters pour l'objet ParticipantMission
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

        // Total des objets trouvés
        public function setTotal($total)
        {
            $this->total = $total;
        }

        public function getTotal()
        {
            return $this->total;
        }

        // Rang
        public function setRank($rank)
        {
            $this->rank = $rank;
        }

        public function getRank()
        {
            return $this->rank;
        }
    }
?>