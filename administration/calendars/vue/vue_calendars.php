<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "Calendriers";
      $style_head      = "styleAdmin.css";
      $script_head     = "scriptAdmin.js";
      $angular_head    = false;
      $chat_head       = false;
      $datepicker_head = false;
      $masonry_head    = false;
      $exif_head       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
		<header>
      <?php
        $title = "Gestion calendriers";

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

          /**********************************************/
          /* Formulaire autorisation saisie calendriers */
          /**********************************************/
          include('vue/vue_autorisations.php');

          /*******************************************************/
          /* Tableau des demandes de suppression des calendriers */
          /*******************************************************/
					include('vue/vue_table_calendars.php');

          /***************************************************/
          /* Tableau des demandes de suppression des annexes */
          /***************************************************/
          include('vue/vue_table_annexes.php');
				?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
