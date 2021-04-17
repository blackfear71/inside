<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead      = 'Event Manager';
      $styleHead      = 'styleEM.css';
      $scriptHead     = '';
      $angularHead    = false;
      $chatHead       = true;
      $datepickerHead = false;
      $masonryHead    = false;
      $exifHead       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Entête -->
		<header>
      <?php
        $title = 'Event Manager';

        include('../../includes/common/header.php');
			  include('../../includes/common/onglets.php');
      ?>
		</header>

    <!-- Contenu -->
		<section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

      <!-- Déblocage succès -->
      <?php include('../../includes/common/success.php'); ?>

			<article>
        <?php
          /********************/
          /* Boutons missions */
          /********************/
          $zoneInside = 'article';
          include('../../includes/common/missions.php');

          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          echo '<div class="entete_event">';
            echo 'Les évènements à venir';
          echo '</div>';

          echo '<div class="entete_event">';
            echo 'Tous les évènements';
          echo '</div>';

          echo '<div class="entete_event">';
            echo 'Le référentiel';
          echo '</div>';
        ?>
			</article>

      <!-- Chat -->
      <?php include('../../includes/common/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
