<?php
    // METIER : Génération du portail administration
    // RETOUR : Tableau des liens
    function getPortail($alertEquipes, $alertUsers, $alertFilms, $alertVacances, $alertCalendars, $alertAnnexes, $alertParcours, $alertCron, $nombreBugs, $nombreEvols)
    {
        // Vérification des alertes
        if ($alertEquipes == true)
            $avertissementEquipes = true;
        else
            $avertissementEquipes = false;

        if ($alertUsers == true)
            $avertissementUsers = true;
        else
            $avertissementUsers = false;

        if ($alertFilms == true)
            $avertissementFilms = true;
        else
            $avertissementFilms = false;

        if ($alertVacances == true OR $alertCalendars == true OR $alertAnnexes == true)
            $avertissementCalendars = true;
        else
            $avertissementCalendars = false;

        if ($alertParcours == true)
            $avertissementParcours = true;
        else
            $avertissementParcours = false;

        if ($nombreBugs != 0 OR $nombreEvols != 0)
            $avertissementBugs = true;
        else
            $avertissementBugs = false;

        if ($alertCron == true)
            $avertissementCron = true;
        else
            $avertissementCron = false;

        // Tableau des catégories
        $listeCategories = array(
            array(
                'categorie' => 'INFORMATIONS<br />UTILISATEURS',
                'lien'      => '../infosusers/infosusers.php?action=goConsulter',
                'title'     => 'Infos utilisateurs',
                'image'     => '../../includes/icons/common/inside.png',
                'alt'       => 'inside',
                'alert'     => $avertissementEquipes
            ),
            array(
                'categorie' => 'GESTION DES<br />UTILISATEURS',
                'lien'      => '../manageusers/manageusers.php?action=goConsulter',
                'title'     => 'Gestion des utilisateurs',
                'image'     => '../../includes/icons/admin/users.png',
                'alt'       => 'users',
                'alert'     => $avertissementUsers
            ),
            array(
                'categorie' => 'GESTION DES<br />THÈMES',
                'lien'      => '../themes/themes.php?action=goConsulter',
                'title'     => 'Gestion des thèmes',
                'image'     => '../../includes/icons/admin/themes.png',
                'alt'       => 'themes',
                'alert'     => false
            ),
            array(
                'categorie' => 'GESTION DES<br />SUCCÈS',
                'lien'      => '../success/success.php?action=goConsulter',
                'title'     => 'Gestion des succès',
                'image'     => '../../includes/icons/admin/success.png',
                'alt'       => 'success',
                'alert'     => false
            ),
            array(
                'categorie' => 'GESTION DES<br />FILMS',
                'lien'      => '../movies/movies.php?action=goConsulter',
                'title'     => 'Gestion des films',
                'image'     => '../../includes/icons/common/movie_house.png',
                'alt'       => 'movie_house',
                'alert'     => $avertissementFilms
            ),
            array(
                'categorie' => 'GESTION DES<br />CALENDRIERS',
                'lien'      => '../calendars/calendars.php?action=goConsulter',
                'title'     => 'Gestion des calendriers',
                'image'     => '../../includes/icons/common/calendars.png',
                'alt'       => 'calendars',
                'alert'     => $avertissementCalendars
            ),
            array(
                'categorie' => 'GESTION DES<br />PARCOURS',
                'lien'      => '../parcours/parcours.php?action=goConsulter',
                'title'     => 'Gestion des parcours',
                'image'     => '../../includes/icons/common/petits_pedestres.png',
                'alt'       => 'parcours',
                'alert'     => $avertissementParcours
            ),
            array(
                'categorie' => 'GESTION DES<br />MISSIONS',
                'lien'      => '../missions/missions.php?action=goConsulter',
                'title'     => 'Gestion des missions',
                'image'     => '../../includes/icons/common/missions.png',
                'alt'       => 'missions',
                'alert'     => false
            ),
            array(
                'categorie' => 'BUGS <div class="nombre_lien_portail">' . $nombreBugs . '</div> ET<br />ÉVOLUTIONS <div class="nombre_lien_portail">' . $nombreEvols . '</div>',
                'lien'      => '../reports/reports.php?view=unresolved&action=goConsulter',
                'title'     => 'Bugs et évolutions',
                'image'     => '../../includes/icons/admin/bugs_evolutions.png',
                'alt'       => 'bugs_evolutions',
                'alert'     => $avertissementBugs
            ),
            array(
                'categorie' => 'GESTION DES<br />ALERTES',
                'lien'      => '../alerts/alerts.php?action=goConsulter',
                'title'     => 'Gestion des alertes',
                'image'     => '../../includes/icons/common/alert.png',
                'alt'       => 'alert',
                'alert'     => false
            ),
            array(
                'categorie' => 'GESTION<br />CRON',
                'lien'      => '../cron/cron.php?action=goConsulter',
                'title'     => 'Gestion CRON',
                'image'     => '../../includes/icons/admin/cron.png',
                'alt'       => 'cron',
                'alert'     => $avertissementCron
            ),
            array(
                'categorie' => 'JOURNAL DES<br />MODIFICATIONS',
                'lien'      => '../changelog/changelog.php?action=goConsulter',
                'title'     => 'Journal des modifications',
                'image'     => '../../includes/icons/admin/datas.png',
                'alt'       => 'datas',
                'alert'     => false
            ),
            array(
                'categorie' => 'GÉNÉRATEUR DE<br />CODE',
                'lien'      => '../codegenerator/codegenerator.php?action=goConsulter',
                'title'     => 'Générateur de code',
                'image'     => '../../includes/icons/admin/code.png',
                'alt'       => 'code',
                'alert'     => false
            )
        );

        // Retour
        return $listeCategories;
    }

    // METIER : Contrôle alertes équipes
    // RETOUR : Booléen
    function getAlerteEquipes()
    {
        // Appel physique
        $alert = physiqueAlerteEquipes();

        // Retour
        return $alert;
    }

    // METIER : Contrôle alertes utilisateurs
    // RETOUR : Booléen
    function getAlerteUsers()
    {
        // Appel physique
        $alert = physiqueAlerteUsers();

        // Retour
        return $alert;
    }

    // METIER : Contrôle alertes Movie House
    // RETOUR : Booléen
    function getAlerteFilms()
    {
        // Appel physique
        $alert = physiqueAlerteSuppression('movie_house');

        // Retour
        return $alert;
    }

    // METIER : Contrôle alertes vacances
    // RETOUR : Booléen
    function getAlerteVacances()
    {
        // Initialisations
        $alert         = false;
        $anneeInitiale = date('Y');
        $anneeFinale   = $anneeInitiale + 1;
        $nomFichier    = $anneeInitiale . '-' . $anneeFinale . '.csv';

        // Vérification fichier existant
        $dossier = '../../includes/datas/calendars';

        if (date('m') == 12 AND !file_exists($dossier . '/' . $nomFichier))
            $alert = true;

        // Retour
        return $alert;
    }

    // METIER : Contrôle alertes Calendars
    // RETOUR : Booléen
    function getAlerteCalendars()
    {
        // Appel physique
        $alert = physiqueAlerteSuppression('calendars');

        // Retour
        return $alert;
    }

    // METIER : Contrôle alertes Annexes
    // RETOUR : Booléen
    function getAlerteAnnexes()
    {
        // Appel physique
        $alert = physiqueAlerteSuppression('calendars_annexes');

        // Retour
        return $alert;
    }

    // METIER : Contrôle alertes Parcours
    // RETOUR : Booléen
    function getAlerteParcours()
    {
        // Appel physique
        $alert = physiqueAlerteSuppression('petits_pedestres_parcours');

        // Retour
        return $alert;
    }

    // METIER : Contrôle alertes CRON
    // RETOUR : Booléen
    function getAlerteCron()
    {
        // Initialisations
        $alert            = false;
        $logsJournalier   = array();
        $logsHebdomadaire = array();

        // Récupération fichiers journaliers et tri
        $dirJournalier = '../../cron/logs/daily';

        if (is_dir($dirJournalier))
        {
            // Récupération liste des fichiers journaliers par ordre décroissant
            $filesJournalier = scandir($dirJournalier, SCANDIR_SORT_DESCENDING);

            // Suppression des racines de dossier
            unset($filesJournalier[array_search('..', $filesJournalier)]);
            unset($filesJournalier[array_search('.', $filesJournalier)]);

            if (!empty($filesJournalier))
            {
                // Récupération du tri sur date et heure
                foreach ($filesJournalier as $fileJournalier)
                {
                    $triAnneeJournalier[]   = substr($fileJournalier, 12, 4);
                    $triMoisJournalier[]    = substr($fileJournalier, 9, 2);
                    $triJourJournalier[]    = substr($fileJournalier, 6, 2);
                    $triHeureJournalier[]   = substr($fileJournalier, 17, 2);
                    $triMinuteJournalier[]  = substr($fileJournalier, 20, 2);
                    $triSecondeJournalier[] = substr($fileJournalier, 23, 2);
                }

                // Tri
                array_multisort($triAnneeJournalier, SORT_DESC,
                                $triMoisJournalier, SORT_DESC,
                                $triJourJournalier, SORT_DESC,
                                $triHeureJournalier, SORT_DESC,
                                $triMinuteJournalier, SORT_DESC,
                                $triSecondeJournalier, SORT_DESC,
                                $filesJournalier);

                // Réinitialisation du tri
                unset($triAnneeJournalier);
                unset($triMoisJournalier);
                unset($triJourJournalier);
                unset($triHeureJournalier);
                unset($triMinuteJournalier);
                unset($triSecondeJournalier);                                
            }

            $logsJournalier = array_slice($filesJournalier, 0, 10);
        }

        // Récupération fichiers hebdomadaires et tri
        $dirHebdomadaire = '../../cron/logs/weekly';

        if (is_dir($dirHebdomadaire))
        {
            // Récupération fichiers hebdomadaires et tri
            $filesHebdomadaire = scandir($dirHebdomadaire, SCANDIR_SORT_DESCENDING);

            // Suppression des racines de dossier
            unset($filesHebdomadaire[array_search('..', $filesHebdomadaire)]);
            unset($filesHebdomadaire[array_search('.', $filesHebdomadaire)]);

            if (!empty($filesHebdomadaire))
            {
                // Récupération du tri sur date et heure
                foreach ($filesHebdomadaire as $fileHebdomadaire)
                {
                    $triAnneeHebdomadaire[]   = substr($fileHebdomadaire, 12, 4);
                    $triMoisHebdomadaire[]    = substr($fileHebdomadaire, 9, 2);
                    $triJourHebdomadaire[]    = substr($fileHebdomadaire, 6, 2);
                    $triHeureHebdomadaire[]   = substr($fileHebdomadaire, 17, 2);
                    $triMinuteHebdomadaire[]  = substr($fileHebdomadaire, 20, 2);
                    $triSecondeHebdomadaire[] = substr($fileHebdomadaire, 23, 2);
                }

                // Tri
                array_multisort($triAnneeHebdomadaire, SORT_DESC,
                                $triMoisHebdomadaire, SORT_DESC,
                                $triJourHebdomadaire, SORT_DESC,
                                $triHeureHebdomadaire, SORT_DESC,
                                $triMinuteHebdomadaire, SORT_DESC,
                                $triSecondeHebdomadaire, SORT_DESC,
                                $filesHebdomadaire);

                // Réinitialisation du tri
                unset($triAnneeHebdomadaire);
                unset($triMoisHebdomadaire);
                unset($triJourHebdomadaire);
                unset($triHeureHebdomadaire);
                unset($triMinuteHebdomadaire);
                unset($triSecondeHebdomadaire);
            }

            $logsHebdomadaire = array_slice($filesHebdomadaire, 0, 10);
        }

        // Vérification des logs journaliers
        foreach ($logsJournalier as $fileJ)
        {
            $lines = file('../../cron/logs/daily/' . $fileJ);

            if (substr($lines[6], 30, 2) == 'KO')
            {
                $alert = true;
                break;
            }
        }

        // Vérification des logs hebdomadaires
        foreach ($logsHebdomadaire as $fileH)
        {
            $lines = file('../../cron/logs/weekly/' . $fileH);

            if (substr($lines[6], 30, 2) == 'KO')
            {
                $alert = true;
                break;
            }
        }

        // Retour
        return $alert;
    }

    // METIER : Nombre de bugs / évolutions en attente
    // RETOUR : Nombre de bugs
    function getNombreBugsEvolutions($type)
    {
        // Appel physique
        $nombreBugsEvolutions = physiqueNombreBugsEvolutions($type);

        // Retour
        return $nombreBugsEvolutions;
    }

    // METIER : Sauvegarde de la base de données
    // RETOUR : Aucun
    function saveBdd()
    {
        // Appel extraction BDD
        $contenu = extractBdd(false);

        // Génération nom du fichier
        $fileName = 'inside_(' . date('d-m-Y') . '_' . date('H-i-s') . ')_' . rand(1, 11111111) . '.sql';

        // Génération du fichier
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: Binary');
        header('Content-disposition: attachment; filename="' . $fileName . '"');

        // Retour
        echo $contenu;
    }
?>