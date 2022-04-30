<!DOCTYPE html>
<html lang="fr" ng-app="parcoursApp">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Les Petits Pédestres';
      $styleHead       = 'stylePP.css';
      $scriptHead      = 'scriptPP.js';
      $angularHead     = true;
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

          /*********/
          /* Liens */
          /*********/
          echo '<div class="zone_liens_saisie">';
            // Saisie parcours
            echo '<a href="parcours.php?action=goAjouter" title="Ajouter un parcours" class="lien_categorie">';
              echo '<div class="zone_logo_lien"><img src="../../includes/icons/petitspedestres/parcours.png" alt="parcours" class="image_lien" /></div>';
              echo '<div class="zone_texte_lien">Ajouter un parcours</div>';
            echo '</a>';
          echo '</div>';

          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /************/
          /* Parcours */
          /************/
          if (!empty($listeParcours))
            echo '<parcours-list></parcours-list>';
          else
            echo '<div class="empty">Aucun parcours disponible...</div>';
        ?>

  			<!-- Monsieur et madame Santé ont un fils, comment qu'y s'appelle ?
  				   Réponse : Parcours.
  				   C'est nul ? Oui, c'est nul. -->
    	</article>

      <!-- Chat -->
      <?php include('../../includes/common/chat/chat.php'); ?>
		</section>

    <!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/web/footer.php'); ?>
		</footer>

    <script>
      var listeParcoursJson = <?php echo $listeParcoursJson; ?>;
    </script>
  </body>
</html>
