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

    // PHYSIQUE : Lecture des identifiants inscrits
    // RETOUR : Liste des utilisateurs uniques
    function physiqueIdentifiantsInscrits()
    {
        // Initialisations
        $listeUsersInscrits = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT DISTINCT identifiant
                            FROM users
                            ORDER BY identifiant ASC');

        while ($data = $req->fetch())
        {
            array_push($listeUsersInscrits, $data['identifiant']);
        }

        $req->closeCursor();

        // Retour
        return $listeUsersInscrits;
    }

    // PHYSIQUE : Lecture des identifiants des films
    // RETOUR : Liste des utilisateurs uniques
    function physiqueIdentifiantsFilms()
    {
        // Initialisations
        $listeUsersFilms = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT DISTINCT identifiant_add
                            FROM movie_house
                            ORDER BY identifiant_add ASC');

        while ($data = $req->fetch())
        {
            array_push($listeUsersFilms, $data['identifiant_add']);
        }

        $req->closeCursor();

        // Retour
        return $listeUsersFilms;
    }

    // PHYSIQUE : Lecture des identifiants des commentaires de films
    // RETOUR : Liste des utilisateurs uniques
    function physiqueIdentifiantsCommentairesFilms()
    {
        // Initialisations
        $listeUsersComments = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT DISTINCT identifiant
                            FROM movie_house_comments
                            ORDER BY identifiant ASC');

        while ($data = $req->fetch())
        {
            array_push($listeUsersComments, $data['identifiant']);
        }

        $req->closeCursor();

        // Retour
        return $listeUsersComments;
    }

    // PHYSIQUE : Lecture des identifiants des phrases cultes
    // RETOUR : Liste des utilisateurs uniques
    function physiqueIdentifiantsCollector()
    {
        // Initialisations
        $listeUsersCollector = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT DISTINCT author
                            FROM collector
                            ORDER BY author ASC');

        while ($data = $req->fetch())
        {
            array_push($listeUsersCollector, $data['author']);
        }

        $req->closeCursor();

        // Retour
        return $listeUsersCollector;
    }

    // PHYSIQUE : Lecture des identifiants des dépenses
    // RETOUR : Liste des utilisateurs uniques
    function physiqueIdentifiantsDepenses()
    {
        // Initialisations
        $listeUsersExpenses = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT DISTINCT buyer
                            FROM expense_center
                            ORDER BY buyer ASC');

        while ($data = $req->fetch())
        {
            array_push($listeUsersExpenses, $data['buyer']);
        }

        $req->closeCursor();

        // Retour
        return $listeUsersExpenses;
    }

    // PHYSIQUE : Lecture des identifiants des parts des dépenses
    // RETOUR : Liste des utilisateurs uniques
    function physiqueIdentifiantsPartsDepenses()
    {
        // Initialisations
        $listeUsersParts = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT DISTINCT identifiant
                            FROM expense_center_users
                            ORDER BY identifiant ASC');

        while ($data = $req->fetch())
        {
            array_push($listeUsersParts, $data['identifiant']);
        }

        $req->closeCursor();

        // Retour
        return $listeUsersParts;
    }

    // PHYSIQUE : Lecture des identifiants des bugs / évolutions
    // RETOUR : Liste des utilisateurs uniques
    function physiqueIdentifiantsBugs()
    {
        // Initialisations
        $listeUsersBugs = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT DISTINCT identifiant
                            FROM bugs
                            ORDER BY identifiant ASC');

        while ($data = $req->fetch())
        {
            array_push($listeUsersBugs, $data['identifiant']);
        }

        $req->closeCursor();

        // Retour
        return $listeUsersBugs;
    }

    // PHYSIQUE : Lecture des identifiants des idées #TheBox
    // RETOUR : Liste des utilisateurs uniques
    function physiqueIdentifiantsTheBox()
    {
        // Initialisations
        $listeUsersTheBox = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT DISTINCT author
                            FROM ideas
                            ORDER BY author ASC');

        while ($data = $req->fetch())
        {
            array_push($listeUsersTheBox, $data['author']);
        }

        $req->closeCursor();

        // Retour
        return $listeUsersTheBox;
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