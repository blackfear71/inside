<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "CL";
      $style_head      = "styleCL.css";
      $script_head     = "scriptCL.js";
      $angular_head    = false;
      $chat_head       = true;
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
          $zone_inside = "article";
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

          if ($_GET['action'] == "goConsulterHistoire")
          {
            /********************/
            /* Histoire du site */
            /********************/
            include('vue/vue_history.php');
          }
          else
          {
            /*****************************/
            /* Journaux de modifications */
            /*****************************/
            include('vue/vue_journaux.php');
          }
        ?>
      </article>

      <!-- Chat -->
      <?php include('../../includes/chat/chat.php'); ?>
    </section>

    <!-- Pied de page -->
    <footer>
      <?php include('../../includes/common/footer.php'); ?>
    </footer>
  </body>
</html>
