<?php
  include_once('appel_bdd.php');

  /****************************************************************************/
  /********************************** SELECT **********************************/
  /****************************************************************************/

  // PHYSIQUE : Lecture notifications entre 2 dates
  // RETOUR : Liste des notifications
  function physiqueNotificationsDates($date1, $date2)
  {
    // Initialisations
    $listeNotifications = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM notifications
                        WHERE date >= "' . $date1 . '" AND date <= "' . $date2 . '"
                        ORDER BY date DESC, time DESC, id DESC');

    while($data = $req->fetch())
    {
      // Instanciation d'un objet Notification à partir des données remontées de la bdd
      $notification = Notification::withData($data);

      // On ajoute la ligne au tableau
      array_push($listeNotifications, $notification);
    }


    $req->closeCursor();

    // Retour
    return $listeNotifications;
  }

  // PHYSIQUE : Lecture si élément à supprimer
  // RETOUR : Booléen
  function physiqueToDelete($table, $id)
  {
    // Initialisations
    $toDelete = false;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT *
                        FROM ' . $table . '
                        WHERE id = ' . $id);

    $data = $req->fetch();

    if ($data['to_delete'] == 'Y')
      $toDelete = true;

    $req->closeCursor();

    // Retour
    return $toDelete;
  }

  // PHYSIQUE : Lecture nombre de bugs
  // RETOUR : Nombre de bugs
  function physiqueNombreBugs()
  {
    // Initialisations
    $nombreBugs = 0;

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT COUNT(*) AS nombreBugs
                        FROM bugs
                        WHERE resolved = "N"');

    $data = $req->fetch();

    $nombreBugs = $data['nombreBugs'];

    $req->closeCursor();

    // Retour
    return $nombreBugs;
  }

  // PHYSIQUE : Lecture liste utilisateurs
  // RETOUR : Liste utilisateurs
  function physiquePingsUsers()
  {
    // Initialisations
    $listeUsers = array();

    // Requête
    global $bdd;

    $req = $bdd->query('SELECT id, identifiant, avatar, ping, pseudo
                        FROM users
                        WHERE identifiant != "admin" AND status != "I"
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
