<?php
  /***********************
  *** Gestion des fims ***
  ************************
  Fonctionnalités :
  - Suppression des films
  ***********************/

  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données
  include_once('modele/metier_movies.php');
  include_once('modele/physique_movies.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Récupération de liste des films à supprimer
			$listeSuppression = getFilmsToDelete();

      // Récupération de l'alerte
			$alerteFilms      = getAlerteFilms();
      break;

		case 'doDeleteFilm':
      // Suppression d'un film
			deleteFilm($_POST);
			break;

		case 'doResetFilm':
      // Annulation de la demande de suppression d'un film
			resetFilm($_POST);
			break;

    default:
      // Contrôle action renseignée URL
      header('location: movies.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
			foreach ($listeSuppression as &$film)
			{
				$film->setId(htmlspecialchars($film->getId()));
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
				$film->setNb_comments(htmlspecialchars($film->getNb_comments()));
				$film->setStars_user(htmlspecialchars($film->getStars_user()));
				$film->setParticipation(htmlspecialchars($film->getParticipation()));
				$film->setNb_users(htmlspecialchars($film->getNb_users()));
				$film->setAverage(htmlspecialchars($film->getAverage()));
			}

      unset($film);
      break;

		case 'doDeleteFilm':
		case 'doResetFilm':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
		case 'doDeleteFilm':
		case 'doResetFilm':
			header ('location: movies.php?action=goConsulter');
			break;

    case 'goConsulter':
    default:
      include_once('vue/vue_movies.php');
      break;
  }
?>
