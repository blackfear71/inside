<?php
	// Contrôles communs Utilisateurs
	include('../includes/controls_users.php');
?>

<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />

		<title>Inside - GOT</title>
  </head>

	<body>
		<!-- Onglets -->
		<header>
			<?php include('../includes/onglets.php') ; ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect = true;
					$profil = true;
					$back = true;
					$ideas = true;
					$bug = true;

					include('../includes/aside.php');
				?>
			</aside>

			<article class="article_portail">
				<img src="travaux.png" alt="travaux" title="En cours de réalisation..." style="display: block; margin-left: auto; margin-right: auto; width: 300px; padding-top: 100px;" />

				<p style="font-family: robotolight, Verdana, sans-serif; font-size: 150%; text-align: center; margin-left: auto; margin-right: auto;">Page en cours de réalisation...</p>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>
  </body>
</html>
