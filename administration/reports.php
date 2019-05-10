<?php
  // Fonction communes
  include_once('../includes/functions/fonctions_communes.php');
  include_once('../includes/functions/fonctions_dates.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données : "module métier"
  include_once('modele/metier_administration.php');

  // Appel métier
  switch ($_GET['action'])
  {
    // Lecture liste des données par le modèle
    case 'goConsulter':
      switch ($_GET['view'])
      {
        case 'all':
        case 'resolved':
        case 'unresolved':
          $listeBugs = getBugs($_GET['view']);
          break;

        default:
          header('location: reports.php?view=all&action=goConsulter');
          break;
      }
      break;

		case "doChangerStatut":
			// Mise à jour des données par le modèle
			$resolved = updateBug($_GET['id'], $_POST);
			break;

    case "doSupprimer":
      deleteBug($_GET['id']);
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
				$bug->setName_a(htmlspecialchars($bug->getName_a()));
				$bug->setContent(htmlspecialchars($bug->getContent()));
				$bug->getType(htmlspecialchars($bug->getType()));
				$bug->getResolved(htmlspecialchars($bug->getResolved()));
			}

      unset($bug);
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
      if ($resolved == "Y")
        header('location: reports.php?view=resolved&action=goConsulter&anchor=' . $_GET['id']);
      else
        header('location: reports.php?view=unresolved&action=goConsulter&anchor=' . $_GET['id']);
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
