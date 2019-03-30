<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "MI";
      $style_head  = "styleMI.css";
      $script_head = "";
      $chat_head   = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Onglets -->
		<header>
      <?php
        $title = "Missions : Insider";

        include('../../includes/common/header.php');
			  include('../../includes/common/onglets.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu" class="aside_nav">
				<?php
					$disconnect  = true;
					$back        = true;
					$ideas       = true;
					$reports     = true;

					include('../../includes/common/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

			<article>
        <?php
          if ($missionExistante == true)
          {
            // Switch entre mission et classement (seulement après la fin de mission)
            if (date('Ymd') > $detailsMission->getDate_fin())
            {
              echo '<div class="switch_view">';
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
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
	</body>
</html>
