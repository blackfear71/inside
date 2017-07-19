<?php
	// Contrôles communs Utilisateurs
	include('../includes/controls_users.php');
?>

<!DOCTYPE html>
<html>

  <head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="../favicon.png" />
	<link rel="stylesheet" href="../style.css" />
  <title>Inside CGI - EC</title>
	<meta name="description" content="Bienvenue sur Inside CGI, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside CGI, portail, CDS Finance" />
  </head>

	<body>

		<header>
			<?php include('../includes/onglets.php') ; ?>
		</header>

		<section>
			<aside>
				<!-- Boutons d'action -->
				<?php
					$disconnect = true;
					$profil = true;
					$back = true;
					$ideas = true;
					$bug = true;

					include('../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../includes/alerts.php');
			?>

			<article class="article_portail">

          <?php
            // Saisie nouvelle ligne
            include('expensecenter/table_saisie_depense.php');

            // Affichage total
            include('expensecenter/table_total_depenses.php');

            // Lignes saisie
            include('expensecenter/table_resume_depenses.php');
          ?>

			</article>
		</section>

		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>

  </body>

</html>
