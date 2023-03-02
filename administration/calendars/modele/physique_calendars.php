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

    // PHYSIQUE : Lecture préférences utilisateurs
    // RETOUR : Préférences utilisateurs
    function physiqueAutorisationsCalendars()
    {
        // Initialisations
        $listeAutorisations = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM preferences
                            ORDER BY identifiant ASC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet AutorisationCalendriers à partir des données remontées de la bdd
            $autorisation = AutorisationCalendriers::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeAutorisations, $autorisation);
        }

        $req->closeCursor();

        // Retour
        return $listeAutorisations;
    }

    // PHYSIQUE : Lecture des informations utilisateur
    // RETOUR : Données utilisateur
    function physiqueDonneesUser($identifiant)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT id, identifiant, team, pseudo
                            FROM users
                            WHERE identifiant = "' . $identifiant . '"');

        $data = $req->fetch();

        $user = array(
            'pseudo' => $data['pseudo'],
            'team'   => $data['team']
        );

        $req->closeCursor();

        // Retour
        return $user;
    }

    // PHYSIQUE : Lecture des utilisateurs
    // RETOUR : Liste des utilisateurs
    function physiqueUsers()
    {
        // Initialisations
        $listeUsers = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT id, identifiant
                            FROM users
                            WHERE identifiant != "admin"
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

    // PHYSIQUE : Lecture liste des calendriers à supprimer
    // RETOUR : Liste des calendriers
    function physiqueCalendarsToDelete($listeMois)
    {
        // Initialisations
        $listeCalendarsToDelete = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM calendars
                            WHERE to_delete = "Y"
                            ORDER BY year DESC, month DESC, id DESC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Calendrier à partir des données remontées de la bdd
            $calendar = Calendrier::withData($data);

            // Titre du calendrier
            $calendar->setTitle($listeMois[$calendar->getMonth()] . ' ' . $calendar->getYear());

            // On ajoute la ligne au tableau
            array_push($listeCalendarsToDelete, $calendar);
        }

        $req->closeCursor();

        // Retour
        return $listeCalendarsToDelete;
    }

    // PHYSIQUE : Lecture liste des annexes à supprimer
    // RETOUR : Liste des annexes
    function physiqueAnnexesToDelete()
    {
        // Initialisations
        $listeAnnexesToDelete = array();

        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM calendars_annexes
                            WHERE to_delete = "Y"
                            ORDER BY id DESC');

        while ($data = $req->fetch())
        {
            // Instanciation d'un objet Annexe à partir des données remontées de la bdd
            $annexe = Annexe::withData($data);

            // On ajoute la ligne au tableau
            array_push($listeAnnexesToDelete, $annexe);
        }

        $req->closeCursor();

        // Retour
        return $listeAnnexesToDelete;
    }

    // PHYSIQUE : Lecture données élément Calendars
    // RETOUR : Objet Calendars ou Annexe
    function physiqueDonneesCalendars($idCalendars, $table)
    {
        // Requête
        global $bdd;

        $req = $bdd->query('SELECT *
                            FROM ' . $table . '
                            WHERE id = ' . $idCalendars);

        $data = $req->fetch();

        // Instanciation d'un objet Calendrier ou Annexe à partir des données remontées de la bdd
        if ($table == 'calendars')
            $calendars = Calendrier::withData($data);
        else
            $calendars = Annexe::withData($data);

        $req->closeCursor();

        // Retour
        return $calendars;
    }

    /****************************************************************************/
    /********************************** UPDATE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Mise à jour de la préférence utilisateur
    // RETOUR : Aucun
    function physiqueUpdateAutorisationsCalendars($identifiant, $manageCalendars)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE preferences
                              SET manage_calendars = :manage_calendars
                              WHERE identifiant = "' . $identifiant . '"');

        $req->execute(array(
            'manage_calendars' => $manageCalendars
        ));

        $req->closeCursor();
    }

    // PHYSIQUE : Mise à jour du statut du calendrier ou de l'annexe
    // RETOUR : Aucun
    function physiqueUpdateStatusCalendars($table, $idCalendars, $toDelete)
    {
        // Requête
        global $bdd;

        $req = $bdd->prepare('UPDATE ' . $table . '
                              SET to_delete = :to_delete
                              WHERE id = ' . $idCalendars);

        $req->execute(array(
            'to_delete' => $toDelete
        ));

        $req->closeCursor();
    }

    /****************************************************************************/
    /********************************** DELETE **********************************/
    /****************************************************************************/
    // PHYSIQUE : Suppression calendrier
    // RETOUR : Aucun
    function physiqueDeleteCalendrier($idCalendars)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM calendars
                           WHERE id = ' . $idCalendars);
    }

    // PHYSIQUE : Suppression annexe
    // RETOUR : Aucun
    function physiqueDeleteAnnexe($idCalendars)
    {
        // Requête
        global $bdd;

        $req = $bdd->exec('DELETE FROM calendars_annexes
                           WHERE id = ' . $idCalendars);
    }
?>