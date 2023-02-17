<?php
    /**************************
    ******** Recherche ********
    ***************************
    Fonctionnalités :
    - Recherche de films
    - Recherche de restaurants
    - Recherche de courses
    - Recherche de missions
    **************************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');
    include_once('../../includes/functions/fonctions_dates.php');
    include_once('../../includes/functions/fonctions_regex.php');

    // Contrôles communs Utilisateur
    controlsUser();

    // Modèle de données
    include_once('modele/metier_search.php');
    include_once('modele/physique_search.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'doSearch':
            // Initialisation de la sauvegarde en session
            initializeSaveSearch();
            break;

        case 'goSearch':
            // Récupération des résultats de recherche
            $resultats = getSearch($_SESSION['search']['text_search'], $_SESSION['user']['equipe']);
            break;

        default:
            // Contrôle action renseignée URL
            header('location: search.php?action=goSearch');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goSearch':
            if (!empty($resultats))
            {
                foreach ($resultats['movie_house'] as $resultatsMH)
                {
                    Movie::secureData($resultatsMH);
                }

                foreach ($resultats['food_advisor'] as &$resultatsFA)
                {
                    $resultatsFA = Restaurant::secureData($resultatsFA);
                }

                unset($resultatsFA);

                foreach ($resultats['petits_pedestres'] as &$resultatsPP)
                {
                    $resultatsPP = Parcours::secureData($resultatsPP);
                }

                unset($resultatsPP);

                foreach ($resultats['missions'] as $resultatsMI)
                {
                    Mission::secureData($resultatsMI);
                }
            }
            break;

        case 'doSearch':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doSearch':
            header('location: search.php?action=goSearch');
            break;

        case 'goSearch':
        default:
            include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_search.php');
            break;
    }
?>