<?php
  /************************
  ****** Movie House ******
  *************************
  Fonctionnalités :
  - Accueil des films
  - Fiches des films
  - Gestion des préférences
  - Ajout de films
  ************************/

  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');
  include_once('../../includes/functions/fonctions_dates.php');
  include_once('../../includes/functions/fonctions_regex.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données : "module métier"
  include_once('modele/metier_commun.php');
  include_once('modele/metier_moviehouse.php');

  // Initialisation sauvegarde saisie
  if ((!isset($_SESSION['alerts']['wrong_date'])        OR $_SESSION['alerts']['wrong_date'] != true)
  AND (!isset($_SESSION['alerts']['wrong_date_doodle']) OR $_SESSION['alerts']['wrong_date_doodle'] != true))
  {
    unset($_SESSION['save']);

    $_SESSION['save']['nom_film_saisi']         = "";
    $_SESSION['save']['date_theater_saisie']    = "";
    $_SESSION['save']['date_release_saisie']    = "";
    $_SESSION['save']['trailer_saisi']          = "";
    $_SESSION['save']['link_saisi']             = "";
    $_SESSION['save']['poster_saisi']           = "";
    $_SESSION['save']['synopsis_saisi']         = "";
    $_SESSION['save']['doodle_saisi']           = "";
    $_SESSION['save']['date_doodle_saisie']     = "";
    $_SESSION['save']['time_doodle_saisi']      = "";
    $_SESSION['save']['hours_doodle_saisies']   = "";
    $_SESSION['save']['minutes_doodle_saisies'] = "";
    $_SESSION['save']['restaurant_saisi']       = "";
    $_SESSION['save']['place_saisie']           = "";
  }

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Contrôle si l'année est renseignée et numérique
      if (!isset($_GET['year']) OR !is_numeric($_GET['year']))
        header('location: moviehouse.php?view=home&year=' . date("Y") . '&action=goConsulter');
      else
      {
        // Lecture liste des données par le modèle
        $anneeExistante = controlYear($_GET['year']);
        $ongletsYears   = getOnglets();
        $preferences    = getPreferences($_SESSION['user']['identifiant']);

        switch ($_GET['view'])
        {
          case 'home':
            list($films_waited, $films_way_out) = explode(';', $preferences->getCategories_movie_house());
            $listeRecents = getRecents($_GET['year']);

            if ($films_waited == "Y")
              $listeAttendus = getAttendus($_GET['year']);

            if ($films_way_out == "Y")
              $listeSorties = getSorties($_GET['year']);
            break;

          case 'cards':
            $listeFilms = getFilms($_GET['year'], $_SESSION['user']['identifiant']);

            if (!empty($listeFilms))
              $listeEtoiles = getStarsFiches($listeFilms);
            break;

          default:
            header('location: moviehouse.php?view=home&year=' . date("Y") . '&action=goConsulter');
            break;
        }
      }
      break;

    case "doAjouter":
      $id_film = insertFilm($_POST, $_SESSION['user']['identifiant']);
      break;

    case "doVoterFilm":
      $id_film = insertStars($_POST, $_SESSION['user']['identifiant']);
      break;

    case "doParticiperFilm":
      $id_film = insertParticipation($_POST, $_SESSION['user']['identifiant']);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: moviehouse.php?view=home&year=' . date("Y") . '&action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      foreach ($ongletsYears as &$onglet)
      {
        $onglet = htmlspecialchars($onglet);
      }

      unset($onglet);

      switch ($_GET['view'])
      {
        case 'cards':
          foreach ($listeFilms as &$film)
          {
            $film->setFilm(htmlspecialchars($film->getFilm()));
            $film->setTo_delete(htmlspecialchars($film->getTo_delete()));
            $film->setDate_add(htmlspecialchars($film->getDate_add()));
            $film->setIdentifiant_add(htmlspecialchars($film->getIdentifiant_add()));
            $film->setPseudo_add(htmlspecialchars($film->getPseudo_add()));
            $film->setIdentifiant_del(htmlspecialchars($film->getIdentifiant_del()));
            $film->setPseudo_del(htmlspecialchars($film->getPseudo_del()));
            $film->setDate_theater(htmlspecialchars($film->getDate_theater()));
            $film->setDate_release(htmlspecialchars($film->getDate_release()));
            $film->setLink(htmlspecialchars($film->getLink()));
            $film->setPoster(htmlspecialchars($film->getPoster()));
            $film->setTrailer(htmlspecialchars($film->getTrailer()));
            $film->setId_url(htmlspecialchars($film->getId_url()));
            $film->setDoodle(htmlspecialchars($film->getDoodle()));
            $film->setDate_doodle(htmlspecialchars($film->getDate_doodle()));
            $film->setTime_doodle(htmlspecialchars($film->getTime_doodle()));
            $film->setRestaurant(htmlspecialchars($film->getRestaurant()));
            $film->setPlace(htmlspecialchars($film->getPlace()));
            $film->setNb_comments(htmlspecialchars($film->getNb_comments()));
            $film->setStars_user(htmlspecialchars($film->getStars_user()));
            $film->setParticipation(htmlspecialchars($film->getParticipation()));
            $film->setNb_users(htmlspecialchars($film->getNb_users()));
          }

          unset($film);

          if (isset($listeEtoiles) AND !empty($listeEtoiles))
          {
            foreach ($listeEtoiles as &$etoilesFilm)
            {
              foreach ($etoilesFilm as &$ligneEtoilesFilm)
              {
                $ligneEtoilesFilm['identifiant'] = htmlspecialchars($ligneEtoilesFilm['identifiant']);
                $ligneEtoilesFilm['pseudo']      = htmlspecialchars($ligneEtoilesFilm['pseudo']);
                $ligneEtoilesFilm['avatar']      = htmlspecialchars($ligneEtoilesFilm['avatar']);
                $ligneEtoilesFilm['stars']       = htmlspecialchars($ligneEtoilesFilm['stars']);
              }

              unset($ligneEtoilesFilm);
            }

            unset($etoilesFilm);
          }
          break;

        case 'home':
        default:
          foreach ($listeRecents as &$recent)
          {
            $recent->setFilm(htmlspecialchars($recent->getFilm()));
            $recent->setTo_delete(htmlspecialchars($recent->getTo_delete()));
            $recent->setDate_add(htmlspecialchars($recent->getDate_add()));
            $recent->setIdentifiant_add(htmlspecialchars($recent->getIdentifiant_add()));
            $recent->setPseudo_add(htmlspecialchars($recent->getPseudo_add()));
            $recent->setIdentifiant_del(htmlspecialchars($recent->getIdentifiant_del()));
            $recent->setPseudo_del(htmlspecialchars($recent->getPseudo_del()));
            $recent->setDate_theater(htmlspecialchars($recent->getDate_theater()));
            $recent->setDate_release(htmlspecialchars($recent->getDate_release()));
            $recent->setLink(htmlspecialchars($recent->getLink()));
            $recent->setPoster(htmlspecialchars($recent->getPoster()));
            $recent->setTrailer(htmlspecialchars($recent->getTrailer()));
            $recent->setId_url(htmlspecialchars($recent->getId_url()));
            $recent->setDoodle(htmlspecialchars($recent->getDoodle()));
            $recent->setDate_doodle(htmlspecialchars($recent->getDate_doodle()));
            $recent->setTime_doodle(htmlspecialchars($recent->getTime_doodle()));
            $recent->setRestaurant(htmlspecialchars($recent->getRestaurant()));
            $recent->setPlace(htmlspecialchars($recent->getPlace()));
          }

          unset($recent);

          if ($films_waited == "Y")
          {
            foreach ($listeAttendus as &$attendu)
            {
              $attendu->setFilm(htmlspecialchars($attendu->getFilm()));
              $attendu->setTo_delete(htmlspecialchars($attendu->getTo_delete()));
              $attendu->setDate_add(htmlspecialchars($attendu->getDate_add()));
              $attendu->setIdentifiant_add(htmlspecialchars($attendu->getIdentifiant_add()));
              $attendu->setPseudo_add(htmlspecialchars($attendu->getPseudo_add()));
              $attendu->setIdentifiant_del(htmlspecialchars($attendu->getIdentifiant_del()));
              $attendu->setPseudo_del(htmlspecialchars($attendu->getPseudo_del()));
              $attendu->setDate_theater(htmlspecialchars($attendu->getDate_theater()));
              $attendu->setDate_release(htmlspecialchars($attendu->getDate_release()));
              $attendu->setLink(htmlspecialchars($attendu->getLink()));
              $attendu->setPoster(htmlspecialchars($attendu->getPoster()));
              $attendu->setTrailer(htmlspecialchars($attendu->getTrailer()));
              $attendu->setId_url(htmlspecialchars($attendu->getId_url()));
              $attendu->setDoodle(htmlspecialchars($attendu->getDoodle()));
              $attendu->setDate_doodle(htmlspecialchars($attendu->getDate_doodle()));
              $attendu->setTime_doodle(htmlspecialchars($attendu->getTime_doodle()));
              $attendu->setRestaurant(htmlspecialchars($attendu->getRestaurant()));
              $attendu->setPlace(htmlspecialchars($attendu->getPlace()));
              $attendu->setNb_users(htmlspecialchars($attendu->getNb_users()));
              $attendu->setAverage(htmlspecialchars($attendu->getAverage()));
            }

            unset($attendu);
          }

          if ($films_way_out == "Y")
          {
            foreach ($listeSorties as &$sortie)
            {
              $sortie->setFilm(htmlspecialchars($sortie->getFilm()));
              $sortie->setTo_delete(htmlspecialchars($sortie->getTo_delete()));
              $sortie->setDate_add(htmlspecialchars($sortie->getDate_add()));
              $sortie->setIdentifiant_add(htmlspecialchars($sortie->getIdentifiant_add()));
              $sortie->setPseudo_add(htmlspecialchars($sortie->getPseudo_add()));
              $sortie->setIdentifiant_del(htmlspecialchars($sortie->getIdentifiant_del()));
              $sortie->setPseudo_del(htmlspecialchars($sortie->getPseudo_del()));
              $sortie->setDate_theater(htmlspecialchars($sortie->getDate_theater()));
              $sortie->setDate_release(htmlspecialchars($sortie->getDate_release()));
              $sortie->setLink(htmlspecialchars($sortie->getLink()));
              $sortie->setPoster(htmlspecialchars($sortie->getPoster()));
              $sortie->setTrailer(htmlspecialchars($sortie->getTrailer()));
              $sortie->setId_url(htmlspecialchars($sortie->getId_url()));
              $sortie->setDoodle(htmlspecialchars($sortie->getDoodle()));
              $sortie->setDate_doodle(htmlspecialchars($sortie->getDate_doodle()));
              $sortie->setTime_doodle(htmlspecialchars($sortie->getTime_doodle()));
              $sortie->setRestaurant(htmlspecialchars($sortie->getRestaurant()));
              $sortie->setPlace(htmlspecialchars($sortie->getPlace()));
            }

            unset($sortie);
          }
          break;
      }

      $preferences->setRef_theme(htmlspecialchars($preferences->getRef_theme()));
      $preferences->setView_movie_house(htmlspecialchars($preferences->getView_movie_house()));
      $preferences->setCategories_movie_house(htmlspecialchars($preferences->getCategories_movie_house()));
      $preferences->setView_the_box(htmlspecialchars($preferences->getView_the_box()));
      $preferences->setView_notifications(htmlspecialchars($preferences->getView_notifications()));
      $preferences->setManage_calendars(htmlspecialchars($preferences->getManage_calendars()));
      break;

    case "doAjouter":
    case "doVoterFilm":
    case "doParticiperFilm":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case "doAjouter":
      if ((isset($_SESSION['alerts']['wrong_date'])        AND $_SESSION['alerts']['wrong_date']        == true)
      OR  (isset($_SESSION['alerts']['wrong_date_doodle']) AND $_SESSION['alerts']['wrong_date_doodle'] == true))
        header('location: moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&action=goConsulter');
      else
        header('location: details.php?id_film=' . $id_film . '&action=goConsulter');
      break;

    case "doVoterFilm":
      header('location: moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&action=goConsulter&anchor=' . $id_film);
      break;

    case "doParticiperFilm":
      header('location: moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&action=goConsulter&anchor=' . $id_film);
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_moviehouse.php');
      break;
  }
?>
