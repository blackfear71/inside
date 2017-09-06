<!DOCTYPE html>
<html>
  <head>
		<meta charset="utf-8" />
		<meta name="description" content="Bienvenue sur Inside, le portail interne au seul vrai CDS Finance" />
		<meta name="keywords" content="Inside, portail, CDS Finance" />

		<link rel="icon" type="image/png" href="/inside/favicon.png" />
		<link rel="stylesheet" href="/inside/style.css" />
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

		<title>Inside - MH</title>
  </head>

	<body>
		<!-- Onglets -->
		<header>
			<?php include('../../includes/onglets.php') ; ?>
		</header>

		<section>
			<!-- Paramétrage des boutons de navigation -->
			<aside>
				<?php
					$disconnect = true;
					$profil     = true;
					$add_film   = true;
					$back       = true;
					$ideas      = true;
					$reports    = true;

					include('../../includes/aside.php');
				?>
			</aside>

			<!-- Messages d'alerte -->
			<?php
				include('../../includes/alerts.php');
			?>

			<article class="article_portail">
				<!-- Bandeau catégorie -->
				<img src="../../includes/images/movie_house_band.png" alt="movie_house_band" class="bandeau_categorie" />

				<!-- Switch entre accueil, vue générale et vue personnelle-->
				<div class="switch_view_2">
					<?php
						$listeSwitch = array('home' => array('lib' => 'Accueil',  'date' => date("Y")),
																 'main' => array('lib' => 'Synthèse', 'date' => $_GET['year']),
																 'user' => array('lib' => 'Détails',  'date' => $_GET['year'])
																);

						foreach ($listeSwitch as $view => $lib_view)
						{
							if ($_GET['view'] == $view)
								$switch = '<a href="moviehouse.php?view=' . $view . '&year=' . $lib_view['date'] . '&action=goConsulter" class="link_switch_active">' . $lib_view['lib'] . '</a>';
							else
								$switch = '<a href="moviehouse.php?view=' . $view . '&year=' . $lib_view['date'] . '&action=goConsulter" class="link_switch_inactive">' . $lib_view['lib'] . '</a>';

							echo $switch;
						}
					?>
				</div>

				<!-- Affichage de la page en fonction de la vue -->
				<?php
          switch($_GET['view'])
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
		</section>

		<!-- Pied de page -->
		<footer>
			<?php include('../../includes/footer.php'); ?>
		</footer>

		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
		<script>
			// Génère un calendrier
			$(function()
			{
				$( "#datepicker" ).datepicker(
				{
					firstDay: 1,
					altField: "#datepicker",
					closeText: 'Fermer',
					prevText: 'Précédent',
					nextText: 'Suivant',
					currentText: 'Aujourd\'hui',
					monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
					monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
					dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
					dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
					dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
					weekHeader: 'Sem.',
					dateFormat: 'dd/mm/yy'
				});
				$( "#datepicker2" ).datepicker(
				{
					firstDay: 1,
					altField: "#datepicker2",
					closeText: 'Fermer',
					prevText: 'Précédent',
					nextText: 'Suivant',
					currentText: 'Aujourd\'hui',
					monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
					monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
					dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
					dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
					dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
					weekHeader: 'Sem.',
					dateFormat: 'dd/mm/yy'
				});
			});
		</script>
	</body>
</html>
