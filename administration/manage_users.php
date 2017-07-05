<?php
	session_start();

	if (isset($_SESSION['connected']) AND $_SESSION['connected'] == true AND $_SESSION['identifiant'] != "admin")
		header('location: ../portail/portail.php');

	if ($_SESSION['connected'] == false)
		header('location: ../index.php');

	if (!isset($_SESSION['user_ask_id']) OR !isset($_SESSION['user_ask_name']) OR !isset($_SESSION['new_password']))
	{
		$_SESSION['user_ask_id'] = "";
		$_SESSION['user_ask_name'] = "";
		$_SESSION['new_password'] = "";
	}
?>

<!DOCTYPE html>
<html>

  <head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="../favicon.png" />
	<link rel="stylesheet" href="../style.css" />
  <title>Inside CGI - Utilisateurs</title>
	<meta name="description" content="Bienvenue sur Inside CGI, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside CGI, portail, CDS Finance" />
  </head>

	<body>

		<header>
			<div class="main_title">
				Gestion des utilisateurs
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

			<article class="article_portail">

				<?php
					if (isset($_SESSION['user_ask_id'])   AND !empty($_SESSION['user_ask_id'])
					AND isset($_SESSION['user_ask_name']) AND !empty($_SESSION['user_ask_name'])
					AND isset($_SESSION['new_password'])  AND !empty($_SESSION['new_password']))
					{
						echo '<div class="reseted">Le mot de passe a été réinitialisé pour l\'utilisateur <b>' . $_SESSION['user_ask_id'] . ' / ' . $_SESSION['user_ask_name'] . '</b> : </div>';
						echo '<p class="reseted_2"><b>' . $_SESSION['new_password'] . '</b></p>';
						$_SESSION['user_ask_id'] = "";
						$_SESSION['user_ask_name'] = "";
						$_SESSION['new_password'] = "";
					}

					include('table_users.php');

					echo '<br /><br />';

					include('table_stats.php');
				?>

			</article>
		</section>

		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>

  </body>

</html>
