<?php
    include_once('../../includes/functions/appel_bdd.php');

    /****************************************************************************/
    /********************************** SELECT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Lecture des utilisateurs inscrits
    // RETOUR : Liste des utilisateurs
    function physiqueUsers($equipe, $date)
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
                                       WHERE food_advisor_choices.caller = users.identifiant AND food_advisor_choices.team = "' . $equipe . '" AND date = "' . $date . '")
                            OR EXISTS (SELECT id, team, identifiant, date
                                       FROM food_advisor_users
                                       WHERE food_advisor_users.identifiant = users.identifiant AND food_advisor_users.team = "' . $equipe . '" AND date = "' . $date . '")
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

    // PHYSIQUE : Lecture utilisateurs faisant bande à part
    // RETOUR : Liste utilisateurs
    function physiqueUtilisateursSolos($equipe, $date)
    {
        // Initialisations
        $listeUtilisateurs = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT DISTINCT food_advisor_users.identifiant, users.*
                            FROM food_advisor_users
                            LEFT JOIN users ON food_advisor_users.identifiant = users.identifiant
                            WHERE food_advisor_users.id_restaurant = 0 AND food_advisor_users.team = "' . $equipe . '" AND food_advisor_users.date = "' . $date . '"
                            ORDER BY food_advisor_users.identifiant ASC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Profile à partir des données remontées de la bdd
            $user = Profile::withData($data);
            
            // On ajoute la ligne au tableau
            array_push($listeUtilisateurs, $user);
        }

        $req->closeCursor();

        // Retour
        return $listeUtilisateurs;
    }
    
    // PHYSIQUE : Lecture nombre de propositions d'un utilisateur
    // RETOUR : Nombre de propositions
    function physiqueNombrePropositions($equipe, $identifiant, $date)
    {
        // Initialisations
        $nombrePropositions = 0;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                            FROM food_advisor_users
                            WHERE date = "' . $date . '" AND team = "' . $equipe . '" AND identifiant = "' . $identifiant . '"');

        $data = $req->fetch();

        if ($data['nombreLignes'] > 0)
            $nombrePropositions = $data['nombreLignes'];

        $req->closeCursor();

        // Retour
        return $nombrePropositions;
    }

    // PHYSIQUE : Lecture utilisateurs résumé
    // RETOUR : Liste des utilisateurs
    function physiqueUsersResume($equipe, $dateMin, $dateMax)
    {
        // Initialisations
        $listeUsers = array();

        // Requête
        global $bdd;
        
        $req = $bdd->query('SELECT DISTINCT food_advisor_choices.caller, users.*
                            FROM food_advisor_choices
                            LEFT JOIN users ON food_advisor_choices.caller = users.identifiant
                            WHERE food_advisor_choices.caller != "" AND food_advisor_choices.team = "' . $equipe . '" AND food_advisor_choices.date >= "' . $dateMin . '" AND food_advisor_choices.date <= "' . $dateMax . '"
                            ORDER BY food_advisor_choices.caller ASC');

        while ($data = $req->fetch())
        {
            // Création tableau de correspondance identifiant / pseudo / avatar
            $listeUsers[$data['identifiant']] = array(
                'pseudo' => $data['pseudo'],
                'avatar' => $data['avatar']
            );
        }

        $req->closeCursor();

        // Retour
        return $listeUsers;
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
    function physiqueDeterminationExistanteUser($identifiant, $equipe, $date)
    {
        // Initialisations
        $exist = false;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                            FROM food_advisor_choices
                            WHERE date = "' . $date . '" AND team = "' . $equipe . '" AND caller = "' . $identifiant . '"');

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
    function physiqueAnnulationReservation($idRestaurant, $equipe, $identifiant, $date)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE food_advisor_choices
                              SET reserved      = :reserved
                              WHERE id_restaurant = ' . $idRestaurant . ' AND date = "' . $date . '" AND team = "' . $equipe . '" AND caller = "' . $identifiant . '"');

        $req->execute(array(
            'reserved' => 'N'
        ));

        $req->closeCursor();
    }

    // PHYSIQUE : Mise à jour choix existant
    // RETOUR : Aucun
    function physiqueUpdateChoix($idChoix, $choix, $identifiant, $date)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE food_advisor_users
                              SET time       = :time,
                                  transports = :transports,
                                  menu       = :menu
                              WHERE id = ' . $idChoix . ' AND identifiant = "' . $identifiant . '" AND date = "' . $date . '"');

        $req->execute($choix);

        $req->closeCursor();
    }

    /****************************************************************************/
    /********************************** DELETE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Suppression choix bande à part
    // RETOUR : Aucun
    function physiqueDeleteSolo($equipe, $identifiant, $date)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM food_advisor_users
                           WHERE id_restaurant = 0 AND date = "' . $date . '" AND team = "' . $equipe . '" AND identifiant = "' . $identifiant . '"');
    }

    // PHYSIQUE : Suppression choix d'un restaurant
    // RETOUR : Aucun
    function physiqueDeleteComplete($idRestaurant, $equipe, $date)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM food_advisor_users
                           WHERE date = "' . $date . '" AND id_restaurant = "' . $idRestaurant . '" AND team = "' . $equipe . '"');
    }

    // PHYSIQUE : Suppression détermination du jour liée à l'utilisateur
    // RETOUR : Aucun
    function physiqueDeleteDeterminationUser($idRestaurant, $equipe, $identifiant, $date)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM food_advisor_choices
                           WHERE id_restaurant = ' . $idRestaurant . ' AND date = "' . $date . '" AND team = "' . $equipe . '" AND caller = "' . $identifiant . '"');
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
    function physiqueDeleteDeterminationRestaurantUser($idRestaurant, $equipe, $identifiant, $date)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM food_advisor_choices
                           WHERE date = "' . $date . '" AND id_restaurant = ' . $idRestaurant . ' AND team = "' . $equipe . '" AND caller = "' . $identifiant . '"');
    }

    // PHYSIQUE : Suppression de tous les choix d'un utilisateur
    // RETOUR : Aucun
    function physiqueDeleteTousChoix($equipe, $identifiant, $date)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM food_advisor_users
                           WHERE date = "' . $date . '" AND team = "' . $equipe . '" AND identifiant = "' . $identifiant . '"');
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