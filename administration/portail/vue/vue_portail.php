<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "Administration";
      $style_head      = "styleAdmin.css";
      $script_head     = "scriptAdmin.js";
      $angular_head    = false;
      $chat_head       = false;
      $datepicker_head = false;
      $masonry_head    = true;
      $exif_head       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
		<header>
      <?php
        $title = "Administration";

        include('../../includes/common/header.php');
      ?>
		</header>

    <!-- Contenu -->
		<section>
			<article>
        <?php
          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /***********/
          /* Portail */
          /***********/
          echo '<div class="menu_admin">';
            // Lien générés
            foreach ($portail as $lienPortail)
            {
              echo '<a href="' . $lienPortail['lien'] . '" class="menu_link_admin">';
                echo '<div class="menu_admin_box">';
                  echo '<div class="mask_admin"></div>';
                  echo '<div class="mask_admin_triangle"></div>';
                  echo '<div class="title_admin">';
                    echo '<div>' . $lienPortail['ligne_1'] . '</div>';
                    echo '<div class="margin_top_20">' . $lienPortail['ligne_2'] . '</div>';
                    echo '<div>' . $lienPortail['ligne_3'] . '</div>';
                  echo '</div>';
                echo '</div>';
              echo '</a>';
            }

            // Accès phpMyAdmin
            echo '<a href="/phpmyadmin/" target="_blank" class="menu_link_admin">';
  						echo '<div class="menu_admin_box">';
  							echo '<div class="mask_admin"></div>';
  							echo '<div class="mask_admin_triangle"></div>';
  							echo '<div class="title_admin">phpMyAdmin</div>';
  						echo '</div>';
  					echo '</a>';

            // Sauvegarde BDD
            echo '<form method="post" action="../../includes/functions/export_bdd.php" class="menu_link_admin">';
              echo '<div class="menu_admin_box">';
                echo '<div class="mask_admin"></div>';
                echo '<div class="mask_admin_triangle"></div>';
                echo '<input type="submit" name="export" value="Sauvegarde" class="export_bdd" />';
                echo '<div class="title_admin">';
                  echo '<div class="margin_top_60">BDD</div>';
                echo '</div>';
              echo '</div>';
            echo '</form>';
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
