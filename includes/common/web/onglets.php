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
        $onglet1 = '<a href="/inside/portail/moviehouse/moviehouse.php?view=' . $viewMovieHouse . '&year=' . date('Y') . '&action=goConsulter" title="Movie House" class="onglet_inactif"><img src="/inside/includes/icons/common/movie_house.png" alt="movie_house" title="Movie House" class="logo_onglet" /></a>';
        $onglet2 = '<a href="/inside/portail/foodadvisor/foodadvisor.php?action=goConsulter" title="Les enfants ! À table !" class="onglet_inactif"><img src="/inside/includes/icons/common/food_advisor.png" alt="food_advisor" title="Les enfants ! À table !" class="logo_onglet" /></a>';
        $onglet3 = '<a href="/inside/portail/cookingbox/cookingbox.php?year=' . date('Y') . '&action=goConsulter" title="Cooking Box" class="onglet_inactif"><img src="/inside/includes/icons/common/cooking_box.png" alt="cooking_box" title="Cooking Box" class="logo_onglet" /></a>';
        $onglet4 = '<a href="/inside/portail/expensecenter/expensecenter.php?year=' . date('Y') . '&filter=all&action=goConsulter" title="Expense Center" class="onglet_inactif"><img src="/inside/includes/icons/common/expense_center.png" alt="expense_center" title="Expense Center" class="logo_onglet" /></a>';
        $onglet5 = '<a href="/inside/portail/collector/collector.php?action=goConsulter&page=1&sort=dateDesc&filter=none" title="Collector Room" class="onglet_inactif"><img src="/inside/includes/icons/common/collector.png" alt="collector" title="Collector Room" class="logo_onglet" /></a>';
        $onglet6 = '<a href="/inside/portail/calendars/calendars.php?year=' . date('Y') . '&action=goConsulter" title="Calendars" class="onglet_inactif"><img src="/inside/includes/icons/common/calendars.png" alt="calendars" title="Calendars" class="logo_onglet" /></a>';
        $onglet7 = '<a href="/inside/portail/petitspedestres/parcours.php?action=goConsulterListe" title="Les Petits Pédestres" class="onglet_inactif"><img src="/inside/includes/icons/common/petits_pedestres.png" alt="petits_pedestres" title="Les Petits Pédestres" class="logo_onglet" /></a>';
        $onglet8 = '<a href="/inside/portail/missions/missions.php?action=goConsulter" title="Missions : Insider" class="onglet_inactif"><img src="/inside/includes/icons/common/missions.png" alt="missions" title="Missions : Insider" class="logo_onglet" /></a>';
        //$onglet9 = '<a href="/inside/portail/eventmanager/eventmanager.php?action=goConsulter" title="Event Manager" class="onglet_inactif"><img src="/inside/includes/icons/common/event_manager.png" alt="event_manager" title="Event Manager" class="logo_onglet" /></a>';

        // Activation de l'onglets de la page courante en remplaçant les valeurs par défaut
        $path = $_SERVER['PHP_SELF'];

        // Movie House
        if ($path == '/inside/portail/moviehouse/moviehouse.php'
        OR  $path == '/inside/portail/moviehouse/details.php'
        OR  $path == '/inside/portail/moviehouse/mailing.php')
        {
            $onglet1 = '<a href="/inside/portail/moviehouse/moviehouse.php?view=' . $viewMovieHouse . '&year=' . date('Y') . '&action=goConsulter" title="Movie House" class="onglet_actif"><img src="/inside/includes/icons/common/movie_house.png" alt="movie_house" title="Movie House" class="logo_onglet" /></a>';
        }

        // Les enfants ! À table !
        if ($path == '/inside/portail/foodadvisor/foodadvisor.php'
        OR  $path == '/inside/portail/foodadvisor/restaurants.php')
        {
            $onglet2 = '<a href="/inside/portail/foodadvisor/foodadvisor.php?action=goConsulter" title="Les enfants ! À table !" class="onglet_actif"><img src="/inside/includes/icons/common/food_advisor.png" alt="food_advisor" title="Les enfants ! À table !" class="logo_onglet" /></a>';
        }

        // Cooking Box
        if ($path == '/inside/portail/cookingbox/cookingbox.php')
        {
            $onglet3 = '<a href="/inside/portail/cookingbox/cookingbox.php?year=' . date('Y') . '&action=goConsulter" title="Cooking Box" class="onglet_actif"><img src="/inside/includes/icons/common/cooking_box.png" alt="cooking_box" title="Cooking Box" class="logo_onglet" /></a>';
        }

        // Expense center
        if ($path == '/inside/portail/expensecenter/expensecenter.php')
        {
            $onglet4 = '<a href="/inside/portail/expensecenter/expensecenter.php?year=' . date('Y') . '&filter=all&action=goConsulter" title="Expense Center" class="onglet_actif"><img src="/inside/includes/icons/common/expense_center.png" alt="expense_center" title="Expense Center" class="logo_onglet" /></a>';
        }

        // Collector Room
        if ($path == '/inside/portail/collector/collector.php')
        {
            $onglet5 = '<a href="/inside/portail/collector/collector.php?action=goConsulter&page=1&sort=dateDesc&filter=none" title="Collector Room" class="onglet_actif"><img src="/inside/includes/icons/common/collector.png" alt="collector" title="Collector Room" class="logo_onglet" /></a>';
        }

        // Calendars
        if ($path == '/inside/portail/calendars/calendars.php'
        OR  $path == '/inside/portail/calendars/calendars_generator.php')
        {
            $onglet6 = '<a href="/inside/portail/calendars/calendars.php?year=' . date('Y') . '&action=goConsulter" title="Calendars" class="onglet_actif"><img src="/inside/includes/icons/common/calendars.png" alt="calendars" title="Calendars" class="logo_onglet" /></a>';
        }

        // Petits pédestres
        if ($path == '/inside/portail/petitspedestres/parcours.php')
        {
            $onglet7 = '<a href="/inside/portail/petitspedestres/parcours.php?action=goConsulterListe" class="onglet_actif" title="Les Petits Pédestres"><img src="/inside/includes/icons/common/petits_pedestres.png" alt="petits_pedestres" title="Les Petits Pédestres" class="logo_onglet" /></a>';
        }

        // Missions : Insider
        if ($path == '/inside/portail/missions/missions.php'
        OR  $path == '/inside/portail/missions/details.php')
        {
            $onglet8 = '<a href="/inside/portail/missions/missions.php?action=goConsulter" title="Missions : Insider" class="onglet_actif"><img src="/inside/includes/icons/common/missions.png" alt="missions" title="Missions : Insider" class="logo_onglet" /></a>';
        }

        /*// Event Manager
        if ($path == '/inside/portail/eventmanager/eventmanager.php')
        {
            $onglet9 = '<a href="/inside/portail/eventmanager/eventmanager.php?action=goConsulter" title="Event Manager" class="onglet_actif"><img src="/inside/includes/icons/common/event_manager.png" alt="event_manager" title="Event Manager" class="logo_onglet" /></a>';
        }*/

        // Affichage des onglets
        echo $onglet1, $onglet2, $onglet3, $onglet4, $onglet5, $onglet6, $onglet7, $onglet8/*, $onglet9*/;
    echo '</nav>';
?>