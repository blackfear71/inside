<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "CB";
      $style_head      = "styleCB.css";
      $script_head     = "scriptCB.js";
      $angular_head    = false;
      $chat_head       = true;
      $datepicker_head = false;
      $masonry_head    = true;
      $exif_head       = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Entête -->
		<header>
      <?php
        $title = "Cooking Box";

        include('../../includes/common/header.php');
			  include('../../includes/common/onglets.php');
      ?>
		</header>

    <!-- Contenu -->
		<section>
			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

			<article>
        <?php
          /********************/
          /* Boutons missions */
          /********************/
          $zone_inside = "article";
          include('../../includes/common/missions.php');

          if (!empty($listeSemaines))
          {
            /*******************/
            /* Liens de saisie */
            /*******************/
            echo '<div class="zone_liens_saisie">';
              // Bouton saisie
              echo '<a id="ajouterRecette" title="Ajouter un gâteau ou une recette" class="lien_categorie">';
                echo '<div class="zone_logo_lien"><img src="../../includes/icons/common/cooking_box.png" alt="cooking_box" class="image_lien" /></div>';
                echo '<div class="zone_texte_lien">Ajouter un gâteau ou une recette</div>';
              echo '</a>';
            echo '</div>';
          }

          /*****************************/
          /* Zone de saisie de recette */
          /*****************************/
          include('vue/vue_saisie_recette.php');

          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';

          /*********************************/
          /* Gâteaux des semaines n et n+1 */
          /*********************************/
          include('vue/vue_semaines.php');

          /**********/
          /* Années */
          /**********/
          include('vue/vue_onglets.php');

          /************/
          /* Recettes */
          /************/
          include('vue/vue_recettes.php');
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
      // Récupération liste semaines par années pour le script
      var listWeeks = <?php echo $listeSemainesJson; ?>;

      // Récupération liste utilisateurs pour le script
      var listUsers = <?php echo $listeUsersJson; ?>;

      // Récupération liste recettes pour le script
      var listRecipes = <?php echo $recettesJson; ?>;

      // Récupération utilisateur connecté
      var userSession = <?php echo json_encode($_SESSION['user']['identifiant']); ?>;
    </script>
  </body>
</html>
