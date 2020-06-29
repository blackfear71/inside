<?php
  class Expenses
  {
    private $id;
    private $date;
    private $price;
    private $buyer;
    private $pseudo;
    private $avatar;
    private $comment;
    private $nb_parts;
    private $parts;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id       = 0;
      $this->date     = '';
      $this->price    = '';
      $this->buyer    = '';
      $this->pseudo   = '';
      $this->avatar   = '';
      $this->comment  = '';
      $this->nb_parts = 0;
      $this->parts    = array();
    }

    // Constructeur de l'objet Expenses en fonction des données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
      $expenses = new self();
      $expenses->fill($data);

      return $expenses;
    }

    protected function fill ($data)
    {
      if (isset($data['id']))
        $this->id      = $data['id'];

      if (isset($data['date']))
        $this->date    = $data['date'];

      if (isset($data['price']))
        $this->price   = $data['price'];

      if (isset($data['buyer']))
        $this->buyer   = $data['buyer'];

      if (isset($data['comment']))
        $this->comment = $data['comment'];
    }

    // getters et setters pour l'objet Expenses
    // id
    public function setId($id)
    {
      $this->id = $id;
    }

    public function getId()
    {
      return $this->id;
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

    // Prix
    public function setPrice($price)
    {
      $this->price = $price;
    }

    public function getPrice()
    {
      return $this->price;
    }

    // Acheteur
    public function setBuyer($buyer)
    {
      $this->buyer = $buyer;
    }

    public function getBuyer()
    {
      return $this->buyer;
    }

    // Pseudo
    public function setPseudo($pseudo)
    {
      $this->pseudo = $pseudo;
    }

    public function getPseudo()
    {
      return $this->pseudo;
    }

    // Avatar
    public function setAvatar($avatar)
    {
      $this->avatar = $avatar;
    }

    public function getAvatar()
    {
      return $this->avatar;
    }

    // Commentaire
    public function setComment($comment)
    {
      $this->comment = $comment;
    }

    public function getComment()
    {
      return $this->comment;
    }

    // Nombre de parts
    public function setNb_parts($nb_parts)
    {
      $this->nb_parts = $nb_parts;
    }

    public function getNb_parts()
    {
      return $this->nb_parts;
    }

    // Tableau des parts
    public function setParts($parts)
    {
      $this->parts = $parts;
    }

    public function getParts()
    {
      return $this->parts;
    }
  }

  class Parts
  {
    private $id;
    private $id_expense;
    private $id_identifiant;
    private $identifiant;
    private $pseudo;
    private $avatar;
    private $parts;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id             = 0;
      $this->id_expense     = 0;
      $this->id_identifiant = 0;
      $this->identifiant    = '';
      $this->pseudo         = '';
      $this->avatar         = '';
      $this->parts          = 0;
    }

    // Constructeur de l'objet Parts en fonction des données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
      $expenses = new self();
      $expenses->fill($data);

      return $expenses;
    }

    protected function fill ($data)
    {
      if (isset($data['id']))
        $this->id          = $data['id'];

      if (isset($data['id_expense']))
        $this->id_expense  = $data['id_expense'];

      if (isset($data['identifiant']))
        $this->identifiant = $data['identifiant'];

      if (isset($data['parts']))
        $this->parts       = $data['parts'];
    }

    // getters et setters pour l'objet Parts
    // id
    public function setId($id)
    {
      $this->id = $id;
    }

    public function getId()
    {
      return $this->id;
    }

    // Id dépense
    public function setId_expense($id_expense)
    {
      $this->id_expense = $id_expense;
    }

    public function getId_expense()
    {
      return $this->id_expense;
    }

    // Id identifiant
    public function setId_identifiant($id_identifiant)
    {
      $this->id_identifiant = $id_identifiant;
    }

    public function getId_identifiant()
    {
      return $this->id_identifiant;
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

    // Pseudo
    public function setPseudo($pseudo)
    {
      $this->pseudo = $pseudo;
    }

    public function getPseudo()
    {
      return $this->pseudo;
    }

    // Avatar
    public function setAvatar($avatar)
    {
      $this->avatar = $avatar;
    }

    public function getAvatar()
    {
      return $this->avatar;
    }

    // Parts
    public function setParts($parts)
    {
      $this->parts = $parts;
    }

    public function getParts()
    {
      return $this->parts;
    }
  }
?>
