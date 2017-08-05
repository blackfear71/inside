<?php
	// Contrôles communs Utilisateurs
	include('../includes/controls_users.php');

	//include('../includes/init_session.php');
?>

<!DOCTYPE html>
<html>

  <head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="../favicon.png" />
	<link rel="stylesheet" href="../style.css" />
	<title>Inside - Portail</title>
	<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
	<meta name="keywords" content="Inside, portail, CDS Finance" />
  </head>

	<body>

		<header>
			<div class="main_title">
				<img src="../includes/images/portail_band.png" alt="portail_band" class="bandeau_categorie_2" />
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

				<?php
					echo '<div class="new_menu_portail">';

						// Lien ReferenceGuide
						/*echo '<a href="referenceguide.php" class="new_menu_link_portail">';
							echo '<div class="menu_portail_box">';
								echo '<div class="mask_portail"></div>';
								echo '<div class="mask_portail_triangle"></div>';
								//<div class="title_portail">REF<br />ERE<br />NCE<br /><br />Guide</div>
								echo '<div class="title_portail">REFE-<br />RENCE<br /><br />Guide</div>';
							echo '</div>';
						echo '</a>';*/

						// Lien TimeSheet
						/*<a href="timesheet.php" class="new_menu_link_portail">
							<div class="menu_portail_box">
								<div class="mask_portail"></div>
								<div class="mask_portail_triangle"></div>
								<div class="title_portail">Good<br />Old<br /><br />TIME<br />SHEET</div>
							</div>
						</a>*/

						// Lien MovieHouse
						switch ($_SESSION['view_movie_house'])
						{
							case "S":
								echo '<a href="moviehouse.php?view=main&year=' . date("Y") . '" class="new_menu_link_portail">';
									echo '<div class="menu_portail_box">';
										echo '<div class="mask_portail"></div>';
										echo '<div class="mask_portail_triangle"></div>';
										echo '<div class="title_portail">MOVIE<br />HOUSE</div>';
										echo '<img src="../includes/icons/movie_house.png" alt="movie_house" title="Movie House" class="logo_menu" />';
									echo '</div>';
								echo '</a>';
								break;

							case "D":
								echo '<a href="moviehouse.php?view=user&year=' . date("Y") . '" class="new_menu_link_portail">';
									echo '<div class="menu_portail_box">';
										echo '<div class="mask_portail"></div>';
										echo '<div class="mask_portail_triangle"></div>';
										echo '<div class="title_portail">MOVIE<br />HOUSE</div>';
										echo '<img src="../includes/icons/movie_house.png" alt="movie_house" title="Movie House" class="logo_menu" />';
									echo '</div>';
								echo '</a>';
								break;

							case "H":
							default:
								echo '<a href="moviehouse.php?view=home&year=' . date("Y") . '" class="new_menu_link_portail">';
									echo '<div class="menu_portail_box">';
										echo '<div class="mask_portail"></div>';
										echo '<div class="mask_portail_triangle"></div>';
										echo '<div class="title_portail">MOVIE<br />HOUSE</div>';
										echo '<img src="../includes/icons/movie_house.png" alt="movie_house" title="Movie House" class="logo_menu" />';
									echo '</div>';
								echo '</a>';
								break;
						}

						// Lien ExpenseCenter
						echo '<a href="expensecenter.php?year=' . date("Y") . '" class="new_menu_link_portail">';
							echo '<div class="menu_portail_box">';
								echo '<div class="mask_portail"></div>';
								echo '<div class="mask_portail_triangle"></div>';
								echo '<div class="title_portail">EXPENSE<br />Center</div>';
								echo '<img src="../includes/icons/expense_center.png" alt="expense_center" title="Expense Center" class="logo_menu" />';
							echo '</div>';
						echo '</a>';

						// Lien Petits Pédestres
						echo '<a href="petitspedestres.php" class="new_menu_link_portail">';
							echo '<div class="menu_portail_box">';
								echo '<div class="mask_portail"></div>';
								echo '<div class="mask_portail_triangle"></div>';
								echo '<div class="title_portail">LES<br />PETITS<br />Pédestres</div>';
								echo '<img src="../includes/icons/petits_pedestres.png" alt="petits_pedestres" title="Les Petits Pédestres" class="logo_menu" />';
							echo '</div>';
						echo '</a>';

					echo '</div>';
				?>

			</article>
		</section>

		<footer>
			<?php include('../includes/footer.php'); ?>
		</footer>

  </body>

</html>
