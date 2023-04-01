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
                            ORDER BY name ASC, location ASC');

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

    /****************************************************************************/
    /********************************** INSERT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Insertion nouveau parcours
    // RETOUR : Aucun
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









    /****************************************************************************/
    /********************************** UPDATE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Mise à jour
    // RETOUR : Aucun
    function physiqueUpdate($champ1, $champ2, $id)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE table
                              SET champ1 = :champ1,
                                  champ2 = :champ2
                              WHERE id = ' . $id);

        $req->execute(array(
            'champ1' => $champ1,
            'champ2' => $champ2
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