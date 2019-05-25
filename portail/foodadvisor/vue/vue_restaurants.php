<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head   = "FA";
      $style_head   = "styleFA.css";
      $script_head  = "scriptFA.js";
      $chat_head    = true;
      $masonry_head = true;
      $exif_head    = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Onglets -->
		<header>
      <?php
        $title = "Les enfants ! À table !";

        include('../../includes/common/header.php');
			  include('../../includes/common/onglets.php');
      ?>
		</header>

		<section>
			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

			<article>
        <?php
          // Boutons missions
          $zone_inside = "article";
          include('../../includes/common/missions.php');

          /*******************/
          /* Liens de saisie */
          /*******************/
          echo '<div class="zone_liens_saisie">';
            echo '<a id="saisieRestaurant" title="Ajouter un restaurant" class="lien_categorie">';
              echo '<div class="zone_logo_lien"><img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurant" class="image_lien" /></div>';
              echo '<div class="zone_texte_lien">Ajouter un restaurant</div>';
            echo '</a>';
          echo '</div>';

          /*********************/
          /* Saisie restaurant */
          /*********************/
          include('vue/vue_saisie_restaurant.php');

          // Liens vers lieux
          echo '<div class="zone_liens_lieux">';
            foreach ($listeLieux as $lieu)
            {
              echo '<a id="link_' . formatId($lieu) . '" class="lien_lieu lienLieu">';
                echo '<div class="image_lieu"></div>';
                echo $lieu;
              echo '</a>';
            }
          echo '</div>';

          /**************************/
          /* Fiches des restaurants */
          /**************************/
          include('vue/vue_fiches_restaurants.php');
        ?>
			</article>

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
