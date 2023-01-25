<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Recherche';
      $styleHead       = 'styleSearch.css';
      $scriptHead      = '';
      $angularHead     = false;
      $chatHead        = true;
      $datepickerHead  = false;
      $masonryHead     = false;
      $exifHead        = false;
      $html2canvasHead = false;
      $jqueryCsv       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

  <body>
    <!-- Entête -->
    <header>
      <?php include('../../includes/common/mobile/header_mobile.php'); ?>
    </header>

    <!-- Contenu -->
    <section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

      <!-- Déblocage succès -->
      <?php include('../../includes/common/success.php'); ?>

      <!-- Menus -->
      <aside>
        <?php include('../../includes/common/mobile/aside_mobile.php'); ?>
      </aside>

      <!-- Chargement page -->
      <div class="zone_loading_image">
        <img src="../../includes/icons/common/loading.png" alt="loading" id="loading_image" class="loading_image" />
      </div>

      <!-- Celsius -->
      <?php
        $celsius = 'search';
        include('../../includes/common/mobile/celsius.php');
      ?>

      <!-- Contenu -->
      <article>
        <?php
          /********************/
          /* Boutons missions */
          /********************/
          $zoneInside = 'article';
          include('../../includes/common/missions.php');

          /*********************/
          /* Zone de recherche */
          /*********************/
          include('../../includes/common/mobile/search_mobile.php');

          /*********/
          /* Titre */
          /*********/
          echo '<div class="titre_section_mobile">' . mb_strtoupper($titleHead) . '</div>';

          /***********************/
          /* Résultats recherche */
          /***********************/
          echo '<div class="zone_recherche">';
            if (!empty($resultats))
            {
              // Message pas de résultats
              if (empty($resultats['movie_house'])
              AND empty($resultats['food_advisor'])
              AND empty($resultats['petits_pedestres'])
              AND empty($resultats['missions']))
              {
                // Titre
                echo '<div class="titre_section">';
                  echo '<img src="../../includes/icons/search/search.png" alt="search" class="logo_titre_section" />';
                  echo '<div class="texte_titre_section">Pas de résultats</div>';
                echo '</div>';

                echo '<div class="empty">Pas de résultats trouvés pour "' . $_SESSION['search']['text_search'] . '" !</div>';
              }

              // Résultats par section
              if (!empty($resultats['movie_house']))
              {
                // Titre
                echo '<div id="titre_recherche_movie_house" class="titre_section">';
                  echo '<img src="../../includes/icons/search/movie_house.png" alt="movie_house" class="logo_titre_section" />';
                  echo '<div class="texte_titre_section_fleche">Movie House<div class="count_search">' . $resultats['nb_movie_house'] . '</div></div>';
                  echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
                echo '</div>';

                // Résultats
                echo '<div id="afficher_recherche_movie_house">';
                  foreach ($resultats['movie_house'] as $resultatsMH)
                  {
                    echo '<a href="../moviehouse/details.php?id_film=' . $resultatsMH->getId() . '&action=goConsulter" class="lien_resultat">';
                      echo '<table class="zone_resultat">';
                        echo '<tr>';
                          echo '<td class="zone_resultat_titre">';
                            echo $resultatsMH->getFilm();
                          echo '</td>';
    
                          echo '<td class="zone_resultat_info">';
                            if (!empty($resultatsMH->getDate_theater()))
                              echo 'Sortie au cinéma le ' . formatDateForDisplay($resultatsMH->getDate_theater());
                            else
                              echo 'Sortie au cinéma non communiquée';
                          echo '</td>';
                        echo '</tr>';
                      echo '</table>';
                    echo '</a>';
                  }
                echo '</div>';
              }

              if (!empty($resultats['food_advisor']))
              {
                // Titre
                echo '<div id="titre_recherche_food_advisor" class="titre_section">';
                  echo '<img src="../../includes/icons/search/restaurants.png" alt="restaurants" class="logo_titre_section" />';
                  echo '<div class="texte_titre_section_fleche">Restaurants<div class="count_search">' . $resultats['nb_food_advisor'] . '</div></div>';
                  echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
                echo '</div>';

                // Résultats
                echo '<div id="afficher_recherche_food_advisor">';
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
                echo '</div>';
              }

              if (!empty($resultats['petits_pedestres']))
              {
                // Titre
                echo '<div id="titre_recherche_petits_pedestres" class="titre_section">';
                  echo '<img src="../../includes/icons/search/petits_pedestres.png" alt="petits_pedestres" class="logo_titre_section" />';
                  echo '<div class="texte_titre_section_fleche">Les Petits Pédestres<div class="count_search">' . $resultats['nb_petits_pedestres'] . '</div></div>';
                  echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
                echo '</div>';

                // Résultats
                echo '<div id="afficher_recherche_petits_pedestres">';
                  foreach ($resultats['petits_pedestres'] as $resultatsPP)
                  {
                    echo '<a href="../petitspedestres/parcours.php?id_parcours=' . $resultatsPP->getId() . '&action=goConsulter" class="lien_resultat">';
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
                echo '</div>';
              }

              if (!empty($resultats['missions']))
              {
                // Titre
                echo '<div id="titre_recherche_missions" class="titre_section">';
                  echo '<img src="../../includes/icons/search/missions.png" alt="missions" class="logo_titre_section" />';
                  echo '<div class="texte_titre_section_fleche">Missions<div class="count_search">' . $resultats['nb_missions'] . '</div></div>';
                  echo '<img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_titre_section" />';
                echo '</div>';

                // Résultats
                echo '<div id="afficher_recherche_missions">';
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
                echo '</div>';
              }
            }
            else
            {
              // Titre
              echo '<div class="titre_section">';
                echo '<img src="../../includes/icons/search/search.png" alt="search" class="logo_titre_section" />';
                echo '<div class="texte_titre_section">Pas de résultats</div>';
              echo '</div>';

              echo '<div class="empty">Veuillez saisir et relancer la recherche afin d\'obtenir des résultats...</div>';
            }
          echo '</div>';
        ?>
      </article>

      <!-- Chat -->
      <?php include('../../includes/common/chat/chat.php'); ?>
    </section>

    <!-- Pied de page -->
    <footer>
      <?php include('../../includes/common/mobile/footer_mobile.php'); ?>
    </footer>
  </body>
</html>