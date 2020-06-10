<?php
  /**************************
  *** Gestion des alertes ***
  ***************************
  Fonctionnalités :
  - Ajout d'alertes
  - Modification d'alertes
  - Suppression d'alertes
  **************************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données
  include_once('modele/metier_alerts.php');
  include_once('modele/controles_alerts.php');
  include_once('modele/physique_alerts.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Initialisation de la sauvegarde en session
      initializeSaveSession();

      // Récupération de la liste des alertes
      $listeAlertes = getAlerts();
      break;

    case 'doAjouter':
      // Insertion d'une nouvelle alerte
      $idAlerte = insertAlert($_POST);
      break;

		case 'doModifier':
      // Mise à jour d'une alerte
			$idAlerte = updateAlert($_POST);
			break;

    case 'doSupprimer':
      // Suppression d'une alerte
      deleteAlert($_POST);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: alerts.php?action=goConsulter');
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

    case 'doAjouter':
    case 'doModifier':
		case 'doSupprimer':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doAjouter':
      if (!empty($idAlerte))
        header('location: alerts.php?action=goConsulter&anchorAlerts=' . $idAlerte);
      else
        header('location: alerts.php?action=goConsulter');
      break;

    case 'doModifier':
      header('location: alerts.php?action=goConsulter&anchorAlerts=' . $idAlerte);
      break;

    case 'doSupprimer':
      header('location: alerts.php?action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_alerts.php');
      break;
  }
?>
