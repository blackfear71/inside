<?php
  // Fonction communes
  include_once('../../includes/fonctions_communes.php');
  include_once('../../includes/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données : "module métier"
  include_once('modele/metier_moviehouse.php');

  // Contrôle si l'id est renseignée et numérique
  if (!isset($_GET['id_film']) OR !is_numeric($_GET['id_film']))
    header('location: moviehouse.php?view=home&year=' . date("Y") . '&action=goConsulter');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
      $detailsFilm  = getDetails($_GET['id_film'], $_SESSION['identifiant']);
      $listeEtoiles = getDetailsStars($_GET['id_film']);
      break;

    case "sendMail":
      // Lecture liste des données par le modèle
      $detailsFilm  = getDetails($_GET['id_film'], $_SESSION['identifiant']);
      $listeEtoiles = getDetailsStars($_GET['id_film']);
      sendMail($_GET['id_film'], $detailsFilm, $listeEtoiles);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: mailing.php?id_film=' . $_GET['id_film'] . '&action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      $detailsFilm->setId(htmlspecialchars($detailsFilm->getId()));
      $detailsFilm->setFilm(htmlspecialchars($detailsFilm->getFilm()));
      $detailsFilm->setTo_delete(htmlspecialchars($detailsFilm->getTo_delete()));
      $detailsFilm->setDate_add(htmlspecialchars($detailsFilm->getDate_add()));
      $detailsFilm->setIdentifiant_add(htmlspecialchars($detailsFilm->getIdentifiant_add()));
      $detailsFilm->setPseudo_add(htmlspecialchars($detailsFilm->getPseudo_add()));
      $detailsFilm->setIdentifiant_del(htmlspecialchars($detailsFilm->getIdentifiant_del()));
      $detailsFilm->setPseudo_del(htmlspecialchars($detailsFilm->getPseudo_del()));
      $detailsFilm->setDate_theater(htmlspecialchars($detailsFilm->getDate_theater()));
      $detailsFilm->setDate_release(htmlspecialchars($detailsFilm->getDate_release()));
      $detailsFilm->setLink(htmlspecialchars($detailsFilm->getLink()));
      $detailsFilm->setPoster(htmlspecialchars($detailsFilm->getPoster()));
      $detailsFilm->setTrailer(htmlspecialchars($detailsFilm->getTrailer()));
      $detailsFilm->setId_url(htmlspecialchars($detailsFilm->getId_url()));
      $detailsFilm->setDoodle(htmlspecialchars($detailsFilm->getDoodle()));
      $detailsFilm->setDate_doodle(htmlspecialchars($detailsFilm->getDate_doodle()));
      $detailsFilm->setTime_doodle(htmlspecialchars($detailsFilm->getTime_doodle()));
      $detailsFilm->setRestaurant(htmlspecialchars($detailsFilm->getRestaurant()));
      $detailsFilm->setNb_comments(htmlspecialchars($detailsFilm->getNb_comments()));
      $detailsFilm->setStars_user(htmlspecialchars($detailsFilm->getStars_user()));
      $detailsFilm->setParticipation(htmlspecialchars($detailsFilm->getParticipation()));
      $detailsFilm->setNb_users(htmlspecialchars($detailsFilm->getNb_users()));
      $detailsFilm->setAverage(htmlspecialchars($detailsFilm->getAverage()));

      foreach ($listeEtoiles as &$etoiles)
      {
        $etoiles->setId(htmlspecialchars($etoiles->getId()));
        $etoiles->setId_film(htmlspecialchars($etoiles->getId_film()));
        $etoiles->setIdentifiant(htmlspecialchars($etoiles->getIdentifiant()));
        $etoiles->setPseudo(htmlspecialchars($etoiles->getPseudo()));
        $etoiles->setAvatar(htmlspecialchars($etoiles->getAvatar()));
        $etoiles->setEmail(htmlspecialchars($etoiles->getEmail()));
        $etoiles->setStars(htmlspecialchars($etoiles->getStars()));
        $etoiles->setParticipation(htmlspecialchars($etoiles->getParticipation()));
      }

      unset($etoiles);
      break;

    case "sendMail":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case "sendMail":
      header('location: details.php?id_film=' . $_GET['id_film'] . '&action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_mailing.php');
      break;
  }
?>
