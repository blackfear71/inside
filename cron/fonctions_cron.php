<?php
  /********************************/
  /*** Liste des fonctions CRON ***/
  /********************************/

  // FONCTION : Insertion notification sortie cinéma du jour
  // RETOUR : aucun
  // FREQUENCE : tous les jours à 7h
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
