<!DOCTYPE html>
<html>
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
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu" class="aside_nav">
				<?php
					$disconnect  = true;
					$back        = true;
					$ideas       = true;
					$reports     = true;

					include('../../includes/common/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

			<article>
        <?php
          // Liens
          echo '<div class="zone_liens_saisie">';
            echo '<a onclick="afficherMasquer(\'zone_add_restaurant\');" title="Ajouter un restaurant" class="lien_categorie">';
              echo '<div class="zone_logo_lien"><img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurant" class="image_lien" /></div>';
              echo '<div class="zone_texte_lien">Ajouter un restaurant</div>';
            echo '</a>';
          echo '</div>';

          // Saisie restaurant
          include('vue/saisie_restaurant.php');

          // Fiches des restaurants
          include('vue/fiches_restaurants.php');
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
