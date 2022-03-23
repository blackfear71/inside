<?php
  /**************************
  ******* Tâches CRON *******
  ***************************
  Fonctionnalités :
  - Consultation logs
  - Déclenchement manuel CRON
  **************************/

  // Fonctions communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/physique_commun.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données
  include_once('modele/metier_cron.php');

  // Appels métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Récupération des 10 derniers logs journaliers et hebdomadaires
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
