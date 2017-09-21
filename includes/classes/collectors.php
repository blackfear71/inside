<?php
  class Collector
  {
    private $id;
    private $author;
    private $name_a;
    private $speaker;
    private $name_s;
    private $avatar_s;
    private $date_collector;
    private $collector;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id             = 0;
      $this->author         = '';
      $this->name_a         = '';
      $this->speaker        = '';
      $this->name_s         = '';
      $this->avatar_s       = '';
      $this->date_collector = '';
      $this->collector      = '';
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

      if (isset($data['author']))
        $this->author         = $data['author'];

      if (isset($data['speaker']))
        $this->speaker        = $data['speaker'];

      if (isset($data['date_collector']))
        $this->date_collector = $data['date_collector'];

      if (isset($data['collector']))
        $this->collector      = $data['collector'];
    }

    // getters et setters pour l'objet Bugs
    // id
    public function setId($id)
    {
      $this->id = $id;
    }

    public function getId()
    {
      return $this->id;
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

    // Date Collector
    public function setDate_collector($date_collector)
    {
      $this->date_collector = $date_collector;
    }

    public function getDate_collector()
    {
      return $this->date_collector;
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

    // getters et setters pour l'objet Bugs
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
