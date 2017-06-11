<?php
	// Par défaut, tous les onglets sont off
	$onglet_1 = '<a href="/insidecgi/portail/referenceguide.php" class="onglet_inactif">Reference Guide</a>';
	//$onglet_2 = '<a href="/insidecgi/portail/timesheet.php" class="onglet_inactif">Timesheet</a>';
	switch ($_SESSION['view_movie_house'])
	{
		case "D":
			$onglet_3 = '<a href="/insidecgi/portail/moviehouse.php?view=user&year=' . date("Y") . '" class="onglet_inactif" style="border-right: solid 1px white;">Movie House</a>';
			break;
			
		case "S":
		default:
			$onglet_3 = '<a href="/insidecgi/portail/moviehouse.php?view=main&year=' . date("Y") . '" class="onglet_inactif" style="border-right: solid 1px white;">Movie House</a>';
			break;
	}
	
	// Activation des onglets en vérifiant la page courante et en remplaçant les valeurs par défaut au-dessus
	$path = $_SERVER['PHP_SELF'];
	
	/*echo 'page courante : ' . $path;*/
	
	if ($path == '/insidecgi/portail/referenceguide.php'
	OR  $path == '/insidecgi/portail/referenceguide/liste_articles.php'
	OR  $path == '/insidecgi/portail/referenceguide/article.php'
	OR  $path == '/insidecgi/portail/referenceguide/saisie_article.php'
	OR  $path == '/insidecgi/portail/referenceguide/saisie_medias.php'
	OR  $path == '/insidecgi/portail/referenceguide/previsu.php')
		$onglet_1 = '<a href="/insidecgi/portail/referenceguide.php" class="onglet_actif">Reference Guide</a>';
	
	/*if ($path == '/insidecgi/portail/timesheet.php')
		$onglet_2 = '<a href="/insidecgi/portail/timesheet.php" class="onglet_actif">Timesheet</a>';*/
	
	if ($path == '/insidecgi/portail/moviehouse.php'
	OR  $path == '/insidecgi/portail/moviehouse/saisie_film_plus.php'
	OR  $path == '/insidecgi/portail/moviehouse/details_film.php')
	{
		switch ($_SESSION['view_movie_house'])
		{
			case "D":
				$onglet_3 = '<a href="/insidecgi/portail/moviehouse.php?view=user&year=' . date("Y") . '" class="onglet_actif" style="border-right: solid 1px white;">Movie House</a>';
				break;
				
			case "S":
			default:
				$onglet_3 = '<a href="/insidecgi/portail/moviehouse.php?view=main&year=' . date("Y") . '" class="onglet_actif" style="border-right: solid 1px white;">Movie House</a>';
				break;
		}
	}
	
	// Affichage des onglets	
	echo '<div class="main_title_2">';
		echo $onglet_1, $onglet_3;
		//echo $onglet_1, $onglet_2, $onglet_3;
	echo '</div>';
?>