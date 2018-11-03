<?php
  // Fonction communes
  include_once('../includes/fonctions_communes.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données : "module métier"
  include_once('modele/metier_administration.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
			$alerteUsers     = getAlerteUsers();
			$alerteFilms     = getAlerteFilms();
      $alerteCalendars = getAlerteCalendars();
      $alerteAnnexes   = getAlerteAnnexes();
			$nbBugs          = getNbBugs();
			$nbEvols         = getNbEvols();
      break;

    default:
      // Contrôle action renseignée URL
      header('location: administration.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      break;

    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'goConsulter':
    default:
      include_once('vue/vue_administration.php');
      break;
  }
?>
