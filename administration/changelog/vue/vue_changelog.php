<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead      = 'Changelog';
      $styleHead      = 'styleAdmin.css';
      $scriptHead     = 'scriptAdmin.js';
      $angularHead    = false;
      $chatHead       = false;
      $datepickerHead = false;
      $masonryHead    = true;
      $exifHead       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

  <body>
    <!-- Entête -->
    <header>
      <?php
        $title = 'Journal des modifications';

        include('../../includes/common/header.php');
      ?>
    </header>

    <!-- Contenu -->
    <section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

      <article>
        <?php
          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /**************/
          /* Paramètres */
          /**************/
          include('vue/vue_saisie_parametres.php');

          /**********/
          /* Saisie */
          /**********/
          if (!empty($changeLogParameters->getAction()) AND $errorChangelog != true)
            include('vue/vue_saisie_changelog.php');
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
      <?php include('../../includes/common/footer.php'); ?>
    </footer>

    <!-- Données JSON -->
    <script>
      // Récupération de la liste des semaines par années pour le script
      var categoriesChangeLog = <?php if (isset($categoriesChangeLogJson) AND !empty($categoriesChangeLogJson)) echo $categoriesChangeLogJson; else echo '{}'; ?>;
    </script>
  </body>
</html>
