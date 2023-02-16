<?php
    class Team
    {
        private $id;
        private $reference;
        private $team;
        private $activation;
        private $nombre_users;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->id           = 0;
            $this->reference    = '';
            $this->team         = '';
            $this->activation   = '';
            $this->nombre_users = 0;
        }

        // Constructeur de l'objet Team en fonction des données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $team = new self();
            $team->fillWithData($data);

            return $team;
        }

        protected function fillWithData($data)
        {
            if (isset($data['id']))
                $this->id         = $data['id'];

            if (isset($data['reference']))
                $this->reference  = $data['reference'];

            if (isset($data['team']))
                $this->team       = $data['team'];

            if (isset($data['activation']))
                $this->activation = $data['activation'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $team = new self();
            $team->fillSecureData($data);

            return $team;
        }

        protected function fillSecureData($data)
        {
            $this->id           = $data->getId();
            $this->reference    = $data->getReference();
            $this->team         = htmlspecialchars($data->getTeam());
            $this->activation   = htmlspecialchars($data->getActivation());
            $this->nombre_users = htmlspecialchars($data->getNombre_users());            
        }

        // Getters et Setters pour l'objet Team
        // id
        public function setId($id)
        {
            $this->id = $id;
        }

        public function getId()
        {
            return $this->id;
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

        // Nom de l'équipe
        public function setTeam($team)
        {
            $this->team = $team;
        }

        public function getTeam()
        {
            return $this->team;
        }

        // Indicateur d'activation
        public function setActivation($activation)
        {
            $this->activation = $activation;
        }

        public function getActivation()
        {
            return $this->activation;
        }

        // Nomnbre d'utilisateurs
        public function setNombre_users($nombre_users)
        {
            $this->nombre_users = $nombre_users;
        }

        public function getNombre_users()
        {
            return $this->nombre_users;
        }
    }
?>