<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Demandes d\'évolution';
      $styleHead       = 'styleBugs.css';
      $scriptHead      = 'scriptBugs.js';
      $angularHead     = false;
      $chatHead        = true;
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
        $celsius = 'bugs';
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
          /* Saisie */
          /**********/
          include('vue/mobile/vue_saisie_bug.php');

          /********/
          /* Vues */
          /********/
          include('vue/mobile/vue_vues.php');

          /********************/
          /* Boutons d'action */
          /********************/
          // Saisie idée
          echo '<a id="afficherSaisieRapport" title="Rapporter un bug ou une évolution" class="lien_red lien_demi margin_lien">';
            echo '<img src="../../includes/icons/common/alert_grey.png" alt="alert_grey" class="image_lien" />';
            echo '<div class="titre_lien">RAPPORTER</div>';
          echo '</a>';

          // Vues
          echo '<a id="afficherSaisieVue" title="Changer de vue" class="lien_green lien_demi">';
            echo '<img src="../../includes/icons/reports/view_grey.png" alt="view_grey" class="image_lien" />';

            switch ($_GET['view'])
            {
              case 'resolved':
                echo '<div class="titre_lien">RÉSOLU(E)S</div>';
                break;

              case 'unresolved':
              default:
                echo '<div class="titre_lien">EN COURS</div>';
                break;
            }
          echo '</a>';

          /***********/
          /* Contenu */
          /***********/
          include('vue/mobile/vue_liste_bugs.php');
        ?>
      </article>

      <!-- Chat -->
      <?php include('../../includes/common/chat/chat.php'); ?>
    </section>

    <!-- Pied de page -->
    <footer>
      <?php include('../../includes/common/mobile/footer_mobile.php'); ?>
    </footer>
  </body>
</html>
