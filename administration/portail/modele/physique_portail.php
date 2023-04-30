<?php
    include_once('../../includes/functions/appel_bdd.php');

    /****************************************************************************/
    /********************************** SELECT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Lecture alerte équipes
    // RETOUR : Booléen
    function physiqueAlerteEquipes()
    {
        // Initialisations
        $alert = false;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(teams.id) AS nombreEquipes
                            FROM teams
                            LEFT JOIN users ON users.team = teams.reference
                            WHERE teams.activation = "Y" AND users.id IS NULL');

        $data = $req->fetch();

        if ($data['nombreEquipes'] > 0)
            $alert = true;

        $req->closeCursor();

        // Retour
        return $alert;
    }

    // PHYSIQUE : Lecture alerte utilisateurs
    // RETOUR : Booléen
    function physiqueAlerteUsers()
    {
        // Initialisations
        $alert = false;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreStatusUsers
                            FROM users
                            WHERE identifiant != "admin" AND (status = "P" OR status = "I" OR status = "D" OR status = "T")
                            ORDER BY identifiant ASC');

        $data = $req->fetch();

        if ($data['nombreStatusUsers'] > 0)
            $alert = true;

        $req->closeCursor();

        // Retour
        return $alert;
    }

    // PHYSIQUE : Lecture alerte suppression
    // RETOUR : Booléen
    function physiqueAlerteSuppression($table)
    {
        // Initialisations
        $alert = false;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreSuppressions
                            FROM ' . $table . '
                            WHERE to_delete = "Y"');

        $data = $req->fetch();

        if ($data['nombreSuppressions'] > 0)
            $alert = true;

        $req->closeCursor();

        // Retour
        return $alert;
    }

    // PHYSIQUE : Lecture du nombre de bugs ou évolutions en cours
    // RETOUR : Nombre de demandes
    function physiqueNombreBugsEvolutions($type)
    {
        // Initialisations
        $nombreBugsEvolutions = 0;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                            FROM bugs
                            WHERE type = "' . $type . '" AND resolved = "N"');

        $data = $req->fetch();

        if ($data['nombreLignes'] > 0)
            $nombreBugsEvolutions = $data['nombreLignes'];

        $req->closeCursor();

        // Retour
        return $nombreBugsEvolutions;
    }
?>