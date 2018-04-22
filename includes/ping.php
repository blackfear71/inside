<?php
  include_once('appel_bdd.php');
  include_once('classes/profile.php');
  include_once('fonctions_dates.php');
  session_start();
  global $bdd;

  switch ($_POST['function'])
  {
    // Mise à jour du ping
    case 'updatePing':
      if ($_SESSION['index']['connected'] == true AND $_SESSION['user']['identifiant'] != "admin")
      {
        $ping = date("Y-m-d_H-i-s_") . rand(1,11111111);

        $req = $bdd->prepare('UPDATE users SET ping = :ping WHERE identifiant = "' . $_SESSION['user']['identifiant'] . '"');
        $req->execute(array(
          'ping' => $ping
        ));
        $req->closeCursor();
      }
      break;

    // Lecture des utilisateurs et statut de connexion
    case 'getPings':
      $listUsers = array();

      $req = $bdd->query('SELECT id, identifiant, avatar, ping, pseudo FROM users WHERE identifiant != "admin" AND status != "I" ORDER BY identifiant ASC');
      while($data = $req->fetch())
      {
        // Récupération des données en base
        $currentUser = Profile::withData($data);

        $myUser = array('identifiant'          => $currentUser->getIdentifiant(),
                        'pseudo'               => $currentUser->getPseudo(),
                        'avatar'               => $currentUser->getAvatar(),
                        'ping'                 => $currentUser->getPing(),
                        'connected'            => $currentUser->getConnected(),
                        'date_last_connection' => '',
                        'hour_last_connection' => ''
                       );

        // Extraction date et heure ping
        if (!empty($myUser['ping']))
        {
          list($date, $time, $rand)        = explode('_', $myUser['ping']);
          list($year, $month, $day)        = explode('-', $date);
          list($hour, $minutes, $secondes) = explode('-', $time);
          $last_ping = $year . $month . $day . $hour . $minutes . $secondes;

          // Date - 3 minutes
          $limite = date('YmdHis', strtotime('now - 3 minutes'));

          // Détermination statut connexion
          if ($last_ping < $limite)
            $myUser['connected'] = false;
          else
            $myUser['connected'] = true;

          // Date et heure de dernière connexion
          $myUser['date_last_connection'] = formatDateForDisplay($year . $month . $day);
          $myUser['hour_last_connection'] = formatTimeForDisplayLight($hour . $minutes . $secondes);
        }
        else
          $myUser['connected'] = false;

        // On ajoute la ligne au tableau
        array_push($listUsers, $myUser);
      }
      $req->closeCursor();

      // Tri sur statut connexion puis identifiant
      foreach ($listUsers as $user)
      {
        $tri_statut[]      = $user['connected'];
        $tri_identifiant[] = $user['identifiant'];
      }

      array_multisort($tri_statut, SORT_DESC, $tri_identifiant, SORT_ASC, $listUsers);

      // Récupération de la sortie
      $listUsersJson = json_encode($listUsers);
      echo $listUsersJson;
      break;

    default:
      break;
  }
?>
