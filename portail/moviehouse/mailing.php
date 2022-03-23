<?php
  /******************
  *** Movie House ***
  *******************
  Fonctionnalités :
  - Aperçu du mail
  - Envoi du mail
  ******************/

  // Fonctions communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/physique_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_moviehouse_commun.php');
  include_once('modele/metier_details.php');
  include_once('modele/physique_moviehouse_commun.php');
  include_once('modele/physique_details.php');

  // Appels métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Contrôle si l'id est renseignée et numérique
      if (!isset($_GET['id_film']) OR !is_numeric($_GET['id_film']))
        header('location: moviehouse.php?view=home&year=' . date('Y') . '&action=goConsulter');
      else
      {
        // Récupération des détails du film
        $detailsFilm = getDetails($_GET['id_film'], $_SESSION['user']['identifiant']);

        // Récupération de la liste des utilisateurs
        $listeUsers = getUsers($_SESSION['user']['equipe']);

        // Récupération des votes associés au film
        $listeEtoiles = getEtoilesDetailsFilm($_GET['id_film'], $listeUsers, $_SESSION['user']['equipe']);
      }
      break;

    case 'sendMail':
      // Récupération de l'id du film
      $idFilm = $_POST['id_film'];

      // Récupération des détails du film
      $detailsFilm = getDetails($idFilm, $_SESSION['user']['identifiant']);

      // Récupération de la liste des utilisateurs
      $listeUsers = getUsers($_SESSION['user']['equipe']);

      // Récupération des votes associés au film
      $listeEtoiles = getEtoilesDetailsFilm($idFilm, $listeUsers, $_SESSION['user']['equipe']);

      // Envoi du mail
      sendMail($detailsFilm, $listeEtoiles);
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
    case 'sendMail':
      Movie::secureData($detailsFilm);

      foreach ($listeUsers as &$user)
      {
        $user['pseudo'] = htmlspecialchars($user['pseudo']);
        $user['avatar'] = htmlspecialchars($user['avatar']);
        $user['email']  = htmlspecialchars($user['email']);
      }

      unset($user);

      foreach ($listeEtoiles as $etoiles)
      {
        Stars::secureData($etoiles);
      }
      break;

    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'sendMail':
      header('location: details.php?id_film=' . $idFilm . '&action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/' . $_SESSION['index']['plateforme'] . '/vue_mailing.php');
      break;
  }
?>
