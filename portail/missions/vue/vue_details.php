<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="styleMI.css" />

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script type="text/javascript" src="/inside/script.js"></script>

		<title>Inside - MI</title>
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
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
	</body>
</html>
