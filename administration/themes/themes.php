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

  // Appels métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Initialisation de la sauvegarde en session
      initializeSaveSession();

      // Récupération des thèmes utilisateurs
      $themesUsers = getThemes('U');

      // Récupération des thèmes de missions
      $themesMissions = getThemes('M');
      break;

		case 'doAjouter':
      // Ajout d'un nouveau thème
      $idTheme = insertTheme($_POST, $_FILES);
			break;

    case 'doModifier':
      // Mise à jour d'un thème
      $idTheme = updateTheme($_POST);
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
      foreach ($themesUsers as $themeUsers)
      {
        Theme::secureData($themeUsers);
      }

      foreach ($themesMissions as $themeMission)
      {
        Theme::secureData($themeMission);
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
      if (!empty($idTheme))
        header ('location: themes.php?action=goConsulter&anchorTheme=' . $idTheme);
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
