<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = '&#35;TheBox';
      $styleHead       = 'styleTheBox.css';
      $scriptHead      = 'scriptTheBox.js';
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
        $celsius = 'ideas';
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
          include('vue/mobile/vue_saisie_idea.php');

          /********/
          /* Vues */
          /********/
          include('vue/mobile/vue_vues.php');

          /********************/
          /* Boutons d'action */
          /********************/
          // Saisie idée
          echo '<a id="afficherSaisieIdee" title="Proposer un idée" class="lien_red lien_demi margin_lien">';
            echo '<img src="../../includes/icons/ideas/ideas_grey.png" alt="ideas_grey" class="image_lien" />';
            echo '<div class="titre_lien">PROPOSER</div>';
          echo '</a>';

          // Vues
          echo '<a id="afficherSaisieVue" title="Changer de vue" class="lien_green lien_demi">';
            echo '<img src="../../includes/icons/ideas/view_grey.png" alt="view_grey" class="image_lien" />';

            switch ($_GET['view'])
            {
              case 'inprogress':
                echo '<div class="titre_lien">EN COURS</div>';
                break;

              case 'mine':
                echo '<div class="titre_lien">EN CHARGE</div>';
                break;

              case 'done':
                echo '<div class="titre_lien">TERMINÉES</div>';
                break;

              case 'all':
              default:
                echo '<div class="titre_lien">TOUTES</div>';
                break;
            }
          echo '</a>';

          /***********/
          /* Contenu */
          /***********/
          include('vue/mobile/vue_liste_ideas.php');

          /**************/
          /* Pagination */
          /**************/
          include('vue/mobile/vue_pagination.php');
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
      <?php include('../../includes/common/mobile/footer_mobile.php'); ?>
    </footer>
  </body>
</html>
