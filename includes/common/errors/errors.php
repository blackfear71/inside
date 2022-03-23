<?php
  /******************************
  *********** Erreur ************
  *******************************
  Fonctionnalités :
  - Affichage des erreurs serveur
  ******************************/

  // Fonctions communes
  include_once($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/functions/metier_commun.php');
  include_once($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/functions/physique_commun.php');

  // Modèle de données
  include_once('modele/metier_errors.php');

  // Appels métier
  switch ($_GET['code'])
  {
    default:
      // Récupération de la plateforme
      $plateforme = getPlateforme();

      // Récupération de la date de dernière modification pour mise à jour automatique du cache du navigateur
      $dateModificationCssErrors = filemtime($_SERVER['DOCUMENT_ROOT'] . '/inside/includes/assets/css/' . $plateforme . '/styleErrors.css');

      // Récupération de l'erreur
      $erreur = getErreurServeur($_GET['code']);
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['code'])
  {
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['code'])
  {
    default:
      include_once('vue/vue_errors.php');
      break;
  }
?>
