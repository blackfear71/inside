<?php
    include_once('../../includes/functions/appel_bdd.php');

    /****************************************************************************/
    /********************************** SELECT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Lecture types de restaurants
    // RETOUR : Types concaténés
    function physiqueTypesRestaurants($equipe)
    {
        // Initialisations
        $types = '';

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT DISTINCT types
                            FROM food_advisor_restaurants
                            WHERE team = "' . $equipe . '"');

        while ($data = $req->fetch()) 
        {
            $types .= $data['types'];
        }

        $req->closeCursor();

        // Retour
        return $types;
    }

    // PHYSIQUE : Lecture déterminations restaurants
    // RETOUR : Liste des déterminations
    function physiqueDeterminationsRestaurant($idRestaurant)
    {
        // Initialisations
        $listeDeterminations = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM food_advisor_choices
                            WHERE id_restaurant = "' . $idRestaurant . '" AND caller != ""');

        while ($data = $req->fetch()) 
        {
            // Instanciation d'un objet Proposition à partir des données remontées de la bdd
            $determination = Proposition::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeDeterminations, $determination);
        }

        // Retour
        return $listeDeterminations;
    }

    /****************************************************************************/
    /********************************** INSERT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Insertion nouveau restaurant
    // RETOUR : Id restaurant
    function physiqueInsertionRestaurant($restaurant)
    {
        // Initialisations
        $newId = NULL;

        // Requête
        global $bdd;

        $req = $bdd->prepare('INSERT INTO food_advisor_restaurants(team,
                                                                   identifiant_add,
                                                                   name,
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
                                                           VALUES(:team,
                                                                  :identifiant_add,
                                                                  :name,
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

        $req = $bdd->prepare('UPDATE food_advisor_restaurants
                              SET name         = :name,
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

    // PHYSIQUE : Suppression déterminations
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