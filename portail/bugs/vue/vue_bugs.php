<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
    <link rel="stylesheet" href="/inside/style.css" />
		<link rel="stylesheet" href="styleBugs.css" />

		<title>Inside - Bug</title>
  </head>

	<body>
		<header>
			<div class="main_title">
				<img src="../../includes/images/bugs_band.png" alt="bugs_band" class="bandeau_categorie_2" />
			</div>

			<div class="mask">
				<div class="triangle"></div>
			</div>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect = true;
					$profil = true;
					$back = true;
					$ideas = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article class="article_portail">
				<p class="intro_bug">
					Le site ne présente aucun bug. Si toutefois vous pensez être tombé sur ce qui prétend en être un, vous pouvez le signaler via le formulaire ci-dessous.
					Ce que nous appellerons désormais "évolution" sera traitée dans les plus brefs délais par une équipe exceptionnelle, toujours à votre écoute pour vous
					servir au mieux.
				</p>

				<form method="post" action="bugs.php?action=doSignaler">
					<input type="text" name="subject_bug" placeholder="Objet" maxlength="255" class="saisie_titre_bug" required />

					<select name="type_bugs" class="saisie_type_bug">
						<option value="B">Bug</option>
						<option value="E">Evolution</option>
					</select>

					<div class="trait_bugs"></div>

					<textarea placeholder="Description du problème" name="content_bug" class="saisie_contenu_bug"></textarea>

					<div class="trait_bugs"></div>

					<input type="submit" name="report" value="Soumettre" class="submit_bug" />
				</form>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
