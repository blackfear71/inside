<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture restaurants par lieu
  // RETOUR : Objet Restaurant
  function physiqueRestaurantsParLieux($lieu)
  {
    // Initialisations
    $restaurantsParLieux = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM food_advisor_restaurants
                        WHERE location = "' . $lieu . '"
                        ORDER BY name ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Restaurant à partir des données remontées de la bdd
      $myRestaurant = Restaurant::withData($data);

      $myRestaurant->setMin_price(str_replace('.', ',', $myRestaurant->getMin_price()));
      $myRestaurant->setMax_price(str_replace('.', ',', $myRestaurant->getMax_price()));

      array_push($restaurantsParLieux, $myRestaurant);
    }

    $req->closeCursor();

    // Retour
    return $restaurantsParLieux;
  }

  // PHYSIQUE : Lecture types de restaurants
  // RETOUR : Types concaténés
  function physiqueTypesRestaurants()
  {
    // Initialisations
    $types = '';

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT DISTINCT types
                        FROM food_advisor_restaurants');

    while ($data = $req->fetch())
    {
      $types .= $data['types'];
    }

    $req->closeCursor();

    // Retour
    return $types;
  }

  // PHYSIQUE : Lecture données élément Restaurant
  // RETOUR : Objet Restaurant
  function physiqueDonneesRestaurant($idRestaurant)
  {
    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM food_advisor_restaurants
                        WHERE id = ' . $idRestaurant);

    $data = $req->fetch();

    // Instanciation d'un objet Restaurant à partir des données remontées de la bdd
    $restaurant = Restaurant::withData($data);

    $req->closeCursor();

    // Retour
    return $restaurant;
  }

  /****************************************************************************/
  /********************************** INSERT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Insertion nouveau restaurant
  // RETOUR : Id alerte
  function physiqueInsertionRestaurant($restaurant)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('INSERT INTO food_advisor_restaurants(name,
                                                               picture,
                                                               types,
                                                               location,
                                                               phone,
                                                               opened,
                                                               min_price,
                                                               max_price,
                                                               website,
                                                               plan,
                                                               lafourchette,
                                                               description)
                                                        VALUES(:name,
                                                               :picture,
                                                               :types,
                                                               :location,
                                                               :phone,
                                                               :opened,
                                                               :min_price,
                                                               :max_price,
                                                               :website,
                                                               :plan,
                                                               :lafourchette,
                                                               :description)');

    $req->execute($restaurant);

    $newId = $bdd->lastInsertId();

    $req->closeCursor();

    // Retour
    return $newId;
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Mise à jour restaurant existant
  // RETOUR : Aucun
  function physiqueUpdateRestaurant($idRestaurant, $restaurant)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE food_advisor_restaurants SET name         = :name,
                                                              picture      = :picture,
                                                              types        = :types,
                                                              location     = :location,
                                                              phone        = :phone,
                                                              opened       = :opened,
                                                              min_price    = :min_price,
                                                              max_price    = :max_price,
                                                              website      = :website,
                                                              plan         = :plan,
                                                              lafourchette = :lafourchette,
                                                              description  = :description
                                                        WHERE id = ' . $idRestaurant);

    $req->execute($restaurant);

    $req->closeCursor();
  }

  /****************************************************************************/
  /********************************** DELETE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Suppression choix utilisateurs
  // RETOUR : Aucun
  function physiqueDeleteUsersChoices($idRestaurant)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM food_advisor_users
                       WHERE id_restaurant = ' . $idRestaurant);
  }

  // PHYSIQUE : Suppression determinations
  // RETOUR : Aucun
  function physiqueDeleteDeterminations($idRestaurant)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM food_advisor_choices
                       WHERE id_restaurant = ' . $idRestaurant);
  }

  // PHYSIQUE : Suppression restaurant
  // RETOUR : Aucun
  function physiqueDeleteRestaurant($idRestaurant)
  {
    // Requête
    global $bdd;

    $req = $bdd->exec('DELETE FROM food_advisor_restaurants
                       WHERE id = ' . $idRestaurant);
  }
?>
