<?php
    include_once('../../includes/functions/appel_bdd.php');

    /****************************************************************************/
    /********************************** SELECT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Lecture de la liste des équipes activées
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
            $listeEquipes[$equipe->getReference()] = $equipe;
        }

        $req->closeCursor();

        // Retour
        return $listeEquipes;
    }

    // PHYSIQUE : Lecture du nombre d'utilisateurs d'une équipe
    // RETOUR : Nombre d'utilisateurs
    function physiqueNombreUsersEquipe($equipe)
    {
        // Initialisations
        $nombreUsers = 0;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreUsers
                            FROM users
                            WHERE team = "' . $equipe . '"');

        $data = $req->fetch();

        $nombreUsers = $data['nombreUsers'];

        $req->closeCursor();

        // Retour
        return $nombreUsers;
    }

    // PHYSIQUE : Lecture des utilisateurs
    // RETOUR : Tableau des utilisateurs
    function physiqueUsers()
    {
        // Initialisations
        $listeUsers = array();

        global $bdd;

        // Requête
        $req = $bdd->query('SELECT id, identifiant, team, ping, status, pseudo, avatar, email, anniversary, experience
                            FROM users
                            WHERE identifiant != "admin" AND status != "I"
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

    // PHYSIQUE : Lecture des succès administrateur
    // RETOUR : Valeur succès
    function physiqueSuccessAdmin($success, $identifiant)
    {
        // Initialisation
        $value = 0;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *, COUNT(*) AS nombreLignes
                            FROM success_users
                            WHERE reference = "' . $success . '" AND identifiant = "' . $identifiant . '"');

        $data = $req->fetch();

        if ($data['nombreLignes'] > 0)
            $value = $data['value'];

        $req->closeCursor();

        // Retour
        return $value;
    }

    // PHYSIQUE : Lecture d'une table pour une équipe
    // RETOUR : Liste des données de la table
    function physiqueTableEquipe($table, $champs, $objet, $equipe)
    {
        // Initialisations
        $listeElementsTable = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT ' . $champs . '
                            FROM ' . $table . '
                            WHERE team = "' . $equipe . '"');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet $objet à partir des données remontées de la bdd
            $elementTable = $objet::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeElementsTable, $elementTable);
        }

        $req->closeCursor();

        // Retour
        return $listeElementsTable;
    }

    /****************************************************************************/
    /********************************** UPDATE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Mise à jour nom équipe
    // RETOUR : Aucun
    function physiqueUpdateEquipe($reference, $team)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE teams
                              SET team = :team
                              WHERE reference = "' . $reference . '"');

        $req->execute(array(
            'team' => $team
        ));

        $req->closeCursor();
    }

    /****************************************************************************/
    /********************************** DELETE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Suppression des données d'une table pour une équipe
    // RETOUR : Aucun
    function physiqueDeleteTableEquipe($table, $idElement, $equipe)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM ' . $table . '
                           WHERE id = ' . $idElement . ' AND team = "' . $equipe . '"');
    }

    // PHYSIQUE : Suppression des données d'une table pour une équipe (simple)
    // RETOUR : Aucun
    function physiqueDeleteTableEquipeLight($table, $equipe)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM ' . $table . '
                           WHERE team = "' . $equipe . '"');
    }

    // PHYSIQUE : Suppression des votes Collector
    // RETOUR : Aucun
    function physiqueDeleteVotesCollector($idCollector, $equipe)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM collector_users
                           WHERE id_collector = ' . $idCollector . ' AND team = "' . $equipe . '"');
    }

    // PHYSIQUE : Suppression des parts des dépenses
    // RETOUR : Aucun
    function physiqueDeletePartsDepenses($idDepense, $equipe)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM expense_center_users
                           WHERE id_expense = ' . $idDepense . ' AND team = "' . $equipe . '"');
    }

    // PHYSIQUE : Suppression des commentaires film
    // RETOUR : Aucun
    function physiqueDeleteCommentairesFilm($idFilm)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM movie_house_comments
                           WHERE id_film = ' . $idFilm);
    }

    // PHYSIQUE : Suppression des votes film
    // RETOUR : Aucun
    function physiqueDeleteVotesFilm($idFilm)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM movie_house_users
                           WHERE id_film = ' . $idFilm);
    }

    // PHYSIQUE : Suppression d'une équipe
    // RETOUR : Aucun
    function physiqueDeleteEquipe($reference)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM teams
                           WHERE reference = "' . $reference . '"');
    }
?>