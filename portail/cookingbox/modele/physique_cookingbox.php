<?php
    include_once('../../includes/functions/appel_bdd.php');

    /****************************************************************************/
    /********************************** SELECT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Vérification présence semaine et lecture des données d'une semaine
    // RETOUR : Objet WeekCake
    function physiqueSemaineGateau($equipe, $semaine, $annee)
    {
        // Initialisations
        $semaineGateau = new WeekCake();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *, COUNT(*) AS nombreGateauxSemaine
                            FROM cooking_box
                            WHERE team = "' . $equipe . '" AND week = "' . $semaine . '" AND year = "' . $annee . '"');

        $data = $req->fetch();

        if ($data['nombreGateauxSemaine'] > 0)
        {
            // Instanciation d'un objet WeekCake à partir des données remontées de la bdd
            $semaineGateau = WeekCake::withData($data);
        }

        $req->closeCursor();

        // Retour
        return $semaineGateau;
    }

    // PHYSIQUE : Lecture des données d'un utilisateur
    // RETOUR : Objet Profile
    function physiqueUser($identifiant)
    {
        // Initialisations
        $user = NULL;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT id, identifiant, pseudo, avatar, COUNT(*) AS nombreUser
                            FROM users
                            WHERE identifiant = "' . $identifiant . '"');

        $data = $req->fetch();

        // Instanciation d'un objet Profile à partir des données remontées de la bdd
        if ($data['nombreUser'] > 0)
            $user = Profile::withData($data);

        $req->closeCursor();

        // Retour
        return $user;
    }

    // PHYSIQUE : Lecture des utilisateurs inscrits
    // RETOUR : Liste des utilisateurs
    function physiqueUsers($equipe)
    {
        // Initialisations
        $listeUsers = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT id, identifiant, team, pseudo, avatar
                            FROM users
                            WHERE (identifiant != "admin" AND team = "' . $equipe . '" AND status != "I" AND status != "D")
                            OR EXISTS (SELECT id, identifiant, team
                                       FROM cooking_box
                                       WHERE cooking_box.identifiant = users.identifiant AND cooking_box.team = "' . $equipe . '")
                            ORDER BY identifiant ASC');

        while ($data = $req->fetch())
        {
            // Création tableau de correspondance identifiant / pseudo / avatar
            $listeUsers[$data['identifiant']] = array(
                'team'   => $data['team'],
                'pseudo' => $data['pseudo'],
                'avatar' => $data['avatar']
            );
        }

        $req->closeCursor();

        // Retour
        return $listeUsers;
    }

    // PHYSIQUE : Lecture des recettes saisissables d'un utilisateur
    // RETOUR : Liste des semaines par années
    function physiqueSemainesGateauUser($identifiant, $equipe)
    {
        // Initialisations
        $listeSemainesParAnnees = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM cooking_box
                            WHERE identifiant = "' . $identifiant . '" AND team = "' . $equipe . '" AND name = "" AND picture = ""  AND (year < ' . date('Y') . ' OR (year = ' . date('Y') . ' AND week <= ' . date('W') . '))
                            ORDER BY year DESC, week DESC');

        while ($data = $req->fetch())
        {
            // Si l'année n'existe pas on la créé
            if (!isset($listeSemainesParAnnees[$data['year']]))
                $listeSemainesParAnnees[$data['year']] = array();

            // On ajoute la ligne au tableau
            array_push($listeSemainesParAnnees[$data['year']], formatWeekForDisplay($data['week']));
        }

        $req->closeCursor();

        // Retour
        return $listeSemainesParAnnees;
    }

    // PHYSIQUE : Lecture semaine et recette existant
    // RETOUR : Indicateurs semaine et recette existante
    function physiqueSemaineExistante($equipe, $semaine, $annee)
    {
        // Initialisations
        $semaineExistante = array(
            'exist'       => false,
            'identifiant' => '',
            'cooked'      => 'N'
        );

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *, COUNT(*) AS nombreRecettes
                            FROM cooking_box
                            WHERE team = "' . $equipe . '" AND week = "' . $semaine . '" AND year = "' . $annee . '"');

        $data = $req->fetch();

        if ($data['nombreRecettes'] > 0)
        {
            $semaineExistante['exist']       = true;
            $semaineExistante['identifiant'] = $data['identifiant'];
            $semaineExistante['cooked']      = $data['cooked'];
        }

        $req->closeCursor();

        // Retour
        return $semaineExistante;
    }

    // PHYSIQUE : Lecture nombre de lignes existantes pour une année
    // RETOUR : Booléen
    function physiqueAnneeExistante($annee, $equipe)
    {
        // Initialisations
        $anneeExistante = false;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                            FROM cooking_box
                            WHERE team = "' . $equipe . '" AND year = "' . $annee . '" AND name != "" AND picture != ""');

        $data = $req->fetch();

        if ($data['nombreLignes'] > 0)
            $anneeExistante = true;

        $req->closeCursor();

        // Retour
        return $anneeExistante;
    }

    // PHYSIQUE : Lecture des années existantes
    // RETOUR : Liste des années
    function physiqueOnglets($equipe)
    {
        // Initialisations
        $onglets = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT DISTINCT year
                            FROM cooking_box
                            WHERE team = "' . $equipe . '" AND name != "" AND picture != ""
                            ORDER BY year DESC');

        while ($data = $req->fetch())
        {
            // On ajoute la ligne au tableau
            array_push($onglets, $data['year']);
        }

        $req->closeCursor();

        // Retour
        return $onglets;
    }

    // PHYSIQUE : Lecture des recettes
    // RETOUR : Liste des recettes
    function physiqueRecettes($annee, $equipe)
    {
        // Initialisations
        $listeRecettes = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM cooking_box
                            WHERE team = "' . $equipe . '" AND year = "' . $annee . '" AND name != "" AND picture != ""
                            ORDER BY week DESC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet WeekCake à partir des données remontées de la bdd
            $recette = WeekCake::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeRecettes, $recette);
        }

        $req->closeCursor();

        // Retour
        return $listeRecettes;
    }

    // PHYSIQUE : Lecture recette
    // RETOUR : Objet WeekCake
    function physiqueRecette($equipe, $semaine, $annee)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM cooking_box
                            WHERE team = "' . $equipe . '" AND week = "' . $semaine . '" AND year = "' . $annee . '"');

        $data = $req->fetch();

        // Instanciation d'un objet WeekCake à partir des données remontées de la bdd
        $recette = WeekCake::withData($data);

        $req->closeCursor();

        // Retour
        return $recette;
    }

    /****************************************************************************/
    /********************************** INSERT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Insertion nouvelle semaine de gâteau pour un utilisateur
    // RETOUR : Aucun
    function physiqueInsertionSemaineGateau($cooking)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('INSERT INTO cooking_box(identifiant,
                                                      team,
                                                      week,
                                                      year,
                                                      cooked,
                                                      name,
                                                      picture,
                                                      ingredients,
                                                      recipe,
                                                      tips)
                                              VALUES(:identifiant,
                                                     :team,
                                                     :week,
                                                     :year,
                                                     :cooked,
                                                     :name,
                                                     :picture,
                                                     :ingredients,
                                                     :recipe,
                                                     :tips)');

        $req->execute($cooking);

        $req->closeCursor();
    }

    /****************************************************************************/
    /********************************** UPDATE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Mise à jour d'une semaine de gâteau pour un utilisateur
    // RETOUR : Aucun
    function physiqueUpdateSemaineGateau($semaine, $annee, $identifiant, $equipe)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE cooking_box
                              SET identifiant = :identifiant
                              WHERE team = "' . $equipe . '" AND week = "' . $semaine . '" AND year = "' . $annee . '"');

        $req->execute(array(
            'identifiant' => $identifiant
        ));

        $req->closeCursor();
    }

    // PHYSIQUE : Validation d'une semaine de gâteau pour un utilisateur
    // RETOUR : Aucun
    function physiqueUpdateStatusSemaineGateau($equipe, $semaine, $annee, $cooked)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE cooking_box
                              SET cooked = :cooked
                              WHERE team = "' . $equipe . '" AND week = "' . $semaine . '" AND year = "' . $annee . '"');

        $req->execute(array(
            'cooked' => $cooked
        ));

        $req->closeCursor();
    }

    // PHYSIQUE : Mise à jour d'une recette
    // RETOUR : Aucun
    function physiqueUpdateRecette($idRecette, $recette)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE cooking_box
                              SET name        = :name,
                                  picture     = :picture,
                                  ingredients = :ingredients,
                                  recipe      = :recipe,
                                  tips        = :tips
                              WHERE id = ' . $idRecette);

        $req->execute($recette);

        $req->closeCursor();
    }

    // PHYSIQUE : Réinitialisation d'une recette
    // RETOUR : Aucun
    function physiqueResetRecette($semaine, $annee, $reinitialisationRecette)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE cooking_box
                              SET name        = :name,
                                  picture     = :picture,
                                  ingredients = :ingredients,
                                  recipe      = :recipe,
                                  tips        = :tips
                              WHERE week = "' . $semaine . '" AND year = "' . $annee . '"');

        $req->execute($reinitialisationRecette);

        $req->closeCursor();
    }
?>