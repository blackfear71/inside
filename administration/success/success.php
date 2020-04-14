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
	include_once('../../includes/functions/fonctions_communes.php');
  include_once('../../includes/functions/fonctions_regex.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données : "module métier"
	include_once('modele/metier_success.php');
	include_once('modele/controles_success.php');
	include_once('modele/physique_success.php');

  // Initialisation sauvegarde saisie succès
  if ((!isset($_SESSION['alerts']['already_referenced']) OR $_SESSION['alerts']['already_referenced'] != true)
  AND (!isset($_SESSION['alerts']['level_not_numeric'])  OR $_SESSION['alerts']['level_not_numeric']  != true)
  AND (!isset($_SESSION['alerts']['order_not_numeric'])  OR $_SESSION['alerts']['order_not_numeric']  != true)
  AND (!isset($_SESSION['alerts']['already_ordered'])    OR $_SESSION['alerts']['already_ordered']    != true)
  AND (!isset($_SESSION['alerts']['limit_not_numeric'])  OR $_SESSION['alerts']['limit_not_numeric']  != true)
	AND (!isset($_SESSION['alerts']['file_too_big'])       OR $_SESSION['alerts']['file_too_big']       != true)
	AND (!isset($_SESSION['alerts']['temp_not_found'])     OR $_SESSION['alerts']['temp_not_found']     != true)
	AND (!isset($_SESSION['alerts']['wrong_file_type'])    OR $_SESSION['alerts']['wrong_file_type']    != true)
	AND (!isset($_SESSION['alerts']['wrong_file'])         OR $_SESSION['alerts']['wrong_file']         != true))
  {
		unset($_SESSION['save']);

    $_SESSION['save']['reference_success']   = "";
		$_SESSION['save']['level']               = "";
    $_SESSION['save']['unicity']             = "";
		$_SESSION['save']['order_success']       = "";
    $_SESSION['save']['title_success']       = "";
    $_SESSION['save']['description_success'] = "";
    $_SESSION['save']['limit_success']       = "";
		$_SESSION['save']['explanation_success'] = "";
  }

  // Appel métier
  switch ($_GET['action'])
  {
    case "goConsulter":
      // Récupération de la liste des succès
			$listeSuccess = getSuccess();
      break;

    case "goModifier":
      // Récupération de la liste des succès
      $listeSuccess = getSuccess();

			// Sauvegarde des données saisies en cas d'erreur
			if (!isset($_GET['error']) OR $_GET['error'] != true)
		    $_SESSION['save']['save_success'] = NULL;
			else
        $listeSuccess = initModErrSucces($listeSuccess, $_SESSION['save']['save_success']);
      break;

    case "doAjouter":
			// Ajout d'un nouveau succès
      insertSuccess($_POST, $_FILES);
      break;

    case "doSupprimer":
			// Suppression d'un succès
      deleteSuccess($_POST);
      break;

    case "doModifier":
			// Mise à jour de tous les succès
      $erreurUpdateSuccess = updateSuccess($_POST);
      break;

		case "doInitialiser":
			// Récupération de la liste des utilisateurs
			$listeUsers   = getUsers();

			// Récupération de la liste des succès
			$listeSuccess = getSuccess();

			// Réinitialisation des succès
			initializeSuccess($listeSuccess, $listeUsers);
			break;

		case "doPurger":
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

    case "doAjouter":
    case "doSupprimer":
    case "doModifier":
		case "doInitialiser":
		case "doPurger":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case "doModifier":
			if ($erreurUpdateSuccess == true)
        header('location: success.php?error=true&action=goModifier');
      else
        header('location: success.php?action=goConsulter');
      break;

    case "doAjouter":
    case "doSupprimer":
		case "doInitialiser":
		case "doPurger":
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
