<?php
  class Mission
  {
    private $id;
    private $mission;
    private $reference;
    private $date_deb;
    private $date_fin;
    private $heure;
    private $objectif;
    private $description;
    private $explications;
    private $conclusion;
    private $statut;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id           = 0;
      $this->mission      = '';
      $this->reference    = '';
      $this->date_deb     = '';
      $this->date_fin     = '';
      $this->heure        = '';
      $this->objectif     = '';
      $this->description  = '';
      $this->explications = '';
      $this->conclusion   = '';
      $this->statut       = '';
    }

    // Constructeur de l'objet Mission en fonction des données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
      $mission = new self();
      $mission->fill($data);

      return $mission;
    }

    protected function fill ($data)
    {
      if (isset($data['id']))
        $this->id           = $data['id'];

      if (isset($data['mission']))
        $this->mission      = $data['mission'];

      if (isset($data['reference']))
        $this->reference    = $data['reference'];

      if (isset($data['date_deb']))
        $this->date_deb     = $data['date_deb'];

      if (isset($data['date_fin']))
        $this->date_fin     = $data['date_fin'];

      if (isset($data['heure']))
        $this->heure        = $data['heure'];

      if (isset($data['objectif']))
        $this->objectif     = $data['objectif'];

      if (isset($data['description']))
        $this->description  = $data['description'];

      if (isset($data['explications']))
        $this->explications = $data['explications'];

      if (isset($data['conclusion']))
        $this->conclusion   = $data['conclusion'];
    }

    // getters et setters pour l'objet Mission
    // id
    public function setId($id)
    {
      $this->id = $id;
    }

    public function getId()
    {
      return $this->id;
    }

    // Mission
    public function setMission($mission)
    {
      $this->mission = $mission;
    }

    public function getMission()
    {
      return $this->mission;
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

    // Heure
    public function setHeure($heure)
    {
      $this->heure = $heure;
    }

    public function getHeure()
    {
      return $this->heure;
    }

    // Objectif
    public function setObjectif($objectif)
    {
      $this->objectif = $objectif;
    }

    public function getObjectif()
    {
      return $this->objectif;
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

    // Explications
    public function setExplications($explications)
    {
      $this->explications = $explications;
    }

    public function getExplications()
    {
      return $this->explications;
    }

    // Conclusion
    public function setConclusion($conclusion)
    {
      $this->conclusion = $conclusion;
    }

    public function getConclusion()
    {
      return $this->conclusion;
    }

    // Statut mission
    public function setStatut($statut)
    {
      $this->statut = $statut;
    }

    public function getStatut()
    {
      return $this->statut;
    }
  }
?>
