<?php
  class Success
  {
    private $id;
    private $reference;
    private $level;
    private $order_success;
    private $title;
    private $description;
    private $limit_success;
    private $explanation;
    private $defined;
    private $value_user;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id            = 0;
      $this->reference     = '';
      $this->level         = '';
      $this->order_success = '';
      $this->title         = '';
      $this->description   = '';
      $this->limit_success = '';
      $this->explanation   = '';
      $this->defined       = '';
      $this->value_user    = 0;
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

      if (isset($data['level']))
        $this->level         = $data['level'];

      if (isset($data['order_success']))
        $this->order_success = $data['order_success'];

      if (isset($data['defined']))
        $this->defined       = $data['defined'];

      if (isset($data['title']))
        $this->title         = $data['title'];

      if (isset($data['description']))
        $this->description   = $data['description'];

      if (isset($data['limit_success']))
        $this->limit_success = $data['limit_success'];

      if (isset($data['explanation']))
        $this->explanation   = $data['explanation'];
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

    // Niveau
    public function setLevel($level)
    {
      $this->level = $level;
    }

    public function getLevel()
    {
      return $this->level;
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

    // Succès défini
    public function setDefined($defined)
    {
      $this->defined = $defined;
    }

    public function getDefined()
    {
      return $this->defined;
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

    // Explications
    public function setExplanation($explanation)
    {
      $this->explanation = $explanation;
    }

    public function getExplanation()
    {
      return $this->explanation;
    }

    // Valeur utilisateur
    public function setValue_user($value_user)
    {
      $this->value_user = $value_user;
    }

    public function getValue_user()
    {
      return $this->value_user;
    }
  }
?>
