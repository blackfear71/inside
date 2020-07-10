<?php
  include_once('appel_bdd.php');

  // Lancement de la session
  if (empty(session_id()))
    session_start();

  global $bdd;

  switch ($_GET['function'])
  {
    case 'count_notifications':
      // Récupération préférence
      switch ($_SESSION['user']['view_notifications'])
      {
        case 'M':
          $view_notifications = 'me';
          $page               = '&page=1';
          break;

        case 'T':
          $view_notifications = 'today';
          $page               = '';
          break;

        case 'W':
          $view_notifications = 'week';
          $page               = '&page=1';
          break;

        case 'A':
        default:
          $view_notifications = 'all';
          $page               = '&page=1';
          break;
      }

      // Récupération compteur de notifications du jour
      $nb_notifs_jour = 0;

      $reponse = $bdd->query('SELECT COUNT(id) AS nb_notifs_jour FROM notifications WHERE date = ' . date('Ymd'));
      $donnees = $reponse->fetch();
      $nb_notifs_jour = $donnees['nb_notifs_jour'];
      $reponse->closeCursor();

      // Récupération de la sortie
      $data = array('identifiant'         => $_SESSION['user']['identifiant'],
                    'nbNotificationsJour' => $nb_notifs_jour,
                    'view'                => $view_notifications,
                    'page'                => $page
                   );

      $dataJson = json_encode($data);

      echo $dataJson;
      break;

    case 'get_details_notifications':
      // Récupération compteur de notifications du jour
      $nb_notifs_jour = 0;

      $req1 = $bdd->query('SELECT COUNT(id) AS nb_notifs_jour FROM notifications WHERE date = ' . date('Ymd'));
      $data1 = $req1->fetch();
      $nb_notifs_jour = $data1['nb_notifs_jour'];
      $req1->closeCursor();

      // Calcul des dates de la semaine
      $nb_jours_lundi    = 1 - date('N');
      $nb_jours_dimanche = 7 - date('N');
      $lundi             = date('Ymd', strtotime('+' . $nb_jours_lundi . ' days'));
      $aujourdhui        = date('Ymd', strtotime('+' . $nb_jours_dimanche . ' days'));

      // Récupération compteur de notifications de la semaine
      $nb_notifs_semaine = 0;

      $req2 = $bdd->query('SELECT COUNT(id) AS nb_notifs_semaine FROM notifications WHERE date >= "' . $lundi . '" AND date <= "' . $aujourdhui . '"');
      $data2 = $req2->fetch();
      $nb_notifs_semaine = $data2['nb_notifs_semaine'];
      $req2->closeCursor();

      // Récupération de la sortie
      $data = array('identifiant'            => $_SESSION['user']['identifiant'],
                    'nbNotificationsJour'    => $nb_notifs_jour,
                    'nbNotificationsSemaine' => $nb_notifs_semaine
                   );

      $dataJson = json_encode($data);

      echo $dataJson;
      break;

    default:
      break;
  }
?>
