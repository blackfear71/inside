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
  include_once('modele/physique_ideas.php');

  // Appels métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Contrôle si la page renseignée et numérique
      if (!isset($_GET['page']) OR !is_numeric($_GET['page']))
        header('location: ideas.php?view=all&action=goConsulter&page=1');
      else
      {
        // Lecture des idées en fonction de la vue
        switch ($_GET['view'])
        {
          case 'all':
          case 'done':
          case 'mine':
          case 'inprogress':
            // Récupération du nombre de pages
            $nombrePages = getPages($_GET['view'], $_SESSION['user']['identifiant']);

            // Récupération des idées
            if ($nombrePages > 0)
              $listeIdees = getIdeas($_GET['view'], $_GET['page'], $nombrePages, $_SESSION['user']['identifiant']);
            break;

          default:
            // Contrôle vue renseignée URL
            header('location: ideas.php?view=all&action=goConsulter&page=1');
            break;
        }
      }
      break;

    case 'doInserer':
      // Insertion d'une idée
      $idIdee = insertIdea($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doChangerStatut':
      // Récupération de l'id de l'idée
      $idIdee = $_POST['id_idea'];

      // Modification du statut d'une idée
      $view = updateIdea($_POST, $_GET['view'], $_SESSION['user']['identifiant']);

      // Récupération du numéro de page pour la redirection
      $numeroPage = getNumeroPageIdea($idIdee, $view, $_SESSION['user']['identifiant']);
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
      if ($nombrePages > 0)
      {
        foreach ($listeIdees as $idee)
        {
          Idea::secureData($idee);
        }
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
      header('location: ideas.php?view=' . $view . '&action=goConsulter&page=' . $numeroPage . '&anchor=' . $idIdee);
      break;

    case 'doInserer':
      header('location: ideas.php?view=inprogress&action=goConsulter&page=1&anchor=' . $idIdee);
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_ideas.php');
      break;
  }
?>
