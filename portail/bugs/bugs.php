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
  include_once('../../includes/functions/physique_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_bugs.php');
  include_once('modele/physique_bugs.php');

  // Appels métier
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
          // Récupération de la liste des utilisateurs
          $listeUsers = getListeUsers($_SESSION['user']['equipe']);

          // Récupération de la liste des bugs
          $listeBugs = getBugs($_GET['view'], 'B', $_SESSION['user']['equipe'], $listeUsers);

          // Récupération de la liste des évolutions
          $listeEvolutions = getBugs($_GET['view'], 'E', $_SESSION['user']['equipe'], $listeUsers);
          break;

        default:
          // Contrôle vue renseignée URL
          header('location: bugs.php?view=unresolved&action=goConsulter');
          break;
      }
      break;

    case 'doSignaler':
      // Insertion d'un bug / d'une évolution
      $idRapport = insertBug($_POST, $_FILES, $_SESSION['user']);
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
      foreach ($listeUsers as &$user)
      {
        $user['pseudo'] = htmlspecialchars($user['pseudo']);
        $user['avatar'] = htmlspecialchars($user['avatar']);
      }

      unset($user);

      foreach ($listeBugs as $bug)
      {
        BugEvolution::secureData($bug);
      }

      foreach ($listeEvolutions as $evolution)
      {
        BugEvolution::secureData($evolution);
      }
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
      include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_bugs.php');
      break;
  }
?>
