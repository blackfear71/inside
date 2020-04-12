<?php
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

  // METIER : Récupération de la liste des types
  // RETOUR : Liste des types
  function getTypesRestaurants()
  {
    $listTypes   = array();
    $stringTypes = "";

    global $bdd;

    // Lecture des types
    $reponse = $bdd->query('SELECT DISTINCT types FROM food_advisor_restaurants');
    while ($donnees = $reponse->fetch())
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

  // METIER : Détermine la présence des boutons d'action (choix rapide)
  // RETOUR : Booléen
  function getFastActions($user)
  {
    $choixRapide = true;
    $solo        = false;

    // Contrôle date et heure
    if (date("N") > 5 OR date("H") >= 13)
      $choixRapide = false;

    // Contrôle bande à part
    $solo = getSolo($user);

    if ($solo == true)
      $choixRapide = false;

    return $choixRapide;
  }

  // METIER : Insère un nouveau restaurant
  // RETOUR : Id enregistrement créé
  function insertRestaurant($post, $files, $user)
  {
    $new_id     = NULL;
    $control_ok = true;

    // Récupération des données
    $nom_restaurant          = $post['name_restaurant'];
    $website_restaurant      = $post['website_restaurant'];
    $plan_restaurant         = $post['plan_restaurant'];
    $lafourchette_restaurant = $post['lafourchette_restaurant'];
    $description_restaurant  = $post['description_restaurant'];
    $ouverture_restaurant    = $post['ouverture_restaurant'];

    $search                  = array(" ", ".");
    $replace                 = array("", "");
    $telephone_restaurant    = str_replace($search, $replace, $post['phone_restaurant']);

    $prix_min_test           = str_replace(',', '.', $post['prix_min_restaurant']);
    $prix_max_test           = str_replace(',', '.', $post['prix_max_restaurant']);

    if (is_numeric($prix_min_test))
      $prix_min              = number_format($prix_min_test, 2, '.', '');
    else
      $prix_min              = "";

    if (is_numeric($prix_max_test))
      $prix_max              = number_format($prix_max_test, 2, '.', '');
    else
      $prix_max              = "";

    if ($post['location'] == "other_location"  AND !empty($post['saisie_other_location']))
    {
      $search                = array("'", '"');
      $replace               = array("", "");
      $lieu_restaurant       = str_replace($search, $replace, $post['saisie_other_location']);
    }
    else
      $lieu_restaurant       = $post['location'];

    if (isset($post['types_restaurants']))
    {
      $types_restaurant      = array_unique($post['types_restaurants']);

      foreach ($types_restaurant as $keyType => $type)
      {
        if (empty($type))
          unset($types_restaurant[$keyType]);
        else
          $types_restaurant[$keyType] = trim(str_replace(';', ' ', $types_restaurant[$keyType]));
      }
    }

    // Sauvegarde en session en cas d'erreur
    $_SESSION['save']['name_restaurant']         = $post['name_restaurant'];
    $_SESSION['save']['phone_restaurant']        = $post['phone_restaurant'];
    $_SESSION['save']['website_restaurant']      = $post['website_restaurant'];
    $_SESSION['save']['plan_restaurant']         = $post['plan_restaurant'];
    $_SESSION['save']['lafourchette_restaurant'] = $post['lafourchette_restaurant'];
    $_SESSION['save']['description_restaurant']  = $post['description_restaurant'];
    $_SESSION['save']['location']                = $post['location'];
    $_SESSION['save']['saisie_other_location']   = $post['saisie_other_location'];
    $_SESSION['save']['prix_min']                = $post['prix_min_restaurant'];
    $_SESSION['save']['prix_max']                = $post['prix_max_restaurant'];

    if (isset($post['ouverture_restaurant']))
      $_SESSION['save']['ouverture_restaurant']  = $ouverture_restaurant;

    if (isset($post['types_restaurants']))
      $_SESSION['save']['types_restaurants']     = $types_restaurant;

    // Contrôle prix min et max renseigné
    if ($control_ok == true)
    {
      if ((!empty($prix_min) AND empty($prix_max))
      OR  (empty($prix_min)  AND !empty($prix_max)))
      {
        $_SESSION['alerts']['miss_price'] = true;
        $control_ok                       = false;
      }
    }

    // Contrôle prix min numérique et positif
    if ($control_ok == true)
    {
      if (!empty($prix_min))
      {
        if (!is_numeric($prix_min) OR $prix_min <= 0)
        {
          $_SESSION['alerts']['wrong_price_min'] = true;
          $control_ok                            = false;
        }
      }
    }

    // Contrôle prix max numérique et positif
    if ($control_ok == true)
    {
      if (!empty($prix_max))
      {
        if (!is_numeric($prix_max) OR $prix_max <= 0)
        {
          $_SESSION['alerts']['wrong_price_max'] = true;
          $control_ok                            = false;
        }
      }
    }

    // Contrôle prix min <= prix max
    if ($control_ok == true)
    {
      if ($prix_min > $prix_max)
      {
        $_SESSION['alerts']['price_max_min'] = true;
        $control_ok                          = false;
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

    // Récupération des jours d'ouverture
    if ($control_ok == true)
    {
      $ouvertures = "";

      for ($i = 0; $i < 5; $i++)
      {
        if (isset($ouverture_restaurant[$i]))
          $ouvertures .= "Y;";
        else
          $ouvertures .= "N;";
      }
    }

    // Enregistrement et contrôles image
    if ($control_ok == true)
    {
      $new_name = '';

      // On vérifie la présence du dossier, sinon on le créé
      $dossier = "../../includes/images/foodadvisor";

      if (!is_dir($dossier))
        mkdir($dossier);

      // Dossier de destination et nom du fichier
      $restaurant_dir = $dossier . '/';
      $name           = rand();

      // Contrôles fichier
      $fileDatas = controlsUploadFile($files['image_restaurant'], $name, 'all');

      // Traitements fichier
      if ($fileDatas['control_ok'] == true)
      {
        // Upload fichier
        $control_ok = uploadFile($files['image_restaurant'], $fileDatas, $restaurant_dir);

        if ($control_ok == true)
        {
          $new_name   = $fileDatas['new_name'];
          $type_image = $fileDatas['type_file'];

          // Rotation de l'image (si JPEG)
          if ($type_image == 'jpg' OR $type_image == 'jpeg')
            $rotate = rotateImage($restaurant_dir . $new_name, $type_image);

          // Créé une miniature de la source vers la destination en la rognant avec une hauteur/largeur max de 500px (cf fonction imagethumb.php)
          imagethumb($restaurant_dir . $new_name, $restaurant_dir . $new_name, 500, FALSE, TRUE);
        }
      }
    }

    // Enregistrement BDD
    if ($control_ok == true)
    {
      global $bdd;

      $restaurant = array('name'         => $nom_restaurant,
                          'picture'      => $new_name,
                          'types'        => $types_formatted,
                          'location'     => $lieu_restaurant,
                          'phone'        => $telephone_restaurant,
                          'opened'       => $ouvertures,
                          'min_price'    => $prix_min,
                          'max_price'    => $prix_max,
                          'website'      => $website_restaurant,
                          'plan'         => $plan_restaurant,
                          'lafourchette' => $lafourchette_restaurant,
                          'description'  => $description_restaurant
                        );

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
                                                                 description
                                                                )
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
  // RETOUR : Id restaurant
  function updateRestaurant($post, $files)
  {
    $control_ok = true;

    global $bdd;

    // Récupération des données
    $id_restaurant           = $post['id_restaurant'];
    $nom_restaurant          = $post['update_name_restaurant_' . $id_restaurant];
    $website_restaurant      = $post['update_website_restaurant_' . $id_restaurant];
    $plan_restaurant         = $post['update_plan_restaurant_' . $id_restaurant];
    $lafourchette_restaurant = $post['update_lafourchette_restaurant_' . $id_restaurant];
    $description_restaurant  = $post['update_description_restaurant_' . $id_restaurant];
    $ouverture_restaurant    = $post['update_ouverture_restaurant_' . $id_restaurant];

    $search                  = array(" ", ".");
    $replace                 = array("", "");
    $telephone_restaurant    = str_replace($search, $replace, $post['update_phone_restaurant_' . $id_restaurant]);

    $prix_min_test           = str_replace(',', '.', $post['update_prix_min_restaurant_' . $id_restaurant]);
    $prix_max_test           = str_replace(',', '.', $post['update_prix_max_restaurant_' . $id_restaurant]);

    if (is_numeric($prix_min_test))
      $prix_min              = number_format($prix_min_test, 2, '.', '');
    else
      $prix_min              = "";

    if (is_numeric($prix_max_test))
      $prix_max              = number_format($prix_max_test, 2, '.', '');
    else
      $prix_max              = "";

    if ($post['update_location_' . $id_restaurant] == "other_location" AND !empty($post['update_other_location_' . $id_restaurant]))
    {
      $search                = array("'", '"');
      $replace               = array("", "");
      $lieu_restaurant       = str_replace($search, $replace, $post['update_other_location_' . $id_restaurant]);
    }
    else
      $lieu_restaurant       = $post['update_location_' . $id_restaurant];

    if (isset($post['update_types_restaurants_' . $id_restaurant]))
    {
      $types_restaurant      = array_unique($post['update_types_restaurants_' . $id_restaurant]);

      foreach ($types_restaurant as $keyType => $type)
      {
        if (empty($type))
          unset($types_restaurant[$keyType]);
        else
          $types_restaurant[$keyType] = trim(str_replace(';', ' ', $types_restaurant[$keyType]));
      }
    }

    // Contrôle prix min et max renseigné
    if ($control_ok == true)
    {
      if ((!empty($prix_min) AND empty($prix_max))
      OR  (empty($prix_min)  AND !empty($prix_max)))
      {
        $_SESSION['alerts']['miss_price'] = true;
        $control_ok                       = false;
      }
    }

    // Contrôle prix min numérique et positif
    if ($control_ok == true)
    {
      if (!empty($prix_min))
      {
        if (!is_numeric($prix_min) OR $prix_min <= 0)
        {
          $_SESSION['alerts']['wrong_price_min'] = true;
          $control_ok                            = false;
        }
      }
    }

    // Contrôle prix max numérique et positif
    if ($control_ok == true)
    {
      if (!empty($prix_max))
      {
        if (!is_numeric($prix_max) OR $prix_max <= 0)
        {
          $_SESSION['alerts']['wrong_price_max'] = true;
          $control_ok                            = false;
        }
      }
    }

    // Contrôle prix min <= prix max
    if ($control_ok == true)
    {
      if ($prix_min > $prix_max)
      {
        $_SESSION['alerts']['price_max_min'] = true;
        $control_ok                          = false;
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

    // Récupération des jours d'ouverture
    if ($control_ok == true)
    {
      $ouvertures = "";

      for ($i = 0; $i < 5; $i++)
      {
        if (isset($ouverture_restaurant[$i]))
          $ouvertures .= "Y;";
        else
          $ouvertures .= "N;";
      }
    }

    // Enregistrement et contrôles image
    if ($control_ok == true)
    {
      $new_name = '';

      // On vérifie la présence du dossier, sinon on le créé
      $dossier = "../../includes/images/foodadvisor";

      if (!is_dir($dossier))
        mkdir($dossier);

      // Dossier de destination et nom du fichier
      $restaurant_dir = $dossier . '/';
      $name           = rand();

      // Contrôles fichier
      $fileDatas = controlsUploadFile($files['update_image_restaurant_' . $id_restaurant], $name, 'all');

      // Traitements fichier
      if ($fileDatas['control_ok'] == true)
      {
        // Suppression ancienne image
        $req1 = $bdd->query('SELECT id, picture FROM food_advisor_restaurants WHERE id = ' . $id_restaurant);
        $data1 = $req1->fetch();

        if (isset($data1['picture']) AND !empty($data1['picture']))
          unlink("../../includes/images/foodadvisor/" . $data1['picture']);

        $req1->closeCursor();

        // Upload fichier
        $control_ok = uploadFile($files['update_image_restaurant_' . $id_restaurant], $fileDatas, $restaurant_dir);

        if ($control_ok == true)
        {
          $new_name   = $fileDatas['new_name'];
          $type_image = $fileDatas['type_file'];

          // Rotation de l'image
          if ($type_image == 'jpg' OR $type_image == 'jpeg')
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
      $restaurant = array('name'         => $nom_restaurant,
                          'picture'      => $new_name,
                          'types'        => $types_formatted,
                          'location'     => $lieu_restaurant,
                          'phone'        => $telephone_restaurant,
                          'opened'       => $ouvertures,
                          'min_price'    => $prix_min,
                          'max_price'    => $prix_max,
                          'website'      => $website_restaurant,
                          'plan'         => $plan_restaurant,
                          'lafourchette' => $lafourchette_restaurant,
                          'description'  => $description_restaurant
                        );

      $req2 = $bdd->prepare('UPDATE food_advisor_restaurants SET name         = :name,
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
                                                           WHERE id = ' . $id_restaurant);
      $req2->execute($restaurant);
      $req2->closeCursor();

      // Message d'alerte
      $_SESSION['alerts']['restaurant_updated'] = true;
    }

    return $id_restaurant;
  }

  // METIER : Supprime un restaurant
  // RETOUR : Aucun
  function deleteRestaurant($post)
  {
    $id_restaurant = $post['id_restaurant'];

    global $bdd;

    // Suppression image
    $req1 = $bdd->query('SELECT id, picture FROM food_advisor_restaurants WHERE id = ' . $id_restaurant);
    $data1 = $req1->fetch();

    if (isset($data1['picture']) AND !empty($data1['picture']))
      unlink("../../includes/images/foodadvisor/" . $data1['picture']);

    $req1->closeCursor();

    // Suppression choix utilisateurs
    $req2 = $bdd->exec('DELETE FROM food_advisor_users WHERE id_restaurant = ' . $id_restaurant);

    // Suppression détermination
    $req3 = $bdd->exec('DELETE FROM food_advisor_choices WHERE id_restaurant = ' . $id_restaurant);

    // Suppression enregistrement en base
    $req4 = $bdd->exec('DELETE FROM food_advisor_restaurants WHERE id = ' . $id_restaurant);

    // Message alerte
    $_SESSION['alerts']['restaurant_deleted'] = true;
  }
?>
