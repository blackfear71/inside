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
            // Contrôle si la date est renseignée et numérique
            if (!isset($_GET['date']) OR !is_numeric($_GET['date']))
                header('location: foodadvisor.php?date=' . date('Ymd') . '&action=goConsulter');
            else
            {
                $erreurDate = controlDateValide($_GET['date']);

                // Redirection si la date n'est pas valide
                if ($erreurDate == true)
                    header('location: foodadvisor.php?date=' . date('Ymd') . '&action=goConsulter');
                else
                {
                    // Récupération des jours de la semaine
                    $joursSemaine = getJoursSemaine($_GET['date']);

                    // Récupération de tous les lieux
                    $listeLieuxDisponibles = getLieux($_SESSION['user']['equipe']);

                    // Récupération de tous les restaurants (existants)
                    $listeRestaurantsResume = getListeRestaurants($listeLieuxDisponibles, $_SESSION['user']['equipe']);

                    // Filtrage de la liste des restaurants (ouverts)
                    $listeRestaurants = getListeRestaurantsOuverts($listeRestaurantsResume, $_GET['date']);

                    // Filtrage de la liste des lieux (restaurants ouverts)
                    $listeLieux = getLieuxFiltres($listeRestaurants);

                    // Récupération des propositions (avec détails)
                    $propositions = getPropositions($_SESSION['user']['equipe'], $_GET['date'], true);

                    // Récupération des utilisateurs qui font bande à part
                    $solos = getSolos($_SESSION['user']['equipe'], $_GET['date']);

                    // Récupération des choix utilisateur
                    $mesChoix = getMyChoices($_SESSION['user'], $_GET['date']);

                    // Détermination si bande à part
                    $isSolo = getSolo($_SESSION['user'], $_GET['date']);

                    // Détermination si restaurant réservé
                    $isReserved = getReserved($_SESSION['user']['equipe'], $_GET['date']);

                    // Récupération du résumé de la semaine
                    $choixSemaine = getWeekChoices($_SESSION['user']['equipe'], $joursSemaine);

                    // Détermination des actions possibles
                    $actions = getActions($propositions, $mesChoix, $isSolo, $isReserved, $_SESSION['user']['identifiant'], $_GET['date']);

                    // Récupération des utilisateurs n'ayant pas voté (à partir du jour courant)
                    if ($_GET['date'] >= date('Ymd') AND (!empty($propositions) OR !empty($solos)))
                        $sansPropositions = getNoPropositions($_SESSION['user']['equipe'], $_GET['date']);
                }
            }
            break;

        case 'doDeterminer':
            // Vérification date du jour sélectionné
            $erreurDetermination = controlDateDetermination($_POST['date']);

            // Redirection si la date n'est pas la date du jour
            if ($erreurDetermination == true)
                header('location: foodadvisor.php?date=' . $_POST['date'] . '&action=goConsulter');
            else
            {
                // Récupération des propositions (sans détails)
                $propositions = getPropositions($_SESSION['user']['equipe'], $_POST['date'], false);

                // Récupération de l'Id du restaurant déterminé
                $idRestaurant = getRestaurantDetermined($propositions, $_POST['date']);

                // Détermination si bande à part
                $isSolo = getSolo($_SESSION['user'], $_POST['date']);

                // Détermination si restaurant réservé
                $isReserved = getReserved($_SESSION['user']['equipe'], $_POST['date']);

                // Lancement de la détermination
                if ((!isset($_SESSION['alerts']['week_end_determination']) OR $_SESSION['alerts']['week_end_determination'] != true)
                AND (!isset($_SESSION['alerts']['determination_date'])     OR $_SESSION['alerts']['determination_date']     != true)
                AND (!isset($_SESSION['alerts']['determination_time'])     OR $_SESSION['alerts']['determination_time']     != true)
                AND  $isSolo != true AND empty($isReserved))
                {
                    // Récupération des appelants possibles
                    $appelant = getCallers($idRestaurant, $_SESSION['user']['equipe'], $_POST['date']);

                    // Lancement de la détermination
                    setDetermination($propositions, $idRestaurant, $appelant, $_SESSION['user']['equipe'], $_POST['date']);
                }
            }
            break;

        case 'doSolo':
            // Récupération des choix utilisateur
            $mesChoix = getMyChoices($_SESSION['user'], $_POST['date']);

            // Détermination si bande à part
            $isSolo = getSolo($_SESSION['user'], $_POST['date']);

            // Insertion bande à part
            setSolo($mesChoix, $isSolo, $_SESSION['user'], $_POST['date']);
            break;

        case 'doSupprimerSolo':
            // Suppression bande à part
            deleteSolo($_SESSION['user'], $_POST['date']);
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
            $isSolo = getSolo($_SESSION['user'], $_POST['date']);

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
            deleteAllChoices($_SESSION['user'], $_POST['date']);
            break;

        case 'doChoixRapide':
            // Détermination si bande à part
            $isSolo = getSolo($_SESSION['user'], $_POST['date']);

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
            header('location: foodadvisor.php?date=' . date('Ymd') . '&action=goConsulter');
            break;
    }

    // Traitements de sécurité avant la vue
    switch ($_GET['action'])
    {
        case 'goConsulter':
            foreach ($joursSemaine as &$jourSemaine)
            {
                $jourSemaine['date']   = htmlspecialchars($jourSemaine['date']);
                $jourSemaine['web']    = htmlspecialchars($jourSemaine['web']);
                $jourSemaine['mobile'] = htmlspecialchars($jourSemaine['mobile']);
            }

            unset($jourSemaine);

            foreach ($listeLieuxDisponibles as &$lieu)
            {
                $lieu = htmlspecialchars($lieu);
            }

            unset($lieu);

            foreach ($listeRestaurantsResume as &$restaurantsParLieuxResume)
            {
                foreach ($restaurantsParLieuxResume as &$restaurant)
                {
                    $restaurant = Restaurant::secureData($restaurant);
                }

                unset($restaurant);
            }

            unset($restaurantsParLieuxResume);

            foreach ($listeRestaurants as &$restaurantsParLieux)
            {
                foreach ($restaurantsParLieux as &$restaurant)
                {
                    $restaurant = Restaurant::secureData($restaurant);
                }

                unset($restaurant);
            }

            unset($restaurantsParLieux);

            foreach ($listeLieux as &$lieu)
            {
                $lieu = htmlspecialchars($lieu);
            }

            unset($lieu);

            foreach ($propositions as &$proposition)
            {
                $proposition = Proposition::secureData($proposition);
            }
           
            unset($proposition);

            foreach ($solos as &$solo)
            {
                $solo = Profile::secureData($solo);
            }

            unset($solo);

            foreach ($mesChoix as &$monChoix)
            {
                $monChoix = Choix::secureData($monChoix);
            }

            unset($monChoix);

            foreach ($choixSemaine as &$choixJour)
            {
                $choixJour['date'] = htmlspecialchars($choixJour['date']);
                $choixJour['jour'] = htmlspecialchars($choixJour['jour']);

                if (!empty($choixJour['choix']))
                    $choixJour['choix'] = Proposition::secureData($choixJour['choix']);
            }

            unset($choixJour);

            if (!empty($sansPropositions))
            {
                foreach ($sansPropositions as &$userNoChoice)
                {
                    $userNoChoice = Profile::secureData($userNoChoice);
                }

                unset($userNoChoice);
            }

            // Conversion JSON
            $listeLieuxResumeJson       = json_encode($listeLieuxDisponibles);
            $listeRestaurantsResumeJson = json_encode(convertForJsonListeRestaurantsParLieu($listeRestaurantsResume));
            $listeLieuxJson             = json_encode($listeLieux);
            $listeRestaurantsJson       = json_encode(convertForJsonListeRestaurantsParLieu($listeRestaurants));
            $detailsPropositionsJson    = json_encode(convertForJsonListePropositions($propositions));
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
            header('location: foodadvisor.php?date=' . $_POST['date'] . '&action=goConsulter');
            break;

        case 'goConsulter':
        default:
            include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_foodadvisor.php');
            break;
    }
?>