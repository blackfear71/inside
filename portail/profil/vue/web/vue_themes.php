<?php
  /***********/
  /* Polices */
  /***********/
  echo '<div class="titre_section"><img src="../../includes/icons/common/edit_grey.png" alt="edit_grey" class="logo_titre_section" /><div class="texte_titre_section">Polices de caractères</div></div>';

  echo '<form method="post" action="profil.php?action=doModifierPolice" class="form_update_police">';
    echo '<select id="select_police" name="police" class="select_update_police" required>';
      foreach ($policesCaracteres as $police)
      {
        if ($police == $_SESSION['user']['font'])
          echo '<option value="' . $police . '" selected>' . $police . '</option>';
        else
          echo '<option value="' . $police . '">' . $police . '</option>';
      }
    echo '</select>';

    echo '<input type="submit" name="update_font" value="Mettre à jour la police" class="bouton_form_police" />';
  echo '</form>';

  echo '<div id="exemple_police" style="font-family: ' . $_SESSION['user']['font'] . ',Verdana, sans-serif;">';
    echo '<p><strong>Exemple de texte</strong></p>';

    echo '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec,
    ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi. Proin porttitor, orci nec
    nonummy molestie, enim est eleifend mi, non fermentum diam nisl sit amet erat. Duis semper. Duis arcu massa, scelerisque vitae, consequat in,
    pretium a, enim. Pellentesque congue. Ut in risus volutpat libero pharetra tempor. Cras vestibulum bibendum augue. Praesent egestas leo in pede.
    Praesent blandit odio eu enim. Pellentesque sed dui ut augue blandit sodales. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices
    posuere cubilia Curae; Aliquam nibh. Mauris ac mauris sed pede pellentesque fermentum. Maecenas adipiscing ante non diam sodales hendrerit.</p>';
  echo '</div>';

  /**********/
  /* Thèmes */
  /**********/
  echo '<div class="zone_themes_user" style="display: none;">';
    // Récompenses de niveaux
    echo '<div class="titre_section"><img src="../../includes/icons/profil/rewards_grey.png" alt="rewards_grey" class="logo_titre_section" />';
      echo '<div class="texte_titre_section">';
        echo 'Mes récompenses';

        if (!empty($themesUsers))
        {
          echo '<div class="zone_actions">';
            echo '<a id="fold_themes_user" class="bouton_fold">Plier</a>';
          echo '</div>';
        }
      echo '</div>';
    echo '</div>';

    if (!empty($themesUsers))
    {
      echo '<div id="themes_user">';
        foreach ($themesUsers as $themeUsers)
        {
          echo '<div class="zone_theme">';
            echo '<div class="zone_theme_infos">';
              // Indicateur sélection
              if ($isThemeMission != true AND $preferences->getRef_theme() == $themeUsers->getReference())
                echo '<div class="selection_theme">Sélectionné</div>';

              // Header (Logo + header)
              echo '<div class="zone_header_theme">';
                if ($themeUsers->getLogo() == 'Y')
                  echo '<img src="../../includes/images/themes/logos/' . $themeUsers->getReference() . '_l.png" alt="' . $themeUsers->getReference() . '_l" title="Logo" class="theme_logo" />';

                echo '<img src="../../includes/images/themes/headers/' . $themeUsers->getReference() . '_h.png" alt="' . $themeUsers->getReference() . '_h" title="Header" class="theme_header_footer" />';
              echo '</div>';

              // Background
              echo '<img src="../../includes/images/themes/backgrounds/' . $themeUsers->getReference() . '.png" alt="' . $themeUsers->getReference() . '" title="Background" class="theme_background" />';

              // Footer
              echo '<img src="../../includes/images/themes/footers/' . $themeUsers->getReference() . '_f.png" alt="' . $themeUsers->getReference() . '_f" title="Footer" class="theme_header_footer" />';

              echo '<table class="theme_infos">';
                echo '<tr>';
                  // Nom
                  echo '<td class="theme_name">';
                    echo $themeUsers->getName();
                  echo '</td>';

                  // Niveau
                  echo '<td class="theme_level">';
                    echo 'Niveau <span class="number_exp">' . $themeUsers->getLevel() . '</span>';
                  echo '</td>';
                echo '</tr>';
              echo '</table>';
            echo '</div>';

            echo '<div class="zone_theme_actions">';
              // Bouton
              echo '<form method="post" action="profil.php?action=doModifierTheme" class="form_theme">';
                echo '<input type="hidden" name="id_theme" value="' . $themeUsers->getId() . '" />';
                echo '<input type="submit" name="update_theme" value="Utiliser ce thème" class="bouton_theme" />';
              echo '</form>';

              // Aperçu
              if ($themeUsers->getLogo() == 'Y')
                echo '<a id="' . $themeUsers->getReference() . '" class="bouton_apercu apercuTheme">Aperçu</a>';
              else
                echo '<a id="nologo_' . $themeUsers->getReference() . '" class="bouton_apercu apercuTheme">Aperçu</a>';
            echo '</div>';
          echo '</div>';
        }
      echo '</div>';
    }
    else
      echo '<div class="empty">Pas de thèmes disponibles, gagnez un peu d\'expérience...</div>';

    // Thèmes de missions
    echo '<div class="titre_section"><img src="../../includes/icons/profil/missions_grey.png" alt="missions_grey" class="logo_titre_section" />';
      echo '<div class="texte_titre_section">';
        echo 'Les thèmes de missions';

        if (!empty($themesMissions))
        {
          echo '<div class="zone_actions">';
            echo '<a id="fold_themes_missions" class="bouton_fold">Plier</a>';
          echo '</div>';
        }
      echo '</div>';
    echo '</div>';

    if (!empty($themesMissions))
    {
      echo '<div id="themes_missions">';
        foreach ($themesMissions as $themeMission)
        {
          echo '<div class="zone_theme">';
            echo '<div class="zone_theme_infos">';
              // Indicateur sélection
              if ($isThemeMission != true AND $preferences->getRef_theme() == $themeMission->getReference())
                echo '<div class="selection_theme">Sélectionné</div>';

              // Indicateur sélection (mission)
              if ($isThemeMission == true AND date('Ymd') >= $themeMission->getDate_deb() AND date('Ymd') <= $themeMission->getDate_fin())
                echo '<div class="selection_theme">Mission en cours</div>';

              // Header (Logo + header)
              echo '<div class="zone_header_theme">';
                if ($themeMission->getLogo() == 'Y')
                  echo '<img src="../../includes/images/themes/logos/' . $themeMission->getReference() . '_l.png" alt="' . $themeMission->getReference() . '_l" title="Logo" class="theme_logo" />';

                echo '<img src="../../includes/images/themes/headers/' . $themeMission->getReference() . '_h.png" alt="' . $themeMission->getReference() . '_h" title="Header" class="theme_header_footer" />';
              echo '</div>';

              // Background
              echo '<img src="../../includes/images/themes/backgrounds/' . $themeMission->getReference() . '.png" alt="' . $themeMission->getReference() . '" title="Background" class="theme_background" />';

              // Footer
              echo '<img src="../../includes/images/themes/footers/' . $themeMission->getReference() . '_f.png" alt="' . $themeMission->getReference() . '_f" title="Footer" class="theme_header_footer" />';

              // Nom mission
              echo '<div class="theme_name_mission">' . $themeMission->getName() . '</div>';
            echo '</div>';

            echo '<div class="zone_theme_actions">';
              // Bouton
              echo '<form method="post" action="profil.php?action=doModifierTheme" class="form_theme">';
                echo '<input type="hidden" name="id_theme" value="' . $themeMission->getId() . '" />';
                echo '<input type="submit" name="update_theme" value="Utiliser ce thème" class="bouton_theme" />';
              echo '</form>';

              // Aperçu
              if ($themeMission->getLogo() == 'Y')
                echo '<a id="' . $themeMission->getReference() . '" class="bouton_apercu apercuTheme">Aperçu</a>';
              else
                echo '<a id="nologo_' . $themeMission->getReference() . '" class="bouton_apercu apercuTheme">Aperçu</a>';
            echo '</div>';
          echo '</div>';
        }
      echo '</div>';
    }
    else
      echo '<div class="empty">Des thèmes seront bientôt disponibles...</div>';
  echo '</div>';
?>
