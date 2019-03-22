<!DOCTYPE html>
<html>
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
