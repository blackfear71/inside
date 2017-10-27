<?php
  // Lancement de la session
  if (empty(session_id()))
    session_start();

  // Fonctions communes
  include_once('../includes/appel_bdd.php');
  include_once('../includes/fonctions_communes.php');
  include_once('fonctions_cron.php');

  /*** Traitements journaliers (tous les jours à 7h)***/

  // Sortie cinéma du jour
  isCinemaToday();

  // Mise à jour des succès
  // à développer (après la refonte des succès)

  // Génération log
  // à développer (stocker des fichiers .txt)

  // Redirection si asynchrone
  if (isset($_POST['daily_cron']))
    header('location: /inside/administration/cron.php?action=goConsulter');
?>
