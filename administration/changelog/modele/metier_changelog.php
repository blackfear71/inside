<?php
    include_once('../../includes/classes/changelog.php');

    // METIER : Récupération des catégories pour les logs
    // RETOUR : Liste des catégories
    function getCategories()
    {
        // Liste des catégories
        $listeCategories = array(
            'admin'            => 'ADMINISTRATION',
            'other'            => 'AUTRE',
            'calendars'        => 'CALENDARS',
            'collector'        => 'COLLECTOR ROOM',
            'cooking_box'      => 'COOKING BOX',
            'bugs'             => 'DEMANDES D\'ÉVOLUTION',
            'expense_center'   => 'EXPENSE CENTER',
            'general'          => 'GÉNÉRAL',
            'chat'             => 'INSIDE ROOM',
            'change_log'       => 'JOURNAL DES MODIFICATIONS',
            'food_advisor'     => 'LES ENFANTS ! À TABLE !',
            'petits_pedestres' => 'LES PETITS PÉDESTRES',
            'missions'         => 'MISSIONS : INSIDER',
            'movie_house'      => 'MOVIE HOUSE',
            'notifications'    => 'NOTIFICATIONS',
            'portail'          => 'PORTAIL',
            'profile'          => 'PROFIL',
            'search'           => 'RECHERCHE',
            'cron'             => 'TÂCHES CRON',
            'technical'        => 'TECHNIQUE',
            'ideas'            => '#THEBOX'
        );

        // Retour
        return $listeCategories;
    }

    // METIER : Initialise les paramètres des changelogs
    // RETOUR : Paramètres
    function initializeChangeLog()
    {
        // Initialisations
        $changeLogParameters = new ChangeLogParameters();

        // Retour
        return $changeLogParameters;
    }

    // METIER : Récupère les paramètres des changelogs
    // RETOUR : Paramètres
    function getChangeLogParameters($parametres)
    {
        // Suppression de la session
        unset($_SESSION['changelog']);

        // Initialisations
        $changeLogParameters = new ChangeLogParameters();

        // Récupération des paramètres
        $changeLogParameters->setAction($parametres['action']);
        $changeLogParameters->setYear($parametres['year']);
        $changeLogParameters->setWeek($parametres['week']);

        // Retour
        return $changeLogParameters;
    }

    // METIER : Sauvegarde des paramètres en session
    // RETOUR : Aucun
    function saveChangeLogParameters($post)
    {
        // Sauvegarde des paramètres saisis en session
        $_SESSION['changelog']['action'] = $post['action_changelog'];
        $_SESSION['changelog']['year']   = $post['annee_changelog'];
        $_SESSION['changelog']['week']   = formatWeekForInsert($post['semaine_changelog']);
    }

    // METIER : Contrôle l'existence d'un journal
    // RETOUR : Booléen
    function controlChangeLog($parametres)
    {
        // Récupération des données
        $action = $parametres->getAction();
        $year   = $parametres->getYear();
        $week   = $parametres->getWeek();

        // Contrôle journal existant
        $errorChangelog = controleChangelogExistant($action, $year, $week);

        // Retour
        return $errorChangelog;
    }

    // METIER : Récupération des données existantes
    // RETOUR : Log à modifier ou supprimer
    function getChangeLog($parametres, $categories)
    {
        // Récupération des données
        $year = $parametres->getYear();
        $week = $parametres->getWeek();

        // Récupération du log
        $changelog = physiqueChangelog($year, $week);

        // Extraction des logs
        $extractLogs = explode(';', $changelog->getLogs());

        // Tri par catégories
        $sortedLogs = array();

        foreach ($categories as $categorie => $labelCategorie)
        {
            foreach ($extractLogs as $keyExtract => $extractedLog)
            {
                if (!empty($extractedLog))
                {
                    list($entryExtracted, $categoryExtracted) = explode('@', $extractedLog);

                    if ($categoryExtracted == $categorie)
                    {
                        if (!isset($sortedLogs[$categorie]))
                            $sortedLogs[$categorie] = array();

                        array_push($sortedLogs[$categorie], $entryExtracted);
                        unset($extractLogs[$keyExtract]);
                    }
                }
                else
                    unset($extractLogs[$keyExtract]);
            }
        }

        // Sécurité si besoin (logs restants sans catégorie)
        if (!empty($extractLogs))
        {
            if (!isset($sortedLogs['other']))
                $sortedLogs['other'] = array();

            foreach ($extractLogs as $keyExtract => $extractedLog)
            {
                if (!empty($extractedLog))
                {
                    list($entryExtracted, $categoryExtracted) = explode('@', $extractedLog);
                    array_push($sortedLogs['other'], $entryExtracted);
                }
            }
        }

        // Redéfinition des lignes du journal
        $changelog->setLogs($sortedLogs);

        // Retour
        return $changelog;
    }

    // METIER : Insertion d'un journal
    // RETOUR : Aucun
    function insertChangeLog($post, $categories)
    {
        // Initialisations
        $control_ok = true;

        // Récupération des données
        $year                = $post['saisie_annee_changelog'];
        $week                = $post['saisie_semaine_changelog'];
        $notes               = $post['saisie_notes_changelog'];
        $saisiesEntrees      = $post['saisies_entrees'];
        $categoriesEntrees   = $post['categories_entrees'];
        $entries             = '';
        $contentNotification = $week . ';' . $year . ';';

        // Filtrage des entrées vides
        foreach ($saisiesEntrees as $keyEntry => $entry)
        {
            if (empty($entry))
                unset($saisiesEntrees[$keyEntry]);
        }

        // Contrôle si notes et entrées vides
        $control_ok = controleNotesEntreesVides($notes, $saisiesEntrees);

        // Filtrage des symboles interdits
        if ($control_ok == true)
        {
            $search  = array('@', ';');
            $replace = array(' ', ' ');

            foreach ($saisiesEntrees as $keyEntry => $entry)
            {
                $saisiesEntrees[$keyEntry] = str_replace($search, $replace, $entry);
            }
        }

        // Tri et récupération des entrées
        if ($control_ok == true)
        {
            foreach ($categories as $categorie => $labelCategorie)
            {
                if (!empty($saisiesEntrees))
                {
                    foreach ($saisiesEntrees as $keyEntry => $entry)
                    {
                        if ($categoriesEntrees[$keyEntry] == $categorie)
                        {
                            $entries .= $entry . '@' . $categoriesEntrees[$keyEntry] . ';';
                            unset($saisiesEntrees[$keyEntry]);
                        }
                    }
                }
            }
        }

        // Insertion enregistrement en base
        if ($control_ok == true)
        {
            $changelog = array(
                'week'  => $week,
                'year'  => $year,
                'notes' => $notes,
                'logs'  => $entries
            );

            physiqueInsertionChangelog($changelog);

            // Insertion notification
            insertNotification('changelog', '', $contentNotification, 'admin');

            // Message d'alerte
            $_SESSION['alerts']['log_added'] = true;
        }
    }

    // METIER : Mise à jour d'un journal
    // RETOUR : Aucun
    function updateChangeLog($post, $categories)
    {
        // Initialisations
        $control_ok = true;

        // Récupération des données
        $year                = $post['saisie_annee_changelog'];
        $week                = $post['saisie_semaine_changelog'];
        $notes               = $post['saisie_notes_changelog'];
        $saisiesEntrees      = $post['saisies_entrees'];
        $categoriesEntrees   = $post['categories_entrees'];
        $entries             = '';

        // Filtrage des entrées vides
        foreach ($saisiesEntrees as $keyEntry => $entry)
        {
            if (empty($entry))
                unset($saisiesEntrees[$keyEntry]);
        }

        // Contrôle si notes et entrées vides
        $control_ok = controleNotesEntreesVides($notes, $saisiesEntrees);

        // Filtrage des symboles interdits
        if ($control_ok == true)
        {
            $search  = array('@', ';');
            $replace = array(' ', ' ');

            foreach ($saisiesEntrees as $keyEntry => $entry)
            {
                $saisiesEntrees[$keyEntry] = str_replace($search, $replace, $entry);
            }
        }

        // Tri et récupération des entrées
        if ($control_ok == true)
        {
            foreach ($categories as $categorie => $labelCategorie)
            {
                if (!empty($saisiesEntrees))
                {
                    foreach ($saisiesEntrees as $keyEntry => $entry)
                    {
                        if ($categoriesEntrees[$keyEntry] == $categorie)
                        {
                            $entries .= $entry . '@' . $categoriesEntrees[$keyEntry] . ';';
                            unset($saisiesEntrees[$keyEntry]);
                        }
                    }
                }
            }
        }

        // Modification de l'enregistrement en base
        if ($control_ok == true)
        {
            $changelog = array(
                'notes' => $notes,
                'logs'  => $entries
            );

            physiqueUpdateChangelog($changelog, $year, $week);

            // Message d'alerte
            $_SESSION['alerts']['log_updated'] = true;
        }
    }

    // METIER : Suppression d'un journal
    // RETOUR : Aucun
    function deleteChangeLog($post)
    {
        // Récupération des données
        $year                = $post['saisie_annee_changelog'];
        $week                = $post['saisie_semaine_changelog'];
        $contentNotification = $week . ';' . $year . ';';

        // Suppression du journal
        physiqueDeleteChangelog($year, $week);

        // Suppression des notifications
        deleteNotification('changelog', '', $contentNotification);

        // Message d'alerte
        $_SESSION['alerts']['log_deleted'] = true;
    }
?>