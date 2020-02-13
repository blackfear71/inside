<?php
  class Collector
  {
    private $id;
    private $date_add;
    private $author;
    private $pseudo_a;
    private $speaker;
    private $pseudo_s;
    private $avatar_s;
    private $type_s;
    private $date_collector;
    private $type_collector;
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
      $this->pseudo_a       = '';
      $this->speaker        = '';
      $this->pseudo_s       = '';
      $this->avatar_s       = '';
      $this->type_s         = '';
      $this->date_collector = '';
      $this->type_collector = '';
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

    protected function fill ($data)
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
        $this->type_s         = $data['type_speaker'];

      if (isset($data['date_collector']))
        $this->date_collector = $data['date_collector'];

      if (isset($data['type_collector']))
        $this->type_collector = $data['type_collector'];

      if (isset($data['collector']))
        $this->collector      = $data['collector'];

      if (isset($data['context']))
        $this->context        = $data['context'];
    }

    // getters et setters pour l'objet Collector
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
    public function setPseudo_a($pseudo_a)
    {
      $this->pseudo_a = $pseudo_a;
    }

    public function getPseudo_a()
    {
      return $this->pseudo_a;
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
    public function setPseudo_s($pseudo_s)
    {
      $this->pseudo_s = $pseudo_s;
    }

    public function getPseudo_s()
    {
      return $this->pseudo_s;
    }

    // Avatar personne
    public function setAvatar_s($avatar_s)
    {
      $this->avatar_s = $avatar_s;
    }

    public function getAvatar_s()
    {
      return $this->avatar_s;
    }

    // Type personne
    public function setType_s($type_s)
    {
      $this->type_s = $type_s;
    }

    public function getType_s()
    {
      return $this->type_s;
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
