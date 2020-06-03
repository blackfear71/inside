<?php
  /******************************
  *** Les enfants ! A table ! ***
  *******************************
  Fonctionnalités :
  - Consultation restaurants
  - Ajout restaurants
  - Modification restaurants
  - Suppression restaurants
  ******************************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_foodadvisor_commun.php');
  include_once('modele/metier_restaurants.php');
  include_once('modele/controles_foodadvisor_commun.php');
  include_once('modele/controles_restaurants.php');
  include_once('modele/physique_foodadvisor_commun.php');
  include_once('modele/physique_restaurants.php');

  // Initialisation sauvegarde saisie
  if ((!isset($_SESSION['alerts']['wrong_phone_number']) OR $_SESSION['alerts']['wrong_phone_number'] != true)
  AND (!isset($_SESSION['alerts']['wrong_price_min'])    OR $_SESSION['alerts']['wrong_price_min']    != true)
  AND (!isset($_SESSION['alerts']['wrong_price_max'])    OR $_SESSION['alerts']['wrong_price_max']    != true)
  AND (!isset($_SESSION['alerts']['miss_price'])         OR $_SESSION['alerts']['miss_price']         != true)
  AND (!isset($_SESSION['alerts']['price_max_min'])      OR $_SESSION['alerts']['price_max_min']      != true)
  AND (!isset($_SESSION['alerts']['file_too_big'])       OR $_SESSION['alerts']['file_too_big']       != true)
  AND (!isset($_SESSION['alerts']['temp_not_found'])     OR $_SESSION['alerts']['temp_not_found']     != true)
  AND (!isset($_SESSION['alerts']['wrong_file_type'])    OR $_SESSION['alerts']['wrong_file_type']    != true)
  AND (!isset($_SESSION['alerts']['wrong_file'])         OR $_SESSION['alerts']['wrong_file']         != true))
  {
    unset($_SESSION['save']);

    $_SESSION['save']['name_restaurant']         = "";
    $_SESSION['save']['prix_min']                = "";
    $_SESSION['save']['prix_max']                = "";
    $_SESSION['save']['phone_restaurant']        = "";
    $_SESSION['save']['types_restaurants']       = "";
    $_SESSION['save']['description_restaurant']  = "";
    $_SESSION['save']['location']                = "";
    $_SESSION['save']['ouverture_restaurant']    = "";
    $_SESSION['save']['website_restaurant']      = "";
    $_SESSION['save']['plan_restaurant']         = "";
    $_SESSION['save']['lafourchette_restaurant'] = "";
    $_SESSION['save']['saisie_other_location']   = "";
  }

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Récupération des lieux
      $listeLieux = getLieux();

      // Récupération des types de restaurants
      $listeTypes = getTypesRestaurants();

      // Récupération de la liste des restaurants
      $listeRestaurants = getRestaurants($listeLieux);

      // Détermination si bande à part
      $isSolo = getSolo($_SESSION['user']['identifiant']);

      // Récupération des tops d'actions rapides
      $choixRapide = getFastActions($_SESSION['user']['identifiant'], $isSolo);

      // Lecture des choix utilisateurs
      if ($choixRapide == true)
        $mesChoix = getMyChoices($_SESSION['user']['identifiant']);
      break;

    case 'doAjouter':
      // Insertion d'un nouveau restaurant
      $idRestaurant = insertRestaurant($_POST, $_FILES, $_SESSION['user']['identifiant']);
      break;

    case 'doModifier':
      // Modification d'un restaurant
      $idRestaurant = updateRestaurant($_POST, $_FILES);
      break;

    case 'doSupprimer':
      // Suppression d'un restaurant
      deleteRestaurant($_POST);
      break;

    case 'doChoixRapide':
      // Détermination si bande à part
      $isSolo = getSolo($_SESSION['user']['identifiant']);

      // Insertion d'un choix rapide
      $idRestaurant = insertFastChoice($_POST, $isSolo, $_SESSION['user']['identifiant']);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: restaurants.php?action=goConsulter');
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

      foreach ($listeTypes as &$type)
      {
        $type = htmlspecialchars($type);
      }

      unset($type);

      foreach ($listeRestaurants as $restaurantsParLieux)
      {
        foreach ($restaurantsParLieux as &$restaurant)
        {
          $restaurant->setName(htmlspecialchars($restaurant->getName()));
          $restaurant->setPicture(htmlspecialchars($restaurant->getPicture()));
          $restaurant->setTypes(htmlspecialchars($restaurant->getTypes()));
          $restaurant->setLocation(htmlspecialchars($restaurant->getLocation()));
          $restaurant->setPhone(htmlspecialchars($restaurant->getPhone()));
          $restaurant->setOpened(htmlspecialchars($restaurant->getOpened()));
          $restaurant->setMin_price(htmlspecialchars($restaurant->getMin_price()));
          $restaurant->setMax_price(htmlspecialchars($restaurant->getMax_price()));
          $restaurant->setWebsite(htmlspecialchars($restaurant->getWebsite()));
          $restaurant->setPlan(htmlspecialchars($restaurant->getPlan()));
          $restaurant->setLafourchette(htmlspecialchars($restaurant->getLafourchette()));
          $restaurant->setDescription(htmlspecialchars($restaurant->getDescription()));
        }

        unset($restaurant);

        if ($choixRapide == true)
        {
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
        }
      }
      break;

    case 'doAjouter':
    case 'doModifier':
    case 'doSupprimer':
    case 'doChoixRapide':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doAjouter':
      if (!empty($idRestaurant))
        header('location: restaurants.php?action=goConsulter&anchor=' . $idRestaurant);
      else
        header('location: restaurants.php?action=goConsulter');
      break;

    case 'doModifier':
      header('location: restaurants.php?action=goConsulter&anchor=' . $idRestaurant);
      break;

    case 'doSupprimer':
      header('location: restaurants.php?action=goConsulter');
      break;

    case 'doChoixRapide':
      header('location: restaurants.php?action=goConsulter&anchor=' . $idRestaurant);
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_restaurants.php');
      break;
  }
?>
