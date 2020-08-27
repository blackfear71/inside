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
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_collector.php');

  // Appel métier
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
        $listeUsers = getUsers();

        // Calcul du minimum de smileys pour être culte (75%)
        $minGolden = getMinGolden($listeUsers);

        // Récupération de la pagination
        $nbPages = getPages($_GET['filter'], $_SESSION['user']['identifiant'], $minGolden);

        // Récupération de la liste des phrases cultes ou redirection
        if ($nbPages > 0)
        {
          if ($_GET['page'] > $nbPages)
            header('location: collector.php?action=goConsulter&page=' . $nbPages . '&sort=' . $_GET['sort'] . '&filter=' . $_GET['filter']);
          elseif ($_GET['page'] < 1)
            header('location: collector.php?action=goConsulter&page=1&sort=' . $_GET['sort'] . '&filter=' . $_GET['filter']);
          else
            $listeCollectors = getCollectors($listeUsers, $nbPages, $minGolden, $_GET['page'], $_SESSION['user']['identifiant'], $_GET['sort'], $_GET['filter']);
        }
      }
      break;

    case 'doAjouter':
      // Insertion d'une phrase culte
      $idCollector = insertCollector($_POST, $_FILES, $_SESSION['user']['identifiant']);

      // Récupération du numéro de page
      if (!empty($idCollector))
        $numeroPage = numeroPageCollector($idCollector);
      break;

    case 'doSupprimer':
      // Suppression des votes d'une phrase culte
      deleteVotes($_POST);

      // SUppression d'une phrase culte
      deleteCollector($_POST);
      break;

    case 'doModifier':
      // Modification d'une phrase culte
      $idCollector = updateCollector($_POST, $_FILES);
      break;

    case 'doVoter':
      // Vote d'un utilisateur
      $idCollector = voteCollector($_POST, $_SESSION['user']['identifiant']);
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

      if ($nbPages > 0)
      {
        foreach ($listeCollectors as $collector)
        {
          Collector::secureData($collector);
        }
      }
      break;

    case 'doAjouter':
    case 'doSupprimer':
    case 'doModifier':
    case 'doVoter':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doAjouter':
      if (!empty($idCollector) AND !empty($numeroPage))
        header('location: collector.php?action=goConsulter&page=' . $numeroPage . '&sort=dateDesc&filter=none&anchor=' . $idCollector);
      else
        header('location: collector.php?action=goConsulter&page=' . $_GET['page'] . '&sort=dateDesc&filter=none');
      break;

    case 'doSupprimer':
      header('location: collector.php?action=goConsulter&page=' . $_GET['page'] . '&sort=dateDesc&filter=none');
      break;

    case 'doModifier':
    case 'doVoter':
      header('location: collector.php?action=goConsulter&page=' . $_GET['page'] . '&sort=' . $_GET['sort'] . '&filter=' . $_GET['filter'] . '&anchor=' . $idCollector);
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_collector.php');
      break;
  }
?>
