<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head      = "PP";
      $style_head      = "stylePP.css";
      $script_head     = "";
      $angular_head    = false;
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
      <!-- Messages d'alerte -->
      <?php include('../../includes/common/alerts.php'); ?>

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

				<div class="PP-contenu-saisie">
					<form method="post" action="parcours.php?id=<?php echo $parcours->getId(); ?>&action=domodifier" class="PP-form-saisie">
						<div class="PP-zone-saisie-avancee-infos">
							<label class="label_parcours">Nom : </label>
							<input type="text" value="<?php echo $name; ?>" name="name" class="PP-monoligne" /><br />
							<label class="label_parcours">Distance : </label>
							<input type="text" value="<?php echo $dist; ?>" name="dist" class="PP-monoligne" /><br />
							<label class="label_parcours">Lieu : </label>
							<input type="text" value="<?php echo $location; ?>" name="location" class="PP-monoligne" /><br />
							<label class="label_parcours">Url image : </label>
							<input type="text" value="<?php echo $picture; ?>" name="picurl" class="PP-monoligne" /><br />
						</div>

            <br /><br />

						<input type="submit" name="modification" value="Valider" />
					</form>
        </div>
      </article>

      <!-- Chat -->
      <?php include('../../includes/chat/chat.php'); ?>
    </section>

    <!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
