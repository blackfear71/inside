<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Bugs';
      $styleHead       = 'styleBugs.css';
      $scriptHead      = 'scriptBugs.js';
      $angularHead     = false;
      $chatHead        = false;
      $datepickerHead  = false;
      $masonryHead     = false;
      $exifHead        = true;
      $html2canvasHead = false;
      $jqueryCsv       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
		<header>
      <?php
        $title = 'Rapports de bugs';

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

          /***************/
          /*** Onglets ***/
          /***************/
          include('vue/vue_onglets.php');

          /****************/
          /*** Rapports ***/
          /****************/
          include('vue/vue_liste_rapports.php');
				?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/web/footer.php'); ?>
		</footer>
  </body>
</html>
