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

        $crypt = array(
            'salt' => $data['salt'],
            'password' => $data['password']
        );

        $req->closeCursor();

        // Retour
        return $crypt;
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
                              SET pseudo = :pseudo,
                                  email  = :email
                              WHERE identifiant = "' . $identifiant . '"');

        $req->execute($user);

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
?>