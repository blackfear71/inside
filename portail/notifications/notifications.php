<?php
  // Fonction communes
  include_once('../../includes/fonctions_communes.php');
  include_once('../../includes/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données : "module métier"
  include_once('modele/metier_notifications.php');

  // Contrôle vue renseignée URL
  switch ($_GET['view'])
  {
    case 'today':
      break;

    case 'week':
    case 'me':
    case 'all':
      // Contrôle si la page renseignée et numérique
      if (!isset($_GET['page']) OR !is_numeric($_GET['page']))
        header('location: notifications.php?view=' . $_GET['view'] . '&action=goConsulter&page=1');
      break;

    default:
      header('location: notifications.php?view=today&action=goConsulter');
      break;
  }

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
      if ($_GET['view'] == 'all' OR $_GET['view'] == 'week' OR $_GET['view'] == 'me')
      {
        $nbPages = getPages($_GET['view'], $_SESSION['user']['identifiant']);

        if ($nbPages > 0)
        {
          if ($_GET['page'] > $nbPages)
            header('location: notifications.php?view=' . $_GET['view'] . '&action=goConsulter&page=' . $nbPages);
          elseif ($_GET['page'] < 1)
            header('location: notifications.php?view=' . $_GET['view'] . '&action=goConsulter&page=1');
          else
            $notifications = getNotifications($_GET['view'], $_SESSION['user']['identifiant'], $nbPages, $_GET['page']);
        }
      }
      else
        $notifications = getNotifications($_GET['view'], $_SESSION['user']['identifiant'], NULL, NULL);
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
        foreach ($notifications as &$notification)
        {
          $notification->setAuthor(htmlspecialchars($notification->getAuthor()));
          $notification->setDate(htmlspecialchars($notification->getDate()));
          $notification->setTime(htmlspecialchars($notification->getTime()));
          $notification->setCategory(htmlspecialchars($notification->getCategory()));
          $notification->setContent(htmlspecialchars($notification->getContent()));
          $notification->setIcon(htmlspecialchars($notification->getIcon()));
          $notification->setSentence(htmlspecialchars($notification->getSentence()));
          $notification->setLink(htmlspecialchars($notification->getLink()));
        }

        unset($notification);

        $notifications = formatNotifications($notifications);
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
