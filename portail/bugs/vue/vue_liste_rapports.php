<?php
  echo '<div class="zone_bugs">';
    echo '<div class="titre_section"><img src="../../includes/icons/reports/bug.png" alt="bug" class="logo_titre_section" /><div class="texte_titre_section">Bugs</div></div>';

    if (!empty($listeBugs))
    {
      foreach ($listeBugs as $bug)
      {
        echo '<div class="zone_report">';
          echo '<div id="zone_shadow_' . $bug->getId() . '" class="zone_shadow">';
            // Titre
            echo '<div class="zone_report_top" id="' . $bug->getId() . '">';
              echo '<div class="zone_report_titre">';
                echo $bug->getSubject();
              echo '</div>';
            echo '</div>';

            // Infos
            echo '<div class="zone_report_middle">';
              // Avatar
              $avatarFormatted = formatAvatar($bug->getAvatar(), $bug->getPseudo(), 2, 'avatar');

              echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_report" />';

              // Pseudo
              echo '<div class="pseudo_report">' . formatUnknownUser($bug->getPseudo(), true, true) . '</div>';

              // Date
              echo '<div class="date_report">';
                echo '<img src="../../includes/icons/reports/date.png" alt="date" class="icone_report" />';
                echo formatDateForDisplay($bug->getDate());
              echo '</div>';

              // Statut
              switch ($bug->getResolved())
              {
                case 'Y':
                  echo '<div class="report_ended">Terminé</div>';
                  break;

                case 'R':
                  echo '<div class="report_in_progress">Rejeté</div>';
                  break;

                case 'N':
                default:
                  echo '<div class="report_in_progress">En cours</div>';
                  break;
              }
            echo '</div>';

            // Contenu
            echo '<div class="zone_report_bottom">';
              if (!empty($bug->getPicture()))
                echo '<a class="agrandirImage"><img src="../../includes/images/reports/' . $bug->getPicture() . '" alt="' . $bug->getPicture() . '" class="image_report" /></a>';

              echo '<div class="content_report">' . nl2br($bug->getContent()) . '</div>';

              if (!empty($bug->getPicture()))
                echo '<div class="clear_report"></div>';
            echo '</div>';
          echo '</div>';
        echo '</div>';
      }
    }
    else
      echo '<div class="empty">Pas de bugs, tout va bien...</div>';
  echo '</div>';

  echo '<div class="zone_evolutions">';
    echo '<div class="titre_section"><img src="../../includes/icons/reports/evolution.png" alt="evolution" class="logo_titre_section" /><div class="texte_titre_section">Evolutions</div></div>';

    if (!empty($listeEvolutions))
    {
      foreach ($listeEvolutions as $evolution)
      {
        echo '<div class="zone_report">';
          echo '<div id="zone_shadow_' . $evolution->getId() . '" class="zone_shadow">';
            // Titre
            echo '<div class="zone_report_top" id="' . $evolution->getId() . '">';
              echo '<div class="zone_report_titre">';
                echo $evolution->getSubject();
              echo '</div>';
            echo '</div>';

            // Infos
            echo '<div class="zone_report_middle">';
              // Avatar
              $avatarFormatted = formatAvatar($evolution->getAvatar(), $evolution->getPseudo(), 2, 'avatar');

              echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_report" />';

              // Pseudo
              echo '<div class="pseudo_report">' . formatUnknownUser($evolution->getPseudo(), true, true) . '</div>';

              // Date
              echo '<div class="date_report">';
                echo '<img src="../../includes/icons/reports/date.png" alt="date" class="icone_report" />';
                echo formatDateForDisplay($evolution->getDate());
              echo '</div>';

              // Statut
              switch ($evolution->getResolved())
              {
                case 'Y':
                  echo '<div class="report_ended">Terminée</div>';
                  break;

                case 'R':
                  echo '<div class="report_in_progress">Rejetée</div>';
                  break;

                case 'N':
                default:
                  echo '<div class="report_in_progress">En cours</div>';
                  break;
              }
            echo '</div>';

            // Contenu
            echo '<div class="zone_report_bottom">';
              if (!empty($evolution->getPicture()))
                echo '<a class="agrandirImage"><img src="../../includes/images/reports/' . $evolution->getPicture() . '" alt="' . $evolution->getPicture() . '" class="image_report" /></a>';

              echo '<div class="content_report">' . nl2br($evolution->getContent()) . '</div>';

              if (!empty($evolution->getPicture()))
                echo '<div class="clear_report"></div>';
            echo '</div>';
          echo '</div>';
        echo '</div>';
      }
    }
    else
      echo '<div class="empty">Pas d\'évolutions proposées...</div>';
  echo '</div>';
?>
