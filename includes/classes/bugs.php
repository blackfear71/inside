<?php
    class BugEvolution
    {
        private $id;
        private $subject;
        private $date;
        private $author;
        private $pseudo;
        private $avatar;
        private $team;
        private $content;
        private $picture;
        private $type;
        private $resolved;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->id       = 0;
            $this->subject  = '';
            $this->date     = '';
            $this->author   = '';
            $this->pseudo   = '';
            $this->avatar   = '';
            $this->team     = '';
            $this->content  = '';
            $this->picture  = '';
            $this->type     = '';
            $this->resolved = '';
        }

        // Constructeur de l'objet BugEvolution en fonction des données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $bugs = new self();
            $bugs->fill($data);

            return $bugs;
        }

        protected function fill($data)
        {
            if (isset($data['id']))
                $this->id       = $data['id'];

            if (isset($data['subject']))
                $this->subject  = $data['subject'];

            if (isset($data['date']))
                $this->date     = $data['date'];

            if (isset($data['author']))
                $this->author   = $data['author'];

            if (isset($data['team']))
                $this->team     = $data['team'];

            if (isset($data['content']))
                $this->content  = $data['content'];

            if (isset($data['picture']))
                $this->picture  = $data['picture'];

            if (isset($data['type']))
                $this->type     = $data['type'];

            if (isset($data['resolved']))
                $this->resolved = $data['resolved'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $data->setSubject(htmlspecialchars($data->getSubject()));
            $data->setDate(htmlspecialchars($data->getDate()));
            $data->setAuthor(htmlspecialchars($data->getAuthor()));
            $data->setPseudo(htmlspecialchars($data->getPseudo()));
            $data->setAvatar(htmlspecialchars($data->getAvatar()));
            //$data->setTeam(htmlspecialchars($data->getTeam()));
            $data->setContent(htmlspecialchars($data->getContent()));
            $data->setPicture(htmlspecialchars($data->getPicture()));
            $data->getType(htmlspecialchars($data->getType()));
            $data->getResolved(htmlspecialchars($data->getResolved()));
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

        // Auteur
        public function setAuthor($author)
        {
            $this->author = $author;
        }

        public function getAuthor()
        {
            return $this->author;
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