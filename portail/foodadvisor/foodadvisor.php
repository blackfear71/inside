<?php
  /**********************************
  ***** Les enfants ! À table ! *****
  ***********************************
  Fonctionnalités :
  - Consultation propositions
  - Bande à part
  - Utilisateurs en attente
  - Ajout de propositions
  - Ajout de propositions sur mobile
  - Modification de propositions
  - Suppression de propositions
  - Consultation détails propositions
  - Détermination choix
  - Réservation choix
  - Choix complet
  - Modification jour sans choix
  **********************************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_foodadvisor_commun.php');
  include_once('modele/metier_foodadvisor.php');
  include_once('modele/controles_foodadvisor_commun.php');
  include_once('modele/controles_foodadvisor.php');
  include_once('modele/physique_foodadvisor_commun.php');
  include_once('modele/physique_foodadvisor.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Récupération de tous les lieux
      $listeLieuxDisponibles = getLieux();

      // Récupération et filtrage de la liste des restaurants (ouverts)
      $listeRestaurants = getListeRestaurantsOuverts($listeLieuxDisponibles);

      // Récupération de tous les restaurants (existants)
      $listeRestaurantsResume = getListeRestaurants($listeLieuxDisponibles);

      // Filtrage de la liste des lieux (restaurants ouverts)
      $listeLieux = getLieuxFiltres($listeRestaurants);

      // Récupération des propositions (avec détails)
      $propositions = getPropositions(true);

      // Récupération des utilisateurs qui font bande à part
      $solos = getSolos();

      // Récupération des choix utilisateur
      $mesChoix = getMyChoices($_SESSION['user']['identifiant']);

      // Détermination si bande à part
      $isSolo = getSolo($_SESSION['user']['identifiant']);

      // Détermination si restaurant réservé
      $isReserved = getReserved();

      // Récupération du résumé de la semaine
      $choixSemaine = getWeekChoices();

      // Détermination des actions possibles
      $actions = getActions($propositions, $mesChoix, $isSolo, $isReserved, $_SESSION['user']['identifiant']);

      // Récupération des utilisateurs n'ayant pas voté
      if (!empty($propositions) OR !empty($solos))
        $sansPropositions = getNoPropositions();
      break;

    case 'doDeterminer':
      // Récupération des propositions (sans détails)
      $propositions = getPropositions(false);

      // Récupération de l'Id du restaurant déterminé
      $idRestaurant = getRestaurantDetermined($propositions);

      // Détermination si bande à part
      $isSolo = getSolo($_SESSION['user']['identifiant']);

      // Détermination si restaurant réservé
      $isReserved = getReserved();

      // Lancement de la détermination
      if ((!isset($_SESSION['alerts']['week_end_determination']) OR $_SESSION['alerts']['week_end_determination'] != true)
      AND (!isset($_SESSION['alerts']['heure_determination'])    OR $_SESSION['alerts']['heure_determination']    != true)
      AND  $isSolo != true AND empty($isReserved))
      {
        // Récupération des appelants possibles
        $appelant = getCallers($idRestaurant);

        // Lancement de la détermination
        setDetermination($propositions, $idRestaurant, $appelant);
      }
      break;

    case 'doSolo':
      // Récupération des choix utilisateur
      $mesChoix = getMyChoices($_SESSION['user']['identifiant']);

      // Détermination si bande à part
      $isSolo = getSolo($_SESSION['user']['identifiant']);

      // Insertion bande à part
      setSolo($mesChoix, $isSolo, $_SESSION['user']['identifiant']);
      break;

    case 'doSupprimerSolo':
      // Suppression bande à part
      deleteSolo($_SESSION['user']['identifiant']);
      break;

    case 'doReserver':
      // Insertion réservation
      insertReservation($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doAnnulerReserver':
      // Suppression réservation
      deleteReservation($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doComplet':
      // Insertion restaurant complet
      completeChoice($_POST);
      break;

    case 'doAjouter':
      // Détermination si bande à part
      $isSolo = getSolo($_SESSION['user']['identifiant']);

      // Insertion choix
      insertChoices($_POST, $isSolo, $_SESSION['user']['identifiant']);
      break;

    case 'doAjouterMobile':
      // Détermination si bande à part
      $isSolo = getSolo($_SESSION['user']['identifiant']);

      // Insertion choix (mobile)
      insertChoicesMobile($_POST, $isSolo, $_SESSION['user']['identifiant']);
      break;

    case 'doModifier':
      // Modification d'un choix
      updateChoice($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doSupprimer':
      // Suppression d'un choix
      deleteChoice($_POST);
      break;

    case 'doSupprimerChoix':
      // Suppression de tous les choix
      deleteAllChoices($_SESSION['user']['identifiant']);
      break;

    case 'doChoixRapide':
      // Détermination si bande à part
      $isSolo = getSolo($_SESSION['user']['identifiant']);

      // Insertion choix rapide
      insertFastChoice($_POST, $isSolo, $_SESSION['user']['identifiant']);
      break;

    case 'doAjouterResume':
      // Insertion choix résumé de la semaine
      insertResume($_POST);
      break;

    case 'doSupprimerResume':
      // Suppression choix résumé de la semaine
      deleteResume($_POST);
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

      foreach ($listeRestaurants as $restaurantsParLieux)
      {
        foreach ($restaurantsParLieux as $restaurant)
        {
          Restaurant::secureData($restaurant);
        }
      }

      foreach ($listeRestaurantsResume as $restaurantsParLieuxResume)
      {
        foreach ($restaurantsParLieuxResume as $restaurant)
        {
          Restaurant::secureData($restaurant);
        }
      }

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
    case 'doAjouterMobile':
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
    case 'doAjouterMobile':
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
