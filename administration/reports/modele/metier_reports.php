<?php
    include_once('../../includes/classes/bugs.php');
    include_once('../../includes/classes/teams.php');

    // METIER : Lecture de la liste des utilisateurs
    // RETOUR : Liste des utilisateurs
    function getListeUsers()
    {
        // Lecture de la liste des équipes
        $listeUsers = physiqueListeUsers();

        // Retour
        return $listeUsers;
    }

    // METIER : Lecture de la liste des équipes
    // RETOUR : Liste des équipes
    function getListeEquipes()
    {
        // Lecture de la liste des équipes
        $listeEquipes = physiqueListeEquipes();

        // Retour
        return $listeEquipes;
    }

    // METIER : Lecture liste des bugs / évolutions
    // RETOUR : Liste des bugs / évolutions
    function getBugs($view, $type, $listeUsers, $listeEquipe)
    {
        // Récupération des rapports en fonction de la vue et du type
        $rapports = physiqueListeRapports($view, $type);

        // Récupération des données complémentaires
        foreach ($rapports as $rapport)
        {
            // Recherche des données de l'auteur
            if (isset($listeUsers[$rapport->getIdentifiant()]))
            {
                $rapport->setPseudo($listeUsers[$rapport->getIdentifiant()]['pseudo']);
                $rapport->setAvatar($listeUsers[$rapport->getIdentifiant()]['avatar']);
            }

            // Recherche du libellé de l'équipe
            if (isset($listeEquipe[$rapport->getTeam()]))
                $rapport->setTeam($listeEquipe[$rapport->getTeam()]->getTeam());
        }

        // Retour
        return $rapports;
    }

    // METIER : Mise à jour du statut d'un bug
    // RETOUR : Top redirection
    function updateBug($post)
    {
        // Récupération des données
        $idRapport  = $post['id_report'];

        // Lecture des données du rapport
        $rapport = physiqueRapport($idRapport);

        // Détermination du statut en fonction de l'action
        if (isset($post['resolve_bug']))
        {
            $resolution = $post['resolution'];
            $resolved   = 'Y';
        }
        elseif (isset($post['unresolve_bug']))
        {
            $resolution = $rapport->getResolution();
            $resolved   = 'N';
        }
        elseif (isset($post['reject_bug']))
        {
            $resolution = $post['resolution'];
            $resolved   = 'R';
        }
        else
        {
            $resolution = '';
            $resolved   = 'N';
        }
        
        // Modification de l'enregistrement en base
        $bug = array(
            'resolution' => $resolution,
            'resolved'   => $resolved
        );

        // Mise à jour du statut
        physiqueUpdateRapport($idRapport, $bug);

        // Génération succès (sauf si rejeté ou remis en cours après rejet)
        if ($resolved != 'R' AND $rapport->getResolved() != 'R')
        {
            if ($resolved == 'Y')
                insertOrUpdateSuccesValue('compiler', $rapport->getIdentifiant(), 1);
            else
                insertOrUpdateSuccesValue('compiler', $rapport->getIdentifiant(), -1);
        }

        // Retour
        return $resolved;
    }

    // METIER : Suppression d'un bug
    // RETOUR : Aucun
    function deleteBug($post)
    {
        // Récupération des données
        $idRapport = $post['id_report'];

        // Lecture des données du rapport
        $rapport = physiqueRapport($idRapport);

        // Suppression image si présente
        if (!empty($rapport->getPicture()))
            unlink('../../includes/images/reports/' . $rapport->getPicture());

        // Suppression de l'enregistrement en base
        physiqueDeleteRapport($idRapport);

        // Génération succès
        insertOrUpdateSuccesValue('debugger', $rapport->getIdentifiant(), -1);

        if ($rapport->getResolved() == 'Y')
            insertOrUpdateSuccesValue('compiler', $rapport->getIdentifiant(), -1);

        // Message d'alerte
        $_SESSION['alerts']['bug_deleted'] = true;
    }
?>