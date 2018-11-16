<?php
  // Fonction communes
  include_once('../includes/functions/fonctions_communes.php');
  include_once('../includes/functions/fonctions_dates.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données : "module métier"
  include_once('modele/metier_administration.php');

  // Initialisation sauvegarde saisie thème
  if ((!isset($_SESSION['alerts']['date_less'])          OR $_SESSION['alerts']['date_less']          != true)
  AND (!isset($_SESSION['alerts']['date_conflict'])      OR $_SESSION['alerts']['date_conflict']      != true)
  AND (!isset($_SESSION['alerts']['wrong_date'])         OR $_SESSION['alerts']['wrong_date']         != true)
  AND (!isset($_SESSION['alerts']['already_ref_theme'])  OR $_SESSION['alerts']['already_ref_theme']  != true)
  AND (!isset($_SESSION['alerts']['missing_theme_file']) OR $_SESSION['alerts']['missing_theme_file'] != true)
  AND (!isset($_SESSION['alerts']['wrong_file'])         OR $_SESSION['alerts']['wrong_file']         != true))
  {
    $_SESSION['save']['theme_title']    = "";
    $_SESSION['save']['theme_ref']      = "";
    $_SESSION['save']['theme_date_deb'] = "";
    $_SESSION['save']['theme_date_fin'] = "";
  }

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
      $themes = getThemes();
      break;

		case "doAjouter":
      $new_id = insertTheme($_POST, $_FILES);
			break;

    case "doModifier":
      updateTheme($_POST, $_GET['update_id']);
      $new_id = $_GET['update_id'];
			break;

		case "doSupprimer":
      deleteTheme($_GET['delete_id']);
			break;

    default:
      // Contrôle action renseignée URL
      header('location: manage_themes.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      foreach ($themes as &$theme)
      {
        $theme->setReference(htmlspecialchars($theme->getReference()));
        $theme->setName(htmlspecialchars($theme->getName()));
        $theme->setLogo(htmlspecialchars($theme->getLogo()));
        $theme->setDate_deb(htmlspecialchars($theme->getDate_deb()));
        $theme->setDate_fin(htmlspecialchars($theme->getDate_fin()));
      }

      unset($theme);
      break;

    case "doAjouter":
		case "doModifier":
		case "doSupprimer":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case "doAjouter":
    case "doModifier":
      if (!empty($new_id))
        header ('location: manage_themes.php?action=goConsulter&anchorTheme=' . $new_id);
      else
        header ('location: manage_themes.php?action=goConsulter');
      break;

		case "doSupprimer":
			header ('location: manage_themes.php?action=goConsulter');
			break;

    case 'goConsulter':
    default:
      include_once('vue/vue_manage_themes.php');
      break;
  }
?>
