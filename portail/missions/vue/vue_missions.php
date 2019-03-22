<!DOCTYPE html>
<html>
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
          if (!empty($tabMissions))
          {
            $titre_en_cours = false;
            $titre_a_venir  = false;
            $titre_passees  = false;

            echo '<div class="zone_missions">';
              foreach ($tabMissions as $ligneMission)
              {
                // Missions future
                if ($ligneMission->getStatut() == "V")
                {
                  if ($titre_a_venir != true)
                  {
                    echo '<div class="titre_accueil_mission">Missions à venir</div>';
                    $titre_a_venir = true;
                  }

                  echo '<div class="zone_presentation_mission_default">';
                    echo '<img src="../../includes/icons/missions/default_mission.png" alt="default_mission" title="A venir" class="img_mission_default" />';
                    echo '<div class="titre_mission_default">Revenez pour une nouvelle mission à partir du ' . formatDateForDisplay($ligneMission->getDate_deb()) . ' à ' . formatTimeForDisplayLight($ligneMission->getHeure()) . ' !</div>';
                  echo '</div>';
                }
                // Missions en cours
                elseif ($ligneMission->getStatut() == "C")
                {
                  if ($titre_en_cours != true)
                  {
                    echo '<div class="titre_accueil_mission">Missions en cours</div>';
                    $titre_en_cours = true;
                  }

                  echo '<a href="details.php?id_mission=' . $ligneMission->getId() . '&view=mission&action=goConsulter" class="zone_presentation_mission_first">';
                    echo '<div class="titre_mission_first">' . $ligneMission->getMission() . '</div>';
                    echo '<img src="../../includes/images/missions/banners/' . $ligneMission->getReference() . '.png" alt="' . $ligneMission->getReference() . '" title="' . $ligneMission->getMission() . '" class="img_presentation_mission" />';
                  echo '</a>';
                }
                // Missions précédentes
                elseif ($ligneMission->getStatut() == "A")
                {
                  if ($titre_passees != true)
                  {
                    echo '<div class="titre_accueil_mission">Anciennes missions</div>';
                    $titre_passees = true;
                  }

                  echo '<a href="details.php?id_mission=' . $ligneMission->getId() . '&view=mission&action=goConsulter" class="zone_presentation_mission">';
                    echo '<div class="titre_mission">' . $ligneMission->getMission() . '</div>';
                    echo '<img src="../../includes/images/missions/banners/' . $ligneMission->getReference() . '.png" alt="' . $ligneMission->getReference() . '" title="' . $ligneMission->getMission() . '" class="img_presentation_mission" />';
                  echo '</a>';
                }
              }
            echo '</div>';
          }
          else
            echo '<div class="titre_accueil_mission">Pas encore de missions !</div>';
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
