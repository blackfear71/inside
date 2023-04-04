<?php
    include_once('../../includes/functions/appel_bdd.php');

    /****************************************************************************/
    /********************************** SELECT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Récupération tableau de bord
    // RETOUR : Objet TableauDeBord
    function physiqueTableauDeBord($identifiant)
    {
        // Initialisations
        $tableauDeBord = new TableauDeBord();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreLignes, SUM(distance) AS totalDistance, SUM(time) AS totalTemps, SUM(speed) AS totalVitesse, SUM(cardio) AS totalCardio
                            FROM petits_pedestres_users
                            WHERE identifiant = "' . $identifiant . '"');

        $data = $req->fetch();

        // Instanciation d'un objet TableauDeBord à partir des données remontées de la bdd
        if ($data['nombreLignes'] > 0)
        {
            $tableauDeBord->setDistanceMoyenne($data['totalDistance'] / $data['nombreLignes']);
            $tableauDeBord->setTempsMoyen($data['totalTemps'] / $data['nombreLignes']);
            $tableauDeBord->setVitesseMoyenne($data['totalVitesse'] / $data['nombreLignes']);
            $tableauDeBord->setCardioMoyen($data['totalCardio'] / $data['nombreLignes']);
        }

        $req->closeCursor();

        // Retour
        return $tableauDeBord;
    }

    // PHYSIQUE : Lecture dernières courses
    // RETOUR : Liste des dernières courses
    function physiqueDernieresCourses($identifiant, $equipe)
    {
        // Initialisations
        $dernieresCourses = array();

        // Requête
        global $bdd;
        
        $req = $bdd->query('SELECT petits_pedestres_users.*, petits_pedestres_parcours.name
                            FROM petits_pedestres_users
                            LEFT JOIN petits_pedestres_parcours ON petits_pedestres_users.id_parcours = petits_pedestres_parcours.id
                            WHERE petits_pedestres_parcours.team = "' . $equipe . '" AND petits_pedestres_parcours.to_delete != "Y" AND petits_pedestres_users.identifiant = "' . $identifiant . '"
                            ORDER BY petits_pedestres_users.date DESC
                            LIMIT 5');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet ParticipationCourse à partir des données remontées de la bdd
            $participation = ParticipationCourse::withData($data);

            $participation->setNom_parcours($data['name']);

            // On ajoute la ligne au tableau
            array_push($dernieresCourses, $participation);
        }

        $req->closeCursor();

        // Retour
        return $dernieresCourses;
    }

    // PHYSIQUE : Lecture parcours
    // RETOUR : Liste des parcours
    function physiqueListeParcours($equipe)
    {
        // Initialisations
        $listeParcours = array();

        // Requête
        global $bdd;
        
        $req = $bdd->query('SELECT *
                            FROM petits_pedestres_parcours
                            WHERE team = "' . $equipe . '" AND to_delete != "Y"
                            ORDER BY location ASC, name ASC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Parcours à partir des données remontées de la bdd
            $parcours = Parcours::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeParcours, $parcours);
        }

        $req->closeCursor();

        // Retour
        return $listeParcours;
    }

    // PHYSIQUE : Lecture participation existante
    // RETOUR : Booléen
    function physiqueParticipationExistante($identifiant, $equipe, $idParcours, $date)
    {
        // Initialisations
        $participationExistante = false;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                            FROM petits_pedestres_users
                            WHERE id_parcours = ' . $idParcours . ' AND date = "' . $date . '" AND identifiant = "' . $identifiant . '" AND team = "' . $equipe . '"');

        $data = $req->fetch();

        if ($data['nombreLignes'] > 0)
            $participationExistante = true;

        $req->closeCursor();

        // Retour
        return $participationExistante;
    }

    // PHYSIQUE : Lecture parcours disponible
    // RETOUR : Booléen
    function physiqueParcoursDisponible($idParcours, $equipe)
    {
        // Initialisations
        $parcoursExistant = false;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreLignes
                            FROM petits_pedestres_parcours
                            WHERE id = ' . $idParcours . ' AND team = "' . $equipe . '"');

        $data = $req->fetch();

        if ($data['nombreLignes'] > 0)
            $parcoursExistant = true;

        $req->closeCursor();

        // Retour
        return $parcoursExistant;
    }

    // PHYSIQUE : Lecture d'un parcours
    // RETOUR : Objet parcours
    function physiqueParcours($idParcours)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT petits_pedestres_parcours.*, COUNT(petits_pedestres_users.id) AS nombreRuns
                            FROM petits_pedestres_parcours
                            JOIN petits_pedestres_users ON petits_pedestres_parcours.id = petits_pedestres_users.id_parcours
                            WHERE petits_pedestres_parcours.id = ' . $idParcours);

        $data = $req->fetch();

        // Instanciation d'un objet Parcours à partir des données remontées de la bdd
        $parcours = Parcours::withData($data);

        $parcours->setRuns($data['nombreRuns']);

        $req->closeCursor();

        // Retour
        return $parcours;
    }

    // PHYSIQUE : Lecture des utilisateurs inscrits
    // RETOUR : Liste des utilisateurs
    function physiqueUsersDetailsParcours($idParcours, $equipe)
    {
        // Initialisations
        $listeUsers = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT id, identifiant, team, pseudo, avatar
                            FROM users
                            WHERE (identifiant != "admin" AND status != "I" AND team = "' . $equipe . '")
                            OR EXISTS (SELECT id, id_parcours, identifiant
                                       FROM petits_pedestres_users
                                       WHERE petits_pedestres_users.identifiant = users.identifiant AND petits_pedestres_users.id_parcours = "' . $idParcours . '")
                            ORDER BY identifiant ASC');

        while ($data = $req->fetch())
        {
            // Création tableau de correspondance identifiant / pseudo / avatar
            $listeUsers[$data['identifiant']] = array(
                'pseudo' => $data['pseudo'],
                'equipe' => $data['team'],
                'avatar' => $data['avatar']
            );
        }

        $req->closeCursor();

        // Retour
        return $listeUsers;
    }

    // PHYSIQUE : Lecture des participations d'un parcours
    // RETOUR : Liste des participations
    function physiqueParticipantsParcours($idParcours)
    {
        // Initialisations
        $listeParticipants = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM petits_pedestres_users
                            WHERE id_parcours = ' . $idParcours . '
                            ORDER BY date DESC, identifiant ASC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet ParticipationCourse à partir des données remontées de la bdd
            $participation = ParticipationCourse::withData($data);

            // On ajoute la ligne au tableau
            if (!isset($listeParticipants[$data['date']]))
                $listeParticipants[$data['date']] = array();

            array_push($listeParticipants[$data['date']], $participation);
        }

        $req->closeCursor();

        // Retour
        return $listeParticipants;
    }

    /****************************************************************************/
    /********************************** INSERT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Insertion nouveau parcours
    // RETOUR : Id parcours
    function physiqueInsertionParcours($parcours)
    {
        // Initialisations
        $newId = NULL;

        // Requête
        global $bdd;

        $req = $bdd->prepare('INSERT INTO petits_pedestres_parcours(to_delete,
                                                                    team,
                                                                    identifiant_add,
                                                                    identifiant_del,
                                                                    name,
                                                                    distance,
                                                                    location,
                                                                    picture,
                                                                    document,
                                                                    type)
                                                            VALUES(:to_delete,
                                                                   :team,
                                                                   :identifiant_add,
                                                                   :identifiant_del,
                                                                   :name,
                                                                   :distance,
                                                                   :location,
                                                                   :picture,
                                                                   :document,
                                                                   :type)');

        $req->execute($parcours);

        $req->closeCursor();

        $newId = $bdd->lastInsertId();

        // Retour
        return $newId;
    }

    // PHYSIQUE : Insertion nouvelle participation
    // RETOUR : Aucun
    function physiqueInsertionParticipation($participation)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('INSERT INTO petits_pedestres_users(id_parcours,
                                                                 identifiant,
                                                                 team,
                                                                 date,
                                                                 distance,
                                                                 time,
                                                                 speed,
                                                                 cardio,
                                                                 competition)
                                                         VALUES(:id_parcours,
                                                                :identifiant,
                                                                :team,
                                                                :date,
                                                                :distance,
                                                                :time,
                                                                :speed,
                                                                :cardio,
                                                                :competition)');

        $req->execute($participation);

        $req->closeCursor();
    }

    /****************************************************************************/
    /********************************** UPDATE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Mise à jour parcours
    // RETOUR : Aucun
    function physiqueUpdateParcours($idParcours, $parcours)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE petits_pedestres_parcours
                              SET name     = :name,
                                  distance = :distance,
                                  location = :location,
                                  picture  = :picture,
                                  document = :document,
                                  type     = :type
                              WHERE id = ' . $idParcours);

        $req->execute($parcours);

        $req->closeCursor();
    }

    // PHYSIQUE : Mise à jour du statut du parcours
    // RETOUR : Aucun
    function physiqueUpdateStatusParcours($idParcours, $toDelete, $identifiant)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE petits_pedestres_parcours
                              SET to_delete       = :to_delete,
                                  identifiant_del = :identifiant_del
                              WHERE id = ' . $idParcours);

        $req->execute(array(
            'to_delete'       => $toDelete,
            'identifiant_del' => $identifiant
        ));

        $req->closeCursor();
    }














    /****************************************************************************/
    /********************************** DELETE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Suppression
    // RETOUR : Aucun
    function physiqueDelete($id)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM table
                           WHERE id = ' . $id);
    }




    


    
?>