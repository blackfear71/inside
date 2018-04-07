<?php
  class Profile
  {
    private $id;
    private $identifiant;
    private $ping;
    private $connected;
    private $reset;
    private $pseudo;
    private $avatar;
    private $email;
    private $beginner;
    private $developper;
    private $expenses;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id          = 0;
      $this->identifiant = '';
      $this->ping        = '';
      $this->connected   = '';
      $this->reset       = '';
      $this->pseudo      = '';
      $this->avatar      = '';
      $this->email       = '';
      $this->beginner    = '';
      $this->developper  = '';
      $this->expenses    = '';
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

      if (isset($data['ping']))
        $this->ping        = $data['ping'];

      if (isset($data['reset']))
        $this->reset       = $data['reset'];

      if (isset($data['pseudo']))
        $this->pseudo      = $data['pseudo'];

      if (isset($data['avatar']))
        $this->avatar      = $data['avatar'];

      if (isset($data['email']))
        $this->email       = $data['email'];

      if (isset($data['beginner']))
        $this->beginner    = $data['beginner'];

      if (isset($data['developper']))
        $this->developper  = $data['developper'];

      if (isset($data['expenses']))
        $this->expenses    = $data['expenses'];
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

    // Ping de connexion
    public function setPing($ping)
    {
      $this->ping = $ping;
    }

    public function getPing()
    {
      return $this->ping;
    }

    // Statut de connexion
    public function setConnected($connected)
    {
      $this->connected = $connected;
    }

    public function getConnected()
    {
      return $this->connected;
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
    public function setPseudo($pseudo)
    {
      $this->pseudo = $pseudo;
    }

    public function getPseudo()
    {
      return $this->pseudo;
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

    // Email
    public function setEmail($email)
    {
      $this->email = $email;
    }

    public function getEmail()
    {
      return $this->email;
    }

    // True Insider
    public function setBeginner($beginner)
    {
      $this->beginner = $beginner;
    }

    public function getBeginner()
    {
      return $this->beginner;
    }

    // Développeur
    public function setDevelopper($developper)
    {
      $this->developper = $developper;
    }

    public function getDevelopper()
    {
      return $this->developper;
    }

    // Dépenses (total)
    public function setExpenses($expenses)
    {
      $this->expenses = $expenses;
    }

    public function getExpenses()
    {
      return $this->expenses;
    }
  }

  class Statistiques
  {
    private $nb_films_ajoutes;
    private $nb_comments;
    private $expenses;
    private $nb_collectors;
    private $nb_ideas;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->nb_films_ajoutes = 0;
      $this->nb_comments      = 0;
      $this->expenses         = 0;
      $this->nb_collectors    = 0;
      $this->nb_ideas         = 0;
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
      if (isset($data['nb_films_ajoutes']))
        $this->nb_films_ajoutes = $data['nb_films_ajoutes'];

      if (isset($data['nb_comments']))
        $this->nb_comments      = $data['nb_comments'];

      if (isset($data['expenses']))
        $this->expenses         = $data['expenses'];

      if (isset($data['nb_collectors']))
        $this->nb_collectors    = $data['nb_collectors'];

      if (isset($data['nb_ideas']))
        $this->nb_ideas         = $data['nb_ideas'];
    }

    // getters et setters pour l'objet Statistiques
    // Nombre de films ajoutés Movie House
    public function setNb_films_ajoutes($nb_films_ajoutes)
    {
      $this->nb_films_ajoutes = $nb_films_ajoutes;
    }

    public function getNb_films_ajoutes()
    {
      return $this->nb_films_ajoutes;
    }

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

    // Nombre de phrases cultes soumises
    public function setNb_collectors($nb_collectors)
    {
      $this->nb_collectors = $nb_collectors;
    }

    public function getNb_collectors()
    {
      return $this->nb_collectors;
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
    private $ref_theme;
    private $view_movie_house;
    private $categories_home;
    private $today_movie_house;
    private $view_old_movies;
    private $view_the_box;
    private $view_notifications;
    private $manage_calendars;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id                 = 0;
      $this->ref_theme          = '';
      $this->view_movie_house   = '';
      $this->categories_home    = '';
      $this->today_movie_house  = '';
      $this->view_old_movies    = '';
      $this->view_the_box       = '';
      $this->view_notifications = '';
      $this->manage_calendars   = '';
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
        $this->id                 = $data['id'];

      if (isset($data['ref_theme']))
        $this->ref_theme          = $data['ref_theme'];

      if (isset($data['view_movie_house']))
        $this->view_movie_house   = $data['view_movie_house'];

      if (isset($data['categories_home']))
        $this->categories_home    = $data['categories_home'];

      if (isset($data['today_movie_house']))
        $this->today_movie_house  = $data['today_movie_house'];

      if (isset($data['view_old_movies']))
        $this->view_old_movies    = $data['view_old_movies'];

      if (isset($data['view_the_box']))
        $this->view_the_box       = $data['view_the_box'];

      if (isset($data['view_notifications']))
        $this->view_notifications = $data['view_notifications'];

      if (isset($data['manage_calendars']))
        $this->manage_calendars   = $data['manage_calendars'];
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

    // Référence thème
    public function setRef_theme($ref_theme)
    {
      $this->ref_theme = $ref_theme;
    }

    public function getRef_theme()
    {
      return $this->ref_theme;
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

    // Préférence affichage anciens films
    public function setView_old_movies($view_old_movies)
    {
      $this->view_old_movies = $view_old_movies;
    }

    public function getView_old_movies()
    {
      return $this->view_old_movies;
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

    // Préférence vue par défaut Notifications
    public function setView_notifications($view_notifications)
    {
      $this->view_notifications = $view_notifications;
    }

    public function getView_notifications()
    {
      return $this->view_notifications;
    }

    // Préférence (admin) gestion des calendriers
    public function setManage_calendars($manage_calendars)
    {
      $this->manage_calendars = $manage_calendars;
    }

    public function getManage_calendars()
    {
      return $this->manage_calendars;
    }
  }
?>
