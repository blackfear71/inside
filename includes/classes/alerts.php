<?php
    class Alerte
    {
        private $id;
        private $category;
        private $type;
        private $alert;
        private $message;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->id       = 0;
            $this->category = '';
            $this->type     = '';
            $this->alert    = '';
            $this->message  = '';
        }

        // Constructeur de l'objet Alerte en fonction des données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $alerte = new self();
            $alerte->fillWithData($data);

            return $alerte;
        }

        protected function fillWithData($data)
        {
            if (isset($data['id']))
                $this->id       = $data['id'];

            if (isset($data['category']))
                $this->category = $data['category'];

            if (isset($data['type']))
                $this->type     = $data['type'];

            if (isset($data['alert']))
                $this->alert    = $data['alert'];

            if (isset($data['message']))
                $this->message  = $data['message'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $alerte = new self();
            $alerte->fillSecureData($data);

            return $alerte;
        }

        protected function fillSecureData($data)
        {
            $this->id       = $data->getId();
            $this->category = htmlspecialchars($data->getCategory());
            $this->type     = htmlspecialchars($data->getType());
            $this->alert    = htmlspecialchars($data->getAlert());
            $this->message  = htmlspecialchars($data->getMessage());
        }

        // Getters et Setters pour l'objet Alerte
        // id
        public function setId($id)
        {
            $this->id = $id;
        }

        public function getId()
        {
            return $this->id;
        }

        // Catégorie
        public function setCategory($category)
        {
            $this->category = $category;
        }

        public function getCategory()
        {
            return $this->category;
        }

        // Type
        public function setType($type)
        {
            $this->type = $type;
        }

        public function getType()
        {
            return $this->type;
        }

        // Alerte
        public function setAlert($alert)
        {
            $this->alert = $alert;
        }

        public function getAlert()
        {
            return $this->alert;
        }

        // Message
        public function setMessage($message)
        {
            $this->message = $message;
        }

        public function getMessage()
        {
            return $this->message;
        }
    }
?>