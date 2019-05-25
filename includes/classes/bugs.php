<?php
  class Bugs
  {
    private $id;
    private $subject;
    private $date;
    private $author;
    private $pseudo;
    private $avatar;
    private $content;
    private $picture;
    private $type;
    private $resolved;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id       = 0;
      $this->subject  = '';
      $this->date     = '';
      $this->author   = '';
      $this->pseudo   = '';
      $this->avatar   = '';
      $this->content  = '';
      $this->picture  = '';
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

      if (isset($data['pseudo']))
        $this->pseudo   = $data['pseudo'];

      if (isset($data['avatar']))
        $this->avatar   = $data['avatar'];

      if (isset($data['content']))
        $this->content  = $data['content'];

      if (isset($data['picture']))
        $this->picture  = $data['picture'];

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

    // Pseudo auteur
    public function setPseudo($pseudo)
    {
      $this->pseudo = $pseudo;
    }

    public function getPseudo()
    {
      return $this->pseudo;
    }

    // Avatar auteur
    public function setAvatar($avatar)
    {
      $this->avatar = $avatar;
    }

    public function getAvatar()
    {
      return $this->avatar;
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

    // Image
    public function setPicture($picture)
    {
      $this->picture = $picture;
    }

    public function getPicture()
    {
      return $this->picture;
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
