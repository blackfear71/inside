<?php
  /*******************************
  *** Gestion des utilisateurs ***
  ********************************
  Fonctionnalités :
  - Génération nouvelle page
  *******************************/

  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données : "module métier"
  include_once('modele/metier_codegenerator.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
      break;

    default:
      // Contrôle action renseignée URL
      header('location: codegenerator.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':




    
      /*$checkbox_admin      = "N";
      $checkbox_angular    = "N";
      $checkbox_chat       = "Y";
      $checkbox_datepicker = "N";
      $checkbox_masonry    = "N";
      $checkbox_exif       = "N";
      $checkbox_alerts     = "Y";*/






      $checkboxes = array(array('checked' => 'N', 'option' => 'admin', 'titre' => 'Page admin'),
                          array('checked' => 'N', 'option' => 'angular', 'titre' => 'Angular'),
                          array('checked' => 'Y', 'option' => 'chat', 'titre' => 'Chat'),
                          array('checked' => 'N', 'option' => 'datepicker', 'titre' => 'Datepicker'),
                          array('checked' => 'N', 'option' => 'masonry', 'titre' => 'Masonry'),
                          array('checked' => 'N', 'option' => 'exif', 'titre' => 'Données EXIF'),
                          array('checked' => 'Y', 'option' => 'alerts', 'titre' => 'Alertes')
                         );
      break;

    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'goConsulter':
    default:
      include_once('vue/vue_codegenerator.php');
      break;
  }
?>
