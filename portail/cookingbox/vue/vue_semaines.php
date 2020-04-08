<?php
  // Semaine en cours
  echo '<div class="zone_semaines_left">';
    echo '<div class="titre_section"><img src="../../includes/icons/cookingbox/cake.png" alt="cake" class="logo_titre_section" /><div class="texte_titre_section">Le gâteau de la semaine</div></div>';

    // Zone semaine courante
    echo '<div class="zone_semaine">';
      // Numéro semaine
      echo '<div class="numero_week">' . formatWeekForDisplay(date("W")) . '</div>';

      if (!empty($currentWeek->getIdentifiant()))
      {
        // Avatar
        $avatarFormatted = formatAvatar($currentWeek->getAvatar(), $currentWeek->getPseudo(), 2, "avatar");

        echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_week" />';

        // Pseudo
        echo '<div class="pseudo_week">' . formatPseudo($currentWeek->getPseudo(), 50) . '</div>';

        // Boutons d'action
        echo '<div class="zone_boutons" id="zone_current_week">';
          if ($currentWeek->getCooked() == 'N')
          {
            echo '<div id="boutons_current_week">';
              echo '<a id="choix_semaine_courante_' . date("W") . '" class="bouton_semaine afficherUtilisateursCurrent">';
                echo 'Modifier';
              echo '</a>';

              if ($currentWeek->getIdentifiant() == $_SESSION['user']['identifiant'])
              {
                echo '<form method="post" action="cookingbox.php?year=' . $_GET["year"] . '&action=doValider">';
                  echo '<input type="hidden" name="week_cake" value="' . $currentWeek->getWeek() .  '" />';
                  echo '<input type="submit" name="validate_cake" value="Je l\'ai fait" class="bouton_semaine_2" />';
                echo '</form>';
              }
            echo '</div>';
          }
          else
          {
            echo '<div class="cake_done">Le gâteau a été fait pour cette semaine !</div>';

            if ($currentWeek->getIdentifiant() == $_SESSION['user']['identifiant'])
            {
              echo '<form method="post" action="cookingbox.php?year=' . $_GET["year"] . '&action=doAnnuler">';
                echo '<input type="hidden" name="week_cake" value="' . $currentWeek->getWeek() .  '" />';
                echo '<input type="submit" name="cancel_cake" value="Annuler" class="bouton_semaine_2" />';
              echo '</form>';
            }
          }
        echo '</div>';
      }
      else
      {
        echo '<div class="empty_week">';
          echo 'Encore personne d\'affecté...';
        echo '</div>';

        // Bouton d'action
        echo '<div class="zone_boutons_2" id="zone_current_week">';
          echo '<div id="boutons_current_week">';
            echo '<a id="choix_semaine_courante_' . date("W") . '" class="bouton_semaine afficherUtilisateursCurrent">';
              echo 'Modifier';
            echo '</a>';
          echo '</div>';
        echo '</div>';
      }
    echo '</div>';
  echo '</div>';

  // Semaine suivante
  echo '<div class="zone_semaines_right">';
    echo '<div class="titre_section"><img src="../../includes/icons/cookingbox/date_grey.png" alt="date_grey" class="logo_titre_section" /><div class="texte_titre_section">Pour la semaine prochaine</div></div>';
    // Zone semaine suivante
    echo '<div class="zone_semaine">';
      // Numéro semaine
      echo '<div class="numero_week">' . formatWeekForDisplay(date("W", strtotime('+ 1 week'))) . '</div>';

      $week_next = date("W", strtotime('+ 1 week'));

      if (!empty($nextWeek->getIdentifiant()))
      {
        // Avatar
        $avatarFormatted = formatAvatar($nextWeek->getAvatar(), $nextWeek->getPseudo(), 2, "avatar");

        echo '<img src="' . $avatarFormatted['path'] . '" alt="' . $avatarFormatted['alt'] . '" title="' . $avatarFormatted['title'] . '" class="avatar_week" />';

        // Pseudo
        echo '<div class="pseudo_week">' . formatUnknownUser(formatPseudo($nextWeek->getPseudo(), 50), true, true) . '</div>';

        // Bouton d'action
        echo '<div class="zone_boutons" id="zone_next_week">';
          echo '<div id="boutons_next_week">';
            echo '<a id="choix_semaine_suivante_' . $week_next . '" class="bouton_semaine afficherUtilisateursNext">';
              echo 'Modifier';
            echo '</a>';
          echo '</div>';
        echo '</div>';
      }
      else
      {
        echo '<div class="empty_week">';
          echo 'Encore personne d\'affecté...';
        echo '</div>';

        // Bouton d'action
        echo '<div class="zone_boutons_2" id="zone_next_week">';
          echo '<div id="boutons_next_week">';
            echo '<a id="choix_semaine_suivante_' . $week_next . '" class="bouton_semaine afficherUtilisateursNext">';
              echo 'Modifier';
            echo '</a>';
          echo '</div>';
        echo '</div>';
      }
    echo '</div>';
  echo '</div>';
?>
