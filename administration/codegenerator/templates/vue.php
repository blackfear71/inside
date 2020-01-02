<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = /*title_head*/;
      $style_head      = /*style_specifique*/;
      $script_head     = /*script_specifique*/;
      $angular_head    = /*angular_head*/;
      $chat_head       = /*chat_head*/;
      $datepicker_head = /*datepicker_head*/;
      $masonry_head    = /*masonry_head*/;
      $exif_head       = /*exif_head*/;

      include('../../includes/common/head.php');
    ?>
  </head>

  <body>
    <!-- Entête -->
    <header>
      <?php
        $title = /*title*/;

        include('../../includes/common/header.php');/*onglets*/
      ?>
    </header>

    <!-- Contenu -->
    <section>/*alerts*/
      <article>
        <?php/*missions*/
          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /***********/
          /* Contenu */
          /***********/
        ?>
      </article>/*chat*/
    </section>

    <!-- Pied de page -->
    <footer>
      <?php include('../../includes/common/footer.php'); ?>
    </footer>
  </body>
</html>
