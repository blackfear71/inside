<?php
    /***********************
    *** Gestion des fims ***
    ************************
    Fonctionnalités :
    - Suppression des films
    ***********************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');

    // Contrôles communs Administrateur
    controlsAdmin();

    // Modèle de données
    include_once('modele/metier_movies.php');
    include_once('modele/physique_movies.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Récupération de la liste des équipes
            $listeEquipes = getListeEquipes();

            // Récupération de la liste des films à supprimer
            $listeSuppression = getFilmsToDelete();
            break;

        case 'doDeleteFilm':
            // Suppression d'un film
            deleteFilm($_POST);
            break;

        case 'doResetFilm':
            // Annulation de la demande de suppression d'un film
            resetFilm($_POST);
            break;

        default:
            // Contrôle action renseignée URL
            header('location: movies.php?action=goConsulter');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goConsulter':
            foreach ($listeEquipes as &$equipe)
            {
                $equipe = Team::secureData($equipe);
            }

            unset($equipe);            

            foreach ($listeSuppression as &$film)
            {
                $film = Movie::secureData($film);
            }

            unset($film);
            break;

        case 'doDeleteFilm':
        case 'doResetFilm':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doDeleteFilm':
        case 'doResetFilm':
            header('location: movies.php?action=goConsulter');
            break;

        case 'goConsulter':
        default:
            include_once('vue/vue_movies.php');
            break;
    }
?>