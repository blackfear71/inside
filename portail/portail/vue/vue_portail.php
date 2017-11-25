<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />
    <link rel="stylesheet" href="stylePortail.css" />

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
    <script type="text/javascript" src="/inside/script.js"></script>

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
			<aside id="left_menu">
				<?php
					$disconnect  = true;
					$profil_user = true;
					$ideas       = true;
					$reports     = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<article>
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
                echo '<img src="../../includes/icons/collector.png" alt="collector" class="img_lien_portail" />';
              echo '</div>';
            echo '</a>';

            // Missions
            echo '<a href="../missions/missions.php?action=goConsulter" title="Missions : Insider" class="lien_portail">';
              echo '<div class="text_portail">MISSIONS : INSIDER</div>';
              echo '<div class="fond_lien_portail">';
                echo '<img src="../../includes/icons/missions.png" alt="missions" class="img_lien_portail" />';
              echo '</div>';
            echo '</a>';
					echo '</div>';

          // Résumé mission
          if (isset($mission) AND !empty($mission))
          {
            echo '<div class="zone_resume_mission">';
              if (isset($_SESSION['tableau_missions']) AND !empty($_SESSION['tableau_missions']) AND $mission->getDate_deb() != $mission->getDate_fin())
              {
                $nbRestants = count($_SESSION['tableau_missions']);
                echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter" class="link_mission">';
                  if (date('Ymd') == $mission->getDate_deb())
                    echo 'La mission <strong>' . $mission->getMission() . '</strong> commence aujourd\'hui, trouve vite les objectifs avant les autres !<br />';

                  if ($nbRestants == 1)
                    echo 'Il reste encore ' . $nbRestants . ' objectif à trouver aujourd\'hui pour terminer la mission <strong>' . $mission->getMission() . '</strong>.';
                  else
                    echo 'Il reste encore ' . $nbRestants . ' objectifs à trouver aujourd\'hui pour terminer la mission <strong>' . $mission->getMission() . '</strong>.';

                  if (date('Ymd') == $mission->getDate_fin())
                    echo '<br />La mission <strong>' . $mission->getMission() . '</strong> se termine aujourd\'hui, trouve vite les derniers objectifs !';
                echo '</a>';
              }
              if (isset($_SESSION['tableau_missions']) AND !empty($_SESSION['tableau_missions']) AND $mission->getDate_deb() == $mission->getDate_fin())
              {
                $nbRestants = count($_SESSION['tableau_missions']);
                echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter" class="link_mission">';
                  if ($nbRestants == 1)
                    echo 'La mission <strong>' . $mission->getMission() . '</strong> ne dure qu\'une journée et il reste encore ' . $nbRestants . ' objectif à trouver !';
                  else
                    echo 'La mission <strong>' . $mission->getMission() . '</strong> ne dure qu\'une journée et il reste encore ' . $nbRestants . ' objectifs à trouver !';
                echo '</a>';
              }
              elseif (empty($_SESSION['tableau_missions']) AND date('Ymd') < $mission->getDate_fin() AND date('His') >= $mission->getHeure())
              {
                echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter" class="link_mission">';
                  echo 'La mission <strong>' . $mission->getMission() . '</strong> est terminée pour aujourd\'hui ! Reviens demain pour continuer...';
                echo '</a>';
              }
              elseif (empty($_SESSION['tableau_missions']) AND date('Ymd') < $mission->getDate_fin() AND date('His') < $mission->getHeure())
              {
                echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter" class="link_mission">';
                  echo 'La mission <strong>' . $mission->getMission() . '</strong> commence à ' . formatTimeForDisplayLight($mission->getHeure()) . ', reviens un peu plus tard pour continuer...';
                echo '</a>';
              }
              elseif (empty($_SESSION['tableau_missions']) AND date('Ymd') == $mission->getDate_fin() AND date('His') >= $mission->getHeure())
              {
                echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter" class="link_mission">';
                  echo 'La mission <strong>' . $mission->getMission() . '</strong> se termine aujourd\'hui. Tu as trouvé tous les objectifs, reviens demain pour voir les scores !';
                echo '</a>';
              }
              elseif (empty($_SESSION['tableau_missions']) AND date('Ymd') == $mission->getDate_fin() AND date('His') < $mission->getHeure())
              {
                echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter" class="link_mission">';
                  echo 'La mission <strong>' . $mission->getMission() . '</strong> se termine aujourd\'hui. Trouve les derniers objectifs à partir de ' . formatTimeForDisplayLight($mission->getHeure()) . '.';
                echo '</a>';
              }
              elseif (empty($_SESSION['tableau_missions']) AND (date('Ymd') >= $mission->getDate_fin() + 1) AND (date('Ymd') <= $mission->getDate_fin() + 7))
              {
                echo '<a href="../missions/details.php?id_mission=' . $mission->getId() . '&action=goConsulter" class="link_mission">';
                  echo 'La mission <strong>' . $mission->getMission() . '</strong> est terminée. Va voir les résultats en cliquant sur ce message.';
                echo '</a>';
              }
            echo '</div>';
          }
				?>
			</article>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
