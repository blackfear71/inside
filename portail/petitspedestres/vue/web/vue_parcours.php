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

          if ($parcoursExistant == true)
          {
            /*********/
            /* Liens */
            /*********/
            echo '<div class="zone_liens_saisie">';
              // Saisie parcours
              echo '<a href="parcours.php?action=goAjouter" title="Ajouter un parcours" class="lien_categorie">';
                echo '<div class="zone_logo_lien"><img src="../../includes/icons/petitspedestres/parcours.png" alt="parcours" class="image_lien" /></div>';
                echo '<div class="zone_texte_lien">Ajouter un parcours</div>';
              echo '</a>';

              // Modifier le parcours
              echo '<a href="parcours.php?id_parcours=' . $_GET['id_parcours'] . '&action=goModifier" title="Modifier le parcours" class="lien_categorie">';
                echo '<div class="zone_logo_lien"><img src="../../includes/icons/petitspedestres/edit.png" alt="edit" class="image_lien" /></div>';
                echo '<div class="zone_texte_lien">Modifier le parcours</div>';
              echo '</a>';
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

                if (!empty($parcours->getUrl()))
                  echo '<embed src="https://drive.google.com/viewerng/viewer?embedded=true&url=' . $parcours->getUrl() .'" class="PP-pdf">';
              echo '</p>';
            echo '</div>';
          }
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
