<?php
    class BugEvolution
    {
        private $id;
        private $subject;
        private $date;
        private $identifiant;
        private $pseudo;
        private $avatar;
        private $team;
        private $content;
        private $picture;
        private $resolution;
        private $type;
        private $resolved;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->id          = 0;
            $this->subject     = '';
            $this->date        = '';
            $this->identifiant = '';
            $this->pseudo      = '';
            $this->avatar      = '';
            $this->team        = '';
            $this->content     = '';
            $this->picture     = '';
            $this->resolution  = '';
            $this->type        = '';
            $this->resolved    = '';
        }

        // Constructeur de l'objet BugEvolution en fonction des données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $bugs = new self();
            $bugs->fillWithData($data);

            return $bugs;
        }

        protected function fillWithData($data)
        {
            if (isset($data['id']))
                $this->id          = $data['id'];

            if (isset($data['subject']))
                $this->subject     = $data['subject'];

            if (isset($data['date']))
                $this->date        = $data['date'];

            if (isset($data['identifiant']))
                $this->identifiant = $data['identifiant'];

            if (isset($data['team']))
                $this->team        = $data['team'];

            if (isset($data['content']))
                $this->content     = $data['content'];

            if (isset($data['picture']))
                $this->picture     = $data['picture'];

            if (isset($data['resolution']))
                $this->resolution  = $data['resolution'];

            if (isset($data['type']))
                $this->type        = $data['type'];

            if (isset($data['resolved']))
                $this->resolved    = $data['resolved'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $bugs = new self();
            $bugs->fillSecureData($data);

            return $bugs;
        }

        protected function fillSecureData($data)
        {
            $this->id          = $data->getId();
            $this->subject     = htmlspecialchars($data->getSubject());
            $this->date        = htmlspecialchars($data->getDate());
            $this->identifiant = htmlspecialchars($data->getIdentifiant());
            $this->pseudo      = htmlspecialchars($data->getPseudo());
            $this->avatar      = htmlspecialchars($data->getAvatar());
            $this->team        = $data->getTeam();
            $this->content     = htmlspecialchars($data->getContent());
            $this->picture     = htmlspecialchars($data->getPicture());
            $this->resolution  = htmlspecialchars($data->getResolution());
            $this->type        = htmlspecialchars($data->getType());
            $this->resolved    = htmlspecialchars($data->getResolved());
        }        

        // Getters et Setters pour l'objet BugEvolution
        // id
        public function setId($id)
        {
            $this->id = $id;
        }

        public function getId()
        {
            return $this->id;
        }

        // Sujet
        public function setSubject($subject)
        {
            $this->subject = $subject;
        }

        public function getSubject()
        {
            return $this->subject;
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

        // Identifiant
        public function setIdentifiant($identifiant)
        {
            $this->identifiant = $identifiant;
        }

        public function getIdentifiant()
        {
            return $this->identifiant;
        }

        // Pseudo auteur
        public function setPseudo($pseudo)
        {
            $this->pseudo = $pseudo;
        }

        public function getPseudo()
        {
            return $this->pseudo;
        }

        // Avatar auteur
        public function setAvatar($avatar)
        {
            $this->avatar = $avatar;
        }

        public function getAvatar()
        {
            return $this->avatar;
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

        // Contenu
        public function setContent($content)
        {
            $this->content = $content;
        }

        public function getContent()
        {
            return $this->content;
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

        // Image
        public function setResolution($resolution)
        {
            $this->resolution = $resolution;
        }

        public function getResolution()
        {
            return $this->resolution;
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

        // Etat résolution
        public function setResolved($resolved)
        {
            $this->resolved = $resolved;
        }

        public function getResolved()
        {
            return $this->resolved;
        }
    }
?>