<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Les Petits Pédestres';
      $styleHead       = 'stylePP.css';
      $scriptHead      = 'scriptPP.js';
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
        $celsius = 'petitspedestres';

        include('../../includes/common/celsius.php');
      ?>

      <!-- Contenu -->
      <article>
        <?php
          /*********/
          /* Titre */
          /*********/
          echo '<div class="titre_section_mobile">' . mb_strtoupper($titleHead) . '</div>';

          /**********/
          /* Saisie */
          /**********/
          echo '<div class="PP-contenu-saisie">';
            echo '<form method="post" action="parcours.php?action=doAjouter" class="PP-form-saisie">';
              echo '<div class="PP-zone-saisie-avancee-infos">';
                // Nom du parcours
                echo '<label class="PP-label-parcours">Nom : </label>';
                echo '<input type="text" placeholder="Nom parcours" value="' . $_SESSION['save']['nom_parcours'] . '" name="name" class="PP-monoligne" />';

                // Distance
                echo '<label class="PP-label-parcours">Distance : </label>';
                echo '<input type="text" placeholder="Distance (km)" value="' . $_SESSION['save']['distance_parcours'] . '" name="distance" class="PP-monoligne" />';

                // Lieu
                echo '<label class="PP-label-parcours">Lieu : </label>';
                echo '<input type="text" placeholder="Lieu" value="' . $_SESSION['save']['lieu_parcours'] . '" name="location" class="PP-monoligne" />';

                // Lien image
                echo '<label class="PP-label-parcours">Url image : </label>';
                echo '<input type="text" placeholder="Url de l\'image" value="' . $_SESSION['save']['image_parcours'] . '" name="picurl" class="PP-monoligne" />';
              echo '</div>';

              echo '<input type="submit" name="modification" value="Valider" class="PP-bouton" />';
            echo '</form>';
          echo '</div>';
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
      <?php include('../../includes/common/footer_mobile.php'); ?>
    </footer>
  </body>
</html>
