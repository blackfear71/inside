<?php
  class Team
  {
    private $id;
    private $reference;
    private $team;
    private $short;
    private $activation;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id         = 0;
      $this->reference  = '';
      $this->team       = '';
      $this->short      = '';
      $this->activation = '';
    }

    // Constructeur de l'objet Team en fonction des données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
      $team = new self();
      $team->fill($data);

      return $team;
    }

    protected function fill ($data)
    {
      if (isset($data['id']))
        $this->id         = $data['id'];

      if (isset($data['reference']))
        $this->reference  = $data['reference'];

      if (isset($data['team']))
        $this->team       = $data['team'];

      if (isset($data['short']))
        $this->short      = $data['short'];

      if (isset($data['activation']))
        $this->activation = $data['activation'];
    }

    // Sécurisation des données
    public static function secureData($data)
    {
      //$data->setReference(htmlspecialchars($data->getReference()));
      $data->setTeam(htmlspecialchars($data->getTeam()));
      $data->setShort(htmlspecialchars($data->getShort()));
      $data->setActivation(htmlspecialchars($data->getActivation()));
    }

    // Getters et Setters pour l'objet Team
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

    // Nom de l'équipe
    public function setTeam($team)
    {
      $this->team = $team;
    }

    public function getTeam()
    {
      return $this->team;
    }

    // Nom court de l'équipe
    public function setShort($short)
    {
      $this->short = $short;
    }

    public function getShort()
    {
      return $this->short;
    }

    // Indicateur d'activation
    public function setActivation($activation)
    {
      $this->activation = $activation;
    }

    public function getActivation()
    {
      return $this->activation;
    }
  }
?>
