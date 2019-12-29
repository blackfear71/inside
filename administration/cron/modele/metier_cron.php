<?php
  // METIER : Récupération des fichiers de log (10 derniers de chaque)
  // RETOUR : Tableau fichiers logs
  function getLastLogs()
  {
    $logsJ = array();
    $logsH = array();

    // Récupération fichiers journaliers et tri
    $dirJ   = '../../cron/logs/daily';

    if (is_dir($dirJ))
    {
      $filesJ = scandir($dirJ, 1);

      // Suppression racines de dossier
      unset($filesJ[array_search('..', $filesJ)]);
      unset($filesJ[array_search('.', $filesJ)]);

      if (!empty($filesJ))
      {
        // Tri sur date
        foreach ($filesJ as $fileJ)
        {
          $tri_anneeJ[]   = substr($fileJ, 12, 4);
          $tri_moisJ[]    = substr($fileJ, 9, 2);
          $tri_jourJ[]    = substr($fileJ, 6, 2);
          $tri_heureJ[]   = substr($fileJ, 17, 2);
          $tri_minuteJ[]  = substr($fileJ, 20, 2);
          $tri_secondeJ[] = substr($fileJ, 23, 2);
        }

        array_multisort($tri_anneeJ, SORT_DESC, $tri_moisJ, SORT_DESC, $tri_jourJ, SORT_DESC, $tri_heureJ, SORT_DESC, $tri_minuteJ, SORT_DESC, $tri_secondeJ, SORT_DESC, $filesJ);
      }

      $logsJ = array_slice($filesJ, 0, 10);
    }

    // Récupération fichiers hebdomadaires et tri
    $dirH   = '../../cron/logs/weekly';

    if (is_dir($dirH))
    {
      $filesH = scandir($dirH, 1);

      // Suppression racines de dossier
      unset($filesH[array_search('..', $filesH)]);
      unset($filesH[array_search('.', $filesH)]);

      if (!empty($filesH))
      {
        // Tri sur date
        foreach ($filesH as $fileH)
        {
          $tri_anneeH[]   = substr($fileH, 12, 4);
          $tri_moisH[]    = substr($fileH, 9, 2);
          $tri_jourH[]    = substr($fileH, 6, 2);
          $tri_heureH[]   = substr($fileH, 17, 2);
          $tri_minuteH[]  = substr($fileH, 20, 2);
          $tri_secondeH[] = substr($fileH, 23, 2);
        }

        array_multisort($tri_anneeH, SORT_DESC, $tri_moisH, SORT_DESC, $tri_jourH, SORT_DESC, $tri_heureH, SORT_DESC, $tri_minuteH, SORT_DESC, $tri_secondeH, SORT_DESC, $filesH);
      }

      $logsH = array_slice($filesH, 0, 10);
    }

    $files = array('daily' => $logsJ, 'weekly' => $logsH);

    return $files;
  }
?>
