<!DOCTYPE html>
<html>
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "Missions";
      $style_head  = "styleAdmin.css";
      $script_head = "scriptAdmin.js";

      include($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/common.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "Gestion missions";

        include('../includes/header.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect  = true;
          $add_mission = true;
					$back_admin  = true;

					include('../includes/aside.php');
				?>
			</aside>

      <!-- Messages d'alerte -->
			<?php
				include('../includes/alerts.php');
			?>

			<article>
        <div class="zone_manage_missions">
          <?php
            switch ($_GET['action'])
            {
              case "goConsulter":
                include('table_vue_missions.php');
                break;

              case "goAjouter":
              case "goModifier":
                include('table_saisie_mission.php');
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
			<?php include('../includes/footer.php'); ?>
		</footer>
  </body>
</html>
