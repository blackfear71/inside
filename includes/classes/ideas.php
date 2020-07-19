<?php
  class Idea
  {
    private $id;
    private $subject;
    private $date;
    private $author;
    private $pseudo_author;
    private $avatar_author;
    private $content;
    private $status;
    private $developper;
    private $pseudo_developper;
    private $avatar_developper;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id                = 0;
      $this->subject           = '';
      $this->date              = '';
      $this->author            = '';
      $this->pseudo_author     = '';
      $this->avatar_author     = '';
      $this->content           = '';
      $this->status            = '';
      $this->developper        = '';
      $this->pseudo_developper = '';
      $this->avatar_developper = '';
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
        $this->id                = $data['id'];

      if (isset($data['subject']))
        $this->subject           = $data['subject'];

      if (isset($data['date']))
        $this->date              = $data['date'];

      if (isset($data['author']))
        $this->author            = $data['author'];

      if (isset($data['pseudo_author']))
        $this->pseudo_author     = $data['pseudo_author'];

      if (isset($data['avatar_author']))
        $this->avatar_author     = $data['avatar_author'];

      if (isset($data['content']))
        $this->content           = $data['content'];

      if (isset($data['status']))
        $this->status            = $data['status'];

      if (isset($data['developper']))
        $this->developper        = $data['developper'];

      if (isset($data['pseudo_developper']))
        $this->pseudo_developper = $data['pseudo_developper'];

      if (isset($data['avatar_developper']))
        $this->avatar_developper = $data['avatar_developper'];
    }

    // Sécurisation des données
    public static function secureData($data)
    {
      $data->setSubject(htmlspecialchars($data->getSubject()));
      $data->setDate(htmlspecialchars($data->getDate()));
      $data->setAuthor(htmlspecialchars($data->getAuthor()));
      $data->setPseudo_author(htmlspecialchars($data->getPseudo_author()));
      $data->setAvatar_author(htmlspecialchars($data->getAvatar_author()));
      $data->setContent(htmlspecialchars($data->getContent()));
      $data->setStatus(htmlspecialchars($data->getStatus()));
      $data->setDevelopper(htmlspecialchars($data->getDevelopper()));
      $data->setPseudo_developper(htmlspecialchars($data->getPseudo_developper()));
      $data->setAvatar_developper(htmlspecialchars($data->getAvatar_developper()));
    }

    // Getters et Setters pour l'objet Ideas
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
    public function setPseudo_author($pseudo_author)
    {
      $this->pseudo_author = $pseudo_author;
    }

    public function getPseudo_author()
    {
      return $this->pseudo_author;
    }

    // Avatar auteur
    public function setAvatar_author($avatar_author)
    {
      $this->avatar_author = $avatar_author;
    }

    public function getAvatar_author()
    {
      return $this->avatar_author;
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
    public function setPseudo_developper($pseudo_developper)
    {
      $this->pseudo_developper = $pseudo_developper;
    }

    public function getPseudo_developper()
    {
      return $this->pseudo_developper;
    }

    // Avatar développeur
    public function setAvatar_developper($avatar_developper)
    {
      $this->avatar_developper = $avatar_developper;
    }

    public function getAvatar_developper()
    {
      return $this->avatar_developper;
    }
  }
?>
