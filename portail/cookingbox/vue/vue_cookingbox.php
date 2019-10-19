<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "CB";
      $style_head      = "styleCB.css";
      $script_head     = "scriptCB.js";
      $chat_head       = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Onglets -->
		<header>
      <?php
        $title = "Cooking Box";

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

          /*********************************/
          /* Gâteaux des semaines n et n+1 */
          /*********************************/
          include('vue/vue_semaines.php');
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
      // Récupération liste utilisateurs pour le script
      var listeUsers = <?php echo $listeUsersJson; ?>;
    </script>
  </body>
</html>
