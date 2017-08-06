<?php
  class Ideas
  {
    private $id;
    private $subject;
    private $date;
    private $author;
    private $name_a;
    private $content;
    private $status;
    private $developper;
    private $name_d;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id         = 0;
      $this->subject    = '';
      $this->date       = '';
      $this->author     = '';
      $this->name_a     = '';
      $this->content    = '';
      $this->status     = '';
      $this->developper = '';
      $this->name_d     = '';
    }

    // Constructeur de l'objet Ideas en fonction des données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
      $ideas = new self();
      $ideas->fill($data);

      return $ideas;
    }

    protected function fill ($data)
    {

       /******\
     /         \
    |    !!    | Il faut bien mettre un if sur toutes ?
    \         /
     \******/

      if (isset($data['id']))
        $this->id         = $data['id'];

      if (isset($data['subject']))
        $this->subject    = $data['subject'];

      if (isset($data['date']))
        $this->date       = $data['date'];

      if (isset($data['author']))
        $this->author     = $data['author'];

      if (isset($data['name_a']))
        $this->name_a     = $data['name_a'];

      if (isset($data['content']))
        $this->content    = $data['content'];

      if (isset($data['status']))
        $this->status     = $data['status'];

      if (isset($data['developper']))
        $this->developper = $data['developper'];

      if (isset($data['name_d']))
        $this->name_d     = $data['name_d'];
    }

    // getters et setters pour l'objet Ideas
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

    // Status
    public function setStatus($status)
    {
      $this->status = $status;
    }

    public function getStatus()
    {
      return $this->status;
    }

    // Développeur
    public function setDevelopper($developper)
    {
      $this->developper = $developper;
    }

    public function getDevelopper()
    {
      return $this->developper;
    }

    // Nom complet développeur
    public function setName_d($name_d)
    {
      $this->name_d = $name_d;
    }

    public function getName_d()
    {
      return $this->name_d;
    }
  }
?>
