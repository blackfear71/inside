<?php
  /******************
  ***** Portail *****
  *******************
  Fonctionnalités :
  - News
  - Liens catégories
  ******************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/physique_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');
  include_once('../../includes/functions/fonctions_regex.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_portail.php');
  include_once('modele/physique_portail.php');

  // Appels métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture des préférences utilisateur
      $preferences = getPreferences($_SESSION['user']['identifiant']);

      // Récupération des news
      $news = getNews($_SESSION['user']['identifiant']);

      // Récupération du portail
      $portail = getPortail($preferences);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: portail.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      Preferences::secureData($preferences);

      foreach ($news as $messageNews)
      {
        News::secureData($messageNews);
      }

      foreach ($portail as &$lienPortail)
      {
        $lienPortail['lien']   = htmlspecialchars($lienPortail['lien']);
        $lienPortail['title']  = htmlspecialchars($lienPortail['title']);
        $lienPortail['image']  = htmlspecialchars($lienPortail['image']);
        $lienPortail['alt']    = htmlspecialchars($lienPortail['alt']);
        $lienPortail['mobile'] = htmlspecialchars($lienPortail['mobile']);
      }

      unset($lienPortail);
      break;

    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'goConsulter':
    default:
      include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_portail.php');
      break;
  }
?>
