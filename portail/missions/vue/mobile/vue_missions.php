<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Missions : Insider';
      $styleHead       = 'styleMI.css';
      $scriptHead      = '';
      $angularHead     = false;
      $chatHead        = false;
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
      <?php include('../../includes/common/header_mobile.php'); ?>
    </header>

    <!-- Contenu -->
    <section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

      <!-- Déblocage succès -->
      <?php include('../../includes/common/success.php'); ?>

      <!-- Menus -->
      <aside>
        <?php include('../../includes/common/aside_mobile.php'); ?>
      </aside>

      <!-- Chargement page -->
      <div class="zone_loading_image">
        <img src="../../includes/icons/common/loading.png" alt="loading" id="loading_image" class="loading_image" />
      </div>

      <!-- Celsius -->
      <?php
        $celsius = 'missions';

        include('../../includes/common/celsius.php');
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
          include('../../includes/common/search_mobile.php');

          /*********/
          /* Titre */
          /*********/
          echo '<div class="titre_section_mobile">' . mb_strtoupper($titleHead) . '</div>';

          /**********************/
          /* Liste des missions */
          /**********************/
          if (!empty($listeMissions))
          {
            $titreEnCours = false;
            $titreAVenir  = false;
            $titrePassees = false;

            echo '<div class="zone_missions">';
              foreach ($listeMissions as $keyMission => $mission)
              {
                // Missions futures
                if ($mission->getStatut() == 'V')
                {
                  if ($titreAVenir != true)
                  {
                    // Titre
                    echo '<div id="titre_missions_a_venir" class="titre_section">';
                      echo '<img src="../../includes/icons/missions/missions_to_come.png" alt="missions_to_come" class="logo_titre_section" />';
                      echo '<div class="texte_titre_section">Missions à venir</div>';
                      echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
                    echo '</div>';

                    // Zone missions à venir
                    echo '<div id="afficher_missions_a_venir">';

                    $titreAVenir = true;
                  }

                  echo '<div class="zone_mission_default">';
                    echo '<img src="../../includes/icons/missions/default_mission.png" alt="default_mission" title="A venir" class="image_mission_default" />';
                    echo '<div class="titre_mission_default">Revenez pour une nouvelle mission à partir du ' . formatDateForDisplay($mission->getDate_deb()) . ' à ' . formatTimeForDisplayLight($mission->getHeure()) . ' !</div>';
                  echo '</div>';
                }
                // Missions en cours
                elseif ($mission->getStatut() == 'C')
                {
                  if ($titreEnCours != true)
                  {
                    // Titre
                    echo '<div id="titre_missions_en_cours" class="titre_section">';
                      echo '<img src="../../includes/icons/missions/missions_in_progress.png" alt="missions_in_progress" class="logo_titre_section" />';
                      echo '<div class="texte_titre_section">Missions en cours</div>';
                      echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
                    echo '</div>';

                    // Zone missions
                    echo '<div id="afficher_missions_en_cours">';

                    $titreEnCours = true;
                  }

                  echo '<a href="details.php?id_mission=' . $mission->getId() . '&action=goConsulter" class="zone_mission_accueil">';
                    echo '<img src="../../includes/images/missions/banners/' . $mission->getReference() . '.png" alt="' . $mission->getReference() . '" title="' . $mission->getMission() . '" class="image_mission_accueil" />';
                    echo '<div class="titre_mission_accueil">' . $mission->getMission() . '</div>';
                  echo '</a>';
                }
                // Missions précédentes
                elseif ($mission->getStatut() == 'A')
                {
                  if ($titrePassees != true)
                  {
                    // Titre
                    echo '<div id="titre_missions_terminees" class="titre_section">';
                      echo '<img src="../../includes/icons/missions/missions_ended.png" alt="missions_ended" class="logo_titre_section" />';
                      echo '<div class="texte_titre_section">Anciennes missions</div>';
                      echo '<img src="../../includes/icons/common/open.png" alt="open" class="fleche_titre_section" />';
                    echo '</div>';

                    // Zone missions
                    echo '<div id="afficher_missions_terminees">';

                    $titrePassees = true;
                  }

                  echo '<a href="details.php?id_mission=' . $mission->getId() . '&action=goConsulter" class="zone_mission_accueil">';
                    echo '<img src="../../includes/images/missions/banners/' . $mission->getReference() . '.png" alt="' . $mission->getReference() . '" title="' . $mission->getMission() . '" class="image_mission_accueil" />';
                    echo '<div class="titre_mission_accueil">' . $mission->getMission() . '</div>';
                  echo '</a>';
                }

                if (!isset($listeMissions[$keyMission + 1]) OR $mission->getStatut() != $listeMissions[$keyMission + 1]->getStatut())
                {
                  // Termine la zone des missions
                  echo '</div>';
                }
              }
            echo '</div>';
          }
          else
          {
            // Titre
            echo '<div class="titre_section">';
              echo '<img src="../../includes/icons/missions/missions_in_progress.png" alt="missions_in_progress" class="logo_titre_section" />';
              echo '<div class="texte_titre_section">Rien à signaler</div>';
            echo '</div>';

            echo '<div class="empty">Pas encore de missions...</div>';
          }
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
      <?php include('../../includes/common/footer_mobile.php'); ?>
    </footer>
  </body>
</html>