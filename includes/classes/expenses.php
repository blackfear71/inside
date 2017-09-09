<?php
  class Expenses
  {
    private $id;
    private $date;
    private $price;
    private $buyer;
    private $comment;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id      = 0;
      $this->date    = '';
      $this->price   = '';
      $this->buyer   = '';
      $this->comment = '';
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

    // Commentaire
    public function setComment($comment)
    {
      $this->comment = $comment;
    }

    public function getComment()
    {
      return $this->comment;
    }
  }

  class Bilans
  {
    private $id;
    private $identifiant;
    private $pseudo;
    private $avatar;
    private $bilan;
    private $bilan_format;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id           = 0;
      $this->identifiant  = '';
      $this->pseudo       = '';
      $this->avatar       = '';
      $this->bilan        = 0;
      $this->bilan_format = '';
    }

    // Constructeur de l'objet Bilans en fonction des données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
      $bilans = new self();
      $bilans->fill($data);

      return $bilans;
    }

    protected function fill ($data)
    {
      if (isset($data['id']))
        $this->id           = $data['id'];

      if (isset($data['identifiant']))
        $this->identifiant  = $data['identifiant'];

      if (isset($data['pseudo']))
        $this->pseudo       = $data['pseudo'];

      if (isset($data['avatar']))
        $this->avatar       = $data['avatar'];

      if (isset($data['bilan']))
        $this->bilan        = $data['bilan'];

      if (isset($data['bilan_format']))
        $this->bilan_format = $data['bilan_format'];
    }

    // getters et setters pour l'objet Bilans
    // id
    public function setId($id)
    {
      $this->id = $id;
    }

    public function getId()
    {
      return $this->id;
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

    // Bilan numérique
    public function setBilan($bilan)
    {
      $this->bilan = $bilan;
    }

    public function getBilan()
    {
      return $this->bilan;
    }

    // Bilan formaté
    public function setBilan_format($bilan_format)
    {
      $this->bilan_format = $bilan_format;
    }

    public function getBilan_format()
    {
      return $this->bilan_format;
    }
  }

  class Parts
  {
    private $id;
    private $id_expense;
    private $identifiant;
    private $parts;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id          = 0;
      $this->id_expense  = 0;
      $this->identifiant = '';
      $this->parts       = 0;
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

    // Date
    public function setId_expense($id_expense)
    {
      $this->id_expense = $id_expense;
    }

    public function getId_expense()
    {
      return $this->id_expense;
    }

    // Prix
    public function setIdentifiant($identifiant)
    {
      $this->identifiant = $identifiant;
    }

    public function getIdentifiant()
    {
      return $this->identifiant;
    }

    // Acheteur
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
