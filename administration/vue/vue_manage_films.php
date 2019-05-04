<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "Films";
      $style_head  = "styleAdmin.css";
      $script_head = "";

      include('../includes/common/head.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "Gestion films";

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
					// Tableau des demandes
					include('table_films.php');
				?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
