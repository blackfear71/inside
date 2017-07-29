<?php
	session_start();

	// Si déjà connecté
	if (isset($_SESSION['connected']) AND $_SESSION['connected'] == true AND $_SESSION['identifiant'] != "admin")
		header('location: portail/portail.php');
	elseif (isset($_SESSION['connected']) AND $_SESSION['connected'] == true AND $_SESSION['identifiant'] == "admin")
		header('location: administration/administration.php');
	else
		$_SESSION['connected'] = false;

	if (!isset($_SESSION['wrong']))
		$_SESSION['wrong'] = false;

	if (!isset($_SESSION['not_yet']))
		$_SESSION['not_yet'] = false;

	include('includes/init_session.php');
?>

<!DOCTYPE html>
<html>

  <head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="favicon.png" />
	<link rel="stylesheet" href="style.css" />
	<title>Inside</title>
	<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside, portail, CDS Finance" />
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
			<article>

				<div class="bloc_identification">
					<form method="post" action="connexion/connect.php">
						<input type="text" name="login" placeholder="Identifiant" maxlength="100" class="monoligne" required />
						<input type="password" name="mdp" placeholder="Mot de passe" maxlength="100" class="monoligne" required />
						<input type="submit" name="connect" value="CONNEXION" class="bouton_connexion"/>
					</form>

					<?php
						if ($_SESSION['wrong'] == true)
						{
							echo '<div class="wrong_password">Mot de passe incorrect ou utilisateur inconnu.</div>';
							$_SESSION['wrong'] = false;
						}

						if ($_SESSION['not_yet'] == true)
						{
							echo '<div class="wrong_password" style="margin-bottom: -114px;">Veuillez patienter jusqu\'à ce que l\'administrateur valide votre inscription.</div>';
							$_SESSION['not_yet'] = false;
						}
					?>
				</div>

				<a href="connexion/forgot_password.php" class="forgot_password">
					Mot de passe oublié ?
				</a>

				<a href="connexion/inscription.php" class="subcribe">
					S'inscrire
				</a>
			</article>
		</section>

		<footer>
			<?php include('includes/footer.php'); ?>
		</footer>

  </body>

</html>
