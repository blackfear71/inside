<?php
    class Collector
    {
        private $id;
        private $date_add;
        private $author;
        private $pseudo_author;
        private $speaker;
        private $pseudo_speaker;
        private $avatar_speaker;
        private $type_speaker;
        private $date_collector;
        private $type_collector;
        private $team;
        private $collector;
        private $context;
        private $nb_votes;
        private $vote_user;
        private $votes;

        // Constructeur par défaut (objet vide)
        public function __construct()
        {
            $this->id             = 0;
            $this->date_add       = '';
            $this->author         = '';
            $this->pseudo_author  = '';
            $this->speaker        = '';
            $this->pseudo_speaker = '';
            $this->avatar_speaker = '';
            $this->type_speaker   = '';
            $this->date_collector = '';
            $this->type_collector = '';
            $this->team           = '';
            $this->collector      = '';
            $this->context        = '';
            $this->nb_votes       = 0;
            $this->vote_user      = 0;
            $this->votes          = array();
        }

        // Constructeur de l'objet Collector en fonction des données
        // -> il faut passer une variable $data contenant le résultat de la requête fetch
        public static function withData($data)
        {
            $collector = new self();
            $collector->fill($data);

            return $collector;
        }

        protected function fill($data)
        {
            if (isset($data['id']))
                $this->id             = $data['id'];

            if (isset($data['date_add']))
                $this->date_add       = $data['date_add'];

            if (isset($data['author']))
                $this->author         = $data['author'];

            if (isset($data['speaker']))
                $this->speaker        = $data['speaker'];

            if (isset($data['type_speaker']))
                $this->type_speaker   = $data['type_speaker'];

            if (isset($data['date_collector']))
                $this->date_collector = $data['date_collector'];

            if (isset($data['type_collector']))
                $this->type_collector = $data['type_collector'];

            if (isset($data['team']))
                $this->team           = $data['team'];

            if (isset($data['collector']))
                $this->collector      = $data['collector'];

            if (isset($data['context']))
                $this->context        = $data['context'];
        }

        // Sécurisation des données
        public static function secureData($data)
        {
            $data->setDate_add(htmlspecialchars($data->getDate_add()));
            $data->setAuthor(htmlspecialchars($data->getAuthor()));
            $data->setPseudo_author(htmlspecialchars($data->getPseudo_author()));
            $data->setSpeaker(htmlspecialchars($data->getSpeaker()));
            $data->setPseudo_speaker(htmlspecialchars($data->getPseudo_speaker()));
            $data->setAvatar_speaker(htmlspecialchars($data->getAvatar_speaker()));
            $data->setType_speaker(htmlspecialchars($data->getType_speaker()));
            $data->setDate_collector(htmlspecialchars($data->getDate_collector()));
            $data->setType_collector(htmlspecialchars($data->getType_collector()));
            //$data->setTeam(htmlspecialchars($data->getTeam()));
            $data->setCollector(htmlspecialchars($data->getCollector()));
            $data->setContext(htmlspecialchars($data->getContext()));
            $data->setNb_votes(htmlspecialchars($data->getNb_votes()));
            $data->setVote_user(htmlspecialchars($data->getVote_user()));

            $listeVotes = $data->getVotes();

            foreach ($listeVotes as &$votesParSmiley)
            {
                foreach ($votesParSmiley as &$vote)
                {
                    $vote = htmlspecialchars($vote);
                }

                unset($vote);
            }

            unset($votesParSmiley);

            $data->setVotes($listeVotes);
        }

        // Getters et Setters pour l'objet Collector
        // id
        public function setId($id)
        {
            $this->id = $id;
        }

        public function getId()
        {
            return $this->id;
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
        public function setPseudo_author($pseudo_author)
        {
            $this->pseudo_author = $pseudo_author;
        }

        public function getPseudo_author()
        {
            return $this->pseudo_author;
        }

        // Personne
        public function setSpeaker($speaker)
        {
            $this->speaker = $speaker;
        }

        public function getSpeaker()
        {
            return $this->speaker;
        }

        // Pseudo personne
        public function setPseudo_speaker($pseudo_speaker)
        {
            $this->pseudo_speaker = $pseudo_speaker;
        }

        public function getPseudo_speaker()
        {
            return $this->pseudo_speaker;
        }

        // Avatar personne
        public function setAvatar_speaker($avatar_speaker)
        {
            $this->avatar_speaker = $avatar_speaker;
        }

        public function getAvatar_speaker()
        {
            return $this->avatar_speaker;
        }

        // Type personne
        public function setType_speaker($type_speaker)
        {
            $this->type_speaker = $type_speaker;
        }

        public function getType_speaker()
        {
            return $this->type_speaker;
        }

        // Date Collector
        public function setDate_collector($date_collector)
        {
            $this->date_collector = $date_collector;
        }

        public function getDate_collector()
        {
            return $this->date_collector;
        }

        // Type Collector
        public function setType_collector($type_collector)
        {
            $this->type_collector = $type_collector;
        }

        public function getType_collector()
        {
            return $this->type_collector;
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

        // Phrase collector
        public function setCollector($collector)
        {
            $this->collector = $collector;
        }

        public function getCollector()
        {
            return $this->collector;
        }

        // Contexte collector
        public function setContext($context)
        {
            $this->context = $context;
        }

        public function getContext()
        {
            return $this->context;
        }

        // Nombre de votes
        public function setNb_votes($nb_votes)
        {
            $this->nb_votes = $nb_votes;
        }

        public function getNb_votes()
        {
            return $this->nb_votes;
        }

        // Vote de l'utilisateur
        public function setVote_user($vote_user)
        {
            $this->vote_user = $vote_user;
        }

        public function getVote_user()
        {
            return $this->vote_user;
        }

        // Votes tous utilisateurs
        public function setVotes($votes)
        {
            $this->votes = $votes;
        }

        public function getVotes()
        {
            return $this->votes;
        }
    }
?>