<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $titleHead       = 'Collector Room';
      $styleHead       = 'styleCO.css';
      $scriptHead      = 'scriptCO.js';
      $angularHead     = false;
      $chatHead        = true;
      $datepickerHead  = true;
      $masonryHead     = true;
      $exifHead        = true;
      $html2canvasHead = false;
      $jqueryCsv       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Entête -->
		<header>
      <?php
        $title = 'Collector Room';

        include('../../includes/common/header.php');
			  include('../../includes/common/onglets.php');
      ?>
		</header>

    <!-- Contenu -->
		<section>
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

          /******************************/
          /* Liens de saisie et filtres */
          /******************************/
          echo '<div class="zone_liens_saisie">';
            echo '<a id="ajouterCollector" title="Ajouter une phrase culte" class="lien_categorie">';
              echo '<div class="zone_logo_lien"><img src="../../includes/icons/collector/phrases.png" alt="comments" class="image_lien" /></div>';
              echo '<div class="zone_texte_lien">Ajouter une phrase culte</div>';
            echo '</a>';

            echo '<a id="ajouterImage" title="Ajouter une image" class="lien_categorie">';
              echo '<div class="zone_logo_lien"><img src="../../includes/icons/collector/images.png" alt="images" class="image_lien" /></div>';
              echo '<div class="zone_texte_lien">Ajouter une image</div>';
            echo '</a>';

            echo '<div class="zone_filtres">';
              // Tris
              echo '<select id="applySort" class="listbox_filtre">';
                foreach ($ordersAndFilters['tris'] as $tri)
                {
                  if ($_GET['sort'] == $tri['value'])
                    echo '<option value="' . $tri['value'] . '" selected>' . $tri['label'] . '</option>';
                  else
                    echo '<option value="' . $tri['value'] . '">' . $tri['label'] . '</option>';
                }
              echo '</select>';

              // Filtres
              echo '<select id="applyFilter" class="listbox_filtre">';
                foreach ($ordersAndFilters['filtres'] as $filtre)
                {
                  if ($_GET['filter'] == $filtre['value'])
                    echo '<option value="' . $filtre['value'] . '" selected>' . $filtre['label'] . '</option>';
                  else
                    echo '<option value="' . $filtre['value'] . '">' . $filtre['label'] . '</option>';
                }
              echo '</select>';
            echo '</div>';
          echo '</div>';

          /**********************************/
          /* Zone de saisie de phrase culte */
          /**********************************/
          include('vue/web/vue_saisie_collector.php');

          /**************************/
          /* Zone de saisie d'image */
          /**************************/
          include('vue/web/vue_saisie_image.php');

          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /********************************/
          /* Affichage des phrases cultes */
          /********************************/
          include('vue/web/vue_table_collectors.php');

          /**************/
          /* Pagination */
          /**************/
          include('vue/web/vue_pagination.php');
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
