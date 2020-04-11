<?php
  /*******************************
  *** Gestion des utilisateurs ***
  ********************************
  Fonctionnalités :
  - Réinitialisation mot de passe
  - Inscriptions
  - Désinscriptions
  - Consultation des statistiques
  *******************************/

  // Fonction communes
  include_once('../../includes/functions/fonctions_communes.php');
  include_once('../../includes/functions/fonctions_regex.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données : "module métier"
  include_once('modele/metier_manageusers.php');
  include_once('modele/controles_manageusers.php');
  include_once('modele/physique_manageusers.php');

	// Initialisation sauvegarde saisie
	if (!isset($_SESSION['save']['user_ask_id']) OR !isset($_SESSION['save']['user_ask_name']) OR !isset($_SESSION['save']['new_password']))
	{
    unset($_SESSION['save']);

		$_SESSION['save']['user_ask_id']   = "";
		$_SESSION['save']['user_ask_name'] = "";
		$_SESSION['save']['new_password']  = "";
	}

  // Appel métier
  switch ($_GET['action'])
  {
    case 'goConsulter':
      // Récupération des utilisateurs inscrits et désinscrits
			$listeUsers       = getUsers();
      $listeUsersDes    = getUsersDes($listeUsers);

      // Récupération alerte gestion des utilisateurs
			$alerteUsers      = getAlerteUsers();

      // Récupération des statistiques par catégories
      $tabCategoriesIns = getTabCategoriesIns($listeUsers);
			$tabCategoriesDes = getTabCategoriesDes($listeUsersDes);
      $totalCategories  = getTotalCategories($tabCategoriesIns, $tabCategoriesDes);

      // Récupération des statistiques de demandes et publications
			$tabStats         = getTabStats($listeUsers, $listeUsersDes);
			$totalStats       = getTotalStats();
      break;

    case "doAnnulerMdp":
      resetOldPassword($_POST);
      break;

    case "doChangerMdp":
      setNewPassword($_POST);
      break;

    case "doAccepterInscription":
      acceptInscription($_POST);
      break;

    case "doRefuserInscription":
      declineInscription($_POST);
      break;

    case "doAccepterDesinscription":
      acceptDesinscription($_POST);
      break;

    case "doRefuserDesinscription":
      resetDesinscription($_POST);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: manageusers.php?action=goConsulter');
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
			}

      unset($user);

      foreach ($listeUsersDes as &$userDes)
      {
        $userDes = htmlspecialchars($userDes);
      }

      unset($userDes);

			foreach ($tabCategoriesIns as &$statsCatIns)
			{
				$statsCatIns['identifiant']     = htmlspecialchars($statsCatIns['identifiant']);
				$statsCatIns['pseudo']          = htmlspecialchars($statsCatIns['pseudo']);
        $statsCatIns['nombreFilms']     = htmlspecialchars($statsCatIns['nombreFilms']);
				$statsCatIns['nombreComments']  = htmlspecialchars($statsCatIns['nombreComments']);
        $statsCatIns['nombreCollector'] = htmlspecialchars($statsCatIns['nombreCollector']);
        $statsCatIns['bilanUser']       = htmlspecialchars($statsCatIns['bilanUser']);
			}

      unset($statsCatIns);

      foreach ($tabCategoriesDes as &$statsCatDes)
      {
        $statsCatDes['identifiant']     = htmlspecialchars($statsCatDes['identifiant']);
        $statsCatDes['pseudo']          = htmlspecialchars($statsCatDes['pseudo']);
        $statsCatDes['nombreFilms']     = htmlspecialchars($statsCatDes['nombreFilms']);
        $statsCatDes['nombreComments']  = htmlspecialchars($statsCatDes['nombreComments']);
        $statsCatDes['nombreCollector'] = htmlspecialchars($statsCatDes['nombreCollector']);
        $statsCatDes['bilanUser']       = htmlspecialchars($statsCatDes['bilanUser']);
      }

      unset($statsCatDes);

      $totalCategories['nombreFilms']     = htmlspecialchars($totalCategories['nombreFilms']);
      $totalCategories['nombreComments']  = htmlspecialchars($totalCategories['nombreComments']);
      $totalCategories['nombreCollector'] = htmlspecialchars($totalCategories['nombreCollector']);
      $totalCategories['sommeBilans']     = htmlspecialchars($totalCategories['sommeBilans']);

			foreach ($tabStats as &$stats)
			{
        foreach ($stats as &$statUser)
        {
          $statUser['identifiant']           = htmlspecialchars($statUser['identifiant']);
          $statUser['pseudo']                = htmlspecialchars($statUser['pseudo']);
          $statUser['nombreBugsSoumis']      = htmlspecialchars($statUser['nombreBugsSoumis']);
          $statUser['nombreBugsResolus']     = htmlspecialchars($statUser['nombreBugsResolus']);
          $statUser['nombreTheBox']          = htmlspecialchars($statUser['nombreTheBox']);
          $statUser['nombreTheBoxEnCharge']  = htmlspecialchars($statUser['nombreTheBoxEnCharge']);
          $statUser['nombreTheBoxTerminees'] = htmlspecialchars($statUser['nombreTheBoxTerminees']);
        }

        unset($statUser);
			}

      unset($stats);

			$totalStats['nombreBugsSoumis']      = htmlspecialchars($totalStats['nombreBugsSoumis']);
			$totalStats['nombreBugsResolus']     = htmlspecialchars($totalStats['nombreBugsResolus']);
			$totalStats['nombreTheBox']          = htmlspecialchars($totalStats['nombreTheBox']);
			$totalStats['nombreTheBoxEnCharge']  = htmlspecialchars($totalStats['nombreTheBoxEnCharge']);
			$totalStats['nombreTheBoxTerminees'] = htmlspecialchars($totalStats['nombreTheBoxTerminees']);
      break;

    case "doAnnulerMdp":
    case "doChangerMdp":
    case "doAccepterInscription":
    case "doRefuserInscription":
    case "doAccepterDesinscription":
    case "doRefuserDesinscription":
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
    case "doAnnulerMdp":
    case "doChangerMdp":
    case "doAccepterInscription":
    case "doRefuserInscription":
    case "doAccepterDesinscription":
    case "doRefuserDesinscription":
      header('location: manageusers.php?action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_manageusers.php');
      break;
  }
?>
