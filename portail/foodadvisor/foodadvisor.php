<?php
  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données : "module métier"
  include_once('modele/metier_foodadvisor.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture des données par le modèle
      $listeLieux       = getLieux();
      $listeRestaurants = getRestaurants($listeLieux);

      // Conversion JSON
      $listeLieuxJson       = json_encode($listeLieux);
      $listeRestaurantsJson = json_encode(convertForJson($listeRestaurants));

      // Propositions, choix et semaine
      $propositions = getPropositions();
      $mesChoix     = getMyChoices($_SESSION['user']['identifiant']);
      $choixSemaine = getWeekChoices();
      break;

    case 'doDeterminer':
      $propositions = getPropositions();
      $idRestaurant = getRestaurantDetermined($propositions);

      if ((!isset($_SESSION['alerts']['week_end_determination']) OR $_SESSION['alerts']['week_end_determination'] != true)
      AND (!isset($_SESSION['alerts']['heure_determination'])    OR $_SESSION['alerts']['heure_determination']    != true))
      {
        $appelant = getCallers($idRestaurant);
        setDetermination($propositions, $idRestaurant, $appelant);
      }
      break;

    case 'doAjouter':
      insertChoices($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doSupprimer':
      deleteChoice($_GET['delete_id']);
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
        $proposition->setCaller(htmlspecialchars($proposition->getCaller()));
        $proposition->setPseudo(htmlspecialchars($proposition->getPseudo()));
        $proposition->setAvatar(htmlspecialchars($proposition->getAvatar()));
        $proposition->setPhone(htmlspecialchars($proposition->getPhone()));
      }

      unset($proposition);

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
          $choixJour->setCaller(htmlspecialchars($choixJour->getCaller()));
          $choixJour->setPseudo(htmlspecialchars($choixJour->getPseudo()));
          $choixJour->setAvatar(htmlspecialchars($choixJour->getAvatar()));
          $choixJour->setPhone(htmlspecialchars($choixJour->getPhone()));
        }
      }

      unset($choixJour);
      break;

    case 'doDeterminer':
    case 'doAjouter':
    case 'doSupprimer':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doDeterminer':
    case 'doAjouter':
    case 'doSupprimer':
      header('location: foodadvisor.php?action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_foodadvisor.php');
      break;
  }
?>
