<?php
  class Calendrier
  {
    private $id;
    private $to_delete;
    private $month;
    private $year;
    private $title;
    private $calendar;
    private $width;
    private $height;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id        = 0;
      $this->to_delete = '';
      $this->month     = '';
      $this->year      = '';
      $this->title     = '';
      $this->calendar  = '';
      $this->width     = '';
      $this->height    = '';
    }

    // Constructeur de l'objet Calendrier en fonction des données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
      $calendar = new self();
      $calendar->fill($data);

      return $calendar;
    }

    protected function fill ($data)
    {
      if (isset($data['id']))
        $this->id        = $data['id'];

      if (isset($data['to_delete']))
        $this->to_delete = $data['to_delete'];

      if (isset($data['month']))
        $this->month     = $data['month'];

      if (isset($data['year']))
        $this->year      = $data['year'];

      if (isset($data['calendar']))
        $this->calendar  = $data['calendar'];
    }

    // getters et setters pour l'objet Calendrier
    // id
    public function setId($id)
    {
      $this->id = $id;
    }

    public function getId()
    {
      return $this->id;
    }

    // Indicateur suppression
    public function setTo_delete($to_delete)
    {
      $this->to_delete = $to_delete;
    }

    public function getTo_delete()
    {
      return $this->to_delete;
    }

    // Mois
    public function setMonth($month)
    {
      $this->month = $month;
    }

    public function getMonth()
    {
      return $this->month;
    }

    // Année
    public function setYear($year)
    {
      $this->year = $year;
    }

    public function getYear()
    {
      return $this->year;
    }

    // Titre
    public function setTitle($title)
    {
      $this->title = $title;
    }

    public function getTitle()
    {
      return $this->title;
    }

    // Calendrier
    public function setCalendar($calendar)
    {
      $this->calendar = $calendar;
    }

    public function getCalendar()
    {
      return $this->calendar;
    }

    // Largeur calendrier
    public function setWidth($width)
    {
      $this->width = $width;
    }

    public function getWidth()
    {
      return $this->width;
    }

    // Hauteur calendrier
    public function setHeight($height)
    {
      $this->height = $height;
    }

    public function getHeight()
    {
      return $this->height;
    }
  }

  class Annexe
  {
    private $id;
    private $to_delete;
    private $annexe;
    private $title;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id        = 0;
      $this->to_delete = '';
      $this->annexe    = '';
      $this->title     = '';
    }

    // Constructeur de l'objet Annexe en fonction des données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
      $calendar = new self();
      $calendar->fill($data);

      return $calendar;
    }

    protected function fill ($data)
    {
      if (isset($data['id']))
        $this->id        = $data['id'];

      if (isset($data['to_delete']))
        $this->to_delete = $data['to_delete'];

      if (isset($data['annexe']))
        $this->annexe    = $data['annexe'];

      if (isset($data['title']))
        $this->title     = $data['title'];
    }

    // getters et setters pour l'objet Annexe
    // id
    public function setId($id)
    {
      $this->id = $id;
    }

    public function getId()
    {
      return $this->id;
    }

    // Indicateur suppression
    public function setTo_delete($to_delete)
    {
      $this->to_delete = $to_delete;
    }

    public function getTo_delete()
    {
      return $this->to_delete;
    }

    // Annexe
    public function setAnnexe($annexe)
    {
      $this->annexe = $annexe;
    }

    public function getAnnexe()
    {
      return $this->annexe;
    }

    // Titre
    public function setTitle($title)
    {
      $this->title = $title;
    }

    public function getTitle()
    {
      return $this->title;
    }
  }
?>
