<?php
  // Lancement de la session
  if (empty(session_id()))
    session_start();

  // Fonctions communes
  include('../includes/appel_bdd.php');
  include('../includes/fonctions_communes.php');

  /*** Traitements hebdomadaires (tous les lundi à 7h)***/

  // Détermination + généreux et + radin
  // à développer (après la refonte des dépenses)

  // Sauvegarde BDD
  // à développer (stocker dans un dossier)

  // Génération log
  // à développer (stocker des fichiers .txt)

  // Redirection si asynchrone
  if (isset($_POST['weekly_cron']))
    header('location: /inside/administration/cron.php?action=goConsulter');

  // Fonctions
?>
