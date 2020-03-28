<?php
  // Journaux de modification
  echo '<div class="zone_changelog_right">';
    if (!empty($listeLogs))
    {
      foreach ($listeLogs as $log)
      {
        // Titre
        echo '<div class="titre_section"><img src="../../includes/icons/changelog/change_log_grey.png" alt="change_log_grey" class="logo_titre_section" /><div class="texte_titre_section">Nouveautés de la semaine ' . $log->getWeek() . '</div></div>';

        // Notes
        echo '<div class="notes_changelog">';
          echo $log->getNotes();
        echo '</div>';

        // Logs par catégories
        echo '<div class="zone_logs_semaine">';
          foreach ($log->getLogs() as $keyLogs => $logsCategorie)
          {
            echo '<div class="zone_logs_categorie">';
              // Titre catégorie
              echo '<div class="titre_categorie">';
                if (isset($categories[$keyLogs]))
                {
                  echo '<img src="../../includes/icons/changelog/' . $keyLogs . '_grey.png" alt="' . $keyLogs . '" class="logo_titre_categorie" />';
                  echo $categories[$keyLogs];
                }
                else
                  echo $keyLogs;
              echo '</div>';

              // Entrées
              echo '<ul class="logs_categorie">';
                foreach ($logsCategorie as $logCategorie)
                {
                  echo '<li>' . $logCategorie . '</li>';
                }
              echo '</ul>';
            echo '</div>';
          }
        echo '</div>';
      }
    }
    else
    {
      // Titre
      echo '<div class="titre_section"><img src="../../includes/icons/changelog/change_log_grey.png" alt="change_log_grey" class="logo_titre_section" /><div class="texte_titre_section">Journaux de modification</div></div>';

      echo '<div class="empty">Il n\'y a pas de journaux pour cette année...</div>';
    }
  echo '</div>';
?>
