<?php
	// Penser à changer le style="border-right: solid 1px white;" sur le dernier onglet

	// Récupération des préférences
	switch ($_SESSION['view_movie_house'])
	{
		case "S":
			$view_movie_house   = "main";
			break;

		case "D":
			$view_movie_house   = "user";
			break;

		case "H":
		default:
			$view_movie_house   = "home";
			break;
	}

	// Par défaut, tous les onglets sont off
	$onglet_1 = '<a href="/inside/portail/moviehouse/moviehouse.php?view=' . $view_movie_house . '&year=' . date("Y") . '&action=goConsulter" title="Movie House" class="onglet_inactif"><img src="/inside/includes/icons/movie_house.png" alt="movie_house" title="Movie House" class="logo_onglet" /></a>';
	$onglet_2 = '<a href="/inside/portail/expensecenter/expensecenter.php?year=' . date("Y") . '&action=goConsulter" title="Expense Center" class="onglet_inactif""><img src="/inside/includes/icons/expense_center.png" alt="expense_center" title="Expense Center" class="logo_onglet" /></a>';
	$onglet_3 = '<a href="/inside/portail/petitspedestres/parcours.php?action=liste" title="Les Petits Pédestres" class="onglet_inactif"><img src="/inside/includes/icons/petits_pedestres.png" alt="petits_pedestres" title="Les Petits Pédestres" class="logo_onglet" /></a>';
	$onglet_4 = '<a href="/inside/portail/calendars/calendars.php?year=' . date("Y") . '&action=goConsulter" title="Calendars" class="onglet_inactif"><img src="/inside/includes/icons/calendars.png" alt="calendars" title="Calendars" class="logo_onglet" /></a>';
	$onglet_5 = '<a href="/inside/portail/collector/collector.php?action=goConsulter&page=1" title="Collector Room" class="onglet_inactif" style="border-right: solid 1px white;"><img src="/inside/includes/icons/collector.png" alt="collector" title="Collector" class="logo_onglet" /></a>';

	// Activation des onglets en vérifiant la page courante et en remplaçant les valeurs par défaut au-dessus
	$path = $_SERVER['PHP_SELF'];

	/*echo 'page courante : ' . $path;*/

	// Movie House
	if ($path == '/inside/portail/moviehouse/moviehouse.php'
	OR  $path == '/inside/portail/moviehouse/saisie.php'
	OR  $path == '/inside/portail/moviehouse/details.php'
	OR  $path == '/inside/portail/moviehouse/mailing.php')
	{
		$onglet_1 = '<a href="/inside/portail/moviehouse/moviehouse.php?view=' . $view_movie_house . '&year=' . date("Y") . '&action=goConsulter" title="Movie House" class="onglet_actif"><img src="/inside/includes/icons/movie_house.png" alt="movie_house" title="Movie House" class="logo_onglet" /></a>';
	}

	// Expense center
	if ($path == '/inside/portail/expensecenter/expensecenter.php')
	{
		$onglet_2 = '<a href="/inside/portail/expensecenter/expensecenter.php?year=' . date("Y") . '&action=goConsulter" title="Expense Center" class="onglet_actif""><img src="/inside/includes/icons/expense_center.png" alt="expense_center" title="Expense Center" class="logo_onglet" /></a>';
	}

	// Petits pédestres
	if ($path == '/inside/portail/petitspedestres/parcours.php')
	{
		$onglet_3 = '<a href="/inside/portail/petitspedestres/parcours.php?action=liste" class="onglet_actif" title="Les Petits Pédestres"><img src="/inside/includes/icons/petits_pedestres.png" alt="petits_pedestres" title="Les Petits Pédestres" class="logo_onglet" /></a>';
	}

	// Calendars
	if ($path == '/inside/portail/calendars/calendars.php')
	{
		$onglet_4 = '<a href="/inside/portail/calendars/calendars.php?year=' . date("Y") . '&action=goConsulter" title="Calendars" class="onglet_actif"><img src="/inside/includes/icons/calendars.png" alt="calendars" title="Calendars" class="logo_onglet" /></a>';
	}

	// Collector Room
	if ($path == '/inside/portail/collector/collector.php')
	{
		$onglet_5 = '<a href="/inside/portail/collector/collector.php?action=goConsulter&page=1" title="Collector Room" class="onglet_actif" style="border-right: solid 1px white;"><img src="/inside/includes/icons/collector.png" alt="collector" title="Collector" class="logo_onglet" /></a>';
	}

	// Affichage des onglets
	echo '<div class="main_title_2">';
		echo $onglet_1, $onglet_2, $onglet_3, $onglet_4, $onglet_5;
	echo '</div>';

	// Affichage titre section
	echo '<div class="rectangle_titre_section">' . $title . '</div>';
	echo '<div class="triangle_titre_section"></div>';
?>
