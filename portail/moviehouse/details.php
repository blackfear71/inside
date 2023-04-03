<?php
    /************************
    ****** Movie House ******
    *************************
    Fonctionnalités :
    - Détails du film
    - Modification du film
    - Suppression du film
    - Gestion des préférences
    - Commentaires
    ************************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');
    include_once('../../includes/functions/fonctions_dates.php');
    include_once('../../includes/functions/fonctions_regex.php');

    // Contrôles communs Utilisateur
    controlsUser();

    // Modèle de données
    include_once('modele/metier_moviehouse_commun.php');
    include_once('modele/metier_details.php');
    include_once('modele/controles_moviehouse_commun.php');
    include_once('modele/controles_details.php');
    include_once('modele/physique_moviehouse_commun.php');
    include_once('modele/physique_details.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Contrôle si l'id est renseignée et numérique
            if (!isset($_GET['id_film']) OR !is_numeric($_GET['id_film']))
                header('location: moviehouse.php?view=home&year=' . date('Y') . '&action=goConsulter');
            else
            {
                // Initialisation de la sauvegarde en session
                initializeSaveSession();

                // Vérification film disponible
                $filmExistant = isFilmDisponible($_GET['id_film'], $_SESSION['user']['equipe']);

                if ($filmExistant == true)
                {
                    // Récupération des détails du film
                    $detailsFilm = getDetails($_GET['id_film'], $_SESSION['user']['identifiant']);

                    // Récupération des films précédent et suivant
                    $listeNavigation = getNavigation($detailsFilm, $_SESSION['user']['equipe']);

                    // Récupération de la liste des utilisateurs
                    $listeUsersDetails = getUsersDetailsFilm($_GET['id_film'], $_SESSION['user']['equipe']);

                    // Récupération des votes associés au film
                    $listeEtoiles = getEtoilesDetailsFilm($_GET['id_film'], $listeUsersDetails, $_SESSION['user']['equipe']);

                    // Récupération des commentaires associés aux films
                    $listeCommentaires = getCommentaires($_GET['id_film'], $listeUsersDetails);
                }
            }
            break;

        case 'doModifier':
            // Modification d'un film
            $idFilm = updateFilm($_POST, $_SESSION['user'], false);
            break;

        case 'doModifierMobile':
            // Modification d'un film
            $idFilm = updateFilm($_POST, $_SESSION['user'], true);
            break;

        case 'doSupprimer':
            // Récupération de la vue pour redirection
            $viewMovieHouse = getVueSuppression($_SESSION['user']['identifiant']);

            // Suppression d'un film
            deleteFilm($_POST, $_SESSION['user']['identifiant']);
            break;

        case 'doVoterFilm':
            // Vote de l'utilisateur sur un film
            $idFilm = insertStar($_POST, $_SESSION['user']['identifiant']);
            break;

        case 'doParticiperFilm':
            // Action de l'utilisateur sur un film
            $idFilm = insertParticipation($_POST, $_SESSION['user']['identifiant']);
            break;

        case 'doCommenter':
            // Insertion commentaire de l'utilisateur sur un film
            $idFilm = insertCommentaire($_POST, $_SESSION['user']);
            break;

        case 'doSupprimerCommentaire':
            // Suppression commentaire de l'utilisateur sur un film
            $idFilm = deleteCommentaire($_POST, $_SESSION['user']);
            break;

        case 'doModifierCommentaire':
            // Modification commentaire de l'utilisateur sur un film
            $ids = updateCommentaire($_POST);
            break;

        default:
            // Contrôle action renseignée URL
            header('location: details.php?id_film=' . $_GET['id_film'] . '&action=goConsulter');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goConsulter':
            if ($filmExistant == true)
            {
                $detailsFilm = Movie::secureData($detailsFilm);

                foreach ($listeNavigation as &$navigation)
                {
                    if (!empty($navigation))
                    {
                        $navigation['id']   = htmlspecialchars($navigation['id']);
                        $navigation['film'] = htmlspecialchars($navigation['film']);
                    }
                }

                unset($navigation);

                foreach ($listeUsersDetails as &$user)
                {
                    $user['pseudo'] = htmlspecialchars($user['pseudo']);
                    $user['avatar'] = htmlspecialchars($user['avatar']);
                    $user['email']  = htmlspecialchars($user['email']);
                }

                unset($user);

                foreach ($listeEtoiles as &$etoiles)
                {
                    $etoiles = Stars::secureData($etoiles);
                }

                unset($etoiles);

                foreach ($listeCommentaires as &$comment)
                {
                    $comment = Commentaire::secureData($comment);
                }

                unset($comment);

                // Conversion JSON
                $detailsFilmJson = json_encode(convertForJsonDetailsFilm($detailsFilm));
            }
            break;

        case 'doModifier':
        case 'doModifierMobile':
        case 'doSupprimer':
        case 'doVoterFilm':
        case 'doParticiperFilm':
        case 'doCommenter':
        case 'doSupprimerCommentaire':
        case 'doModifierCommentaire':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doModifier':
        case 'doModifierMobile':
        case 'doVoterFilm':
        case 'doParticiperFilm':
            header('location: details.php?id_film=' . $idFilm . '&action=goConsulter');
            break;

        case 'doSupprimer':
            header('location: moviehouse.php?view=' . $viewMovieHouse . '&year=' . date('Y') . '&action=goConsulter');
            break;

        case 'doCommenter':
        case 'doSupprimerCommentaire':
            header('location: details.php?id_film=' . $idFilm . '&action=goConsulter&anchor=comments');
            break;

        case 'doModifierCommentaire':
            header('location: details.php?id_film=' . $ids['id_film'] . '&action=goConsulter&anchor=' . $ids['id_comment']);
            break;

        case 'goConsulter':
        default:
            include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_details_film.php');
            break;
    }
?>