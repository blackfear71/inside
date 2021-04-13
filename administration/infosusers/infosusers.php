<?php
  /********************************
  *** Informations utilisateurs ***
  *********************************
  Fonctionnalités :
  - Consultation utilisateurs
  - Attribution succès
  ********************************/

  // Fonction communes
  include_once('../../includes/functions/metier_commun.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données
  include_once('modele/metier_infosusers.php');
  include_once('modele/physique_infosusers.php');

  // Appels métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Récupération de la liste des utilisateurs
			$listeUsers = getUsers();
      break;

    case 'changeBeginnerStatus':
      // Mise à jour du succès "beginner"
      changeBeginner($_POST);
      break;

    case 'changeDevelopperStatus':
      // Mise à jour du succès "developper"
      changeDevelopper($_POST);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: infosusers.php?action=goConsulter');
      break;
  }

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
    case 'goConsulter':
			foreach ($listeUsers as $user)
			{
        Profile::secureData($user);
			}
      break;

    case 'changeBeginnerStatus':
    case 'changeDevelopperStatus':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case 'changeBeginnerStatus':
    case 'changeDevelopperStatus':
      header('location: infosusers.php?action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_infosusers.php');
      break;
  }
?>
