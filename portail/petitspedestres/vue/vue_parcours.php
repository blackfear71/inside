<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "PP";
      $style_head  = "stylePP.css";
      $script_head = "";
      $chat_head   = true;

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
					$modify_parcours  = true;

					include('../../includes/common/aside.php');
				?>
			</aside>

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

          /*******************/
          /* Chargement page */
          /*******************/
          echo '<div class="zone_loading_page">';
            echo '<div id="loading_page" class="loading_page"></div>';
          echo '</div>';
        ?>

				<div class="PP-parcours">
          <div class="PP-titre">
            <?php echo $parcours->getNom(); ?>
          </div>

          <p>
            Distance : <?php echo $parcours->getDistance() . ' km'; ?><br/>
            Lieu : <?php echo $parcours->getLieu(); ?>

            <?php
              if ($parcours->isImageSet())
              {
                echo '<br/><img src="' . $parcours->getImage() .'" alt="' . $parcours->getNom() . '" class="PP-image" /><br/>';
              }
            ?>
          </p>
        </div>
      </article>

      <?php include('../../includes/chat/chat.php'); ?>
    </section>

    <!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
