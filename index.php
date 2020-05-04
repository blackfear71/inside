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
	include_once('includes/functions/fonctions_communes.php');

	// Contrôles communs
	controlsIndex();

	// Modèle de données
  include_once('portail/index/modele/metier_index.php');

	// Initialisation sauvegarde saisie inscription
	if (((!isset($_SESSION['alerts']['too_short'])      OR  $_SESSION['alerts']['too_short']       != true)
	AND  (!isset($_SESSION['alerts']['already_exist'])  OR  $_SESSION['alerts']['already_exist']   != true)
	AND  (!isset($_SESSION['alerts']['wrong_confirm'])  OR  $_SESSION['alerts']['wrong_confirm']   != true))
	OR   (isset($_SESSION['alerts']['ask_inscription']) AND $_SESSION['alerts']['ask_inscription'] == true))
	{
		$_SESSION['index']['identifiant_saisi']               = "";
    $_SESSION['index']['pseudo_saisi']                    = "";
    $_SESSION['index']['mot_de_passe_saisi']              = "";
    $_SESSION['index']['confirmation_mot_de_passe_saisi'] = "";
	}

	// Initialisation sauvegarde saisie changement mot de passe
	if (((!isset($_SESSION['alerts']['wrong_id'])      OR $_SESSION['alerts']['wrong_id']      != true)
	AND  (!isset($_SESSION['alerts']['already_asked']) OR $_SESSION['alerts']['already_asked'] != true))
	OR   (isset($_SESSION['alerts']['asked'])          AND $_SESSION['alerts']['asked']        == true))
		$_SESSION['index']['identifiant_saisi_mdp'] = "";

	// Top erreur pour affichage
	if ((isset($_SESSION['alerts']['too_short'])     AND $_SESSION['alerts']['too_short']     == true)
	OR  (isset($_SESSION['alerts']['already_exist']) AND $_SESSION['alerts']['already_exist'] == true)
	OR  (isset($_SESSION['alerts']['wrong_confirm']) AND $_SESSION['alerts']['wrong_confirm'] == true))
		$error_inscription = true;
	else
		$error_inscription = false;

	if ((isset($_SESSION['alerts']['already_asked']) AND $_SESSION['alerts']['already_asked'] == true)
	OR  (isset($_SESSION['alerts']['wrong_id'])      AND $_SESSION['alerts']['wrong_id']      == true))
		$error_password = true;
	else
		$error_password = false;

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
				if ($connected == true AND $_SESSION['user']['identifiant'] == "admin")
					header('location: administration/portail/portail.php?action=goConsulter');
				elseif ($connected == true AND $_SESSION['user']['identifiant'] != "admin")
					header('location: portail/portail/portail.php?action=goConsulter');
				else
					header('location: index.php');
				break;

			case "doDemanderInscription":
			case "doDemanderMdp":
				header('location: index.php');
				break;

	    default:
				if ($_SESSION['index']['mobile'] == true)
					include_once('portail/index/vue/vue_index_mobile.php');
				else
	      	include_once('portail/index/vue/vue_index.php');
	      break;
	  }
	}
	else
	{
		if ($_SESSION['index']['mobile'] == true)
			include_once('portail/index/vue/vue_index_mobile.php');
		else
			include_once('portail/index/vue/vue_index.php');
	}
?>
