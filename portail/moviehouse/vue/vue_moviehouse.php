<!DOCTYPE html>
<html>
  <head>
    <!-- Head commun & spécifique-->
    <?php
      $title_head  = "MH";
      $style_head  = "styleMH.css";
      $script_head = "scriptMH.js";
      $chat_head   = true;

      include('../../includes/common/head.php');
    ?>
  </head>

	<body>
		<!-- Onglets -->
		<header>
      <?php
        $title = "Movie House";

        include('../../includes/common/header.php');
			  include('../../includes/common/onglets.php');
      ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside id="left_menu">
				<?php
					$disconnect  = true;
					$add_film    = true;
					$back        = true;
					$ideas       = true;
					$reports     = true;

					include('../../includes/common/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/common/alerts.php');
			?>

			<article>
				<!-- Switch entre accueil, vue générale et vue personnelle -->
				<div class="switch_view" style="margin-top: 30px;">
					<?php
						$listeSwitch = array('home' => array('lib' => 'Accueil',  'date' => date("Y")),
																 'main' => array('lib' => 'Synthèse', 'date' => $_GET['year']),
																 'user' => array('lib' => 'Détails',  'date' => $_GET['year'])
																);

            foreach ($listeSwitch as $view => $lib_view)
						{
              if ($_GET['view'] == $view)
                $actif = 'active';
              else
                $actif = 'inactive';

              echo '<a href="moviehouse.php?view=' . $view . '&year=' . $lib_view['date'] . '&action=goConsulter" class="zone_switch">';
                echo '<div class="titre_switch_' . $actif . '">' . $lib_view['lib'] . '</div>';
                echo '<div class="border_switch_' . $actif . '"></div>';
              echo '</a>';
            }
					?>
				</div>

				<!-- Affichage de la page en fonction de la vue -->
				<?php
          switch ($_GET['view'])
          {
            case "main":
              include("vue/onglets_moviehouse.php");
              include("vue/table_films_main.php");
              break;

            case "user":
              include("vue/onglets_moviehouse.php");
              include("vue/table_films_user.php");
              break;

            case "home":
            default:
              include("vue/table_films_home.php");
              break;
          }
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
