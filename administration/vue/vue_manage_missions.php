<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "Missions";
      $style_head      = "styleAdmin.css";
      $script_head     = "scriptAdmin.js";
      $datepicker_head = true;
      $masonry_head    = true;

      include('../includes/common/head.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "Gestion missions";

        include('../includes/common/header.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu" class="aside_no_nav">
				<?php
          $add_mission = true;

					include('../includes/common/aside.php');
				?>
			</aside>

      <!-- Messages d'alerte -->
			<?php
				include('../includes/common/alerts.php');
			?>

			<article>
        <div class="zone_manage_missions">
          <?php
            switch ($_GET['action'])
            {
              case "goConsulter":
                include('vue/table_vue_missions.php');
                break;

              case "goAjouter":
              case "goModifier":
                include('vue/table_saisie_mission.php');
                break;

              default:
                break;
            }
          ?>
        </div>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
