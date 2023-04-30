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

        $req = $bdd->query('SELECT teams.*, COUNT(users.id) AS nombreUsers
                            FROM teams
                            LEFT JOIN users on users.team = teams.reference
                            WHERE teams.activation = "Y"
                            GROUP BY teams.reference
                            ORDER BY teams.reference ASC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Team à partir des données remontées de la bdd
            $equipe = Team::withData($data);

            $equipe->setNombre_users($data['nombreUsers']);

            // On ajoute la ligne au tableau
            $listeEquipes[$equipe->getReference()] = $equipe;
        }

        $req->closeCursor();

        // Retour
        return $listeEquipes;
    }

    // PHYSIQUE : Lecture des utilisateurs par équipe
    // RETOUR : Tableau des utilisateurs
    function physiqueUsersParEquipe()
    {
        // Initialisations
        $listeUsersParEquipe = array();

        global $bdd;

        // Requête
        $req = $bdd->query('SELECT users.*, SU1.value AS beginningValue, SU2.value AS developperValue
                            FROM users
                            LEFT JOIN success_users AS SU1 ON (SU1.identifiant = users.identifiant AND SU1.reference = "beginning")
                            LEFT JOIN success_users AS SU2 ON (SU2.identifiant = users.identifiant AND SU2.reference = "developper")
                            WHERE users.identifiant != "admin" AND users.status != "I"
                            ORDER BY users.team ASC, users.identifiant ASC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Profile à partir des données remontées de la bdd
            $user = Profile::withData($data);

            $user->setLevel(convertExperience($user->getExperience()));
            $user->setBeginner($data['beginningValue'] ?? 0);
            $user->setDevelopper($data['developperValue'] ?? 0);

            // Ajout de l'utilisateur à son équipe
            if (!isset($listeUsersParEquipe[$user->getTeam()]))
                $listeUsersParEquipe[$user->getTeam()] = array();

            array_push($listeUsersParEquipe[$user->getTeam()], $user);
        }

        $req->closeCursor();

        // Retour
        return $listeUsersParEquipe;
    }

    // PHYSIQUE : Lecture d'une table pour une équipe
    // RETOUR : Liste des données de la table
    function physiqueLectureTableEquipe($table, $champs, $objet, $equipe)
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
    function physiqueDeleteTableEquipe($table, $equipe)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM ' . $table . '
                           WHERE team = "' . $equipe . '"');
    }

    // PHYSIQUE : Suppression d'un élément d'une table
    // RETOUR : Aucun
    function physiqueDeleteElementTable($table, $colonne, $idElement)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM ' . $table . '
                           WHERE ' . $colonne . ' = ' . $idElement);
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