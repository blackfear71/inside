<?php
  class Collector
  {
    private $id;
    private $date_add;
    private $author;
    private $name_a;
    private $speaker;
    private $name_s;
    private $avatar_s;
    private $type_s;
    private $date_collector;
    private $type_collector;
    private $collector;
    private $context;
    private $nbVotes;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id             = 0;
      $this->date_add       = '';
      $this->author         = '';
      $this->name_a         = '';
      $this->speaker        = '';
      $this->name_s         = '';
      $this->avatar_s       = '';
      $this->type_s         = '';
      $this->date_collector = '';
      $this->type_collector = '';
      $this->collector      = '';
      $this->context        = '';
      $this->nbVotes        = 0;
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
    public function setName_a($name_a)
    {
      $this->name_a = $name_a;
    }

    public function getName_a()
    {
      return $this->name_a;
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
    public function setName_s($name_s)
    {
      $this->name_s = $name_s;
    }

    public function getName_s()
    {
      return $this->name_s;
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
    public function setNb_votes($nbVotes)
    {
      $this->nbVotes = $nbVotes;
    }

    public function getNb_votes()
    {
      return $this->nbVotes;
    }
  }

  class VotesCollector
  {
    private $id;
    private $id_collector;
    private $identifiant;
    private $vote;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id           = 0;
      $this->id_collector = 0;
      $this->identifiant  = '';
      $this->vote         = 0;
    }

    // Constructeur de l'objet VotesCollector en fonction des données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
      $vote = new self();
      $vote->fill($data);

      return $vote;
    }

    protected function fill ($data)
    {
      if (isset($data['id']))
        $this->id           = $data['id'];

      if (isset($data['id_collector']))
        $this->id_collector = $data['id_collector'];

      if (isset($data['identifiant']))
        $this->identifiant  = $data['identifiant'];

      if (isset($data['vote']))
        $this->vote         = $data['vote'];
    }

    // getters et setters pour l'objet VotesCollector
    // id
    public function setId($id)
    {
      $this->id = $id;
    }

    public function getId()
    {
      return $this->id;
    }

    // Id collector
    public function setId_collector($id_collector)
    {
      $this->id_collector = $id_collector;
    }

    public function getId_collector()
    {
      return $this->id_collector;
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

    // Vote
    public function setVote($vote)
    {
      $this->vote = $vote;
    }

    public function getVote()
    {
      return $this->vote;
    }
  }
?>
