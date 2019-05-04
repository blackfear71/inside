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
					$disconnect       = true;
          $ajouter_parcours = true;
					$back             = true;

					include('../../includes/common/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

			<article>
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

      <?php include('../../includes/chat/chat.php'); ?>
    </section>

    <!-- Pied de page -->
		<footer>
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
