<?php
  /*********************************
  ********* Collector Room *********
  **********************************
  Fonctionnalités :
  - Consultation des phrases cultes
  - Ajout des phrases cultes
  - Modification des phrases cultes
  - Suppression des phrases cultes
  - Consultation des images cultes
  - Ajout des images cultes
  - Modification des images cultes
  - Suppression des images cultes
  - Filtrage
  *********************************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/physique_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');
  include_once('../../includes/functions/fonctions_regex.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_collector.php');
  include_once('modele/controles_collector.php');
  include_once('modele/physique_collector.php');

  // Appels métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Contrôle si la page renseignée et numérique, si le tri et le filtre sont présents
      if (!isset($_GET['page'])   OR empty($_GET['page'])   OR !is_numeric($_GET['page'])
      OR  !isset($_GET['sort'])   OR empty($_GET['sort'])
      OR  !isset($_GET['filter']) OR empty($_GET['filter']))
        header('location: collector.php?action=goConsulter&page=1&sort=dateDesc&filter=none');
      else
      {
        // Initialisation de la sauvegarde en session
        initializeSaveSession();

        // Récupération de la liste des utilisateurs
        $listeUsers = getUsers($_SESSION['user']['equipe']);

        // Calcul du minimum de smileys pour être culte (75%)
        $minGolden = getMinGolden($listeUsers, $_SESSION['user']['equipe']);

        // Récupération des tris et des filtres
        $ordersAndFilters = getOrdersAndFilters();

        // Récupération de la pagination
        $nombrePages = getPages($_GET['filter'], $_SESSION['user'], $minGolden);

        // Récupération de la liste des phrases cultes ou redirection
        if ($nombrePages > 0)
        {
          if ($_GET['page'] > $nombrePages)
            header('location: collector.php?action=goConsulter&page=' . $nombrePages . '&sort=' . $_GET['sort'] . '&filter=' . $_GET['filter']);
          elseif ($_GET['page'] < 1)
            header('location: collector.php?action=goConsulter&page=1&sort=' . $_GET['sort'] . '&filter=' . $_GET['filter']);
          else
            $listeCollectors = getCollectors($listeUsers, $nombrePages, $minGolden, $_GET['page'], $_SESSION['user'], $_GET['sort'], $_GET['filter']);
        }
      }
      break;

    case 'doAjouter':
      // Insertion d'une phrase / image culte
      $idCollector = insertCollector($_POST, $_FILES, $_SESSION['user'], false);

      // Récupération du numéro de page
      if (!empty($idCollector))
        $numeroPage = numeroPageCollector($idCollector, $_SESSION['user']['equipe']);
      break;

    case 'doAjouterMobile':
      // Insertion d'une phrase / image culte
      $idCollector = insertCollector($_POST, $_FILES, $_SESSION['user'], true);

      // Récupération du numéro de page
      if (!empty($idCollector))
        $numeroPage = numeroPageCollector($idCollector, $_SESSION['user']['equipe']);
      break;

    case 'doSupprimer':
      // Suppression d'une phrase / image culte
      deleteCollector($_POST);
      break;

    case 'doModifier':
      // Modification d'une phrase / image culte
      $idCollector = updateCollector($_POST, $_FILES, false);

      // Récupération du numéro de page
      $numeroPage = numeroPageCollector($idCollector, $_SESSION['user']['equipe']);
      break;

    case 'doModifierMobile':
      // Modification d'une phrase / image culte
      $idCollector = updateCollector($_POST, $_FILES, true);

      // Récupération du numéro de page
      $numeroPage = numeroPageCollector($idCollector, $_SESSION['user']['equipe']);
      break;

    case 'doVoter':
      // Vote d'un utilisateur
      $idCollector = voteCollector($_POST, $_SESSION['user']);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: collector.php?action=goConsulter&page=1&sort=dateDesc&filter=none');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      foreach ($listeUsers as &$user)
      {
        $user['pseudo'] = htmlspecialchars($user['pseudo']);
        $user['avatar'] = htmlspecialchars($user['avatar']);
      }

      unset($user);

      foreach ($ordersAndFilters as &$orderAndFilter)
      {
        foreach ($orderAndFilter as &$orderAndFilterValue)
        {
          $orderAndFilterValue['label'] = htmlspecialchars($orderAndFilterValue['label']);
          $orderAndFilterValue['value'] = htmlspecialchars($orderAndFilterValue['value']);
        }

        unset($orderAndFilterValue);
      }

      unset($orderAndFilter);

      if ($nombrePages > 0)
      {
        foreach ($listeCollectors as $collector)
        {
          Collector::secureData($collector);
        }

        // Conversion JSON
        $listeCollectorsJson = json_encode(convertForJsonListeCollectors($listeCollectors));
      }

      // Conversion JSON
      $equipeJson     = json_encode($_SESSION['user']['equipe']);
      $listeUsersJson = json_encode($listeUsers);
      break;

    case 'doAjouter':
    case 'doAjouterMobile':
    case 'doSupprimer':
    case 'doModifier':
    case 'doModifierMobile':
    case 'doVoter':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doAjouter':
    case 'doAjouterMobile':
      if (!empty($idCollector) AND !empty($numeroPage))
        header('location: collector.php?action=goConsulter&page=' . $numeroPage . '&sort=dateDesc&filter=none&anchor=' . $idCollector);
      else
        header('location: collector.php?action=goConsulter&page=' . $_GET['page'] . '&sort=dateDesc&filter=none');
      break;

    case 'doModifier':
    case 'doModifierMobile':
      header('location: collector.php?action=goConsulter&page=' . $numeroPage . '&sort=' . $_GET['sort'] . '&filter=' . $_GET['filter'] . '&anchor=' . $idCollector);
      break;

    case 'doSupprimer':
      header('location: collector.php?action=goConsulter&page=' . $_GET['page'] . '&sort=dateDesc&filter=none');
      break;

    case 'doVoter':
      header('location: collector.php?action=goConsulter&page=' . $_GET['page'] . '&sort=' . $_GET['sort'] . '&filter=' . $_GET['filter'] . '&anchor=' . $idCollector);
      break;

    case 'goConsulter':
    default:
      include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_collector.php');
      break;
  }
?>
