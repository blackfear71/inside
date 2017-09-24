<?php
  // Contrôles communs
	// Lancement de la session
	if (empty(session_id()))
	 session_start();

	// Si déjà connecté
	if (isset($_SESSION['connected']) AND $_SESSION['connected'] == true AND $_SESSION['identifiant'] != "admin")
	 header('location: portail/portail/portail.php?action=goConsulter');
	elseif (isset($_SESSION['connected']) AND $_SESSION['connected'] == true AND $_SESSION['identifiant'] == "admin")
	 header('location: administration/administration.php?action=goConsulter');
	else
	 $_SESSION['connected'] = false;

	// Initialisation sauvegarde saisie inscription
	if (((!isset($_SESSION['already_exist'])  OR  $_SESSION['already_exist'] != true)
	AND  (!isset($_SESSION['wrong_confirm'])  OR  $_SESSION['wrong_confirm'] != true))
	OR   (isset($_SESSION['ask_inscription']) AND $_SESSION['ask_inscription'] == true))
	{
		$_SESSION['identifiant_saisi']               = "";
    $_SESSION['pseudo_saisi']                    = "";
    $_SESSION['mot_de_passe_saisi']              = "";
    $_SESSION['confirmation_mot_de_passe_saisi'] = "";
	}

	// Initialisation sauvegarde saisie changement mot de passe
	if (((!isset($_SESSION['wrong_id'])      OR $_SESSION['wrong_id'] != true)
	AND  (!isset($_SESSION['already_asked']) OR $_SESSION['already_asked'] != true))
	OR   (isset($_SESSION['asked'])          AND $_SESSION['asked'] == true))
		$_SESSION['identifiant_saisi_mdp'] = "";
		
  // Modèle de données : "module métier"
  include_once('connexion/modele/metier_index.php');

  // Appel métier
	if (isset($_GET['action']))
	{
		switch ($_GET['action'])
		{
			case "goChangerMdp":
			case "goInscription":
				break;

			case "doConnecter":
				$connected = connectUser($_POST);
				break;

			case "doDemanderInscription":
				subscribe($_POST);
				break;

			case "doDemanderMdp":
				resetPassword($_POST);

			default:
				// Initialisation site
				header('location: /inside/index.php');
				break;
		}
	}

  // Traitements de sécurité avant la vue
	if (isset($_GET['action']))
	{
	  switch ($_GET['action'])
	  {
			case "goChangerMdp":
			case "goInscription":
			case "doConnecter":
			case "doDemanderInscription":
			case "doDemanderMdp":
	    default:
	      break;
	  }
	}

  // Redirection affichage
	if (isset($_GET['action']))
	{
	  switch ($_GET['action'])
	  {
			case "goChangerMdp":
				include_once('connexion/vue/vue_mdp.php');
				break;

			case "goInscription":
				include_once('connexion/vue/vue_inscription.php');
				break;

			case "doConnecter":
				if ($connected == true AND $_SESSION['identifiant'] == "admin")
					header('location: administration/administration.php?action=goConsulter');
				elseif ($connected == true AND $_SESSION['identifiant'] != "admin")
					header('location: portail/portail/portail.php?action=goConsulter');
				else
					header('location: index.php');
				break;

			case "doDemanderInscription":
				header('location: index.php?action=goInscription');
				break;

			case "doDemanderMdp":
				header('location: index.php?action=goChangerMdp');
				break;

	    default:
	      include_once('connexion/vue/vue_index.php');
	      break;
	  }
	}
	else
		include_once('connexion/vue/vue_index.php');
?>
