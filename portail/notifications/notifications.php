<?php
  /********************************
  ********* Notifications *********
  *********************************
  Fonctionnalités :
  - Consultation des notifications
  ********************************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_notifications.php');
  include_once('modele/physique_notifications.php');

  // Appels métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
      switch ($_GET['view'])
      {
        case 'all':
        case 'week':
        case 'me':
          // Contrôle si la page renseignée et numérique
          if (!isset($_GET['page']) OR !is_numeric($_GET['page']))
            header('location: notifications.php?view=' . $_GET['view'] . '&action=goConsulter&page=1');
          else
          {
            // Calcul du nombre de pages
            $nombrePages = getPages($_GET['view'], $_SESSION['user']['identifiant']);

            // Récupération des notifications en fonction de la page
            if ($nombrePages > 0)
            {
              if ($_GET['page'] > $nombrePages)
                header('location: notifications.php?view=' . $_GET['view'] . '&action=goConsulter&page=' . $nombrePages);
              elseif ($_GET['page'] < 1)
                header('location: notifications.php?view=' . $_GET['view'] . '&action=goConsulter&page=1');
              else
                $notifications = getNotifications($_GET['view'], $_SESSION['user']['identifiant'], $nombrePages, $_GET['page']);
            }

            // Formatage des notifications
            if (!empty($notifications))
            {
              // Récupération de la liste des utilisateurs
              $listeUsers = physiqueUsers();

              // Formatage des notifications
              $notifications = formatNotifications($notifications, $listeUsers, $_SESSION['user']['identifiant']);
            }
          }
          break;

        case 'today':
          // Récupération des notifications du jour
          $notifications = getNotifications($_GET['view'], $_SESSION['user']['identifiant'], NULL, NULL);

          // Formatage des notifications
          if (!empty($notifications))
          {
            // Récupération de la liste des utilisateurs
            $listeUsers = physiqueUsers();

            // Formatage des notifications
            $notifications = formatNotifications($notifications, $listeUsers, $_SESSION['user']['identifiant']);
          }
          break;

        default:
          // Contrôle vue renseignée URL
          header('location: notifications.php?view=today&action=goConsulter');
          break;
      }
      break;

    default:
      // Contrôle action renseignée URL
      header('location: notifications.php?view=' . $_GET['view'] . '&action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      if (!empty($notifications))
      {
        foreach ($listeUsers as &$user)
        {
          $user = htmlspecialchars($user);
        }

        unset($user);

        foreach ($notifications as $notification)
        {
          Notification::secureData($notification);
        }
      }
      break;

    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'goConsulter':
    default:
      include_once('vue/vue_notifications.php');
      break;
  }
?>
