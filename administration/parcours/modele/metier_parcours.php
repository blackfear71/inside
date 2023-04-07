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
        // Initialisations
        $cumulsParticipations = array();

        // Récupération des données
        $idParcours = $post['id_parcours'];
        $equipe     = $post['team_parcours'];

        // Lecture des données du parcours
        $parcours = physiqueDonneesParcours($idParcours);

        // Lecture de la liste des participations
        $listeParticipations = physiqueParticipationsParcours($idParcours);

        // Calcul des cumuls pour la suppression des succès
        if (!empty($listeParticipations))
        {
            foreach ($listeParticipations as $participation)
            {
                if (!isset($cumulsParticipations[$participation->getIdentifiant()]))
                {
                    $cumulsParticipations[$participation->getIdentifiant()] = array(
                        'runner'     => 1,
                        'marathon'   => $participation->getDistance(),
                        'competitor' => $participation->getCompetition() == 'Y' ? 1 : 0
                    );
                }
                else
                {
                    $cumulsParticipations[$participation->getIdentifiant()]['runner']     += 1;
                    $cumulsParticipations[$participation->getIdentifiant()]['marathon']   += $participation->getDistance();
                    $cumulsParticipations[$participation->getIdentifiant()]['competitor'] += $participation->getCompetition() == 'Y' ? 1 : 0;
                }
            }
        }

        // Suppression des images et documents
        if (!empty($parcours->getPicture()))
            unlink('../../includes/images/petitspedestres/pictures/' . $parcours->getPicture());

        switch ($parcours->getType())
        {
            case 'document':
                unlink('../../includes/datas/petitspedestres/' . $parcours->getDocument());
                break;

            case 'picture':
                unlink('../../includes/images/petitspedestres/documents/' . $parcours->getDocument());
                break;

            default:
                break;
        }

        // Suppression des participations du parcours
        physiqueDeleteParticipationsParcours($idParcours);

        // Suppression de l'enregistrement en base
        physiqueDeleteParcours($idParcours);

        // Génération succès
        insertOrUpdateSuccesValue('explorer', $parcours->getIdentifiant_add(), -1);
        
        if (!empty($cumulsParticipations))
        {
            foreach ($cumulsParticipations as $identifiant => $cumulParticipation)
            {
                insertOrUpdateSuccesValue('runner', $identifiant, -1 * $cumulParticipation['runner']);

                if (!empty($cumulParticipation['marathon']))
                    insertOrUpdateSuccesValue('marathon', $identifiant, -1 * $cumulParticipation['marathon']);
        
                if (!empty($cumulParticipation['competitor']))
                    insertOrUpdateSuccesValue('competitor', $identifiant, -1 * $cumulParticipation['competitor']);
            }
        }

        // Suppression des notifications
        deleteNotification('parcours', $equipe, $idParcours);

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