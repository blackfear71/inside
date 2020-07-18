<?php
  class Success
  {
    private $id;
    private $reference;
    private $level;
    private $order_success;
    private $defined;
    private $unicity;
    private $title;
    private $description;
    private $limit_success;
    private $explanation;
    private $value_user;
    private $classement;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->id            = 0;
      $this->reference     = '';
      $this->level         = '';
      $this->order_success = '';
      $this->defined       = '';
      $this->unicity       = '';
      $this->title         = '';
      $this->description   = '';
      $this->limit_success = '';
      $this->explanation   = '';
      $this->value_user    = 0;
      $this->classement    = array();
    }

    // Constructeur de l'objet Success en fonction des données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data)
    {
      $success = new self();
      $success->fill($data);

      return $success;
    }

    protected function fill ($data)
    {
      if (isset($data['id']))
        $this->id            = $data['id'];

      if (isset($data['reference']))
        $this->reference     = $data['reference'];

      if (isset($data['level']))
        $this->level         = $data['level'];

      if (isset($data['order_success']))
        $this->order_success = $data['order_success'];

      if (isset($data['defined']))
        $this->defined       = $data['defined'];

      if (isset($data['unicity']))
        $this->unicity       = $data['unicity'];

      if (isset($data['title']))
        $this->title         = $data['title'];

      if (isset($data['description']))
        $this->description   = $data['description'];

      if (isset($data['limit_success']))
        $this->limit_success = $data['limit_success'];

      if (isset($data['explanation']))
        $this->explanation   = $data['explanation'];
    }

    // Sécurisation des données
    public static function secureData($data)
    {
      $data->setReference(htmlspecialchars($data->getReference()));
      $data->setLevel(htmlspecialchars($data->getLevel()));
      $data->setOrder_success(htmlspecialchars($data->getOrder_success()));
      $data->setDefined(htmlspecialchars($data->getDefined()));
      $data->setUnicity(htmlspecialchars($data->getUnicity()));
      $data->setTitle(htmlspecialchars($data->getTitle()));
      $data->setDescription(htmlspecialchars($data->getDescription()));
      $data->setLimit_success(htmlspecialchars($data->getLimit_success()));
      $data->setExplanation(htmlspecialchars($data->getExplanation()));
      $data->setValue_user(htmlspecialchars($data->getValue_user()));

      foreach ($data->getClassement() as $rank)
      {
        Classement::secureData($rank);
      }
    }

    // getters et setters pour l'objet Success
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

    // Niveau
    public function setLevel($level)
    {
      $this->level = $level;
    }

    public function getLevel()
    {
      return $this->level;
    }

    // Ordonnancement
    public function setOrder_success($order_success)
    {
      $this->order_success = $order_success;
    }

    public function getOrder_success()
    {
      return $this->order_success;
    }

    // Succès défini
    public function setDefined($defined)
    {
      $this->defined = $defined;
    }

    public function getDefined()
    {
      return $this->defined;
    }

    // Unicité
    public function setUnicity($unicity)
    {
      $this->unicity = $unicity;
    }

    public function getUnicity()
    {
      return $this->unicity;
    }

    // Titre succès
    public function setTitle($title)
    {
      $this->title = $title;
    }

    public function getTitle()
    {
      return $this->title;
    }

    // Description succès
    public function setDescription($description)
    {
      $this->description = $description;
    }

    public function getDescription()
    {
      return $this->description;
    }

    public function getLogo()
    {
      return $this->logo;
    }

    // Limite succès
    public function setLimit_success($limit_success)
    {
      $this->limit_success = $limit_success;
    }

    public function getLimit_success()
    {
      return $this->limit_success;
    }

    // Explications
    public function setExplanation($explanation)
    {
      $this->explanation = $explanation;
    }

    public function getExplanation()
    {
      return $this->explanation;
    }

    // Valeur utilisateur
    public function setValue_user($value_user)
    {
      $this->value_user = $value_user;
    }

    public function getValue_user()
    {
      return $this->value_user;
    }

    // Classement
    public function setClassement($classement)
    {
      $this->classement = $classement;
    }

    public function getClassement()
    {
      return $this->classement;
    }
  }

  class Classement
  {
    private $identifiant;
    private $pseudo;
    private $avatar;
    private $value;
    private $rank;

    // Constructeur par défaut (objet vide)
    public function __construct()
    {
      $this->identifiant = '';
      $this->pseudo      = '';
      $this->avatar      = '';
      $this->value       = '';
      $this->rank        = 0;
    }

    // Sécurisation des données
    public static function secureData($data)
    {
      $data->setIdentifiant(htmlspecialchars($data->getIdentifiant()));
      $data->setPseudo(htmlspecialchars($data->getPseudo()));
      $data->setAvatar(htmlspecialchars($data->getAvatar()));
      $data->setValue(htmlspecialchars($data->getValue()));
      $data->setRank(htmlspecialchars($data->getRank()));
    }

    // getters et setters pour l'objet Classement
    // id
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

    // Avatar
    public function setAvatar($avatar)
    {
      $this->avatar = $avatar;
    }

    public function getAvatar()
    {
      return $this->avatar;
    }

    // Valeur du succès
    public function setValue($value)
    {
      $this->value = $value;
    }

    public function getValue()
    {
      return $this->value;
    }

    // Rang
    public function setRank($rank)
    {
      $this->rank = $rank;
    }

    public function getRank()
    {
      return $this->rank;
    }
  }
?>
