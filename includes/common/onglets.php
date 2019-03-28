<?php
	// Récupération des préférences
	switch ($_SESSION['user']['view_movie_house'])
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

	echo '<div class="separation_nav"></div>';

  echo '<nav class="menu_nav">';
		// Par défaut, tous les onglets sont off
		$onglet_1 = '<a href="/inside/portail/moviehouse/moviehouse.php?view=' . $view_movie_house . '&year=' . date("Y") . '&action=goConsulter" title="Movie House" class="onglet_inactif"><img src="/inside/includes/icons/common/movie_house.png" alt="movie_house" title="Movie House" class="logo_onglet" /></a>';
		$onglet_2 = '<a href="/inside/portail/foodadvisor/foodadvisor.php?action=goConsulter" title="Les enfants ! À table !" class="onglet_inactif""><img src="/inside/includes/icons/common/food_advisor.png" alt="food_advisor" title="Les enfants ! À table !" class="logo_onglet" /></a>';
		$onglet_3 = '<a href="/inside/portail/expensecenter/expensecenter.php?year=' . date("Y") . '&action=goConsulter" title="Expense Center" class="onglet_inactif""><img src="/inside/includes/icons/common/expense_center.png" alt="expense_center" title="Expense Center" class="logo_onglet" /></a>';
		$onglet_4 = '<a href="/inside/portail/collector/collector.php?action=goConsulter&page=1&sort=dateDesc&filter=none" title="Collector Room" class="onglet_inactif"><img src="/inside/includes/icons/common/collector.png" alt="collector" title="Collector Room" class="logo_onglet" /></a>';
		$onglet_5 = '<a href="/inside/portail/calendars/calendars.php?year=' . date("Y") . '&action=goConsulter" title="Calendars" class="onglet_inactif"><img src="/inside/includes/icons/common/calendars.png" alt="calendars" title="Calendars" class="logo_onglet" /></a>';
		$onglet_6 = '<a href="/inside/portail/petitspedestres/parcours.php?action=liste" title="Les Petits Pédestres" class="onglet_inactif"><img src="/inside/includes/icons/common/petits_pedestres.png" alt="petits_pedestres" title="Les Petits Pédestres" class="logo_onglet" /></a>';
		$onglet_7 = '<a href="/inside/portail/missions/missions.php?action=goConsulter" title="Missions : Insider" class="onglet_inactif"><img src="/inside/includes/icons/common/missions.png" alt="missions" title="Missions" class="logo_onglet" /></a>';
		//$onglet_8 = '<a href="/inside/portail/eventmanager/eventmanager.php?action=goConsulter" title="Event Manager" class="onglet_inactif"><img src="/inside/includes/icons/common/event_manager.png" alt="event_manager" title="Event Manager" class="logo_onglet" /></a>';

		// Activation des onglets en vérifiant la page courante et en remplaçant les valeurs par défaut au-dessus
		$path = $_SERVER['PHP_SELF'];

		/*echo 'page courante : ' . $path;*/

		// Movie House
		if ($path == '/inside/portail/moviehouse/moviehouse.php'
		OR  $path == '/inside/portail/moviehouse/saisie.php'
		OR  $path == '/inside/portail/moviehouse/details.php'
		OR  $path == '/inside/portail/moviehouse/mailing.php')
		{
			$onglet_1 = '<a href="/inside/portail/moviehouse/moviehouse.php?view=' . $view_movie_house . '&year=' . date("Y") . '&action=goConsulter" title="Movie House" class="onglet_actif"><img src="/inside/includes/icons/common/movie_house.png" alt="movie_house" title="Movie House" class="logo_onglet" /></a>';
		}

		// Les enfants ! À table !
		if ($path == '/inside/portail/foodadvisor/foodadvisor.php'
		OR  $path == '/inside/portail/foodadvisor/restaurants.php')
		{
			$onglet_2 = '<a href="/inside/portail/foodadvisor/foodadvisor.php?action=goConsulter" title="Les enfants ! À table !" class="onglet_actif""><img src="/inside/includes/icons/common/food_advisor.png" alt="food_advisor" title="Les enfants ! À table !" class="logo_onglet" /></a>';
		}

		// Expense center
		if ($path == '/inside/portail/expensecenter/expensecenter.php')
		{
			$onglet_3 = '<a href="/inside/portail/expensecenter/expensecenter.php?year=' . date("Y") . '&action=goConsulter" title="Expense Center" class="onglet_actif""><img src="/inside/includes/icons/common/expense_center.png" alt="expense_center" title="Expense Center" class="logo_onglet" /></a>';
		}

		// Collector Room
		if ($path == '/inside/portail/collector/collector.php')
		{
			$onglet_4 = '<a href="/inside/portail/collector/collector.php?action=goConsulter&page=1&sort=dateDesc&filter=none" title="Collector Room" class="onglet_actif"><img src="/inside/includes/icons/common/collector.png" alt="collector" title="Collector Room" class="logo_onglet" /></a>';
		}

		// Calendars
		if ($path == '/inside/portail/calendars/calendars.php')
		{
			$onglet_5 = '<a href="/inside/portail/calendars/calendars.php?year=' . date("Y") . '&action=goConsulter" title="Calendars" class="onglet_actif"><img src="/inside/includes/icons/common/calendars.png" alt="calendars" title="Calendars" class="logo_onglet" /></a>';
		}

		// Petits pédestres
		if ($path == '/inside/portail/petitspedestres/parcours.php')
		{
			$onglet_6 = '<a href="/inside/portail/petitspedestres/parcours.php?action=liste" class="onglet_actif" title="Les Petits Pédestres"><img src="/inside/includes/icons/common/petits_pedestres.png" alt="petits_pedestres" title="Les Petits Pédestres" class="logo_onglet" /></a>';
		}

		// Missions : Insider
		if ($path == '/inside/portail/missions/missions.php'
		OR  $path == '/inside/portail/missions/details.php')
		{
			$onglet_7 = '<a href="/inside/portail/missions/missions.php?action=goConsulter" title="Missions : Insider" class="onglet_actif"><img src="/inside/includes/icons/common/missions.png" alt="missions" title="Missions" class="logo_onglet" /></a>';
		}

		/*if ($path == '/inside/portail/eventmanager/eventmanager.php')
		{
			$onglet_8 = '<a href="/inside/portail/eventmanager/eventmanager.php?action=goConsulter" title="Event Manager" class="onglet_actif"><img src="/inside/includes/icons/common/event_manager.png" alt="event_manager" title="Event Manager" class="logo_onglet" /></a>';
		}*/

		echo $onglet_1, $onglet_2, $onglet_3, $onglet_4, $onglet_5, $onglet_6, $onglet_7/*, $onglet_8*/;
	echo '</nav>';
?>
