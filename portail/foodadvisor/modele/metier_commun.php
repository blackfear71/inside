<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/restaurants.php');

  // METIER : Récupération de la liste des lieux
  // RETOUR : Liste des lieux
  function getLieux()
  {
    $listLocations = array();

    global $bdd;

    $reponse = $bdd->query('SELECT DISTINCT location FROM food_advisor_restaurants ORDER BY location ASC');
    while ($donnees = $reponse->fetch())
    {
      array_push($listLocations, $donnees['location']);
    }
    $reponse->closeCursor();

    return $listLocations;
  }

  // METIER : Récupération de la liste des restaurants
  // RETOUR : Liste des restaurants
  function getRestaurants($list_locations)
  {
    $listRestaurants = array();

    global $bdd;

    foreach ($list_locations as $location)
    {
      $restaurants_by_location = array();

      $reponse = $bdd->query('SELECT * FROM food_advisor_restaurants WHERE location = "' . $location . '" ORDER BY name ASC');
      while ($donnees = $reponse->fetch())
      {
        $myRestaurant = Restaurant::withData($donnees);
        $myRestaurant->setMin_price(str_replace('.', ',', $myRestaurant->getMin_price()));
        $myRestaurant->setMax_price(str_replace('.', ',', $myRestaurant->getMax_price()));

        array_push($restaurants_by_location, $myRestaurant);
      }
      $reponse->closeCursor();

      $listRestaurants[$location] = $restaurants_by_location;
    }

    return $listRestaurants;
  }
?>
