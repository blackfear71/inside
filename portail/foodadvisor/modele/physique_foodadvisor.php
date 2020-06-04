<?php
  include_once('../../includes/functions/appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture restaurants ouverts par lieu
  // RETOUR : Liste restaurants
  function physiqueRestaurantsOuvertsParLieux($lieu)
  {
    // Initialisations
    $restaurantsParLieux = array();
    $availableDay        = true;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM food_advisor_restaurants
                        WHERE location = "' . $lieu . '"
                        ORDER BY name ASC');

    while ($data = $req->fetch())
    {
      // Vérification restaurant ouvert ce jour
      $explodedOpened = explode(";", $data['opened']);

      foreach ($explodedOpened as $keyOpened => $opened)
      {
        if (!empty($opened))
        {
          if (date('N') == $keyOpened + 1 AND $opened == "N")
          {
            $availableDay = false;
            break;
          }
        }
      }

      // Récupération des données si ouvert
      if ($availableDay == true)
      {
        // Instanciation d'un objet Restaurant à partir des données remontées de la bdd
        $myRestaurant = Restaurant::withData($data);

        $myRestaurant->setMin_price(str_replace('.', ',', $myRestaurant->getMin_price()));
        $myRestaurant->setMax_price(str_replace('.', ',', $myRestaurant->getMax_price()));

        // On ajoute la ligne au tableau
        array_push($restaurantsParLieux, $myRestaurant);
      }
    }

    $req->closeCursor();

    // Retour
    return $restaurantsParLieux;
  }
?>
