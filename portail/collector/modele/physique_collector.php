<?php
    include_once('../../includes/functions/appel_bdd.php');

    /****************************************************************************/
    /********************************** SELECT **********************************/
    /****************************************************************************/
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
                            WHERE (identifiant != "admin" AND team = "' . $equipe . '" AND status != "I")
                            OR EXISTS (SELECT id, author, speaker, team
                                       FROM collector
                                       WHERE (collector.author = users.identifiant OR collector.speaker = users.identifiant) AND collector.team = "' . $equipe . '" AND collector.type_speaker != "other")
                            OR EXISTS (SELECT id, team, identifiant
                                       FROM collector_users
                                       WHERE collector_users.identifiant = users.identifiant AND collector_users.team = "' . $equipe . '")
                            ORDER BY identifiant ASC');

        while ($data = $req->fetch())
        {
            // Création tableau de correspondance identifiant / équipe / pseudo / avatar
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

    // PHYSIQUE : Lecture du nombre de phrases cultes en fonction du filtre
    // RETOUR : Nombre de phrases cultes
    function physiqueNombreCollector($filtre, $identifiant, $equipe, $minGolden)
    {
        // Initialisations
        $nombreCollectors = 0;

        // Requête
        global $bdd;

        switch ($filtre)
        {
            case 'noVote':
                $req = $bdd->query('SELECT COUNT(*) AS nombreCollector
                                    FROM collector
                                    WHERE team = "' . $equipe . '" AND NOT EXISTS (SELECT id, id_collector, identifiant
                                                                                   FROM collector_users
                                                                                   WHERE (collector.id = collector_users.id_collector AND collector_users.identifiant = "' . $identifiant . '"))');
                break;

            case 'meOnly':
                $req = $bdd->query('SELECT COUNT(*) AS nombreCollector
                                    FROM collector
                                    WHERE team = "' . $equipe . '" AND speaker = "' . $identifiant . '"');
                break;

            case 'byMe':
                $req = $bdd->query('SELECT COUNT(*) AS nombreCollector
                                    FROM collector
                                    WHERE team = "' . $equipe . '" AND author = "' . $identifiant . '"');
                break;

            case 'usersOnly':
                $req = $bdd->query('SELECT COUNT(*) AS nombreCollector
                                    FROM collector
                                    WHERE team = "' . $equipe . '" AND type_speaker = "user" AND speaker != "' . $identifiant . '"');
                break;

            case 'othersOnly':
                $req = $bdd->query('SELECT COUNT(*) AS nombreCollector
                                    FROM collector
                                    WHERE team = "' . $equipe . '" AND type_speaker = "other"');
                break;

            case 'textOnly':
                $req = $bdd->query('SELECT COUNT(*) AS nombreCollector
                                    FROM collector
                                    WHERE team = "' . $equipe . '" AND type_collector = "T"');
                break;

            case 'picturesOnly':
                $req = $bdd->query('SELECT COUNT(*) AS nombreCollector
                                    FROM collector
                                    WHERE team = "' . $equipe . '" AND type_collector = "I"');
                break;

            case 'topCulte':
                $req = $bdd->query('SELECT COUNT(*) AS nombreCollector
                                    FROM collector
                                    WHERE team = "' . $equipe . '" AND (SELECT COUNT(*)
                                                                        FROM collector_users
                                                                        WHERE collector_users.id_collector = collector.id) >= ' . $minGolden);
                break;

            case 'none':
            default:
                $req = $bdd->query('SELECT COUNT(*) AS nombreCollector
                                    FROM collector
                                    WHERE team = "' . $equipe . '"');
                break;
        }

        $data = $req->fetch();

        if ($data['nombreCollector'] > 0)
            $nombreCollectors = $data['nombreCollector'];

        $req->closeCursor();

        // Retour
        return $nombreCollectors;
    }

    // PHYSIQUE : Lecture des phrases cultes en fonction du filtre et du tri
    // RETOUR : Liste des phrases cultes
    function physiqueCollectors($tri, $filtre, $nombreParPage, $premiereEntree, $identifiant, $equipe, $minGolden)
    {
        // Initialisations
        $listeCollectors = array();

        // Détermination du tri
        switch ($tri)
        {
            case 'dateAsc':
                $order = 'collector.date_collector ASC, collector.id ASC';
                break;

            case 'dateDesc':
            default:
                $order = 'collector.date_collector DESC, collector.id DESC';
                break;
        }

        // Requête
        global $bdd;

        switch ($filtre)
        {
            case 'noVote':
                $req = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nombreVotes
                                    FROM collector
                                    LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                    WHERE collector.team = "' . $equipe . '" AND NOT EXISTS (SELECT collector_users.id, collector_users.id_collector, collector_users.identifiant
                                                                                             FROM collector_users
                                                                                             WHERE (collector.id = collector_users.id_collector AND collector_users.identifiant = "' . $identifiant . '"))
                                    GROUP BY collector.id
                                    ORDER BY ' . $order . '
                                    LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
                break;

            case 'meOnly':
                $req = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nombreVotes
                                    FROM collector
                                    LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                    WHERE collector.team = "' . $equipe . '" AND collector.speaker = "' . $identifiant . '"
                                    GROUP BY collector.id
                                    ORDER BY ' . $order . '
                                    LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
                break;

            case 'byMe':
                $req = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nombreVotes
                                    FROM collector
                                    LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                    WHERE collector.team = "' . $equipe . '" AND collector.author = "' . $identifiant . '"
                                    GROUP BY collector.id
                                    ORDER BY ' . $order . '
                                    LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
                break;

            case 'usersOnly':
                $req = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nombreVotes
                                    FROM collector
                                    LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                    WHERE collector.team = "' . $equipe . '" AND collector.type_speaker = "user" AND collector.speaker != "' . $identifiant . '"
                                    GROUP BY collector.id
                                    ORDER BY ' . $order . '
                                    LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
                break;

            case 'othersOnly':
                $req = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nombreVotes
                                    FROM collector
                                    LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                    WHERE collector.team = "' . $equipe . '" AND collector.type_speaker = "other"
                                    GROUP BY collector.id
                                    ORDER BY ' . $order . '
                                    LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
                break;

            case 'textOnly':
                $req = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nombreVotes
                                    FROM collector
                                    LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                    WHERE collector.team = "' . $equipe . '" AND collector.type_collector = "T"
                                    GROUP BY collector.id
                                    ORDER BY ' . $order . '
                                    LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
                break;

            case 'picturesOnly':
                $req = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nombreVotes
                                    FROM collector
                                    LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                    WHERE collector.team = "' . $equipe . '" AND collector.type_collector = "I"
                                    GROUP BY collector.id
                                    ORDER BY ' . $order . '
                                    LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
                break;

            case 'topCulte':
                $req = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nombreVotes
                                    FROM collector
                                    LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                    WHERE collector.team = "' . $equipe . '" AND (SELECT COUNT(collector_users.id)
                                                                                  FROM collector_users
                                                                                  WHERE collector_users.id_collector = collector.id) >= ' . $minGolden . '
                                    GROUP BY collector.id
                                    ORDER BY ' . $order . '
                                    LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
                break;

            case 'none':
            default:
                $req = $bdd->query('SELECT collector.*, COUNT(collector_users.id) AS nombreVotes
                                    FROM collector
                                    LEFT JOIN collector_users ON collector.id = collector_users.id_collector
                                    WHERE collector.team = "' . $equipe . '"
                                    GROUP BY collector.id
                                    ORDER BY ' . $order . '
                                    LIMIT ' . $premiereEntree . ', ' . $nombreParPage);
                break;
        }

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Collector à partir des données remontées de la bdd
            $collector = Collector::withData($data);

            // Nombre de votes
            $collector->setNb_votes($data['nombreVotes']);

            // On ajoute la ligne au tableau
            array_push($listeCollectors, $collector);
        }

        $req->closeCursor();

        // Retour
        return $listeCollectors;
    }

    // PHYSIQUE : Lecture vote utilisateur connecté
    // RETOUR : Vote utilisateur
    function physiqueVoteUser($idCollector, $identifiant)
    {
        // Initialisations
        $vote = array(
            'id_vote' => '',
            'vote'    => 0
        );

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *, COUNT(*) AS nombreVote
                            FROM collector_users
                            WHERE id_collector = ' . $idCollector . ' AND identifiant = "' . $identifiant . '"');

        $data = $req->fetch();

        if ($data['nombreVote'] > 0)
        {
            $vote = array(
                'id_vote' => $data['id'],
                'vote'    => $data['vote']
            );
        }

        $req->closeCursor();

        // Retour
        return $vote;
    }

    // PHYSIQUE : Lecture votes tous utilisateurs
    // RETOUR : Votes utilisateurs
    function physiqueVotesUsers($idCollector, $listeUsers)
    {
        // Initialisations
        $listeVotes = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM collector_users
                            WHERE id_collector = ' . $idCollector . '
                            ORDER BY vote ASC, identifiant ASC');

        while ($data = $req->fetch())
        {
            // Récupération pseudo
            if (isset($listeUsers[$data['identifiant']]))
                $pseudo = $listeUsers[$data['identifiant']]['pseudo'];
            else
                $pseudo = '';

            // Si le vote n'existe pas dans le tableau, on créé une nouvelle entrée
            if (!isset($listeVotes[$data['vote']]))
                $listeVotes[$data['vote']] = array();

            // On ajoute la ligne au tableau
            array_push($listeVotes[$data['vote']], $pseudo);
        }

        $req->closeCursor();

        // Retour
        return $listeVotes;
    }

    // PHYSIQUE : Lecture phrase / image culte
    // RETOUR : Objet Collector
    function physiqueCollector($idCollector)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM collector
                            WHERE id = ' . $idCollector);

        $data = $req->fetch();

        // Instanciation d'un objet Collector à partir des données remontées de la bdd
        $collector = Collector::withData($data);

        $req->closeCursor();

        // Retour
        return $collector;
    }

    // PHYSIQUE : Lecture phrase / image culte pour l'utilisateur
    // RETOUR : Booléen
    function physiqueCollectorUser($idCollector, $identifiant)
    {
        // Initialisations
        $selfSatisfied = false;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreCollector
                            FROM collector
                            WHERE id = ' . $idCollector . ' AND speaker = "' . $identifiant . '"');

        $data = $req->fetch();

        if ($data['nombreCollector'] > 0)
            $selfSatisfied = true;

        $req->closeCursor();

        // Retour
        return $selfSatisfied;
    }

    // PHYSIQUE : Lecture position phrase / image culte dans la table en fonction du filtre
    // RETOUR : Position
    function physiquePositionCollector($idCollector, $identifiant, $equipe, $tri, $filtre, $minGolden)
    {
        // Initialisations
        $position = 1;

        // Détermination du tri
        switch ($tri)
        {
            case 'dateAsc':
                $order = 'collector.date_collector ASC, collector.id ASC';
                break;

            case 'dateDesc':
            default:
                $order = 'collector.date_collector DESC, collector.id DESC';
                break;
        }
        
        // Requête
        global $bdd;

        switch ($filtre)
        {
            case 'noVote':
                $req = $bdd->query('SELECT id, date_collector
                                    FROM collector
                                    WHERE team = "' . $equipe . '" AND NOT EXISTS (SELECT id, id_collector, identifiant
                                                                                   FROM collector_users
                                                                                   WHERE (collector.id = collector_users.id_collector AND collector_users.identifiant = "' . $identifiant . '"))
                                    ORDER BY ' . $order);
                break;

            case 'meOnly':
                $req = $bdd->query('SELECT id, date_collector
                                    FROM collector
                                    WHERE team = "' . $equipe . '" AND speaker = "' . $identifiant . '"
                                    ORDER BY ' . $order);
                break;

            case 'byMe':
                $req = $bdd->query('SELECT id, date_collector
                                    FROM collector
                                    WHERE team = "' . $equipe . '" AND author = "' . $identifiant . '"
                                    ORDER BY ' . $order);
                break;

            case 'usersOnly':
                $req = $bdd->query('SELECT id, date_collector
                                    FROM collector
                                    WHERE team = "' . $equipe . '" AND type_speaker = "user" AND speaker != "' . $identifiant . '"
                                    ORDER BY ' . $order);
                break;

            case 'othersOnly':
                $req = $bdd->query('SELECT id, date_collector
                                    FROM collector
                                    WHERE team = "' . $equipe . '" AND type_speaker = "other"
                                    ORDER BY ' . $order);
                break;

            case 'textOnly':
                $req = $bdd->query('SELECT id, date_collector
                                    FROM collector
                                    WHERE team = "' . $equipe . '" AND type_collector = "T"
                                    ORDER BY ' . $order);
                break;

            case 'picturesOnly':
                $req = $bdd->query('SELECT id, date_collector
                                    FROM collector
                                    WHERE team = "' . $equipe . '" AND type_collector = "I"
                                    ORDER BY ' . $order);
                break;

            case 'topCulte':
                $req = $bdd->query('SELECT id, date_collector
                                    FROM collector
                                    WHERE team = "' . $equipe . '" AND (SELECT COUNT(*)
                                                                        FROM collector_users
                                                                        WHERE collector_users.id_collector = collector.id) >= ' . $minGolden . '
                                    ORDER BY ' . $order);
                break;

            case 'none':
            default:
                $req = $bdd->query('SELECT id, date_collector
                                    FROM collector
                                    WHERE team = "' . $equipe . '"
                                    ORDER BY ' . $order);
                break;
        }

        while ($data = $req->fetch())
        {
            if ($idCollector == $data['id'])
                break;
            else
                $position++;
        }

        $req->closeCursor();

        // Retour
        return $position;
    }

    // PHYSIQUE : Lecture du vote d'un utilisateur ayant un vote sur une phrase / image culte
    // RETOUR : Booléen
    function physiqueVoteCollectorUser($idCollector, $identifiant)
    {
        // Initialisations
        $selfSatisfied = false;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreVote
                            FROM collector_users
                            WHERE id_collector = ' . $idCollector . ' AND identifiant = "' . $identifiant . '"');

        $data = $req->fetch();

        if ($data['nombreVote'] > 0)
            $selfSatisfied = true;

        // Retour
        return $selfSatisfied;
    }

    // PHYSIQUE : Lecture des utilisateurs ayant un vote sur une phrase / image culte
    // RETOUR : Liste des utilisateurs
    function physiqueVotesCollector($idCollector)
    {
        // Initialisations
        $listeUsersVotes = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM collector_users
                            WHERE id_collector = ' . $idCollector . '
                            ORDER BY identifiant ASC');

        while ($data = $req->fetch())
        {
            // On ajoute la ligne au tableau
            array_push($listeUsersVotes, $data['identifiant']);
        }

        $req->closeCursor();

        // Retour
        return $listeUsersVotes;
    }

    /****************************************************************************/
    /********************************** INSERT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Insertion nouvelle phrase / image culte
    // RETOUR : Id phrase / image culte
    function physiqueInsertionCollector($collector)
    {
        // Initialisations
        $newId = NULL;

        // Requête
        global $bdd;

        $req = $bdd->prepare('INSERT INTO collector(date_add,
                                                    author,
                                                    speaker,
                                                    type_speaker,
                                                    date_collector,
                                                    type_collector,
                                                    team,
                                                    collector,
                                                    context)
                                            VALUES(:date_add,
                                                   :author,
                                                   :speaker,
                                                   :type_speaker,
                                                   :date_collector,
                                                   :type_collector,
                                                   :team,
                                                   :collector,
                                                   :context)');

        $req->execute($collector);

        $req->closeCursor();

        $newId = $bdd->lastInsertId();

        // Retour
        return $newId;
    }

    // PHYSIQUE : Insertion vote
    // RETOUR : Aucun
    function physiqueInsertionVote($vote)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('INSERT INTO collector_users(id_collector,
                                                          team,
                                                          identifiant,
                                                          vote)
                                                  VALUES(:id_collector,
                                                         :team,
                                                         :identifiant,
                                                         :vote)');

        $req->execute($vote);

        $req->closeCursor();
    }

    /****************************************************************************/
    /********************************** UPDATE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Mise à jour phrase / image culte
    // RETOUR : Aucun
    function physiqueUpdateCollector($idCollector, $collector)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE collector
                              SET speaker        = :speaker,
                                  type_speaker   = :type_speaker,
                                  date_collector = :date_collector,
                                  collector      = :collector,
                                  context        = :context
                              WHERE id = ' . $idCollector);

        $req->execute($collector);

        $req->closeCursor();
    }

    // PHYSIQUE : Mise à jour vote
    // RETOUR : Aucun
    function physiqueUpdateVote($idVote, $vote)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE collector_users
                              SET vote = :vote
                              WHERE id = ' . $idVote);

        $req->execute(array(
            'vote' => $vote
        ));

        $req->closeCursor();
    }

    /****************************************************************************/
    /********************************** DELETE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Suppression d'une phrase / image culte
    // RETOUR : Aucun
    function physiqueDeleteCollector($idCollector)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM collector
                           WHERE id = ' . $idCollector);
    }

    // PHYSIQUE : Suppression d'un vote
    // RETOUR : Aucun
    function physiqueDeleteVote($idVote)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM collector_users
                           WHERE id = ' . $idVote);
    }

    // PHYSIQUE : Suppression d'un vote
    // RETOUR : Aucun
    function physiqueDeleteVotes($idCollector)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM collector_users
                           WHERE id_collector = ' . $idCollector);
    }
?>