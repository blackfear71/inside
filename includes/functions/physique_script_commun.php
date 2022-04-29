<?php
  include_once('appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/
  // PHYSIQUE : Lecture du nombre de notifications entre 2 dates
  // RETOUR : Nombre de notifications
  function physiqueNombreNotificationsDates($equipe, $date1, $date2)
  {
    // Initialisations
    $nombreNotifications = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreNotifications
                        FROM notifications
                        WHERE (team = "' . $equipe . '" OR team = "") AND (date >= "' . $date1 . '" AND date <= "' . $date2 . '" AND to_delete = "N")');

    $data = $req->fetch();

    if ($data['nombreNotifications'] > 0)
      $nombreNotifications = $data['nombreNotifications'];

    $req->closeCursor();

    // Retour
    return $nombreNotifications;
  }

  // PHYSIQUE : Lecture nombre de bugs
  // RETOUR : Nombre de bugs
  function physiqueNombreBugs($equipe)
  {
    // Initialisations
    $nombreBugs = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreBugs
                        FROM bugs
                        WHERE team = "' . $equipe . '" AND resolved = "N"');

    $data = $req->fetch();

    $nombreBugs = $data['nombreBugs'];

    $req->closeCursor();

    // Retour
    return $nombreBugs;
  }

  // PHYSIQUE : Lecture liste utilisateurs
  // RETOUR : Liste utilisateurs
  function physiquePingsUsers($equipe)
  {
    // Initialisations
    $listeUsers = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, team, avatar, ping, pseudo
                        FROM users
                        WHERE identifiant != "admin" AND team = "' . $equipe . '" AND status != "I"
                        ORDER BY identifiant ASC');

    while ($data = $req->fetch())
    {
      // Instanciation d'un objet Profile à partir des données remontées de la bdd
      $currentUser = Profile::withData($data);

      // Création d'un tableau pour chaque utilisateur
      $user = array('identifiant'          => $currentUser->getIdentifiant(),
                    'pseudo'               => $currentUser->getPseudo(),
                    'avatar'               => $currentUser->getAvatar(),
                    'ping'                 => $currentUser->getPing(),
                    'connected'            => $currentUser->getConnected(),
                    'date_last_connection' => '',
                    'hour_last_connection' => ''
                   );

      // Extraction date et heure ping
      if (!empty($user['ping']))
      {
        list($date, $time, $rand)        = explode('_', $user['ping']);
        list($year, $month, $day)        = explode('-', $date);
        list($hour, $minutes, $secondes) = explode('-', $time);

        $lastPing = $year . $month . $day . $hour . $minutes . $secondes;

        // Date - 3 minutes
        $limite = date('YmdHis', strtotime('now - 3 minutes'));

        // Détermination statut connexion
        if ($lastPing < $limite)
          $user['connected'] = false;
        else
          $user['connected'] = true;

        // Date et heure de dernière connexion
        $user['date_last_connection'] = formatDateForDisplay($year . $month . $day);
        $user['hour_last_connection'] = formatTimeForDisplayLight($hour . $minutes . $secondes);
      }
      else
        $user['connected'] = false;

      // On ajoute la ligne au tableau
      array_push($listeUsers, $user);
    }

    $req->closeCursor();

    // Retour
    return $listeUsers;
  }

  /****************************************************************************/
  /********************************** UPDATE **********************************/
  /****************************************************************************/
  // PHYSIQUE : Modification ping
  // RETOUR : Aucun
  function physiqueUpdatePing($ping, $identifiant)
  {
    // Requête
    global $bdd;

    $req = $bdd->prepare('UPDATE users
                          SET ping = :ping
                          WHERE identifiant = "' . $identifiant . '"');

    $req->execute(array(
      'ping' => $ping
    ));

    $req->closeCursor();
  }
?>
