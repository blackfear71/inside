<?php
  /**********************
  ******* #TheBox *******
  ***********************
  Fonctionnalités :
  - Consulation des idées
  - Ajout d'une idée
  - Gestion d'une idée
  **********************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
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
      $id_idea = insertIdea($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doChangerStatut':
      // Mise à jour des données par le modèle
      $id_idea    = $_POST['id_idea'];
      $view       = updateIdea($_POST, $_GET['view']);
      $numeroPage = numPageIdea($id_idea, $view);
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
      if (!empty($listeIdeas))
      {
        foreach ($listeIdeas as &$idea)
        {
          $idea->setSubject(htmlspecialchars($idea->getSubject()));
          $idea->setDate(htmlspecialchars($idea->getDate()));
          $idea->setAuthor(htmlspecialchars($idea->getAuthor()));
          $idea->setPseudo_author(htmlspecialchars($idea->getPseudo_author()));
          $idea->setAvatar_author(htmlspecialchars($idea->getAvatar_author()));
          $idea->setContent(htmlspecialchars($idea->getContent()));
          $idea->setStatus(htmlspecialchars($idea->getStatus()));
          $idea->setDevelopper(htmlspecialchars($idea->getDevelopper()));
          $idea->setPseudo_developper(htmlspecialchars($idea->getPseudo_developper()));
          $idea->setAvatar_developper(htmlspecialchars($idea->getAvatar_developper()));
        }

        unset($idea);
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
      header('location: ideas.php?view=' . $view . '&action=goConsulter&page=' . $numeroPage . '&anchor=' . $id_idea);
      break;

    case 'doInserer':
      header('location: ideas.php?view=inprogress&action=goConsulter&page=1&anchor=' . $id_idea);
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_ideas.php');
      break;
  }
?>
