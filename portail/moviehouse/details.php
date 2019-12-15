<?php
  /************************
  ****** Movie House ******
  *************************
  Fonctionnalités :
  - Détails du films
  - Modification du films
  - Suppression du films
  - Gestion des préférences
  - Commentaires
  ************************/

  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');
  include_once('../../includes/functions/fonctions_dates.php');
  include_once('../../includes/functions/fonctions_regex.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données : "module métier"
  include_once('modele/metier_commun.php');
  include_once('modele/metier_details.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Contrôle si l'id est renseignée et numérique
      if (!isset($_GET['id_film']) OR !is_numeric($_GET['id_film']))
        header('location: moviehouse.php?view=home&year=' . date("Y") . '&action=goConsulter');
      else
      {
        // Lecture liste des données par le modèle
        $filmExistant = controlFilm($_GET['id_film']);

        if ($filmExistant == true)
        {
          $listeNavigation   = getNavigation($_GET['id_film']);
          $detailsFilm       = getDetails($_GET['id_film'], $_SESSION['user']['identifiant']);
          $listeEtoiles      = getDetailsStars($_GET['id_film']);
          $listeCommentaires = getComments($_GET['id_film']);
        }
      }
      break;

    case "doModifier":
      $id_film = updateFilm($_POST, $_SESSION['user']['identifiant']);
      break;

    case "doSupprimer":
      $preferences = getPreferences($_SESSION['user']['identifiant']);
      deleteFilm($_POST, $_SESSION['user']['identifiant']);
      break;

    case "doVoterFilm":
      $id_film = insertStars($_POST, $_SESSION['user']['identifiant']);
      break;

    case "doParticiperFilm":
      $id_film = insertParticipation($_POST, $_SESSION['user']['identifiant']);
      break;

    case "doCommenter":
      $id_film = insertComment($_POST, $_SESSION['user']['identifiant']);
      break;

    case "doSupprimerCommentaire":
      $id_film = deleteComment($_POST, $_SESSION['user']['identifiant']);
      break;

    case "doModifierCommentaire":
      $ids = updateComment($_POST);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: details.php?id_film=' . $_GET['id_film'] . '&action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      if ($filmExistant == true)
      {
        foreach ($listeNavigation as &$navigation)
        {
          if (!empty($navigation))
          {
            $navigation['id']   = htmlspecialchars($navigation['id']);
            $navigation['film'] = htmlspecialchars($navigation['film']);
          }
        }

        unset($navigation);

        $detailsFilm->setId(htmlspecialchars($detailsFilm->getId()));
        $detailsFilm->setFilm(htmlspecialchars($detailsFilm->getFilm()));
        $detailsFilm->setTo_delete(htmlspecialchars($detailsFilm->getTo_delete()));
        $detailsFilm->setDate_add(htmlspecialchars($detailsFilm->getDate_add()));
        $detailsFilm->setIdentifiant_add(htmlspecialchars($detailsFilm->getIdentifiant_add()));
        $detailsFilm->setPseudo_add(htmlspecialchars($detailsFilm->getPseudo_add()));
        $detailsFilm->setIdentifiant_del(htmlspecialchars($detailsFilm->getIdentifiant_del()));
        $detailsFilm->setPseudo_del(htmlspecialchars($detailsFilm->getPseudo_del()));
        $detailsFilm->setSynopsis(htmlspecialchars($detailsFilm->getSynopsis()));
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
        $detailsFilm->setPlace(htmlspecialchars($detailsFilm->getPlace()));
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

        foreach ($listeCommentaires as &$comment)
        {
          $comment->setId(htmlspecialchars($comment->getId()));
          $comment->setId_film(htmlspecialchars($comment->getId_film()));
          $comment->setAuthor(htmlspecialchars($comment->getAuthor()));
          $comment->setPseudo(htmlspecialchars($comment->getPseudo()));
          $comment->setAvatar(htmlspecialchars($comment->getAvatar()));
          $comment->setDate(htmlspecialchars($comment->getDate()));
          $comment->setTime(htmlspecialchars($comment->getTime()));
          $comment->setComment(htmlspecialchars($comment->getComment()));
        }

        unset($comment);

        // Conversion JSON
        $detailsFilmJson = json_encode(convertForJson($detailsFilm));
      }
      break;

    case "doModifier":
    case "doSupprimer":
    case "doVoterFilm":
    case "doParticiperFilm":
    case "doCommenter":
    case "doSupprimerCommentaire":
    case "doModifierCommentaire":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case "doModifier":
      header('location: details.php?id_film=' . $id_film . '&action=goConsulter');
      break;

    case "doSupprimer":
      switch ($preferences->getView_movie_house())
      {
        case "C":
          $view_movie_house = "cards";
          break;

        case "H":
        default:
          $view_movie_house = "home";
          break;
      }

      header('location: moviehouse.php?view=' . $view_movie_house . '&year=' . date("Y") . '&action=goConsulter');
      break;

    case "doVoterFilm":
    case "doParticiperFilm":
      header('location: details.php?id_film=' . $id_film . '&action=goConsulter');
      break;

    case "doCommenter":
      header('location: details.php?id_film=' . $id_film . '&action=goConsulter&anchor=comments');
      break;

    case "doSupprimerCommentaire":
      header('location: details.php?id_film=' . $id_film . '&action=goConsulter&anchor=comments');
      break;

    case "doModifierCommentaire":
      header('location: details.php?id_film=' . $ids['id_film'] . '&action=goConsulter&anchor=' . $ids['id_comment']);
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_details_film.php');
      break;
  }
?>
