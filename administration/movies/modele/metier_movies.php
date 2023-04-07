<?php
    include_once('../../includes/classes/movies.php');
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

    // METIER : Lecture des films à supprimer
    // RETOUR : Liste des films à supprimer
    function getFilmsToDelete()
    {
        // Récupération de la liste des films à supprimer
        $listeFilmsToDelete = physiqueFilmsToDelete();

        // Récupération des données complémentaires
        foreach ($listeFilmsToDelete as $film)
        {
            // Pseudo du suppresseur
            $film->setPseudo_del(physiquePseudoUser($film->getIdentifiant_del()));

            // Pseudo de l'ajouteur
            $film->setPseudo_add(physiquePseudoUser($film->getIdentifiant_add()));

            // Nombre de participants
            $film->setNb_users(physiqueNombreParticipants($film->getId()));
        }

        // Retour
        return $listeFilmsToDelete;
    }

    // METIER : Supprime un film de la base
    // RETOUR : Aucun
    function deleteFilm($post)
    {
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
        physiqueDeleteFilm($idFilm);

        // Génération succès
        insertOrUpdateSuccesValue('publisher', $identifiantAjout, -1);

        // Suppression des notifications
        deleteNotification('film', $equipe, $idFilm);
        deleteNotification('doodle', $equipe, $idFilm);
        deleteNotification('cinema', $equipe, $idFilm);
        deleteNotification('comments', $equipe, $idFilm);

        // Message d'alerte
        $_SESSION['alerts']['film_deleted'] = true;
    }

    // METIER : Réinitialise un film de la base
    // RETOUR : Aucun
    function resetFilm($post)
    {
        // Récupération des données
        $idFilm         = $post['id_film'];
        $equipe         = $post['team_film'];
        $toDelete       = 'N';
        $identifiantDel = '';

        // Remise à "N" de l'indicateur de demande et effacement identifiant suppression
        physiqueResetFilm($idFilm, $toDelete, $identifiantDel);

        // Mise à jour du statut des notifications
        updateNotification('film', $equipe, $idFilm, $toDelete);
        updateNotification('doodle', $equipe, $idFilm, $toDelete);
        updateNotification('cinema', $equipe, $idFilm, $toDelete);
        updateNotification('comments', $equipe, $idFilm, $toDelete);

        // Message d'alerte
        $_SESSION['alerts']['film_reseted'] = true;
    }
?>