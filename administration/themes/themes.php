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
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données
  include_once('modele/metier_themes.php');
  include_once('modele/controles_themes.php');
  include_once('modele/physique_themes.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Initialisation de la sauvegarde en session
      initializeSaveSession();

      // Récupération des thèmes utilisateurs
      $themes_users = getThemes('U');

      // Récupération des thèmes de missions
      $themes_missions = getThemes('M');
      break;

		case 'doAjouter':
      // Ajout d'un nouveau thème
      $id_theme = insertTheme($_POST, $_FILES);
			break;

    case 'doModifier':
      // Mise à jour d'un thème
      $id_theme = updateTheme($_POST);
			break;

		case 'doSupprimer':
      // Suppression d'un thème
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
    case 'doModifier':
      if (!empty($id_theme))
        header ('location: themes.php?action=goConsulter&anchorTheme=' . $id_theme);
      else
        header ('location: themes.php?action=goConsulter');
      break;

		case 'doSupprimer':
			header ('location: themes.php?action=goConsulter');
			break;

    case 'goConsulter':
    default:
      include_once('vue/vue_themes.php');
      break;
  }
?>
