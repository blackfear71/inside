<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Les Petits Pédestres';
      $styleHead       = 'stylePP.css';
      $scriptHead      = 'scriptPP.js';
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
      <?php include('../../includes/common/header_mobile.php'); ?>
    </header>

    <!-- Contenu -->
    <section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

      <!-- Déblocage succès -->
      <?php include('../../includes/common/success.php'); ?>

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
        $celsius = 'petitspedestres';

        include('../../includes/common/celsius.php');
      ?>

      <!-- Contenu -->
      <article>
        <?php
          /*********/
          /* Titre */
          /*********/
          echo '<div class="titre_section_mobile">' . mb_strtoupper($titleHead) . '</div>';

          if ($parcoursExistant == true)
          {
            /*********/
            /* Liens */
            /*********/
            // Ajout parcours
            echo '<a href="parcours.php?action=goAjouter" title="Ajouter un parcours" class="lien_red lien_demi margin_lien">';
              echo '<img src="../../includes/icons/petitspedestres/parcours_grey.png" alt="parcours_grey" class="image_lien" />';
              echo '<div class="titre_lien">AJOUTER PARCOURS</div>';
            echo '</a>';

            // Modification parcours
            echo '<a href="parcours.php?id_parcours=' . $_GET['id_parcours'] . '&action=goModifier" title="Modifier le parcours" class="lien_red lien_demi">';
              echo '<img src="../../includes/icons/petitspedestres/edit_grey.png" alt="edit_grey" class="image_lien" />';
              echo '<div class="titre_lien">MODIFIER PARCOURS</div>';
            echo '</a>';

            /***********/
            /* Contenu */
            /***********/
            echo '<div class="PP-parcours">';
              echo '<div class="PP-titre">';
                echo $parcours->getNom();
              echo '</div>';

              echo '<div class="PP-texte">';
                echo 'Distance : ' . $parcours->getDistance() . ' km';
              echo '</div>';

              echo '<div class="PP-texte">';
                echo 'Lieu : ' . $parcours->getLieu();
              echo '</div>';

              if (!empty($parcours->getImage()))
                echo '<img src="' . $parcours->getImage() .'" alt="' . $parcours->getNom() . '" class="PP-image" />';
            echo '</div>';
          }
        ?>
      </article>
    </section>

    <!-- Pied de page -->
    <footer>
      <?php include('../../includes/common/footer_mobile.php'); ?>
    </footer>
  </body>
</html>