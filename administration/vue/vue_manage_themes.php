<!DOCTYPE html>
<html>
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head   = "Thèmes";
      $style_head   = "styleAdmin.css";
      $script_head  = "scriptAdmin.js";
      $masonry_head = true;

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
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu" class="aside_no_nav">
				<?php
					$disconnect = true;
					$back_admin = true;

					include('../includes/common/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../includes/common/alerts.php');
			?>

			<article>
        <?php
          echo '<div class="zone_themes">';
            /************************/
            /* Saisie nouveau thème */
            /************************/
            echo '<form method="post" action="manage_themes.php?action=doAjouter" enctype="multipart/form-data" class="zone_theme">';
              echo '<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />';

              // Header
              echo '<div class="zone_parcourir_header">';
                echo '<div class="titre_saisie_header">Header</div>';
                echo '<input type="file" accept=".png" name="header" class="bouton_parcourir_header" onchange="loadFile(event, \'theme_header\')" required />';
              echo '</div>';

              echo '<div class="mask_header">';
                echo '<img id="theme_header" alt="" class="img_header" />';
              echo '</div>';

              // Background
              echo '<div class="zone_parcourir_background">';
                echo '<div class="titre_saisie_background">Background</div>';
                echo '<input type="file" accept=".png" name="background" class="bouton_parcourir_background" onchange="loadFile(event, \'theme_background\')" required />';
              echo '</div>';

              echo '<div class="mask_background">';
                echo '<img id="theme_background" alt="" class="img_background" />';
              echo '</div>';

              // Footer
              echo '<div class="zone_parcourir_footer">';
                echo '<div class="titre_saisie_footer">Footer</div>';
                echo '<input type="file" accept=".png" name="footer" class="bouton_parcourir_footer" onchange="loadFile(event, \'theme_footer\')" required />';
              echo '</div>';

              echo '<div class="mask_footer">';
                echo '<img id="theme_footer" alt="" class="img_footer" />';
              echo '</div>';

              echo '<div class="zone_theme_titre" style="margin-top: 8px; padding-top: 7px; padding-bottom: 7px;">';
                // Logo
                echo '<div class="zone_parcourir_logo">';
                  echo '<div class="titre_saisie_logo">Logo</div>';
                  echo '<input type="file" accept=".png" name="logo" class="bouton_parcourir_logo" onchange="loadFile(event, \'theme_logo\')" />';
                echo '</div>';

                echo '<div class="mask_logo">';
                  echo '<img id="theme_logo" alt="" class="img_logo" />';
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
                echo '<input type="text" name="theme_date_deb" value="' . $_SESSION['save']['theme_date_deb'] . '" placeholder="Date début" maxlength="10" autocomplete="off" id="datepicker" class="saisie_date_theme" required />';
                echo '<div class="theme_texte_dates">&nbsp;au&nbsp;</div>';
                echo '<input type="text" name="theme_date_fin" value="' . $_SESSION['save']['theme_date_fin'] . '" placeholder="Date fin" maxlength="10" autocomplete="off" id="datepicker2" class="saisie_date_theme" required />';
              echo '</div>';
            echo '</form>';

            /******************************/
            /* Affichage thèmes existants */
            /******************************/
            if (!empty($themes))
            {
              foreach ($themes as $theme)
              {
                echo '<div class="zone_theme" id="' . $theme->getId() . '">';
                  // Images
                  echo '<div class="zone_header_theme">';
                    if ($theme->getLogo() == "Y")
                      echo '<img src="../includes/images/themes/logos/' . $theme->getReference() . '_l.png" alt="' . $theme->getReference() . '_l" title="Logo" class="theme_logo" />';

                    echo '<img src="../includes/images/themes/headers/' . $theme->getReference() . '_h.png" alt="' . $theme->getReference() . '_h" title="Header" class="theme_header_footer" />';
                  echo '</div>';

                  echo '<img src="../includes/images/themes/backgrounds/' . $theme->getReference() . '.png" alt="' . $theme->getReference() . '" title="Background" class="theme_background" />';
                  echo '<img src="../includes/images/themes/footers/' . $theme->getReference() . '_f.png" alt="' . $theme->getReference() . '_f" title="Footer" class="theme_header_footer" />';

                  /*********************************************/
                  /* Visualisation normale (sans modification) */
                  /*********************************************/
                  echo '<div id="modifier_theme_2[' . $theme->getId() . ']">';
                    echo '<div class="zone_theme_titre">';
                      // Bouton suppression
                      echo '<form method="post" action="manage_themes.php?delete_id=' . $theme->getId() . '&action=doSupprimer" onclick="if(!confirm(\'Supprimer ce thème ?\')) return false;">';
                        echo '<input type="submit" name="delete_theme" value="" title="Supprimer le thème" class="icon_delete_theme" />';
                      echo '</form>';

                      // Bouton modification
                      echo '<a onclick="afficherMasquer(\'modifier_theme[' . $theme->getId() . ']\'); afficherMasquer(\'modifier_theme_2[' . $theme->getId() . ']\'); initMasonry();" title="Modifier" class="icone_modify_theme"></a>';

                      // Titre
                      echo '<div class="theme_titre">';
                        echo $theme->getName();
                      echo '</div>';
                    echo '</div>';

                    // Référence et dates
                    echo '<div class="theme_ref">' . $theme->getReference() . '</div>';
                    echo '<div class="theme_dates">Du ' . formatDateForDisplay($theme->getDate_deb()) . ' au ' . formatDateForDisplay($theme->getDate_fin()) . '</div>';
                  echo '</div>';

                  /***************************/
                  /* Caché pour modification */
                  /***************************/
                  echo '<div id="modifier_theme[' . $theme->getId() . ']" style="display: none;">';
                    echo '<form method="post" action="manage_themes.php?update_id=' . $theme->getId() . '&action=doModifier">';
                      echo '<div class="zone_theme_titre" style="padding-top: 7px; padding-bottom: 7px;">';
                        // Annulation modification
                        echo '<a onclick="afficherMasquer(\'modifier_theme[' . $theme->getId() . ']\'); afficherMasquer(\'modifier_theme_2[' . $theme->getId() . ']\'); initMasonry();" title="Annuler" class="icone_cancel_theme"></a>';

                        // Validation modification
                        echo '<input type="submit" name="modify_theme" value="" title="Valider" class="icon_validate_theme" />';

                        // Titre
                        echo '<input type="text" name="theme_title" value="' . $theme->getName() . '" placeholder="Titre" maxlength="255" class="modify_titre_theme" required />';
                      echo '</div>';

                      // Référence
                      echo '<div class="theme_ref">' . $theme->getReference() . '</div>';

                      // Dates de début et de fin
                      echo '<div class="theme_dates" style="padding-top: 7px; padding-bottom: 7px;">';
                        echo '<div class="theme_texte_dates">Du&nbsp;</div>';
                        echo '<input type="text" name="theme_date_deb" value="' . formatDateForDisplay($theme->getDate_deb()) . '" placeholder="Date début" maxlength="10" autocomplete="off" id="datepicker_mod_deb[' . $theme->getId() . ']" class="modify_date_deb_theme" required />';
                        echo '<div class="theme_texte_dates">&nbsp;au&nbsp;</div>';
                        echo '<input type="text" name="theme_date_fin" value="' . formatDateForDisplay($theme->getDate_fin()) . '" placeholder="Date fin" maxlength="10" autocomplete="off" id="datepicker_mod_fin[' . $theme->getId() . ']" class="modify_date_fin_theme" required />';
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
