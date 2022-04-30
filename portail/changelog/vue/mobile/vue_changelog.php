<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Journal des modifications';
      $styleHead       = 'styleCL.css';
      $scriptHead      = 'scriptCL.js';
      $angularHead     = false;
      $chatHead        = false;
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
        $celsius = 'changelog';

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

          /********/
          /* Vues */
          /********/
          include('vue/mobile/vue_vues.php');

          /********************/
          /* Boutons d'action */
          /********************/
          // Vues
          echo '<a id="afficherSaisieVue" title="Changer de vue" class="lien_green">';
            echo '<img src="../../includes/icons/changelog/view_grey.png" alt="view_grey" class="image_lien" />';

            if ($_GET['action'] == 'goConsulterHistoire')
              echo '<div class="titre_lien">HISTOIRE DU SITE</div>';
            else
              echo '<div class="titre_lien">ANNÉE - ' . $_GET['year'] . '</div>';
          echo '</a>';

          /***********/
          /* Contenu */
          /***********/
          if ($_GET['action'] == 'goConsulterHistoire')
            include('vue/mobile/vue_history.php');
          else
            include('vue/mobile/vue_liste_journaux.php');
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
      <?php include('../../includes/common/mobile/footer_mobile.php'); ?>
    </footer>
  </body>
</html>
