<?php
  class Notification
  {
    private $id;
    private $author;
    private $date;
    private $time;
    private $category;
    private $content;
    private $icon;
    private $sentence;
    private $link;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id       = 0;
      $this->author   = '';
      $this->date     = '';
      $this->time     = '';
      $this->category = '';
      $this->content  = '';
      $this->icon     = '';
      $this->sentence = '';
      $this->link     = '';
    }

    // Constructeur de l'objet Notification en fonction des données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
      $notification = new self();
      $notification->fill($data);

      return $notification;
    }

    protected function fill ($data)
    {
      if (isset($data['id']))
        $this->id       = $data['id'];

      if (isset($data['author']))
        $this->author   = $data['author'];

      if (isset($data['date']))
        $this->date     = $data['date'];

      if (isset($data['time']))
        $this->time     = $data['time'];

      if (isset($data['category']))
        $this->category = $data['category'];

      if (isset($data['content']))
        $this->content  = $data['content'];
    }

    // getters et setters pour l'objet Notification
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

    // Catégorie
    public function setCategory($category)
    {
      $this->category = $category;
    }

    public function getCategory()
    {
      return $this->category;
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

    // Icône
    public function setIcon($icon)
    {
      $this->icon = $icon;
    }

    public function getIcon()
    {
      return $this->icon;
    }

    // Phrase
    public function setSentence($sentence)
    {
      $this->sentence = $sentence;
    }

    public function getSentence()
    {
      return $this->sentence;
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
