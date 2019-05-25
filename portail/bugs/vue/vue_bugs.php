<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "Bugs";
      $style_head  = "styleBugs.css";
      $script_head = "scriptBugs.js";
      $chat_head   = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "Demandes d'évolution";

        include('../../includes/common/header.php');
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

          /*******************/
          /* Liens de saisie */
          /*******************/
          echo '<div class="zone_liens_saisie">';
            echo '<a id="ajouterRapport" title="Rapporter un bug ou une évolution" class="lien_categorie">';
              echo '<div class="zone_logo_lien"><img src="../../includes/icons/common/bug.png" alt="bug" class="image_lien" /></div>';
              echo '<div class="zone_texte_lien">Rapporter un bug ou une évolution</div>';
            echo '</a>';
          echo '</div>';

          /*****************************/
          /* Zone de saisie de rapport */
          /*****************************/
          include('vue/vue_saisie_rapport.php');

          /***********/
          /* Onglets */
          /***********/
          include('vue/vue_onglets.php');

          /****************/
          /*** Rapports ***/
          /****************/
          include('vue_liste_rapports.php');
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
