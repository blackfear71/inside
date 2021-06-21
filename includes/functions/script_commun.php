<?php
  // Fonction communes
  include_once('fonctions_dates.php');

  // Modèle de données
  include_once('metier_script_commun.php');
  include_once('physique_script_commun.php');

  // Lancement de la session
  if (empty(session_id()))
    session_start();

  // Aiguilleur $_GET
  if (isset($_GET['function']))
  {
    switch ($_GET['function'])
    {
      // Déconnexion de l'utilisateur
      case 'disconnectUser':
        disconnectUser();
        break;

      // Bascule entre la version web et la version mobile
      case 'switchMobile':
        switchMobile();
        break;

      // Décompte du nombre de notifications du jour
      case 'countNotifications':
        countNotifications($_SESSION['user']);
        break;

      // Récupère le détail des notifications
      case 'getDetailsNotifications':
        getDetailsNotifications($_SESSION['user']);
        break;

      // Décompte du nombre de bugs en temps réel
      case 'countBugs':
        countBugs($_SESSION['user']);
        break;

      // Action par défaut
      default:
        break;
    }
  }

  // Aiguilleur $_POST
  if (isset($_POST['function']))
  {
    switch ($_POST['function'])
    {
      // Récupération du ping des utilisateurs
      case 'getPings':
        getPings($_SESSION['user']['equipe']);
        break;

      // Mise à jour du ping d'un utilisateur
      case 'updatePing':
        updatePing();
        break;

      // Action par défaut
      default:
        break;
    }
  }
?>
