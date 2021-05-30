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

    // Sécurisation des données
    public static function secureData($data)
    {
      $data->setTo_delete(htmlspecialchars($data->getTo_delete()));
      $data->setMonth(htmlspecialchars($data->getMonth()));
      $data->setYear(htmlspecialchars($data->getYear()));
      $data->setCalendar(htmlspecialchars($data->getCalendar()));
      $data->setTitle(htmlspecialchars($data->getTitle()));
    }

    // Getters et Setters pour l'objet Calendrier
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

    // Calendrier
    public function setCalendar($calendar)
    {
      $this->calendar = $calendar;
    }

    public function getCalendar()
    {
      return $this->calendar;
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

    // Sécurisation des données
    public static function secureData($data)
    {
      $data->setTo_delete(htmlspecialchars($data->getTo_delete()));
      $data->setAnnexe(htmlspecialchars($data->getAnnexe()));
      $data->setTitle(htmlspecialchars($data->getTitle()));
    }

    // Getters et Setters pour l'objet Annexe
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

  class AutorisationCalendriers
  {
    private $identifiant;
    private $pseudo;
    private $manage_calendars;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->identifiant      = '';
      $this->pseudo           = '';
      $this->manage_calendars = '';
    }

    // Constructeur de l'objet AutorisationCalendriers en fonction des données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
      $autorisation = new self();
      $autorisation->fill($data);

      return $autorisation;
    }

    protected function fill ($data)
    {
      if (isset($data['identifiant']))
        $this->identifiant      = $data['identifiant'];

      if (isset($data['manage_calendars']))
        $this->manage_calendars = $data['manage_calendars'];
    }

    // Sécurisation des données
    public static function secureData($data)
    {
      $data->setIdentifiant(htmlspecialchars($data->getIdentifiant()));
      $data->setPseudo(htmlspecialchars($data->getPseudo()));
      $data->setManage_calendars(htmlspecialchars($data->getManage_calendars()));
    }

    // Getters et Setters pour l'objet AutorisationCalendriers
    // Identifiant
    public function setIdentifiant($identifiant)
    {
      $this->identifiant = $identifiant;
    }

    public function getIdentifiant()
    {
      return $this->identifiant;
    }

    // Pseudo
    public function setPseudo($pseudo)
    {
      $this->pseudo = $pseudo;
    }

    public function getPseudo()
    {
      return $this->pseudo;
    }

    // Autorisation gestion calendriers
    public function setManage_calendars($manage_calendars)
    {
      $this->manage_calendars = $manage_calendars;
    }

    public function getManage_calendars()
    {
      return $this->manage_calendars;
    }
  }

  class CalendarParameters
  {
    private $month;
    private $year;
    private $picture;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->month   = '';
      $this->year    = '';
      $this->picture = '';
    }

    // Sécurisation des données
    public static function secureData($data)
    {
      $data->setMonth(htmlspecialchars($data->getMonth()));
      $data->setYear(htmlspecialchars($data->getYear()));
      $data->setPicture(htmlspecialchars($data->getPicture()));
    }

    // Getters et Setters pour l'objet CalendarParameters
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

    // Image
    public function setPicture($picture)
    {
      $this->picture = $picture;
    }

    public function getPicture()
    {
      return $this->picture;
    }
  }
?>
