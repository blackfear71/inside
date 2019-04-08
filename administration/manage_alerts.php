<?php
  // Fonction communes
  include_once('../includes/functions/fonctions_communes.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données : "module métier"
  include_once('modele/metier_administration.php');

  // Initialisation sauvegarde saisie alerte
  if ((!isset($_SESSION['alerts']['already_referenced']) OR $_SESSION['alerts']['already_referenced'] != true))
  {
    $_SESSION['save']['type_alert']      = "";
    $_SESSION['save']['category_alert']  = "";
    $_SESSION['save']['reference_alert'] = "";
    $_SESSION['save']['message_alert']   = "";
  }

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
      $listeAlertes = getAlerts();
      break;

    case "doAjouter":
      $new_id = insertAlert($_POST);
      break;

		case "doModifier":
			updateAlert($_POST, $_GET['update_id']);
			break;

    case "doSupprimer":
      deleteAlert($_GET['delete_id']);
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
    case "doAjouter":
      if (!empty($new_id))
        header('location: manage_alerts.php?action=goConsulter&anchorAlerts=' . $new_id);
      else
        header('location: manage_alerts.php?action=goConsulter');
      break;

    case "doModifier":
      header('location: manage_alerts.php?action=goConsulter&anchorAlerts=' . $_GET['update_id']);
      break;

    case "doSupprimer":
      header('location: manage_alerts.php?action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_manage_alerts.php');
      break;
  }
?>
