<?php
  /**********/
  /* Thèmes */
  /**********/
  echo '<div class="zone_preferences_themes">';
    // Titre
    echo '<div id="titre_preferences_themes" class="titre_section">';
      echo '<img src="../../includes/icons/common/inside_grey.png" alt="inside_grey" class="logo_titre_section" />';
      echo '<div class="texte_titre_section_fleche">Profiter de vos thèmes</div>';
      echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
    echo '</div>';

    // Explications
    echo '<div id="afficher_preferences_themes">';
      echo '<div class="texte_themes">';
        echo 'Vous pouvez modifier ici votre thème qui sera <strong>appliqué à l\'ensemble du site</strong>, ainsi que la police de caractères utilisée.';
      echo '</div>';

      echo '<div class="texte_themes">';
        echo 'Vous avez le choix de sélectionner soit un thème débloqué par votre niveau <span class="niveau_themes">' . convertExperience($profil->getExperience()) . '</span> en accumulant de l\'expérience, soit un thème utilisé lors d\'une mission passée.';
      echo '</div>';

      echo '<div class="texte_themes texte_themes_italique">';
        echo 'Le meilleur moyen d\'accumuler de l\'expérience reste de faire vivre le site pour tous !';
      echo '</div>';

      echo '<div class="texte_themes">';
        echo 'Par défaut, si un thème a été défini sur une période donnée par l\'administrateur, <strong>celui-ci prévaudra</strong> sur votre préférence.';
      echo '</div>';

      if (!empty($preferences->getRef_theme()))
      {
        echo '<div class="texte_themes">';
          echo 'Vous pouvez également désactiver le thème courant (<strong>hors mission en cours</strong>) en cliquant sur ce bouton :';

          echo '<form method="post" action="profil.php?action=doSupprimerTheme">';
            echo '<input type="submit" name="delete_theme" value="Désactiver le thème" class="bouton_form_themes" />';
          echo '</form>';
        echo '</div>';
      }
    echo '</div>';
  echo '</div>';

  /***********/
  /* Polices */
  /***********/
  echo '<div class="zone_preferences_police">';
    // Titre
    echo '<div id="titre_preferences_police" class="titre_section">';
      echo '<img src="../../includes/icons/common/edit_grey.png" alt="edit_grey" class="logo_titre_section" />';
      echo '<div class="texte_titre_section_fleche">Polices de caractères</div>';
      echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
    echo '</div>';

    // Polices
    echo '<div id="afficher_preferences_police">';
      // Choix de la police
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

      // Exemple de texte
      echo '<div id="exemple_police" style="font-family: ' . $_SESSION['user']['font'] . ',Verdana, sans-serif;">';
        echo '<p><strong>Exemple de texte</strong></p>';

        echo '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec,
        ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi. Proin porttitor, orci nec
        nonummy molestie, enim est eleifend mi, non fermentum diam nisl sit amet erat. Duis semper. Duis arcu massa, scelerisque vitae, consequat in,
        pretium a, enim. Pellentesque congue. Ut in risus volutpat libero pharetra tempor. Cras vestibulum bibendum augue. Praesent egestas leo in pede.
        Praesent blandit odio eu enim. Pellentesque sed dui ut augue blandit sodales. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices
        posuere cubilia Curae; Aliquam nibh. Mauris ac mauris sed pede pellentesque fermentum. Maecenas adipiscing ante non diam sodales hendrerit.</p>';
      echo '</div>';
    echo '</div>';
  echo '</div>';

  /***************/
  /* Récompenses */
  /***************/
  echo '<div class="zone_preferences_themes">';
    // Titre
    echo '<div id="titre_preferences_recompenses" class="titre_section">';
      echo '<img src="../../includes/icons/profil/rewards_grey.png" alt="rewards_grey" class="logo_titre_section" />';
      echo '<div class="texte_titre_section_fleche">Mes récompenses</div>';
      echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
    echo '</div>';

    // Récompenses
    echo '<div id="afficher_preferences_recompenses">';      
      if (!empty($themesUsers))
      {
        echo '<div class="zone_themes">';
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
                  {
                    echo '<img src="../../includes/images/themes/logos/' . $themeUsers->getReference() . '_l.png" alt="' . $themeUsers->getReference() . '_l" title="Logo" class="theme_logo" />';
                    echo '<img src="../../includes/images/themes/headers/' . $themeUsers->getReference() . '_h.png" alt="' . $themeUsers->getReference() . '_h" title="Header" class="theme_header_logo" />';
                  }
                  else
                    echo '<img src="../../includes/images/themes/headers/' . $themeUsers->getReference() . '_h.png" alt="' . $themeUsers->getReference() . '_h" title="Header" class="theme_header_no_logo" />';
                echo '</div>';

                // Background
                echo '<img src="../../includes/images/themes/backgrounds/' . $themeUsers->getReference() . '.png" alt="' . $themeUsers->getReference() . '" title="Background" class="theme_background" />';

                // Footer
                echo '<img src="../../includes/images/themes/footers/' . $themeUsers->getReference() . '_f.png" alt="' . $themeUsers->getReference() . '_f" title="Footer" class="theme_footer" />';

                echo '<table class="theme_infos">';
                  echo '<tr>';
                    // Nom
                    echo '<td class="theme_name">';
                      echo $themeUsers->getName();
                    echo '</td>';

                    // Niveau
                    echo '<td class="theme_level">';
                      echo 'Niveau <span class="niveau_themes">' . $themeUsers->getLevel() . '</span>';
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
    echo '</div>';
  echo '</div>';

  /******************/
  /* Thèmes mission */
  /******************/
  echo '<div class="zone_preferences_themes">';
    // Titre
    echo '<div id="titre_preferences_themes_missions" class="titre_section">';
      echo '<img src="../../includes/icons/profil/missions_grey.png" alt="missions_grey" class="logo_titre_section" />';
      echo '<div class="texte_titre_section_fleche">Les thèmes des missions</div>';
      echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
    echo '</div>';

    // Thèmes des missions
    echo '<div id="afficher_preferences_themes_missions">';
      if (!empty($themesMissions))
      {
        echo '<div class="zone_themes">';
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
                  {
                    echo '<img src="../../includes/images/themes/logos/' . $themeMission->getReference() . '_l.png" alt="' . $themeMission->getReference() . '_l" title="Logo" class="theme_logo" />';
                    echo '<img src="../../includes/images/themes/headers/' . $themeMission->getReference() . '_h.png" alt="' . $themeMission->getReference() . '_h" title="Header" class="theme_header_logo" />';
                  }
                  else
                    echo '<img src="../../includes/images/themes/headers/' . $themeMission->getReference() . '_h.png" alt="' . $themeMission->getReference() . '_h" title="Header" class="theme_header_no_logo" />';
                echo '</div>';

                // Background
                echo '<img src="../../includes/images/themes/backgrounds/' . $themeMission->getReference() . '.png" alt="' . $themeMission->getReference() . '" title="Background" class="theme_background" />';

                // Footer
                echo '<img src="../../includes/images/themes/footers/' . $themeMission->getReference() . '_f.png" alt="' . $themeMission->getReference() . '_f" title="Footer" class="theme_footer" />';

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
  echo '</div>';
?>
