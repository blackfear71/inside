<?php
    class Movie
    {
        private $id;
        private $film;
        private $to_delete;
        private $team;
        private $date_add;
        private $identifiant_add;
        private $pseudo_add;
        private $identifiant_del;
        private $pseudo_del;
        private $synopsis;
        private $date_theater;
        private $date_release;
        private $link;
        private $poster;
        private $trailer;
        private $id_url;
        private $doodle;
        private $date_doodle;
        private $time_doodle;
        private $restaurant;
        private $place;
        private $nb_comments;
        private $stars_user;
        private $participation;
        private $nb_users;
        private $average;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->id              = 0;
            $this->film            = '';
            $this->to_delete       = '';
            $this->team            = '';
            $this->date_add        = '';
            $this->identifiant_add = '';
            $this->pseudo_add      = '';
            $this->identifiant_del = '';
            $this->pseudo_del      = '';
            $this->synopsis        = '';
            $this->date_theater    = '';
            $this->date_release    = '';
            $this->link            = '';
            $this->poster          = '';
            $this->trailer         = '';
            $this->id_url          = '';
            $this->doodle          = '';
            $this->date_doodle     = '';
            $this->time_doodle     = '';
            $this->restaurant      = '';
            $this->place           = '';
            $this->nb_comments     = 0;
            $this->stars_user      = 0;
            $this->participation   = '';
            $this->nb_users        = 0;
            $this->average         = 0;
        }

        // Constructeur de l'objet Movie en fonction des données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $movie = new self();
            $movie->fillWithData($data);

            return $movie;
        }

        protected function fillWithData($data)
        {
            if (isset($data['id']))
                $this->id              = $data['id'];

            if (isset($data['film']))
                $this->film            = $data['film'];

            if (isset($data['to_delete']))
                $this->to_delete       = $data['to_delete'];

            if (isset($data['team']))
                $this->team            = $data['team'];

            if (isset($data['date_add']))
                $this->date_add        = $data['date_add'];

            if (isset($data['identifiant_add']))
                $this->identifiant_add = $data['identifiant_add'];

            if (isset($data['pseudo_add']))
                $this->pseudo_add      = $data['pseudo_add'];

            if (isset($data['identifiant_del']))
                $this->identifiant_del = $data['identifiant_del'];

            if (isset($data['pseudo_del']))
                $this->pseudo_del      = $data['pseudo_del'];

            if (isset($data['synopsis']))
                $this->synopsis        = $data['synopsis'];

            if (isset($data['date_theater']))
                $this->date_theater    = $data['date_theater'];

            if (isset($data['date_release']))
                $this->date_release    = $data['date_release'];

            if (isset($data['link']))
                $this->link            = $data['link'];

            if (isset($data['poster']))
                $this->poster          = $data['poster'];

            if (isset($data['trailer']))
                $this->trailer         = $data['trailer'];

            if (isset($data['id_url']))
                $this->id_url          = $data['id_url'];

            if (isset($data['doodle']))
                $this->doodle          = $data['doodle'];

            if (isset($data['date_doodle']))
                $this->date_doodle     = $data['date_doodle'];

            if (isset($data['time_doodle']))
                $this->time_doodle     = $data['time_doodle'];

            if (isset($data['restaurant']))
                $this->restaurant      = $data['restaurant'];

            if (isset($data['place']))
                $this->place           = $data['place'];

            if (isset($data['nb_comments']))
                $this->nb_comments     = $data['nb_comments'];

            if (isset($data['stars_user']))
                $this->stars_user      = $data['stars_user'];

            if (isset($data['participation']))
                $this->participation   = $data['participation'];

            if (isset($data['nb_users']))
                $this->nb_users        = $data['nb_users'];

            if (isset($data['average']))
                $this->average         = $data['average'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $movie = new self();
            $movie->fillSecureData($data);

            return $movie;
        }

        protected function fillSecureData($data)
        {
            $this->id              = $data->getId();
            $this->film            = htmlspecialchars($data->getFilm());
            $this->to_delete       = htmlspecialchars($data->getTo_delete());
            $this->team            = $data->getTeam();
            $this->date_add        = htmlspecialchars($data->getDate_add());
            $this->identifiant_add = htmlspecialchars($data->getIdentifiant_add());
            $this->pseudo_add      = htmlspecialchars($data->getPseudo_add());
            $this->identifiant_del = htmlspecialchars($data->getIdentifiant_del());
            $this->pseudo_del      = htmlspecialchars($data->getPseudo_del());
            $this->synopsis        = htmlspecialchars($data->getSynopsis());
            $this->date_theater    = htmlspecialchars($data->getDate_theater());
            $this->date_release    = htmlspecialchars($data->getDate_release());
            $this->link            = htmlspecialchars($data->getLink());
            $this->poster          = htmlspecialchars($data->getPoster());
            $this->trailer         = htmlspecialchars($data->getTrailer());
            $this->id_url          = htmlspecialchars($data->getId_url());
            $this->doodle          = htmlspecialchars($data->getDoodle());
            $this->date_doodle     = htmlspecialchars($data->getDate_doodle());
            $this->time_doodle     = htmlspecialchars($data->getTime_doodle());
            $this->restaurant      = htmlspecialchars($data->getRestaurant());
            $this->place           = htmlspecialchars($data->getPlace());
            $this->nb_comments     = htmlspecialchars($data->getNb_comments());
            $this->stars_user      = htmlspecialchars($data->getStars_user());
            $this->participation   = htmlspecialchars($data->getParticipation());
            $this->nb_users        = htmlspecialchars($data->getNb_users());
            $this->average         = htmlspecialchars($data->getAverage());
        }

        // Getters et Setters pour l'objet Movie
        // id
        public function setId($id)
        {
            $this->id = $id;
        }

        public function getId()
        {
            return $this->id;
        }

        // Nom film
        public function setFilm($film)
        {
            $this->film = $film;
        }

        public function getFilm()
        {
            return $this->film;
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

        // Date ajout
        public function setDate_add($date_add)
        {
            $this->date_add = $date_add;
        }

        public function getDate_add()
        {
            return $this->date_add;
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

        // Synopsis
        public function setSynopsis($synopsis)
        {
            $this->synopsis = $synopsis;
        }

        public function getSynopsis()
        {
            return $this->synopsis;
        }

        // Date sortie cinéma
        public function setDate_theater($date_theater)
        {
            $this->date_theater = $date_theater;
        }

        public function getDate_theater()
        {
            return $this->date_theater;
        }

        // Date sortie DVD
        public function setDate_release($date_release)
        {
            $this->date_release = $date_release;
        }

        public function getDate_release()
        {
            return $this->date_release;
        }

        // Lien fiche
        public function setLink($link)
        {
            $this->link = $link;
        }

        public function getLink()
        {
            return $this->link;
        }

        // Affiche
        public function setPoster($poster)
        {
            $this->poster = $poster;
        }

        public function getPoster()
        {
            return $this->poster;
        }

        // Bande-annonce
        public function setTrailer($trailer)
        {
            $this->trailer = $trailer;
        }

        public function getTrailer()
        {
            return $this->trailer;
        }

        // Id lien bande-annonce
        public function setId_url($id_url)
        {
            $this->id_url = $id_url;
        }

        public function getId_url()
        {
            return $this->id_url;
        }

        // Lien Doodle
        public function setDoodle($doodle)
        {
            $this->doodle = $doodle;
        }

        public function getDoodle()
        {
            return $this->doodle;
        }

        // Date Doodle
        public function setDate_doodle($date_doodle)
        {
            $this->date_doodle = $date_doodle;
        }

        public function getDate_doodle()
        {
            return $this->date_doodle;
        }

        // Heure Doodle
        public function setTime_doodle($time_doodle)
        {
            $this->time_doodle = $time_doodle;
        }

        public function getTime_doodle()
        {
            return $this->time_doodle;
        }

        // Choix restaurant
        public function setRestaurant($restaurant)
        {
            $this->restaurant = $restaurant;
        }

        public function getRestaurant()
        {
            return $this->restaurant;
        }

        // Lieu restaurant
        public function setPlace($place)
        {
            $this->place = $place;
        }

        public function getPlace()
        {
            return $this->place;
        }

        // Nombre de commentaires
        public function setNb_comments($nb_comments)
        {
            $this->nb_comments = $nb_comments;
        }

        public function getNb_comments()
        {
            return $this->nb_comments;
        }

        // Etoiles utilisateur connecté
        public function setStars_user($stars_user)
        {
            $this->stars_user = $stars_user;
        }

        public function getStars_user()
        {
            return $this->stars_user;
        }

        // Participation utilisateur connecté
        public function setParticipation($participation)
        {
            $this->participation = $participation;
        }

        public function getParticipation()
        {
            return $this->participation;
        }

        // Nombre d'utilisateurs
        public function setNb_users($nb_users)
        {
            $this->nb_users = $nb_users;
        }

        public function getNb_users()
        {
            return $this->nb_users;
        }

        // Moyenne des étoiles
        public function setAverage($average)
        {
            $this->average = $average;
        }

        public function getAverage()
        {
            return $this->average;
        }
    }

    class Stars
    {
        private $id;
        private $id_film;
        private $identifiant;
        private $pseudo;
        private $avatar;
        private $email;
        private $stars;
        private $participation;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->id            = 0;
            $this->id_film       = 0;
            $this->identifiant   = '';
            $this->pseudo        = '';
            $this->avatar        = '';
            $this->email         = '';
            $this->stars         = 0;
            $this->participation = '';
        }

        // Constructeur de l'objet Stars en fonction des données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $stars = new self();
            $stars->fillWithData($data);

            return $stars;
        }

        protected function fillWithData($data)
        {
            if (isset($data['id']))
                $this->id             = $data['id'];

            if (isset($data['id_film']))
                $this->id_film        = $data['id_film'];

            if (isset($data['identifiant']))
                $this->identifiant    = $data['identifiant'];

            if (isset($data['stars']))
                $this->stars          = $data['stars'];

            if (isset($data['participation']))
                $this->participation  = $data['participation'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $stars = new self();
            $stars->fillSecureData($data);

            return $stars;
        }

        protected function fillSecureData($data)
        {
            $this->id            = $data->getId();
            $this->id_film       = $data->getId_film();
            $this->identifiant   = htmlspecialchars($data->getIdentifiant());
            $this->pseudo        = htmlspecialchars($data->getPseudo());
            $this->avatar        = htmlspecialchars($data->getAvatar());
            $this->email         = htmlspecialchars($data->getEmail());
            $this->stars         = htmlspecialchars($data->getStars());
            $this->participation = htmlspecialchars($data->getParticipation());
        }

        // Getters et Setters pour l'objet Stars
        // id
        public function setId($id)
        {
            $this->id = $id;
        }

        public function getId()
        {
            return $this->id;
        }

        // Id film
        public function setId_film($id_film)
        {
            $this->id_film = $id_film;
        }

        public function getId_film()
        {
            return $this->id_film;
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

        // Email
        public function setEmail($email)
        {
            $this->email = $email;
        }

        public function getEmail()
        {
            return $this->email;
        }

        // Etoiles utilisateur
        public function setStars($stars)
        {
            $this->stars = $stars;
        }

        public function getStars()
        {
            return $this->stars;
        }

        // Participation
        public function setParticipation($participation)
        {
            $this->participation = $participation;
        }

        public function getParticipation()
        {
            return $this->participation;
        }
    }

    class Commentaire
    {
        private $id;
        private $id_film;
        private $identifiant;
        private $pseudo;
        private $avatar;
        private $date;
        private $time;
        private $comment;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->id          = 0;
            $this->id_film     = 0;
            $this->identifiant = '';
            $this->pseudo      = '';
            $this->avatar      = '';
            $this->date        = '';
            $this->time        = '';
            $this->comment     = '';
        }

        // Constructeur de l'objet Comments en fonction des données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $comments = new self();
            $comments->fillWithData($data);

            return $comments;
        }

        protected function fillWithData($data)
        {
            if (isset($data['id']))
                $this->id          = $data['id'];

            if (isset($data['id_film']))
                $this->id_film     = $data['id_film'];

            if (isset($data['identifiant']))
                $this->identifiant = $data['identifiant'];

            if (isset($data['date']))
                $this->date        = $data['date'];

            if (isset($data['time']))
                $this->time        = $data['time'];

            if (isset($data['comment']))
                $this->comment     = $data['comment'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $comments = new self();
            $comments->fillSecureData($data);

            return $comments;
        }

        protected function fillSecureData($data)
        {
            $this->id          = $data->getId();
            $this->id_film     = $data->getId_film();
            $this->identifiant = htmlspecialchars($data->getIdentifiant());
            $this->pseudo      = htmlspecialchars($data->getPseudo());
            $this->avatar      = htmlspecialchars($data->getAvatar());
            $this->date        = htmlspecialchars($data->getDate());
            $this->time        = htmlspecialchars($data->getTime());
            $this->comment     = htmlspecialchars($data->getComment());
        }

        // Getters et Setters pour l'objet Comments
        // id
        public function setId($id)
        {
            $this->id = $id;
        }

        public function getId()
        {
            return $this->id;
        }

        // Id film
        public function setId_film($id_film)
        {
            $this->id_film = $id_film;
        }

        public function getId_film()
        {
            return $this->id_film;
        }

        // Auteur
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

        // Heure
        public function setTime($time)
        {
            $this->time = $time;
        }

        public function getTime()
        {
            return $this->time;
        }

        // Commentaire
        public function setComment($comment)
        {
            $this->comment = $comment;
        }

        public function getComment()
        {
            return $this->comment;
        }
    }
?>