<?php
  // METIER : Récupération des fichiers de log (10 derniers de chaque)
  // RETOUR : Tableau fichiers logs
  function getLastLogs()
  {
    // Initialisations
    $logsJournalier   = array();
    $logsHebdomadaire = array();

    // Récupération fichiers journaliers et tri
    $dirJournalier= '../../cron/logs/daily';

    if (is_dir($dirJournalier))
    {
      // Récupération liste des fichiers journaliers par ordre décroissant
      $filesJournalier = scandir($dirJournalier, 1);

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
      }

      $logsJournalier = array_slice($filesJournalier, 0, 10);
    }

    // Récupération fichiers hebdomadaires et tri
    $dirHebdomadaire = '../../cron/logs/weekly';

    if (is_dir($dirHebdomadaire))
    {
      // Récupération fichiers hebdomadaires et tri
      $filesHebdomadaire = scandir($dirHebdomadaire, 1);

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
      }

      $logsHebdomadaire = array_slice($filesHebdomadaire, 0, 10);
    }

    // Ajout des logs au tableau
    $files = array('daily' => $logsJournalier, 'weekly' => $logsHebdomadaire);

    // Retour
    return $files;
  }
?>
