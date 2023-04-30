<?php
    include_once('../../includes/functions/appel_bdd.php');

    /****************************************************************************/
    /********************************** SELECT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Lecture profil
    // RETOUR : Objet Profile
    function physiqueProfil($identifiant)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM users
                            WHERE identifiant = "' . $identifiant . '"');

        $data = $req->fetch();

        // Instanciation d'un objet Profile à partir des données remontées de la bdd
        $profil = Profile::withData($data);

        $req->closeCursor();

        // Retour
        return $profil;
    }

    // PHYSIQUE : Lecture équipe
    // RETOUR : Objet Team
    function physiqueEquipe($reference)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM teams
                            WHERE reference = "' . $reference . '"');

        $data = $req->fetch();

        // Instanciation d'un objet Team à partir des données remontées de la bdd
        $equipe = Team::withData($data);

        $req->closeCursor();

        // Retour
        return $equipe;
    }

    // PHYSIQUE : Lecture du nombre de lignes dans une table
    // RETOUR : Nombre de lignes
    function physiqueNombreLignesTable($table, $colonne, $identifiant)
    {
        // Initialisations
        $nombreLignes = 0;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                            FROM ' . $table . '
                            WHERE ' . $colonne . ' = "' . $identifiant . '"');

        $data = $req->fetch();

        $nombreLignes = $data['nombreLignes'];

        $req->closeCursor();

        // Retour
        return $nombreLignes;
    }

    // PHYSIQUE : Lecture du nombre de gâteaux de la semaine
    // RETOUR : Nombre de gâteaux de la semaine
    function physiqueGateauxSemaineUser($identifiant)
    {
        // Initialisations
        $nombreGateauxSemaine = 0;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreGateauxSemaine
                            FROM cooking_box
                            WHERE identifiant = "' . $identifiant . '" AND cooked = "Y"');

        $data = $req->fetch();

        $nombreGateauxSemaine = $data['nombreGateauxSemaine'];

        $req->closeCursor();

        // Retour
        return $nombreGateauxSemaine;
    }

    // PHYSIQUE : Lecture du nombre de recettes partagées
    // RETOUR : Nombre de recettes partagées
    function physiqueRecettesUser($identifiant)
    {
        // Initialisations
        $nombreRecettes = 0;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreRecettes
                            FROM cooking_box
                            WHERE identifiant = "' . $identifiant . '" AND name != "" AND picture != ""');

        $data = $req->fetch();

        $nombreRecettes = $data['nombreRecettes'];

        $req->closeCursor();

        // Retour
        return $nombreRecettes;
    }

    // PHYSIQUE : Lecture du nombre de bugs / évolutions soumis d'un utilisateur
    // RETOUR : Nombre de bugs / évolutions soumis de l'utilisateur
    function physiqueBugsEvolutionsSoumisUser($identifiant, $type)
    {
        // Initialisations
        $nombreSoumis = 0;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreSoumis
                            FROM bugs
                            WHERE identifiant = "' . $identifiant . '" AND type = "' . $type . '"');

        $data = $req->fetch();

        $nombreSoumis = $data['nombreSoumis'];

        $req->closeCursor();

        // Retour
        return $nombreSoumis;
    }

    // PHYSIQUE : Lecture préférences
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
        $preferences = Preferences::withData($data);

        $req->closeCursor();

        // Retour
        return $preferences;
    }

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

    // PHYSIQUE : Lecture avatar utilisateur
    // RETOUR : Avatar
    function physiqueAvatarUser($identifiant)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT identifiant, avatar
                            FROM users
                            WHERE identifiant = "' . $identifiant . '"');

        $data = $req->fetch();

        $avatar = $data['avatar'];

        $req->closeCursor();

        // Retour
        return $avatar;
    }

    // PHYSIQUE : Lecture données mot de passe utilisateur
    // RETOUR : Données mot de passe
    function physiqueDonneesPasswordUser($identifiant)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT id, identifiant, salt, password
                            FROM users
                            WHERE identifiant = "' . $identifiant . '"');

        $data = $req->fetch();

        $crypt = array('salt' => $data['salt'], 'password' => $data['password']);

        $req->closeCursor();

        // Retour
        return $crypt;
    }

    // PHYSIQUE : Lecture des utilisateurs
    // RETOUR : Tableau des utilisateurs
    function physiqueUsers($equipe)
    {
        // Initialisations
        $listeUsers = array();

        global $bdd;

        // Requête
        $req = $bdd->query('SELECT id, identifiant, team, status, pseudo, avatar, email, experience
                            FROM users
                            WHERE identifiant != "admin" AND team = "' . $equipe . '" AND status != "I"
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

    // PHYSIQUE : Lecture des succès
    // RETOUR : Liste des succès
    function physiqueListeSuccess()
    {
        // Initialisations
        $listeSuccess = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM success
                            ORDER BY level ASC, order_success ASC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Success à partir des données remontées de la bdd
            $success = Success::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeSuccess, $success);
        }

        $req->closeCursor();

        // Retour
        return $listeSuccess;
    }

    // PHYSIQUE : Lecture des succès de l'utilisateur
    // RETOUR : Valeur succès
    function physiqueSuccessUser($reference, $identifiant)
    {
        // Initialisations
        $value = NULL;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *, COUNT(*) AS nombreLignes
                            FROM success_users
                            WHERE reference = "' . $reference . '" AND identifiant = "' . $identifiant . '"');

        $data = $req->fetch();

        if ($data['nombreLignes'] > 0)
            $value = $data['value'];

        $req->closeCursor();

        // Retour
        return $value;
    }

    // PHYSIQUE : Lecture des succès des utilisateurs
    // RETOUR : Liste des utilisateurs
    function physiqueSuccessUsers($reference, $limite, $user)
    {
        // Initialisations
        $rangSuccess = NULL;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM success_users
                            WHERE reference = "' . $reference . '" AND identifiant = "' . $user['identifiant'] . '"');

        $data = $req->fetch();

        // Vérification que l'utilisateur a débloqué le succès pour l'ajouter
        if ($data AND $data['value'] >= $limite)
        {
            // Génération d'un objet Classement
            $rangSuccess = new Classement();

            $rangSuccess->setIdentifiant($data['identifiant']);
            $rangSuccess->setPseudo($user['pseudo']);
            $rangSuccess->setAvatar($user['avatar']);
            $rangSuccess->setValue($data['value']);
        }

        $req->closeCursor();

        // Retour
        return $rangSuccess;
    }

    // PHYSIQUE : Lecture date de fin de mission
    // RETOUR : Date
    function physiqueDateFinMission($reference)
    {
        // Initialisations
        $dateFinMission = '';

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM missions
                            WHERE reference = "' . $reference . '"');

        $data = $req->fetch();

        if ($data)
            $dateFinMission = $data['date_fin'];

        $req->closeCursor();

        // Retour
        return $dateFinMission;
    }

    // PHYSIQUE : Lecture liste des thèmes
    // RETOUR : Liste des thèmes
    function physiqueThemes($type, $niveau)
    {
        // Initialisations
        $listeThemes = array();

        // Requête
        global $bdd;

        if ($type == 'U')
        {
            $req = $bdd->query('SELECT *
                            FROM themes
                            WHERE type = "' . $type . '" AND level <= ' . $niveau . '
                            ORDER BY CAST(level AS UNSIGNED) ASC');
        }
        else
        {
            $req = $bdd->query('SELECT *
                            FROM themes
                            WHERE type = "' . $type . '" AND date_deb <= ' . date('Ymd') . '
                            ORDER BY date_deb DESC');
        }

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Theme à partir des données remontées de la bdd
            $theme = Theme::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeThemes, $theme);
        }

        $req->closeCursor();

        // Retour
        return $listeThemes;
    }

    // PHYSIQUE : Détermination thème mission en cours
    // RETOUR : Booléen
    function physiqueThemeMission()
    {
        // Initialisations
        $isThemeMission = false;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                            FROM themes
                            WHERE type = "M" AND date_deb <= ' . date('Ymd') . ' AND date_fin >= ' . date('Ymd') . '
                            ORDER BY id ASC');

        $data = $req->fetch();

        if ($data['nombreLignes'] > 0)
            $isThemeMission = true;

        $req->closeCursor();

        // Retour
        return $isThemeMission;
    }

    // PHYSIQUE : Lecture référence thème
    // RETOUR : Référence thème
    function physiqueReferenceTheme($idTheme)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM themes
                            WHERE id = ' . $idTheme);

        $data = $req->fetch();

        $referenceTheme = $data['reference'];

        $req->closeCursor();

        // Retour
        return $referenceTheme;
    }

    /****************************************************************************/
    /********************************** INSERT **********************************/
    /****************************************************************************/
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
    // PHYSIQUE : Mise à jour avatar
    // RETOUR : Aucun
    function physiqueUpdateAvatarUser($identifiant, $avatar)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE users
                              SET avatar = :avatar
                              WHERE identifiant = "' . $identifiant . '"');

        $req->execute(array(
            'avatar' => $avatar
        ));

        $req->closeCursor();
    }

    // PHYSIQUE : Mise à jour utilisateur
    // RETOUR : Aucun
    function physiqueUpdateUser($user, $identifiant)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE users
                              SET pseudo      = :pseudo,
                                  email       = :email,
                                  anniversary = :anniversary
                              WHERE identifiant = "' . $identifiant . '"');

        $req->execute($user);

        $req->closeCursor();
    }

    // PHYSIQUE : Changement équipe utilisateur
    // RETOUR : Aucun
    function physiqueUpdateEquipeUser($equipeUser, $identifiant)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE users
                              SET new_team = :new_team,
                                  status   = :status
                              WHERE identifiant = "' . $identifiant . '"');

        $req->execute($equipeUser);

        $req->closeCursor();
    }

    // PHYSIQUE : Mise à jour préférences
    // RETOUR : Aucun
    function physiqueUpdatePreferences($preferences, $identifiant)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE preferences
                              SET init_chat              = :init_chat,
                                  celsius                = :celsius,
                                  view_movie_house       = :view_movie_house,
                                  categories_movie_house = :categories_movie_house,
                                  view_the_box           = :view_the_box,
                                  view_notifications     = :view_notifications
                              WHERE identifiant = "' . $identifiant . '"');

        $req->execute($preferences);

        $req->closeCursor();
    }

    // PHYSIQUE : Mise à jour mot de passe
    // RETOUR : Aucun
    function physiqueUpdatePasswordUser($salt, $password, $identifiant)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE users
                              SET salt     = :salt,
                                  password = :password
                              WHERE identifiant = "' . $identifiant . '"');

        $req->execute(array(
            'salt'     => $salt,
            'password' => $password
        ));

        $req->closeCursor();
    }

    // PHYSIQUE : Mise à jour statut utilisateur
    // RETOUR : Aucun
    function physiqueUpdateStatusUser($identifiant, $status)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE users
                              SET status = :status
                              WHERE identifiant = "' . $identifiant . '"');

        $req->execute(array(
            'status' => $status
        ));

        $req->closeCursor();
    }

    // PHYSIQUE : Mise à jour police utilisateur
    // RETOUR : Aucun
    function physiqueUpdateFont($identifiant, $police)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE preferences
                              SET font = :font
                              WHERE identifiant = "' . $identifiant . '"');

        $req->execute(array(
            'font' => $police
        ));

        $req->closeCursor();
    }

    // PHYSIQUE : Mise à jour thème utilisateur
    // RETOUR : Aucun
    function physiqueUpdateTheme($identifiant, $referenceTheme)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE preferences
                              SET ref_theme = :ref_theme
                              WHERE identifiant = "' . $identifiant . '"');

        $req->execute(array(
            'ref_theme' => $referenceTheme
        ));

        $req->closeCursor();
    }
?>