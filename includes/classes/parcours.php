<?php
  class Parcours
  {
    private $id;
    private $team;
    private $nom;
    private $distance;
    private $lieu;
    private $image;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id       = 0;
      $this->team     = '';
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
        $this->id       = $data['id'];

      if (isset($data['team']))
        $this->team     = $data['team'];

      if (isset($data['nom']))
        $this->nom      = $data['nom'];

      if (isset($data['distance']))
        $this->distance = $data['distance'];

      if (isset($data['lieu']))
        $this->lieu     = $data['lieu'];

      if (isset($data['image']))
        $this->image    = $data['image'];
    }

    // Sécurisation des données
    public static function secureData($data)
    {
      //$data->setTeam(htmlspecialchars($data->getTeam()));
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

    // Equipe
    public function setTeam($team)
    {
      $this->team = $team;
    }

    public function getTeam()
    {
      return $this->team;
    }

    // Nom
    public function setNom($name)
    {
      $this->nom = $name;
    }

    public function getNom()
    {
      return $this->nom;
    }

    // Distance
    public function setDistance($distance)
    {
      $this->distance = $distance;
    }

    public function getDistance()
    {
      return $this->distance;
    }

    // Lieu
    public function setLieu($lieu)
    {
      $this->lieu = $lieu;
    }

    public function getLieu()
    {
      return $this->lieu;
    }

    // Image
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
