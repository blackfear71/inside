<?php
  /**********************************
  ***** Les enfants ! A table ! *****
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

      // Récupération de tous les restaurants (ouverts)
      $listeRestaurantsOuverts = getListeRestaurantsOuverts($listeLieuxDisponibles);

      // Récupération de tous les restaurants (existants)
      $listeRestaurantsResume = getListeRestaurants($listeLieuxDisponibles);

      // Filtrage de la liste des restaurants (ouverts)
      $listeRestaurants = getListeRestaurantsFiltres($listeRestaurantsOuverts);

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

      foreach ($listeRestaurantsResume as $restaurantsParLieuxResume)
      {
        foreach ($restaurantsParLieuxResume as &$restaurant)
        {
          $restaurant->setName(htmlspecialchars($restaurant->getName()));
          $restaurant->setPicture(htmlspecialchars($restaurant->getPicture()));
          $restaurant->setTypes(htmlspecialchars($restaurant->getTypes()));
          $restaurant->setLocation(htmlspecialchars($restaurant->getLocation()));
          $restaurant->setPhone(htmlspecialchars($restaurant->getPhone()));
          $restaurant->setWebsite(htmlspecialchars($restaurant->getWebsite()));
          $restaurant->setPlan(htmlspecialchars($restaurant->getPlan()));
          $restaurant->setLafourchette(htmlspecialchars($restaurant->getLafourchette()));
          $restaurant->setDescription(htmlspecialchars($restaurant->getDescription()));
        }

        unset($restaurant);
      }

      foreach ($listeLieux as &$lieu)
      {
        $lieu = htmlspecialchars($lieu);
      }

      unset($lieu);

      foreach ($listeRestaurants as $restaurantsParLieux)
      {
        foreach ($restaurantsParLieux as &$restaurant)
        {
          $restaurant->setName(htmlspecialchars($restaurant->getName()));
          $restaurant->setPicture(htmlspecialchars($restaurant->getPicture()));
          $restaurant->setTypes(htmlspecialchars($restaurant->getTypes()));
          $restaurant->setLocation(htmlspecialchars($restaurant->getLocation()));
          $restaurant->setPhone(htmlspecialchars($restaurant->getPhone()));
          $restaurant->setWebsite(htmlspecialchars($restaurant->getWebsite()));
          $restaurant->setPlan(htmlspecialchars($restaurant->getPlan()));
          $restaurant->setLafourchette(htmlspecialchars($restaurant->getLafourchette()));
          $restaurant->setDescription(htmlspecialchars($restaurant->getDescription()));
        }

        unset($restaurant);
      }

      foreach ($propositions as &$proposition)
      {
        $proposition->setId_restaurant(htmlspecialchars($proposition->getId_restaurant()));
        $proposition->setName(htmlspecialchars($proposition->getName()));
        $proposition->setPicture(htmlspecialchars($proposition->getPicture()));
        $proposition->setLocation(htmlspecialchars($proposition->getLocation()));
        $proposition->setNb_participants(htmlspecialchars($proposition->getNb_participants()));
        $proposition->setClassement(htmlspecialchars($proposition->getClassement()));
        $proposition->setDetermined(htmlspecialchars($proposition->getDetermined()));
        $proposition->setDate(htmlspecialchars($proposition->getDate()));
        $proposition->setCaller(htmlspecialchars($proposition->getCaller()));
        $proposition->setPseudo(htmlspecialchars($proposition->getPseudo()));
        $proposition->setAvatar(htmlspecialchars($proposition->getAvatar()));
        $proposition->setReserved(htmlspecialchars($proposition->getReserved()));
        $proposition->setTypes(htmlspecialchars($proposition->getTypes()));
        $proposition->setPhone(htmlspecialchars($proposition->getPhone()));
        $proposition->setWebsite(htmlspecialchars($proposition->getWebsite()));
        $proposition->setPlan(htmlspecialchars($proposition->getPlan()));
        $proposition->setLafourchette(htmlspecialchars($proposition->getLafourchette()));
        $proposition->setOpened(htmlspecialchars($proposition->getOpened()));
        $proposition->setMin_price(htmlspecialchars($proposition->getMin_price()));
        $proposition->setMax_price(htmlspecialchars($proposition->getMax_price()));
        $proposition->setDescription(htmlspecialchars($proposition->getDescription()));

        if (!empty($proposition->getDetails()))
        {
          foreach ($proposition->getDetails() as &$detailsUser)
          {
            $detailsUser['identifiant'] = htmlspecialchars($detailsUser['identifiant']);
            $detailsUser['pseudo']      = htmlspecialchars($detailsUser['pseudo']);
            $detailsUser['avatar']      = htmlspecialchars($detailsUser['avatar']);
            $detailsUser['transports']  = htmlspecialchars($detailsUser['transports']);
            $detailsUser['horaire']     = htmlspecialchars($detailsUser['horaire']);
            $detailsUser['menu']        = htmlspecialchars($detailsUser['menu']);
          }

          unset($detailsUser);
        }
      }

      unset($proposition);

      foreach ($solos as &$solo)
      {
        $solo->setIdentifiant(htmlspecialchars($solo->getIdentifiant()));
        $solo->setPseudo(htmlspecialchars($solo->getPseudo()));
        $solo->setAvatar(htmlspecialchars($solo->getAvatar()));
      }

      unset($solo);

      foreach ($mesChoix as &$monChoix)
      {
        $monChoix->setId_restaurant(htmlspecialchars($monChoix->getId_restaurant()));
        $monChoix->setIdentifiant(htmlspecialchars($monChoix->getIdentifiant()));
        $monChoix->setDate(htmlspecialchars($monChoix->getDate()));
        $monChoix->setTime(htmlspecialchars($monChoix->getTime()));
        $monChoix->setTransports(htmlspecialchars($monChoix->getTransports()));
        $monChoix->setMenu(htmlspecialchars($monChoix->getMenu()));
        $monChoix->setName(htmlspecialchars($monChoix->getName()));
        $monChoix->setPicture(htmlspecialchars($monChoix->getPicture()));
        $monChoix->setLocation(htmlspecialchars($monChoix->getLocation()));
        $monChoix->setOpened(htmlspecialchars($monChoix->getOpened()));
      }

      unset($monChoix);

      foreach ($choixSemaine as $key => &$choixJour)
      {
        if (!empty($choixJour))
        {
          $choixJour->setId_restaurant(htmlspecialchars($choixJour->getId_restaurant()));
          $choixJour->setName(htmlspecialchars($choixJour->getName()));
          $choixJour->setPicture(htmlspecialchars($choixJour->getPicture()));
          $choixJour->setLocation(htmlspecialchars($choixJour->getLocation()));
          $choixJour->setNb_participants(htmlspecialchars($choixJour->getNb_participants()));
          $choixJour->setClassement(htmlspecialchars($choixJour->getClassement()));
          $choixJour->setDetermined(htmlspecialchars($choixJour->getDetermined()));
          $choixJour->setDate(htmlspecialchars($choixJour->getDate()));
          $choixJour->setCaller(htmlspecialchars($choixJour->getCaller()));
          $choixJour->setPseudo(htmlspecialchars($choixJour->getPseudo()));
          $choixJour->setAvatar(htmlspecialchars($choixJour->getAvatar()));
          $choixJour->setReserved(htmlspecialchars($choixJour->getReserved()));
          $choixJour->setPhone(htmlspecialchars($choixJour->getPhone()));
        }
      }

      unset($choixJour);

      if (!empty($sansPropositions))
      {
        foreach ($sansPropositions as &$userNoChoice)
        {
          $userNoChoice->setIdentifiant(htmlspecialchars($userNoChoice->getIdentifiant()));
          $userNoChoice->setPseudo(htmlspecialchars($userNoChoice->getPseudo()));
          $userNoChoice->setAvatar(htmlspecialchars($userNoChoice->getAvatar()));
        }

        unset($userNoChoice);
      }

      // Conversion JSON
      $listeLieuxResumeJson       = json_encode($listeLieuxDisponibles);
      $listeRestaurantsResumeJson = json_encode(convertForJsonListeRestaurants($listeRestaurantsResume));
      $listeLieuxJson             = json_encode($listeLieux);
      $listeRestaurantsJson       = json_encode(convertForJsonListeRestaurants($listeRestaurants));
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
      if ($_SESSION['index']['plateforme'] == 'mobile')
        include_once('vue/vue_foodadvisor_mobile.php');
      else
        include_once('vue/vue_foodadvisor.php');
      break;
  }
?>
