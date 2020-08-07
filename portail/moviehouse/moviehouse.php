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
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');
  include_once('../../includes/functions/fonctions_regex.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_moviehouse_commun.php');
  include_once('modele/metier_moviehouse.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Contrôle si l'année est renseignée et numérique
      if (!isset($_GET['year']) OR (!is_numeric($_GET['year']) AND $_GET['year'] != 'none'))
        header('location: moviehouse.php?view=home&year=' . date('Y') . '&action=goConsulter');
      // Contrôle de la vue pour les films à date non communiquée
      elseif ($_GET['year'] == 'none' AND $_GET['view'] != 'cards')
        header('location: moviehouse.php?view=cards&year=' . $_GET['year'] . '&action=goConsulter');
      // Lecture liste des données par le modèle
      else
      {
        // Initialisation de la sauvegarde en session
        initializeSaveSession();

        $anneeExistante = controlYear($_GET['year']);
        $ongletsYears   = getOnglets();
        $preferences    = getPreferences($_SESSION['user']['identifiant']);

        switch ($_GET['view'])
        {
          case 'home':
            list($filmsSemaine, $filmsWaited, $filmsWayOut) = explode(';', $preferences->getCategories_movie_house());

            $listeRecents = getRecents($_GET['year']);

            if ($filmsSemaine == 'Y')
            {
              $afficherSemaine = controlWeek($_GET['year']);

              // Si semaine comprise dans l'année courante
              if ($afficherSemaine == true)
                $listeSemaine = getSemaine();
            }

            if ($filmsWaited == 'Y')
              $listeAttendus = getAttendus($_GET['year']);

            if ($filmsWayOut == 'Y')
              $listeSorties = getSorties($_GET['year']);
            break;

          case 'cards':
            $listeFilms = getFilms($_GET['year'], $_SESSION['user']['identifiant']);

            if (!empty($listeFilms))
              $listeEtoiles = getStarsFiches($listeFilms);
            break;

          default:
            header('location: moviehouse.php?view=home&year=' . date('Y') . '&action=goConsulter');
            break;
        }
      }
      break;

    case 'doAjouter':
      $idFilm = insertFilm($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doVoterFilm':
      $idFilm = insertStars($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doParticiperFilm':
      $idFilm = insertParticipation($_POST, $_SESSION['user']['identifiant']);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: moviehouse.php?view=home&year=' . date('Y') . '&action=goConsulter');
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

      Preferences::secureData($preferences);

      switch ($_GET['view'])
      {
        case 'cards':
          foreach ($listeFilms as $film)
          {
            Movie::secureData($film);
          }

          if (isset($listeEtoiles) AND !empty($listeEtoiles))
          {
            foreach ($listeEtoiles as $etoilesFilm)
            {
              foreach ($etoilesFilm as $ligneEtoilesFilm)
              {
                Stars::secureData($ligneEtoilesFilm);
              }
            }
          }
          break;

        case 'home':
        default:
          foreach ($listeRecents as $recent)
          {
            Movie::secureData($recent);
          }

          if ($filmsSemaine == 'Y' AND $afficherSemaine == true)
          {
            foreach ($listeSemaine as $filmSemaine)
            {
              Movie::secureData($filmSemaine);
            }
          }

          if ($filmsWaited == 'Y')
          {
            foreach ($listeAttendus as $attendu)
            {
              Movie::secureData($attendu);
            }
          }

          if ($filmsWayOut == 'Y')
          {
            foreach ($listeSorties as $sortie)
            {
              Movie::secureData($sortie);
            }
          }
          break;
      }
      break;

    case 'doAjouter':
    case 'doVoterFilm':
    case 'doParticiperFilm':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doAjouter':
      if ((isset($_SESSION['alerts']['wrong_date'])        AND $_SESSION['alerts']['wrong_date']        == true)
      OR  (isset($_SESSION['alerts']['wrong_date_doodle']) AND $_SESSION['alerts']['wrong_date_doodle'] == true))
        header('location: moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&action=goConsulter');
      else
        header('location: details.php?id_film=' . $idFilm . '&action=goConsulter');
      break;

    case 'doVoterFilm':
    case 'doParticiperFilm':
      header('location: moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&action=goConsulter&anchor=' . $idFilm);
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_moviehouse.php');
      break;
  }
?>
