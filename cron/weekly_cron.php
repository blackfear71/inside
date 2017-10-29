<?php
  // Lancement de la session
  if (empty(session_id()))
    session_start();

  // Fonctions communes
  include_once('../includes/appel_bdd.php');
  include_once('../includes/fonctions_communes.php');
  include_once('fonctions_cron.php');

  /*** Traitements hebdomadaires (tous les lundi à 7h)***/

  // Remise à plat des bilans des dépenses
  reinitializeExpenses();

  // Détermination + généreux et + radin
  // à développer (après la refonte des dépenses), à conditionner sur "lundi" ?

  // Sauvegarde BDD
  // à développer (stocker dans un dossier), à conditionner sur "lundi" ?

  // Génération log
  // à développer (stocker des fichiers .txt)

  // Redirection si asynchrone
  if (isset($_POST['weekly_cron']))
  {
    $_SESSION['weekly_cron'] = true;
    header('location: /inside/administration/cron.php?action=goConsulter');
  }
?>
