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
        case 'doRechercher':
            // Initialisation de la sauvegarde en session
            initializeSaveSearch();
            break;

        case 'goRechercher':
            // Récupération des résultats de recherche
            $resultats = getSearch($_SESSION['search']['text_search'], $_SESSION['user']['equipe']);
            break;

        default:
            // Contrôle action renseignée URL
            header('location: search.php?action=goRechercher');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goRechercher':
            if (!empty($resultats))
            {
                foreach ($resultats['movie_house'] as &$resultatsMH)
                {
                    $resultatsMH = Movie::secureData($resultatsMH);
                }

                unset($resultatsMH);

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

                foreach ($resultats['missions'] as &$resultatsMI)
                {
                    $resultatsMI = Mission::secureData($resultatsMI);
                }

                unset($resultatsMI);
            }
            break;

        case 'doRechercher':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doRechercher':
            header('location: search.php?action=goRechercher');
            break;

        case 'goRechercher':
        default:
            include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_search.php');
            break;
    }
?>