<?php
  include_once('appel_bdd.php');

  if (empty(session_id()))
    session_start();

  global $bdd;

  // Récupération préférence
  switch ($_SESSION['user']['view_notifications'])
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

  $reponse = $bdd->query('SELECT COUNT(id) AS nb_notifs FROM notifications WHERE date = ' . date("Ymd"));
  $donnees = $reponse->fetch();
  $nb_notifs = $donnees['nb_notifs'];
  $reponse->closeCursor();

  // Récupération de la sortie
  $data = array('identifiant'     => $_SESSION['user']['identifiant'],
                'nbNotifications' => $nb_notifs,
                'view'            => $view_notifications,
                'page'            => $page
               );

  $dataJson = json_encode($data);

  echo $dataJson;
?>
