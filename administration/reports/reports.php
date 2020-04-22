<?php
  /*****************************
  **** Gestion des rapports ****
  ******************************
  Fonctionnalités :
  - Résolution de bug/évolution
  - Suppression de bug/évolution
  - Rejet de bug/évolution
  *****************************/

  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données
  include_once('modele/metier_reports.php');
  include_once('modele/physique_reports.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Récupération de la liste des bugs/évolutions en fonction de la vue
      switch ($_GET['view'])
      {
        case 'all':
        case 'resolved':
        case 'unresolved':
          $listeBugs       = getBugs($_GET['view'], 'B');
          $listeEvolutions = getBugs($_GET['view'], 'E');
          break;

        default:
          header('location: reports.php?view=all&action=goConsulter');
          break;
      }
      break;

		case "doChangerStatut":
			// Mise à jour d'un bug ou d'une évolution
			$resolved  = updateBug($_POST);
      $idRapport = $_POST['id_report'];
			break;

    case "doSupprimer":
      // Suppression d'un bug ou d'une évolution
      deleteBug($_POST);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: reports.php?view=all&action=goConsulter');
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

		case "doChangerStatut":
    case "doSupprimer":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
		case "doChangerStatut":
      if ($resolved == "Y" OR $resolved == "R")
        header('location: reports.php?view=resolved&action=goConsulter&anchor=' . $idRapport);
      else
        header('location: reports.php?view=unresolved&action=goConsulter&anchor=' . $idRapport);
      break;

    case "doSupprimer":
			header('location: reports.php?view=' . $_GET['view'] . '&action=goConsulter');
			break;

    case 'goConsulter':
    default:
      include_once('vue/vue_reports.php');
      break;
  }
?>
