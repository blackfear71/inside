<script>
  $(window).on('load', function()
  {
    console.log($('.numero_week'));
    console.log($('.numero_week').css('width'));
    console.log($('.numero_week').css('height'));
  });
</script>

<?php
  // Semaine en cours
  echo '<div class="zone_semaines_left">';
    echo '<div class="titre_section"><img src="../../includes/icons/cookingbox/cake.png" alt="cake" class="logo_titre_section" /><div class="texte_titre_section">Le gâteau de la semaine</div></div>';

    // Zone semaine courante
    echo '<div class="zone_semaine">';
      // Numéro semaine
      echo '<div class="numero_week">' . date("W") . '</div>';

      if (!empty($currentWeek->getIdentifiant()))
      {
        // Avatar
        if (!empty($currentWeek->getAvatar()))
          echo '<img src="../../includes/images/profil/avatars/' . $currentWeek->getAvatar() . '" alt="avatar" title="' . $currentWeek->getPseudo() . '" class="avatar_week" />';
        else
          echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $currentWeek->getPseudo() . '" class="avatar_week" />';

        // Pseudo
        if (strlen($currentWeek->getPseudo()) > 50)
          echo '<div class="pseudo_week">' . substr($currentWeek->getPseudo(), 0, 50) . '...</div>';
        else
          echo '<div class="pseudo_week">' . $currentWeek->getPseudo() . '</div>';

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
      echo '<div class="numero_week">' . date("W", strtotime('+ 1 week')) . '</div>';

      $week_next = date("W", strtotime('+ 1 week'));

      if (!empty($nextWeek->getIdentifiant()))
      {
        // Avatar
        if (!empty($nextWeek->getAvatar()))
          echo '<img src="../../includes/images/profil/avatars/' . $nextWeek->getAvatar() . '" alt="avatar" title="' . $nextWeek->getPseudo() . '" class="avatar_week" />';
        else
          echo '<img src="../../includes/icons/common/default.png" alt="avatar" title="' . $nextWeek->getPseudo() . '" class="avatar_week" />';

        // Pseudo
        if (strlen($nextWeek->getPseudo()) > 50)
          echo '<div class="pseudo_week">' . substr($nextWeek->getPseudo(), 0, 50) . '...</div>';
        else
          echo '<div class="pseudo_week">' . $nextWeek->getPseudo() . '</div>';

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
