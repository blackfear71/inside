<?php
  class Collector
  {
    private $id;
    private $author;
    private $name_a;
    private $speaker;
    private $name_s;
    private $avatar_s;
    private $date;
    private $collector;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id        = 0;
      $this->author    = '';
      $this->name_a    = '';
      $this->speaker   = '';
      $this->name_s    = '';
      $this->avatar_s  = '';
      $this->date      = '';
      $this->collector = '';
    }

    // Constructeur de l'objet Bugs en fonction des données
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
        $this->id        = $data['id'];

      if (isset($data['author']))
        $this->author    = $data['author'];

      if (isset($data['speaker']))
        $this->speaker   = $data['speaker'];

      if (isset($data['date']))
        $this->date      = $data['date'];

      if (isset($data['collector']))
        $this->collector = $data['collector'];
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

    // Date
    public function setDate($date)
    {
      $this->date = $date;
    }

    public function getDate()
    {
      return $this->date;
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
?>
