<?php
	session_start();

	if (isset($_SESSION['connected']) AND $_SESSION['connected'] == true AND $_SESSION['identifiant'] != "admin")
		header('location: ../portail/portail.php');

	if ($_SESSION['connected'] == false)
		header('location: ../index.php');

	if (!isset($_SESSION['film_deleted']))
		$_SESSION['film_deleted'] = NULL;

	if (!isset($_SESSION['film_reseted']))
		$_SESSION['film_reseted'] = NULL;
?>

<!DOCTYPE html>
<html>

  <head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="../favicon.png" />
	<link rel="stylesheet" href="../style.css" />
  <title>Inside CGI - Films</title>
	<meta name="description" content="Bienvenue sur Inside CGI, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside CGI, portail, CDS Finance" />
  </head>

	<body>

		<header>
			<div class="main_title">
				Gestion des films
			</div>

			<div class="mask">
				<div class="triangle"></div>
			</div>
		</header>

		<section>
			<aside>
				<!-- Boutons d'action -->
				<?php
					$disconnect = true;
					$back_admin = true;

					include('../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../includes/alerts.php');
			?>

			<article class="article_portail">

				<?php
					// Tableau des demandes
					include('table_films.php');
				?>

			</article>
		</section>

		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>

  </body>

</html>
