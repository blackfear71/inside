<?php
  // Fonctions communes
	include_once('../includes/fonctions_communes.php');
  include_once('../includes/fonctions_regex.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données : "module métier"
  include_once('modele/metier_administration.php');

  // Initialisation sauvegarde saisie succès
  if ((!isset($_SESSION['alerts']['already_referenced']) OR $_SESSION['alerts']['already_referenced'] != true)
  AND (!isset($_SESSION['alerts']['level_not_numeric'])  OR $_SESSION['alerts']['level_not_numeric']  != true)
  AND (!isset($_SESSION['alerts']['order_not_numeric'])  OR $_SESSION['alerts']['order_not_numeric']  != true)
  AND (!isset($_SESSION['alerts']['already_ordered'])    OR $_SESSION['alerts']['already_ordered']    != true)
  AND (!isset($_SESSION['alerts']['limit_not_numeric'])  OR $_SESSION['alerts']['limit_not_numeric']  != true))
  {
    $_SESSION['reference_success']   = "";
    $_SESSION['level']               = "";
    $_SESSION['order_success']       = "";
    $_SESSION['title_success']       = "";
    $_SESSION['description_success'] = "";
    $_SESSION['limit_success']       = "";
		$_SESSION['explanation_success'] = "";
  }

  if (!isset($_SESSION['erreur_succes']) OR $_SESSION['erreur_succes'] != true)
  {
    $_SESSION['save_success'] = NULL;
  }

  // Appel métier
  switch ($_GET['action'])
  {
    case "goConsulter":
      // Lecture liste des données par le modèle
			$listeSuccess = getSuccess();
      break;

    case "goModifier":
      // Lecture liste des données par le modèle
      $listeSuccess = getSuccess();

      if (isset($_SESSION['erreur_succes']) AND $_SESSION['erreur_succes'] == true)
      {
        $listeSuccess = initModErrSucces($listeSuccess, $_SESSION['save_success']);
        $_SESSION['erreur_succes'] = NULL;
      }
      break;

    case "doAjouter":
      insertSuccess($_POST, $_FILES);
      break;

    case "doSupprimer":
      deleteSuccess($_GET['id']);
      break;

    case "doModifier":
      updateSuccess($_POST);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: manage_success.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      foreach ($listeSuccess as &$success)
      {
        $success->setReference(htmlspecialchars($success->getReference()));
        $success->setLevel(htmlspecialchars($success->getLevel()));
        $success->setOrder_success(htmlspecialchars($success->getOrder_success()));
        $success->setTitle(htmlspecialchars($success->getTitle()));
        $success->setDescription(htmlspecialchars($success->getDescription()));
				$success->setLimit_success(htmlspecialchars($success->getLimit_success()));
        $success->setExplanation(htmlspecialchars($success->getExplanation()));
      }

			unset($success);
      break;

    case 'goModifier':
      foreach ($listeSuccess as &$success)
      {
        $success->setReference(htmlspecialchars($success->getReference()));
        $success->setLevel(htmlspecialchars($success->getLevel()));
        $success->setOrder_success(htmlspecialchars($success->getOrder_success()));
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
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case "doModifier":
      if ($_SESSION['erreur_succes'] == true)
        header('location: manage_success.php?action=goModifier');
      else
        header('location: manage_success.php?action=goConsulter');
      break;

    case "doAjouter":
    case "doSupprimer":
      header('location: manage_success.php?action=goConsulter');
      break;

    case 'goModifier':
      include_once('vue/vue_modify_success.php');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_manage_success.php');
      break;
  }
?>
