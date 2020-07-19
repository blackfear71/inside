<?php
  /***************************
  *** Les petits pédestres ***
  ****************************
  Fonctionnalités :
  - Recherche de course
  - Ajout de course
  - Modification de course
  ***************************/

  //@ini_set('display_errors', 'on');

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_parcours.php');

  // EVALUATE TRUE WHEN COI-FCT = 'L0001' PERFORM...
  switch ($_GET['action'])
  {
    case 'goConsulterListe':
      // Initialisation de la sauvegarde en session
      $erreurParcours = initializeSaveSession();

      // Récupération de tous les parcours. Attention, $parcours est un tableau d'objets Parcours
      $listeParcours = listParcours();
      break;

    case 'goAjouter':
      // Initialisation de la sauvegarde en session
      $erreurParcours = initializeSaveSession();
      break;

    case 'goConsulter':
    case 'goModifier':
      // Récupération des données par le modèle
      if (!isset($_GET['id']) OR empty($_GET['id']))
        header('location: parcours.php?action=goConsulterListe');
      else
      {
        // Initialisation de la sauvegarde en session
        $erreurParcours = initializeSaveSession();

        $parcours = getParcours($_GET['id']);
      }
      break;

    case 'doAjouter':
      $erreurParcours = insertParcours($_POST);
      break;

    case 'doModifier':
      $erreurParcours = updateParcours($_GET['id'], $_POST);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: parcours.php?action=goConsulterListe');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulterListe':
      foreach ($listeParcours as $parcours)
      {
        Parcours::secureData($parcours);
      }

      // Conversion JSON
      $listeParcoursJson = json_encode(convertForJson($listeParcours));
      break;

    case 'goConsulter':
    case 'goModifier':
      Parcours::secureData($parcours);
      break;

    case 'goAjouter':
    case 'doAjouter':
    case 'doModifier':
    default:
      break;
  }

  // Affichage vue
  switch ($_GET['action'])
  {
    case 'doAjouter':
      if (isset($erreurParcours) AND $erreurParcours == true)
        header('location: parcours.php?action=goAjouter');
      else
        header('location: parcours.php?id=' . $parcours->getId() . '&action=goConsulter');
      break;

    case 'doModifier':
      if (isset($erreurParcours) AND $erreurParcours == true)
        header('location: parcours.php?id=' . $_GET['id'] . '&action=goModifier');
      else
        header('location: parcours.php?id=' . $_GET['id'] . '&action=goConsulter');
      break;

    case 'goConsulterListe':
      include_once('vue/liste_parcours.php');
      break;

    case 'goAjouter':
      include_once('vue/ajout_parcours.php');
      break;

    case 'goModifier':
      include_once('vue/mod_parcours.php');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_parcours.php');
      break;
  }
?>
