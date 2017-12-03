<?php
  // Fonction communes
  include_once('../../includes/fonctions_communes.php');
  include_once('../../includes/fonctions_dates.php');
  include_once('../../includes/fonctions_regex.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données : "module métier"
  include_once('modele/metier_search.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'doSearch':
      $_SESSION['search'] = $_POST['text_search'];
      break;

    case 'goSearch':
      $resultats = getSearch($_SESSION['search']);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: search.php?action=goSearch');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goSearch':
      if (!empty($resultats))
      {
        foreach ($resultats['movie_house'] as &$resultatsMH)
        {
          $resultatsMH->setId(htmlspecialchars($resultatsMH->getId()));
          $resultatsMH->setFilm(htmlspecialchars($resultatsMH->getFilm()));
          $resultatsMH->setTo_delete(htmlspecialchars($resultatsMH->getTo_delete()));
          $resultatsMH->setDate_add(htmlspecialchars($resultatsMH->getDate_add()));
          $resultatsMH->setIdentifiant_add(htmlspecialchars($resultatsMH->getIdentifiant_add()));
          $resultatsMH->setPseudo_add(htmlspecialchars($resultatsMH->getPseudo_add()));
          $resultatsMH->setIdentifiant_del(htmlspecialchars($resultatsMH->getIdentifiant_del()));
          $resultatsMH->setPseudo_del(htmlspecialchars($resultatsMH->getPseudo_del()));
          $resultatsMH->setDate_theater(htmlspecialchars($resultatsMH->getDate_theater()));
          $resultatsMH->setDate_release(htmlspecialchars($resultatsMH->getDate_release()));
          $resultatsMH->setLink(htmlspecialchars($resultatsMH->getLink()));
          $resultatsMH->setPoster(htmlspecialchars($resultatsMH->getPoster()));
          $resultatsMH->setTrailer(htmlspecialchars($resultatsMH->getTrailer()));
          $resultatsMH->setId_url(htmlspecialchars($resultatsMH->getId_url()));
          $resultatsMH->setDoodle(htmlspecialchars($resultatsMH->getDoodle()));
          $resultatsMH->setDate_doodle(htmlspecialchars($resultatsMH->getDate_doodle()));
          $resultatsMH->setTime_doodle(htmlspecialchars($resultatsMH->getTime_doodle()));
          $resultatsMH->setRestaurant(htmlspecialchars($resultatsMH->getRestaurant()));
          $resultatsMH->setNb_comments(htmlspecialchars($resultatsMH->getNb_comments()));
          $resultatsMH->setStars_user(htmlspecialchars($resultatsMH->getStars_user()));
          $resultatsMH->setParticipation(htmlspecialchars($resultatsMH->getParticipation()));
          $resultatsMH->setNb_users(htmlspecialchars($resultatsMH->getNb_users()));
          $resultatsMH->setAverage(htmlspecialchars($resultatsMH->getAverage()));
        }

        unset($resultatsMH);

        foreach ($resultats['petits_pedestres'] as &$resultatsPP)
        {
          $resultatsPP->setNom(htmlspecialchars($resultatsPP->getNom()));
          $resultatsPP->setDistance(htmlspecialchars($resultatsPP->getDistance()));
          $resultatsPP->setLieu(htmlspecialchars($resultatsPP->getLieu()));
          $resultatsPP->setImage(htmlspecialchars($resultatsPP->getImage()));
        }

        unset($resultatsPP);

        foreach ($resultats['missions'] as &$resultatsMI)
        {
          $resultatsMI->setMission(htmlspecialchars($resultatsMI->getMission()));
          $resultatsMI->setReference(htmlspecialchars($resultatsMI->getReference()));
          $resultatsMI->setDate_deb(htmlspecialchars($resultatsMI->getDate_deb()));
          $resultatsMI->setDate_fin(htmlspecialchars($resultatsMI->getDate_fin()));
          $resultatsMI->setHeure(htmlspecialchars($resultatsMI->getHeure()));
          $resultatsMI->setObjectif(htmlspecialchars($resultatsMI->getObjectif()));
          $resultatsMI->setDescription(htmlspecialchars($resultatsMI->getDescription()));
          $resultatsMI->setExplications(htmlspecialchars($resultatsMI->getExplications()));
        }

        unset($resultatsMI);
      }
      break;

    case 'doSearch':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doSearch':
      header('location: search.php?action=goSearch');
      break;

    case 'goSearch':
    default:
      include_once('vue/vue_search.php');
      break;
  }
?>
