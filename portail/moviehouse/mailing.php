<?php
  /******************
  *** Movie House ***
  *******************
  Fonctionnalités :
  - Aperçu du mail
  - Envoi du mail
  ******************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');

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
        $detailsFilm  = getDetails($_GET['id_film'], $_SESSION['user']['identifiant']);
        $listeEtoiles = getDetailsStars($_GET['id_film']);
      }
      break;

    case 'sendMail':
      // Lecture liste des données par le modèle
      $idFilm       = $_POST['id_film'];
      $detailsFilm  = getDetails($idFilm, $_SESSION['user']['identifiant']);
      $listeEtoiles = getDetailsStars($idFilm);
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
      Movie::secureData($detailsFilm);

      foreach ($listeEtoiles as $etoiles)
      {
        Stars::secureData($etoiles);
      }
      break;

    case 'sendMail':
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
      include_once('vue/vue_mailing.php');
      break;
  }
?>
