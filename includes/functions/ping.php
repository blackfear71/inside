<?php
  include_once('appel_bdd.php');
  include_once('fonctions_dates.php');
  include_once('../classes/profile.php');

  // Lancement de la session
  if (empty(session_id()))
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
      $listeUsers = array();

      $req = $bdd->query('SELECT id, identifiant, avatar, ping, pseudo FROM users WHERE identifiant != "admin" AND status != "I" ORDER BY identifiant ASC');
      while ($data = $req->fetch())
      {
        // Récupération des données en base
        $currentUser = Profile::withData($data);

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
          $last_ping = $year . $month . $day . $hour . $minutes . $secondes;

          // Date - 3 minutes
          $limite = date('YmdHis', strtotime('now - 3 minutes'));

          // Détermination statut connexion
          if ($last_ping < $limite)
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

      // Tri sur statut connexion puis identifiant
      foreach ($listeUsers as $userTri)
      {
        $tri_statut[]      = $userTri['connected'];
        $tri_identifiant[] = $userTri['identifiant'];
      }

      array_multisort($tri_statut, SORT_DESC, $tri_identifiant, SORT_ASC, $listeUsers);

      // Récupération de la sortie
      $listeUsersJson = json_encode($listeUsers);

      echo $listeUsersJson;
      break;

    default:
      break;
  }
?>
