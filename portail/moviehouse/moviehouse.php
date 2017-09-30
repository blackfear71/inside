<?php
  // Contrôles communs Utilisateurs
  include_once('../../includes/controls_users.php');

  // Fonctions communes
  include('../../includes/fonctions_dates.php');

  // Modèle de données : "module métier"
  include_once('modele/metier_moviehouse.php');

  // Contrôle si l'année est renseignée et numérique
	if (!isset($_GET['year']) OR !is_numeric($_GET['year']))
		header('location: moviehouse.php?view=home&year=' . date("Y") . '&action=goConsulter');

  // Initialisation sauvegarde saisie
  if (!isset($_SESSION['wrong_date']) OR $_SESSION['wrong_date'] != true)
  {
    $_SESSION['nom_film_saisi']      = "";
    $_SESSION['date_theater_saisie'] = "";
  }

  // Contrôle vue renseignée URL
  switch ($_GET['view'])
  {
    case 'home':
    case 'main':
    case 'user':
      break;

    default:
      header('location: moviehouse.php?view=home&year=' . date("Y") . '&action=goConsulter');
      break;
  }

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
      $anneeExistante = controlYear($_GET['year']);

      switch ($_GET['view'])
      {
        case 'main':
          $ongletsYears = getOnglets();
          $preferences  = getPreferences($_SESSION['identifiant']);
          $nbUsers      = countUsers();
          $listeUsers   = getUsers();
          $tableauFilms = getTabFilms($_GET['year'], $listeUsers, $nbUsers);
          break;

        case 'user':
          $ongletsYears = getOnglets();
          $preferences  = getPreferences($_SESSION['identifiant']);
          $listeFilms   = getFilms($_GET['year'], $_SESSION['identifiant']);
          break;

        case 'home':
        default:
          $listeRecents  = getRecents();

          $preferences   = getPreferences($_SESSION['identifiant']);
          $films_waited  = $preferences->getCategories_home()[0];
          $films_way_out = $preferences->getCategories_home()[1];

          if ($films_waited == "Y")
            $listeAttendus = getAttendus($_GET['year']);

          if ($films_way_out == "Y")
            $listeSorties = getSorties($_GET['year']);
          break;
      }
      break;

    case "doSaisieRapide":
      insertFilmRapide($_POST, $_GET['year']);
      break;

    case "doVoterFilm":
      insertStars($_POST, $_GET, $_SESSION['identifiant']);
      break;

    case "doParticiperFilm":
      insertParticipation($_POST, $_GET, $_SESSION['identifiant']);
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
      switch ($_GET['view'])
      {
        case 'main':
          foreach ($ongletsYears as $onglet)
          {
            $onglet = htmlspecialchars($onglet);
          }

          foreach ($tableauFilms as $film)
          {
            $film['id_film']          = htmlspecialchars($film['id_film']);
            $film['film']             = htmlspecialchars($film['film']);
            $film['date_theater']     = htmlspecialchars($film['date_theater']);

            foreach ($film['tableStars'] as $stars)
            {
              $stars['identifiant']   = htmlspecialchars($stars['identifiant']);
              $stars['stars']         = htmlspecialchars($stars['stars']);
              $stars['participation'] = htmlspecialchars($stars['participation']);
            }
          }
          break;

        case 'user':
          foreach ($ongletsYears as $onglet)
          {
            $onglet = htmlspecialchars($onglet);
          }

          foreach ($listeFilms as $film)
          {
            $film->setFilm(htmlspecialchars($film->getFilm()));
            $film->setTo_delete(htmlspecialchars($film->getTo_delete()));
            $film->setDate_add(htmlspecialchars($film->getDate_add()));
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
          break;

        case 'home':
        default:
          foreach ($listeRecents as $recent)
          {
            $recent->setFilm(htmlspecialchars($recent->getFilm()));
            $recent->setTo_delete(htmlspecialchars($recent->getTo_delete()));
            $recent->setDate_add(htmlspecialchars($recent->getDate_add()));
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

          if ($films_waited == "Y")
          {
            foreach ($listeAttendus as $attendu)
            {
              $attendu->setFilm(htmlspecialchars($attendu->getFilm()));
              $attendu->setTo_delete(htmlspecialchars($attendu->getTo_delete()));
              $attendu->setDate_add(htmlspecialchars($attendu->getDate_add()));
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
          }

          if ($films_way_out == "Y")
          {
            foreach ($listeSorties as $sortie)
            {
              $sortie->setFilm(htmlspecialchars($sortie->getFilm()));
              $sortie->setTo_delete(htmlspecialchars($sortie->getTo_delete()));
              $sortie->setDate_add(htmlspecialchars($sortie->getDate_add()));
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
          }
          break;
      }
      break;

    case "doSaisieRapide":
    case "doVoterFilm":
    case "doParticiperFilm":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case "doSaisieRapide":
      if ($_SESSION['wrong_date'] == true OR empty($_POST['date_theater']))
        header('location: moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&action=goConsulter');
      else
        header('location: moviehouse.php?view=' . $_GET['view'] . '&year=' . substr($_POST['date_theater'], 6, 4) . '&action=goConsulter');
      break;

    case "doVoterFilm":
      header('location: moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&action=goConsulter#' . $_GET['id_film']);
      break;

    case "doParticiperFilm":
      header('location: moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&action=goConsulter#' . $_GET['id_film']);
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_moviehouse.php');
      break;
  }
?>
