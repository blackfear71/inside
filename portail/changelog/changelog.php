<?php
  /********************************
  *** Journal des modifications ***
  *********************************
  Fonctionnalités :
  - Consultation des modifications
  ********************************/

  // Fonctions communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/physique_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_changelog.php');
  include_once('modele/physique_changelog.php');

  // Appels métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Contrôle si la page et année renseignées et numériques
      if (!isset($_GET['year']) OR !is_numeric($_GET['year']))
        header('location: changelog.php?year=' . date('Y') . '&action=goConsulter');
      else
      {
        // Vérification année existante
        $anneeExistante = controlYear($_GET['year']);

        // Récupération des onglets (années)
        $onglets = getOnglets();

        // Récupération des catégories
        $categories = getCategories();

        // Récupération des journaux
        $listeLogs = getLogs($_GET['year'], $categories);
      }
      break;

    case 'goConsulterHistoire':
      // Récupération des onglets (années)
      $onglets = getOnglets();

      // Récupération de l'histoire du site
      $histoires = getHistory();
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
      foreach ($onglets as &$year)
      {
        $year = htmlspecialchars($year);
      }

      unset($year);

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
      foreach ($onglets as &$year)
      {
        $year = htmlspecialchars($year);
      }

      unset($year);

      foreach ($histoires as &$histoire)
      {
        foreach ($histoire as &$contenu)
        {
          $contenu = htmlspecialchars($contenu);
        }

        unset($contenu);
      }

      unset($histoire);
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
      include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_changelog.php');
      break;
  }
?>
