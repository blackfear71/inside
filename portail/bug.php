<?php
	// Contrôles communs Utilisateurs
	include('../includes/controls_users.php');

	// Initialisation des variables SESSION pour la création d'articles
	//include('../includes/init_session.php');
?>

<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />

		<title>Inside - Bug</title>
  </head>

	<body>
		<header>
			<div class="main_title">
				<img src="../includes/images/bugs_band.png" alt="bugs_band" class="bandeau_categorie_2" />
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

					include('../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../includes/alerts.php');
			?>

			<article class="article_portail">
				<p class="intro_bug">
					Le site ne présente aucun bug. Si toutefois vous pensez être tombé sur ce qui prétend en être un, vous pouvez le signaler via le formulaire ci-dessous.
					Ce que nous appellerons désormais "évolution" sera traitée dans les plus brefs délais par une équipe exceptionnelle, toujours à votre écoute pour vous
					servir au mieux.
				</p>

				<form method="post" action="bugs/report_bug.php">
					<input type="text" name="subject" placeholder="Objet" maxlength="255" class="saisie_titre_2" required />

					<select name="type_bug" class="saisie_type_bug">
						<option value="B">Bug</option>
						<option value="E">Evolution</option>
					</select>

					<div class="trait"></div>

					<textarea placeholder="Description du problème" name="contenu_bug" class="saisie_contenu"></textarea>

					<div class="trait"></div>

					<input type="submit" name="report" value="Soumettre" class="saisie_valider" />
				</form>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>
  </body>
</html>
