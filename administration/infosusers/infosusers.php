<?php
  /********************************
  *** Informations utilisateurs ***
  *********************************
  Fonctionnalités :
  - Consultation utilisateurs
  - Attribution succès
  ********************************/

  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');
  include_once('../../includes/functions/fonctions_dates.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données : "module métier"
  include_once('modele/metier_infosusers.php');
  include_once('modele/physique_infosusers.php');

  // Appel métier
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
			foreach ($listeUsers as &$user)
			{
        $user->setIdentifiant(htmlspecialchars($user->getIdentifiant()));
				$user->setPing(htmlspecialchars($user->getPing()));
				$user->setStatus(htmlspecialchars($user->getStatus()));
				$user->setPseudo(htmlspecialchars($user->getPseudo()));
				$user->setAvatar(htmlspecialchars($user->getAvatar()));
        $user->setEmail(htmlspecialchars($user->getEmail()));
        $user->setAnniversary(htmlspecialchars($user->getAnniversary()));
        $user->setExperience(htmlspecialchars($user->getExperience()));
        $user->setLevel(htmlspecialchars($user->getLevel()));
        $user->setBeginner(htmlspecialchars($user->getBeginner()));
        $user->setDevelopper(htmlspecialchars($user->getDevelopper()));
			}

      unset($user);

      unset($niveau);
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
