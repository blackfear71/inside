<?php
  /********************
  ****** Portail ******
  *********************
  Fonctionnalités :
  - Menu administration
  - Sauvegarde BDD
  - Accès phpMyAdmin
  ********************/

  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données : "module métier"
  include_once('modele/metier_portail.php');
  include_once('../manageusers/modele/metier_manageusers.php');
  include_once('../calendars/modele/metier_calendars.php');
  include_once('../movies/modele/metier_movies.php');

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
      $portail         = getPortail($alerteUsers, $alerteFilms, $alerteCalendars, $alerteAnnexes, $nbBugs, $nbEvols);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: portail.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
      foreach ($portail as &$lien)
      {
        $lien['ligne_1'] = htmlspecialchars($lien['ligne_1']);
        $lien['ligne_2'] = htmlspecialchars($lien['ligne_2']);
        $lien['ligne_3'] = htmlspecialchars($lien['ligne_3']);
        $lien['lien']    = htmlspecialchars($lien['lien']);
      }

      unset($lien);
      break;

    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'goConsulter':
    default:
      include_once('vue/vue_portail.php');
      break;
  }
?>
