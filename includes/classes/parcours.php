<?php
class Parcours{
    private $id;
    private $nom;
    private $distance;
    private $lieu;
    private $url_image;

    // Constructeur par défaut (objet vide)
    public function __construct(){
        $this->id = 0;
        $this->nom = '';
        $this->distance = 0;
        $this->lieu = '';
        $this->url_image = '';
    }

    // Constructeur de l'objet Parcours en fonction de données
    // -> il faut passer une variable $data contenant le résultat de la requête fetch
    public static function withData($data){
        $parcours = new self();
        $parcours->fill($data);
        return $parcours;
    }

    protected function fill ($data){
        $this->id = $data['id'];
        $this->nom = $data['nom'];
        $this->distance = $data['distance'];
        $this->lieu = $data['lieu'];
        $this->url_image = $data['image'];
    }

    // getters et setters pour l'objet Parcours
    // id
    public function setId($id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }


    // nom
    public function setNom($name){
        $this->nom = $name;
    }

    public function getNom(){
        return $this->nom;
    }

    // distance
    public function setDistance($dist){
        $this->distance = $dist;
    }

    public function getDistance(){
        return $this->distance;
    }

    // lieu
    public function setLieu($place){
        $this->lieu = $place;
    }

    public function getLieu(){
        return $this->lieu;
    }

    // url image
    public function setImage($imageUrl){
        $this->url_image = $imageUrl;
    }

    public function getImage(){
        return $this->url_image;
    }

    // Méthode pour savoir si url image présente ou non 
    public function isImageSet(){
        if (empty($this->url_image)){
            return false;
        }
        else{
            return true;
        }
    }
}
?>