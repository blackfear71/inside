<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead      = 'Les enfants ! À table !';
      $styleHead      = 'styleFA.css';
      $scriptHead     = 'scriptFA.js';
      $angularHead    = false;
      $chatHead       = false;
      $datepickerHead = false;
      $masonryHead    = false;
      $exifHead       = true;

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
        $celsius = 'restaurants';

        include('../../includes/common/celsius.php');
      ?>

      <!-- Contenu -->
      <article>
        <?php
          /*********/
          /* Titre */
          /*********/
          echo '<div class="titre_section_mobile">' . strtoupper($titleHead) . '</div>';

          /**********/
          /* Saisie */
          /**********/








          /********************/
          /* Boutons d'action */
          /********************/
          // Ajouter un restaurant







          // Propositions
          echo '<a href="foodadvisor.php?action=goConsulter" title="Les propositions" class="lien_green">Les propositions</a>';









          /***********/
          /* Détails */
          /***********/









          /*********/
          /* Lieux */
          /*********/
          include('vue/mobile/vue_lieux_restaurants.php');

          /***************/
          /* Restaurants */
          /***************/
          include('vue/mobile/vue_fiches_restaurants.php');
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
			<?php include('../../includes/common/footer_mobile.php'); ?>
		</footer>

    <!-- Données JSON -->
    <script>





      // Récupération de la liste des restaurants pour le script
      //var detailsRestaurants = <?php //echo $detailsRestaurants; ?>;






    </script>
  </body>
</html>
