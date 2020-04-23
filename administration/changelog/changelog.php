<?php
  /********************************
  *** Journal des modifications ***
  *********************************
  Fonctionnalités :
  - Ajout de journal
  - Modification de journal
  - Suppression de journal
  ********************************/

  // Fonctions communes
  include_once('../../includes/functions/fonctions_communes.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données
  include_once('modele/metier_changelog.php');
  include_once('modele/controles_changelog.php');
  include_once('modele/physique_changelog.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Récupère les catégories
      $categoriesChangeLog = getCategories();

      // Initialisation de l'écran
      if (!isset($changeLogParameters) AND !isset($_SESSION['changelog']))
        $changeLogParameters = initializeChangeLog();
      else
      {
        // Récupération des paramètres saisis
        $changeLogParameters = getChangeLogParameters($_SESSION['changelog']);

        // Contrôle de l'existence d'un journal pour les paramètres saisis
        $errorChangelog = controlChangeLog($changeLogParameters);

        // Récupération des données en cas de modification ou suppression
        if ($errorChangelog == false AND ($changeLogParameters->getAction() == "M" OR $changeLogParameters->getAction() == "S"))
          $changeLog = getChangeLog($changeLogParameters, $categoriesChangeLog);
      }
      break;

    case 'doGenerer':
      // Sauvegarde des paramètres saisis en session
      saveChangeLogParameters($_POST);
      break;

    case 'doAjouter':
      // Récupère les catégories
      $categoriesChangeLog = getCategories();

      // Insertion d'un journal
      insertChangeLog($_POST, $categoriesChangeLog);
      break;

    case 'doModifier':
      // Récupère les catégories
      $categoriesChangeLog = getCategories();

      // Mise à jour d'un journal
      updateChangeLog($_POST, $categoriesChangeLog);
      break;

    case 'doSupprimer':
      // Suppression d'un journal
      deleteChangeLog($_POST);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: changelog.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      foreach ($categoriesChangeLog as &$categorie)
      {
        $categorie = htmlspecialchars($categorie);
      }

      unset($categorie);

      $changeLogParameters->setAction(htmlspecialchars($changeLogParameters->getAction()));
      $changeLogParameters->setYear(htmlspecialchars($changeLogParameters->getYear()));
      $changeLogParameters->setWeek(htmlspecialchars($changeLogParameters->getWeek()));

      if (isset($changeLog))
      {
        $changeLog->setWeek(htmlspecialchars($changeLog->getWeek()));
        $changeLog->setYear(htmlspecialchars($changeLog->getYear()));
        $changeLog->setNotes(htmlspecialchars($changeLog->getNotes()));

        foreach ($changeLog->getLogs() as &$logsCategorie)
        {
          foreach ($logsCategorie as &$logCategorie)
          {
            $logCategorie = htmlspecialchars($logCategorie);
          }

          unset($logCategorie);
        }

        unset($logCategorie);
      }

      // Conversion JSON
      $categoriesChangeLogJson = json_encode($categoriesChangeLog);
      break;

    case 'doGenerer':
    case 'doAjouter':
    case 'doModifier':
    case 'doSupprimer':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doGenerer':
    case 'doAjouter':
    case 'doModifier':
    case 'doSupprimer':
      header('location: changelog.php?action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_changelog.php');
      break;
  }
?>
