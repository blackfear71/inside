<?php
  // Lancement de la session
  if (empty(session_id()))
    session_start();

  // Fonctions communes
  include('../includes/appel_bdd.php');
  include('../includes/fonctions_communes.php');

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

  // Fonctions
  function isCinemaToday()
  {
    global $bdd;

    $req = $bdd->query('SELECT id, date_doodle FROM movie_house WHERE date_doodle = ' . date("Ymd") . ' ORDER BY id ASC');
    while($data = $req->fetch())
    {
      // Contrôle notification non existante
      $notification_cinema_exist = controlNotification('cinema', $data['id']);

      // Génération notification sortie cinéma
      if ($notification_cinema_exist != true)
        insertNotification('admin', 'cinema', $data['id']);
    }
    $req->closeCursor();

    $_SESSION['daily_cron'] = true;
  }
?>
