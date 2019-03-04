<?php
  class Restaurant
  {
    private $id;
    private $name;
    private $picture;
    private $types;
    private $location;
    private $phone;
    private $website;
    private $plan;
    private $description;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id          = 0;
      $this->name        = '';
      $this->picture     = '';
      $this->types       = '';
      $this->location    = '';
      $this->phone       = '';
      $this->website     = '';
      $this->plan        = '';
      $this->description = '';
    }

    // Constructeur de l'objet Restaurant en fonction des données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
      $restaurant = new self();
      $restaurant->fill($data);

      return $restaurant;
    }

    protected function fill ($data)
    {
      if (isset($data['id']))
        $this->id          = $data['id'];

      if (isset($data['name']))
        $this->name        = $data['name'];

      if (isset($data['picture']))
        $this->picture     = $data['picture'];

      if (isset($data['types']))
        $this->types       = $data['types'];

      if (isset($data['location']))
        $this->location    = $data['location'];

      if (isset($data['phone']))
        $this->phone       = $data['phone'];

      if (isset($data['website']))
        $this->website     = $data['website'];

      if (isset($data['plan']))
        $this->plan        = $data['plan'];

      if (isset($data['description']))
        $this->description = $data['description'];
    }

    // getters et setters pour l'objet Restaurant
    // id
    public function setId($id)
    {
      $this->id = $id;
    }

    public function getId()
    {
      return $this->id;
    }

    // Nom du restaurant
    public function setName($name)
    {
      $this->name = $name;
    }

    public function getName()
    {
      return $this->name;
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

    // Types de restaurant
    public function setTypes($types)
    {
      $this->types = $types;
    }

    public function getTypes()
    {
      return $this->types;
    }

    // Lieu
    public function setLocation($location)
    {
      $this->location = $location;
    }

    public function getLocation()
    {
      return $this->location;
    }

    // Numéro de téléphone
    public function setPhone($phone)
    {
      $this->phone = $phone;
    }

    public function getPhone()
    {
      return $this->phone;
    }

    // Site web
    public function setWebsite($website)
    {
      $this->website = $website;
    }

    public function getWebsite()
    {
      return $this->website;
    }

    // Plan
    public function setPlan($plan)
    {
      $this->plan = $plan;
    }

    public function getPlan()
    {
      return $this->plan;
    }

    // Description
    public function setDescription($description)
    {
      $this->description = $description;
    }

    public function getDescription()
    {
      return $this->description;
    }
  }
?>
