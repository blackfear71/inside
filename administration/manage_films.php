<?php
  // Contrôles communs Administrateur
  include_once('../includes/controls_admin.php');

  // Modèle de données : "module métier"
  include_once('modele/metier_administration.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
			$listeSuppression = getFilmsToDelete();
			$alerteFilms      = getAlerteFilms();
      break;

		case "doDeleteFilm":
			deleteFilm($_GET['delete_id']);
			break;

		case "doResetFilm":
			resetFilm($_GET['delete_id']);
			break;

    default:
      // Contrôle action renseignée URL
      header('location: manage_films.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
			foreach ($listeSuppression as $film)
			{
				$film->setId(htmlspecialchars($film->getId()));
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
				$film->setNb_comments(htmlspecialchars($film->getNb_comments()));
				$film->setStars_user(htmlspecialchars($film->getStars_user()));
				$film->setParticipation(htmlspecialchars($film->getParticipation()));
				$film->setNb_users(htmlspecialchars($film->getNb_users()));
				$film->setAverage(htmlspecialchars($film->getAverage()));
			}
      break;

		case "doDeleteFilm":
		case "doResetFilm":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
		case "doDeleteFilm":
		case "doResetFilm":
			header ('location: manage_films.php?action=goConsulter');
			break;

    case 'goConsulter':
    default:
      include_once('vue/vue_manage_films.php');
      break;
  }
?>
