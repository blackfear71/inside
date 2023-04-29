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
            // Nombre de participants
            $film->setNb_users(physiqueNombreParticipants($film->getId()));

            // Nombre de commentaires
            $film->setNb_comments(physiqueNombreCommentaires($film->getId()));
        }

        // Retour
        return $listeFilmsToDelete;
    }

    // METIER : Supprime un film de la base
    // RETOUR : Aucun
    function deleteFilm($post)
    {
        // Initialisations
        $cumulsFilm = array();
        
        // Récupération des données
        $idFilm = $post['id_film'];
        $equipe = $post['team_film'];

        // Lecture des données du film
        $film = physiqueDonneesFilm($idFilm);

        // Lecture de la liste des commentaires
        $listeCommentaires = physiqueCommentairesFilms($idFilm);

        // Lecture de la liste des avis
        $listeEtoiles = physiqueEtoilesFilms($idFilm);

        // Calcul des cumuls pour la suppression des succès
        if (!empty($listeCommentaires))
        {
            foreach ($listeCommentaires as $commentaire)
            {
                if (!isset($cumulsFilm[$commentaire->getIdentifiant()]))
                {
                    $cumulsFilm[$commentaire->getIdentifiant()] = array(
                        'commentator' => 1,
                        'viewer'      => 0,
                        'padawan'     => 0
                    );
                }
                else
                    $cumulsFilm[$commentaire->getIdentifiant()]['commentator'] += 1;
            }
        }

        if (!empty($listeEtoiles))
        {
            foreach ($listeEtoiles as $etoile)
            {
                if (!isset($cumulsFilm[$etoile->getIdentifiant()]))
                {
                    $cumulsFilm[$etoile->getIdentifiant()] = array(
                        'commentator' => 0,
                        'viewer'      => $etoile->getParticipation() == 'S' ? 1 : 0,
                        'padawan'     => 0
                    );
                }
                else
                {
                   if ($etoile->getParticipation() == 'S')
                       $cumulsFilm[$etoile->getIdentifiant()]['viewer'] = 1;
                }
            }
        }

        if (stripos($film->getFilm(), 'Les derniers Jedi') !== false AND !empty($listeEtoiles))
        {
            foreach ($listeEtoiles as $etoile)
            {
                if ($etoile->getParticipation() == 'S')
                {
                    if (!isset($cumulsFilm[$etoile->getIdentifiant()]))
                    {
                        $cumulsFilm[$etoile->getIdentifiant()] = array(
                            'commentator' => 0,
                            'viewer'      => 0,
                            'padawan'     => 0
                        );
                    }
                    else
                        $cumulsFilm[$etoile->getIdentifiant()]['padawan'] = 0;
                }
            }
        }

        // Suppression des commentaires du film
        physiqueDeleteCommentsFilms($idFilm);

        // Suppression des avis du film
        physiqueDeleteAvisFilms($idFilm);

        // Suppression du film
        physiqueDeleteFilm($idFilm);

        // Génération succès
        insertOrUpdateSuccesValue('publisher', $film->getIdentifiant_add(), -1);

        if (!empty($cumulsFilm))
        {
            foreach ($cumulsFilm as $identifiant => $cumulFilm)
            {
                insertOrUpdateSuccesValue('commentator', $identifiant, -1 * $cumulFilm['commentator']);
                insertOrUpdateSuccesValue('viewer', $identifiant, -1 * $cumulFilm['viewer']);
        
                if (stripos($film->getFilm(), 'Les derniers Jedi') !== false)
                    insertOrUpdateSuccesValue('padawan', $identifiant, $cumulFilm['padawan']);
            }
        }

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