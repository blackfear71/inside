<?php
  // @ini_set('display_errors', 'on');

  // Contrôles communs Utilisateurs
  include_once('../../../includes/controls_users.php');

  // Modèle de données : "module métier"
  include_once('../modele/metier_parcours.php');

  // EVALUATE TRUE WHEN COI-FCT = 'L0001' PERFORM...
  switch ($_GET['action']){
    case 'consulter':
    case 'gomodifier':
      // Récupération des données par le modèle
      $parcours = getParcours($_GET['id']);
      break;

    case 'domodifier':
      // Mise à jour des données par le modèle
      $parcours = updateParcours($_GET['id'], $_POST);
      break;

    default:
      break;
  }

  // Traitements de sécurité avant la vue
  $parcours->setNom(htmlspecialchars($parcours->getNom()));
  $parcours->setDistance(htmlspecialchars($parcours->getDistance()));
  $parcours->setLieu(htmlspecialchars($parcours->getLieu()));
  $parcours->setImage(htmlspecialchars($parcours->getImage()));

  // Affichage vue
  switch ($_GET['action']){
    case 'consulter':
    case 'domodifier':
      include_once('../vue/vue_parcours.php');
      break;

    case 'gomodifier':
      include_once('../vue/mod_parcours.php');
      break;

    default:
      include_once('../vue/vue_parcours.php');
      break;
  }
?>
