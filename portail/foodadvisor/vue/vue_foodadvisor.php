<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "FA";
      $style_head      = "styleFA.css";
      $script_head     = "scriptFA.js";
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
        $title = "Les enfants ! À table !";

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

          /*********/
          /* Liens */
          /*********/
          echo '<div class="zone_liens_saisie">';
            // Saisie utilisateur
            if ($actions["saisir_choix"] == true)
            {
              echo '<a id="saisiePropositions" title="Proposer où manger" class="lien_categorie">';
                echo '<div class="zone_logo_lien"><img src="../../includes/icons/common/food_advisor.png" alt="food_advisor" class="image_lien" /></div>';
                echo '<div class="zone_texte_lien">Proposer où manger</div>';
              echo '</a>';
            }

            // Restaurants
            echo '<a href="restaurants.php?action=goConsulter" title="Les restaurants" class="lien_categorie">';
              echo '<div class="zone_logo_lien"><img src="../../includes/icons/foodadvisor/restaurants.png" alt="restaurants" class="image_lien" /></div>';
              echo '<div class="zone_texte_lien">Les restaurants</div>';
            echo '</a>';
          echo '</div>';

          /****************/
          /* Saisie choix */
          /****************/
          if ($actions["saisir_choix"] == true)
            include('vue/vue_saisie_choix.php');

          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /*************************/
          /* Détails détermination */
          /*************************/
          include('vue/vue_details_determination.php');

          /***********************************************/
          /* Propositions, choix et résumé de la semaine */
          /***********************************************/
          echo '<div class="zone_propositions_determination">';
            // Utilisateurs
            include('vue/vue_utilisateurs.php');

            // Propositions
            include('vue/vue_propositions.php');

            // Mes choix
            include('vue/vue_mes_choix.php');

            // Résumé de la semaine
            include('vue/vue_resume_semaine.php');
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

    <!-- Données JSON -->
    <script>
      // Récupération liste lieux et restaurants pour le script
      var listeLieux          = <?php echo $listeLieuxJson; ?>;
      var listeRestaurants    = <?php echo $listeRestaurantsJson; ?>;
      var detailsPropositions = <?php echo $detailsPropositions; ?>;
      var userSession         = <?php echo json_encode($_SESSION['user']['identifiant']); ?>;
    </script>
  </body>
</html>
