<?php
  // @ini_set('display_errors', 'on');

  // Contrôles communs Utilisateurs
  include_once('../../../includes/controls_users.php');

  // Récupération des données par le modèle
  include_once('../modele/get_parcours.php');
  $parcours = getParcours($_GET['id']);

  // Traitements de sécurité avant la vue
  $parcours->setNom(htmlspecialchars($parcours->getNom()));
  $parcours->setDistance(htmlspecialchars($parcours->getDistance()));
  $parcours->setLieu(htmlspecialchars($parcours->getLieu()));
  $parcours->setImage(htmlspecialchars($parcours->getImage()));

  // var_dump($parcours);

  // Affichage vue
  include_once('../vue/vue_parcours.php');
?>
