<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Calendriers';
      $styleHead       = 'styleAdmin.css';
      $scriptHead      = 'scriptAdmin.js';
      $angularHead     = false;
      $chatHead        = false;
      $datepickerHead  = false;
      $masonryHead     = true;
      $exifHead        = false;
      $html2canvasHead = false;
      $jqueryCsv       = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
		<header>
      <?php
        $title = 'Gestion calendriers';

        include('../../includes/common/web/header.php');
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

          echo '<div class="zone_calendriers_admin" style="display: none;">';
            /**********************************************/
            /* Formulaire autorisation saisie calendriers */
            /**********************************************/
            include('vue/vue_autorisations.php');

            /********************************************/
            /* Formulaire création périodes de vacances */
            /********************************************/
            include('vue/vue_vacances.php');

            /*******************************************************/
            /* Tableau des demandes de suppression des calendriers */
            /*******************************************************/
            include('vue/vue_table_calendars.php');

            /***************************************************/
            /* Tableau des demandes de suppression des annexes */
            /***************************************************/
            include('vue/vue_table_annexes.php');
          echo '</div>';
				?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/web/footer.php'); ?>
		</footer>
  </body>
</html>
