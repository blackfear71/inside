<?php
  include_once('appel_bdd.php');

  session_start();

  global $bdd;

  // Récupération préférence
  $req1 = $bdd->query('SELECT id, view_notifications FROM preferences WHERE identifiant = "' . $_SESSION['user']['identifiant'] . '"');
  $data1 = $req1->fetch();
  $view_notifications = $data1['view_notifications'];
  $req1->closeCursor();

  switch ($view_notifications)
  {
    case "M":
      $view_notifications = "me";
      $page               = "&page=1";
      break;

    case "T":
      $view_notifications = "today";
      $page               = "";
      break;

    case "W":
      $view_notifications = "week";
      $page               = "&page=1";
      break;

    case "A":
    default:
      $view_notifications = "all";
      $page               = "&page=1";
      break;
  }

  // Récupération compteur de notifications
  $nb_notifs = 0;

  $req2 = $bdd->query('SELECT COUNT(id) AS nb_notifs FROM notifications WHERE date = ' . date("Ymd"));
  $data2 = $req2->fetch();
  $nb_notifs = $data2['nb_notifs'];
  $req2->closeCursor();

  // Récupération de la sortie
  $data = array('identifiant'     => $_SESSION['user']['identifiant'],
                'nbNotifications' => $nb_notifs,
                'view'            => $view_notifications,
                'page'            => $page
               );

  $dataJson = json_encode($data);

  echo $dataJson;
?>
