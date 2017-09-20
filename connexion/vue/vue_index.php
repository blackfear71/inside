<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />

		<title>Inside</title>
  </head>

	<body>
		<header>
			<div class="main_title">
				<div class="zone_title">
					<div class="trait_title_1"></div>
					<img src="includes/icons/inside.png" alt="inside" class="inside" />
					<div class="trait_title_2"></div>
				</div>
			</div>

			<div class="mask">
				<div class="triangle"></div>
			</div>
		</header>

		<section>
      <!-- Messages d'alerte -->
      <?php
        include('includes/alerts.php');
      ?>

			<article class="article_portail">
				<div class="bloc_identification">
					<!-- Connexion -->
					<form method="post" action="index.php?action=doConnecter">
						<input type="text" name="login" placeholder="Identifiant" maxlength="100" class="monoligne" required />
						<input type="password" name="mdp" placeholder="Mot de passe" maxlength="100" class="monoligne" required />
						<input type="submit" name="connect" value="CONNEXION" class="bouton_connexion" />
					</form>
				</div>

				<!-- Lien mot de passe perdu -->
				<a href="index.php?action=goChangerMdp" class="forgot_password">
					Mot de passe oublié ?
				</a>

				<!-- Lien inscription -->
				<a href="index.php?action=goInscription" class="subcribe">
					S'inscrire
				</a>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('includes/footer.php'); ?>
		</footer>
  </body>
</html>
