<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "Thèmes";
      $style_head      = "styleAdmin.css";
      $script_head     = "scriptAdmin.js";
      $datepicker_head = true;
      $masonry_head    = true;

      include('../includes/common/head.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "Gestion thèmes";

        include('../includes/common/header.php');
      ?>
		</header>

		<section>
			<!-- Messages d'alerte -->
			<?php
				include('../includes/common/alerts.php');
			?>

			<article>
        <?php
          /***********************/
          /* Thèmes utilisateurs */
          /***********************/
          echo '<div class="titre_section"><img src="../includes/icons/admin/users_grey.png" alt="users_grey" class="logo_titre_section" />Thèmes utilisateurs</div>';

          echo '<div class="zone_themes">';
            /***************************************/
            /* Saisie nouveau thème (utilisateurs) */
            /***************************************/
            echo '<form method="post" action="manage_themes.php?action=doAjouter" enctype="multipart/form-data" class="zone_theme">';
              echo '<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />';
              echo '<input type="hidden" name="theme_type" value="U" />';

              // Header
              echo '<div class="zone_parcourir_header">';
                echo '<div class="titre_saisie_header">Header</div>';
                echo '<input type="file" accept=".png" name="header" class="bouton_parcourir_header loadHeaderUsers" required />';
              echo '</div>';

              echo '<div class="mask_header">';
                echo '<img id="theme_header_users" alt="" class="img_header" />';
              echo '</div>';

              // Background
              echo '<div class="zone_parcourir_background">';
                echo '<div class="titre_saisie_background">Background</div>';
                echo '<input type="file" accept=".png" name="background" class="bouton_parcourir_background loadBackgroundUsers" required />';
              echo '</div>';

              echo '<div class="mask_background">';
                echo '<img id="theme_background_users" alt="" class="img_background" />';
              echo '</div>';

              // Footer
              echo '<div class="zone_parcourir_footer">';
                echo '<div class="titre_saisie_footer">Footer</div>';
                echo '<input type="file" accept=".png" name="footer" class="bouton_parcourir_footer loadFooterUsers" required />';
              echo '</div>';

              echo '<div class="mask_footer">';
                echo '<img id="theme_footer_users" alt="" class="img_footer" />';
              echo '</div>';

              echo '<div class="zone_theme_titre" style="margin-top: 8px; padding-top: 7px; padding-bottom: 7px;">';
                // Logo
                echo '<div class="zone_parcourir_logo">';
                  echo '<div class="titre_saisie_logo">Logo</div>';
                  echo '<input type="file" accept=".png" name="logo" class="bouton_parcourir_logo loadLogoUsers" />';
                echo '</div>';

                echo '<div class="mask_logo">';
                  echo '<img id="theme_logo_users" alt="" class="img_logo" />';
                echo '</div>';

                // Titre
                echo '<input type="text" name="theme_title" value="' . $_SESSION['save']['theme_title'] . '" placeholder="Titre" maxlength="255" class="saisie_titre_theme" required />';

                // Bouton validation
                echo '<input type="submit" name="insert_theme" value="Ajouter" class="saisie_theme_bouton" />';
              echo '</div>';

              // Référence
              echo '<div class="theme_ref_2">';
                echo '<input type="text" name="theme_ref" value="' . $_SESSION['save']['theme_ref'] . '" placeholder="Référence" maxlength="255" class="saisie_ref_theme" required />';
              echo '</div>';

              // Niveau
              echo '<div class="theme_level">';
                echo '<input type="text" name="theme_level" value="' . $_SESSION['save']['theme_level'] . '" placeholder="Niveau" maxlength="2" class="saisie_ref_theme" required />';
              echo '</div>';
            echo '</form>';

            /********************************************/
            /* Affichage thèmes existants (utilisateurs)*/
            /********************************************/
            if (!empty($themes_users))
            {
              foreach ($themes_users as $theme_users)
              {
                echo '<div class="zone_theme" id="' . $theme_users->getId() . '">';
                  // Images
                  echo '<div class="zone_header_theme">';
                    if ($theme_users->getLogo() == "Y")
                      echo '<img src="../includes/images/themes/logos/' . $theme_users->getReference() . '_l.png" alt="' . $theme_users->getReference() . '_l" title="Logo" class="theme_logo" />';

                    echo '<img src="../includes/images/themes/headers/' . $theme_users->getReference() . '_h.png" alt="' . $theme_users->getReference() . '_h" title="Header" class="theme_header_footer" />';
                  echo '</div>';

                  echo '<img src="../includes/images/themes/backgrounds/' . $theme_users->getReference() . '.png" alt="' . $theme_users->getReference() . '" title="Background" class="theme_background" />';
                  echo '<img src="../includes/images/themes/footers/' . $theme_users->getReference() . '_f.png" alt="' . $theme_users->getReference() . '_f" title="Footer" class="theme_header_footer" />';

                  /*********************************************/
                  /* Visualisation normale (sans modification) */
                  /*********************************************/
                  echo '<div id="modifier_theme_2_' . $theme_users->getId() . '">';
                    echo '<div class="zone_theme_titre">';
                      // Bouton suppression
                      echo '<form id="delete_theme_' . $theme_users->getId() . '" method="post" action="manage_themes.php?delete_id=' . $theme_users->getId() . '&action=doSupprimer">';
                        echo '<input type="submit" name="delete_theme" value="" title="Supprimer le thème" class="icon_delete_theme eventConfirm" />';
                        echo '<input type="hidden" value="Supprimer ce thème ?" class="eventMessage" />';
                      echo '</form>';

                      // Bouton modification
                      echo '<a id="theme_' . $theme_users->getId() . '" title="Modifier" class="icone_modify_theme modifierTheme"></a>';

                      // Titre
                      echo '<div class="theme_titre">';
                        echo $theme_users->getName();
                      echo '</div>';
                    echo '</div>';

                    // Référence
                    echo '<div class="theme_ref_2">' . $theme_users->getReference() . '</div>';

                    // Niveau
                    echo '<div class="theme_level">Niveau ' . $theme_users->getLevel() . '</div>';
                  echo '</div>';

                  /***************************/
                  /* Caché pour modification */
                  /***************************/
                  echo '<div id="modifier_theme_' . $theme_users->getId() . '" style="display: none;">';
                    echo '<form method="post" action="manage_themes.php?update_id=' . $theme_users->getId() . '&action=doModifier">';
                      echo '<div class="zone_theme_titre" style="padding-top: 7px; padding-bottom: 7px;">';
                        echo '<input type="hidden" name="theme_type" value="U" />';

                        // Annulation modification
                        echo '<a id="annuler_' . $theme_users->getId() . '" title="Annuler" class="icone_cancel_theme annulerTheme"></a>';

                        // Validation modification
                        echo '<input type="submit" name="modify_theme" value="" title="Valider" class="icon_validate_theme" />';

                        // Titre
                        echo '<input type="text" name="theme_title" value="' . $theme_users->getName() . '" placeholder="Titre" maxlength="255" class="modify_titre_theme" required />';
                      echo '</div>';

                      // Référence
                      echo '<div class="theme_ref_2">' . $theme_users->getReference() . '</div>';

                      // Niveau
                      echo '<div class="theme_level" style="padding-top: 7px; padding-bottom: 7px;">';
                        echo '<input type="text" name="theme_level" value="' . $theme_users->getLevel() . '" placeholder="Niveau" maxlength="2" class="saisie_ref_theme" required />';
                      echo '</div>';
                    echo '</form>';
                  echo '</div>';
                echo '</div>';
              }
            }
          echo '</div>';

          /*******************/
          /* Thèmes missions */
          /*******************/
          echo '<div class="titre_section"><img src="../includes/icons/admin/missions_grey.png" alt="missions_grey" class="logo_titre_section" />Thèmes missions</div>';

          echo '<div class="zone_themes">';
            /**********************************/
            /* Saisie nouveau thème (mission) */
            /**********************************/
            echo '<form method="post" action="manage_themes.php?action=doAjouter" enctype="multipart/form-data" class="zone_theme">';
              echo '<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />';
              echo '<input type="hidden" name="theme_type" value="M" />';
              echo '<input type="hidden" name="theme_level" value="" />';

              // Header
              echo '<div class="zone_parcourir_header">';
                echo '<div class="titre_saisie_header">Header</div>';
                echo '<input type="file" accept=".png" name="header" class="bouton_parcourir_header loadHeaderMission" required />';
              echo '</div>';

              echo '<div class="mask_header">';
                echo '<img id="theme_header_mission" alt="" class="img_header" />';
              echo '</div>';

              // Background
              echo '<div class="zone_parcourir_background">';
                echo '<div class="titre_saisie_background">Background</div>';
                echo '<input type="file" accept=".png" name="background" class="bouton_parcourir_background loadBackgroundMission" required />';
              echo '</div>';

              echo '<div class="mask_background">';
                echo '<img id="theme_background_mission" alt="" class="img_background" />';
              echo '</div>';

              // Footer
              echo '<div class="zone_parcourir_footer">';
                echo '<div class="titre_saisie_footer">Footer</div>';
                echo '<input type="file" accept=".png" name="footer" class="bouton_parcourir_footer loadFooterMission" required />';
              echo '</div>';

              echo '<div class="mask_footer">';
                echo '<img id="theme_footer_mission" alt="" class="img_footer" />';
              echo '</div>';

              echo '<div class="zone_theme_titre" style="margin-top: 8px; padding-top: 7px; padding-bottom: 7px;">';
                // Logo
                echo '<div class="zone_parcourir_logo">';
                  echo '<div class="titre_saisie_logo">Logo</div>';
                  echo '<input type="file" accept=".png" name="logo" class="bouton_parcourir_logo loadLogoMission" />';
                echo '</div>';

                echo '<div class="mask_logo">';
                  echo '<img id="theme_logo_mission" alt="" class="img_logo" />';
                echo '</div>';

                // Titre
                echo '<input type="text" name="theme_title" value="' . $_SESSION['save']['theme_title'] . '" placeholder="Titre" maxlength="255" class="saisie_titre_theme" required />';

                // Bouton validation
                echo '<input type="submit" name="insert_theme" value="Ajouter" class="saisie_theme_bouton" />';
              echo '</div>';

              // Référence
              echo '<div class="theme_ref" style="padding-top: 7px; padding-bottom: 7px;">';
                echo '<input type="text" name="theme_ref" value="' . $_SESSION['save']['theme_ref'] . '" placeholder="Référence" maxlength="255" class="saisie_ref_theme" required />';
              echo '</div>';

              // Dates de début et de fin
              echo '<div class="theme_dates" style="padding-top: 7px; padding-bottom: 7px;">';
                echo '<div class="theme_texte_dates">Du&nbsp;</div>';
                echo '<input type="text" name="theme_date_deb" value="' . $_SESSION['save']['theme_date_deb'] . '" placeholder="Date début" maxlength="10" autocomplete="off" id="datepicker_saisie_deb" class="saisie_date_theme" required />';
                echo '<div class="theme_texte_dates">&nbsp;au&nbsp;</div>';
                echo '<input type="text" name="theme_date_fin" value="' . $_SESSION['save']['theme_date_fin'] . '" placeholder="Date fin" maxlength="10" autocomplete="off" id="datepicker_saisie_fin" class="saisie_date_theme" required />';
              echo '</div>';
            echo '</form>';

            /****************************************/
            /* Affichage thèmes existants (missions)*/
            /****************************************/
            if (!empty($themes_missions))
            {
              foreach ($themes_missions as $theme_mission)
              {
                echo '<div class="zone_theme" id="' . $theme_mission->getId() . '">';
                  // Images
                  echo '<div class="zone_header_theme">';
                    if ($theme_mission->getLogo() == "Y")
                      echo '<img src="../includes/images/themes/logos/' . $theme_mission->getReference() . '_l.png" alt="' . $theme_mission->getReference() . '_l" title="Logo" class="theme_logo" />';

                    echo '<img src="../includes/images/themes/headers/' . $theme_mission->getReference() . '_h.png" alt="' . $theme_mission->getReference() . '_h" title="Header" class="theme_header_footer" />';
                  echo '</div>';

                  echo '<img src="../includes/images/themes/backgrounds/' . $theme_mission->getReference() . '.png" alt="' . $theme_mission->getReference() . '" title="Background" class="theme_background" />';
                  echo '<img src="../includes/images/themes/footers/' . $theme_mission->getReference() . '_f.png" alt="' . $theme_mission->getReference() . '_f" title="Footer" class="theme_header_footer" />';

                  /*********************************************/
                  /* Visualisation normale (sans modification) */
                  /*********************************************/
                  echo '<div id="modifier_theme_2_' . $theme_mission->getId() . '">';
                    echo '<div class="zone_theme_titre">';
                      // Bouton suppression
                      echo '<form id="delete_theme_' . $theme_mission->getId() . '" method="post" action="manage_themes.php?delete_id=' . $theme_mission->getId() . '&action=doSupprimer">';
                        echo '<input type="submit" name="delete_theme" value="" title="Supprimer le thème" class="icon_delete_theme eventConfirm" />';
                        echo '<input type="hidden" value="Supprimer ce thème ?" class="eventMessage" />';
                      echo '</form>';

                      // Bouton modification
                      echo '<a id="theme_' . $theme_mission->getId() . '" title="Modifier" class="icone_modify_theme modifierTheme"></a>';

                      // Titre
                      echo '<div class="theme_titre">';
                        echo $theme_mission->getName();
                      echo '</div>';
                    echo '</div>';

                    // Référence et dates
                    echo '<div class="theme_ref">' . $theme_mission->getReference() . '</div>';
                    echo '<div class="theme_dates">Du ' . formatDateForDisplay($theme_mission->getDate_deb()) . ' au ' . formatDateForDisplay($theme_mission->getDate_fin()) . '</div>';
                  echo '</div>';

                  /***************************/
                  /* Caché pour modification */
                  /***************************/
                  echo '<div id="modifier_theme_' . $theme_mission->getId() . '" style="display: none;">';
                    echo '<form method="post" action="manage_themes.php?update_id=' . $theme_mission->getId() . '&action=doModifier">';
                      echo '<div class="zone_theme_titre" style="padding-top: 7px; padding-bottom: 7px;">';
                        echo '<input type="hidden" name="theme_type" value="M" />';

                        // Annulation modification
                        echo '<a id="annuler_' . $theme_mission->getId() . '" title="Annuler" class="icone_cancel_theme annulerTheme"></a>';

                        // Validation modification
                        echo '<input type="submit" name="modify_theme" value="" title="Valider" class="icon_validate_theme" />';

                        // Titre
                        echo '<input type="text" name="theme_title" value="' . $theme_mission->getName() . '" placeholder="Titre" maxlength="255" class="modify_titre_theme" required />';
                      echo '</div>';

                      // Référence
                      echo '<div class="theme_ref">' . $theme_mission->getReference() . '</div>';

                      // Dates de début et de fin
                      echo '<div class="theme_dates" style="padding-top: 7px; padding-bottom: 7px;">';
                        echo '<div class="theme_texte_dates">Du&nbsp;</div>';
                        echo '<input type="text" name="theme_date_deb" value="' . formatDateForDisplay($theme_mission->getDate_deb()) . '" placeholder="Date début" maxlength="10" autocomplete="off" id="datepicker_mod_deb[' . $theme_mission->getId() . ']" class="modify_date_deb_theme" required />';
                        echo '<div class="theme_texte_dates">&nbsp;au&nbsp;</div>';
                        echo '<input type="text" name="theme_date_fin" value="' . formatDateForDisplay($theme_mission->getDate_fin()) . '" placeholder="Date fin" maxlength="10" autocomplete="off" id="datepicker_mod_fin[' . $theme_mission->getId() . ']" class="modify_date_fin_theme" required />';
                      echo '</div>';
                    echo '</form>';
                  echo '</div>';
                echo '</div>';
              }
            }
          echo '</div>';
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
