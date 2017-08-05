<!DOCTYPE html>
<html>

  <head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="../../../favicon.png" />
	<link rel="stylesheet" href="../../../style.css" />
	<title>Inside - PP</title>
	<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside, portail, CDS Finance" />
  </head>

	<body>

		<header>
			<?php include('../../../includes/onglets.php') ; ?>
		</header>

		<section>
			<aside>
				<!-- Boutons d'action -->
				<?php
					$disconnect = true;
					$profil = true;
					$modify_film = false;
					$delete_film = false;
					$back = true;
					$ideas = true;
					$bug = true;

					include('../../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../../includes/alerts.php');
			?>

			<article class="article_portail">
                <!-- Bandeau catégorie -->
				<img src="../../../includes/images/petits_pedestres_band.png" alt="petits_pedestres_band" class="bandeau_categorie" />
                <p>Parcours : <?php echo $parcours->getNom(); ?></p>
            </article>

        </section>

		<footer>
			<?php include('../../../includes/footer.php'); ?>
		</footer>

  </body>

</html>
