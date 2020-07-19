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
      <!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu" class="aside_nav">
				<?php
					$ajouterParcours  = true;
					$modifierParcours = true;

					include('../../includes/common/aside.php');
				?>
			</aside>

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

          /***********/
          /* Contenu */
          /***********/
  				echo '<div class="PP-parcours">';
            echo '<div class="PP-titre">';
              echo $parcours->getNom();
            echo '</div>';

            echo '<p>';
              echo 'Distance : ' . $parcours->getDistance() . ' km<br/>';
              echo 'Lieu : ' . $parcours->getLieu();

              if (!empty($parcours->getImage()))
                echo '<br/><img src="' . $parcours->getImage() .'" alt="' . $parcours->getNom() . '" class="PP-image" /><br/>';
            echo '</p>';
          echo '</div>';
        ?>
      </article>

      <!-- Chat -->
      <?php include('../../includes/chat/chat.php'); ?>
    </section>

    <!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
