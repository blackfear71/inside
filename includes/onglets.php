<?php
	// Penser à changer le style="border-right: solid 1px white;" sur le dernier onglet

	// Par défaut, tous les onglets sont off
	//$onglet_1 = '<a href="/inside/portail/referenceguide.php" class="onglet_inactif">Reference Guide</a>';
	//$onglet_2 = '<a href="/inside/portail/timesheet.php" class="onglet_inactif">Timesheet</a>';
	switch ($_SESSION['view_movie_house'])
	{
		case "S":
			$onglet_3 = '<a href="/inside/portail/moviehouse.php?view=main&year=' . date("Y") . '" title="Movie House" class="onglet_inactif"><img src="/inside/includes/icons/movie_house.png" alt="movie_house" title="Movie House" class="logo_onglet" /></a>';
			break;

		case "D":
			$onglet_3 = '<a href="/inside/portail/moviehouse.php?view=user&year=' . date("Y") . '" title="Movie House" class="onglet_inactif"><img src="/inside/includes/icons/movie_house.png" alt="movie_house" title="Movie House" class="logo_onglet" /></a>';
			break;

		case "H":
		default:
			$onglet_3 = '<a href="/inside/portail/moviehouse.php?view=home&year=' . date("Y") . '" title="Movie House" class="onglet_inactif"><img src="/inside/includes/icons/movie_house.png" alt="movie_house" title="Movie House" class="logo_onglet" /></a>';
			break;
	}
	$onglet4 = '<a href="/inside/portail/expensecenter.php?year=' . date("Y") . '" title="Expense Center" class="onglet_inactif""><img src="/inside/includes/icons/expense_center.png" alt="expense_center" title="Expense Center" class="logo_onglet" /></a>';
	$onglet5 = '<a href="/inside/portail/petitspedestres/parcours.php?action=liste" title="Les Petits Pédestres" class="onglet_inactif" style="border-right: solid 1px white;"><img src="/inside/includes/icons/petits_pedestres.png" alt="petits_pedestres" title="Les Petits Pédestres" class="logo_onglet" /></a>';

	// Activation des onglets en vérifiant la page courante et en remplaçant les valeurs par défaut au-dessus
	$path = $_SERVER['PHP_SELF'];

	/*echo 'page courante : ' . $path;*/

	// Reference Guide
	/*if ($path == '/inside/portail/referenceguide.php'
	OR  $path == '/inside/portail/referenceguide/liste_articles.php'
	OR  $path == '/inside/portail/referenceguide/article.php'
	OR  $path == '/inside/portail/referenceguide/saisie_article.php'
	OR  $path == '/inside/portail/referenceguide/saisie_medias.php'
	OR  $path == '/inside/portail/referenceguide/previsu.php')
		$onglet_1 = '<a href="/inside/portail/referenceguide.php" class="onglet_actif">Reference Guide</a>';*/

	// Timesheet
	/*if ($path == '/inside/portail/timesheet.php')
		$onglet_2 = '<a href="/inside/portail/timesheet.php" class="onglet_actif">Timesheet</a>';*/

	// Movie House
	if ($path == '/inside/portail/moviehouse.php'
	OR  $path == '/inside/portail/moviehouse/saisie_avancee.php'
	OR  $path == '/inside/portail/moviehouse/details_film.php')
	{
		switch ($_SESSION['view_movie_house'])
		{
			case "S":
				$onglet_3 = '<a href="/inside/portail/moviehouse.php?view=main&year=' . date("Y") . '" title="Movie House" class="onglet_actif"><img src="/inside/includes/icons/movie_house.png" alt="movie_house" title="Movie House" class="logo_onglet" /></a>';
				break;

			case "D":
				$onglet_3 = '<a href="/inside/portail/moviehouse.php?view=user&year=' . date("Y") . '" title="Movie House" class="onglet_actif"><img src="/inside/includes/icons/movie_house.png" alt="movie_house" title="Movie House" class="logo_onglet" /></a>';
				break;

			case "H":
			default:
				$onglet_3 = '<a href="/inside/portail/moviehouse.php?view=home&year=' . date("Y") . '" title="Movie House" class="onglet_actif"><img src="/inside/includes/icons/movie_house.png" alt="movie_house" title="Movie House" class="logo_onglet" /></a>';
				break;
		}
	}

	// Expense center
	if ($path == '/inside/portail/expensecenter.php')
	{
		$onglet4 = '<a href="/inside/portail/expensecenter.php?year=' . date("Y") . '" title="Expense Center" class="onglet_actif""><img src="/inside/includes/icons/expense_center.png" alt="expense_center" title="Expense Center" class="logo_onglet" /></a>';
	}

	// Petits pédestres
	if ($path == '/inside/portail/petitspedestres/parcours.php')
	{
		$onglet5 = '<a href="/inside/portail/petitspedestres/parcours.php?action=liste" class="onglet_actif" title="Les Petits Pédestres" style="border-right: solid 1px white;"><img src="/inside/includes/icons/petits_pedestres.png" alt="petits_pedestres" title="Les Petits Pédestres" class="logo_onglet" /></a>';
	}

	// Affichage des onglets
	echo '<div class="main_title_2">';
		echo $onglet_3, $onglet4, $onglet5;
		//echo $onglet_1, $onglet_2, $onglet_3;
	echo '</div>';
?>
