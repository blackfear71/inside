<?php
    include_once('../../includes/functions/appel_bdd.php');

    /****************************************************************************/
    /********************************** SELECT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Lecture liste des missions
    // RETOUR : Liste missions
    function physiqueListeMissions()
    {
        // Initialisations
        $listeMissions = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM missions');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Mission à partir des données remontées de la bdd
            $mission = Mission::withData($data);

            // Assignation du statut en fonction de la date
            if (date('Ymd') < $mission->getDate_deb() OR (date('Ymd') == $mission->getDate_deb() AND date('His') < $mission->getHeure()))
                $mission->setStatut('V');
            elseif (((date('Ymd') == $mission->getDate_deb() AND date('His') >= $mission->getHeure()) OR date('Ymd') > $mission->getDate_deb()) AND date('Ymd') <= $mission->getDate_fin())
                $mission->setStatut('C');
            elseif (date('Ymd') > $mission->getDate_fin())
                $mission->setStatut('A');

            // On ajoute la ligne au tableau
            array_push($listeMissions, $mission);
        }

        $req->closeCursor();

        // Retour
        return $listeMissions;
    }

    // PHYSIQUE : Lecture des détails d'une mission
    // RETOUR : Objet mission
    function physiqueMission($idMission)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM missions
                            WHERE id = ' . $idMission);

        $data = $req->fetch();

        // Instanciation d'un objet Mission à partir des données remontées de la bdd
        $mission = Mission::withData($data);

        $req->closeCursor();

        // Retour
        return $mission;
    }

    // PHYSIQUE : Lecture des participants d'une mission
    // RETOUR : Liste des utilisateurs
    function physiqueUsersMission($idMission)
    {
        // Initialisations
        $listeUsersParEquipes = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT missions_users.id, missions_users.team, missions_users.identifiant, users.pseudo, users.avatar, SUM(missions_users.avancement) AS total
                            FROM missions_users
                            LEFT JOIN users ON users.identifiant = missions_users.identifiant
                            WHERE missions_users.id_mission = ' . $idMission . '
                            GROUP BY missions_users.identifiant
                            ORDER BY missions_users.team ASC, missions_users.identifiant ASC');

        while ($data = $req->fetch())
        {
            // Récupération des identifiants
            $user = new ParticipantMission();

            $user->setIdentifiant($data['identifiant']);
            $user->setTeam($data['team']);
            $user->setPseudo($data['pseudo']);
            $user->setAvatar($data['avatar']);
            $user->setTotal($data['total']);

            // On ajoute la ligne au tableau
            if (!isset($listeUsersParEquipes[$user->getTeam()]))
                $listeUsersParEquipes[$user->getTeam()] = array();

            array_push($listeUsersParEquipes[$user->getTeam()], $user);
        }

        $req->closeCursor();

        // Retour
        return $listeUsersParEquipes;
    }

    // PHYSIQUE : Lecture du nombre de références existantes
    // RETOUR : Booléen
    function physiqueReferenceUnique($reference)
    {
        // Initialisations
        $isUnique = true;

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT COUNT(*) AS nombreReferences
                            FROM missions
                            WHERE reference = "' . $reference . '"');

        $data = $req->fetch();

        if ($data['nombreReferences'] > 0)
            $isUnique = false;

        $req->closeCursor();

        // Retour
        return $isUnique;
    }

    // PHYSIQUE : Lecture des équipes dont les utilisateurs participent à une mission
    // RETOUR : Liste des équipe
    function physiqueEquipesMission($idMission)
    {
        // Initialisations
        $listeEquipes = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT DISTINCT missions_users.team, teams.*
                            FROM missions_users
                            LEFT JOIN teams ON teams.reference = missions_users.team
                            WHERE missions_users.id_mission = ' . $idMission . '
                            ORDER BY missions_users.team ASC');

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

    /****************************************************************************/
    /********************************** INSERT **********************************/
    /****************************************************************************/
    // PHYSIQUE : Insertion nouvelle mission
    // RETOUR : Aucun
    function physiqueInsertionMission($mission)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('INSERT INTO missions(mission,
                                                   reference,
                                                   date_deb,
                                                   date_fin,
                                                   heure,
                                                   objectif,
                                                   description,
                                                   explications,
                                                   conclusion)
                                           VALUES(:mission,
                                                  :reference,
                                                  :date_deb,
                                                  :date_fin,
                                                  :heure,
                                                  :objectif,
                                                  :description,
                                                  :explications,
                                                  :conclusion)');

        $req->execute($mission);

        $req->closeCursor();
    }

    /****************************************************************************/
    /********************************** UPDATE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Mise à jour mission existante
    // RETOUR : Aucun
    function physiqueUpdateMission($idMission, $mission)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE missions
                              SET mission      = :mission,
                                  date_deb     = :date_deb,
                                  date_fin     = :date_fin,
                                  heure        = :heure,
                                  objectif     = :objectif,
                                  description  = :description,
                                  explications = :explications,
                                  conclusion   = :conclusion
                              WHERE id = ' . $idMission);

        $req->execute($mission);

        $req->closeCursor();
    }

    // PHYSIQUE : Mise à jour succès lié
    // RETOUR : Aucun
    function physiqueUpdateSuccesMission($referenceMission)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE success
                              SET mission = :mission
                              WHERE mission = "' . $referenceMission . '"');

        $req->execute(array(
            'mission' => ''
        ));

        $req->closeCursor();
    }

    /****************************************************************************/
    /********************************** DELETE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Suppression mission
    // RETOUR : Aucun
    function physiqueDeleteMission($idMission)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM missions
                           WHERE id = ' . $idMission);
    }

    // PHYSIQUE : Suppression participations mission
    // RETOUR : Aucun
    function physiqueDeleteMissionUsers($idMission)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM missions_users
                           WHERE id_mission = ' . $idMission);
    }
?>