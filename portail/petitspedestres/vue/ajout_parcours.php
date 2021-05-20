<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead      = 'Les Petits Pédestres';
      $styleHead      = 'stylePP.css';
      $scriptHead     = '';
      $angularHead    = false;
      $chatHead       = true;
      $datepickerHead = false;
      $masonryHead    = false;
      $exifHead       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
		<header>
      <?php
        $title = 'Les Petits Pédestres';

        include('../../includes/common/header.php');
        include('../../includes/common/onglets.php');
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
  				echo '<div class="PP-contenu-saisie">';
  					echo '<form method="post" action="parcours.php?action=doAjouter" class="PP-form-saisie">';
  						echo '<div class="PP-zone-saisie-avancee-infos">';
  							echo '<label class="label_parcours">Nom : </label>';
  							echo '<input type="text" placeholder="Nom parcours" value="' . $_SESSION['save']['nom_parcours'] . '" name="name" class="PP-monoligne" />';

                echo '<label class="label_parcours">Distance : </label>';
  							echo '<input type="text" placeholder="Distance (km)" value="' . $_SESSION['save']['distance_parcours'] . '" name="dist" class="PP-monoligne" />';

                echo '<label class="label_parcours">Lieu : </label>';
  							echo '<input type="text" placeholder="Lieu" value="' . $_SESSION['save']['lieu_parcours'] . '" name="location" class="PP-monoligne" />';

                echo '<label class="label_parcours">Url image : </label>';
  							echo '<input type="text" placeholder="Url de l\'image" value="' . $_SESSION['save']['image_parcours'] . '" name="picurl" class="PP-monoligne" />';
  						echo '</div>';

              echo '<br /><br />';

  						echo '<input type="submit" name="modification" value="Valider" />';
  					echo '</form>';
          echo '</div>';
        ?>
      </article>

      <!-- Chat -->
      <?php include('../../includes/common/chat/chat.php'); ?>
    </section>

    <!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
