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
      if (!empty($themesUsers))
      {
        foreach ($themesUsers as &$themeUsers)
        {
          $themeUsers->setReference(htmlspecialchars($themeUsers->getReference()));
          $themeUsers->setName(htmlspecialchars($themeUsers->getName()));
          $themeUsers->setType(htmlspecialchars($themeUsers->getType()));
          $themeUsers->setLevel(htmlspecialchars($themeUsers->getLevel()));
          $themeUsers->setLogo(htmlspecialchars($themeUsers->getLogo()));
          $themeUsers->setDate_deb(htmlspecialchars($themeUsers->getDate_deb()));
          $themeUsers->setDate_fin(htmlspecialchars($themeUsers->getDate_fin()));
        }

        unset($themeUsers);
      }

      if (!empty($themesMissions))
      {
        foreach ($themesMissions as &$themeMission)
        {
          $themeMission->setReference(htmlspecialchars($themeMission->getReference()));
          $themeMission->setName(htmlspecialchars($themeMission->getName()));
          $themeMission->setType(htmlspecialchars($themeMission->getType()));
          $themeMission->setLevel(htmlspecialchars($themeMission->getLevel()));
          $themeMission->setLogo(htmlspecialchars($themeMission->getLogo()));
          $themeMission->setDate_deb(htmlspecialchars($themeMission->getDate_deb()));
          $themeMission->setDate_fin(htmlspecialchars($themeMission->getDate_fin()));
        }

        unset($themeMission);
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
