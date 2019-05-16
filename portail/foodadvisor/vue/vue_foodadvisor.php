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
          include($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/common/missions.php');

          // Liens
          echo '<div class="zone_liens_saisie">';
            // Saisie utilisateur
            echo '<a id="saisiePropositions" title="Proposer où manger" class="lien_categorie">';
              echo '<div class="zone_logo_lien"><img src="../../includes/icons/common/food_advisor.png" alt="food_advisor" class="image_lien" /></div>';
              echo '<div class="zone_texte_lien">Proposer où manger</div>';
            echo '</a>';

            // Restaurants
            echo '<a href="restaurants.php?action=goConsulter" title="Les restaurants" class="lien_categorie">';
              echo '<div class="zone_logo_lien"><img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurants" class="image_lien" /></div>';
              echo '<div class="zone_texte_lien">Les restaurants</div>';
            echo '</a>';
          echo '</div>';

          // Saisie choix
          include('vue/vue_saisie_choix.php');

          // Détails détermination
          include('vue/vue_details_determination.php');

          // Propositions, choix et résumé de la semaine
          echo '<div class="zone_propositions_determination">';
            // Propositions
            include('vue/vue_propositions.php');

            // Mes choix
            include('vue/vue_mes_choix.php');

            // Résumé de la semaine
            include('vue/vue_resume_semaine.php');
          echo '</div>';
        ?>
			</article>

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>

    <!-- Données JSON -->
    <script>
      // Récupération liste lieux et restaurants pour le script
      var listeLieux          = <?php echo $listeLieuxJson; ?>;
      var listeRestaurants    = <?php echo $listeRestaurantsJson; ?>;
    </script>
  </body>
</html>
