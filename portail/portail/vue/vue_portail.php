<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="stylePortail.css" />

		<title>Inside - Portail</title>
  </head>

	<body>
		<header>
			<div class="main_title">
				<img src="../../includes/images/portail_band.png" alt="portail_band" class="bandeau_categorie_2" />
			</div>

			<div class="mask">
				<div class="triangle"></div>
			</div>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect = true;
					$profil     = true;
					$ideas      = true;
					$reports    = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<article class="article_portail">
				<?php
					echo '<div class="menu_portail">';
						// Lien MovieHouse
						switch ($preferences->getView_movie_house())
						{
							case "S":
								$view_movie_house = "main";
								break;

							case "D":
								$view_movie_house = "user";
								break;

							case "H":
							default:
								$view_movie_house = "home";
								break;
						}

            echo '<a href="../moviehouse/moviehouse.php?view=' . $view_movie_house . '&year=' . date("Y") . '&action=goConsulter" title="MOVIE HOUSE" class="lien_portail">';
              echo '<div class="fond_lien_portail">';
                echo '<img src="../../includes/icons/movie_house.png" alt="movie_house" class="img_lien_portail" />';
              echo '</div>';
            echo '</a>';

            echo '<a href="../expensecenter/expensecenter.php?year=' . date("Y") . '&action=goConsulter" title="EXPENSE CENTER" class="lien_portail">';
              echo '<div class="fond_lien_portail">';
                echo '<img src="../../includes/icons/expense_center.png" alt="expense_center" class="img_lien_portail" />';
              echo '</div>';
            echo '</a>';

            echo '<a href="../petitspedestres/parcours.php?action=liste" title="LES PETITS PEDESTRES" class="lien_portail">';
              echo '<div class="fond_lien_portail">';
                echo '<img src="../../includes/icons/petits_pedestres.png" alt="petits_pedestres" class="img_lien_portail" />';
              echo '</div>';
            echo '</a>';

						/*echo '<a href="../moviehouse/moviehouse.php?view=' . $view_movie_house . '&year=' . date("Y") . '&action=goConsulter" class="new_menu_link_portail">';
							echo '<div class="menu_portail_box">';
								echo '<div class="mask_portail"></div>';
								echo '<div class="mask_portail_triangle"></div>';
								echo '<div class="title_portail">MOVIE<br />HOUSE</div>';
								echo '<img src="../../includes/icons/movie_house.png" alt="movie_house" title="Movie House" class="logo_menu" />';
							echo '</div>';
						echo '</a>';

						// Lien ExpenseCenter
						echo '<a href="../expensecenter/expensecenter.php?year=' . date("Y") . '&action=goConsulter" class="new_menu_link_portail">';
							echo '<div class="menu_portail_box">';
								echo '<div class="mask_portail"></div>';
								echo '<div class="mask_portail_triangle"></div>';
								echo '<div class="title_portail">EXPENSE<br />Center</div>';
								echo '<img src="../../includes/icons/expense_center.png" alt="expense_center" title="Expense Center" class="logo_menu" />';
							echo '</div>';
						echo '</a>';

						// Lien Petits Pédestres
						echo '<a href="../petitspedestres/parcours.php?action=liste" class="new_menu_link_portail">';
							echo '<div class="menu_portail_box">';
								echo '<div class="mask_portail"></div>';
								echo '<div class="mask_portail_triangle"></div>';
								echo '<div class="title_portail">LES<br />PETITS<br />Pédestres</div>';
								echo '<img src="../../includes/icons/petits_pedestres.png" alt="petits_pedestres" title="Les Petits Pédestres" class="logo_menu" />';
							echo '</div>';
						echo '</a>';*/
					echo '</div>';
				?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
