<?php
  // Contrôles communs Utilisateurs
  include_once('../../includes/controls_users.php');

  // Fonctions communes
  include('../../includes/fonctions_dates.php');

  // Modèle de données : "module métier"
  include_once('modele/metier_ideas.php');

  // Contrôle vue renseignée URL
  switch ($_GET['view'])
  {
    case 'all':
    case 'done':
    case 'mine':
    case 'inprogress':
      break;

    default:
      header('location: ideas.php?view=all&action=goConsulter');
  }

  // Contrôle action renseignée URL
  switch ($_GET['action'])
  {
    case 'doChangerStatut':
    case 'doInserer':
    case 'goConsulter':
      break;

    default:
      header('location: ideas.php?view=' . $_GET['view'] . '&action=goConsulter');
  }

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
      $listeIdeas = readIdeas($_GET['view']);
      break;

    case 'doInserer':
      // Insertion des données par le modèle
      insertIdea($_POST);
      break;

    case 'doChangerStatut':
      // Mise à jour des données par le modèle
      updateIdea($_GET['id'], $_POST);
      break;

    default:
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      foreach ($listeIdeas as $idea)
      {
        $idea->setSubject(htmlspecialchars($idea->getSubject()));
        $idea->setDate(htmlspecialchars($idea->getDate()));
        $idea->setAuthor(htmlspecialchars($idea->getAuthor()));
        $idea->setName_a(htmlspecialchars($idea->getName_a()));
        $idea->setContent(htmlspecialchars($idea->getContent()));
        $idea->setStatus(htmlspecialchars($idea->getStatus()));
        $idea->setDevelopper(htmlspecialchars($idea->getDevelopper()));
        $idea->setName_d(htmlspecialchars($idea->getName_d()));
      }
      break;

    case 'doInserer':
    case 'doChangerStatut':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doChangerStatut':
      header('location: ideas.php?view=' . $_GET['view'] . '&action=goConsulter#' . $_GET['id']);
      break;

    case 'doInserer':
      header('location: ideas.php?view=inprogress&action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_ideas.php');
      break;
  }
?>
