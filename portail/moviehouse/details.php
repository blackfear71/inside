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
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');
  include_once('../../includes/functions/fonctions_regex.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_moviehouse_commun.php');
  include_once('modele/metier_details.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Contrôle si l'id est renseignée et numérique
      if (!isset($_GET['id_film']) OR !is_numeric($_GET['id_film']))
        header('location: moviehouse.php?view=home&year=' . date('Y') . '&action=goConsulter');
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

    case 'doModifier':
      $idFilm = updateFilm($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doSupprimer':
      $preferences = getPreferences($_SESSION['user']['identifiant']);
      deleteFilm($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doVoterFilm':
      $idFilm = insertStars($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doParticiperFilm':
      $idFilm = insertParticipation($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doCommenter':
      $idFilm = insertComment($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doSupprimerCommentaire':
      $idFilm = deleteComment($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doModifierCommentaire':
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

        Movie::secureData($detailsFilm);

        foreach ($listeEtoiles as $etoiles)
        {
          Stars::secureData($etoiles);
        }

        foreach ($listeCommentaires as $comment)
        {
          Commentaire::secureData($comment);
        }

        // Conversion JSON
        $detailsFilmJson = json_encode(convertForJson($detailsFilm));
      }
      break;

    case 'doModifier':
    case 'doSupprimer':
    case 'doVoterFilm':
    case 'doParticiperFilm':
    case 'doCommenter':
    case 'doSupprimerCommentaire':
    case 'doModifierCommentaire':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doModifier':
    case 'doVoterFilm':
    case 'doParticiperFilm':
      header('location: details.php?id_film=' . $idFilm . '&action=goConsulter');
      break;

    case 'doSupprimer':
      switch ($preferences->getView_movie_house())
      {
        case 'C':
          $viewMovieHouse = 'cards';
          break;

        case 'H':
        default:
          $viewMovieHouse = 'home';
          break;
      }

      header('location: moviehouse.php?view=' . $viewMovieHouse . '&year=' . date('Y') . '&action=goConsulter');
      break;

    case 'doCommenter':
    case 'doSupprimerCommentaire':
      header('location: details.php?id_film=' . $idFilm . '&action=goConsulter&anchor=comments');
      break;

    case 'doModifierCommentaire':
      header('location: details.php?id_film=' . $ids['id_film'] . '&action=goConsulter&anchor=' . $ids['id_comment']);
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_details_film.php');
      break;
  }
?>
