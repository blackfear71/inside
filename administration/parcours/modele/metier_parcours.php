<?php
    include_once('../../includes/classes/parcours.php');
    include_once('../../includes/classes/teams.php');

    // METIER : Lecture de la liste des équipes
    // RETOUR : Liste des équipes
    function getListeEquipes()
    {
        // Lecture de la liste des équipes
        $listeEquipes = physiqueListeEquipes();

        // Retour
        return $listeEquipes;
    }

    // METIER : Lecture des parcours à supprimer
    // RETOUR : Liste des parcours à supprimer
    function getParcoursToDelete()
    {
        // Récupération de la liste des parcours à supprimer
        $listeParcoursToDelete = physiqueParcoursToDelete();

        // Récupération des données complémentaires
        foreach ($listeParcoursToDelete as $parcours)
        {
            // Pseudo du suppresseur
            $parcours->setPseudo_del(physiquePseudoUser($parcours->getIdentifiant_del()));

            // Pseudo de l'ajouteur
            $parcours->setPseudo_add(physiquePseudoUser($parcours->getIdentifiant_add()));

            // Nombre de participants
            $parcours->setRuns(physiqueNombreParticipants($parcours->getId()));
        }

        // Retour
        return $listeParcoursToDelete;
    }

    // METIER : Supprime un parcours de la base
    // RETOUR : Aucun
    function deleteParcours($post)
    {


        // TODO : supprimer le parcours, les participations, les fichiers, les succès et les notifications






        /*
        // Récupération des données
        $idFilm = $post['id_film'];
        $equipe = $post['team_film'];

        // Récupération de l'identifiant de l'ajouteur
        $identifiantAjout = physiqueIdentifiantAjoutFilm($idFilm);

        // Suppression des avis du film
        physiqueDeleteAvisFilms($idFilm);

        // Suppression des commentaires du film
        physiqueDeleteCommentsFilms($idFilm);

        // Suppression du film
        physiqueDeleteFilms($idFilm);

        // Génération succès
        insertOrUpdateSuccesValue('publisher', $identifiantAjout, -1);

        // Suppression des notifications
        deleteNotification('film', $equipe, $idFilm);
        deleteNotification('doodle', $equipe, $idFilm);
        deleteNotification('cinema', $equipe, $idFilm);
        deleteNotification('comments', $equipe, $idFilm);*/




        





        // Message d'alerte
        $_SESSION['alerts']['parcours_deleted'] = true;
    }

    // METIER : Réinitialise un parcours de la base
    // RETOUR : Aucun
    function resetParcours($post)
    {
        // Récupération des données
        $idParcours     = $post['id_parcours'];
        $equipe         = $post['team_parcours'];
        $toDelete       = 'N';
        $identifiantDel = '';

        // Remise à "N" de l'indicateur de demande et effacement identifiant suppression
        physiqueResetParcours($idParcours, $toDelete, $identifiantDel);

        // Mise à jour du statut des notifications
        updateNotification('parcours', $equipe, $idParcours, $toDelete);

        // Message d'alerte
        $_SESSION['alerts']['parcours_reseted'] = true;
    }
?>