<!DOCTYPE html>
<html>
  <head>
  	<meta charset="utf-8" />
    <meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
    <meta name="keywords" content="Inside, portail, CDS Finance" />

  	<link rel="icon" type="image/png" href="/inside/favicon.png" />
  	<link rel="stylesheet" href="/inside/style.css" />
  	<link rel="stylesheet" href="stylePP.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script type="text/javascript" src="/inside/script.js"></script>

  	<title>Inside - PP</title>
  </head>

	<body>
    <!-- Onglets -->
		<header>
      <?php
        $title = "Les Petits Pédestres";

        include('../../includes/header.php');
        include('../../includes/onglets.php');
      ?>
		</header>

		<section>
      <!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect       = true;
          $ajouter_parcours = true;
					$back             = true;
					$ideas            = true;
					$reports          = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
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
			<?php include('../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
