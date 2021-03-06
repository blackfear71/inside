<?php
  /******************************
  *** Les enfants ! À table ! ***
  *******************************
  Fonctionnalités :
  - Consultation restaurants
  - Ajout restaurants
  - Modification restaurants
  - Suppression restaurants
  ******************************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/physique_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');
  include_once('../../includes/functions/fonctions_regex.php');
  include_once('../../includes/functions/fonctions_images.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_foodadvisor_commun.php');
  include_once('modele/metier_restaurants.php');
  include_once('modele/controles_foodadvisor_commun.php');
  include_once('modele/controles_restaurants.php');
  include_once('modele/physique_foodadvisor_commun.php');
  include_once('modele/physique_restaurants.php');

  // Appels métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Initialisation de la sauvegarde en session
      initializeSaveSession();

      // Récupération des lieux
      $listeLieux = getLieux($_SESSION['user']['equipe']);

      // Récupération des types de restaurants
      $listeTypes = getTypesRestaurants($_SESSION['user']['equipe']);

      // Récupération de la liste des restaurants
      $listeRestaurants = getListeRestaurants($listeLieux, $_SESSION['user']['equipe']);

      // Détermination si bande à part
      $isSolo = getSolo($_SESSION['user']);

      // Récupération des tops d'actions rapides
      $choixRapide = getFastActions($isSolo);

      // Lecture des choix utilisateurs
      if ($choixRapide == true)
        $mesChoix = getMyChoices($_SESSION['user']);
      break;

    case 'doAjouter':
      // Insertion d'un nouveau restaurant
      $idRestaurant = insertRestaurant($_POST, $_FILES, $_SESSION['user']);
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
      $isSolo = getSolo($_SESSION['user']);

      // Insertion d'un choix rapide
      $idRestaurant = insertFastChoice($_POST, $isSolo, $_SESSION['user']);
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
        foreach ($restaurantsParLieux as $restaurant)
        {
          Restaurant::secureData($restaurant);
        }
      }

      if ($choixRapide == true)
      {
        foreach ($mesChoix as $monChoix)
        {
          Choix::secureData($monChoix);
        }
      }

      // Conversion JSON
      $listeRestaurantsJson = json_encode(convertForJsonListeRestaurants($listeRestaurants));

      if ($choixRapide == true)
        $mesChoixJson = json_encode(convertForJsonMesChoix($mesChoix));
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
    case 'doChoixRapide':
      header('location: restaurants.php?action=goConsulter&anchor=' . $idRestaurant);
      break;

    case 'doSupprimer':
      header('location: restaurants.php?action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_restaurants.php');
      break;
  }
?>
