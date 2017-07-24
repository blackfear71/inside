<?php
class Parcours{
    private $nom;
    private $distance;
    private $lieu;
    private $url_image;

    // constructeur de l'objet Parcours
    // il faut passer une variable $data contenant le résultat de la requête fetch
    public function __construct($data){
        $this->nom = $data['nom'];
        $this->distance = $data['distance'];
        $this->lieu = $data['lieu'];
        $this->url_image = $data['image'];
    }
    // getters et setters pour l'objet Parcours
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
}
?>