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
        foreach ($themes_users as $theme_users)
        {
          echo '<div class="zone_theme">';
            echo '<div class="zone_theme_infos">';
              // Header (Logo + header)
              echo '<div class="zone_header_theme">';
                if ($theme_users->getLogo() == "Y")
                  echo '<img src="../includes/images/themes/logos/' . $theme_users->getReference() . '_l.png" alt="' . $theme_users->getReference() . '_l" title="Logo" class="theme_logo" />';

                echo '<img src="../includes/images/themes/headers/' . $theme_users->getReference() . '_h.png" alt="' . $theme_users->getReference() . '_h" title="Header" class="theme_header_footer" />';
              echo '</div>';

              // Background
              echo '<img src="../includes/images/themes/backgrounds/' . $theme_users->getReference() . '.png" alt="' . $theme_users->getReference() . '" title="Background" class="theme_background" />';

              // Footer
              echo '<img src="../includes/images/themes/footers/' . $theme_users->getReference() . '_f.png" alt="' . $theme_users->getReference() . '_f" title="Footer" class="theme_header_footer" />';

              echo '<table class="theme_infos">';
                echo '<tr>';
                  // Nom
                  echo '<td class="theme_name">';
                    echo $theme_users->getName();
                  echo '</td>';

                  // Niveau
                  echo '<td class="theme_level">';
                    echo 'Niveau <span class="number_exp">' . $theme_users->getLevel() . '</span>';
                  echo '</td>';
                echo '</tr>';
              echo '</table>';
            echo '</div>';

            echo '<div class="zone_theme_actions">';
              // Bouton
              echo '<form method="post" action="profil.php?action=doModifierTheme" class="form_theme">';
                echo '<input type="hidden" name="id_theme" value="' . $theme_users->getId() . '" />';
                echo '<input type="submit" name="update_theme" value="Utiliser ce thème" class="bouton_theme" />';
              echo '</form>';

              // Aperçu
              if ($theme_users->getLogo() == "Y")
                echo '<a id="' . $theme_users->getReference() . '" class="bouton_apercu apercuTheme">Aperçu</a>';
              else
                echo '<a id="nologo_' . $theme_users->getReference() . '" class="bouton_apercu apercuTheme">Aperçu</a>';
            echo '</div>';
          echo '</div>';
        }
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
        foreach ($themes_missions as $theme_mission)
        {
          echo '<div class="zone_theme">';
            echo '<div class="zone_theme_infos">';
              // Header (Logo + header)
              echo '<div class="zone_header_theme">';
                if ($theme_mission->getLogo() == "Y")
                  echo '<img src="../includes/images/themes/logos/' . $theme_mission->getReference() . '_l.png" alt="' . $theme_mission->getReference() . '_l" title="Logo" class="theme_logo" />';

                echo '<img src="../includes/images/themes/headers/' . $theme_mission->getReference() . '_h.png" alt="' . $theme_mission->getReference() . '_h" title="Header" class="theme_header_footer" />';
              echo '</div>';

              // Background
              echo '<img src="../includes/images/themes/backgrounds/' . $theme_mission->getReference() . '.png" alt="' . $theme_mission->getReference() . '" title="Background" class="theme_background" />';

              // Footer
              echo '<img src="../includes/images/themes/footers/' . $theme_mission->getReference() . '_f.png" alt="' . $theme_mission->getReference() . '_f" title="Footer" class="theme_header_footer" />';

              // Nom mission
              echo '<div class="theme_name_mission">' . $theme_mission->getName() . '</div>';
            echo '</div>';

            echo '<div class="zone_theme_actions">';
              // Bouton
              echo '<form method="post" action="profil.php?action=doModifierTheme" class="form_theme">';
                echo '<input type="hidden" name="id_theme" value="' . $theme_mission->getId() . '" />';
                echo '<input type="submit" name="update_theme" value="Utiliser ce thème" class="bouton_theme" />';
              echo '</form>';

              // Aperçu
              if ($theme_mission->getLogo() == "Y")
                echo '<a id="' . $theme_mission->getReference() . '" class="bouton_apercu apercuTheme">Aperçu</a>';
              else
                echo '<a id="nologo_' . $theme_mission->getReference() . '" class="bouton_apercu apercuTheme">Aperçu</a>';
            echo '</div>';
          echo '</div>';
        }
      echo '</div>';
    }
    else
      echo '<div class="empty">Des thèmes seront bientôt disponibles !</div>';
  echo '</div>';
?>
