<?php
	/***************************
	**** Gestion des succès ****
	****************************
	Fonctionnalités :
	- Création des succès
	- Modification des succès
	- Suppression des succès
	- Initialisation des succès
	***************************/

  // Fonctions communes
	include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/fonctions_regex.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données
	include_once('modele/metier_success.php');
	include_once('modele/controles_success.php');
	include_once('modele/physique_success.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
			// Initialisation de la sauvegarde en session
			initializeSaveSession($_GET['action']);

      // Récupération de la liste des succès
			$listeSuccess = getSuccess();
      break;

    case 'goModifier':
      // Récupération de la liste des succès
      $listeSuccess = getSuccess();

			// Initialisation de la sauvegarde en session et récupération erreur
			$erreurSuccess = initializeSaveSession($_GET['action']);

			// Sauvegarde des données saisies en cas d'erreur
			if ($erreurSuccess == true)
        $listeSuccess = initialisationErreurModificationSucces($listeSuccess);
      break;

    case 'doAjouter':
			// Ajout d'un nouveau succès
      insertSuccess($_POST, $_FILES);
      break;

    case 'doSupprimer':
			// Suppression d'un succès
      deleteSuccess($_POST);
      break;

    case 'doModifier':
			// Mise à jour de tous les succès
      $erreurUpdateSuccess = updateSuccess($_POST);
      break;

		case 'doInitialiser':
			// Récupération de la liste des utilisateurs
			$listeUsers   = getUsers();

			// Récupération de la liste des succès
			$listeSuccess = getSuccess();

			// Réinitialisation des succès
			initializeSuccess($listeSuccess, $listeUsers);
			break;

		case 'doPurger':
			// Purge des succès
			purgeSuccess();
			break;

    default:
      // Contrôle action renseignée URL
      header('location: success.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
		case 'goModifier':
      foreach ($listeSuccess as &$success)
      {
        $success->setReference(htmlspecialchars($success->getReference()));
        $success->setLevel(htmlspecialchars($success->getLevel()));
        $success->setOrder_success(htmlspecialchars($success->getOrder_success()));
				$success->setDefined(htmlspecialchars($success->getDefined()));
				$success->setUnicity(htmlspecialchars($success->getUnicity()));
        $success->setTitle(htmlspecialchars($success->getTitle()));
        $success->setDescription(htmlspecialchars($success->getDescription()));
				$success->setLimit_success(htmlspecialchars($success->getLimit_success()));
        $success->setExplanation(htmlspecialchars($success->getExplanation()));
      }

			unset($success);
      break;

    case 'doAjouter':
    case 'doSupprimer':
    case 'doModifier':
		case 'doInitialiser':
		case 'doPurger':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doModifier':
			if ($erreurUpdateSuccess == true)
        header('location: success.php?error=true&action=goModifier');
      else
        header('location: success.php?action=goConsulter');
      break;

    case 'doAjouter':
    case 'doSupprimer':
		case 'doInitialiser':
		case 'doPurger':
			header('location: success.php?action=goConsulter');
			break;

    case 'goModifier':
      include_once('vue/vue_update_success.php');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_success.php');
      break;
  }
?>
