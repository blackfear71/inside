<?php
    include_once('../../includes/functions/appel_bdd.php');

    /****************************************************************************/
    /********************************** SELECT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Lecture données utilisateurs
    // RETOUR : Aucun
    function physiqueListeUsers($equipe)
    {
        // Initialisations
        $listeUsers = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT DISTINCT bugs.identifiant, users.pseudo, users.avatar
                            FROM bugs
                            LEFT JOIN users ON (bugs.identifiant = users.identifiant AND users.identifiant != "admin" AND users.status != "I")
                            WHERE bugs.team = "' . $equipe . '"
                            ORDER BY bugs.identifiant ASC');

        while ($data = $req->fetch())
        {
            $listeUsers[$data['identifiant']] = array(
                'pseudo' => $data['pseudo'],
                'avatar' => $data['avatar']
            );
        }

        $req->closeCursor();

        // Retour
        return $listeUsers;
    }

    // PHYSIQUE : Lecture liste des rapports
    // RETOUR : Liste rapports
    function physiqueListeRapports($view, $type, $equipe)
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
                                    WHERE type = "' . $type . '" AND team = "' . $equipe . '" AND (resolved = "Y" OR resolved = "R")
                                    ORDER BY date DESC, id DESC');
                break;

            default:
                $req = $bdd->query('SELECT *
                                    FROM bugs
                                    WHERE type = "' . $type . '" AND team = "' . $equipe . '" AND resolved = "N"
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

    /****************************************************************************/
    /********************************** INSERT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Insertion nouveau bug / nouvelle évolution
    // RETOUR : Id bug / évolution culte
    function physiqueInsertionBug($bug)
    {
        // Initialisations
        $newId = NULL;

        // Requête
        global $bdd;

        $req = $bdd->prepare('INSERT INTO bugs(date,
                                               identifiant,
                                               team,
                                               subject,
                                               content,
                                               picture,
                                               resolution,
                                               type,
                                               resolved)
                                       VALUES(:date,
                                              :identifiant,
                                              :team,
                                              :subject,
                                              :content,
                                              :picture,
                                              :resolution,
                                              :type,
                                              :resolved)');

        $req->execute($bug);

        $req->closeCursor();

        $newId = $bdd->lastInsertId();

        // Retour
        return $newId;
    }
?>