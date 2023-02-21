<?php
    include_once('../../includes/functions/appel_bdd.php');

    /****************************************************************************/
    /********************************** SELECT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Lecture données utilisateur
    // RETOUR : Objet Profile
    function physiqueUser($identifiant)
    {
        // Initialisations
        $user = NULL;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *, COUNT(*) AS nombreLignes
                            FROM users
                            WHERE identifiant = "' . $identifiant . '"');

        $data = $req->fetch();

        if ($data['nombreLignes'] > 0)
        {
            // Instanciation d'un objet Profile à partir des données remontées de la bdd
            $user = Profile::withData($data);
        }

        $req->closeCursor();

        // Retour
        return $user;
    }

    // PHYSIQUE : Lecture des lieux
    // RETOUR : Lieux
    function physiqueLieux($equipe)
    {
        // Initialisations
        $listeLieux = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT DISTINCT location
                            FROM food_advisor_restaurants
                            WHERE team = "' . $equipe . '"
                            ORDER BY location ASC');

        while ($data = $req->fetch())
        {
            // On ajoute la ligne au tableau
            array_push($listeLieux, $data['location']);
        }

        $req->closeCursor();

        // Retour
        return $listeLieux;
    }

    // PHYSIQUE : Lecture restaurants par lieu
    // RETOUR : Liste restaurants
    function physiqueRestaurantsParLieux($lieu, $equipe)
    {
        // Initialisations
        $listeRestaurantsParLieux = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM food_advisor_restaurants
                            WHERE team = "' . $equipe . '" AND location = "' . $lieu . '"
                            ORDER BY name ASC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Restaurant à partir des données remontées de la bdd
            $restaurant = Restaurant::withData($data);

            $restaurant->setMin_price(str_replace('.', ',', $restaurant->getMin_price()));
            $restaurant->setMax_price(str_replace('.', ',', $restaurant->getMax_price()));

            // On ajoute la ligne au tableau
            array_push($listeRestaurantsParLieux, $restaurant);
        }

        $req->closeCursor();

        // Retour
        return $listeRestaurantsParLieux;
    }

    // PHYSIQUE : Lecture bande à part
    // RETOUR : Booléen
    function physiqueSolo($identifiant, $equipe, $date)
    {
        // Initialisations
        $solo = false;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                            FROM food_advisor_users
                            WHERE id_restaurant = 0 AND date = "' . $date . '" AND identifiant = "' . $identifiant . '" AND team = "' . $equipe . '"');

        $data = $req->fetch();

        if ($data['nombreLignes'] > 0)
            $solo = true;

        $req->closeCursor();

        // Retour
        return $solo;
    }

    // PHYSIQUE : Lecture propositions
    // RETOUR : Liste des propositions
    function physiquePropositions($equipe, $date)
    {
        // Initialisations
        $listePropositions = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT DISTINCT id_restaurant
                            FROM food_advisor_users
                            WHERE id_restaurant != 0 AND date = "' . $date . '" AND team = "' . $equipe . '"');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Proposition à partir des données remontées de la bdd
            $proposition = Proposition::withData($data);

            // On ajoute la ligne au tableau
            array_push($listePropositions, $proposition);
        }

        $req->closeCursor();

        // Retour
        return $listePropositions;
    }

    // PHYSIQUE : Lecture choix utilisateur
    // RETOUR : Liste des choix
    function physiqueListeChoix($identifiant, $equipe, $date)
    {
        // Initialisations
        $listeChoix = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM food_advisor_users
                            WHERE id_restaurant != 0 AND team = "' . $equipe . '" AND identifiant = "' . $identifiant . '" AND date = "' . $date . '"
                            ORDER BY id ASC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Choix à partir des données remontées de la bdd
            $choix = Choix::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeChoix, $choix);
        }

        $req->closeCursor();

        // Retour
        return $listeChoix;
    }

    // PHYSIQUE : Lecture détermination du jour sélectionné
    // RETOUR : Objet Proposition
    function physiqueDetermination($equipe, $date)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM food_advisor_choices
                            WHERE date = "' . $date . '" AND team = "' . $equipe . '"');

        $data = $req->fetch();

        // Instanciation d'un objet Proposition à partir des données remontées de la bdd
        $determination = Proposition::withData($data);

        $req->closeCursor();

        // Retour
        return $determination;
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

    // PHYSIQUE : Lecture nombre participants
    // RETOUR : Nombre de participants
    function physiqueNombreParticipants($idRestaurant, $date)
    {
        // Initialisations
        $nombreParticipants = 0;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreParticipants
                            FROM food_advisor_users
                            WHERE date = "' . $date . '" AND id_restaurant = ' . $idRestaurant);

        $data = $req->fetch();

        if ($data['nombreParticipants'] > 0)
            $nombreParticipants = $data['nombreParticipants'];

        $req->closeCursor();

        // Retour
        return $nombreParticipants;
    }

    // PHYSIQUE : Lecture proposition déterminée
    // RETOUR : Objet Proposition
    function physiquePropositionDeterminee($idRestaurant, $date)
    {
        // Initialisations
        $propositionDeterminee = NULL;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *, COUNT(*) AS nombreLignes
                            FROM food_advisor_choices
                            WHERE date = "' . $date . '" AND id_restaurant = ' . $idRestaurant);

        $data = $req->fetch();

        if ($data['nombreLignes'] > 0)
        {
            // Instanciation d'un objet Proposition à partir des données remontées de la bdd
            $propositionDeterminee = Proposition::withData($data);
        }

        $req->closeCursor();

        // Retour
        return $propositionDeterminee;
    }

    // PHYSIQUE : Lecture détails proposition
    // RETOUR : Détails proposition
    function physiqueDetailsProposition($idRestaurant, $date)
    {
        // Initialisations
        $details = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM food_advisor_users
                            WHERE date = "' . $date . '" AND id_restaurant = ' . $idRestaurant . '
                            ORDER BY identifiant ASC');

        while ($data = $req->fetch())
        {
            $detailsUser = new DetailsProposition();

            $detailsUser->setIdentifiant($data['identifiant']);
            $detailsUser->setPseudo('');
            $detailsUser->setAvatar('');
            $detailsUser->setTransports($data['transports']);
            $detailsUser->setHoraire($data['time']);
            $detailsUser->setMenu($data['menu']);

            // On ajoute la ligne au tableau
            array_push($details, $detailsUser);
        }

        $req->closeCursor();

        // Retour
        return $details;
    }

    // PHYSIQUE : Lecture identifiant appelant
    // RETOUR : Identifiant
    function physiqueIdentifiantCaller($equipe, $date)
    {
        // Initialisations
        $caller = '';

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *, COUNT(*) AS nombreLignes
                            FROM food_advisor_choices
                            WHERE date = "' . $date . '" AND team = "' . $equipe . '" AND reserved = "Y"');

        $data = $req->fetch();

        if ($data['nombreLignes'] > 0)
            $caller = $data['caller'];

        $req->closeCursor();

        // Retour
        return $caller;
    }

    // PHYSIQUE : Lecture identifiants participants
    // RETOUR : Liste des identifiants
    function physiqueParticipants($idRestaurant, $date)
    {
        // Initialisations
        $listeParticipants = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT DISTINCT identifiant
                            FROM food_advisor_users
                            WHERE date = "' . $date . '" AND id_restaurant = ' . $idRestaurant . '
                            ORDER BY identifiant ASC');

        while ($data = $req->fetch())
        {
            // On ajoute la ligne au tableau
            array_push($listeParticipants, $data['identifiant']);
        }

        $req->closeCursor();

        // Retour
        return $listeParticipants;
    }

    // PHYSIQUE : Lecture identifiants appelants entre 2 dates
    // RETOUR : Liste des identifiants
    function physiqueAppelants($date1, $date2, $equipe)
    {
        // Initialisations
        $listeAppelants = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT DISTINCT caller
                            FROM food_advisor_choices
                            WHERE date != "' . date('Ymd') . '" AND date >= "' . $date1 . '" AND date <= "' . $date2 . '" AND team = "' . $equipe . '"
                            ORDER BY caller ASC');

        while ($data = $req->fetch())
        {
            // On ajoute la ligne au tableau
            array_push($listeAppelants, $data['caller']);
        }

        $req->closeCursor();

        // Retour
        return $listeAppelants;
    }

    // PHYSIQUE : Lecture détermination existante
    // RETOUR : Booléen
    function physiqueDeterminationExistante($equipe, $date)
    {
        // Initialisations
        $exist = false;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                            FROM food_advisor_choices
                            WHERE date = "' . $date . '" AND team = "' . $equipe . '"');

        $data = $req->fetch();

        if ($data['nombreLignes'] > 0)
            $exist = true;

        $req->closeCursor();

        // Retour
        return $exist;
    }

    // PHYSIQUE : Lecture choix existant
    // RETOUR : Booléen
    function physiqueChoixExistant($idRestaurant, $identifiant, $equipe, $date)
    {
        // Initialisations
        $exist = false;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreChoix
                            FROM food_advisor_users
                            WHERE id_restaurant = ' . $idRestaurant . ' AND team = "' . $equipe . '" AND identifiant = "' . $identifiant . '" AND date = "' . $date . '"');

        $data = $req->fetch();

        if ($data['nombreChoix'] > 0)
            $exist = true;

        $req->closeCursor();

        // Retour
        return $exist;
    }

    // PHYSIQUE : Lecture nombre de choix restants
    // RETOUR : Nombre de choix
    function physiqueChoixRestants($equipe, $date)
    {
        // Initialisations
        $nombreChoix = 0;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreChoix
                            FROM food_advisor_users
                            WHERE id_restaurant != 0 AND date = "' . $date . '" AND team = "' . $equipe . '"');

        $data = $req->fetch();

        if ($data['nombreChoix'] > 0)
            $nombreChoix = $data['nombreChoix'];

        $req->closeCursor();

        // Retour
        return $nombreChoix;
    }

    /****************************************************************************/
    /********************************** INSERT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Insertion nouvelle détermination
    // RETOUR : Aucun
    function physiqueInsertionDetermination($determination)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('INSERT INTO food_advisor_choices(id_restaurant,
                                                               team,
                                                               date,
                                                               caller,
                                                               reserved)
                                                       VALUES(:id_restaurant,
                                                              :team,
                                                              :date,
                                                              :caller,
                                                              :reserved)');

        $req->execute($determination);

        $req->closeCursor();
    }

    // PHYSIQUE : Insertion choix
    // RETOUR : Aucun
    function physiqueInsertionChoix($choix)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('INSERT INTO food_advisor_users(id_restaurant,
                                                             team,
                                                             identifiant,
                                                             date,
                                                             time,
                                                             transports,
                                                             menu)
                                                     VALUES(:id_restaurant,
                                                            :team,
                                                            :identifiant,
                                                            :date,
                                                            :time,
                                                            :transports,
                                                            :menu)');

        $req->execute($choix);

        $req->closeCursor();
    }

    /****************************************************************************/
    /********************************** UPDATE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Mise à jour détermination existante
    // RETOUR : Aucun
    function physiqueUpdateDetermination($determination, $idChoix)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE food_advisor_choices
                              SET id_restaurant = :id_restaurant,
                                  caller        = :caller,
                                  reserved      = :reserved
                              WHERE id = ' . $idChoix);

        $req->execute($determination);

        $req->closeCursor();
    }

    /****************************************************************************/
    /********************************** DELETE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Suppression détermination du jour
    // RETOUR : Aucun
    function physiqueDeleteDetermination($equipe, $date)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM food_advisor_choices
                           WHERE date = "' . $date . '" AND team = "' . $equipe . '"');
    }
?>