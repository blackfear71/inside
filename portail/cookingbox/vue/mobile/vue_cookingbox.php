<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Cooking Box';
      $styleHead       = 'styleCB.css';
      $scriptHead      = 'scriptCB.js';
      $angularHead     = false;
      $chatHead        = false;
      $datepickerHead  = false;
      $masonryHead     = false;
      $exifHead        = true;
      $html2canvasHead = false;
      $jqueryCsv       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

  <body>
    <!-- Entête -->
    <header>
      <?php include('../../includes/common/mobile/header_mobile.php'); ?>
    </header>

    <!-- Contenu -->
    <section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

      <!-- Déblocage succès -->
      <?php include('../../includes/common/success.php'); ?>

      <!-- Menus -->
      <aside>
        <?php include('../../includes/common/mobile/aside_mobile.php'); ?>
      </aside>

      <!-- Chargement page -->
      <div class="zone_loading_image">
        <img src="../../includes/icons/common/loading.png" alt="loading" id="loading_image" class="loading_image" />
      </div>

      <!-- Celsius -->
      <?php
        $celsius = 'cookingbox';

        include('../../includes/common/mobile/celsius.php');
      ?>

      <!-- Contenu -->
      <article>
        <?php
          /********************/
          /* Boutons missions */
          /********************/
          $zoneInside = 'article';
          include('../../includes/common/missions.php');

          /*********************/
          /* Zone de recherche */
          /*********************/
          include('../../includes/common/mobile/search_mobile.php');

          /*********/
          /* Titre */
          /*********/
          echo '<div class="titre_section_mobile">' . mb_strtoupper($titleHead) . '</div>';

          /**********/
          /* Années */
          /**********/
          include('vue/mobile/vue_annees.php');

          /***********/
          /* Saisies */
          /***********/
          include('vue/mobile/vue_saisie_semaine.php');
          include('vue/mobile/vue_saisie_recette.php');

          /********************/
          /* Boutons d'action */
          /********************/
          if (!empty($listeSemaines))
          {
            // Saisie recette
            echo '<a id="afficherSaisieRecette" title="Ajouter un gâteau ou une recette" class="lien_green lien_demi margin_lien">';
              echo '<img src="../../includes/icons/cookingbox/cake.png" alt="cake" class="image_lien" />';
              echo '<div class="titre_lien">GÂTEAU / RECETTE</div>';
            echo '</a>';

            // Années
            echo '<a id="afficherSaisieAnnee" title="Changer d\'année" class="lien_red lien_demi">';
              echo '<img src="../../includes/icons/cookingbox/recent_grey.png" alt="recent_grey" class="image_lien" />';
              echo '<div class="titre_lien">ANNÉE - ' . $_GET['year'] . '</div>';
            echo '</a>';
          }
          else
          {
            // Années
            echo '<a id="afficherSaisieAnnee" title="Changer d\'année" class="lien_red">';
              echo '<img src="../../includes/icons/cookingbox/recent_grey.png" alt="recent_grey" class="image_lien" />';
              echo '<div class="titre_lien">ANNÉE - ' . $_GET['year'] . '</div>';
            echo '</a>';
          }

          /************/
          /* Semaines */
          /************/
          include('vue/mobile/vue_semaines.php');

          /************/
          /* Recettes */
          /************/
          include('vue/mobile/vue_recettes.php');
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
      <?php include('../../includes/common/mobile/footer_mobile.php'); ?>
    </footer>

    <!-- Données JSON -->
    <script>
      // Récupération des semaines pour le script
      var currentWeek = <?php if (isset($currentWeekJson) AND !empty($currentWeekJson)) echo $currentWeekJson; else echo '{}'; ?>;
      var nextWeek    = <?php if (isset($nextWeekJson) AND !empty($nextWeekJson)) echo $nextWeekJson; else echo '{}'; ?>;

      // Récupération de la liste des semaines par années pour le script
      var listWeeks = <?php if (isset($listeSemainesJson) AND !empty($listeSemainesJson)) echo $listeSemainesJson; else echo '{}'; ?>;

      // Récupération de la liste des utilisateurs pour le script
      var listCookers = <?php if (isset($listeCookersJson) AND !empty($listeCookersJson)) echo $listeCookersJson; else echo '{}'; ?>;

      // Récupération de la liste des recettes pour le script
      var listRecipes = <?php if (isset($recettesJson) AND !empty($recettesJson)) echo $recettesJson; else echo '{}'; ?>;

      // Récupération utilisateur connecté
      var userSession = <?php echo json_encode($_SESSION['user']['identifiant']); ?>;
    </script>
  </body>
</html>
