<!DOCTYPE html>
<html lang="fr" ng-app="parcoursApp">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "PP";
      $style_head  = "stylePP.css";
      $script_head = "scriptPP.js";
      $chat_head   = true;
      $angular = true;
      //$bootstrap = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Onglets -->
    <header>
      <?php
        $title = "Les Petits Pédestres";

        include('../../includes/common/header.php');
        include('../../includes/common/onglets.php');
      ?>
		</header>

    <section>
      <!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu" class="aside_nav">
				<?php
					$ajouter_parcours = true;

					include('../../includes/common/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

      <article>

        <?php
          // Boutons missions
          $zone_inside = "article";
          include('../../includes/common/missions.php');
        ?>


        <parcours-list></parcours-list>

  				<!-- Monsieur et madame Santé ont un fils, comment qu'y s'appelle ?
  				     Réponse : Parcours.
  				     C'est nul ? Oui, c'est nul. -->

    	</article>

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

    <!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>

    <script>
      var listeParcoursJson = <?php echo $parcoursJson; ?>;
    </script>
  </body>
</html>
