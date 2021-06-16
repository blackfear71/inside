<?php
  class Expenses
  {
    private $id;
    private $team;
    private $date;
    private $price;
    private $buyer;
    private $pseudo;
    private $avatar;
    private $comment;
    private $frais;
    private $type;
    private $nb_users;
    private $parts;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id       = 0;
      $this->team     = '';
      $this->date     = '';
      $this->price    = '';
      $this->buyer    = '';
      $this->pseudo   = '';
      $this->avatar   = '';
      $this->comment  = '';
      $this->frais    = '';
      $this->type     = '';
      $this->nb_users = 0;
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

      if (isset($data['team']))
        $this->team    = $data['team'];

      if (isset($data['date']))
        $this->date    = $data['date'];

      if (isset($data['price']))
        $this->price   = $data['price'];

      if (isset($data['buyer']))
        $this->buyer   = $data['buyer'];

      if (isset($data['comment']))
        $this->comment = $data['comment'];

      if (isset($data['type']))
        $this->type    = $data['type'];
    }

    // Sécurisation des données
    public static function secureData($data)
    {
      //$data->setTeam(htmlspecialchars($data->getTeam()));
      $data->setDate(htmlspecialchars($data->getDate()));
      $data->setPrice(htmlspecialchars($data->getPrice()));
      $data->setBuyer(htmlspecialchars($data->getBuyer()));
      $data->setPseudo(htmlspecialchars($data->getPseudo()));
      $data->setAvatar(htmlspecialchars($data->getAvatar()));
      $data->setComment(htmlspecialchars($data->getComment()));
      $data->setFrais(htmlspecialchars($data->getFrais()));
      $data->setType(htmlspecialchars($data->getType()));
      $data->setNb_users(htmlspecialchars($data->getNb_users()));

      foreach ($data->getParts() as $parts)
      {
        Parts::secureData($parts);
      }
    }

    // Getters et Setters pour l'objet Expenses
    // id
    public function setId($id)
    {
      $this->id = $id;
    }

    public function getId()
    {
      return $this->id;
    }

    // Equipe
    public function setTeam($team)
    {
      $this->team = $team;
    }

    public function getTeam()
    {
      return $this->team;
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

    // Frais additionnels
    public function setFrais($frais)
    {
      $this->frais = $frais;
    }

    public function getFrais()
    {
      return $this->frais;
    }

    // Type de dépense
    public function setType($type)
    {
      $this->type = $type;
    }

    public function getType()
    {
      return $this->type;
    }

    // Nombre d'utilisateurs
    public function setNb_users($nb_users)
    {
      $this->nb_users = $nb_users;
    }

    public function getNb_users()
    {
      return $this->nb_users;
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
    private $identifiant;
    private $pseudo;
    private $avatar;
    private $team;
    private $parts;
    private $inscrit;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id             = 0;
      $this->id_expense     = 0;
      $this->identifiant    = '';
      $this->pseudo         = '';
      $this->avatar         = '';
      $this->team           = '';
      $this->parts          = 0;
      $this->inscrit        = true;
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

      if (isset($data['team']))
        $this->team        = $data['team'];

      if (isset($data['identifiant']))
        $this->identifiant = $data['identifiant'];

      if (isset($data['parts']))
        $this->parts       = $data['parts'];
    }

    // Sécurisation des données
    public static function secureData($data)
    {
      $data->setIdentifiant(htmlspecialchars($data->getIdentifiant()));
      $data->setPseudo(htmlspecialchars($data->getPseudo()));
      $data->setAvatar(htmlspecialchars($data->getAvatar()));
      //$data->setTeam(htmlspecialchars($data->getTeam()));
      $data->setParts(htmlspecialchars($data->getParts()));
    }

    // Getters et Setters pour l'objet Parts
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

    // Equipe
    public function setTeam($team)
    {
      $this->team = $team;
    }

    public function getTeam()
    {
      return $this->team;
    }

    // Parts ou montant
    public function setParts($parts)
    {
      $this->parts = $parts;
    }

    public function getParts()
    {
      return $this->parts;
    }

    // Utilisateur inscrit
    public function setInscrit($inscrit)
    {
      $this->inscrit = $inscrit;
    }

    public function getInscrit()
    {
      return $this->inscrit;
    }
  }
?>
