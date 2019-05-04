<!DOCTYPE html>
<html lang="fr">
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "Utilisateurs";
      $style_head  = "styleAdmin.css";
      $script_head = "";

      include('../includes/common/head.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "Gestion utilisateurs";

        include('../includes/common/header.php');
      ?>
		</header>

		<section>
			<article>
				<?php
					if (isset($_SESSION['save']['user_ask_id'])   AND !empty($_SESSION['save']['user_ask_id'])
					AND isset($_SESSION['save']['user_ask_name']) AND !empty($_SESSION['save']['user_ask_name'])
					AND isset($_SESSION['save']['new_password'])  AND !empty($_SESSION['save']['new_password']))
					{
						echo '<div class="reseted">Le mot de passe a été réinitialisé pour l\'utilisateur <b>' . $_SESSION['save']['user_ask_id'] . ' / ' . $_SESSION['save']['user_ask_name'] . '</b> : </div>';
						echo '<p class="reseted_2"><b>' . $_SESSION['save']['new_password'] . '</b></p>';

						$_SESSION['save']['user_ask_id']   = "";
						$_SESSION['save']['user_ask_name'] = "";
						$_SESSION['save']['new_password']  = "";
					}

					// Tableau des utilisateurs
					include('table_users.php');

					// Tableau des statistiques des catégories
					include('table_stats_categories.php');

					// Tableau des statistiques demandes
					include('table_stats_requests.php');
				?>

			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
