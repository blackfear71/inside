<?php
    // Récupération des préférences
    switch ($_SESSION['user']['view_movie_house'])
    {
        case 'C':
            $viewMovieHouse = 'cards';
            break;

        case 'H':
        default:
            $viewMovieHouse = 'home';
            break;
    }

    // Bande de séparation
    echo '<div class="separation_nav"></div>';

    // Affichage du menu de navigation
    echo '<nav class="menu_nav">';
        // Initialisation des onglets (désélectionnés par défaut)
        $onglet1 = '<a href="/portail/moviehouse/moviehouse.php?view=' . $viewMovieHouse . '&year=' . date('Y') . '&action=goConsulter" title="Movie House" class="onglet_inactif"><img src="/includes/icons/common/movie_house.png" alt="movie_house" title="Movie House" class="logo_onglet" /></a>';
        $onglet2 = '<a href="/portail/foodadvisor/foodadvisor.php?date=' . date('Ymd') . '&action=goConsulter" title="Les enfants ! À table !" class="onglet_inactif"><img src="/includes/icons/common/food_advisor.png" alt="food_advisor" title="Les enfants ! À table !" class="logo_onglet" /></a>';
        $onglet3 = '<a href="/portail/cookingbox/cookingbox.php?year=' . date('Y') . '&action=goConsulter" title="Cooking Box" class="onglet_inactif"><img src="/includes/icons/common/cooking_box.png" alt="cooking_box" title="Cooking Box" class="logo_onglet" /></a>';
        $onglet4 = '<a href="/portail/expensecenter/expensecenter.php?year=' . date('Y') . '&filter=all&action=goConsulter" title="Expense Center" class="onglet_inactif"><img src="/includes/icons/common/expense_center.png" alt="expense_center" title="Expense Center" class="logo_onglet" /></a>';
        $onglet5 = '<a href="/portail/collector/collector.php?sort=dateDesc&filter=none&action=goConsulter&page=1" title="Collector Room" class="onglet_inactif"><img src="/includes/icons/common/collector.png" alt="collector" title="Collector Room" class="logo_onglet" /></a>';
        $onglet6 = '<a href="/portail/calendars/calendars.php?year=' . date('Y') . '&action=goConsulter" title="Calendars" class="onglet_inactif"><img src="/includes/icons/common/calendars.png" alt="calendars" title="Calendars" class="logo_onglet" /></a>';
        $onglet7 = '<a href="/portail/petitspedestres/petitspedestres.php?action=goConsulter" title="Les Petits Pédestres" class="onglet_inactif"><img src="/includes/icons/common/petits_pedestres.png" alt="petits_pedestres" title="Les Petits Pédestres" class="logo_onglet" /></a>';
        $onglet8 = '<a href="/portail/missions/missions.php?action=goConsulter" title="Missions : Insider" class="onglet_inactif"><img src="/includes/icons/common/missions.png" alt="missions" title="Missions : Insider" class="logo_onglet" /></a>';
        //$onglet9 = '<a href="/portail/eventmanager/eventmanager.php?action=goConsulter" title="Event Manager" class="onglet_inactif"><img src="/includes/icons/common/event_manager.png" alt="event_manager" title="Event Manager" class="logo_onglet" /></a>';

        // Activation de l'onglets de la page courante en remplaçant les valeurs par défaut
        $path = $_SERVER['PHP_SELF'];

        // Movie House
        if ($path == '/portail/moviehouse/moviehouse.php'
        OR  $path == '/portail/moviehouse/details.php'
        OR  $path == '/portail/moviehouse/mailing.php')
        {
            $onglet1 = '<a href="/portail/moviehouse/moviehouse.php?view=' . $viewMovieHouse . '&year=' . date('Y') . '&action=goConsulter" title="Movie House" class="onglet_actif"><img src="/includes/icons/common/movie_house.png" alt="movie_house" title="Movie House" class="logo_onglet" /></a>';
        }

        // Les enfants ! À table !
        if ($path == '/portail/foodadvisor/foodadvisor.php'
        OR  $path == '/portail/foodadvisor/restaurants.php')
        {
            $onglet2 = '<a href="/portail/foodadvisor/foodadvisor.php?date=' . date('Ymd') . '&action=goConsulter" title="Les enfants ! À table !" class="onglet_actif"><img src="/includes/icons/common/food_advisor.png" alt="food_advisor" title="Les enfants ! À table !" class="logo_onglet" /></a>';
        }

        // Cooking Box
        if ($path == '/portail/cookingbox/cookingbox.php')
        {
            $onglet3 = '<a href="/portail/cookingbox/cookingbox.php?year=' . date('Y') . '&action=goConsulter" title="Cooking Box" class="onglet_actif"><img src="/includes/icons/common/cooking_box.png" alt="cooking_box" title="Cooking Box" class="logo_onglet" /></a>';
        }

        // Expense center
        if ($path == '/portail/expensecenter/expensecenter.php')
        {
            $onglet4 = '<a href="/portail/expensecenter/expensecenter.php?year=' . date('Y') . '&filter=all&action=goConsulter" title="Expense Center" class="onglet_actif"><img src="/includes/icons/common/expense_center.png" alt="expense_center" title="Expense Center" class="logo_onglet" /></a>';
        }

        // Collector Room
        if ($path == '/portail/collector/collector.php')
        {
            $onglet5 = '<a href="/portail/collector/collector.php?sort=dateDesc&filter=none&action=goConsulter&page=1" title="Collector Room" class="onglet_actif"><img src="/includes/icons/common/collector.png" alt="collector" title="Collector Room" class="logo_onglet" /></a>';
        }

        // Calendars
        if ($path == '/portail/calendars/calendars.php'
        OR  $path == '/portail/calendars/calendars_generator.php')
        {
            $onglet6 = '<a href="/portail/calendars/calendars.php?year=' . date('Y') . '&action=goConsulter" title="Calendars" class="onglet_actif"><img src="/includes/icons/common/calendars.png" alt="calendars" title="Calendars" class="logo_onglet" /></a>';
        }

        // Petits pédestres
        if ($path == '/portail/petitspedestres/petitspedestres.php'
        OR  $path == '/portail/petitspedestres/details.php')
        {
            $onglet7 = '<a href="/portail/petitspedestres/petitspedestres.php?action=goConsulter" class="onglet_actif" title="Les Petits Pédestres"><img src="/includes/icons/common/petits_pedestres.png" alt="petits_pedestres" title="Les Petits Pédestres" class="logo_onglet" /></a>';
        }

        // Missions : Insider
        if ($path == '/portail/missions/missions.php'
        OR  $path == '/portail/missions/details.php')
        {
            $onglet8 = '<a href="/portail/missions/missions.php?action=goConsulter" title="Missions : Insider" class="onglet_actif"><img src="/includes/icons/common/missions.png" alt="missions" title="Missions : Insider" class="logo_onglet" /></a>';
        }

        /*// Event Manager
        if ($path == '/portail/eventmanager/eventmanager.php')
        {
            $onglet9 = '<a href="/portail/eventmanager/eventmanager.php?action=goConsulter" title="Event Manager" class="onglet_actif"><img src="/includes/icons/common/event_manager.png" alt="event_manager" title="Event Manager" class="logo_onglet" /></a>';
        }*/

        // Affichage des onglets
        echo $onglet1, $onglet2, $onglet3, $onglet4, $onglet5, $onglet6, $onglet7, $onglet8/*, $onglet9*/;
    echo '</nav>';
?>