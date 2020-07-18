<?php
  class Alerte
  {
    private $id;
    private $category;
    private $type;
    private $alert;
    private $message;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id       = 0;
      $this->category = '';
      $this->type     = '';
      $this->alert    = '';
      $this->message  = '';
    }

    // Constructeur de l'objet ALerte en fonction des données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
      $alerte = new self();
      $alerte->fill($data);

      return $alerte;
    }

    protected function fill ($data)
    {
      if (isset($data['id']))
        $this->id       = $data['id'];

      if (isset($data['category']))
        $this->category = $data['category'];

      if (isset($data['type']))
        $this->type     = $data['type'];

      if (isset($data['alert']))
        $this->alert    = $data['alert'];

      if (isset($data['message']))
        $this->message  = $data['message'];
    }

    // Sécurisation des données
    public static function secureData($data)
    {
      $data->setCategory(htmlspecialchars($data->getCategory()));
      $data->setType(htmlspecialchars($data->getType()));
      $data->setAlert(htmlspecialchars($data->getAlert()));
      $data->setMessage(htmlspecialchars($data->getMessage()));
    }

    // getters et setters pour l'objet ALerte
    // id
    public function setId($id)
    {
      $this->id = $id;
    }

    public function getId()
    {
      return $this->id;
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

    // Type
    public function setType($type)
    {
      $this->type = $type;
    }

    public function getType()
    {
      return $this->type;
    }

    // Alerte
    public function setAlert($alert)
    {
      $this->alert = $alert;
    }

    public function getAlert()
    {
      return $this->alert;
    }

    // Message
    public function setMessage($message)
    {
      $this->message = $message;
    }

    public function getMessage()
    {
      return $this->message;
    }
  }
?>
