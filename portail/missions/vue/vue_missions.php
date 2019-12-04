<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head   = "MI";
      $style_head   = "styleMI.css";
      $script_head  = "scriptMI.js";
      $chat_head    = true;
      $masonry_head = true;

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
			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

			<article>
        <?php
          // Boutons missions
          $zone_inside = "article";
          include('../../includes/common/missions.php');

          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';
          
          /**********************/
          /* Liste des missions */
          /**********************/
          if (!empty($tabMissions))
          {
            $titre_en_cours = false;
            $titre_a_venir  = false;
            $titre_passees  = false;

            echo '<div class="zone_missions">';
              foreach ($tabMissions as $keyMission => $ligneMission)
              {
                // Missions future
                if ($ligneMission->getStatut() == "V")
                {
                  if ($titre_a_venir != true)
                  {
                    echo '<div class="titre_section"><img src="../../includes/icons/missions/missions_to_come.png" alt="missions_to_come" class="logo_titre_section" /><div class="texte_titre_section">Missions à venir</div></div>';
                    $titre_a_venir = true;
                  }

                  echo '<div class="zone_mission_default">';
                    echo '<img src="../../includes/icons/missions/default_mission.png" alt="default_mission" title="A venir" class="image_mission_default" />';
                    echo '<div class="titre_mission_default">Revenez pour une nouvelle mission à partir du ' . formatDateForDisplay($ligneMission->getDate_deb()) . ' à ' . formatTimeForDisplayLight($ligneMission->getHeure()) . ' !</div>';
                  echo '</div>';
                }
                // Missions en cours
                elseif ($ligneMission->getStatut() == "C")
                {
                  if ($titre_en_cours != true)
                  {
                    echo '<div class="titre_section"><img src="../../includes/icons/missions/missions_in_progress.png" alt="missions_in_progress" class="logo_titre_section" /><div class="texte_titre_section">Missions en cours</div></div>';
                    $titre_en_cours = true;

                    // Définit une zone pour appliquer la Masonry
                    echo '<div class="zone_missions_accueil">';
                  }

                  echo '<a href="details.php?id_mission=' . $ligneMission->getId() . '&action=goConsulter" class="zone_mission_accueil">';
                    echo '<img src="../../includes/images/missions/banners/' . $ligneMission->getReference() . '.png" alt="' . $ligneMission->getReference() . '" title="' . $ligneMission->getMission() . '" class="image_mission_accueil" />';
                    echo '<div class="titre_mission_accueil">' . $ligneMission->getMission() . '</div>';
                  echo '</a>';
                }
                // Missions précédentes
                elseif ($ligneMission->getStatut() == "A")
                {
                  if ($titre_passees != true)
                  {
                    echo '<div class="titre_section"><img src="../../includes/icons/missions/missions_ended.png" alt="missions_ended" class="logo_titre_section" /><div class="texte_titre_section">Anciennes missions</div></div>';
                    $titre_passees = true;

                    // Définit une zone pour appliquer la Masonry
                    echo '<div class="zone_missions_accueil">';
                  }

                  echo '<a href="details.php?id_mission=' . $ligneMission->getId() . '&action=goConsulter" class="zone_mission_accueil">';
                    echo '<img src="../../includes/images/missions/banners/' . $ligneMission->getReference() . '.png" alt="' . $ligneMission->getReference() . '" title="' . $ligneMission->getMission() . '" class="image_mission_accueil" />';
                    echo '<div class="titre_mission_accueil">' . $ligneMission->getMission() . '</div>';
                  echo '</a>';
                }

                if  ($ligneMission->getStatut() != "V"
                AND (!isset($tabMissions[$keyMission + 1])
                OR   $ligneMission->getStatut() != $tabMissions[$keyMission + 1]->getStatut()))
                {
                  // Termine la zone Masonry du niveau
                  echo '</div>';
                }
              }
            echo '</div>';
          }
          else
          {
            echo '<div class="titre_section"><img src="../../includes/icons/missions/missions_in_progress.png" alt="missions_in_progress" class="logo_titre_section" /><div class="texte_titre_section">Rien à signaler</div></div>';
            echo '<div class="empty">Pas encore de missions...</div>';
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
