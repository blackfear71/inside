<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Les Petits Pédestres';
      $styleHead       = 'stylePP.css';
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
      <?php
        $title = 'Les Petits Pédestres';

        include('../../includes/common/web/header.php');
        include('../../includes/common/web/onglets.php');
      ?>
		</header>

    <!-- Contenu -->
		<section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

      <!-- Déblocage succès -->
      <?php include('../../includes/common/success.php'); ?>

			<article>
        <?php
          /********************/
          /* Boutons missions */
          /********************/
          $zoneInside = 'article';
          include('../../includes/common/missions.php');

          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /**********/
          /* Saisie */
          /**********/
          echo '<form method="post" action="parcours.php?action=doAjouter" class="PP-form-saisie">';
            echo '<div class="PP-zone-saisie-avancee-infos">';
              // Nom du parcours
              echo '<div class="PP-zone-saisie-parcours">';
                echo '<label class="PP-label-parcours">Nom : </label>';
                echo '<input type="text" placeholder="Nom parcours" value="' . $_SESSION['save']['nom_parcours'] . '" name="name" class="PP-monoligne" required />';
              echo '</div>';

              // Distance
              echo '<div class="PP-zone-saisie-parcours">';
                echo '<label class="PP-label-parcours">Distance : </label>';
                echo '<input type="text" placeholder="Distance (km)" value="' . $_SESSION['save']['distance_parcours'] . '" name="distance" class="PP-monoligne" required />';
              echo '</div>';

              // Lieu
              echo '<div class="PP-zone-saisie-parcours">';
                echo '<label class="PP-label-parcours">Lieu : </label>';
                echo '<input type="text" placeholder="Lieu" value="' . $_SESSION['save']['lieu_parcours'] . '" name="location" class="PP-monoligne" required />';
              echo '</div>';

              // Lien url
              echo '<div class="PP-zone-saisie-parcours">';
                echo '<label class="PP-label-parcours">Url : </label>';
                echo '<input type="text" placeholder="Url" value="' . $_SESSION['save']['url_parcours'] . '" name="url" class="PP-monoligne" />';
              echo '</div>';

              // Type de lien 
              echo '<div class="PP-zone-saisie-parcours">';
                echo '<label class="PP-label-parcours">Type de lien : </label>';
                echo '<select name="type" class="PP-listbox">';
                  echo '<option value="" hidden selected>Choisir...</option>';
                  echo '<option value="image">Image</option>';
                  echo '<option value="pdf">PDF</option>';
                echo '</select>';
              echo '</div>';
            echo '</div>';

            echo '<input type="submit" name="modification" value="Valider" class="PP-bouton-saisie" />';
          echo '</form>';
        ?>
      </article>

      <!-- Chat -->
      <?php include('../../includes/common/chat/chat.php'); ?>
    </section>

    <!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/web/footer.php'); ?>
		</footer>
  </body>
</html>
