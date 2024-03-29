<?php
    class News
    {
        private $title;
        private $content;
        private $details;
        private $logo;
        private $link;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->title   = '';
            $this->content = '';
            $this->details = '';
            $this->logo    = '';
            $this->link    = '';
        }

        // Constructeur de l'objet Restaurant en fonction des données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $news = new self();
            $news->fillWithData($data);

            return $news;
        }

        protected function fillWithData($data)
        {
            if (isset($data['title']))
                $this->title   = $data['title'];

            if (isset($data['content']))
                $this->content = $data['content'];

            if (isset($data['details']))
                $this->details = $data['details'];

            if (isset($data['logo']))
                $this->logo    = $data['logo'];

            if (isset($data['link']))
                $this->link    = $data['link'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $news = new self();
            $news->fillSecureData($data);

            return $news;
        }

        protected function fillSecureData($data)
        {
            $this->title   = htmlspecialchars($data->getTitle());
            $this->content = $data->getContent();
            $this->details = $data->getDetails();
            $this->logo    = htmlspecialchars($data->getLogo());
            $this->link    = htmlspecialchars($data->getLink());
        }

        // Getters et Setters pour l'objet News
        // Titre
        public function setTitle($title)
        {
            $this->title = $title;
        }

        public function getTitle()
        {
            return $this->title;
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

        // Complément
        public function setDetails($details)
        {
            $this->details = $details;
        }

        public function getDetails()
        {
            return $this->details;
        }

        // Logo
        public function setLogo($logo)
        {
            $this->logo = $logo;
        }

        public function getLogo()
        {
            return $this->logo;
        }

        // Lien
        public function setLink($link)
        {
            $this->link = $link;
        }

        public function getLink()
        {
            return $this->link;
        }
    }
?>