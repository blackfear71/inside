<?php
  class Theme
  {
    private $id;
    private $reference;
    private $name;
    private $logo;
    private $date_deb;
    private $date_fin;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id        = 0;
      $this->reference = '';
      $this->name      = '';
      $this->logo      = '';
      $this->date_deb  = '';
      $this->date_fin  = '';
    }

    // Constructeur de l'objet Theme en fonction des données
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
        $this->id        = $data['id'];

      if (isset($data['reference']))
        $this->reference = $data['reference'];

      if (isset($data['name']))
        $this->name      = $data['name'];

      if (isset($data['logo']))
        $this->logo      = $data['logo'];

      if (isset($data['date_deb']))
        $this->date_deb  = $data['date_deb'];

      if (isset($data['date_fin']))
        $this->date_fin  = $data['date_fin'];
    }

    // getters et setters pour l'objet Theme
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

    // Nom
    public function setName($name)
    {
      $this->name = $name;
    }

    public function getName()
    {
      return $this->name;
    }

    // Présence logo
    public function setLogo($logo)
    {
      $this->logo = $logo;
    }

    public function getLogo()
    {
      return $this->logo;
    }

    // Date début
    public function setDate_deb($date_deb)
    {
      $this->date_deb = $date_deb;
    }

    public function getDate_deb()
    {
      return $this->date_deb;
    }

    // Date fin
    public function setDate_fin($date_fin)
    {
      $this->date_fin = $date_fin;
    }

    public function getDate_fin()
    {
      return $this->date_fin;
    }
  }
?>
