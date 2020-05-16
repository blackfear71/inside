<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "Movie House";
      $style_head      = "styleMH.css";
      $script_head     = "scriptMH.js";
      $angular_head    = false;
      $chat_head       = true;
      $datepicker_head = true;
      $masonry_head    = true;
      $exif_head       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Entête -->
		<header>
      <?php
        $title = "Movie House";

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
          $zone_inside = "article";
          include('../../includes/common/missions.php');

          /*******************/
          /* Liens de saisie */
          /*******************/
          echo '<div class="zone_liens_saisie">';
            // Bouton saisie
            echo '<a id="ajouterFilm" title="Ajouter un film" class="lien_categorie">';
              echo '<div class="zone_logo_lien"><img src="../../includes/icons/common/movie_house.png" alt="movie_house" class="image_lien" /></div>';
              echo '<div class="zone_texte_lien">Ajouter un film</div>';
            echo '</a>';
          echo '</div>';

          /***************/
          /* Saisie film */
          /***************/
          include('vue/vue_saisie_film.php');

          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /*****************/
          /* Vues & Années */
          /*****************/
          include('vue/vue_onglets.php');

          /*********************/
          /* Accueil ou fiches */
          /*********************/
          echo '<div class="zone_movies_right">';
            switch ($_GET['view'])
            {
              case "cards":
                include("vue/vue_films_fiches.php");
               break;

              case "home":
              default:
                include("vue/vue_films_accueil.php");
                break;
            }
          echo '</div>';
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
