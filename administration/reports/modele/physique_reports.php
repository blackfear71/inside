<?php
    include_once('../../includes/functions/appel_bdd.php');

    /****************************************************************************/
    /********************************** SELECT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Lecture données utilisateurs
    // RETOUR : Aucun
    function physiqueListeUsers()
    {
        // Initialisations
        $listeUsers = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT identifiant, pseudo, avatar
                            FROM users');

        $data = $req->fetch();

        while ($data = $req->fetch())
        {
            $listeUsers[$data['identifiant']] = array(
                'pseudo' => $data['pseudo'],
                'avatar' => $data['avatar'],
            );
        }

        $req->closeCursor();

        // Retour
        return $listeUsers;
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
                            ORDER BY reference ASC');

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

    // PHYSIQUE : Lecture liste des rapports
    // RETOUR : Liste rapports
    function physiqueListeRapports($view, $type)
    {
        // Initialisations
        $rapports = array();

        // Requête
        global $bdd;

        switch ($view)
        {
            case 'resolved':
                $req = $bdd->query('SELECT *
                                    FROM bugs
                                    WHERE type = "' . $type . '" AND (resolved = "Y" OR resolved = "R")
                                    ORDER BY date DESC, id DESC');
                break;

            case 'unresolved':
                $req = $bdd->query('SELECT *
                                    FROM bugs
                                    WHERE type = "' . $type . '" AND resolved = "N"
                                    ORDER BY date DESC, id DESC');
                break;

            default:
                $req = $bdd->query('SELECT *
                                    FROM bugs
                                    WHERE type = "' . $type . '"
                                    ORDER BY date DESC, id DESC');
        }

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet BugEvolution à partir des données remontées de la bdd
            $rapport = BugEvolution::withData($data);

            // On ajoute la ligne au tableau
            array_push($rapports, $rapport);
        }

        $req->closeCursor();

        // Retour
        return $rapports;
    }

    // PHYSIQUE : Lecture données rapport
    // RETOUR : Objet bugs
    function physiqueRapport($idRapport)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM bugs
                            WHERE id = ' . $idRapport);

        $data = $req->fetch();

        // Instanciation d'un objet BugEvolution à partir des données remontées de la bdd
        $rapport = BugEvolution::withData($data);

        $req->closeCursor();

        // Retour
        return $rapport;
    }

    /****************************************************************************/
    /********************************** UPDATE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Mise à jour statut rapport
    // RETOUR : Aucun
    function physiqueUpdateRapport($idRapport, $resolved)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE bugs
                              SET resolved = :resolved
                              WHERE id = ' . $idRapport);

        $req->execute(array(
            'resolved' => $resolved
        ));

        $req->closeCursor();
    }

    /****************************************************************************/
    /********************************** DELETE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Suppression rapport
    // RETOUR : Aucun
    function physiqueDeleteRapport($idRapport)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM bugs
                           WHERE id = ' . $idRapport);
    }
?>