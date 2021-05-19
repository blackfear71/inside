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
  include_once('../../includes/functions/physique_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');
  include_once('../../includes/functions/fonctions_regex.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_moviehouse_commun.php');
  include_once('modele/metier_moviehouse.php');
  include_once('modele/controles_moviehouse_commun.php');
  include_once('modele/physique_moviehouse_commun.php');
  include_once('modele/physique_moviehouse.php');

  // Appels métier
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

        // Vérification année existante
        $anneeExistante = controlYear($_GET['year']);

        // Redirection si l'année n'existe pas et que l'on est sur les films sanas dates
        if ($_GET['year'] == 'none' AND $anneeExistante == false)
          header('location: moviehouse.php?view=cards&year=' . date('Y') . '&action=goConsulter');
        else
        {
          // Récupération des onglets (années)
          $onglets = getOnglets();

          // Récupération des préférences de l'utilisateur
          $preferences = getPreferences($_SESSION['user']['identifiant']);

          // Lecture des films en fonction de la vue
          switch ($_GET['view'])
          {
            case 'home':
              // Lecture des préférences
              list($filmsSemaine, $filmsWaited, $filmsWayOut) = explode(';', $preferences->getCategories_movie_house());

              // Détermination si mobile
              if ($_SESSION['index']['plateforme'] == 'mobile')
                $isMobile = true;
              else
                $isMobile = false;

              // Récupération de la liste des films récemments ajoutés
              $listeRecents = getFilmsRecents($_GET['year'], $isMobile);

              // Récupération de la liste des sorties films de la semaine
              if ($filmsSemaine == 'Y')
              {
                // Vérification si la semaine fait partie de l'année courante
                $afficherSemaine = isWeekYear($_GET['year']);

                // Récupération de la liste des sorties films de la semaine
                if ($afficherSemaine == true)
                  $listeSemaine = getSortiesSemaine();
              }

              // Récupération de la liste des films les plus attendus
              if ($filmsWaited == 'Y')
                $listeAttendus = getFilmsAttendus($_GET['year'], $isMobile);

              // Récupération de la liste des prochaines sorties cinéma organisées
              if ($filmsWayOut == 'Y')
                $listeSorties = getSortiesOrganisees($_GET['year'], $isMobile);
              break;

            case 'cards':
              // Récupération de la liste des films de l'année
              $listeFilms = getFilms($_GET['year'], $_SESSION['user']['identifiant']);

              // Récupération des votes associés aux films
              if (!empty($listeFilms))
              {
                // Récupération de la liste des utilisateurs
                $listeUsers = physiqueUsers();

                // Récupération des étoiles
                $listeEtoiles = getEtoilesFichesFilms($listeFilms, $listeUsers);
              }
              break;

            default:
              // Contrôle vue renseignée URL
              header('location: moviehouse.php?view=home&year=' . date('Y') . '&action=goConsulter');
              break;
          }
        }
      }
      break;

    case 'doAjouter':
      // Insertion d'un film
      $idFilm = insertFilm($_POST, $_SESSION['user']['identifiant'], false);
      break;

    case 'doAjouterMobile':
      // Insertion d'un film
      $idFilm = insertFilm($_POST, $_SESSION['user']['identifiant'], true);
      break;

    case 'doVoterFilm':
      // Vote de l'utilisateur sur un film
      $idFilm = insertStar($_POST, $_SESSION['user']['identifiant']);
      break;

    case 'doParticiperFilm':
      // Action de l'utilisateur sur un film
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
      foreach ($onglets as &$year)
      {
        $year = htmlspecialchars($year);
      }

      unset($year);

      Preferences::secureData($preferences);

      switch ($_GET['view'])
      {
        case 'cards':
          foreach ($listeFilms as $film)
          {
            Movie::secureData($film);
          }

          if (isset($listeUsers) AND !empty($listeUsers))
          {
            foreach ($listeUsers as &$user)
            {
              $user['pseudo'] = htmlspecialchars($user['pseudo']);
              $user['avatar'] = htmlspecialchars($user['avatar']);
              $user['email']  = htmlspecialchars($user['email']);
            }

            unset($user);
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
    case 'doAjouterMobile':
    case 'doVoterFilm':
    case 'doParticiperFilm':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doAjouter':
    case 'doAjouterMobile':
      if ((isset($_SESSION['alerts']['wrong_date'])            AND $_SESSION['alerts']['wrong_date']            == true)
      OR  (isset($_SESSION['alerts']['wrong_date_doodle'])     AND $_SESSION['alerts']['wrong_date_doodle']     == true)
      OR  (isset($_SESSION['alerts']['restaurant_incomplete']) AND $_SESSION['alerts']['restaurant_incomplete'] == true))
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
      include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_moviehouse.php');
      break;
  }
?>
