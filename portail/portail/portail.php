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
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_portail.php');
  include_once('modele/physique_portail.php');

  // Appel métier
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
      $preferences->setRef_theme(htmlspecialchars($preferences->getRef_theme()));
      $preferences->setInit_chat(htmlspecialchars($preferences->getInit_chat()));
      $preferences->setView_movie_house(htmlspecialchars($preferences->getView_movie_house()));
      $preferences->setCategories_movie_house(htmlspecialchars($preferences->getCategories_movie_house()));
      $preferences->setView_the_box(htmlspecialchars($preferences->getView_the_box()));
      $preferences->setView_notifications(htmlspecialchars($preferences->getView_notifications()));
      $preferences->setManage_calendars(htmlspecialchars($preferences->getManage_calendars()));

      foreach ($news as &$messageNews)
      {
        $messageNews->setTitle(htmlspecialchars($messageNews->getTitle()));
        //$messageNews->setContent(htmlspecialchars($messageNews->getContent()));
        //$messageNews->setDetails(htmlspecialchars($messageNews->getDetails()));
        $messageNews->setLogo(htmlspecialchars($messageNews->getLogo()));
        $messageNews->setLink(htmlspecialchars($messageNews->getLink()));
      }

      unset($messageNews);

      if (isset($messagesMissions) AND !empty($messagesMissions))
      {
        foreach ($messagesMissions as $mission)
        {
          $mission->setMission(htmlspecialchars($mission->getMission()));
          $mission->setReference(htmlspecialchars($mission->getReference()));
          $mission->setDate_deb(htmlspecialchars($mission->getDate_deb()));
          $mission->setDate_fin(htmlspecialchars($mission->getDate_fin()));
          $mission->setHeure(htmlspecialchars($mission->getHeure()));
          $mission->setObjectif(htmlspecialchars($mission->getObjectif()));
          $mission->setDescription(htmlspecialchars($mission->getDescription()));
          $mission->setExplications(htmlspecialchars($mission->getExplications()));
          $mission->setConclusion(htmlspecialchars($mission->getConclusion()));
          $mission->setStatut(htmlspecialchars($mission->getStatut()));
        }
      }

      if (isset($gagnantsMissions) AND !empty($gagnantsMissions))
      {
        foreach ($gagnantsMissions as $gagnants)
        {
          foreach ($gagnants as &$gagnant)
          {
            $gagnant['id_mission']  = htmlspecialchars($gagnant['id_mission']);
            $gagnant['identifiant'] = htmlspecialchars($gagnant['identifiant']);
            $gagnant['pseudo']      = htmlspecialchars($gagnant['pseudo']);
            $gagnant['total']       = htmlspecialchars($gagnant['total']);
            $gagnant['rank']        = htmlspecialchars($gagnant['rank']);
          }

          unset($gagnant);
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
      if ($_SESSION['index']['mobile'] == true)
        include_once('vue/vue_portail_mobile.php');
      else
        include_once('vue/vue_portail.php');
      break;
  }
?>
