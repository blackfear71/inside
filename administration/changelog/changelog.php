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

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données : "module métier"
  include_once('modele/metier_changelog.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      $categoriesChangeLog = getCategories();

      // Lecture liste des données par le modèle
      if (!isset($changeLogParameters) AND !isset($_SESSION['changelog']))
        $changeLogParameters = initializeChangeLog();
      else
      {
        $changeLogParameters = getChangeLogParameters($_SESSION['changelog']);
        $error_changelog     = controlChangeLog($changeLogParameters);

        if ($error_changelog == false AND ($changeLogParameters->getAction() == "M" OR $changeLogParameters->getAction() == "S"))
          $changeLog = getChangeLog($changeLogParameters, $categoriesChangeLog);
      }
      break;

    case 'doGenerer':
      saveChangeLogParameters($_POST);
      break;

    case 'doAjouter':
      //ne pas oublier d'afficher un message d'erreur si tout est vide
      //trier les logs (filtrer les caractères @ et ; car ils servent de séparateur)
      //insérer une notification (pas de message dans les news ce n'est pas pertinent) avec logo inside
      //message de bonne fin
      //voir si le tri reste nécessaire pour le cas suppression et pour l'affichage utilisateur
      //pour l'utilisateur, replier tous les logs sauf le dernier et avoir un bouton pour déplier
      break;

    case 'doModifier':
      //ne pas oublier d'afficher un message d'erreur si tout est vide
      //trier les logs (filtrer les caractères @ et ; car ils servent de séparateur)
      //message de bonne fin
      break;

    case 'doSupprimer':
      //supprimer la notification
      //message de bonne fin
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
    case 'doAjouter':
    case 'doModifier':
    case 'doSupprimer':
      break;









    case 'doGenerer':
      header('location: changelog.php?action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_changelog.php');
      break;
  }
?>
