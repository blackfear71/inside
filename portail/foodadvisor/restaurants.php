<?php
  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Initialisation sauvegarde saisie
  if (!isset($_SESSION['alerts']['wrong_phone_number']) OR $_SESSION['alerts']['wrong_phone_number'] != true)
  {
    $_SESSION['save']['name_restaurant']        = "";
    $_SESSION['save']['phone_restaurant']       = "";
    $_SESSION['save']['types_restaurants']      = "";
    $_SESSION['save']['description_restaurant'] = "";
    $_SESSION['save']['location']               = "";
    $_SESSION['save']['website_restaurant']     = "";
    $_SESSION['save']['plan_restaurant']        = "";
    $_SESSION['save']['saisie_other_location']  = "";
  }

  // Modèle de données : "module métier"
  include_once('modele/metier_foodadvisor.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture des données par le modèle
      $listeLieux       = getLieux();
      $listeTypes       = getTypesRestaurants();
      $listeRestaurants = getRestaurants($listeLieux);
      break;

    case 'doAjouter':
      $new_id = insertRestaurant($_POST, $_FILES, $_SESSION['user']['identifiant']);
      break;

    case 'doModifier':
      updateRestaurant($_POST, $_FILES, $_GET['update_id']);
      break;

    case 'doSupprimer':
      deleteRestaurant($_GET['delete_id']);
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
          $restaurant->setWebsite(htmlspecialchars($restaurant->getWebsite()));
          $restaurant->setPlan(htmlspecialchars($restaurant->getPlan()));
          $restaurant->setDescription(htmlspecialchars($restaurant->getDescription()));
        }

        unset($restaurant);
      }
      break;

    case 'doAjouter':
    case 'doModifier':
    case 'doSupprimer':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doAjouter':
      if (!empty($new_id))
        header('location: restaurants.php?action=goConsulter&anchor=' . $new_id);
      else
        header('location: restaurants.php?action=goConsulter');
      break;

    case 'doModifier':
      header('location: restaurants.php?action=goConsulter&anchor=' . $_GET['update_id']);
      break;

    case 'doSupprimer':
      header('location: restaurants.php?action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_restaurants.php');
      break;
  }
?>
