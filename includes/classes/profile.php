<?php
  class Profile
  {
    private $id;
    private $identifiant;
    private $reset;
    private $full_name;
    private $avatar;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id          = 0;
      $this->identifiant = '';
      $this->reset       = '';
      $this->full_name   = '';
      $this->avatar      = '';
    }

    // Constructeur de l'objet Profile en fonction des données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
      $profile = new self();
      $profile->fill($data);

      return $profile;
    }

    protected function fill ($data)
    {
      if (isset($data['id']))
        $this->id          = $data['id'];

      if (isset($data['identifiant']))
        $this->identifiant = $data['identifiant'];

      if (isset($data['reset']))
        $this->reset       = $data['reset'];

      if (isset($data['full_name']))
        $this->full_name   = $data['full_name'];

      if (isset($data['avatar']))
        $this->avatar      = $data['avatar'];
    }

    // getters et setters pour l'objet Profile
    // id
    public function setId($id)
    {
      $this->id = $id;
    }

    public function getId()
    {
      return $this->id;
    }

    // Identifiant
    public function setIdentifiant($identifiant)
    {
      $this->identifiant = $identifiant;
    }

    public function getIdentifiant()
    {
      return $this->identifiant;
    }

    // Top statut inscription
    public function setReset($reset)
    {
      $this->reset = $reset;
    }

    public function getReset()
    {
      return $this->reset;
    }

    // Pseudo
    public function setFull_name($full_name)
    {
      $this->full_name = $full_name;
    }

    public function getFull_name()
    {
      return $this->full_name;
    }

    // Avatar
    public function setAvatar($avatar)
    {
      $this->avatar = $avatar;
    }

    public function getAvatar()
    {
      return $this->avatar;
    }
  }

  class Statistiques
  {
    private $nb_comments;
    private $expenses;
    private $nb_ideas;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->nb_comments = 0;
      $this->expenses    = 0;
      $this->nb_ideas    = 0;
    }

    // Constructeur de l'objet Statistiques en fonction des données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
      $statistiques = new self();
      $statistiques->fill($data);

      return $statistiques;
    }

    protected function fill ($data)
    {
      if (isset($data['nb_comments']))
        $this->nb_comments = $data['nb_comments'];

      if (isset($data['expenses']))
        $this->expenses    = $data['expenses'];

      if (isset($data['nb_ideas']))
        $this->nb_ideas    = $data['nb_ideas'];
    }

    // getters et setters pour l'objet Statistiques
    // Nombre de commentaires Movie House
    public function setNb_comments($nb_comments)
    {
      $this->nb_comments = $nb_comments;
    }

    public function getNb_comments()
    {
      return $this->nb_comments;
    }

    // Solde des dépenses
    public function setExpenses($expenses)
    {
      $this->expenses = $expenses;
    }

    public function getExpenses()
    {
      return $this->expenses;
    }

    // Nombre d'idées soumises
    public function setNb_ideas($nb_ideas)
    {
      $this->nb_ideas = $nb_ideas;
    }

    public function getNb_ideas()
    {
      return $this->nb_ideas;
    }
  }

  class Preferences
  {
    private $id;
    private $view_movie_house;
    private $categories_home;
    private $today_movie_house;
    private $view_the_box;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id                = 0;
      $this->view_movie_house  = '';
      $this->categories_home   = '';
      $this->today_movie_house = '';
      $this->view_the_box      = '';
    }

    // Constructeur de l'objet Preferences en fonction des données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
      $preferences = new self();
      $preferences->fill($data);

      return $preferences;
    }

    protected function fill ($data)
    {
      if (isset($data['id']))
        $this->id                = $data['id'];

      if (isset($data['view_movie_house']))
        $this->view_movie_house  = $data['view_movie_house'];

      if (isset($data['categories_home']))
        $this->categories_home   = $data['categories_home'];

      if (isset($data['today_movie_house']))
        $this->today_movie_house = $data['today_movie_house'];

      if (isset($data['view_the_box']))
        $this->view_the_box      = $data['view_the_box'];
    }

    // getters et setters pour l'objet Preferences
    // id
    public function setId($id)
    {
      $this->id = $id;
    }

    public function getId()
    {
      return $this->id;
    }

    // Préférence vue par défaut Movie House
    public function setView_movie_house($view_movie_house)
    {
      $this->view_movie_house = $view_movie_house;
    }

    public function getView_movie_house()
    {
      return $this->view_movie_house;
    }

    // Préférence catégories affichéees Movie House
    public function setCategories_home($categories_home)
    {
      $this->categories_home = $categories_home;
    }

    public function getCategories_home()
    {
      return $this->categories_home;
    }

    // Préférence affichage date du jour Movie House
    public function setToday_movie_house($today_movie_house)
    {
      $this->today_movie_house = $today_movie_house;
    }

    public function getToday_movie_house()
    {
      return $this->today_movie_house;
    }

    // Préférence vue par défaut #TheBox
    public function setView_the_box($view_the_box)
    {
      $this->view_the_box = $view_the_box;
    }

    public function getView_the_box()
    {
      return $this->view_the_box;
    }
  }
?>
