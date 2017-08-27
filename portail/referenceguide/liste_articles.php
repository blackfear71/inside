<?php
	// Contrôles communs Utilisateurs
	include('../../includes/controls_users.php');

	include('../../includes/init_session.php');

	if (!isset($_GET['search']) OR $_GET['search'] == "no")
		$_SESSION['search'] = "";
?>

<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />

		<title>Inside - RG</title>
  </head>

	<body>
		<!-- Onglets -->
		<header onclick="document.getElementById('menu').style.display='none';">
			<?php include('../../includes/onglets.php') ; ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect = true;
					$profil = true;
					$menu_rg = true;
					$add_article = true;
					$back = true;
					$ideas = true;
					$reports = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<article class="article_portail" onclick="document.getElementById('menu').style.display='none';" style="padding-top: 10px;">
				<!-- Bannière -->
				<?php
					include('banniere.php');
				?>

				<!-- Barre de recherche -->
				<div class="search_bar_liste">
					<?php
						include('search_form.php');
					?>
				</div>

				<!-- Affichage des résultats de la recherche -->
				<?php
					include('search_results.php');
				?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer onclick="document.getElementById('menu').style.display='none';">
			<?php include('../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
