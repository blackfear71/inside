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
  include_once('../../includes/functions/fonctions_communes.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données : "module métier"
  include_once('modele/metier_collector.php');

  // Initialisation sauvegarde saisie
  if (!isset($_SESSION['alerts']['wrong_date']) OR $_SESSION['alerts']['wrong_date'] != true)
  {
    unset($_SESSION['save']);

    $_SESSION['save']['speaker']        = "";
    $_SESSION['save']['other_speaker']  = "";
    $_SESSION['save']['date_collector'] = "";
    $_SESSION['save']['type_collector'] = "";
    $_SESSION['save']['collector']      = "";
    $_SESSION['save']['context']        = "";
  }

  // Appel métier
  switch ($_GET['action'])
  {
    case "goConsulter":
      // Contrôle si la page renseignée et numérique, si le tri et le filtre sont présents
      if (!isset($_GET['page'])   OR !is_numeric($_GET['page'])
      OR  !isset($_GET['sort'])   OR  empty($_GET['sort'])
      OR  !isset($_GET['filter']) OR  empty($_GET['filter']))
        header('location: collector.php?action=goConsulter&page=1&sort=dateDesc&filter=none');
      else
      {
        // Lecture des données par le modèle
        $listeUsers = getUsers();
        $nbPages    = getPages($_GET['filter'], $_SESSION['user']['identifiant']);

        if ($nbPages > 0)
        {
          if ($_GET['page'] > $nbPages)
            header('location: collector.php?action=goConsulter&page=' . $nbPages . '&sort=' . $_GET['sort'] . '&filter=' . $_GET['filter']);
          elseif ($_GET['page'] < 1)
            header('location: collector.php?action=goConsulter&page=1&sort=' . $_GET['sort'] . '&filter=' . $_GET['filter']);
          else
          {
            $listeCollectors = getCollectors($listeUsers, $nbPages, $_GET['page'], $_SESSION['user']['identifiant'], $_GET['sort'], $_GET['filter']);
            $listeVotesUsers = getVotesUser($listeCollectors, $_SESSION['user']['identifiant']);
            $listeVotes      = getVotes($listeCollectors);
          }
        }
      }
      break;

    case "doAjouter":
      $id_col = insertCollector($_POST, $_FILES, $_SESSION['user']['identifiant']);

      if (!empty($id_col))
        $num_page = numPageCollector($id_col);
      break;

    case "doSupprimer":
      deleteVotes($_POST);
      deleteCollector($_POST);
      break;

    case "doModifier":
      $id_col = updateCollector($_POST, $_FILES);
      break;

    case "doVoter":
      $id_col = voteCollector($_POST, $_SESSION['user']['identifiant']);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: collector.php?action=goConsulter&page=1&sort=dateDesc&filter=none');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case "goConsulter":
      if ($nbPages > 0)
      {
        foreach ($listeUsers as &$user)
        {
          $user->setIdentifiant(htmlspecialchars($user->getIdentifiant()));
          $user->setPseudo(htmlspecialchars($user->getPseudo()));
          $user->setAvatar(htmlspecialchars($user->getAvatar()));
        }

        unset($user);

        foreach ($listeCollectors as &$collector)
        {
          $collector->setAuthor(htmlspecialchars($collector->getAuthor()));
          $collector->setPseudo_a(htmlspecialchars($collector->getPseudo_a()));
          $collector->setSpeaker(htmlspecialchars($collector->getSpeaker()));
          $collector->setPseudo_s(htmlspecialchars($collector->getPseudo_s()));
          $collector->setType_s(htmlspecialchars($collector->getType_s()));
          $collector->setDate_collector(htmlspecialchars($collector->getDate_collector()));
          $collector->setType_collector(htmlspecialchars($collector->getType_collector()));
          $collector->setCollector(htmlspecialchars($collector->getCollector()));
          $collector->setContext(htmlspecialchars($collector->getContext()));
          $collector->setDate_add(htmlspecialchars($collector->getDate_add()));
        }

        unset($collector);

        foreach ($listeVotesUsers as &$vote)
        {
          $vote->setId_collector(htmlspecialchars($vote->getId_collector()));
          $vote->setIdentifiant(htmlspecialchars($vote->getIdentifiant()));
          $vote->setVote(htmlspecialchars($vote->getVote()));
        }

        unset($vote);
      }
      break;

    case "doAjouter":
    case "doSupprimer":
    case "doModifier":
    case "doVoter":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case "doAjouter":
      if (!empty($id_col) AND !empty($num_page))
        header('location: collector.php?action=goConsulter&page=' . $num_page . '&sort=dateDesc&filter=none&anchor=' . $id_col);
      else
        header('location: collector.php?action=goConsulter&page=' . $_GET['page'] . '&sort=dateDesc&filter=none');
      break;

    case "doSupprimer":
      header('location: collector.php?action=goConsulter&page=' . $_GET['page'] . '&sort=dateDesc&filter=none');
      break;

    case "doModifier":
      header('location: collector.php?action=goConsulter&page=' . $_GET['page'] . '&sort=' . $_GET['sort'] . '&filter=' . $_GET['filter'] . '&anchor=' . $id_col);
      break;

    case "doVoter":
      header('location: collector.php?action=goConsulter&page=' . $_GET['page'] . '&sort=' . $_GET['sort'] . '&filter=' . $_GET['filter'] . '&anchor=' . $id_col);
      break;

    case "goConsulter":
    default:
      include_once('vue/vue_collector.php');
      break;
  }
?>
