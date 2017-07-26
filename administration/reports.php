<?php
	// Contrôles communs Administrateur
	include('../includes/controls_admin.php');

	if (!isset($_GET['view']) or ($_GET['view'] != "all" AND $_GET['view'] != "resolved" AND $_GET['view'] != "unresolved"))
		header('location: reports.php?view=all');
?>

<!DOCTYPE html>
<html>

  <head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="../favicon.png" />
	<link rel="stylesheet" href="../style.css" />
  <title>Inside - Bugs</title>
	<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside, portail, CDS Finance" />
  </head>

	<body>

		<header>
			<div class="main_title">
				Rapports de bugs
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
				<div class="switch_bug_view">
					<?php
						$switch1 = '<a href="reports.php?view=all" class="link_bug_switch_inactive">Tous</a>';
						$switch2 = '<a href="reports.php?view=unresolved" class="link_bug_switch_inactive">En cours</a>';
						$switch3 = '<a href="reports.php?view=resolved" class="link_bug_switch_inactive">Résolus</a>';

						if ($_GET['view'] == "all")
						{
							$switch1 = '<a href="reports.php?view=all" class="link_bug_switch_active">Tous</a>';
						}
						elseif ($_GET['view'] == "unresolved")
						{
							$switch2 = '<a href="reports.php?view=unresolved" class="link_bug_switch_active">En cours</a>';
						}
						elseif ($_GET['view'] == "resolved")
						{
							$switch3 = '<a href="reports.php?view=resolved" class="link_bug_switch_active">Résolus</a>';
						}

						echo $switch1, $switch2, $switch3;
					?>
				</div>

				<div class="liste_bugs">
					<?php
						include('table_bugs.php');
					?>
				</div>
			</article>
		</section>

		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>

  </body>

</html>
