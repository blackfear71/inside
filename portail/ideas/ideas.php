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
      // Contrôle si la page renseignée et numérique
      if (!isset($_GET['page']) OR !is_numeric($_GET['page']))
        header('location: ideas.php?view=all&action=goConsulter&page=1');
      else
      {
        // Lecture liste des données par le modèle
        switch ($_GET['view'])
        {
          case 'all':
          case 'done':
          case 'mine':
          case 'inprogress':
            $nbPages = getPages($_GET['view'], $_SESSION['user']['identifiant']);

            if ($nbPages > 0)
              $listeIdeas = getIdeas($_GET['view'], $_GET['page'], $nbPages);
            break;

          default:
            header('location: ideas.php?view=all&action=goConsulter&page=1');
            break;
        }
      }
      break;

    case 'doInserer':
      // Insertion des données par le modèle
      $new_id = insertIdea($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doChangerStatut':
      // Mise à jour des données par le modèle
      $view     = updateIdea($_GET['id'], $_GET['view'], $_POST);
      $num_page = numPageIdea($_GET['id'], $view);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: ideas.php?view=' . $_GET['view'] . '&action=goConsulter&page=1');
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
        $idea->setPseudo_a(htmlspecialchars($idea->getPseudo_a()));
        $idea->setAvatar_a(htmlspecialchars($idea->getAvatar_a()));
        $idea->setContent(htmlspecialchars($idea->getContent()));
        $idea->setStatus(htmlspecialchars($idea->getStatus()));
        $idea->setDevelopper(htmlspecialchars($idea->getDevelopper()));
        $idea->setPseudo_d(htmlspecialchars($idea->getPseudo_d()));
        $idea->setAvatar_d(htmlspecialchars($idea->getAvatar_d()));
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
      header('location: ideas.php?view=' . $view . '&action=goConsulter&page=' . $num_page . '&anchor=' . $_GET['id']);
      break;

    case 'doInserer':
      header('location: ideas.php?view=inprogress&action=goConsulter&page=1&anchor=' . $new_id);
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_ideas.php');
      break;
  }
?>
