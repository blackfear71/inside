<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="styleAdmin.css" />

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script type="text/javascript" src="/inside/script.js"></script>

		<title>Inside - Utilisateurs</title>
  </head>

	<body>
		<header>
      <?php
        $title = "Gestion utilisateurs";

        include('../includes/header.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect = true;
					$back_admin = true;

					include('../includes/aside.php');
				?>
			</aside>

			<article>
				<?php
					if (isset($_SESSION['user_ask_id'])   AND !empty($_SESSION['user_ask_id'])
					AND isset($_SESSION['user_ask_name']) AND !empty($_SESSION['user_ask_name'])
					AND isset($_SESSION['new_password'])  AND !empty($_SESSION['new_password']))
					{
						echo '<div class="reseted">Le mot de passe a été réinitialisé pour l\'utilisateur <b>' . $_SESSION['user_ask_id'] . ' / ' . $_SESSION['user_ask_name'] . '</b> : </div>';
						echo '<p class="reseted_2"><b>' . $_SESSION['new_password'] . '</b></p>';

						$_SESSION['user_ask_id']   = "";
						$_SESSION['user_ask_name'] = "";
						$_SESSION['new_password']  = "";
					}

					// Tableau des utilisateurs
					include('table_users.php');

					echo '<br /><br />';

					// Tableau des statistiques des catégories
					include('table_stats_categories.php');

					echo '<br /><br />';

					// Tableau des statistiques demandes
					include('table_stats_requests.php');
				?>

			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>
  </body>
</html>
