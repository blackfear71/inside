<?php
	/**************************
	********** Index **********
	***************************
	Fonctionnalités :
	- Connexion
	- Inscription
	- Récupération mot de passe
	**************************/

	// Fonction communes
	include_once('includes/functions/metier_commun.php');

	// Contrôles communs
	controlsIndex();

	// Modèle de données
	include_once('portail/index/modele/metier_index.php');
	include_once('portail/index/modele/controles_index.php');
  include_once('portail/index/modele/physique_index.php');

  // Appel métier
	switch ($_GET['action'])
	{
		case 'goConsulter':
			// Initialisation de la sauvegarde en session
			$erreursIndex = initializeSaveSession();
			break;

		case 'doConnecter':
			// Connexion de l'utilisateur
			$connected = connectUser($_POST);
			break;

		case 'doDemanderInscription':
			// Demande d'inscription
			subscribe($_POST);
			break;

		case 'doDemanderMdp':
			// Demande de réinitialisation de mot de passe
			resetPassword($_POST);

		default:
			// Contrôle action renseignée URL
			header('location: /inside/index.php?action=goConsulter');
			break;
	}

  // Traitements de sécurité avant la vue
  switch ($_GET['action'])
  {
		case 'goConsulter':
		case 'doConnecter':
		case 'doDemanderInscription':
		case 'doDemanderMdp':
    default:
      break;
  }

  // Redirection affichage
  switch ($_GET['action'])
  {
		case 'doConnecter':
			if ($connected == true AND $_SESSION['user']['identifiant'] == 'admin')
				header('location: administration/portail/portail.php?action=goConsulter');
			elseif ($connected == true AND $_SESSION['user']['identifiant'] != 'admin')
				header('location: portail/portail/portail.php?action=goConsulter');
			else
				header('location: index.php?action=goConsulter');
			break;

		case 'doDemanderInscription':
		case 'doDemanderMdp':
			header('location: index.php?action=goConsulter');
			break;

		case 'goConsulter':
    default:
			if ($_SESSION['index']['mobile'] == true)
				include_once('portail/index/vue/vue_index_mobile.php');
			else
      	include_once('portail/index/vue/vue_index.php');
      break;
  }
?>
