<!DOCTYPE html>
<html>
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "Portail";
      $style_head  = "stylePortail.css";
      $script_head = "";
      $chat_head   = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<header>
      <?php
        $title = "Portail";

        include('../../includes/common/header.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect  = true;
					$ideas       = true;
					$reports     = true;

					include('../../includes/common/aside.php');
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
                                          'image'     => '../../includes/icons/common/movie_house.png',
                                          'alt'       => 'movie_house'),
                                    array('categorie' => 'EXPENSE<br />CENTER',
                                          'lien'      => '../expensecenter/expensecenter.php?year=' . date("Y") . '&action=goConsulter',
                                          'title'     => 'Expense Center',
                                          'image'     => '../../includes/icons/common/expense_center.png',
                                          'alt'       => 'expense_center'),
                                    array('categorie' => 'LES PETITS<br />PEDESTRES',
                                          'lien'      => '../petitspedestres/parcours.php?action=liste',
                                          'title'     => 'Les Petits Pédestres',
                                          'image'     => '../../includes/icons/common/petits_pedestres.png',
                                          'alt'       => 'petits_pedestres'),
                                    array('categorie' => 'CALENDARS',
                                          'lien'      => '../calendars/calendars.php?year=' . date("Y") . '&action=goConsulter',
                                          'title'     => 'Calendars',
                                          'image'     => '../../includes/icons/common/calendars.png',
                                          'alt'       => 'calendars'),
                                    array('categorie' => 'COLLECTOR<br />ROOM',
                                          'lien'      => '../collector/collector.php?action=goConsulter&page=1&sort=dateDesc&filter=none',
                                          'title'     => 'Collector Room',
                                          'image'     => '../../includes/icons/common/collector.png',
                                          'alt'       => 'collector'),
                                    array('categorie' => 'MISSIONS :<br />INSIDER',
                                          'lien'      => '../missions/missions.php?action=goConsulter',
                                          'title'     => 'Missions : Insider',
                                          'image'     => '../../includes/icons/common/missions.png',
                                          'alt'       => 'missions')/*,
                                    array('categorie' => 'EVENT<br />MANAGER',
                                          'lien'      => '../eventmanager/eventmanager.php?action=goConsulter',
                                          'title'     => 'Event Manager',
                                          'image'     => '../../includes/icons/common/event_manager.png',
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
			<?php include('../../includes/common/footer.php'); ?>
		</footer>
  </body>
</html>
