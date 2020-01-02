<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "CO";
      $style_head      = "styleCO.css";
      $script_head     = "scriptCO.js";
      $angular_head    = false;
      $chat_head       = true;
      $datepicker_head = true;
      $masonry_head    = true;
      $exif_head       = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Entête -->
		<header>
      <?php
        $title = "Collector Room";

        include('../../includes/common/header.php');
			  include('../../includes/common/onglets.php');
      ?>
		</header>

    <!-- Contenu -->
		<section>
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

			<article>
        <?php
          /********************/
          /* Boutons missions */
          /********************/
          $zone_inside = "article";
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
                if ($_GET['sort'] == "dateDesc")
                  echo '<option value="dateDesc" selected>Du plus récent au plus vieux</option>';
                else
                  echo '<option value="dateDesc">Du plus récent au plus vieux</option>';

                if ($_GET['sort'] == "dateAsc")
                  echo '<option value="dateAsc" selected>Du plus vieux au plus récent</option>';
                else
                  echo '<option value="dateAsc">Du plus vieux au plus récent</option>';
              echo '</select>';

              // Filtres
              echo '<select id="applyFilter" class="listbox_filtre">';
                if ($_GET['filter'] == "none")
                  echo '<option value="none" selected>Aucun filtre</option>';
                else
                  echo '<option value="none">Aucun filtre</option>';

                if ($_GET['filter'] == "noVote")
                  echo '<option value="noVote" selected>Non votés</option>';
                else
                  echo '<option value="noVote">Non votés</option>';

                if ($_GET['filter'] == "meOnly")
                  echo '<option value="meOnly" selected>Mes phrases cultes</option>';
                else
                  echo '<option value="meOnly">Mes phrases cultes</option>';

                if ($_GET['filter'] == "byMe")
                  echo '<option value="byMe" selected>Mes phrases rapportées</option>';
                else
                  echo '<option value="byMe">Mes phrases rapportées</option>';

                if ($_GET['filter'] == "usersOnly")
                  echo '<option value="usersOnly" selected>Les phrases cultes des autres utilisateurs</option>';
                else
                  echo '<option value="usersOnly">Les phrases cultes des autres utilisateurs</option>';

                if ($_GET['filter'] == "othersOnly")
                  echo '<option value="othersOnly" selected>Les phrases cultes hors utilisateurs</option>';
                else
                  echo '<option value="othersOnly">Les phrases cultes hors utilisateurs</option>';

                if ($_GET['filter'] == "textOnly")
                  echo '<option value="textOnly" selected>Seulement les phrases cultes</option>';
                else
                  echo '<option value="textOnly">Seulement les phrases cultes</option>';

                if ($_GET['filter'] == "picturesOnly")
                  echo '<option value="picturesOnly" selected>Seulement les images</option>';
                else
                  echo '<option value="picturesOnly">Seulement les images</option>';

                if ($_GET['filter'] == "topCulte")
                  echo '<option value="topCulte" selected>Les tops cultes</option>';
                else
                  echo '<option value="topCulte">Les tops cultes</option>';
              echo '</select>';
            echo '</div>';
          echo '</div>';

          /**********************************/
          /* Zone de saisie de phrase culte */
          /**********************************/
          include('vue/vue_saisie_collector.php');

          /**************************/
          /* Zone de saisie d'image */
          /**************************/
          include('vue/vue_saisie_image.php');

          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /********************************/
          /* Affichage des phrases cultes */
          /********************************/
          include('vue/vue_table_collectors.php');

          /**************/
          /* Pagination */
          /**************/
          include('vue/vue_pagination.php');
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
