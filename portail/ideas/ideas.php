<?php
  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données : "module métier"
  include_once('modele/metier_ideas.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
      switch ($_GET['view'])
      {
        case 'all':
        case 'done':
        case 'mine':
        case 'inprogress':
          $listeIdeas = getIdeas($_GET['view']);
          break;

        default:
          header('location: ideas.php?view=all&action=goConsulter');
          break;
      }
      break;

    case 'doInserer':
      // Insertion des données par le modèle
      $new_id = insertIdea($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doChangerStatut':
      // Mise à jour des données par le modèle
      $view = updateIdea($_GET['id'], $_GET['view'], $_POST);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: ideas.php?view=' . $_GET['view'] . '&action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      foreach ($listeIdeas as &$idea)
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

      unset($idea);
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
      header('location: ideas.php?view=' . $view . '&action=goConsulter&anchor=' . $_GET['id']);
      break;

    case 'doInserer':
      header('location: ideas.php?view=inprogress&action=goConsulter&anchor=' . $new_id);
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_ideas.php');
      break;
  }
?>
