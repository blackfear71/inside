<?php
    include_once('../../includes/functions/appel_bdd.php');
    include_once('../../includes/classes/movies.php');
    include_once('../../includes/classes/restaurants.php');
    include_once('../../includes/classes/parcours.php');
    include_once('../../includes/classes/missions.php');

    // METIER : Initialise la sauvegarde en session de la recherche
    // RETOUR : Aucun
    function initializeSaveSearch()
    {
        // Sauvegarde en session de la dernière recherche
        $_SESSION['search']['text_search'] = $_POST['text_search'];
    }

    // METIER : Recherche dans les bases de données
    // RETOUR : Tableau des résultats par catégorie
    function getSearch($search, $equipe)
    {
        // Initialisations
        $resultatsRecherche = array();

        // Récupération des données
        $recherche = htmlspecialchars($search);

        // Lancement de la recherche
        if (!empty($recherche))
        {
            // Movie House (films non à supprimer)
            $resultatsMovieHouse       = physiqueRechercheFilms($recherche, $equipe);
            $nombreResultatsMovieHouse = count($resultatsMovieHouse);

            // Restaurants
            $resultatsFoodAdvisor       = physiqueRechercheRestaurants($recherche, $equipe);
            $nombreResultatsFoodAdvisor = count($resultatsFoodAdvisor);

            // Parcours
            $resultatsPetitsPedestres       = physiqueRechercheParcours($recherche, $equipe);
            $nombreResultatsPetitsPedestres = count($resultatsPetitsPedestres);

            // Missions (déjà commencées ou terminées)
            $resultatsMissions       = physiqueRechercheMissions($recherche);
            $nombreResultatsMissions = count($resultatsMissions);

            // Ajout des résultats au tableau
            $resultatsRecherche = array(
                'movie_house'         => $resultatsMovieHouse,
                'food_advisor'        => $resultatsFoodAdvisor,
                'petits_pedestres'    => $resultatsPetitsPedestres,
                'missions'            => $resultatsMissions,
                'nb_movie_house'      => $nombreResultatsMovieHouse,
                'nb_food_advisor'     => $nombreResultatsFoodAdvisor,
                'nb_petits_pedestres' => $nombreResultatsPetitsPedestres,
                'nb_missions'         => $nombreResultatsMissions
            );
        }

        // Retour
        return $resultatsRecherche;
    }
?>