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
  include_once('../includes/functions/fonctions_communes.php');
  include_once('../includes/functions/fonctions_regex.php');

  // Contrôles communs Administrateur
  controlsAdmin();

  // Modèle de données : "module métier"
  include_once('modele/metier_administration.php');

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
      // Lecture liste des données par le modèle
			$listeUsers       = getUsers();
			$alerteUsers      = getAlerteUsers();
      $tabCategoriesIns = getTabCategoriesIns($listeUsers);
			$tabCategoriesDes = getTabCategoriesDes($listeUsers);
      $totalCategories  = getTotCategories($tabCategoriesIns, $tabCategoriesDes);
			$tabStats         = getTabStats($listeUsers);
			$totalStats       = getTotStats();
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
      resetInscription($_POST);
      break;

    case "doAccepterDesinscription":
      acceptDesinscription($_POST);
      break;

    case "doRefuserDesinscription":
      resetDesinscription($_POST);
      break;

    default:
      // Contrôle action renseignée URL
      header('location: manage_users.php?action=goConsulter');
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

			foreach ($tabCategoriesIns as &$statsCatIns)
			{
				$statsCatIns['identifiant']  = htmlspecialchars($statsCatIns['identifiant']);
				$statsCatIns['pseudo']       = htmlspecialchars($statsCatIns['pseudo']);
        $statsCatIns['nb_ajouts']    = htmlspecialchars($statsCatIns['nb_ajouts']);
				$statsCatIns['nb_comments']  = htmlspecialchars($statsCatIns['nb_comments']);
				$statsCatIns['bilan']        = htmlspecialchars($statsCatIns['bilan']);
				$statsCatIns['bilan_format'] = htmlspecialchars($statsCatIns['bilan_format']);
			}

      unset($statsCatIns);

      foreach ($tabCategoriesDes as &$statsCatDes)
      {
        $statsCatDes['identifiant']  = htmlspecialchars($statsCatDes['identifiant']);
        $statsCatDes['pseudo']       = htmlspecialchars($statsCatDes['pseudo']);
        $statsCatDes['nb_ajouts']    = htmlspecialchars($statsCatDes['nb_ajouts']);
        $statsCatDes['nb_comments']  = htmlspecialchars($statsCatDes['nb_comments']);
        $statsCatDes['bilan']        = htmlspecialchars($statsCatDes['bilan']);
        $statsCatDes['bilan_format'] = htmlspecialchars($statsCatDes['bilan_format']);
      }

      unset($statsCatDes);

      $totalCategories['nb_tot_ajouts']       = htmlspecialchars($totalCategories['nb_tot_ajouts']);
      $totalCategories['nb_tot_commentaires'] = htmlspecialchars($totalCategories['nb_tot_commentaires']);
      $totalCategories['somme_bilans']        = htmlspecialchars($totalCategories['somme_bilans']);
      $totalCategories['somme_bilans_format'] = htmlspecialchars($totalCategories['somme_bilans_format']);

			foreach ($tabStats as &$stats)
			{
				$stats['identifiant']         = htmlspecialchars($stats['identifiant']);
				$stats['pseudo']              = htmlspecialchars($stats['pseudo']);
				$stats['nb_bugs']             = htmlspecialchars($stats['nb_bugs']);
				$stats['nb_bugs_resolved']    = htmlspecialchars($stats['nb_bugs_resolved']);
				$stats['nb_ideas']            = htmlspecialchars($stats['nb_ideas']);
				$stats['nb_ideas_inprogress'] = htmlspecialchars($stats['nb_ideas_inprogress']);
				$stats['nb_ideas_finished']   = htmlspecialchars($stats['nb_ideas_finished']);
			}

      unset($stats);

			$totalStats['nb_tot_bugs']            = htmlspecialchars($totalStats['nb_tot_bugs']);
			$totalStats['nb_tot_bugs_resolus']    = htmlspecialchars($totalStats['nb_tot_bugs_resolus']);
			$totalStats['nb_tot_idees']           = htmlspecialchars($totalStats['nb_tot_idees']);
			$totalStats['nb_tot_idees_en_charge'] = htmlspecialchars($totalStats['nb_tot_idees_en_charge']);
			$totalStats['nb_tot_idees_terminees'] = htmlspecialchars($totalStats['nb_tot_idees_terminees']);
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
      header('location: manage_users.php?action=goConsulter');
      break;

    case 'goConsulter':
    default:
      include_once('vue/vue_manage_users.php');
      break;
  }
?>
