<?php
	// Contrôles communs Utilisateurs
	include('../includes/controls_users.php');

	include('../includes/init_session.php');
?>

<!DOCTYPE html>
<html>

  <head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="../favicon.png" />
	<link rel="stylesheet" href="../style.css" />
  <title>Inside CGI - Portail</title>
	<meta name="description" content="Bienvenue sur Inside CGI, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside CGI, portail, CDS Finance" />
  </head>

	<body>

		<header>
			<div class="main_title">
				Portail
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
					$profil = true;
					$ideas = true;
					$bug = true;

					include('../includes/aside.php');
				?>
			</aside>

			<article class="article_portail">
				<div class="new_menu_portail">
					<a href="referenceguide.php" class="new_menu_link_portail">
						<div class="menu_portail_box">
							<div class="mask_portail"></div>
							<div class="mask_portail_triangle"></div>
							<!--<div class="title_portail">REF<br />ERE<br />NCE<br /><br />Guide</div>-->
							<div class="title_portail">REFE-<br />RENCE<br /><br />Guide</div>
						</div>
					</a>

					<!--<a href="timesheet.php" class="new_menu_link_portail">
						<div class="menu_portail_box">
							<div class="mask_portail"></div>
							<div class="mask_portail_triangle"></div>
							<div class="title_portail">Good<br />Old<br /><br />TIME<br />SHEET</div>
						</div>
					</a>-->

					<?php
						switch ($_SESSION['view_movie_house'])
						{
							case "D":
								echo '<a href="moviehouse.php?view=user&year=' . date("Y") . '" class="new_menu_link_portail">';
									echo '<div class="menu_portail_box">';
										echo '<div class="mask_portail"></div>';
										echo '<div class="mask_portail_triangle"></div>';
										echo '<div class="title_portail">MOVIE<br />HOUSE</div>';
									echo '</div>';
								echo '</a>';
								break;

							case "S":
							default:
								echo '<a href="moviehouse.php?view=main&year=' . date("Y") . '" class="new_menu_link_portail">';
									echo '<div class="menu_portail_box">';
										echo '<div class="mask_portail"></div>';
										echo '<div class="mask_portail_triangle"></div>';
										echo '<div class="title_portail">MOVIE<br />HOUSE</div>';
									echo '</div>';
								echo '</a>';
								break;
						}
					?>

					<a href="expensecenter.php" class="new_menu_link_portail">
						<div class="menu_portail_box">
							<div class="mask_portail"></div>
							<div class="mask_portail_triangle"></div>
							<div class="title_portail">EXP-<br />ENSE<br /><br />Center</div>
						</div>
					</a>

				</div>

			</article>
		</section>

		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>

  </body>

</html>
