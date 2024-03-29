<?php
    // METIER : Initialise les données de sauvegarde en session
    // RETOUR : Aucun
    function initializeSaveSession()
    {
        // On initialise les champs de saisie s'il n'y a pas d'erreur
        if ((!isset($_SESSION['alerts']['wrong_phone_number']) OR $_SESSION['alerts']['wrong_phone_number'] != true)
        AND (!isset($_SESSION['alerts']['wrong_price_min'])    OR $_SESSION['alerts']['wrong_price_min']    != true)
        AND (!isset($_SESSION['alerts']['wrong_price_max'])    OR $_SESSION['alerts']['wrong_price_max']    != true)
        AND (!isset($_SESSION['alerts']['miss_price'])         OR $_SESSION['alerts']['miss_price']         != true)
        AND (!isset($_SESSION['alerts']['price_max_min'])      OR $_SESSION['alerts']['price_max_min']      != true)
        AND (!isset($_SESSION['alerts']['file_too_big'])       OR $_SESSION['alerts']['file_too_big']       != true)
        AND (!isset($_SESSION['alerts']['temp_not_found'])     OR $_SESSION['alerts']['temp_not_found']     != true)
        AND (!isset($_SESSION['alerts']['wrong_file_type'])    OR $_SESSION['alerts']['wrong_file_type']    != true)
        AND (!isset($_SESSION['alerts']['wrong_file'])         OR $_SESSION['alerts']['wrong_file']         != true))
        {
            unset($_SESSION['save']);

            $_SESSION['save']['name_restaurant']         = '';
            $_SESSION['save']['prix_min']                = '';
            $_SESSION['save']['prix_max']                = '';
            $_SESSION['save']['phone_restaurant']        = '';
            $_SESSION['save']['types_restaurant']        = '';
            $_SESSION['save']['description_restaurant']  = '';
            $_SESSION['save']['location']                = '';
            $_SESSION['save']['ouverture_restaurant']    = '';
            $_SESSION['save']['website_restaurant']      = '';
            $_SESSION['save']['plan_restaurant']         = '';
            $_SESSION['save']['lafourchette_restaurant'] = '';
            $_SESSION['save']['saisie_other_location']   = '';
        }
    }

    // METIER : Récupération de la liste des types de restaurants
    // RETOUR : Liste des types
    function getTypesRestaurants($equipe)
    {
        // Initialisations
        $listeTypes = array();

        // Lecture des types de restaurants
        $stringTypes = physiqueTypesRestaurants($equipe);

        // Extraction sous forme de tableau
        $explodedTypes = array_filter(explode(';', $stringTypes));

        foreach ($explodedTypes as $exploded)
        {
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
    function getFastActions($isSolo)
    {
        // Initialisations
        $choixRapide = true;

        // Détermination actions en fonction de la date et de l'heure
        if (date('N') > 5 OR date('H') >= 13)
            $choixRapide = false;

        // Vérification bande à part
        if ($isSolo == true)
            $choixRapide = false;

        // Retour
        return $choixRapide;
    }

    // METIER : Insère un nouveau restaurant
    // RETOUR : Id enregistrement créé
    function insertRestaurant($post, $files, $sessionUser)
    {
        // Initialisations
        $idRestaurant = NULL;
        $control_ok   = true;

        // Récupération des données
        $identifiant            = $sessionUser['identifiant'];
        $equipe                 = $sessionUser['equipe'];
        $nomRestaurant          = $post['name_restaurant'];
        $prixMin                = $post['prix_min_restaurant'];
        $prixMax                = $post['prix_max_restaurant'];
        $websiteRestaurant      = $post['website_restaurant'];
        $planRestaurant         = $post['plan_restaurant'];
        $lafourchetteRestaurant = $post['lafourchette_restaurant'];
        $descriptionRestaurant  = $post['description_restaurant'];
        $ouvertureRestaurant    = $post['ouverture_restaurant'];

        // Remplacement des caractères pour le numéro de téléphone
        $search              = array(' ', '.');
        $replace             = array('', '');
        $telephoneRestaurant = str_replace($search, $replace, $post['phone_restaurant']);

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
        if (isset($post['types_restaurant']))
        {
            $typesRestaurant = array_unique($post['types_restaurant']);

            foreach ($typesRestaurant as $keyType => $type)
            {
                if (empty($type))
                    unset($typesRestaurant[$keyType]);
                else
                    $typesRestaurant[$keyType] = encodeStringForInsert($typesRestaurant[$keyType]);
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
            $_SESSION['save']['ouverture_restaurant'] = $ouvertureRestaurant;

        if (isset($post['types_restaurant']))
            $_SESSION['save']['types_restaurant'] = $typesRestaurant;

        // Contrôle prix min numérique et positif
        $control_ok = controlePrixNumerique($prixMin, 'min');

        // Contrôle prix max numérique et positif
        if ($control_ok == true)
            $control_ok = controlePrixNumerique($prixMax, 'max');
            
        // Contrôle prix min et max renseigné
        if ($control_ok == true)
            $control_ok = controlePrixRenseignes($prixMin, $prixMax);

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

        // Traitement de l'image
        if ($control_ok == true)
        {
            if (!empty($files['image_restaurant']['name']))
            {
                // Insertion image
                $newName = uploadImage($files['image_restaurant'], rand());
            }
            else
                $newName = '';
        }

        // Insertion de l'enregistrement en base
        if ($control_ok == true)
        {
            $restaurant = array(
                'team'            => $equipe,
                'identifiant_add' => $identifiant,
                'name'            => $nomRestaurant,
                'picture'         => $newName,
                'types'           => $typesFormatted,
                'location'        => $lieuRestaurant,
                'phone'           => $telephoneRestaurant,
                'opened'          => $ouvertures,
                'min_price'       => formatAmountForInsert($prixMin),
                'max_price'       => formatAmountForInsert($prixMax),
                'website'         => $websiteRestaurant,
                'plan'            => $planRestaurant,
                'lafourchette'    => $lafourchetteRestaurant,
                'description'     => $descriptionRestaurant
            );

            $idRestaurant = physiqueInsertionRestaurant($restaurant);

            // Génération succès
            insertOrUpdateSuccesValue('restaurant-finder', $identifiant, 1);

            // Ajout expérience
            insertExperience($identifiant, 'add_restaurant');

            // Message d'alerte
            $_SESSION['alerts']['restaurant_added'] = true;
        }

        // Retour
        return $idRestaurant;
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
        $prixMin                = $post['update_prix_min_restaurant_' . $idRestaurant];
        $prixMax                = $post['update_prix_max_restaurant_' . $idRestaurant];
        $websiteRestaurant      = $post['update_website_restaurant_' . $idRestaurant];
        $planRestaurant         = $post['update_plan_restaurant_' . $idRestaurant];
        $lafourchetteRestaurant = $post['update_lafourchette_restaurant_' . $idRestaurant];
        $descriptionRestaurant  = $post['update_description_restaurant_' . $idRestaurant];
        $ouvertureRestaurant    = $post['update_ouverture_restaurant_' . $idRestaurant];

        // Remplacement des caractères pour le numéro de téléphone
        $search              = array(' ', '.');
        $replace             = array('', '');
        $telephoneRestaurant = str_replace($search, $replace, $post['update_phone_restaurant_' . $idRestaurant]);

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
        if (isset($post['update_types_restaurant_' . $idRestaurant]))
        {
            $typesRestaurant = array_unique($post['update_types_restaurant_' . $idRestaurant]);

            foreach ($typesRestaurant as $keyType => $type)
            {
                if (empty($type))
                    unset($typesRestaurant[$keyType]);
                else
                    $typesRestaurant[$keyType] = encodeStringForInsert($typesRestaurant[$keyType]);
            }
        }

        // Contrôle prix min numérique et positif
        $control_ok = controlePrixNumerique($prixMin, 'min');

        // Contrôle prix max numérique et positif
        if ($control_ok == true)
            $control_ok = controlePrixNumerique($prixMax, 'max');
            
        // Contrôle prix min et max renseigné
        if ($control_ok == true)
            $control_ok = controlePrixRenseignes($prixMin, $prixMax);

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

        // Traitement de l'image
        if ($control_ok == true)
        {
            // Lecture des données en base
            $restaurant = physiqueDonneesRestaurant($idRestaurant);

            // Insertion nouvelle image et suppression ancienne
            if (!empty($files['update_image_restaurant_' . $idRestaurant]['name']))
            {
                // Insertion image
                $newName = uploadImage($files['update_image_restaurant_' . $idRestaurant], rand());

                // Suppression des anciennes images
                if (!empty($restaurant->getPicture()))
                    unlink('../../includes/images/foodadvisor/' . $restaurant->getPicture());
            }
            else
                $newName = $restaurant->getPicture();
        }

        // Modification de l'enregistrement en base
        if ($control_ok == true)
        {
            $restaurant = array(
                'name'         => $nomRestaurant,
                'picture'      => $newName,
                'types'        => $typesFormatted,
                'location'     => $lieuRestaurant,
                'phone'        => $telephoneRestaurant,
                'opened'       => $ouvertures,
                'min_price'    => formatAmountForInsert($prixMin),
                'max_price'    => formatAmountForInsert($prixMax),
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

    // METIER : Formatage et insertion image restaurant
    // RETOUR : Nom fichier avec extension
    function uploadImage($file, $name)
    {
        // Initialisations
        $newName    = '';
        $control_ok = true;
        
        // Dossier de destination
        $dossier = '../../includes/images/foodadvisor';

        // Contrôles fichier
        $fileDatas = controlsUploadFile($file, $name, 'all');

        // Récupération contrôles
        $control_ok = controleFichier($fileDatas);

        // Upload fichier
        if ($control_ok == true)
            $control_ok = uploadFile($fileDatas, $dossier);

        // Traitement de l'image (rotation et miniature)
        if ($control_ok == true)
        {
            $newName   = $fileDatas['new_name'];
            $typeImage = $fileDatas['type_file'];

            // Rotation automatique de l'image (si JPEG)
            if ($typeImage == 'jpg' OR $typeImage == 'jpeg')
                rotateImage($dossier . '/' . $newName, $typeImage);

            // Création miniature avec une hauteur/largeur max de 500px
            imageThumb($dossier . '/' . $newName, $dossier . '/' . $newName, 500, false, true);
        }

        // Retour
        return $newName;
    }

    // METIER : Supprime un restaurant
    // RETOUR : Aucun
    function deleteRestaurant($post)
    {
        // Récupération des données
        $idRestaurant = $post['id_restaurant'];

        // Récupération des données du restaurant
        $restaurant = physiqueDonneesRestaurant($idRestaurant);

        // Récupération des déterminations associées
        $determinations = physiqueDeterminationsRestaurant($idRestaurant);

        // Suppression des images
        if (!empty($restaurant->getPicture()))
            unlink('../../includes/images/foodadvisor/' . $restaurant->getPicture());

        // Suppression des choix utilisateurs
        physiqueDeleteUsersChoices($idRestaurant);

        // Suppression des déterminations
        physiqueDeleteDeterminations($idRestaurant);

        // Suppression de l'enregistrement en base
        physiqueDeleteRestaurant($idRestaurant);

        // Génération succès
        insertOrUpdateSuccesValue('restaurant-finder', $restaurant->getIdentifiant_add(), -1);

        foreach ($determinations as $determination)
        {
            insertOrUpdateSuccesValue('star-chief', $determination->getCaller(), -1);
        }

        // Message alerte
        $_SESSION['alerts']['restaurant_deleted'] = true;
    }

    // METIER : Conversion de la liste d'objets des restaurants en tableau simple pour JSON
    // RETOUR : Tableau des restaurants
    function convertForJsonListeRestaurants($listeRestaurants)
    {
        // Initialisations
        $listeRestaurantsAConvertir = array();

        // Conversion de la liste d'objets en tableau pour envoyer au Javascript
        foreach ($listeRestaurants as $restaurantsParLieux)
        {
            foreach ($restaurantsParLieux as $restaurant)
            {
                // Formatage des types
                $explodedTypes = array_filter(explode(';', $restaurant->getTypes()));

                foreach ($explodedTypes as $keyType => $type)
                {
                    $explodedTypes[$keyType] = formatId($type);
                }

                $restaurantAConvertir = array(
                    'id'              => $restaurant->getId(),
                    'name'            => $restaurant->getName(),
                    'picture'         => $restaurant->getPicture(),
                    'types'           => $restaurant->getTypes(),
                    'formatted_types' => $explodedTypes,
                    'location'        => $restaurant->getLocation(),
                    'phone'           => formatPhoneNumber($restaurant->getPhone()),
                    'opened'          => $restaurant->getOpened(),
                    'min_price'       => $restaurant->getMin_price(),
                    'max_price'       => $restaurant->getMax_price(),
                    'website'         => $restaurant->getWebsite(),
                    'plan'            => $restaurant->getPlan(),
                    'lafourchette'    => $restaurant->getLafourchette(),
                    'description'     => $restaurant->getDescription()
                );

                // Ajout au tableau
                $listeRestaurantsAConvertir[$restaurant->getId()] = $restaurantAConvertir;
            }
        }

        // Tri par Id
        ksort($listeRestaurantsAConvertir);

        // Retour
        return $listeRestaurantsAConvertir;
    }

    // METIER : Conversion de la liste d'objets des choix en tableau simple pour JSON
    // RETOUR : Tableau des choix
    function convertForJsonMesChoix($mesChoix)
    {
        // Initialisations
        $mesChoixAConvertir = array();

        // Conversion de la liste d'objets en tableau pour envoyer au Javascript
        foreach ($mesChoix as $monChoix)
        {
            $choixAConvertir = array(
                'id'            => $monChoix->getId(),
                'id_restaurant' => $monChoix->getId_restaurant(),
                'identifiant'   => $monChoix->getIdentifiant(),
                'date'          => $monChoix->getDate(),
                'time'          => $monChoix->getTime(),
                'transports'    => $monChoix->getTransports(),
                'menu'          => $monChoix->getMenu(),
                'name'          => $monChoix->getName(),
                'picture'       => $monChoix->getPicture(),
                'location'      => $monChoix->getLocation(),
                'opened'        => $monChoix->getOpened()
            );

            // On ajoute la ligne au tableau
            array_push($mesChoixAConvertir, $choixAConvertir);
        }

        // Retour
        return $mesChoixAConvertir;
    }
?>