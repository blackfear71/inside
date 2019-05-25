<?php
  echo '<div class="zone_bugs">';
    echo '<div class="titre_section"><img src="../includes/icons/reports/bug.png" alt="bug" class="logo_titre_section" />Bugs</div>';

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
              if (!empty($bug->getAvatar()))
                echo '<img src="../includes/images/profil/avatars/' . $bug->getAvatar() . '" alt="avatar" title="' . $bug->getPseudo() . '" class="avatar_report" />';
              else
                echo '<img src="../includes/icons/common/default.png" alt="avatar" title="' . $bug->getPseudo() . '" class="avatar_report" />';

              echo '<div class="pseudo_report">' . $bug->getPseudo() . '</div>';

              echo '<div class="date_report">';
                echo '<img src="../includes/icons/reports/date.png" alt="date" class="icone_report" />';
                echo formatDateForDisplay($bug->getDate());
              echo '</div>';

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
                echo '<a class="agrandirImage"><img src="../includes/images/reports/' . $bug->getPicture() . '" alt="' . $bug->getPicture() . '" class="image_report" /></a>';

              echo '<div class="content_report">' . nl2br($bug->getContent()) . '</div>';

              if (!empty($bug->getPicture()))
                echo '<div class="clear_report"></div>';
            echo '</div>';

            // Actions
            echo '<div class="zone_report_actions">';
              // Résoudre, rejeter ou remettre en cours
    					echo '<form method="post" action="reports.php?view=' . $_GET['view'] . '&id=' . $bug->getId() . '&action=doChangerStatut" class="form_manage_bug">';
    						if ($bug->getResolved() == "N")
                {
    							echo '<input type="submit" name="resolve_bug" value="Résoudre" class="saisie_bouton margin" />';
                  echo '<input type="submit" name="reject_bug" value="Rejeter" class="saisie_bouton margin" />';
                }
    						else
    							echo '<input type="submit" name="unresolve_bug" value="Remettre en cours" class="saisie_bouton margin" />';
    					echo '</form>';

              // Supprimer
              echo '<form id="delete_report_' . $bug->getId() . '" method="post" action="reports.php?view=' . $_GET['view'] . '&id=' . $bug->getId() . '&action=doSupprimer" class="form_manage_bug">';
                echo '<input type="submit" name="delete_bug" value="Supprimer" class="saisie_bouton margin eventConfirm" />';
                echo '<input type="hidden" value="Supprimer ce rapport ?" class="eventMessage" />';
              echo '</form>';
            echo '</div>';
          echo '</div>';
        echo '</div>';
      }
    }
    else
      echo '<div class="empty">Pas de bugs, tout va bien</div>';
  echo '</div>';

  echo '<div class="zone_evolutions">';
    echo '<div class="titre_section"><img src="../includes/icons/reports/evolution.png" alt="evolution" class="logo_titre_section" />Evolutions</div>';

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
              if (!empty($evolution->getAvatar()))
                echo '<img src="../includes/images/profil/avatars/' . $evolution->getAvatar() . '" alt="avatar" title="' . $evolution->getPseudo() . '" class="avatar_report" />';
              else
                echo '<img src="../includes/icons/common/default.png" alt="avatar" title="' . $evolution->getPseudo() . '" class="avatar_report" />';

              echo '<div class="pseudo_report">' . $evolution->getPseudo() . '</div>';

              echo '<div class="date_report">';
                echo '<img src="../includes/icons/reports/date.png" alt="date" class="icone_report" />';
                echo formatDateForDisplay($evolution->getDate());
              echo '</div>';

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
                echo '<a class="agrandirImage"><img src="../includes/images/reports/' . $evolution->getPicture() . '" alt="' . $evolution->getPicture() . '" class="image_report" /></a>';

              echo '<div class="content_report">' . nl2br($evolution->getContent()) . '</div>';

              if (!empty($evolution->getPicture()))
                echo '<div class="clear_report"></div>';
            echo '</div>';

            // Actions
            echo '<div class="zone_report_actions">';
              // Résoudre, rejeter ou remettre en cours
              echo '<form method="post" action="reports.php?view=' . $_GET['view'] . '&id=' . $evolution->getId() . '&action=doChangerStatut" class="form_manage_bug">';
                if ($evolution->getResolved() == "N")
                {
                  echo '<input type="submit" name="resolve_bug" value="Résoudre" class="saisie_bouton margin" />';
                  echo '<input type="submit" name="reject_bug" value="Rejeter" class="saisie_bouton margin" />';
                }
                else
                  echo '<input type="submit" name="unresolve_bug" value="Remettre en cours" class="saisie_bouton margin" />';
              echo '</form>';

              // Supprimer
              echo '<form id="delete_report_' . $evolution->getId() . '" method="post" action="reports.php?view=' . $_GET['view'] . '&id=' . $evolution->getId() . '&action=doSupprimer" class="form_manage_bug">';
                echo '<input type="submit" name="delete_bug" value="Supprimer" class="saisie_bouton margin eventConfirm" />';
                echo '<input type="hidden" value="Supprimer ce rapport ?" class="eventMessage" />';
              echo '</form>';
            echo '</div>';
          echo '</div>';
        echo '</div>';
      }
    }
    else
      echo '<div class="empty">Pas d\'évolutions proposées</div>';
  echo '</div>';
?>
