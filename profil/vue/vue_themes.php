<?php
  echo '<div class="zone_themes_user" style="display: none;">';
    // Récompenses de niveaux
    echo '<div class="titre_section"><img src="../includes/icons/profil/rewards_grey.png" alt="rewards_grey" class="logo_titre_section" />';
      echo 'Mes récompenses';

      if (!empty($themes_users))
      {
        echo '<div class="zone_actions">';
          echo '<a id="fold_themes_user" class="bouton_fold">Plier</a>';
        echo '</div>';
      }
    echo '</div>';

    if (!empty($themes_users))
    {
      echo '<div id="themes_user">';
        var_dump($themes_users);
      echo '</div>';
    }
    else
      echo '<div class="empty">Pas de thèmes disponibles. Gagnez un peu d\'expérience !</div>';

    // Thèmes de missions
    echo '<div class="titre_section"><img src="../includes/icons/profil/missions_grey.png" alt="missions_grey" class="logo_titre_section" />';
      echo 'Les thèmes de missions';

      if (!empty($themes_missions))
      {
        echo '<div class="zone_actions">';
          echo '<a id="fold_themes_missions" class="bouton_fold">Plier</a>';
        echo '</div>';
      }
    echo '</div>';

    if (!empty($themes_missions))
    {
      echo '<div id="themes_missions">';
        var_dump($themes_missions);
      echo '</div>';
    }
    else
      echo '<div class="empty">Des thèmes seront bientôt disponibles !</div>';
  echo '</div>';
?>
