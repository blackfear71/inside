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
          if (!empty($tabMissions))
          {
            $i             = 0;
            $titre_en_cours = false;
            $titre_a_venir  = false;
            $titre_passees  = false;

            echo '<div class="zone_missions">';
              foreach ($tabMissions as $ligneMission)
              {
                // Mission future
                if (date('Ymd') < $ligneMission->getDate_deb())
                {
                  if ($titre_a_venir != true)
                  {
                    echo '<div class="titre_accueil_mission">Mission à venir</div>';
                    $titre_a_venir = true;
                  }

                  echo '<div class="zone_presentation_mission_default">';
                    echo '<img src="../../includes/icons/default_mission.png" alt="default_mission" title="A venir" class="img_mission_default" />';
                    echo '<div class="titre_mission_default">Revenez pour une nouvelle mission à partir du ' . formatDateForDisplay($ligneMission->getDate_deb()) . ' !</div>';
                  echo '</div>';
                }
                // Mission en cours
                elseif (date('Ymd') >= $ligneMission->getDate_deb() AND date('Ymd') <= $ligneMission->getDate_fin())
                {
                  if ($titre_en_cours != true)
                  {
                    echo '<div class="titre_accueil_mission">Mission en cours</div>';
                    $titre_en_cours = true;
                  }

                  echo '<a href="details.php?id_mission=' . $ligneMission->getId() . '&action=goConsulter" class="zone_presentation_mission_first">';
                    echo '<div class="titre_mission_first">' . $ligneMission->getMission() . '</div>';
                    echo '<img src="images/' . $ligneMission->getReference() . '.png" alt="' . $ligneMission->getReference() . '" title="' . $ligneMission->getMission() . '" class="img_mission" />';
                  echo '</a>';
                }
                // Missions précédentes
                else
                {
                  if ($titre_passees != true)
                  {
                    echo '<div class="titre_accueil_mission">Anciennes missions</div>';
                    $titre_passees = true;
                  }

                  echo '<a href="details.php?id_mission=' . $ligneMission->getId() . '&action=goConsulter" class="zone_presentation_mission">';
                    echo '<div class="titre_mission">' . $ligneMission->getMission() . '</div>';
                    echo '<img src="images/' . $ligneMission->getReference() . '.png" alt="' . $ligneMission->getReference() . '" title="' . $ligneMission->getMission() . '" class="img_mission" />';
                  echo '</a href="">';
                }

                $i++;
              }
            echo '</div>';
          }
          else
          {
            echo '<div class="titre_accueil_mission">Pas encore de missions !</div>';
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
