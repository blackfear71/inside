<!DOCTYPE html>
<html>
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "MI";
      $style_head  = "styleMI.css";
      $script_head = "";

      include($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/common.php');
    ?>
  </head>

	<body>
		<!-- Onglets -->
		<header>
      <?php
        $title = "Missions : Insider";

        include('../../includes/header.php');
			  include('../../includes/onglets.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect  = true;
					$back        = true;
					$ideas       = true;
					$reports     = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article>
        <?php
          if ($missionExistante == true)
          {
            // Switch entre mission et classement (seulement après la fin de mission)
            if (date('Ymd') > $detailsMission->getDate_fin())
            {
              echo '<div class="switch_view" style="margin-top: 30px;">';
                $listeSwitch = array('mission' => 'Mission',
                                     'ranking' => 'Classement',
                                    );

                foreach ($listeSwitch as $view => $lib_view)
                {
                  if ($_GET['view'] == $view)
                    $actif = 'active';
                  else
                    $actif = 'inactive';

                  echo '<a href="details.php?id_mission=' . $detailsMission->getId() . '&view=' . $view . '&action=goConsulter" class="zone_switch">';
                    echo '<div class="titre_switch_' . $actif . '">' . $lib_view . '</div>';
                    echo '<div class="border_switch_' . $actif . '"></div>';
                  echo '</a>';
                }
              echo '</div>';
            }

            switch ($_GET['view'])
            {
              case "ranking":
                include('vue/table_ranking.php');
                break;

              case "mission":
              default:
                include('vue/table_mission.php');
                break;
            }
          }
        ?>
			</article>

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
	</body>
</html>
