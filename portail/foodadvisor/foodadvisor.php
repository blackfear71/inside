<?php
    /**********************************
    ***** Les enfants ! À table ! *****
    ***********************************
    Fonctionnalités :
    - Consultation propositions
    - Bande à part
    - Utilisateurs en attente
    - Ajout de propositions
    - Modification de propositions
    - Suppression de propositions
    - Consultation détails propositions
    - Détermination choix
    - Réservation choix
    - Choix complet
    - Modification jour sans choix
    **********************************/

    // Fonctions communes
    include_once('../../includes/functions/metier_commun.php');
    include_once('../../includes/functions/physique_commun.php');
    include_once('../../includes/functions/fonctions_dates.php');
    include_once('../../includes/functions/fonctions_regex.php');

    // Contrôles communs Utilisateur
    controlsUser();

    // Modèle de données
    include_once('modele/metier_foodadvisor_commun.php');
    include_once('modele/metier_foodadvisor.php');
    include_once('modele/controles_foodadvisor_commun.php');
    include_once('modele/controles_foodadvisor.php');
    include_once('modele/physique_foodadvisor_commun.php');
    include_once('modele/physique_foodadvisor.php');

    // Appels métier
    switch ($_GET['action'])
    {
        case 'goConsulter':
            // Récupération de tous les lieux
            $listeLieuxDisponibles = getLieux($_SESSION['user']['equipe']);

            // Récupération de tous les restaurants (existants)
            $listeRestaurantsResume = getListeRestaurants($listeLieuxDisponibles, $_SESSION['user']['equipe']);

            // Filtrage de la liste des restaurants (ouverts)
            $listeRestaurants = getListeRestaurantsOuverts($listeRestaurantsResume, $_SESSION['user']['equipe']);

            // Filtrage de la liste des lieux (restaurants ouverts)
            $listeLieux = getLieuxFiltres($listeRestaurants);

            // Récupération des propositions (avec détails)
            $propositions = getPropositions($_SESSION['user']['equipe'], true);

            // Récupération des utilisateurs qui font bande à part
            $solos = getSolos($_SESSION['user']['equipe']);

            // Récupération des choix utilisateur
            $mesChoix = getMyChoices($_SESSION['user']);

            // Détermination si bande à part
            $isSolo = getSolo($_SESSION['user']);

            // Détermination si restaurant réservé
            $isReserved = getReserved($_SESSION['user']['equipe']);

            // Récupération du résumé de la semaine
            $choixSemaine = getWeekChoices($_SESSION['user']['equipe']);

            // Détermination des actions possibles
            $actions = getActions($propositions, $mesChoix, $isSolo, $isReserved, $_SESSION['user']['identifiant']);

            // Récupération des utilisateurs n'ayant pas voté
            if (!empty($propositions) OR !empty($solos))
                $sansPropositions = getNoPropositions($_SESSION['user']['equipe']);
            break;

        case 'doDeterminer':
            // Récupération des propositions (sans détails)
            $propositions = getPropositions($_SESSION['user']['equipe'], false);

            // Récupération de l'Id du restaurant déterminé
            $idRestaurant = getRestaurantDetermined($propositions);

            // Détermination si bande à part
            $isSolo = getSolo($_SESSION['user']);

            // Détermination si restaurant réservé
            $isReserved = getReserved($_SESSION['user']['equipe']);

            // Lancement de la détermination
            if ((!isset($_SESSION['alerts']['week_end_determination']) OR $_SESSION['alerts']['week_end_determination'] != true)
            AND (!isset($_SESSION['alerts']['determination_time'])     OR $_SESSION['alerts']['determination_time']     != true)
            AND  $isSolo != true AND empty($isReserved))
            {
                // Récupération des appelants possibles
                $appelant = getCallers($idRestaurant, $_SESSION['user']['equipe']);

                // Lancement de la détermination
                setDetermination($propositions, $idRestaurant, $appelant, $_SESSION['user']['equipe']);
            }
            break;

        case 'doSolo':
            // Récupération des choix utilisateur
            $mesChoix = getMyChoices($_SESSION['user']);

            // Détermination si bande à part
            $isSolo = getSolo($_SESSION['user']);

            // Insertion bande à part
            setSolo($mesChoix, $isSolo, $_SESSION['user']);
            break;

        case 'doSupprimerSolo':
            // Suppression bande à part
            deleteSolo($_SESSION['user']);
            break;

        case 'doReserver':
            // Insertion réservation
            insertReservation($_POST, $_SESSION['user']);
            break;

        case 'doAnnulerReserver':
            // Suppression réservation
            deleteReservation($_POST, $_SESSION['user']);
            break;

        case 'doComplet':
            // Insertion restaurant complet
            completeChoice($_POST, $_SESSION['user']);
            break;

        case 'doAjouter':
            // Détermination si bande à part
            $isSolo = getSolo($_SESSION['user']);

            // Insertion choix
            insertChoices($_POST, $isSolo, $_SESSION['user']);
            break;

        case 'doModifier':
            // Modification d'un choix
            updateChoice($_POST, $_SESSION['user']['identifiant']);
            break;

        case 'doSupprimer':
            // Suppression d'un choix
            deleteChoice($_POST, $_SESSION['user']);
            break;

        case 'doSupprimerChoix':
            // Suppression de tous les choix
            deleteAllChoices($_SESSION['user']);
            break;

        case 'doChoixRapide':
            // Détermination si bande à part
            $isSolo = getSolo($_SESSION['user']);

            // Insertion choix rapide
            insertFastChoice($_POST, $isSolo, $_SESSION['user']);
            break;

        case 'doAjouterResume':
            // Insertion choix résumé de la semaine
            insertResume($_POST, $_SESSION['user']['equipe']);
            break;

        case 'doSupprimerResume':
            // Suppression choix résumé de la semaine
            deleteResume($_POST, $_SESSION['user']['equipe']);
            break;

        default:
            // Contrôle action renseignée URL
            header('location: foodadvisor.php?action=goConsulter');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goConsulter':
            foreach ($listeLieuxDisponibles as &$lieu)
            {
                $lieu = htmlspecialchars($lieu);
            }

            unset($lieu);

            foreach ($listeRestaurantsResume as $restaurantsParLieuxResume)
            {
                foreach ($restaurantsParLieuxResume as $restaurant)
                {
                    Restaurant::secureData($restaurant);
                }
            }

            // Les restaurants de cette liste sont échappés par la boucle précédente à cause de la propriété static de la méthode secureData
            /*foreach ($listeRestaurants as $restaurantsParLieux)
            {
                foreach ($restaurantsParLieux as $restaurant)
                {
                    Restaurant::secureData($restaurant);
                }
            }*/

            foreach ($listeLieux as &$lieu)
            {
                $lieu = htmlspecialchars($lieu);
            }

            unset($lieu);

            foreach ($propositions as $proposition)
            {
                Proposition::secureData($proposition);
            }

            foreach ($solos as $solo)
            {
                Profile::secureData($solo);
            }

            foreach ($mesChoix as $monChoix)
            {
                Choix::secureData($monChoix);
            }

            foreach ($choixSemaine as $choixJour)
            {
                if (!empty($choixJour))
                {
                    Proposition::secureData($choixJour);
                }
            }

            if (!empty($sansPropositions))
            {
                foreach ($sansPropositions as $userNoChoice)
                {
                    Profile::secureData($userNoChoice);
                }
            }

            // Conversion JSON
            $listeLieuxResumeJson       = json_encode($listeLieuxDisponibles);
            $listeRestaurantsResumeJson = json_encode(convertForJsonListeRestaurantsParLieu($listeRestaurantsResume));
            $listeLieuxJson             = json_encode($listeLieux);
            $listeRestaurantsJson       = json_encode(convertForJsonListeRestaurantsParLieu($listeRestaurants));
            $detailsPropositions        = json_encode(convertForJsonListePropositions($propositions));
            break;

        case 'doDeterminer':
        case 'doSolo':
        case 'doSupprimerSolo':
        case 'doReserver':
        case 'doAnnulerReserver':
        case 'doComplet':
        case 'doAjouter':
        case 'doModifier':
        case 'doSupprimer':
        case 'doSupprimerChoix':
        case 'doChoixRapide':
        case 'doAjouterResume':
        case 'doSupprimerResume':
        default:
            break;
    }

    // Redirection affichage
    switch ($_GET['action'])
    {
        case 'doDeterminer':
        case 'doSolo':
        case 'doSupprimerSolo':
        case 'doReserver':
        case 'doAnnulerReserver':
        case 'doComplet':
        case 'doAjouter':
        case 'doModifier':
        case 'doSupprimer':
        case 'doSupprimerChoix':
        case 'doChoixRapide':
        case 'doAjouterResume':
        case 'doSupprimerResume':
            header('location: foodadvisor.php?action=goConsulter');
            break;

        case 'goConsulter':
        default:
            include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_foodadvisor.php');
            break;
    }
?>