<?php
  class Ideas
  {
    private $id;
    private $subject;
    private $date;
    private $author;
    private $pseudo_a;
    private $avatar_a;
    private $content;
    private $status;
    private $developper;
    private $pseudo_d;
    private $avatar_d;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id         = 0;
      $this->subject    = '';
      $this->date       = '';
      $this->author     = '';
      $this->pseudo_a   = '';
      $this->avatar_a   = '';
      $this->content    = '';
      $this->status     = '';
      $this->developper = '';
      $this->pseudo_d   = '';
      $this->avatar_d   = '';
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
      if (isset($data['id']))
        $this->id         = $data['id'];

      if (isset($data['subject']))
        $this->subject    = $data['subject'];

      if (isset($data['date']))
        $this->date       = $data['date'];

      if (isset($data['author']))
        $this->author     = $data['author'];

      if (isset($data['pseudo_a']))
        $this->pseudo_a   = $data['pseudo_a'];

      if (isset($data['avatar_a']))
        $this->avatar_a   = $data['avatar_a'];

      if (isset($data['content']))
        $this->content    = $data['content'];

      if (isset($data['status']))
        $this->status     = $data['status'];

      if (isset($data['developper']))
        $this->developper = $data['developper'];

      if (isset($data['pseudo_d']))
        $this->pseudo_d   = $data['pseudo_d'];

      if (isset($data['avatar_d']))
        $this->avatar_d   = $data['avatar_d'];
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

    // Pseudo auteur
    public function setPseudo_a($pseudo_a)
    {
      $this->pseudo_a = $pseudo_a;
    }

    public function getPseudo_a()
    {
      return $this->pseudo_a;
    }

    // Avatar auteur
    public function setAvatar_a($avatar_a)
    {
      $this->avatar_a = $avatar_a;
    }

    public function getAvatar_a()
    {
      return $this->avatar_a;
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

    // Pseudo développeur
    public function setPseudo_d($pseudo_d)
    {
      $this->pseudo_d = $pseudo_d;
    }

    public function getPseudo_d()
    {
      return $this->pseudo_d;
    }

    // Avatar développeur
    public function setAvatar_d($avatar_d)
    {
      $this->avatar_d = $avatar_d;
    }

    public function getAvatar_d()
    {
      return $this->avatar_d;
    }
  }
?>
