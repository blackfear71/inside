<?php
  // Contrôles communs Administrateur
  include_once('../includes/controls_admin.php');

  // Modèle de données : "module métier"
  include_once('modele/metier_administration.php');

  // Initialisation sauvegarde saisie succès

  if ((!isset($_SESSION['already_referenced']) OR $_SESSION['already_referenced'] != true)
  AND (!isset($_SESSION['order_not_numeric'])  OR $_SESSION['order_not_numeric'] != true)
  AND (!isset($_SESSION['already_ordered'])    OR $_SESSION['already_ordered'] != true)
  AND (!isset($_SESSION['limit_not_numeric'])  OR $_SESSION['limit_not_numeric'] != true))
  {
    $_SESSION['reference_success']   = "";
    $_SESSION['order_success']       = "";
    $_SESSION['title_success']       = "";
    $_SESSION['description_success'] = "";
    $_SESSION['limit_success']       = "";
  }
  
  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
			$listeSuccess = getSuccess();
      break;

    case "doAjouter":
      insertSuccess($_POST, $_FILES);
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
      foreach ($listeSuccess as $success)
      {
        $success->setReference(htmlspecialchars($success->getReference()));
        $success->setOrder_success(htmlspecialchars($success->getOrder_success()));
        $success->setTitle(htmlspecialchars($success->getTitle()));
        $success->setDescription(htmlspecialchars($success->getDescription()));
        $success->setLimit_success(htmlspecialchars($success->getLimit_success()));
      }
      break;

    case "doAjouter":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case "doAjouter":
      header('location: manage_success.php?action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_manage_success.php');
      break;
  }
?>
