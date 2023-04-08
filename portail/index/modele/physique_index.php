<?php
    include_once('includes/functions/appel_bdd.php');

    /****************************************************************************/
    /********************************** SELECT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Lecture de la liste des équipes
    // RETOUR : Liste des équipes
    function physiqueListeEquipes()
    {
        // Initialisations
        $listeEquipes = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM teams
                            WHERE activation = "Y"
                            ORDER BY team ASC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Team à partir des données remontées de la bdd
            $equipe = Team::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeEquipes, $equipe);
        }

        $req->closeCursor();

        // Retour
        return $listeEquipes;
    }

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

    // PHYSIQUE : Lecture mot de passe utilisateur
    // RETOUR : Mot de passe crypté
    function physiquePassword($identifiant)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM users
                            WHERE identifiant = "' . $identifiant . '"');

        $data = $req->fetch();

        $dataUser = array(
            'password' => $data['password'],
            'salt'     => $data['salt']
        );

        $req->closeCursor();

        // Retour
        return $dataUser;
    }

    // PHYSIQUE : Lecture équipe utilisateur
    // RETOUR : Objet Team
    function physiqueEquipe($equipeUser)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM teams
                            WHERE reference = "' . $equipeUser . '"');

        $data = $req->fetch();

        // Instanciation d'un objet Team à partir des données remontées de la bdd
        $equipe = Team::withData($data);

        $req->closeCursor();

        // Retour
        return $equipe;
    }

    // PHYSIQUE : Lecture préférences utilisateur
    // RETOUR : Objet Preferences
    function physiquePreferences($identifiant)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM preferences
                            WHERE identifiant = "' . $identifiant . '"');

        $data = $req->fetch();

        // Instanciation d'un objet Preferences à partir des données remontées de la bdd
        $preference = Preferences::withData($data);

        $req->closeCursor();

        // Retour
        return $preference;
    }

    // PHYSIQUE : Lecture des identifiants d'une table
    // RETOUR : Liste des identifiants uniques
    function physiqueIdentifiantsTable($table, $colonne)
    {
        // Initialisations
        $listeIdentifiants = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT DISTINCT ' . $colonne . '
                            FROM ' . $table . '
                            ORDER BY ' . $colonne . ' ASC');

        while ($data = $req->fetch())
        {
            array_push($listeIdentifiants, $data[$colonne]);
        }

        $req->closeCursor();

        // Retour
        return $listeIdentifiants;
    }

    /****************************************************************************/
    /********************************** INSERT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Insertion nouvel utilisateur
    // RETOUR : Aucun
    function physiqueInsertionUser($user)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('INSERT INTO users(identifiant,
                                                team,
                                                new_team,
                                                salt,
                                                password,
                                                ping,
                                                status,
                                                pseudo,
                                                avatar,
                                                email,
                                                anniversary,
                                                experience,
                                                expenses)
                                        VALUES(:identifiant,
                                               :team,
                                               :new_team,
                                               :salt,
                                               :password,
                                               :ping,
                                               :status,
                                               :pseudo,
                                               :avatar,
                                               :email,
                                               :anniversary,
                                               :experience,
                                               :expenses)');

        $req->execute($user);

        $req->closeCursor();
    }

    // PHYSIQUE : Insertion préférence nouvel utilisateur
    // RETOUR : Aucun
    function physiqueInsertionPreferences($preferences)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('INSERT INTO preferences(identifiant,
                                                      ref_theme,
                                                      font,
                                                      init_chat,
                                                      celsius,
                                                      view_movie_house,
                                                      categories_movie_house,
                                                      view_the_box,
                                                      view_notifications,
                                                      manage_calendars)
                                              VALUES(:identifiant,
                                                     :ref_theme,
                                                     :font,
                                                     :init_chat,
                                                     :celsius,
                                                     :view_movie_house,
                                                     :categories_movie_house,
                                                     :view_the_box,
                                                     :view_notifications,
                                                     :manage_calendars)');

        $req->execute($preferences);

        $req->closeCursor();
    }

    // PHYSIQUE : Insertion nouvelle équipe
    // RETOUR : Aucun
    function physiqueInsertionEquipe($equipe)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('INSERT INTO teams(reference,
                                                team,
                                                activation)
                                        VALUES(:reference,
                                               :team,
                                               :activation)');

        $req->execute($equipe);

        $req->closeCursor();
    }

    /****************************************************************************/
    /********************************** UPDATE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Mise à jour alerte
    // RETOUR : Aucun
    function physiqueUpdateStatut($statut, $identifiant)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE users
                              SET status = :status
                              WHERE identifiant = "' . $identifiant . '"');

        $req->execute(array(
            'status' => $statut
        ));

        $req->closeCursor();
    }
?>