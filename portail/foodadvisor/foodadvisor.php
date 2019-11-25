<?php
  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données : "module métier"
  include_once('modele/metier_commun.php');
  include_once('modele/metier_foodadvisor.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture des données par le modèle
      $listeLieux       = getLieux();
      $listeRestaurants = getRestaurants($listeLieux);

      // Propositions, choix et semaine
      $propositions = getPropositions(true);
      $solos        = getSolos();
      $mesChoix     = getMyChoices($_SESSION['user']['identifiant']);
      $isSolo       = getSolo($_SESSION['user']['identifiant']);
      $isReserved   = getReserved($_SESSION['user']['identifiant']);
      $choixSemaine = getWeekChoices();
      $actions      = getActions($propositions, $mesChoix, $isSolo, $isReserved, $_SESSION['user']['identifiant']);

      if (!empty($propositions) OR !empty($solos))
        $sansPropositions = getNoPropositions();
      break;

    case 'doDeterminer':
      $propositions = getPropositions(false);
      $idRestaurant = getRestaurantDetermined($propositions);
      $isSolo       = getSolo($_SESSION['user']['identifiant']);
      $isReserved   = getReserved($_SESSION['user']['identifiant']);

      if ((!isset($_SESSION['alerts']['week_end_determination']) OR $_SESSION['alerts']['week_end_determination'] != true)
      AND (!isset($_SESSION['alerts']['heure_determination'])    OR $_SESSION['alerts']['heure_determination']    != true)
      AND  $isSolo != true AND empty($isReserved))
      {
        $appelant = getCallers($idRestaurant);
        setDetermination($propositions, $idRestaurant, $appelant);
      }
      break;

    case 'doSolo':
      $mesChoix = getMyChoices($_SESSION['user']['identifiant']);
      $isSolo   = getSolo($_SESSION['user']['identifiant']);
      setSolo($mesChoix, $isSolo, $_SESSION['user']['identifiant']);
      break;

    case 'doSupprimerSolo':
      deleteSolo($_SESSION['user']['identifiant']);
      break;

    case "doReserver":
      insertReservation($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doAnnulerReserver':
      deleteReservation($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doAjouter':
      $isSolo = getSolo($_SESSION['user']['identifiant']);
      insertChoices($_POST, $isSolo, $_SESSION['user']['identifiant']);
      break;

    case "doModifier":
      updateChoice($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doSupprimer':
      deleteChoice($_POST);
      break;

    case 'doSupprimerChoix':
      deleteAllChoices($_SESSION['user']['identifiant']);
      break;

    case 'doChoixRapide':
      $isSolo = getSolo($_SESSION['user']['identifiant']);
      insertFastChoice($_POST, $isSolo, $_SESSION['user']['identifiant']);
      break;

    case 'doAjouterResume':
      insertResume($_POST);
      break;

    case 'doSupprimerResume':
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
        $proposition->setOpened(htmlspecialchars($proposition->getOpened()));
        $proposition->setMin_price(htmlspecialchars($proposition->getMin_price()));
        $proposition->setMax_price(htmlspecialchars($proposition->getMax_price()));

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
      $listeLieuxJson       = json_encode($listeLieux);
      $listeRestaurantsJson = json_encode(convertForJson($listeRestaurants));
      $detailsPropositions  = json_encode(convertForJson2($propositions));
      break;

    case 'doDeterminer':
    case 'doSolo':
    case 'doSupprimerSolo':
    case "doReserver":
    case 'doAnnulerReserver':
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
    case "doReserver":
    case 'doAnnulerReserver':
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
      include_once('vue/vue_foodadvisor.php');
      break;
  }
?>
