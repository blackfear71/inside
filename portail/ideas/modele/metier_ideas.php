<?php
    include_once('../../includes/classes/ideas.php');
    include_once('../../includes/classes/profile.php');

    // METIER : Lecture nombre de pages en fonction de la vue
    // RETOUR : Nombre de pages
    function getPages($view, $sessionUser)
    {
        // Initialisations
        $nombreParPage = 18;

        // Récupération des données
        $identifiant = $sessionUser['identifiant'];
        $equipe      = $sessionUser['equipe'];

        // Récupération du nombre d'idées en fonction de la vue
        $nombreIdees = physiqueNombreIdees($view, $equipe, $identifiant);

        // Calcul du nombre total d'enregistrements pour chaque vue
        $nombrePages = ceil($nombreIdees / $nombreParPage);

        // Retour
        return $nombrePages;
    }

    // METIER : Lecture liste des idées
    // RETOUR : Tableau d'idées
    function getIdees($view, $page, $nombrePages, $sessionUser)
    {
        // Initialisations
        $nombreParPage = 18;

        // Récupération des données
        $identifiant = $sessionUser['identifiant'];
        $equipe      = $sessionUser['equipe'];

        // Vérification que l'on ne dépasse pas la dernière page
        if ($page > $nombrePages)
            $page = $nombrePages;

        // Calcul de la première occurence à récupérer en fonction de la page demandée
        $premiereEntree = ($page - 1) * $nombreParPage;

        // Lecture des idées
        $listeIdees = physiqueIdees($view, $premiereEntree, $nombreParPage, $equipe, $identifiant);

        // Recherche des pseudos et des avatars
        foreach ($listeIdees as $idee)
        {
            // Recherche du pseudo et de l'avatar de l'auteur
            $auteur = physiqueUser($idee->getAuthor());

            // Recherche du pseudo et de l'avatar du developpeur (si renseigné)
            if (!empty($idee->getDevelopper()))
                $developpeur = physiqueUser($idee->getDevelopper());

            // Ajout des données complémentaires à l'idée
            if (isset($auteur) AND !empty($auteur))
            {
                $idee->setPseudo_author($auteur->getPseudo());
                $idee->setAvatar_author($auteur->getAvatar());
            }

            if (isset($developpeur) AND !empty($developpeur))
            {
                $idee->setPseudo_developper($developpeur->getPseudo());
                $idee->setAvatar_developper($developpeur->getAvatar());
            }
        }

        // Retour
        return $listeIdees;
    }

    // METIER : Insertion d'une idée
    // RETOUR : Id enregistrement créé
    function insertIdee($post, $sessionUser)
    {
        // Récupération des données
        $author     = $sessionUser['identifiant'];
        $equipe     = $sessionUser['equipe'];
        $subject    = $post['subject_idea'];
        $content    = $post['content_idea'];
        $date       = date('Ymd');
        $status     = 'O';
        $developper = '';

        // Insertion de l'enregistrement en base
        $idee = array(
            'team'       => $equipe,
            'subject'    => $subject,
            'date'       => $date,
            'author'     => $author,
            'content'    => $content,
            'status'     => $status,
            'developper' => $developper
        );

        $idIdee = physiqueInsertionIdee($idee);

        // Insertion notification
        insertNotification('idee', $equipe, $idIdee, $author);

        // Génération succès
        insertOrUpdateSuccesValue('creator', $author, 1);

        // Ajout expérience
        insertExperience($author, 'add_idea');

        // Message d'alerte
        $_SESSION['alerts']['idea_submitted'] = true;

        // Retour
        return $idIdee;
    }

    // METIER : Mise à jour d'une idée
    // RETOUR : Id idée
    function updateIdee($post)
    {
        // Récupération des données
        $idIdee  = $post['id_idea'];
        $subject = $post['subject_idea'];
        $content = $post['content_idea'];

        // Modification de l'enregistrement en base
        $idee = array(
            'subject'    => $subject,
            'content'    => $content
        );

        physiqueUpdateIdee($idIdee, $idee);

        // Message d'alerte
        $_SESSION['alerts']['idea_updated'] = true;

        // Retour
        return $idIdee;
    }

    // METIER : Mise à jour du statut d'une idée
    // RETOUR : Vue à afficher
    function updateStatutIdee($post, $view, $identifiant)
    {
        // Récupération des données
        $idIdee = $post['id_idea'];
        $action = $post;

        unset($action['id_idea']);

        // Récupération des données existantes de l'idée
        $ideeExistante = physiqueIdee($idIdee);

        // Détermination des données à insérer et de la vue en fonction de l'action effectuée
        switch (key($action))
        {
            case 'take':
                $status     = 'C';
                $developper = $identifiant;
                break;

            case 'developp':
                $status     = 'P';
                $developper = $identifiant;
                break;

            case 'end':
                $status     = 'D';
                $developper = $identifiant;
                $view       = 'done';
                break;

            case 'reject':
                $status     = 'R';
                $developper = $identifiant;
                $view       = 'done';
                break;

            case 'reset':
            default:
                $status     = 'O';
                $developper = '';
                $view       = 'inprogress';
                break;
        }

        // Insertion de l'enregistrement en base
        $idee = array(
            'status'     => $status,
            'developper' => $developper
        );

        physiqueUpdateStatutIdee($idIdee, $idee);

        // Génération succès
        switch ($status)
        {
            case 'D':
                insertOrUpdateSuccesValue('applier', $ideeExistante->getDevelopper(), 1);
                break;

            case 'R':
                insertOrUpdateSuccesValue('creator', $ideeExistante->getAuthor(), -1);
                break;

            case 'O':
                if ($ideeExistante->getStatus() == 'D')
                    insertOrUpdateSuccesValue('applier', $ideeExistante->getDevelopper(), -1);

                if ($ideeExistante->getStatus() == 'R')
                    insertOrUpdateSuccesValue('creator', $ideeExistante->getAuthor(), 1);
                break;

            default:
                break;
        }

        // Retour
        return $view;
    }

    // METIER : Récupère le numéro de page pour la redirection après changement de statut
    // RETOUR : Numéro de page
    function getNumeroPageIdea($idIdee, $view, $sessionUser)
    {
        // Initialisations
        $nombreParPage = 18;

        // Récupération des données
        $identifiant = $sessionUser['identifiant'];
        $equipe      = $sessionUser['equipe'];

        // Recherche de la position de l'idée dans la table en fonction de la vue
        $positionIdee = physiquePositionIdee($view, $idIdee, $equipe, $identifiant);

        // Calcul du numéro de page de l'idée
        $numeroPage = ceil($positionIdee / $nombreParPage);

        // Retour
        return $numeroPage;
    }

    // METIER : Conversion de la liste d'objets des idées en tableau simple pour JSON
    // RETOUR : Tableau des idées
    function convertForJsonListeIdees($listeIdees)
    {
        // Initialisations
        $listeIdeesAConvertir = array();

        // Conversion de la liste d'objets en tableau pour envoyer au Javascript
        foreach ($listeIdees as $ideeAConvertir)
        {
            $idee = array(
                'id'                => $ideeAConvertir->getId(),
                'team'              => $ideeAConvertir->getTeam(),
                'subject'           => $ideeAConvertir->getSubject(),
                'date'              => $ideeAConvertir->getDate(),
                'author'            => $ideeAConvertir->getAuthor(),
                'pseudo_author'     => $ideeAConvertir->getPseudo_author(),
                'avatar_author'     => $ideeAConvertir->getAvatar_author(),
                'content'           => $ideeAConvertir->getContent(),
                'status'            => $ideeAConvertir->getStatus(),
                'developper'        => $ideeAConvertir->getDevelopper(),
                'pseudo_developper' => $ideeAConvertir->getPseudo_developper(),
                'avatar_developper' => $ideeAConvertir->getAvatar_developper()
            );

            $listeIdeesAConvertir[$ideeAConvertir->getId()] = $idee;
        }

        // Retour
        return $listeIdeesAConvertir;
    }
?>