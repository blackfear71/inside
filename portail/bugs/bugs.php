<?php
  /*********************************
  ****** Demandes d'évolution ******
  **********************************
  Fonctionnalités :
  - Consultation des bugs/évolutions
  - Ajout de bugs/évolutions
  *********************************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_bugs.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Initialisation de la sauvegarde en session
      initializeSaveSession();

      // Lecture liste des données par le modèle
      switch ($_GET['view'])
      {
        case 'resolved':
        case 'unresolved':
          $listeBugs       = getBugs($_GET['view'], 'B');
          $listeEvolutions = getBugs($_GET['view'], 'E');
          break;

        default:
          header('location: bugs.php?view=unresolved&action=goConsulter');
          break;
      }
      break;

    case 'doSignaler':
      // Insertion des données par le modèle
      $idRapport = insertBug($_POST, $_FILES, $_SESSION['user']['identifiant']);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: bugs.php?view=' . $_GET['view'] . '&action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      foreach ($listeBugs as &$bug)
      {
        $bug->setSubject(htmlspecialchars($bug->getSubject()));
        $bug->setDate(htmlspecialchars($bug->getDate()));
        $bug->setAuthor(htmlspecialchars($bug->getAuthor()));
        $bug->setPseudo(htmlspecialchars($bug->getPseudo()));
        $bug->setAvatar(htmlspecialchars($bug->getAvatar()));
        $bug->setContent(htmlspecialchars($bug->getContent()));
        $bug->setPicture(htmlspecialchars($bug->getPicture()));
        $bug->getType(htmlspecialchars($bug->getType()));
        $bug->getResolved(htmlspecialchars($bug->getResolved()));
      }

      unset($bug);

      foreach ($listeEvolutions as &$evolution)
      {
        $evolution->setSubject(htmlspecialchars($evolution->getSubject()));
        $evolution->setDate(htmlspecialchars($evolution->getDate()));
        $evolution->setAuthor(htmlspecialchars($evolution->getAuthor()));
        $evolution->setPseudo(htmlspecialchars($evolution->getPseudo()));
        $evolution->setAvatar(htmlspecialchars($evolution->getAvatar()));
        $evolution->setContent(htmlspecialchars($evolution->getContent()));
        $evolution->setPicture(htmlspecialchars($evolution->getPicture()));
        $evolution->getType(htmlspecialchars($evolution->getType()));
        $evolution->getResolved(htmlspecialchars($evolution->getResolved()));
      }

      unset($evolution);
      break;

    case 'doSignaler':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doSignaler':
      header('location: bugs.php?view=unresolved&action=goConsulter&anchor=' . $idRapport);
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_bugs.php');
      break;
  }
?>
