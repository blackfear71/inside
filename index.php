<?php
	// Fonction communes
	include_once('includes/fonctions_communes.php');

	// Contrôles communs
	controlsIndex();

	// Initialisation sauvegarde saisie inscription
	if (((!isset($_SESSION['too_short'])      OR  $_SESSION['too_short'] != true)
	AND  (!isset($_SESSION['already_exist'])  OR  $_SESSION['already_exist'] != true)
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

	// Top erreur pour affichage
	if ((isset($_SESSION['too_short'])     AND $_SESSION['too_short']     == true)
	OR  (isset($_SESSION['already_exist']) AND $_SESSION['already_exist'] == true)
	OR  (isset($_SESSION['wrong_confirm']) AND $_SESSION['wrong_confirm'] == true))
		$error_inscription = true;
	else
		$error_inscription = false;

	if ((isset($_SESSION['already_asked']) AND $_SESSION['already_asked'] == true)
	OR  (isset($_SESSION['wrong_id'])      AND $_SESSION['wrong_id']      == true))
		$error_password = true;
	else
		$error_password = false;

  // Modèle de données : "module métier"
  include_once('connexion/modele/metier_index.php');

  // Appel métier
	if (isset($_GET['action']))
	{
		switch ($_GET['action'])
		{
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
			case "doConnecter":
				if ($connected == true AND $_SESSION['identifiant'] == "admin")
					header('location: administration/administration.php?action=goConsulter');
				elseif ($connected == true AND $_SESSION['identifiant'] != "admin")
					header('location: portail/portail/portail.php?action=goConsulter');
				else
					header('location: index.php');
				break;

			case "doDemanderInscription":
			case "doDemanderMdp":
				header('location: index.php');
				break;

	    default:
	      include_once('connexion/vue/vue_index.php');
	      break;
	  }
	}
	else
		include_once('connexion/vue/vue_index.php');
?>
