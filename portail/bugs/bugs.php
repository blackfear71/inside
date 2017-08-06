<?php
  // Contrôles communs Utilisateurs
  include_once('../../includes/controls_users.php');

  // Fonctions communes
  //include('../../includes/fonctions_dates.php');

  // Modèle de données : "module métier"
  include_once('modele/metier_bugs.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'doSignaler':
      // Insertion des données par le modèle
      insertBug($_POST);
      break;

    case 'goSignaler':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doSignaler':
      header('location: bugs.php?action=goSignaler');
      break;

    case 'goSignaler':
    default:
      include_once('vue/vue_bugs.php');
      break;
  }
?>
