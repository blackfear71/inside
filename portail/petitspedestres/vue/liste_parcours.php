<!DOCTYPE html>
<html lang="fr" ng-app="parcoursApp">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "Les Petits Pédestres";
      $style_head      = "stylePP.css";
      $script_head     = "scriptPP.js";
      $angular_head    = true;
      $chat_head       = true;
      $datepicker_head = false;
      $masonry_head    = false;
      $exif_head       = false;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
    <!-- Entête -->
    <header>
      <?php
        $title = "Les Petits Pédestres";

        include('../../includes/common/header.php');
        include('../../includes/common/onglets.php');
      ?>
		</header>

    <!-- Contenu -->
    <section>
      <!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu" class="aside_nav">
				<?php
					$ajouter_parcours = true;

					include('../../includes/common/aside.php');
				?>
			</aside>

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
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';
        ?>

        <parcours-list></parcours-list>

  			<!-- Monsieur et madame Santé ont un fils, comment qu'y s'appelle ?
  				   Réponse : Parcours.
  				   C'est nul ? Oui, c'est nul. -->
    	</article>

      <!-- Chat -->
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
