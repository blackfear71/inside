<!DOCTYPE html>
<html>
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "Recherche";
      $style_head  = "styleSearch.css";
      $script_head = "";
      $chat_head   = true;
      
      include($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/common.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "Recherche";

        include('../../includes/header.php');
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

			<article>
				<?php
          echo '<div class="zone_recherche">';
            if (!empty($resultats))
            {
              // Message pas de résultats
              if (empty($resultats['movie_house']) AND empty($resultats['petits_pedestres']) AND empty($resultats['missions']))
                echo '<div class="titre_search">Pas de résultats trouvés pour "' . $_SESSION['save']['search'] . '" !</div>';

              // Résultats par section
              if (!empty($resultats['movie_house']))
              {
                // Titre section
                echo '<div class="titre_search">Movie House</div><div class="count_search">' . $resultats['nb_movie_house'] . '</div>';

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

              if (!empty($resultats['petits_pedestres']))
              {
                // Titre section
                echo '<div class="titre_search">Les Petits Pédestres</div><div class="count_search">' . $resultats['nb_petits_pedestres'] . '</div>';

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
                echo '<div class="titre_search">Missions</div><div class="count_search">' . $resultats['nb_missions'] . '</div>';

                // Résultats
                foreach ($resultats['missions'] as $resultatsMI)
                {
                  echo '<a href="../missions/details.php?id_mission=' . $resultatsMI->getId() . '&view=mission&action=goConsulter" class="lien_resultat">';
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
              echo '<div class="titre_search">Veuillez relancer la recherche afin d\'obtenir des résultats</div>';
            }
          echo '</div>';
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
