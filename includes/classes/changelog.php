<?php
  class ChangeLog
  {
    private $id;
    private $week;
    private $year;
    private $notes;
    private $logs;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id    = 0;
      $this->week  = '';
      $this->year  = '';
      $this->notes = '';
      $this->logs  = array();
    }

    // Constructeur de l'objet ChangeLog en fonction des données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
      $changeLog = new self();
      $changeLog->fill($data);

      return $changeLog;
    }

    protected function fill ($data)
    {
      if (isset($data['id']))
        $this->id    = $data['id'];

      if (isset($data['week']))
        $this->week  = $data['week'];

      if (isset($data['year']))
        $this->year  = $data['year'];

      if (isset($data['notes']))
        $this->notes = $data['notes'];
    }

    // getters et setters pour l'objet ChangeLog
    // id
    public function setId($id)
    {
      $this->id = $id;
    }

    public function getId()
    {
      return $this->id;
    }

    // Semaine
    public function setWeek($week)
    {
      $this->week = $week;
    }

    public function getWeek()
    {
      return $this->week;
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

    // Notes
    public function setNotes($notes)
    {
      $this->notes = $notes;
    }

    public function getNotes()
    {
      return $this->notes;
    }

    // Logs
    public function setLogs($logs)
    {
      $this->logs = $logs;
    }

    public function getLogs()
    {
      return $this->logs;
    }
  }

  class ChangeLogParameters
  {
    private $action;
    private $year;
    private $week;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->action = '';
      $this->year   = '';
      $this->week   = '';
    }

    // getters et setters pour l'objet ChangeLogParameters
    // Action
    public function setAction($action)
    {
      $this->action = $action;
    }

    public function getAction()
    {
      return $this->action;
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

    // Semaine
    public function setWeek($week)
    {
      $this->week = $week;
    }

    public function getWeek()
    {
      return $this->week;
    }
  }
?>
