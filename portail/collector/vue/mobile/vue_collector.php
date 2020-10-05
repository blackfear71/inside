<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead      = 'Collector Room';
      $styleHead      = 'styleCO.css';
      $scriptHead     = 'scriptCO.js';
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
        $celsius = 'collector';

        include('../../includes/common/celsius.php');
      ?>

      <!-- Contenu -->
      <article>
        <?php
          /*********/
          /* Titre */
          /*********/
          echo '<div class="titre_section_mobile">' . strtoupper($titleHead) . '</div>';

          /***********/
          /* Saisies */
          /***********/

          /********************/
          /* Vote utilisateur */
          /********************/
          include('vue/mobile/vue_vote_user.php');

          /***************************/
          /* Votes tous utilisateurs */
          /***************************/
          include('vue/mobile/vue_votes_collector.php');

          /********************/
          /* Boutons d'action */
          /********************/

          /*******************/
          /* Tris et filtres */
          /*******************/
          include('vue/mobile/vue_tris_filtres.php');

          /***********/
          /* Contenu */
          /***********/
          include('vue/mobile/vue_liste_collectors.php');

          /**************/
          /* Pagination */
          /**************/
          include('vue/mobile/vue_pagination.php');
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
      <?php include('../../includes/common/footer_mobile.php'); ?>
    </footer>

    <!-- Données JSON -->
    <script>
      // Récupération de la liste des phrases / images cultes pour le script
      var listeCollectors = <?php if (isset($listeCollectorsJson) AND !empty($listeCollectorsJson)) echo $listeCollectorsJson; else echo '{}'; ?>;
    </script>
  </body>
</html>
