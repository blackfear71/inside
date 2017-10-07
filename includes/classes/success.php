<?php
  class Success
  {
    private $id;
    private $reference;
    private $order_success;
    private $title;
    private $description;
    private $limit_success;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id            = 0;
      $this->reference     = '';
      $this->order_success = '';
      $this->title         = '';
      $this->description   = '';
      $this->limit_success = '';
    }

    // Constructeur de l'objet Success en fonction des données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
      $success = new self();
      $success->fill($data);

      return $success;
    }

    protected function fill ($data)
    {
      if (isset($data['id']))
        $this->id            = $data['id'];

      if (isset($data['reference']))
        $this->reference     = $data['reference'];

      if (isset($data['order_success']))
        $this->order_success = $data['order_success'];

      if (isset($data['title']))
        $this->title         = $data['title'];

      if (isset($data['description']))
        $this->description   = $data['description'];

      if (isset($data['limit_success']))
        $this->limit_success = $data['limit_success'];
    }

    // getters et setters pour l'objet Success
    // id
    public function setId($id)
    {
      $this->id = $id;
    }

    public function getId()
    {
      return $this->id;
    }

    // Référence
    public function setReference($reference)
    {
      $this->reference = $reference;
    }

    public function getReference()
    {
      return $this->reference;
    }

    // Ordonnancement
    public function setOrder_success($order_success)
    {
      $this->order_success = $order_success;
    }

    public function getOrder_success()
    {
      return $this->order_success;
    }

    // Titre succès
    public function setTitle($title)
    {
      $this->title = $title;
    }

    public function getTitle()
    {
      return $this->title;
    }

    // Description succès
    public function setDescription($description)
    {
      $this->description = $description;
    }

    public function getDescription()
    {
      return $this->description;
    }

    public function getLogo()
    {
      return $this->logo;
    }

    // Limite succès
    public function setLimit_success($limit_success)
    {
      $this->limit_success = $limit_success;
    }

    public function getLimit_success()
    {
      return $this->limit_success;
    }
  }
?>
