<!DOCTYPE html>
<html>
  <head>
  	<meta charset="utf-8" />
    <meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
    <meta name="keywords" content="Inside, portail, CDS Finance" />

  	<link rel="icon" type="image/png" href="../../favicon.png" />
  	<link rel="stylesheet" href="../../style.css" />
  	<link rel="stylesheet" href="stylePP.css" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  	<title>Inside - PP</title>
  </head>

	<body>
    <!-- Onglets -->
		<header>
      <?php
        $title = "Les Petits Pédestres";

        include('../../includes/onglets.php') ;
      ?>
		</header>

		<section>
      <!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect = true;
					$profil     = true;
					$back       = true;
					$ideas      = true;
					$reports    = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article class="article_portail">
				<div class="contenu_saisie_avancee">
					<form method="post" action="parcours.php?action=doajouter" class="form_saisie_avancee">
						<div class="zone_saisie_avancee_infos">
							<label class="label_parcours">Nom : </label>
							<input type="text" placeholder="Nom parcours" name="name" class="monoligne_film" /><br />
							<label class="label_parcours">Distance : </label>
							<input type="text" placeholder="Distance (km)" name="dist" class="monoligne_film" /><br />
							<label class="label_parcours">Lieu : </label>
							<input type="text" placeholder="Lieu" name="location" class="monoligne_film" /><br />
							<label class="label_parcours">Url image : </label>
							<input type="text" placeholder="Url de l'image" name="picurl" class="monoligne_film" /><br />
						</div>

            <br /><br />

						<input type="submit" name="modification" value="Valider" />
					</form>
        </div>
      </article>
    </section>

    <!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
