<?php
	// Penser à changer le style="border-right: solid 1px white;" sur le dernier onglet

	// Par défaut, tous les onglets sont off
	$onglet_1 = '<a href="/insidecgi/portail/referenceguide.php" class="onglet_inactif">Reference Guide</a>';
	//$onglet_2 = '<a href="/insidecgi/portail/timesheet.php" class="onglet_inactif">Timesheet</a>';
	switch ($_SESSION['view_movie_house'])
	{
		case "D":
			$onglet_3 = '<a href="/insidecgi/portail/moviehouse.php?view=user&year=' . date("Y") . '" class="onglet_inactif">Movie House</a>';
			break;

		case "S":
		default:
			$onglet_3 = '<a href="/insidecgi/portail/moviehouse.php?view=main&year=' . date("Y") . '" class="onglet_inactif">Movie House</a>';
			break;
	}
	$onglet4 = '<a href="/insidecgi/portail/expensecenter.php?year=' . date("Y") . '" class="onglet_inactif"">Expense Center</a>';
	$onglet5 = '<a href="/insidecgi/portail/petitspedestres.php" class="onglet_inactif" style="border-right: solid 1px white;">Les Petits Pédestres</a>';

	// Activation des onglets en vérifiant la page courante et en remplaçant les valeurs par défaut au-dessus
	$path = $_SERVER['PHP_SELF'];

	/*echo 'page courante : ' . $path;*/

	// Reference Guide
	if ($path == '/insidecgi/portail/referenceguide.php'
	OR  $path == '/insidecgi/portail/referenceguide/liste_articles.php'
	OR  $path == '/insidecgi/portail/referenceguide/article.php'
	OR  $path == '/insidecgi/portail/referenceguide/saisie_article.php'
	OR  $path == '/insidecgi/portail/referenceguide/saisie_medias.php'
	OR  $path == '/insidecgi/portail/referenceguide/previsu.php')
		$onglet_1 = '<a href="/insidecgi/portail/referenceguide.php" class="onglet_actif">Reference Guide</a>';

	// Timesheet
	/*if ($path == '/insidecgi/portail/timesheet.php')
		$onglet_2 = '<a href="/insidecgi/portail/timesheet.php" class="onglet_actif">Timesheet</a>';*/

	// Movie House
	if ($path == '/insidecgi/portail/moviehouse.php'
	OR  $path == '/insidecgi/portail/moviehouse/saisie_avancee.php'
	OR  $path == '/insidecgi/portail/moviehouse/details_film.php')
	{
		switch ($_SESSION['view_movie_house'])
		{
			case "D":
				$onglet_3 = '<a href="/insidecgi/portail/moviehouse.php?view=user&year=' . date("Y") . '" class="onglet_actif">Movie House</a>';
				break;

			case "S":
			default:
				$onglet_3 = '<a href="/insidecgi/portail/moviehouse.php?view=main&year=' . date("Y") . '" class="onglet_actif">Movie House</a>';
				break;
		}
	}

	// Expense center
	if ($path == '/insidecgi/portail/expensecenter.php')
	{
		$onglet4 = '<a href="/insidecgi/portail/expensecenter.php?year=' . date("Y") . '" class="onglet_actif"">Expense Center</a>';
	}

	// Petits pédestres
	if ($path == '/insidecgi/portail/petitspedestres.php')
	{
		$onglet5 = '<a href="/insidecgi/portail/petitspedestres.php" class="onglet_actif" style="border-right: solid 1px white;">Les Petits Pédestres</a>';
	}

	// Affichage des onglets
	echo '<div class="main_title_2">';
		echo $onglet_1, $onglet_3, $onglet4, $onglet5;
		//echo $onglet_1, $onglet_2, $onglet_3;
	echo '</div>';
?>
