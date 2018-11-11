<?php
  // Fonction communes
  include_once('../includes/functions/fonctions_communes.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données : "module métier"
  include_once('modele/metier_administration.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
      $listeAlertes = getAlerts();
      break;

    case "doAjouterAlerte":
      /*$new_id = insertAlert($_POST);*/
      break;

		case "doModifierAlerte":
			/*updateAlert($_GET['update_id']);*/
			break;

    case "doSupprimerAlerte":
      /*deleteAlert($_GET['delete_id']);*/
      break;

    default:
      // Contrôle action renseignée URL
      header('location: manage_alerts.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
			foreach ($listeAlertes as &$alerte)
			{
        $alerte->setCategory(htmlspecialchars($alerte->getCategory()));
        $alerte->setType(htmlspecialchars($alerte->getType()));
        $alerte->setAlert(htmlspecialchars($alerte->getAlert()));
				$alerte->setMessage(htmlspecialchars($alerte->getMessage()));
			}

      unset($alerte);
      break;

    case "doAjouterAlerte":
    case "doModifierAlerte":
		case "doSupprimerAlerte":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case "doAjouterAlerte":
      header('location: manage_alerts.php?action=goConsulter&anchor=' . $new_id);
      break;

    case "doModifierAlerte":
      header('location: manage_alerts.php?action=goConsulter&anchor=' . $_GET['update_id']);
      break;

    case "doSupprimerAlerte":
      header('location: manage_alerts.php?action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_manage_alerts.php');
      break;
  }
?>
