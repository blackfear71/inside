<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "Recherche";
      $style_head  = "styleSearch.css";
      $script_head = "";
      $chat_head   = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "Recherche";

        include('../../includes/common/header.php');
      ?>
		</header>

		<section>
			<article>
        <?php
          // Boutons missions
          $zone_inside = "article";
          include('../../includes/common/missions.php');

          // Résultats recherche
          echo '<div class="zone_recherche">';
            if (!empty($resultats))
            {
              // Message pas de résultats
              if (empty($resultats['movie_house'])
              AND empty($resultats['food_advisor'])
              AND empty($resultats['petits_pedestres'])
              AND empty($resultats['missions']))
                echo '<div class="titre_section"><img src="../../includes/icons/search/search.png" alt="search" class="logo_titre_section" />Pas de résultats trouvés pour "' . $_SESSION['save']['search'] . '" !</div>';

              // Résultats par section
              if (!empty($resultats['movie_house']))
              {
                // Titre section
                echo '<div class="titre_section"><img src="../../includes/icons/search/movie_house.png" alt="movie_house" class="logo_titre_section" />Movie House</div><div class="count_search">' . $resultats['nb_movie_house'] . '</div>';

                // Résultats
                foreach ($resultats['movie_house'] as $resultatsMH)
                {
                  echo '<a href="../moviehouse/details.php?id_film=' . $resultatsMH->getId() . '&action=goConsulter" class="lien_resultat">';
                    echo '<table class="zone_resultat">';
                      echo '<tr>';
                        echo '<td class="zone_resultat_titre">';
                          echo $resultatsMH->getFilm();
                        echo '</td>';
                        echo '<td class="zone_resultat_info">';
                          echo 'Sortie au cinéma le ' . formatDateForDisplay($resultatsMH->getDate_theater());
                        echo '</td>';
                      echo '</tr>';
                    echo '</table>';
                  echo '</a>';
                }
              }

              if (!empty($resultats['food_advisor']))
              {
                // Titre section
                echo '<div class="titre_section"><img src="../../includes/icons/search/restaurants.png" alt="restaurants" class="logo_titre_section" />Restaurants</div><div class="count_search">' . $resultats['nb_food_advisor'] . '</div>';

                // Résultats
                foreach ($resultats['food_advisor'] as $resultatsFA)
                {
                  echo '<a href="../foodadvisor/restaurants.php?action=goConsulter&anchor=' . $resultatsFA->getId() . '" class="lien_resultat">';
                    echo '<table class="zone_resultat">';
                      echo '<tr>';
                        echo '<td class="zone_resultat_titre">';
                          echo $resultatsFA->getName();
                        echo '</td>';
                        echo '<td class="zone_resultat_info">';
                          echo $resultatsFA->getLocation();
                        echo '</td>';
                      echo '</tr>';
                    echo '</table>';
                  echo '</a>';
                }
              }

              if (!empty($resultats['petits_pedestres']))
              {
                // Titre section
                echo '<div class="titre_section"><img src="../../includes/icons/search/petits_pedestres.png" alt="petits_pedestres" class="logo_titre_section" />Les Petits Pédestres</div><div class="count_search">' . $resultats['nb_petits_pedestres'] . '</div>';

                // Résultats
                foreach ($resultats['petits_pedestres'] as $resultatsPP)
                {
                  echo '<a href="../petitspedestres/parcours.php?id=' . $resultatsPP->getId() . '&action=consulter" class="lien_resultat">';
                    echo '<table class="zone_resultat">';
                      echo '<tr>';
                        echo '<td class="zone_resultat_titre">';
                          echo $resultatsPP->getNom();
                        echo '</td>';
                        echo '<td class="zone_resultat_info">';
                          echo formatDistanceForDisplay($resultatsPP->getDistance());
                        echo '</td>';
                      echo '</tr>';
                    echo '</table>';
                  echo '</a>';
                }
              }

              if (!empty($resultats['missions']))
              {
                // Titre section
                echo '<div class="titre_section"><img src="../../includes/icons/search/missions.png" alt="missions" class="logo_titre_section" />Missions</div><div class="count_search">' . $resultats['nb_missions'] . '</div>';

                // Résultats
                foreach ($resultats['missions'] as $resultatsMI)
                {
                  echo '<a href="../missions/details.php?id_mission=' . $resultatsMI->getId() . '&action=goConsulter" class="lien_resultat">';
                    echo '<table class="zone_resultat">';
                      echo '<tr>';
                        echo '<td class="zone_resultat_titre">';
                          echo $resultatsMI->getMission();
                        echo '</td>';
                        echo '<td class="zone_resultat_info">';
                          if (date('Ymd') > $resultatsMI->getDate_fin())
                            echo 'Terminée le ' . formatDateForDisplay($resultatsMI->getDate_fin());
                          else
                            echo 'Débutée le ' . formatDateForDisplay($resultatsMI->getDate_deb());
                        echo '</td>';
                      echo '</tr>';
                    echo '</table>';
                  echo '</a>';
                }
              }
            }
            else
            {
              echo '<div class="titre_section"><img src="../../includes/icons/search/search.png" alt="search" class="logo_titre_section" />Pas de résultats</div>';

              echo '<div class="empty">Veuillez saisir et relancer la recherche afin d\'obtenir des résultats...</div>';
            }
          echo '</div>';
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
