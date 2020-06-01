<?php
  // METIER : Récupération de la liste des restaurants
  // RETOUR : Liste des restaurants
  function getRestaurants($listeLieux)
  {
    // Initialisations
    $listeRestaurants = array();

    // Récupération de la liste des restaurants pour chaque lieu
    foreach ($listeLieux as $lieu)
    {
      $listeRestaurants[$lieu] = physiqueRestaurantsParLieux($lieu);
    }

    // Retour
    return $listeRestaurants;
  }

  // METIER : Récupération de la liste des types de restaurants
  // RETOUR : Liste des types
  function getTypesRestaurants()
  {
    // Initialisations
    $listeTypes = array();

    // Lecture des types de restaurants
    $stringTypes = physiqueTypesRestaurants();

    // Extraction sous forme de tableau
    $explodedTypes = explode(';', $stringTypes);

    foreach ($explodedTypes as $exploded)
    {
      if (!empty($exploded))
        array_push($listeTypes, $exploded);
    }

    // Suppression des doublons
    $listeTypes = array_unique($listeTypes);

    // Tri par ordre alphabétique
    asort($listeTypes);

    // Retour
    return $listeTypes;
  }

  // METIER : Détermine la présence des boutons d'action (choix rapide)
  // RETOUR : Booléen
  function getFastActions($user, $solo)
  {
    // Initialisations
    $choixRapide = true;

    // Détermination actions en fnction de la date et de l'heure
    if (date('N') > 5 OR date('H') >= 13)
      $choixRapide = false;

    // Vérification bande à part
    if ($solo == true)
      $choixRapide = false;

    // Retour
    return $choixRapide;
  }

  // METIER : Insère un nouveau restaurant
  // RETOUR : Id enregistrement créé
  function insertRestaurant($post, $files, $user)
  {
    // Initialisations
    $newId      = NULL;
    $control_ok = true;

    // Récupération des données
    $nomRestaurant          = $post['name_restaurant'];
    $websiteRestaurant      = $post['website_restaurant'];
    $planRestaurant         = $post['plan_restaurant'];
    $lafourchetteRestaurant = $post['lafourchette_restaurant'];
    $descriptionRestaurant  = $post['description_restaurant'];
    $ouvertureRestaurant    = $post['ouverture_restaurant'];

    // Remplacement des caractères pour le numéro de téléphone
    $search              = array(' ', '.');
    $replace             = array('', '');
    $telephoneRestaurant = str_replace($search, $replace, $post['phone_restaurant']);

    // Remplacement des caractères pour les prix
    $prixMin = str_replace(',', '.', $post['prix_min_restaurant']);
    $prixMax = str_replace(',', '.', $post['prix_max_restaurant']);

    // Formatage du prix minimum
    if (is_numeric($prixMin))
      $prixMin = number_format($prixMin, 2, '.', '');
    else
      $prixMin = '';

    // Formatage du prix maximum
    if (is_numeric($prixMax))
      $prixMax = number_format($prixMax, 2, '.', '');
    else
      $prixMax = '';

    // Remplacement des caractères pour le lieu saisi
    if ($post['location'] == 'other_location'  AND !empty($post['saisie_other_location']))
    {
      $search         = array("'", '"');
      $replace        = array('', '');
      $lieuRestaurant = str_replace($search, $replace, $post['saisie_other_location']);
    }
    else
      $lieuRestaurant = $post['location'];

    // Filtrage et remplacement des caractères pour les types
    if (isset($post['types_restaurants']))
    {
      $typesRestaurant = array_unique($post['types_restaurants']);

      foreach ($typesRestaurant as $keyType => $type)
      {
        if (empty($type))
          unset($typesRestaurant[$keyType]);
        else
          $typesRestaurant[$keyType] = trim(str_replace(';', ' ', $typesRestaurant[$keyType]));
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
      $_SESSION['save']['ouverture_restaurant']  = $ouvertureRestaurant;

    if (isset($post['types_restaurants']))
      $_SESSION['save']['types_restaurants']     = $typesRestaurant;

    // Contrôle prix min et max renseigné
    $control_ok = controlePrixRenseignes($prixMin, $prixMax);

    // Contrôle prix min numérique et positif
    if ($control_ok == true)
      $control_ok = controlePrixNumerique($prixMin, 'min');

    // Contrôle prix max numérique et positif
    if ($control_ok == true)
      $control_ok = controlePrixNumerique($prixMax, 'max');

    // Contrôle prix min <= prix max
    if ($control_ok == true)
      $control_ok = controleOrdrePrix($prixMin, $prixMax);

    // Contrôle numéro téléphone numérique
    if ($control_ok == true)
      $control_ok = controleTelephoneNumerique($telephoneRestaurant);

    // Tri et formatage types de restaurants
    if ($control_ok == true)
    {
      $typesFormatted = '';

      if (!empty($typesRestaurant))
      {
        // Majuscule sur la première lettre de chaque type
        $typesRestaurant = array_map('ucfirst', $typesRestaurant);

        // Tri par ordre alphabétique
        asort($typesRestaurant);

        // Formatage
        foreach ($typesRestaurant as $type)
        {
          $typesFormatted .= $type . ';';
        }
      }
    }

    // Récupération des jours d'ouverture
    if ($control_ok == true)
    {
      $ouvertures = '';

      for ($i = 0; $i < 5; $i++)
      {
        if (isset($ouvertureRestaurant[$i]))
          $ouvertures .= 'Y;';
        else
          $ouvertures .= 'N;';
      }
    }

    if ($control_ok == true)
    {
      // Vérification des dossiers et contrôle des fichiers
      if (!empty($files['image_restaurant']['name']))
      {
        // Nom du fichier
        $name = rand();

        // On vérifie la présence du dossier des images, sinon on le créé
        $dossier = '../../includes/images/foodadvisor';

        if (!is_dir($dossier))
          mkdir($dossier);

        // Dossier de destination
        $restaurantDir = $dossier . '/';

        // Contrôle du fichier
        $fileDatas = controlsUploadFile($files['image_restaurant'], $name, 'all');

        // Récupération contrôles
        $control_ok = $fileDatas['control_ok'];

        // Upload fichier
        if ($control_ok == true)
          $control_ok = uploadFile($files['image_restaurant'], $fileDatas, $restaurantDir);

        // Traitement de l'image
        if ($control_ok == true)
        {
          $newName   = $fileDatas['new_name'];
          $typeImage = $fileDatas['type_file'];

          // Rotation automatique de l'image (si JPEG)
          if ($typeImage == 'jpg' OR $typeImage == 'jpeg')
            $rotate = rotateImage($restaurantDir . $newName, $typeImage);

          // Créé une miniature de la source vers la destination en la rognant avec une hauteur/largeur max de 500px (cf fonction imagethumb.php)
          imagethumb($restaurantDir . $newName, $restaurantDir . $newName, 500, FALSE, TRUE);
        }
      }
      else
        $newName = '';
    }

    // Insertion de l'enregistrement en base
    if ($control_ok == true)
    {
      $restaurant = array('name'         => $nomRestaurant,
                          'picture'      => $newName,
                          'types'        => $typesFormatted,
                          'location'     => $lieuRestaurant,
                          'phone'        => $telephoneRestaurant,
                          'opened'       => $ouvertures,
                          'min_price'    => $prixMin,
                          'max_price'    => $prixMax,
                          'website'      => $websiteRestaurant,
                          'plan'         => $planRestaurant,
                          'lafourchette' => $lafourchetteRestaurant,
                          'description'  => $descriptionRestaurant
                         );

      $newId = physiqueInsertionRestaurant($restaurant);

      // Génération succès
      insertOrUpdateSuccesValue('restaurant-finder', $user, 1);

      // Ajout expérience
      insertExperience($user, 'add_restaurant');

      // Message d'alerte
      $_SESSION['alerts']['restaurant_added'] = true;
    }

    // Retour
    return $newId;
  }

  // METIER : Mise à jour d'un restaurant
  // RETOUR : Id restaurant
  function updateRestaurant($post, $files)
  {
    // Initialisations
    $control_ok = true;

    // Récupération des données
    $idRestaurant           = $post['id_restaurant'];
    $nomRestaurant          = $post['update_name_restaurant_' . $idRestaurant];
    $websiteRestaurant      = $post['update_website_restaurant_' . $idRestaurant];
    $planRestaurant         = $post['update_plan_restaurant_' . $idRestaurant];
    $lafourchetteRestaurant = $post['update_lafourchette_restaurant_' . $idRestaurant];
    $descriptionRestaurant  = $post['update_description_restaurant_' . $idRestaurant];
    $ouvertureRestaurant    = $post['update_ouverture_restaurant_' . $idRestaurant];

    // Remplacement des caractères pour le numéro de téléphone
    $search              = array(' ', '.');
    $replace             = array('', '');
    $telephoneRestaurant = str_replace($search, $replace, $post['update_phone_restaurant_' . $idRestaurant]);

    // Remplacement des caractères pour les prix
    $prixMin = str_replace(',', '.', $post['update_prix_min_restaurant_' . $idRestaurant]);
    $prixMax = str_replace(',', '.', $post['update_prix_max_restaurant_' . $idRestaurant]);

    // Formatage du prix minimum
    if (is_numeric($prixMin))
      $prixMin = number_format($prixMin, 2, '.', '');
    else
      $prixMin = '';

    // Formatage du prix maximum
    if (is_numeric($prixMax))
      $prixMax = number_format($prixMax, 2, '.', '');
    else
      $prixMax = '';

    // Remplacement des caractères pour le lieu saisi
    if ($post['update_location_' . $idRestaurant] == 'other_location' AND !empty($post['update_other_location_' . $idRestaurant]))
    {
      $search         = array("'", '"');
      $replace        = array('', '');
      $lieuRestaurant = str_replace($search, $replace, $post['update_other_location_' . $idRestaurant]);
    }
    else
      $lieuRestaurant = $post['update_location_' . $idRestaurant];

    // Filtrage et remplacement des caractères pour les types
    if (isset($post['update_types_restaurants_' . $idRestaurant]))
    {
      $typesRestaurant = array_unique($post['update_types_restaurants_' . $idRestaurant]);

      foreach ($typesRestaurant as $keyType => $type)
      {
        if (empty($type))
          unset($typesRestaurant[$keyType]);
        else
          $typesRestaurant[$keyType] = trim(str_replace(';', ' ', $typesRestaurant[$keyType]));
      }
    }

    // Contrôle prix min et max renseigné
    $control_ok = controlePrixRenseignes($prixMin, $prixMax);

    // Contrôle prix min numérique et positif
    if ($control_ok == true)
      $control_ok = controlePrixNumerique($prixMin, 'min');

    // Contrôle prix max numérique et positif
    if ($control_ok == true)
      $control_ok = controlePrixNumerique($prixMax, 'max');

    // Contrôle prix min <= prix max
    if ($control_ok == true)
      $control_ok = controleOrdrePrix($prixMin, $prixMax);

    // Contrôle numéro téléphone numérique
    if ($control_ok == true)
      $control_ok = controleTelephoneNumerique($telephoneRestaurant);

    // Tri et formatage types de restaurants
    if ($control_ok == true)
    {
      $typesFormatted = '';

      if (!empty($typesRestaurant))
      {
        // Majuscule sur la première lettre de chaque type
        $typesRestaurant = array_map('ucfirst', $typesRestaurant);

        // Tri par ordre alphabétique
        asort($typesRestaurant);

        // Formatage
        foreach ($typesRestaurant as $type)
        {
          $typesFormatted .= $type . ';';
        }
      }
    }

    // Récupération des jours d'ouverture
    if ($control_ok == true)
    {
      $ouvertures = '';

      for ($i = 0; $i < 5; $i++)
      {
        if (isset($ouvertureRestaurant[$i]))
          $ouvertures .= 'Y;';
        else
          $ouvertures .= 'N;';
      }
    }

    if ($control_ok == true)
    {
      // Lecture des données en base
      $restaurant = physiqueDonneesRestaurant($idRestaurant);

      // Vérification des dossiers et contrôle des fichiers
      if (!empty($files['update_image_restaurant_' . $idRestaurant]['name']))
      {
        // Nom du fichier
        $name = rand();

        // On vérifie la présence du dossier des images, sinon on le créé
        $dossier = '../../includes/images/foodadvisor';

        if (!is_dir($dossier))
          mkdir($dossier);

        // Dossier de destination
        $restaurantDir = $dossier . '/';

        // Contrôles communs d'un fichier
        $fileDatas = controlsUploadFile($files['update_image_restaurant_' . $idRestaurant], $name, 'all');

        // Récupération contrôles
        $control_ok = controleFichier($fileDatas);

        // Suppression ancienne image
        if ($control_ok == true)
        {
          // Suppression de l'image
          if (!empty($restaurant->getPicture()))
            unlink('../../includes/images/foodadvisor/' . $restaurant->getPicture());

          // Upload fichier
          $control_ok = uploadFile($files['update_image_restaurant_' . $idRestaurant], $fileDatas, $restaurantDir);

          // Traitement de l'image
          if ($control_ok == true)
          {
            $newName   = $fileDatas['new_name'];
            $typeImage = $fileDatas['type_file'];

            // Rotation automatique de l'image (si JPEG)
            if ($typeImage == 'jpg' OR $typeImage == 'jpeg')
              $rotate = rotateImage($restaurantDir . $newName, $typeImage);

            // Créé une miniature de la source vers la destination en la rognant avec une hauteur/largeur max de 500px (cf fonction imagethumb.php)
            imagethumb($restaurantDir . $newName, $restaurantDir . $newName, 500, FALSE, TRUE);
          }
        }
      }
      else
        $newName = $restaurant->getPicture();
    }

    // Modification de l'enregistrement en base
    if ($control_ok == true)
    {
      $restaurant = array('name'         => $nomRestaurant,
                          'picture'      => $newName,
                          'types'        => $typesFormatted,
                          'location'     => $lieuRestaurant,
                          'phone'        => $telephoneRestaurant,
                          'opened'       => $ouvertures,
                          'min_price'    => $prixMin,
                          'max_price'    => $prixMax,
                          'website'      => $websiteRestaurant,
                          'plan'         => $planRestaurant,
                          'lafourchette' => $lafourchetteRestaurant,
                          'description'  => $descriptionRestaurant
                         );

      physiqueUpdateRestaurant($idRestaurant, $restaurant);

      // Message d'alerte
      $_SESSION['alerts']['restaurant_updated'] = true;
    }

    // Retour
    return $idRestaurant;
  }

  // METIER : Supprime un restaurant
  // RETOUR : Aucun
  function deleteRestaurant($post)
  {
    // Récupération des données
    $idRestaurant = $post['id_restaurant'];

    // Récupération des données de la mission
    $restaurant = physiqueDonneesRestaurant($idRestaurant);

    // Suppression des images
    if (!empty($restaurant->getPicture()))
      unlink('../../includes/images/foodadvisor/' . $restaurant->getPicture());

    // Suppression des choix utilisateurs
    physiqueDeleteUsersChoices($idRestaurant);

    // Suppression des déterminations
    physiqueDeleteDeterminations($idRestaurant);

    // Suppression de l'enregistrement en base
    physiqueDeleteRestaurant($idRestaurant);

    // Message alerte
    $_SESSION['alerts']['restaurant_deleted'] = true;
  }
?>
