<?php
    class WeekCake
    {
        private $id;
        private $identifiant;
        private $pseudo;
        private $avatar;
        private $team;
        private $week;
        private $year;
        private $cooked;
        private $name;
        private $picture;
        private $ingredients;
        private $recipe;
        private $tips;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->id          = 0;
            $this->identifiant = '';
            $this->pseudo      = '';
            $this->avatar      = '';
            $this->team        = '';
            $this->week        = '';
            $this->year        = '';
            $this->cooked      = '';
            $this->name        = '';
            $this->picture     = '';
            $this->ingredients = '';
            $this->recipe      = '';
            $this->tips        = '';
        }

        // Constructeur de l'objet WeekCake en fonction des données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $weekCake = new self();
            $weekCake->fillWithData($data);

            return $weekCake;
        }

        protected function fillWithData($data)
        {
            if (isset($data['id']))
                $this->id          = $data['id'];

            if (isset($data['identifiant']))
                $this->identifiant = $data['identifiant'];

            if (isset($data['team']))
                $this->team        = $data['team'];

            if (isset($data['week']))
                $this->week        = $data['week'];

            if (isset($data['year']))
                $this->year        = $data['year'];

            if (isset($data['cooked']))
                $this->cooked      = $data['cooked'];

            if (isset($data['name']))
                $this->name        = $data['name'];

            if (isset($data['picture']))
                $this->picture     = $data['picture'];

            if (isset($data['ingredients']))
                $this->ingredients = $data['ingredients'];

            if (isset($data['recipe']))
                $this->recipe      = $data['recipe'];

            if (isset($data['tips']))
                $this->tips        = $data['tips'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $weekCake = new self();
            $weekCake->fillSecureData($data);

            return $weekCake;
        }

        protected function fillSecureData($data)
        {
            $this->id          = $data->getId();
            $this->identifiant = htmlspecialchars($data->getIdentifiant());
            $this->pseudo      = htmlspecialchars($data->getPseudo());
            $this->avatar      = htmlspecialchars($data->getAvatar());
            $this->team        = $data->getTeam();
            $this->week        = htmlspecialchars($data->getWeek());
            $this->year        = htmlspecialchars($data->getYear());
            $this->cooked      = htmlspecialchars($data->getCooked());
            $this->name        = htmlspecialchars($data->getName());
            $this->picture     = htmlspecialchars($data->getPicture());
            $this->ingredients = htmlspecialchars($data->getIngredients());
            $this->recipe      = htmlspecialchars($data->getRecipe());
            $this->tips        = htmlspecialchars($data->getTips());
        }

        // Getters et Setters pour l'objet WeekCake
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

        // Equipe
        public function setTeam($team)
        {
            $this->team = $team;
        }

        public function getTeam()
        {
            return $this->team;
        }

        // Semaine
        public function setWeek($week)
        {
            $this->week = $week;
        }

        public function getWeek()
        {
            return $this->week;
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

        // Réalisé
        public function setCooked($cooked)
        {
            $this->cooked = $cooked;
        }

        public function getCooked()
        {
            return $this->cooked;
        }

        // Nom de la recette
        public function setName($name)
        {
            $this->name = $name;
        }

        public function getName()
        {
            return $this->name;
        }

        // Photo
        public function setPicture($picture)
        {
            $this->picture = $picture;
        }

        public function getPicture()
        {
            return $this->picture;
        }

        // Ingrédients
        public function setIngredients($ingredients)
        {
            $this->ingredients = $ingredients;
        }

        public function getIngredients()
        {
            return $this->ingredients;
        }

        // Recette
        public function setRecipe($recipe)
        {
            $this->recipe = $recipe;
        }

        public function getRecipe()
        {
            return $this->recipe;
        }

        // Remarques et astuces
        public function setTips($tips)
        {
            $this->tips = $tips;
        }

        public function getTips()
        {
            return $this->tips;
        }
    }
?>