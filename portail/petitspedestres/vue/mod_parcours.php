<!DOCTYPE html>
<html>
  <head>
  	<meta charset="utf-8" />
    <meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
    <meta name="keywords" content="Inside, portail, CDS Finance" />

  	<link rel="icon" type="image/png" href="/inside/favicon.png" />
  	<link rel="stylesheet" href="/inside/style.css" />
  	<link rel="stylesheet" href="../stylePP.css" />

  	<title>Inside - PP</title>
  </head>

	<body>
    <!-- Onglets -->
		<header>
			<?php include('../../../includes/onglets.php') ; ?>
		</header>

		<section>
      <!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect = true;
					$profil = true;
					$modify_film = false;
					$modify_parcours = false;
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

				<div class="contenu_saisie_avancee">
					<form method="post" action="../controleur/parcours.php?id=<?php echo $parcours->getId(); ?>&action=domodifier" class="form_saisie_avancee">
						<div class="zone_saisie_avancee_infos">
							<label class="label_parcours">Nom : </label>
							<input type="text" value="<?php echo $parcours->getNom(); ?>" name="name" class="monoligne_film"/><br />
							<label class="label_parcours">Distance : </label>
							<input type="text" value="<?php echo $parcours->getDistance(); ?>" name="dist" class="monoligne_film"/><br />
							<label class="label_parcours">Lieu : </label>
							<input type="text" value="<?php echo $parcours->getLieu(); ?>" name="location" class="monoligne_film"/><br />
							<label class="label_parcours">Url image : </label>
							<input type="text" value="<?php echo $parcours->getImage(); ?>" name="picurl" class="monoligne_film"/><br />
						</div>

						<br /><br />

						<input type="submit" name="modification" value="Valider" />
					</form>
        </div>
      </article>
    </section>

    <!-- Pied de page -->
		<footer>
			<?php include('../../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
