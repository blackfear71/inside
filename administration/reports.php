<?php
  // Fonction communes
  include_once('../includes/fonctions_communes.php');
  include_once('../includes/fonctions_dates.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données : "module métier"
  include_once('modele/metier_administration.php');

	// Contrôle vue renseignée URL
	switch ($_GET['view'])
	{
		case 'all':
		case 'resolved':
		case 'unresolved':
			break;

		default:
			header('location: reports.php?view=all&action=goConsulter');
			break;
	}

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
			$listeBugs = getBugs($_GET['view']);
      break;

		case "doChangerStatut":
			// Mise à jour des données par le modèle
			updateBug($_GET['id'], $_POST);
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
    case "doSupprimer":
			header('location: reports.php?view=' . $_GET['view'] . '&action=goConsulter');
			break;

    case 'goConsulter':
    default:
      include_once('vue/vue_reports.php');
      break;
  }
?>
