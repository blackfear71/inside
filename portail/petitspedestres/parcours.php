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

  // Fonctions communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/physique_commun.php');

  // Contrôles communs Utilisateur
  controlsUser();

  // Modèle de données
  include_once('modele/metier_parcours.php');
  include_once('modele/controles_parcours.php');
  include_once('modele/physique_parcours.php');

  // Appels métier
  switch ($_GET['action'])
  {
    case 'goConsulterListe':
      // Initialisation de la sauvegarde en session
      initializeSaveSession();

      // Récupération de la liste des parcours
      $listeParcours = getListeParcours($_SESSION['user']['equipe']);
      break;

    case 'goAjouter':
      // Initialisation de la sauvegarde en session
      initializeSaveSession();
      break;

    case 'goConsulter':
    case 'goModifier':
      // Contrôle si l'id est renseigné
      if (!isset($_GET['id_parcours']) OR empty($_GET['id_parcours']))
        header('location: parcours.php?action=goConsulterListe');
      else
      {
        // Initialisation de la sauvegarde en session
        initializeSaveSession();

        // Vérification parcours accessible
        $parcoursExistant = isParcoursDisponible($_GET['id_parcours'], $_SESSION['user']['equipe']);

        // Récupération des détails du parcours
        if ($parcoursExistant == true)
          $parcours = getParcours($_GET['id_parcours']);
      }
      break;

    case 'doAjouter':
      // Insertion d'un parcours
      $erreurParcours = insertParcours($_POST, $_SESSION['user']['equipe']);
      break;

    case 'doModifier':
      // Modification d'un parcours
      $erreurParcours = updateParcours($_GET['id_parcours'], $_POST);
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
      // On n'applique pas d'échappement car AngularJS le fait automatiquement dans {{ }}
      /*foreach ($listeParcours as $parcours)
      {
        Parcours::secureData($parcours);
      }*/

      // Conversion JSON
      $listeParcoursJson = json_encode(convertForJsonListeParcours($listeParcours));
      break;

    case 'goConsulter':
    case 'goModifier':
      if ($parcoursExistant == true)
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
      if ($erreurParcours == true)
        header('location: parcours.php?action=goAjouter');
      else
        header('location: parcours.php?action=goConsulterListe');
      break;

    case 'doModifier':
      if ($erreurParcours == true)
        header('location: parcours.php?id_parcours=' . $_GET['id_parcours'] . '&action=goModifier');
      else
        header('location: parcours.php?id_parcours=' . $_GET['id_parcours'] . '&action=goConsulter');
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
