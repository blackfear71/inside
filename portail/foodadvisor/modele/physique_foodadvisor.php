<?php
    include_once('../../includes/functions/appel_bdd.php');

    /****************************************************************************/
    /********************************** SELECT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Lecture des utilisateurs inscrits
    // RETOUR : Liste des utilisateurs
    function physiqueUsers($equipe, $dateDebut, $dateFin)
    {
        // Initialisations
        $listeUsers = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT id, identifiant, team, pseudo, avatar
                            FROM users
                            WHERE (identifiant != "admin" AND team = "' . $equipe . '" AND status != "D"  AND status != "I")
                            OR EXISTS (SELECT id, team, date, caller
                                    FROM food_advisor_choices
                                    WHERE food_advisor_choices.caller = users.identifiant AND food_advisor_choices.team = "' . $equipe . '" AND date >= "' . $dateDebut . '" AND date <= "' . $dateFin . '")
                            OR EXISTS (SELECT id, team, identifiant, date
                                    FROM food_advisor_users
                                    WHERE food_advisor_users.identifiant = users.identifiant AND food_advisor_users.team = "' . $equipe . '" AND date >= "' . $dateDebut . '" AND date <= "' . $dateFin . '")
                            ORDER BY identifiant ASC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Profile à partir des données remontées de la bdd
            $user = Profile::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeUsers, $user);
        }

        $req->closeCursor();

        // Retour
        return $listeUsers;
    }

    // PHYSIQUE : Lecture bande à part
    // RETOUR : Liste identifiants
    function physiqueIdentifiantsSolos($equipe)
    {
        // Initialisations
        $identifiantsSolos = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM food_advisor_users
                            WHERE id_restaurant = 0 AND team = "' . $equipe . '" AND date = "' . date('Ymd') . '"
                            ORDER BY identifiant ASC');

        while ($data = $req->fetch())
        {
            // On ajoute la ligne au tableau
            array_push($identifiantsSolos, $data['identifiant']);
        }

        $req->closeCursor();

        // Retour
        return $identifiantsSolos;
    }

    // PHYSIQUE : Lecture nombre de propositions d'un utilisateur
    // RETOUR : Nombre de propositions
    function physiqueNombrePropositions($equipe, $identifiant)
    {
        // Initialisations
        $nombrePropositions = 0;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                            FROM food_advisor_users
                            WHERE date = "' . date('Ymd') . '" AND team = "' . $equipe . '" AND identifiant = "' . $identifiant . '"');

        $data = $req->fetch();

        if ($data['nombreLignes'] > 0)
            $nombrePropositions = $data['nombreLignes'];

        $req->closeCursor();

        // Retour
        return $nombrePropositions;
    }

    // PHYSIQUE : Lecture choix à date
    // RETOUR : Objet Proposition
    function physiqueDonneesResume($equipe, $date)
    {
        // Initialisations
        $resume = NULL;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *, COUNT(*) AS nombreLignes
                            FROM food_advisor_choices
                            WHERE team = "' . $equipe . '" AND date = "' . $date . '"');

        $data = $req->fetch();

        if ($data['nombreLignes'] > 0)
        {
            // Instanciation d'un objet Proposition à partir des données remontées de la bdd
            $resume = Proposition::withData($data);
        }

        $req->closeCursor();

        // Retour
        return $resume;
    }

    // PHYSIQUE : Lecture choix utilisateur
    // RETOUR : Objet Choix
    function physiqueChoix($idChoix)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM food_advisor_users
                            WHERE id = ' . $idChoix);

        $data = $req->fetch();

        // Instanciation d'un objet Choix à partir des données remontées de la bdd
        $choix = Choix::withData($data);

        $req->closeCursor();

        // Retour
        return $choix;
    }

    // PHYSIQUE : Lecture détermination existante liée à l'utilisateur
    // RETOUR : Booléen
    function physiqueDeterminationExistanteUser($identifiant, $equipe)
    {
        // Initialisations
        $exist = false;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                            FROM food_advisor_choices
                            WHERE date = "' . date('Ymd') . '" AND team = "' . $equipe . '" AND caller = "' . $identifiant . '"');

        $data = $req->fetch();

        if ($data['nombreLignes'] > 0)
            $exist = true;

        $req->closeCursor();

        // Retour
        return $exist;
    }

    // PHYSIQUE : Lecture choix existant
    // RETOUR : Booléen
    function physiqueChoixExistantDate($date, $equipe)
    {
        // Initialisations
        $exist = false;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreChoix
                            FROM food_advisor_choices
                            WHERE team = "' . $equipe . '" AND date = "' . $date . '"');

        $data = $req->fetch();

        if ($data['nombreChoix'] > 0)
            $exist = true;

        $req->closeCursor();

        // Retour
        return $exist;
    }

    /****************************************************************************/
    /********************************** UPDATE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Annulation réservation
    // RETOUR : Aucun
    function physiqueAnnulationReservation($idRestaurant, $equipe, $identifiant)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE food_advisor_choices
                              SET reserved      = :reserved
                              WHERE id_restaurant = ' . $idRestaurant . ' AND date = "' . date('Ymd') . '" AND team = "' . $equipe . '" AND caller = "' . $identifiant . '"');

        $req->execute(array(
            'reserved' => 'N'
        ));

        $req->closeCursor();
    }

    // PHYSIQUE : Mise à jour choix existant
    // RETOUR : Aucun
    function physiqueUpdateChoix($idChoix, $choix, $identifiant)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE food_advisor_users
                              SET time       = :time,
                                  transports = :transports,
                                  menu       = :menu
                              WHERE id = ' . $idChoix . ' AND identifiant = "' . $identifiant . '" AND date = "' . date('Ymd') . '"');

        $req->execute($choix);

        $req->closeCursor();
    }

    /****************************************************************************/
    /********************************** DELETE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Suppression choix bande à part
    // RETOUR : Aucun
    function physiqueDeleteSolo($equipe, $identifiant)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM food_advisor_users
                           WHERE id_restaurant = 0 AND date = "' . date('Ymd') . '" AND team = "' . $equipe . '" AND identifiant = "' . $identifiant . '"');
    }

    // PHYSIQUE : Suppression choix d'un restaurant
    // RETOUR : Aucun
    function physiqueDeleteComplete($idRestaurant, $equipe)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM food_advisor_users
                           WHERE date = "' . date('Ymd') . '" AND id_restaurant = "' . $idRestaurant . '" AND team = "' . $equipe . '"');
    }

    // PHYSIQUE : Suppression détermination du jour liée à l'utilisateur
    // RETOUR : Aucun
    function physiqueDeleteDeterminationUser($idRestaurant, $equipe, $identifiant)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM food_advisor_choices
                           WHERE id_restaurant = ' . $idRestaurant . ' AND date = "' . date('Ymd') . '" AND team = "' . $equipe . '" AND caller = "' . $identifiant . '"');
    }

    // PHYSIQUE : Suppression choix d'un utilisateur
    // RETOUR : Aucun
    function physiqueDeleteChoix($idChoix)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM food_advisor_users
                           WHERE id = ' . $idChoix);
    }

    // PHYSIQUE : Suppression détermination existante pour un utilisateur
    // RETOUR : Aucun
    function physiqueDeleteDeterminationRestaurantUser($idRestaurant, $equipe, $identifiant)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM food_advisor_choices
                           WHERE date = "' . date('Ymd') . '" AND id_restaurant = ' . $idRestaurant . ' AND team = "' . $equipe . '" AND caller = "' . $identifiant . '"');
    }

    // PHYSIQUE : Suppression de tous les choix d'un utilisateur
    // RETOUR : Aucun
    function physiqueDeleteTousChoix($equipe, $identifiant)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM food_advisor_users
                           WHERE date = "' . date('Ymd') . '" AND team = "' . $equipe . '" AND identifiant = "' . $identifiant . '"');
    }

    // PHYSIQUE : Suppression résumé
    // RETOUR : Aucun
    function physiqueDeleteResume($idRestaurant, $equipe, $date)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM food_advisor_choices
                           WHERE id_restaurant = ' . $idRestaurant . ' AND team = "' . $equipe . '" AND date = "' . $date . '"');
    }
?>