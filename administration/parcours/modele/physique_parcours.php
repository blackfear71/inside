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

    // PHYSIQUE : Lecture liste des parcours à supprimer
    // RETOUR : Liste des parcours à supprimer
    function physiqueParcoursToDelete()
    {
        // Initialisations
        $listeParcoursToDelete = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT petits_pedestres_parcours.id, petits_pedestres_parcours.to_delete, petits_pedestres_parcours.team, petits_pedestres_parcours.identifiant_add, petits_pedestres_parcours.identifiant_del, petits_pedestres_parcours.name, U1.pseudo AS pseudo_add, U2.pseudo AS pseudo_del
                            FROM petits_pedestres_parcours
                            LEFT JOIN users AS U1 ON petits_pedestres_parcours.identifiant_add = U1.identifiant
                            LEFT JOIN users AS U2 ON petits_pedestres_parcours.identifiant_del = U2.identifiant
                            WHERE petits_pedestres_parcours.to_delete = "Y"
                            ORDER BY petits_pedestres_parcours.id ASC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Parcours à partir des données remontées de la bdd
            $parcours = Parcours::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeParcoursToDelete, $parcours);
        }

        $req->closeCursor();

        // Retour
        return $listeParcoursToDelete;
    }

    // PHYSIQUE : Comptage du nombre de participants
    // RETOUR : Nombre de participants
    function physiqueNombreParticipants($idParcours)
    {
        // Initialisations
        $count = 0;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreUsers
                            FROM petits_pedestres_users
                            WHERE id_parcours = ' . $idParcours);

        $data = $req->fetch();

        $count = $data['nombreUsers'];

        $req->closeCursor();

        // Retour
        return $count;
    }

    // PHYSIQUE : Lecture données parcours
    // RETOUR : Objet Parcours
    function physiqueDonneesParcours($idParcours)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM petits_pedestres_parcours
                            WHERE id = ' . $idParcours);

        $data = $req->fetch();

        // Instanciation d'un objet Parcours à partir des données remontées de la bdd
        $parcours = Parcours::withData($data);

        $req->closeCursor();

        // Retour
        return $parcours;
    }

    // PHYSIQUE : Lecture participations parcours
    // RETOUR : Liste des participations
    function physiqueParticipationsParcours($idParcours)
    {
        // Initialisations
        $listeParticipations = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM petits_pedestres_users
                            WHERE id_parcours = ' . $idParcours . '
                            ORDER BY identifiant ASC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet ParticipationCourse à partir des données remontées de la bdd
            $participation = ParticipationCourse::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeParticipations, $participation);
        }
                    
        $req->closeCursor();

        // Retour
        return $listeParticipations;
    }

    /****************************************************************************/
    /********************************** UPDATE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Mise à jour statut parcours
    // RETOUR : Aucun
    function physiqueResetParcours($idParcours, $toDelete, $identifiantDel)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE petits_pedestres_parcours
                              SET to_delete = :to_delete,
                                  identifiant_del = :identifiant_del
                              WHERE id = ' . $idParcours);

        $req->execute(array(
            'to_delete'       => $toDelete,
            'identifiant_del' => $identifiantDel
        ));

        $req->closeCursor();
    }

    /****************************************************************************/
    /********************************** DELETE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Suppression participations parcours
    // RETOUR : Aucun
    function physiqueDeleteParticipationsParcours($idParcours)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM petits_pedestres_users
                           WHERE id_parcours = ' . $idParcours);
    }

    // PHYSIQUE : Suppression parcours
    // RETOUR : Aucun
    function physiqueDeleteParcours($idParcours)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM petits_pedestres_parcours
                           WHERE id = ' . $idParcours);
    }
?>