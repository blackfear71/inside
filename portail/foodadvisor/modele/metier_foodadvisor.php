<?php
  include_once('../../includes/functions/appel_bdd.php');
  include_once('../../includes/classes/restaurants.php');
  include_once('../../includes/libraries/php/imagethumb.php');

  // METIER : Récupération de la liste des lieux
  // RETOUR : Liste des lieux
  function getLieux()
  {
    $listLocations = array();

    global $bdd;

    $reponse = $bdd->query('SELECT DISTINCT location FROM food_advisor_restaurants ORDER BY location ASC');
    while($donnees = $reponse->fetch())
    {
      array_push($listLocations, $donnees['location']);
    }
    $reponse->closeCursor();

    return $listLocations;
  }

  // METIER : Récupération de la liste des types
  // RETOUR : Liste des types
  function getTypesRestaurants()
  {
    $listTypes   = array();
    $stringTypes = "";

    global $bdd;

    // Lecture des types
    $reponse = $bdd->query('SELECT DISTINCT types FROM food_advisor_restaurants');
    while($donnees = $reponse->fetch())
    {
      $stringTypes .= $donnees['types'];
    }
    $reponse->closeCursor();

    // Extraction et tri
    $explodedTypes = explode(";", $stringTypes);

    foreach ($explodedTypes as $exploded)
    {
      if (!empty($exploded))
        array_push($listTypes, $exploded);
    }

    $listTypes = array_unique($listTypes);
    asort($listTypes);

    return $listTypes;
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
      while($donnees = $reponse->fetch())
      {
        array_push($restaurants_by_location, Restaurant::withData($donnees));
      }
      $reponse->closeCursor();

      $listRestaurants[$location] = $restaurants_by_location;
    }

    return $listRestaurants;
  }

  // METIER : Insère un nouveau restaurant
  // RETOUR : Id enregistrement créé
  function insertRestaurant($post, $files, $user)
  {
    $new_id     = NULL;
    $control_ok = true;

    // Récupération des données et sauvegarde en session
    if ($control_ok == true)
    {
      $nom_restaurant         = $post['name_restaurant'];
      $telephone_restaurant   = $post['phone_restaurant'];
      $website_restaurant     = $post['website_restaurant'];
      $plan_restaurant        = $post['plan_restaurant'];
      $description_restaurant = $post['description_restaurant'];

      if ($post['location'] == "other_location"  AND !empty($post['saisie_other_location']))
        $lieu_restaurant      = $post['saisie_other_location'];
      else
        $lieu_restaurant      = $post['location'];

      if (isset($post['types_restaurants']))
      {
        $types_restaurant     = array_unique($post['types_restaurants']);

        foreach ($types_restaurant as $keyType => $type)
        {
          if (empty($type))
            unset($types_restaurant[$keyType]);
        }
      }

      $_SESSION['save']['name_restaurant']        = $post['name_restaurant'];
      $_SESSION['save']['phone_restaurant']       = $post['phone_restaurant'];
      $_SESSION['save']['website_restaurant']     = $post['website_restaurant'];
      $_SESSION['save']['plan_restaurant']        = $post['plan_restaurant'];
      $_SESSION['save']['description_restaurant'] = $post['description_restaurant'];
      $_SESSION['save']['location']               = $post['location'];
      $_SESSION['save']['saisie_other_location']  = $post['saisie_other_location'];

      if (isset($post['types_restaurants']))
        $_SESSION['save']['types_restaurants']    = $types_restaurant;
    }

    // Tri et formatage types de restaurants
    if ($control_ok == true)
    {
      $types_formatted = "";

      if (!empty($types_restaurant))
      {
        // Majuscules et tri
        $types_restaurant = array_map('ucfirst', $types_restaurant);
        asort($types_restaurant);

        // Formatage
        foreach ($types_restaurant as $type)
        {
          $types_formatted .= $type . ";";
        }
      }
    }

    // Contrôle numéro téléphone numérique
    if ($control_ok == true)
    {
      if (!empty($telephone_restaurant))
      {
        if (!is_numeric($telephone_restaurant) OR strlen($telephone_restaurant) != 10)
        {
          $_SESSION['alerts']['wrong_phone_number'] = true;
          $control_ok                               = false;
        }
      }
    }

    // Enregistrement et contrôles image
    if ($control_ok == true)
    {
      $new_name = "";

      // On contrôle la présence du dossier, sinon on le créé
      $dossier = "../../includes/images/foodadvisor";

      if (!is_dir($dossier))
        mkdir($dossier);

      // Si on a bien une image
   		if ($files['image_restaurant']['name'] != NULL)
   		{
        // Dossier de destination
   			$restaurant_dir = $dossier . '/';

        // Données du fichier
   			$file      = $files['image_restaurant']['name'];
   			$tmp_file  = $files['image_restaurant']['tmp_name'];
   			$size_file = $files['image_restaurant']['size'];
        $maxsize   = 8388608; // 8Mo

        // Si le fichier n'est pas trop grand
   			if ($size_file < $maxsize)
   			{
          // Contrôle fichier temporaire existant
          if (!is_uploaded_file($tmp_file))
            exit("Le fichier est introuvable");

          // Contrôle type de fichier
          $type_file = $files['image_restaurant']['type'];

          if (!strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp') && !strstr($type_file, 'gif') && !strstr($type_file, 'png'))
            exit("Le fichier n'est pas une image valide");
          else
   				{
   					$type_image = pathinfo($file, PATHINFO_EXTENSION);
   					$new_name   = rand() . '.' . $type_image;
   				}

          // Contrôle upload (si tout est bon, l'image est envoyée)
          if (!move_uploaded_file($tmp_file, $restaurant_dir . $new_name))
            exit("Impossible de copier le fichier dans $restaurant_dir");

          // Rotation de l'image
          $rotate = rotateImage($restaurant_dir . $new_name, $type_image);

          // Créé une miniature de la source vers la destination en la rognant avec une hauteur/largeur max de 400px (cf fonction imagethumb.php)
          imagethumb($restaurant_dir . $new_name, $restaurant_dir . $new_name, 500, FALSE, TRUE);
        }
      }
    }

    // Enregistrement BDD
    if ($control_ok == true)
    {
      global $bdd;

      $restaurant = array('name'        => $nom_restaurant,
                          'picture'     => $new_name,
                          'types'       => $types_formatted,
                          'location'    => $lieu_restaurant,
                          'phone'       => $telephone_restaurant,
                          'website'     => $website_restaurant,
                          'plan'        => $plan_restaurant,
                          'description' => $description_restaurant
                        );

      $req = $bdd->prepare('INSERT INTO food_advisor_restaurants(name,
                                                                 picture,
                    																						 types,
                                                                 location,
                                                                 phone,
                                                                 website,
                    																						 plan,
                                                                 description
                                                                )
                    																      VALUES(:name,
                                                                 :picture,
                    																						 :types,
                                                                 :location,
                    																					   :phone,
                                                                 :website,
                                                                 :plan,
                                                                 :description
                                                                )');
      $req->execute($restaurant);
      $req->closeCursor();

      // Récupération Id créé
      $new_id = $bdd->lastInsertId();

      // Génération succès
      insertOrUpdateSuccesValue('restaurant-finder', $user, 1);

      // Ajout expérience
      insertExperience($user, 'add_restaurant');

      // Message d'alerte
      $_SESSION['alerts']['restaurant_added'] = true;
    }

    return $new_id;
  }

  // METIER : Mise à jour d'un restaurant
  // RETOUR : Aucun
  function updateRestaurant($post, $files, $id_restaurant)
  {
    $control_ok = true;

    global $bdd;

    // Récupération des données
    if ($control_ok == true)
    {
      $nom_restaurant         = $post['name_restaurant'];
      $telephone_restaurant   = $post['phone_restaurant'];
      $website_restaurant     = $post['website_restaurant'];
      $plan_restaurant        = $post['plan_restaurant'];
      $description_restaurant = $post['description_restaurant'];

      if ($post['location'] == "other_location" AND !empty($post['update_other_location']))
        $lieu_restaurant      = $post['update_other_location'];
      else
        $lieu_restaurant      = $post['location'];

      if (isset($post['types_restaurants_update_' . $id_restaurant]))
      {
        $types_restaurant     = array_unique($post['types_restaurants_update_' . $id_restaurant]);

        foreach ($types_restaurant as $keyType => $type)
        {
          if (empty($type))
            unset($types_restaurant[$keyType]);
        }
      }
    }

    // Tri et formatage types de restaurants
    if ($control_ok == true)
    {
      $types_formatted = "";

      if (!empty($types_restaurant))
      {
        // Majuscules et tri
        $types_restaurant = array_map('ucfirst', $types_restaurant);
        asort($types_restaurant);

        // Formatage
        foreach ($types_restaurant as $type)
        {
          $types_formatted .= $type . ";";
        }
      }
    }

    // Contrôle numéro téléphone numérique
    if ($control_ok == true)
    {
      if (!empty($telephone_restaurant))
      {
        if (!is_numeric($telephone_restaurant) OR strlen($telephone_restaurant) != 10)
        {
          $_SESSION['alerts']['wrong_phone_number'] = true;
          $control_ok                               = false;
        }
      }
    }

    // Enregistrement et contrôles image
    if ($control_ok == true)
    {
      $new_name = "";

      // On contrôle la présence du dossier, sinon on le créé
      $dossier = "../../includes/images/foodadvisor";

      if (!is_dir($dossier))
        mkdir($dossier);

      // Si on a bien une image
      if ($files['image_restaurant']['name'] != NULL)
      {
        // Dossier de destination
        $restaurant_dir = $dossier . '/';

        // Suppression ancienne image
        $req1 = $bdd->query('SELECT id, picture FROM food_advisor_restaurants WHERE id = ' . $id_restaurant);
        $data1 = $req1->fetch();

        if (isset($data1['picture']) AND !empty($data1['picture']))
          unlink ("../../includes/images/foodadvisor/" . $data1['picture']);

        $req1->closeCursor();

        // Données du fichier
        $file      = $files['image_restaurant']['name'];
        $tmp_file  = $files['image_restaurant']['tmp_name'];
        $size_file = $files['image_restaurant']['size'];
        $maxsize   = 8388608; // 8Mo

        // Si le fichier n'est pas trop grand
        if ($size_file < $maxsize)
        {
          // Contrôle fichier temporaire existant
          if (!is_uploaded_file($tmp_file))
            exit("Le fichier est introuvable");

          // Contrôle type de fichier
          $type_file = $files['image_restaurant']['type'];

          if (!strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp') && !strstr($type_file, 'gif') && !strstr($type_file, 'png'))
            exit("Le fichier n'est pas une image valide");
          else
          {
            $type_image = pathinfo($file, PATHINFO_EXTENSION);
            $new_name   = rand() . '.' . $type_image;
          }

          // Contrôle upload (si tout est bon, l'image est envoyée)
          if (!move_uploaded_file($tmp_file, $restaurant_dir . $new_name))
            exit("Impossible de copier le fichier dans $restaurant_dir");

          // Rotation de l'image
          $rotate = rotateImage($restaurant_dir . $new_name, $type_image);

          // Créé une miniature de la source vers la destination en la rognant avec une hauteur/largeur max de 400px (cf fonction imagethumb.php)
          imagethumb($restaurant_dir . $new_name, $restaurant_dir . $new_name, 500, FALSE, TRUE);
        }
      }
      else
      {
        // Récupération nom fichier déjà présent
        $req1 = $bdd->query('SELECT id, picture FROM food_advisor_restaurants WHERE id = ' . $id_restaurant);
        $data1 = $req1->fetch();

        if (isset($data1['picture']) AND !empty($data1['picture']))
          $new_name = $data1['picture'];

        $req1->closeCursor();
      }
    }

    // Enregistrement BDD
    if ($control_ok == true)
    {
      $restaurant = array('name'        => $nom_restaurant,
                          'picture'     => $new_name,
                          'types'       => $types_formatted,
                          'location'    => $lieu_restaurant,
                          'phone'       => $telephone_restaurant,
                          'website'     => $website_restaurant,
                          'plan'        => $plan_restaurant,
                          'description' => $description_restaurant
                        );

      $req2 = $bdd->prepare('UPDATE food_advisor_restaurants SET name        = :name,
                                                                 picture     = :picture,
                                                                 types       = :types,
                                                                 location    = :location,
                                                                 phone       = :phone,
                                                                 website     = :website,
                                                                 plan        = :plan,
                                                                 description = :description
                                                           WHERE id = ' . $id_restaurant);
      $req2->execute($restaurant);
      $req2->closeCursor();

      // Message d'alerte
      $_SESSION['alerts']['restaurant_updated'] = true;
    }
  }

  // METIER : Supprime un restaurant
  // RETOUR : Aucun
  function deleteRestaurant($id_restaurant)
  {
    global $bdd;

    // Suppression image
    $req1 = $bdd->query('SELECT id, picture FROM food_advisor_restaurants WHERE id = ' . $id_restaurant);
    $data1 = $req1->fetch();

    if (isset($data1['picture']) AND !empty($data1['picture']))
      unlink ("../../includes/images/foodadvisor/" . $data1['picture']);

    $req1->closeCursor();

    // Suppression enregistrement en base
    $req2 = $bdd->exec('DELETE FROM food_advisor_restaurants WHERE id = ' . $id_restaurant);

    // Message alerte
    $_SESSION['alerts']['restaurant_deleted'] = true;
  }
?>
