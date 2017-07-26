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
	<link rel="icon" type="image/png" href="../../favicon.png" />
	<link rel="stylesheet" href="../../style.css" />
	<title>Inside - RG</title>
	<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside, portail, CDS Finance" />
  </head>

	<body>

		<header onclick="document.getElementById('menu').style.display='none';">
			<?php include('../../includes/onglets.php') ; ?>
		</header>

		<section>
			<aside>
				<!-- Boutons d'action -->
				<?php
					$disconnect = true;
					$profil = true;
					$menu_rg = true;
					$add_article = true;
					$back = true;
					$ideas = true;
					$bug = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<article class="article_portail" onclick="document.getElementById('menu').style.display='none';" style="padding-top: 10px;">
				<?php
					include('banniere.php');
				?>

				<div class="search_bar_liste">
					<?php
						include('search_form.php');
					?>
				</div>

				<?php
					include('search_results.php');
				?>
			</article>
		</section>

		<footer onclick="document.getElementById('menu').style.display='none';">
			<?php include('../../includes/footer.php'); ?>
		</footer>

  </body>

</html>
