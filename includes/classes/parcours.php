<?php
  class Parcours
  {
    private $id;
    private $nom;
    private $distance;
    private $lieu;
    private $image;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id       = 0;
      $this->nom      = '';
      $this->distance = 0;
      $this->lieu     = '';
      $this->image    = '';
    }

    // Constructeur de l'objet Parcours en fonction de données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
        $parcours = new self();
        $parcours->fill($data);

        return $parcours;
    }

    protected function fill ($data)
    {
      if (isset($data['id']))
        $this->id = $data['id'];

      if (isset($data['id']))
        $this->nom = $data['nom'];

      if (isset($data['id']))
        $this->distance = $data['distance'];

      if (isset($data['id']))
        $this->lieu = $data['lieu'];

      if (isset($data['id']))
        $this->image = $data['image'];
    }

    // Sécurisation des données
    public static function secureData($data)
    {
      $data->setNom(htmlspecialchars($data->getNom()));
      $data->setDistance(htmlspecialchars($data->getDistance()));
      $data->setLieu(htmlspecialchars($data->getLieu()));
      $data->setImage(htmlspecialchars($data->getImage()));
    }

    // Getters et Setters pour l'objet Parcours
    // id
    public function setId($id)
    {
      $this->id = $id;
    }

    public function getId()
    {
      return $this->id;
    }

    // nom
    public function setNom($name)
    {
      $this->nom = $name;
    }

    public function getNom()
    {
      return $this->nom;
    }

    // distance
    public function setDistance($dist)
    {
      $this->distance = $dist;
    }

    public function getDistance()
    {
      return $this->distance;
    }

    // lieu
    public function setLieu($place)
    {
      $this->lieu = $place;
    }

    public function getLieu()
    {
      return $this->lieu;
    }

    // url image
    public function setImage($image)
    {
      $this->image = $image;
    }

    public function getImage()
    {
      return $this->image;
    }
  }
?>
