<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Thèmes';
      $styleHead       = 'styleAdmin.css';
      $scriptHead      = 'scriptAdmin.js';
      $angularHead     = false;
      $chatHead        = false;
      $datepickerHead  = true;
      $masonryHead     = true;
      $exifHead        = false;
      $html2canvasHead = false;
      $jqueryCsv       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
		<header>
      <?php
        $title = 'Gestion thèmes';

        include('../../includes/common/header.php');
      ?>
		</header>

    <!-- Contenu -->
		<section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

			<article>
        <?php
          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /***********/
          /* Contenu */
          /***********/
          echo '<div class="zone_themes_admin" style="display: none;">';
            /***********************/
            /* Thèmes utilisateurs */
            /***********************/
            echo '<div class="titre_section"><img src="../../includes/icons/admin/users_grey.png" alt="users_grey" class="logo_titre_section" /><div class="texte_titre_section">Thèmes utilisateurs</div></div>';

            echo '<div class="zone_themes">';
              /***************************************/
              /* Saisie nouveau thème (utilisateurs) */
              /***************************************/
              echo '<form method="post" action="themes.php?action=doAjouter" enctype="multipart/form-data" class="zone_theme">';
                echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';
                echo '<input type="hidden" name="theme_type" value="U" />';

                // Header
                echo '<div class="zone_parcourir_header">';
                  echo '<div class="titre_saisie_header">Header (500 x 80 px)</div>';
                  echo '<input type="file" accept=".png" name="header" class="bouton_parcourir_header loadHeaderUsers" required />';
                echo '</div>';

                echo '<div class="mask_header">';
                  echo '<img id="theme_header_users" alt="" class="img_header" />';
                echo '</div>';

                // Background
                echo '<div class="zone_parcourir_background">';
                  echo '<div class="titre_saisie_background">Background (1920 x 1080 px)</div>';
                  echo '<input type="file" accept=".png" name="background" class="bouton_parcourir_background loadBackgroundUsers" required />';
                echo '</div>';

                echo '<div class="mask_background">';
                  echo '<img id="theme_background_users" alt="" class="img_background" />';
                echo '</div>';

                // Footer
                echo '<div class="zone_parcourir_footer">';
                  echo '<div class="titre_saisie_footer">Footer (500 x 50 px)</div>';
                  echo '<input type="file" accept=".png" name="footer" class="bouton_parcourir_footer loadFooterUsers" required />';
                echo '</div>';

                echo '<div class="mask_footer">';
                  echo '<img id="theme_footer_users" alt="" class="img_footer" />';
                echo '</div>';

                echo '<div class="zone_theme_saisie_titre">';
                  // Logo
                  echo '<div class="zone_parcourir_logo">';
                    echo '<div class="titre_saisie_logo">Logo (2000 x 2000 px)</div>';
                    echo '<input type="file" accept=".png" name="logo" class="bouton_parcourir_logo loadLogoUsers" />';
                  echo '</div>';

                  echo '<div class="mask_logo">';
                    echo '<img id="theme_logo_users" alt="" class="img_logo" />';
                  echo '</div>';

                  // Titre
                  echo '<input type="text" name="theme_title" value="' . $_SESSION['save']['theme_title'] . '" placeholder="Titre" maxlength="255" class="theme_titre_saisie" required />';

                  // Bouton validation
                  echo '<input type="submit" name="insert_theme" value="Ajouter" class="saisie_theme_bouton" />';
                echo '</div>';

                // Référence
                echo '<div class="theme_ref_user_saisie">';
                  echo '<input type="text" name="theme_ref" value="' . $_SESSION['save']['theme_ref'] . '" placeholder="Référence" maxlength="255" class="saisie_ref_theme" required />';
                echo '</div>';

                // Niveau
                echo '<div class="theme_level_user_saisie">';
                  echo '<input type="text" name="theme_level" value="' . $_SESSION['save']['theme_level'] . '" placeholder="Niveau" maxlength="2" class="saisie_ref_theme" required />';
                echo '</div>';
              echo '</form>';

              /********************************************/
              /* Affichage thèmes existants (utilisateurs)*/
              /********************************************/
              if (!empty($themesUsers))
              {
                foreach ($themesUsers as $themeUsers)
                {
                  echo '<div class="zone_theme" id="' . $themeUsers->getId() . '">';
                    // Images
                    echo '<div class="zone_header_theme">';
                      if ($themeUsers->getLogo() == 'Y')
                        echo '<img src="../../includes/images/themes/logos/' . $themeUsers->getReference() . '_l.png" alt="' . $themeUsers->getReference() . '_l" title="Logo" class="theme_logo" />';

                      echo '<img src="../../includes/images/themes/headers/' . $themeUsers->getReference() . '_h.png" alt="' . $themeUsers->getReference() . '_h" title="Header" class="theme_header_footer" />';
                    echo '</div>';

                    echo '<img src="../../includes/images/themes/backgrounds/' . $themeUsers->getReference() . '.png" alt="' . $themeUsers->getReference() . '" title="Background" class="theme_background" />';
                    echo '<img src="../../includes/images/themes/footers/' . $themeUsers->getReference() . '_f.png" alt="' . $themeUsers->getReference() . '_f" title="Footer" class="theme_header_footer" />';

                    /*********************************************/
                    /* Visualisation normale (sans modification) */
                    /*********************************************/
                    echo '<div id="modifier_theme_2_' . $themeUsers->getId() . '">';
                      echo '<div class="zone_theme_titre">';
                        // Bouton suppression
                        echo '<form id="delete_theme_' . $themeUsers->getId() . '" method="post" action="themes.php?action=doSupprimer">';
                          echo '<input type="hidden" name="id_theme" value="' . $themeUsers->getId() . '" />';
                          echo '<input type="submit" name="delete_theme" value="" title="Supprimer le thème" class="icon_delete_theme eventConfirm" />';
                          echo '<input type="hidden" value="Supprimer ce thème ?" class="eventMessage" />';
                        echo '</form>';

                        // Bouton modification
                        echo '<a id="theme_' . $themeUsers->getId() . '" title="Modifier" class="icone_update_theme modifierTheme"></a>';

                        // Titre
                        echo '<div class="theme_titre">';
                          echo $themeUsers->getName();
                        echo '</div>';
                      echo '</div>';

                      // Référence
                      echo '<div class="theme_ref_user">' . $themeUsers->getReference() . '</div>';

                      // Niveau
                      echo '<div class="theme_level">Niveau <span class="number_exp">' . $themeUsers->getLevel() . '</span></div>';
                    echo '</div>';

                    /***************************/
                    /* Caché pour modification */
                    /***************************/
                    echo '<div id="modifier_theme_' . $themeUsers->getId() . '" style="display: none;">';
                      echo '<form method="post" action="themes.php?action=doModifier">';
                        echo '<input type="hidden" name="id_theme" value="' . $themeUsers->getId() . '" />';

                        echo '<div class="zone_theme_modification_titre">';
                          echo '<input type="hidden" name="theme_type" value="U" />';

                          // Annulation modification
                          echo '<a id="annuler_theme_' . $themeUsers->getId() . '" title="Annuler" class="icone_cancel_theme annulerTheme"></a>';

                          // Validation modification
                          echo '<input type="submit" name="update_theme" value="" title="Valider" class="icon_validate_theme" />';

                          // Titre
                          echo '<input type="text" name="theme_title" value="' . $themeUsers->getName() . '" placeholder="Titre" maxlength="255" class="titre_theme_update" required />';
                        echo '</div>';

                        // Référence
                        echo '<div class="theme_ref_user">' . $themeUsers->getReference() . '</div>';

                        // Niveau
                        echo '<div class="theme_level_update">';
                          echo '<input type="text" name="theme_level" value="' . $themeUsers->getLevel() . '" placeholder="Niveau" maxlength="2" class="saisie_ref_theme" required />';
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
            echo '<div class="titre_section"><img src="../../includes/icons/admin/missions_grey.png" alt="missions_grey" class="logo_titre_section" /><div class="texte_titre_section">Thèmes missions</div></div>';

            echo '<div class="zone_themes">';
              /**********************************/
              /* Saisie nouveau thème (mission) */
              /**********************************/
              echo '<form method="post" action="themes.php?action=doAjouter" enctype="multipart/form-data" class="zone_theme">';
                echo '<input type="hidden" name="MAX_FILE_SIZE" value="15728640" />';
                echo '<input type="hidden" name="theme_type" value="M" />';
                echo '<input type="hidden" name="theme_level" value="" />';

                // Header
                echo '<div class="zone_parcourir_header">';
                  echo '<div class="titre_saisie_header">Header (500 x 80 px)</div>';
                  echo '<input type="file" accept=".png" name="header" class="bouton_parcourir_header loadHeaderMission" required />';
                echo '</div>';

                echo '<div class="mask_header">';
                  echo '<img id="theme_header_mission" alt="" class="img_header" />';
                echo '</div>';

                // Background
                echo '<div class="zone_parcourir_background">';
                  echo '<div class="titre_saisie_background">Background (1920 x 1080 px)</div>';
                  echo '<input type="file" accept=".png" name="background" class="bouton_parcourir_background loadBackgroundMission" required />';
                echo '</div>';

                echo '<div class="mask_background">';
                  echo '<img id="theme_background_mission" alt="" class="img_background" />';
                echo '</div>';

                // Footer
                echo '<div class="zone_parcourir_footer">';
                  echo '<div class="titre_saisie_footer">Footer (500 x 50 px)</div>';
                  echo '<input type="file" accept=".png" name="footer" class="bouton_parcourir_footer loadFooterMission" required />';
                echo '</div>';

                echo '<div class="mask_footer">';
                  echo '<img id="theme_footer_mission" alt="" class="img_footer" />';
                echo '</div>';

                echo '<div class="zone_theme_saisie_titre">';
                  // Logo
                  echo '<div class="zone_parcourir_logo">';
                    echo '<div class="titre_saisie_logo">Logo (2000 x 2000 px)</div>';
                    echo '<input type="file" accept=".png" name="logo" class="bouton_parcourir_logo loadLogoMission" />';
                  echo '</div>';

                  echo '<div class="mask_logo">';
                    echo '<img id="theme_logo_mission" alt="" class="img_logo" />';
                  echo '</div>';

                  // Titre
                  echo '<input type="text" name="theme_title" value="' . $_SESSION['save']['theme_title'] . '" placeholder="Titre" maxlength="255" class="theme_titre_saisie" required />';

                  // Bouton validation
                  echo '<input type="submit" name="insert_theme" value="Ajouter" class="saisie_theme_bouton" />';
                echo '</div>';

                // Référence
                echo '<div class="theme_ref_mission_saisie">';
                  echo '<input type="text" name="theme_ref" value="' . $_SESSION['save']['theme_ref'] . '" placeholder="Référence" maxlength="255" class="saisie_ref_theme" required />';
                echo '</div>';

                // Dates de début et de fin
                echo '<div class="theme_dates_mission_saisie">';
                  echo '<div class="theme_dates_update_texte">Du&nbsp;</div>';
                  echo '<input type="text" name="theme_date_deb" value="' . $_SESSION['save']['theme_date_deb'] . '" placeholder="Date début" maxlength="10" autocomplete="off" id="datepicker_saisie_deb" class="saisie_date_theme" required />';
                  echo '<div class="theme_dates_update_texte">&nbsp;au&nbsp;</div>';
                  echo '<input type="text" name="theme_date_fin" value="' . $_SESSION['save']['theme_date_fin'] . '" placeholder="Date fin" maxlength="10" autocomplete="off" id="datepicker_saisie_fin" class="saisie_date_theme" required />';
                echo '</div>';
              echo '</form>';

              /****************************************/
              /* Affichage thèmes existants (missions)*/
              /****************************************/
              if (!empty($themesMissions))
              {
                foreach ($themesMissions as $themeMission)
                {
                  echo '<div class="zone_theme" id="' . $themeMission->getId() . '">';
                    // Images
                    echo '<div class="zone_header_theme">';
                      if ($themeMission->getLogo() == 'Y')
                        echo '<img src="../../includes/images/themes/logos/' . $themeMission->getReference() . '_l.png" alt="' . $themeMission->getReference() . '_l" title="Logo" class="theme_logo" />';

                      echo '<img src="../../includes/images/themes/headers/' . $themeMission->getReference() . '_h.png" alt="' . $themeMission->getReference() . '_h" title="Header" class="theme_header_footer" />';
                    echo '</div>';

                    echo '<img src="../../includes/images/themes/backgrounds/' . $themeMission->getReference() . '.png" alt="' . $themeMission->getReference() . '" title="Background" class="theme_background" />';
                    echo '<img src="../../includes/images/themes/footers/' . $themeMission->getReference() . '_f.png" alt="' . $themeMission->getReference() . '_f" title="Footer" class="theme_header_footer" />';

                    /*********************************************/
                    /* Visualisation normale (sans modification) */
                    /*********************************************/
                    echo '<div id="modifier_theme_2_' . $themeMission->getId() . '">';
                      echo '<div class="zone_theme_titre">';
                        // Bouton suppression
                        echo '<form id="delete_theme_' . $themeMission->getId() . '" method="post" action="themes.php?action=doSupprimer">';
                          echo '<input type="hidden" name="id_theme" value="' . $themeMission->getId() . '" />';
                          echo '<input type="submit" name="delete_theme" value="" title="Supprimer le thème" class="icon_delete_theme eventConfirm" />';
                          echo '<input type="hidden" value="Supprimer ce thème ?" class="eventMessage" />';
                        echo '</form>';

                        // Bouton modification
                        echo '<a id="theme_' . $themeMission->getId() . '" title="Modifier" class="icone_update_theme modifierTheme"></a>';

                        // Titre
                        echo '<div class="theme_titre">';
                          echo $themeMission->getName();
                        echo '</div>';
                      echo '</div>';

                      // Référence et dates
                      echo '<div class="theme_ref_mission">' . $themeMission->getReference() . '</div>';

                      if ($themeMission->getDate_deb() != $themeMission->getDate_fin())
                        echo '<div class="theme_dates">Du ' . formatDateForDisplay($themeMission->getDate_deb()) . ' au ' . formatDateForDisplay($themeMission->getDate_fin()) . '</div>';
                      else
                        echo '<div class="theme_dates">Le ' . formatDateForDisplay($themeMission->getDate_deb()) . ' seulement</div>';
                    echo '</div>';

                    /***************************/
                    /* Caché pour modification */
                    /***************************/
                    echo '<div id="modifier_theme_' . $themeMission->getId() . '" style="display: none;">';
                      echo '<form method="post" action="themes.php?action=doModifier">';
                        echo '<input type="hidden" name="id_theme" value="' . $themeMission->getId() . '" />';

                        echo '<div class="zone_theme_modification_titre">';
                          echo '<input type="hidden" name="theme_type" value="M" />';

                          // Annulation modification
                          echo '<a id="annuler_theme_' . $themeMission->getId() . '" title="Annuler" class="icone_cancel_theme annulerTheme"></a>';

                          // Validation modification
                          echo '<input type="submit" name="update_theme" value="" title="Valider" class="icon_validate_theme" />';

                          // Titre
                          echo '<input type="text" name="theme_title" value="' . $themeMission->getName() . '" placeholder="Titre" maxlength="255" class="titre_theme_update" required />';
                        echo '</div>';

                        // Référence
                        echo '<div class="theme_ref_mission">' . $themeMission->getReference() . '</div>';

                        // Dates de début et de fin
                        echo '<div class="theme_dates_update">';
                          echo '<div class="theme_dates_update_texte">Du&nbsp;</div>';
                          echo '<input type="text" name="theme_date_deb" value="' . formatDateForDisplay($themeMission->getDate_deb()) . '" placeholder="Date début" maxlength="10" autocomplete="off" id="datepicker_mod_deb[' . $themeMission->getId() . ']" class="update_date_deb_theme" required />';
                          echo '<div class="theme_dates_update_texte">&nbsp;au&nbsp;</div>';
                          echo '<input type="text" name="theme_date_fin" value="' . formatDateForDisplay($themeMission->getDate_fin()) . '" placeholder="Date fin" maxlength="10" autocomplete="off" id="datepicker_mod_fin[' . $themeMission->getId() . ']" class="update_date_fin_theme" required />';
                        echo '</div>';
                      echo '</form>';
                    echo '</div>';
                  echo '</div>';
                }
              }
            echo '</div>';
          echo '</div>';
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
