<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Les Petits Pédestres';
      $styleHead       = 'stylePP.css';
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
        $celsius = 'petitspedestres';

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

          if ($parcoursExistant == true)
          {
            /**********/
            /* Saisie */
            /**********/
            echo '<div class="PP-contenu-saisie">';
              echo '<form method="post" action="parcours.php?id_parcours=' . $parcours->getId() . '&action=doModifier" class="PP-form-saisie">';
                echo '<div class="PP-zone-saisie-avancee-infos">';
                  if (isset($erreurParcours) AND $erreurParcours == true)
                  {
                    // Nom du parcours
                    echo '<label class="PP-label-parcours">Nom : </label>';
                    echo '<input type="text" value="' . $_SESSION['save']['nom_parcours'] . '" name="name" class="PP-monoligne" />';

                    // Distance
                    echo '<label class="PP-label-parcours">Distance : </label>';
                    echo '<input type="text" value="' . $_SESSION['save']['distance_parcours'] . '" name="distance" class="PP-monoligne" />';

                    // Lieu
                    echo '<label class="PP-label-parcours">Lieu : </label>';
                    echo '<input type="text" value="' . $_SESSION['save']['lieu_parcours'] . '" name="location" class="PP-monoligne" />';

                    // Lien url
                    echo '<label class="PP-label-parcours">Url : </label>';
                    echo '<input type="text" value="' . $_SESSION['save']['url_parcours'] . '" name="url" class="PP-monoligne" />';
                  }
                  else
                  {
                    // Nom du parcours
                    echo '<label class="PP-label-parcours">Nom : </label>';
                    echo '<input type="text" value="' . $parcours->getNom() . '" name="name" class="PP-monoligne" />';

                    // Distance
                    echo '<label class="PP-label-parcours">Distance : </label>';
                    echo '<input type="text" value="' . $parcours->getDistance() . '" name="distance" class="PP-monoligne" />';

                    // Lieu
                    echo '<label class="PP-label-parcours">Lieu : </label>';
                    echo '<input type="text" value="' . $parcours->getLieu() . '" name="location" class="PP-monoligne" />';
                    
                    // Lien url
                    echo '<label class="PP-label-parcours">Url : </label>';
                    echo '<input type="text" value="' . $parcours->getUrl() . '" name="url" class="PP-monoligne" />';
                  }
                echo '</div>';

                // Valider
                echo '<input type="submit" name="modification" value="Valider" class="PP-bouton" />';
              echo '</form>';
            echo '</div>';
          }
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
      <?php include('../../includes/common/mobile/footer_mobile.php'); ?>
    </footer>
  </body>
</html>