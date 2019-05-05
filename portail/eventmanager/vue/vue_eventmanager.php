<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "EM";
      $style_head  = "styleEM.css";
      $script_head = "";
      $chat_head   = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Onglets -->
		<header>
      <?php
        $title = "Event Manager";

        include('../../includes/common/header.php');
			  include('../../includes/common/onglets.php');
      ?>
		</header>

		<section>
			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

			<article>
        <?php
          // Boutons missions
          $zone_inside = "article";
          include($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/common/missions.php');

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

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
