<?php
  /*************************
  *** Gestion des thèmes ***
  **************************
  Fonctionnalités :
  - Ajout des thèmes
  - Modification des thèmes
  - Suppression des thèmes
  *************************/

  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données : "module métier"
  include_once('modele/metier_themes.php');
  include_once('modele/controles_themes.php');
  include_once('modele/physique_themes.php');

  // Initialisation sauvegarde saisie thème
  if ((!isset($_SESSION['alerts']['date_less'])           OR $_SESSION['alerts']['date_less']           != true)
  AND (!isset($_SESSION['alerts']['date_conflict'])       OR $_SESSION['alerts']['date_conflict']       != true)
  AND (!isset($_SESSION['alerts']['wrong_date'])          OR $_SESSION['alerts']['wrong_date']          != true)
  AND (!isset($_SESSION['alerts']['already_ref_theme'])   OR $_SESSION['alerts']['already_ref_theme']   != true)
  AND (!isset($_SESSION['alerts']['missing_theme_file'])  OR $_SESSION['alerts']['missing_theme_file']  != true)
  AND (!isset($_SESSION['alerts']['file_too_big'])        OR $_SESSION['alerts']['file_too_big']        != true)
  AND (!isset($_SESSION['alerts']['temp_not_found'])      OR $_SESSION['alerts']['temp_not_found']      != true)
  AND (!isset($_SESSION['alerts']['wrong_file_type'])     OR $_SESSION['alerts']['wrong_file_type']     != true)
  AND (!isset($_SESSION['alerts']['wrong_file'])          OR $_SESSION['alerts']['wrong_file']          != true)
  AND (!isset($_SESSION['alerts']['level_theme_numeric']) OR $_SESSION['alerts']['level_theme_numeric'] != true))
  {
    unset($_SESSION['save']);

    $_SESSION['save']['theme_title']    = "";
    $_SESSION['save']['theme_ref']      = "";
    $_SESSION['save']['theme_level']    = "";
    $_SESSION['save']['theme_date_deb'] = "";
    $_SESSION['save']['theme_date_fin'] = "";
  }

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
      $themes_users    = getThemes("U");
      $themes_missions = getThemes("M");
      break;

		case "doAjouter":
      $id_theme = insertTheme($_POST, $_FILES);
			break;

    case "doModifier":
      $id_theme = updateTheme($_POST);
			break;

		case "doSupprimer":
      deleteTheme($_POST);
			break;

    default:
      // Contrôle action renseignée URL
      header('location: themes.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      if (!empty($themes_users))
      {
        foreach ($themes_users as &$theme_users)
        {
          $theme_users->setReference(htmlspecialchars($theme_users->getReference()));
          $theme_users->setName(htmlspecialchars($theme_users->getName()));
          $theme_users->setType(htmlspecialchars($theme_users->getType()));
          $theme_users->setLevel(htmlspecialchars($theme_users->getLevel()));
          $theme_users->setLogo(htmlspecialchars($theme_users->getLogo()));
          $theme_users->setDate_deb(htmlspecialchars($theme_users->getDate_deb()));
          $theme_users->setDate_fin(htmlspecialchars($theme_users->getDate_fin()));
        }

        unset($theme_users);
      }

      if (!empty($themes_missions))
      {
        foreach ($themes_missions as &$theme_mission)
        {
          $theme_mission->setReference(htmlspecialchars($theme_mission->getReference()));
          $theme_mission->setName(htmlspecialchars($theme_mission->getName()));
          $theme_mission->setType(htmlspecialchars($theme_mission->getType()));
          $theme_mission->setLevel(htmlspecialchars($theme_mission->getLevel()));
          $theme_mission->setLogo(htmlspecialchars($theme_mission->getLogo()));
          $theme_mission->setDate_deb(htmlspecialchars($theme_mission->getDate_deb()));
          $theme_mission->setDate_fin(htmlspecialchars($theme_mission->getDate_fin()));
        }

        unset($theme_mission);
      }
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
      if (!empty($id_theme))
        header ('location: themes.php?action=goConsulter&anchorTheme=' . $id_theme);
      else
        header ('location: themes.php?action=goConsulter');
      break;

		case "doSupprimer":
			header ('location: themes.php?action=goConsulter');
			break;

    case 'goConsulter':
    default:
      include_once('vue/vue_themes.php');
      break;
  }
?>
