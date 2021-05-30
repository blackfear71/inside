<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Journal des modifications';
      $styleHead       = 'styleCL.css';
      $scriptHead      = 'scriptCL.js';
      $angularHead     = false;
      $chatHead        = true;
      $datepickerHead  = false;
      $masonryHead     = true;
      $exifHead        = false;
      $html2canvasHead = false;

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
    <section class="section_no_nav">
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

          /**********/
          /* Années */
          /**********/
          include('vue/vue_onglets.php');

          switch ($_GET['action'])
          {
            case 'goConsulterHistoire':
              /********************/
              /* Histoire du site */
              /********************/
              include('vue/vue_history.php');
              break;

            case 'goConsulter':
              /*****************************/
              /* Journaux de modifications */
              /*****************************/
              include('vue/vue_journaux.php');
              break;

            default:
              break;
          }
        ?>
      </article>

      <!-- Chat -->
      <?php include('../../includes/common/chat/chat.php'); ?>
    </section>

    <!-- Pied de page -->
    <footer>
      <?php include('../../includes/common/footer.php'); ?>
    </footer>
  </body>
</html>
