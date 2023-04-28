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

    // METIER : Lecture de la liste des utilisateurs
    // RETOUR : Liste des utilisateurs
    function getListeUsers($equipe)
    {
        // Lecture de la liste des équipes
        $listeUsers = physiqueListeUsers($equipe);

        // Retour
        return $listeUsers;
    }

    // METIER : Lecture liste des idées
    // RETOUR : Tableau d'idées
    function getIdees($get, $nombrePages, $sessionUser, $listeUsers)
    {
        // Initialisations
        $nombreParPage = 18;

        // Récupération des données
        $identifiant = $sessionUser['identifiant'];
        $equipe      = $sessionUser['equipe'];
        $view        = $get['view'];
        $page        = $get['page'];

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
            if (isset($listeUsers[$idee->getAuthor()]))
            {
                $idee->setPseudo_author($listeUsers[$idee->getAuthor()]['pseudo']);
                $idee->setAvatar_author($listeUsers[$idee->getAuthor()]['avatar']);
            }

            // Recherche du pseudo et de l'avatar du developpeur (si renseigné)
            if (!empty($idee->getDevelopper()))
            {
                if (isset($listeUsers[$idee->getDevelopper()]))
                {
                    $idee->setPseudo_developper($listeUsers[$idee->getDevelopper()]['pseudo']);
                    $idee->setAvatar_developper($listeUsers[$idee->getDevelopper()]['avatar']);
                }
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
        $subject    = $post['sujet_idee'];
        $content    = $post['contenu_idee'];
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
        $idIdee  = $post['id_idee'];
        $subject = $post['sujet_idee'];
        $content = $post['contenu_idee'];

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
        $idIdee = $post['id_idee'];

        // Récupération des données existantes de l'idée
        $ideeExistante = physiqueIdee($idIdee);

        // Détermination des données à insérer et de la vue en fonction de l'action effectuée
        if (isset($post['take']))
        {
            $status     = 'C';
            $developper = $identifiant;
        }
        elseif (isset($post['developp']))
        {
            $status     = 'P';
            $developper = $identifiant;
        }
        elseif (isset($post['end']))
        {
            $status     = 'D';
            $developper = $identifiant;
            $view       = 'done';
        }
        elseif (isset($post['reject']))
        {
            $status     = 'R';
            $developper = $identifiant;
            $view       = 'done';
        }
        elseif (isset($post['reset']))
        {
            $status     = 'O';
            $developper = '';
            $view       = 'inprogress';
        }
        else
        {
            $status     = 'O';
            $developper = '';
            $view       = 'inprogress';
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
            // Terminée
            case 'D':
                insertOrUpdateSuccesValue('applier', $ideeExistante->getDevelopper(), 1);
                break;

            // Rejetée
            case 'R':
                insertOrUpdateSuccesValue('creator', $ideeExistante->getAuthor(), -1);
                break;

            // Ouverte
            case 'O':
                if ($ideeExistante->getStatus() == 'D')
                    insertOrUpdateSuccesValue('applier', $ideeExistante->getDevelopper(), -1);

                if ($ideeExistante->getStatus() == 'R')
                    insertOrUpdateSuccesValue('creator', $ideeExistante->getAuthor(), 1);
                break;

            // Autres statuts
            default:
                break;
        }

        // Retour
        return $view;
    }

    // METIER : Récupère le numéro de page pour la redirection après changement de statut
    // RETOUR : Numéro de page
    function getNumeroPageIdee($idIdee, $view, $sessionUser)
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