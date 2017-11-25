<?php
  // Fonction communes
  include_once('../../includes/fonctions_communes.php');
  include_once('../../includes/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données : "module métier"
  include_once('modele/metier_portail.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture des données par le modèle
      $preferences = getPreferences($_SESSION['identifiant']);
      $mission     = getMission7();
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
      $preferences->setView_movie_house(htmlspecialchars($preferences->getView_movie_house()));
      $preferences->setCategories_home(htmlspecialchars($preferences->getCategories_home()));
      $preferences->setToday_movie_house(htmlspecialchars($preferences->getToday_movie_house()));
      $preferences->setView_old_movies(htmlspecialchars($preferences->getView_old_movies()));
      $preferences->setView_the_box(htmlspecialchars($preferences->getView_the_box()));
      $preferences->setView_notifications(htmlspecialchars($preferences->getView_notifications()));
      $preferences->setManage_calendars(htmlspecialchars($preferences->getManage_calendars()));

      if (isset($mission) AND !empty($mission))
      {
        $mission->setMission(htmlspecialchars($mission->getMission()));
        $mission->setReference(htmlspecialchars($mission->getReference()));
        $mission->setDate_deb(htmlspecialchars($mission->getDate_deb()));
        $mission->setDate_fin(htmlspecialchars($mission->getDate_fin()));
        $mission->setHeure(htmlspecialchars($mission->getHeure()));
        $mission->setObjectif(htmlspecialchars($mission->getObjectif()));
        $mission->setDescription(htmlspecialchars($mission->getDescription()));
        $mission->setExplications(htmlspecialchars($mission->getExplications()));
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
      include_once('vue/vue_portail.php');
      break;
  }
?>
