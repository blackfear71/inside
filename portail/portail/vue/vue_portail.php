<!DOCTYPE html>
<html>
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "Portail";
      $style_head  = "stylePortail.css";
      $script_head = "";

      include($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/common.php');
    ?>
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
					$ideas       = true;
					$reports     = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<article>
				<?php
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

          // Tableau des catégories (Movie House, Expense Center, Les Petits Pédestres, Calendars, Collector Room, Missions : Insider, Event Manager)
          $liste_categories = array(array('categorie' => 'MOVIE<br />HOUSE',
                                          'lien'      => '../moviehouse/moviehouse.php?view=' . $view_movie_house . '&year=' . date("Y") . '&action=goConsulter',
                                          'title'     => 'Movie House',
                                          'image'     => '../../includes/icons/movie_house.png',
                                          'alt'       => 'movie_house'),
                                    array('categorie' => 'EXPENSE<br />CENTER',
                                          'lien'      => '../expensecenter/expensecenter.php?year=' . date("Y") . '&action=goConsulter',
                                          'title'     => 'Expense Center',
                                          'image'     => '../../includes/icons/expense_center.png',
                                          'alt'       => 'expense_center'),
                                    array('categorie' => 'LES PETITS<br />PEDESTRES',
                                          'lien'      => '../petitspedestres/parcours.php?action=liste',
                                          'title'     => 'Les Petits Pédestres',
                                          'image'     => '../../includes/icons/petits_pedestres.png',
                                          'alt'       => 'petits_pedestres'),
                                    array('categorie' => 'CALENDARS',
                                          'lien'      => '../calendars/calendars.php?year=' . date("Y") . '&action=goConsulter',
                                          'title'     => 'Calendars',
                                          'image'     => '../../includes/icons/calendars.png',
                                          'alt'       => 'calendars'),
                                    array('categorie' => 'COLLECTOR<br />ROOM',
                                          'lien'      => '../collector/collector.php?action=goConsulter&page=1',
                                          'title'     => 'Collector Room',
                                          'image'     => '../../includes/icons/collector.png',
                                          'alt'       => 'collector'),
                                    array('categorie' => 'MISSIONS :<br />INSIDER',
                                          'lien'      => '../missions/missions.php?action=goConsulter',
                                          'title'     => 'Missions : Insider',
                                          'image'     => '../../includes/icons/missions.png',
                                          'alt'       => 'missions')/*,
                                    array('categorie' => 'EVENT<br />MANAGER',
                                          'lien'      => '../eventmanager/eventmanager.php?action=goConsulter',
                                          'title'     => 'Event Manager',
                                          'image'     => '../../includes/icons/event_manager.png',
                                          'alt'       => 'event_manager')*/
                                   );

          echo '<div class="menu_portail">';
            // Liens des catégories
            foreach ($liste_categories as $categorie)
            {
              echo '<a href="' . $categorie['lien'] . '" title="' . $categorie['title'] . '" class="lien_portail">';
                echo '<div class="text_portail">' . $categorie['categorie'] . '</div>';
                echo '<div class="fond_lien_portail">';
                  echo '<img src="' . $categorie['image'] . '" alt="' . $categorie['alt'] . '" class="img_lien_portail" />';
                echo '</div>';

              echo '</a>';
            }
					echo '</div>';

          // Résumés missions
          include('messages_missions.php');
        ?>
			</article>

      <?php include('../../includes/chat/chat.php'); ?>
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>
  </body>
</html>
