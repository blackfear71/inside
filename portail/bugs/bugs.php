<?php
  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données : "module métier"
  include_once('modele/metier_bugs.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
      switch ($_GET['view'])
      {
        case 'submit':
          // Pas de traitement
          break;

        case 'resolved':
        case 'unresolved':
          $listeBugs = getBugs($_GET['view']);
          break;

        default:
          header('location: bugs.php?view=submit&action=goConsulter');
          break;
      }
      break;

    case 'doSignaler':
      // Insertion des données par le modèle
      $new_id = insertBug($_POST);
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
      if ($_GET['view'] != 'submit')
      {
        foreach ($listeBugs as &$bug)
        {
          $bug->setSubject(htmlspecialchars($bug->getSubject()));
          $bug->setDate(htmlspecialchars($bug->getDate()));
          $bug->setAuthor(htmlspecialchars($bug->getAuthor()));
          $bug->setName_a(htmlspecialchars($bug->getName_a()));
          $bug->setContent(htmlspecialchars($bug->getContent()));
          $bug->getType(htmlspecialchars($bug->getType()));
          $bug->getResolved(htmlspecialchars($bug->getResolved()));
        }

        unset($bug);
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
      header('location: bugs.php?view=unresolved&action=goConsulter&anchor=' . $new_id);
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_bugs.php');
      break;
  }
?>
