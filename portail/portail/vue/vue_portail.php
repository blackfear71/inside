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
      <?php
        $title = "Portail";

        include('../../includes/header.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect  = true;
					$profil_user = true;
					$ideas       = true;
					$reports     = true;
          $notifs      = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<article class="article_portail">
				<?php
					echo '<div class="menu_portail">';
						// Préférence MovieHouse
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

            // Movie House
            echo '<a href="../moviehouse/moviehouse.php?view=' . $view_movie_house . '&year=' . date("Y") . '&action=goConsulter" title="Movie House" class="lien_portail">';
              echo '<div class="text_portail">MOVIE<br />HOUSE</div>';
              echo '<div class="fond_lien_portail">';
                echo '<img src="../../includes/icons/movie_house.png" alt="movie_house" class="img_lien_portail" />';
              echo '</div>';
            echo '</a>';

            // Expense Center
            echo '<a href="../expensecenter/expensecenter.php?year=' . date("Y") . '&action=goConsulter" title="Expense Center" class="lien_portail">';
              echo '<div class="text_portail">EXPENSE CENTER</div>';
              echo '<div class="fond_lien_portail">';
                echo '<img src="../../includes/icons/expense_center.png" alt="expense_center" class="img_lien_portail" />';
              echo '</div>';
            echo '</a>';

            // Les Petits Pédestres
            echo '<a href="../petitspedestres/parcours.php?action=liste" title="Les Petits Pédestres" class="lien_portail">';
              echo '<div class="text_portail">LES PETITS PEDESTRES</div>';
              echo '<div class="fond_lien_portail">';
                echo '<img src="../../includes/icons/petits_pedestres.png" alt="petits_pedestres" class="img_lien_portail" />';
              echo '</div>';
            echo '</a>';

            // Calendars
            echo '<a href="../calendars/calendars.php?year=' . date("Y") . '&action=goConsulter" title="Calendars" class="lien_portail">';
              echo '<div class="text_portail">CALENDARS</div>';
              echo '<div class="fond_lien_portail">';
                echo '<img src="../../includes/icons/calendars.png" alt="calendars" class="img_lien_portail" />';
              echo '</div>';
            echo '</a>';

            // Collector Room
            echo '<a href="../collector/collector.php?action=goConsulter&page=1" title="Collector Room" class="lien_portail">';
              echo '<div class="text_portail">COLLECTOR ROOM</div>';
              echo '<div class="fond_lien_portail">';
                echo '<img src="../../includes/icons/collector.png" alt="calendars" class="img_lien_portail" />';
              echo '</div>';
            echo '</a>';
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
