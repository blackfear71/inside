<!DOCTYPE html>
<html>
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head        = "Thèmes";
      $style_head        = "styleAdmin.css";
      $script_head       = "scriptAdmin.js";
      $masonry_head      = true;
      $image_loaded_head = true;

      include($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/common.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "Gestion thèmes";

        include('../includes/header.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect = true;
					$back_admin = true;

					include('../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../includes/alerts.php');
			?>

			<article>
        <?php
          echo '<div class="zone_themes">';
            // Saisie nouveau thème
            echo '<form method="post" action="manage_themes.php?action=doAjouter" enctype="multipart/form-data" runat="server" class="zone_theme">';
              echo '<input type="hidden" name="MAX_FILE_SIZE" value="8388608" />';

              // Header
              echo '<div class="zone_parcourir_header">';
                echo '<div class="titre_saisie_header">Header</div>';
                echo '<input type="file" accept=".png" name="header" class="bouton_parcourir_header" onchange="loadFile(event, \'theme_header\')" required />';
              echo '</div>';

              echo '<div class="mask_header">';
                echo '<img id="theme_header" class="img_header" />';
              echo '</div>';

              // Background
              echo '<div class="zone_parcourir_background">';
                echo '<div class="titre_saisie_background">Background</div>';
                echo '<input type="file" accept=".png" name="background" class="bouton_parcourir_background" onchange="loadFile(event, \'theme_background\')" required />';
              echo '</div>';

              echo '<div class="mask_background">';
                echo '<img id="theme_background" class="img_background" />';
              echo '</div>';

              // Footer
              echo '<div class="zone_parcourir_footer">';
                echo '<div class="titre_saisie_footer">Footer</div>';
                echo '<input type="file" accept=".png" name="footer" class="bouton_parcourir_footer" onchange="loadFile(event, \'theme_footer\')" required />';
              echo '</div>';

              echo '<div class="mask_footer">';
                echo '<img id="theme_footer" class="img_footer" />';
              echo '</div>';

              echo '<div class="theme_titre" style="margin-top: 8px; padding-top: 7px; padding-bottom: 7px;">';
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
                echo 'Du ';
                echo '<input type="text" name="theme_date_deb" value="' . $_SESSION['save']['theme_date_deb'] . '" placeholder="Date début" maxlength="10" id="datepicker" class="saisie_date_theme" required />';
                echo ' au ';
                echo '<input type="text" name="theme_date_fin" value="' . $_SESSION['save']['theme_date_fin'] . '" placeholder="Date fin" maxlength="10" id="datepicker2" class="saisie_date_theme" required />';
              echo '</div>';
            echo '</form>';

            // Affichage thèmes existants
            if (!empty($themes))
            {
              foreach ($themes as $theme)
              {
                echo '<div class="zone_theme">';
                  echo '<img src="../includes/images/themes/headers/' . $theme->getReference() . '_h.png" alt="' . $theme->getReference() . '_h" title="Header" class="theme_header_footer" />';
                  echo '<img src="../includes/images/themes/backgrounds/' . $theme->getReference() . '.png" alt="' . $theme->getReference() . '" title="Background" class="theme_background" />';
                  echo '<img src="../includes/images/themes/footers/' . $theme->getReference() . '_f.png" alt="' . $theme->getReference() . '_f" title="Footer" class="theme_header_footer" />';

                  echo '<div class="theme_titre">' . $theme->getName() . '</div>';
                  echo '<div class="theme_ref">' . $theme->getReference() . '</div>';
                  echo '<div class="theme_dates">Du ' . formatDateForDisplay($theme->getDate_deb()) . ' au ' . formatDateForDisplay($theme->getDate_fin()) . '</div>';
                echo '</div>';
              }
            }
          echo '</div>';
        ?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>
  </body>
</html>
