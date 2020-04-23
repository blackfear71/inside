<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "Changelog";
      $style_head      = "styleAdmin.css";
      $script_head     = "scriptAdmin.js";
      $angular_head    = false;
      $chat_head       = false;
      $datepicker_head = false;
      $masonry_head    = true;
      $exif_head       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

  <body>
    <!-- Entête -->
    <header>
      <?php
        $title = "Journal des modifications";

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
      // Récupération liste semaines par années pour le script
      var categoriesChangeLog = <?php echo $categoriesChangeLogJson; ?>;
    </script>
  </body>
</html>
