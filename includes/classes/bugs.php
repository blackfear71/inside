<?php
  class Bugs
  {
    private $id;
    private $subject;
    private $date;
    private $author;
    private $name_a;
    private $content;
    private $type;
    private $resolved;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id       = 0;
      $this->subject  = '';
      $this->date     = '';
      $this->author   = '';
      $this->name_a   = '';
      $this->content  = '';
      $this->type     = '';
      $this->resolved = '';
    }

    // Constructeur de l'objet Bugs en fonction des données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
      $bugs = new self();
      $bugs->fill($data);

      return $bugs;
    }

    protected function fill ($data)
    {
      if (isset($data['id']))
        $this->id       = $data['id'];

      if (isset($data['subject']))
        $this->subject  = $data['subject'];

      if (isset($data['date']))
        $this->date     = $data['date'];

      if (isset($data['author']))
        $this->author   = $data['author'];

      if (isset($data['name_a']))
        $this->name_a   = $data['name_a'];

      if (isset($data['content']))
        $this->content  = $data['content'];

      if (isset($data['type']))
        $this->type     = $data['type'];

      if (isset($data['resolved']))
        $this->resolved = $data['resolved'];
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

    // Nom complet auteur
    public function setName_a($name_a)
    {
      $this->name_a = $name_a;
    }

    public function getName_a()
    {
      return $this->name_a;
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
