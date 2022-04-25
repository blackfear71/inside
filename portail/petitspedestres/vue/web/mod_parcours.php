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

                    // Lien image
                    echo '<label class="PP-label-parcours">Url image : </label>';
                    echo '<input type="text" value="' . $_SESSION['save']['image_parcours'] . '" name="picurl" class="PP-monoligne" />';
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

                    // Lien image
                    echo '<label class="PP-label-parcours">Url image : </label>';
                    echo '<input type="text" value="' . $parcours->getImage() . '" name="picurl" class="PP-monoligne" />';
                  }
    						echo '</div>';

                echo '<br /><br />';

                // Valider
    						echo '<input type="submit" name="modification" value="Valider" />';
    					echo '</form>';
            echo '</div>';
          }
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
