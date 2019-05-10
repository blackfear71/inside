<?php
  // Fonction communes
  include_once('../includes/functions/fonctions_communes.php');
  include_once('../includes/functions/fonctions_dates.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données : "module métier"
  include_once('modele/metier_administration.php');

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Lecture liste des données par le modèle
			$listeUsers   = getUsers();
      $listeNiveaux = getProgress($listeUsers);
      break;

    case 'changeBeginnerStatus':
      changeBeginner($_GET['user'], $_GET['top']);
      break;

    case 'changeDevelopperStatus':
      changeDevelopper($_GET['user'], $_GET['top']);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: infos_users.php?action=goConsulter');
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
        $user->setBeginner(htmlspecialchars($user->getBeginner()));
        $user->setDevelopper(htmlspecialchars($user->getDevelopper()));
			}

      unset($user);

      foreach ($listeNiveaux as &$niveau)
      {
        $niveau = htmlspecialchars($niveau);
      }

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
      header('location: infos_users.php?action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_infos_users.php');
      break;
  }
?>
