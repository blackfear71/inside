<?php
  /**************************
  ******* Tâches CRON *******
  ***************************
  Fonctionnalités :
  - Consultation logs
  - Déclenchement manuel CRON
  **************************/

  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données : "module métier"
  include_once('modele/metier_cron.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      $files = getLastLogs();
      break;

    default:
      // Contrôle action renseignée URL
      header('location: cron.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      foreach ($files['daily'] as &$logJ)
      {
        $logJ = htmlspecialchars($logJ);
      }

      unset($logJ);

      foreach ($files['weekly'] as &$logH)
      {
        $logH = htmlspecialchars($logH);
      }

      unset($logH);
      break;

    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'goConsulter':
    default:
      include_once('vue/vue_cron.php');
      break;
  }
?>
