<?php
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

    // Récupération des données et sauvegarde en session
    $nom_restaurant         = $post['name_restaurant'];
    $website_restaurant     = $post['website_restaurant'];
    $plan_restaurant        = $post['plan_restaurant'];
    $description_restaurant = $post['description_restaurant'];
    $ouverture_restaurant   = $post['ouverture_restaurant'];

    $search                 = array(" ", ".");
    $replace                = array("", "");
    $telephone_restaurant   = str_replace($search, $replace, $post['phone_restaurant']);

    $prix_min_test          = str_replace(',', '.', $post['prix_min_restaurant']);
    $prix_max_test          = str_replace(',', '.', $post['prix_max_restaurant']);

    if (is_numeric($prix_min_test))
      $prix_min             = number_format($prix_min_test, 2, '.', '');
    else
      $prix_min             = "";

    if (is_numeric($prix_max_test))
      $prix_max             = number_format($prix_max_test, 2, '.', '');
    else
      $prix_max             = "";

    if ($post['location'] == "other_location"  AND !empty($post['saisie_other_location']))
    {
      $search               = array("'", '"');
      $replace              = array("", "");
      $lieu_restaurant      = str_replace($search, $replace, $post['saisie_other_location']);
    }
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
    $_SESSION['save']['prix_min']               = $post['prix_min_restaurant'];
    $_SESSION['save']['prix_max']               = $post['prix_max_restaurant'];

    if (isset($post['ouverture_restaurant']))
      $_SESSION['save']['ouverture_restaurant'] = $ouverture_restaurant;

    if (isset($post['types_restaurants']))
      $_SESSION['save']['types_restaurants']    = $types_restaurant;

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

      $restaurant = array('name'        => $nom_restaurant,
                          'picture'     => $new_name,
                          'types'       => $types_formatted,
                          'location'    => $lieu_restaurant,
                          'phone'       => $telephone_restaurant,
                          'opened'      => $ouvertures,
                          'min_price'   => $prix_min,
                          'max_price'   => $prix_max,
                          'website'     => $website_restaurant,
                          'plan'        => $plan_restaurant,
                          'description' => $description_restaurant
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
    $id_restaurant          = $post['id_restaurant'];
    $nom_restaurant         = $post['update_name_restaurant_' . $id_restaurant];
    $website_restaurant     = $post['update_website_restaurant_' . $id_restaurant];
    $plan_restaurant        = $post['update_plan_restaurant_' . $id_restaurant];
    $description_restaurant = $post['update_description_restaurant_' . $id_restaurant];
    $ouverture_restaurant   = $post['update_ouverture_restaurant_' . $id_restaurant];

    $search                 = array(" ", ".");
    $replace                = array("", "");
    $telephone_restaurant   = str_replace($search, $replace, $post['update_phone_restaurant_' . $id_restaurant]);

    $prix_min_test          = str_replace(',', '.', $post['update_prix_min_restaurant_' . $id_restaurant]);
    $prix_max_test          = str_replace(',', '.', $post['update_prix_max_restaurant_' . $id_restaurant]);

    if (is_numeric($prix_min_test))
      $prix_min             = number_format($prix_min_test, 2, '.', '');
    else
      $prix_min             = "";

    if (is_numeric($prix_max_test))
      $prix_max             = number_format($prix_max_test, 2, '.', '');
    else
      $prix_max             = "";

    if ($post['update_location_' . $id_restaurant] == "other_location" AND !empty($post['update_other_location_' . $id_restaurant]))
    {
      $search               = array("'", '"');
      $replace              = array("", "");
      $lieu_restaurant      = str_replace($search, $replace, $post['update_other_location_' . $id_restaurant]);
    }
    else
      $lieu_restaurant      = $post['update_location_' . $id_restaurant];

    if (isset($post['update_types_restaurants_' . $id_restaurant]))
    {
      $types_restaurant     = array_unique($post['update_types_restaurants_' . $id_restaurant]);

      foreach ($types_restaurant as $keyType => $type)
      {
        if (empty($type))
          unset($types_restaurant[$keyType]);
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
      $new_name = "";

      // On contrôle la présence du dossier, sinon on le créé
      $dossier = "../../includes/images/foodadvisor";

      if (!is_dir($dossier))
        mkdir($dossier);

      // Si on a bien une image
      if ($files['update_image_restaurant_' . $id_restaurant]['name'] != NULL)
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
        $file      = $files['update_image_restaurant_' . $id_restaurant]['name'];
        $tmp_file  = $files['update_image_restaurant_' . $id_restaurant]['tmp_name'];
        $size_file = $files['update_image_restaurant_' . $id_restaurant]['size'];
        $maxsize   = 8388608; // 8Mo

        // Si le fichier n'est pas trop grand
        if ($size_file < $maxsize)
        {
          // Contrôle fichier temporaire existant
          if (!is_uploaded_file($tmp_file))
            exit("Le fichier est introuvable");

          // Contrôle type de fichier
          $type_file = $files['update_image_restaurant_' . $id_restaurant]['type'];

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
      $restaurant = array('name'        => $nom_restaurant,
                          'picture'     => $new_name,
                          'types'       => $types_formatted,
                          'location'    => $lieu_restaurant,
                          'phone'       => $telephone_restaurant,
                          'opened'      => $ouvertures,
                          'min_price'   => $prix_min,
                          'max_price'   => $prix_max,
                          'website'     => $website_restaurant,
                          'plan'        => $plan_restaurant,
                          'description' => $description_restaurant
                        );

      $req2 = $bdd->prepare('UPDATE food_advisor_restaurants SET name        = :name,
                                                                 picture     = :picture,
                                                                 types       = :types,
                                                                 location    = :location,
                                                                 phone       = :phone,
                                                                 opened      = :opened,
                                                                 min_price   = :min_price,
                                                                 max_price   = :max_price,
                                                                 website     = :website,
                                                                 plan        = :plan,
                                                                 description = :description
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
      unlink ("../../includes/images/foodadvisor/" . $data1['picture']);

    $req1->closeCursor();

    // Suppression enregistrement en base
    $req2 = $bdd->exec('DELETE FROM food_advisor_restaurants WHERE id = ' . $id_restaurant);

    // Message alerte
    $_SESSION['alerts']['restaurant_deleted'] = true;
  }
?>
