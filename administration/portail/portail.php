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

  // Modèle de données
  include_once('modele/metier_portail.php');
  include_once('modele/physique_portail.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Récupération des alertes
			$alerteUsers     = getAlerteUsers();
			$alerteFilms     = getAlerteFilms();
      $alerteCalendars = getAlerteCalendars();
      $alerteAnnexes   = getAlerteAnnexes();

      // Récupération du nombre de bugs et évolutions
			$nombreBugs      = getNombreBugs();
			$nombreEvols     = getNombreEvols();

      // Création du portail administrateur
      $portail         = getPortail($alerteUsers, $alerteFilms, $alerteCalendars, $alerteAnnexes, $nombreBugs, $nombreEvols);
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
        $lienPortail['ligne_1'] = htmlspecialchars($lienPortail['ligne_1']);
        $lienPortail['ligne_2'] = htmlspecialchars($lienPortail['ligne_2']);
        $lienPortail['ligne_3'] = htmlspecialchars($lienPortail['ligne_3']);
        $lienPortail['lien']    = htmlspecialchars($lienPortail['lien']);
      }

      unset($lienPortail);
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
