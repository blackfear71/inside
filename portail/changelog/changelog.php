<?php
  /********************************
  *** Journal des modifications ***
  *********************************
  Fonctionnalités :
  - Consultation des modifications
  ********************************/

  // Fonctions communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_changelog.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Contrôle si la page et année renseignées et numériques
      if (!isset($_GET['year']) OR !is_numeric($_GET['year']))
        header('location: changelog.php?year=' . date('Y') . '&action=goConsulter');
      else
      {
        // Journaux
        $anneeExistante = controlYear($_GET['year']);
        $ongletsYears   = getOnglets();
        $categories     = getCategories();
        $listeLogs      = getLogs($_GET['year'], $categories);
      }
      break;

    case 'goConsulterHistoire':
      $ongletsYears = getOnglets();
      break;

    default:
      // Contrôle action renseignée URL
      header('location: changelog.php?year=' . date('Y') . '&action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      foreach ($ongletsYears as &$onglet)
      {
        $onglet = htmlspecialchars($onglet);
      }

      unset($onglet);

      foreach ($categories as &$categorie)
      {
        $categorie = htmlspecialchars($categorie);
      }

      unset($categorie);

      foreach ($listeLogs as $log)
      {
        ChangeLog::secureData($log);
      }
      break;

    case 'goConsulterHistoire':
      foreach ($ongletsYears as &$onglet)
      {
        $onglet = htmlspecialchars($onglet);
      }

      unset($onglet);
      break;

    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'goConsulterHistoire':
    case 'goConsulter':
    default:
      include_once('vue/vue_changelog.php');
      break;
  }
?>
