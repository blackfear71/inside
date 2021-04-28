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
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/physique_commun.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données
  include_once('modele/metier_portail.php');
  include_once('modele/physique_portail.php');

  // Appels métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Récupération des alertes
			$alerteUsers     = getAlerteUsers();
			$alerteFilms     = getAlerteFilms();
      $alerteCalendars = getAlerteCalendars();
      $alerteAnnexes   = getAlerteAnnexes();

      // Récupération du nombre de bugs et évolutions
			$nombreBugs  = getNombreBugs();
			$nombreEvols = getNombreEvols();

      // Création du portail administrateur
      $portail = getPortail($alerteUsers, $alerteFilms, $alerteCalendars, $alerteAnnexes, $nombreBugs, $nombreEvols);
      break;

    case 'doExtract':
      extractBdd();
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
      foreach ($portail as &$lienPortail)
      {
        $lienPortail['lien']  = htmlspecialchars($lienPortail['lien']);
        $lienPortail['title'] = htmlspecialchars($lienPortail['title']);
        $lienPortail['image'] = htmlspecialchars($lienPortail['image']);
        $lienPortail['alt']   = htmlspecialchars($lienPortail['alt']);
      }

      unset($lienPortail);
      break;

    case 'doExtract':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'doExtract':
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_portail.php');
      break;
  }
?>
