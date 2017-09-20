<?php
	//////////////////////////////////////
	// Paramétrage des boutons d'action //
	//////////////////////////////////////

	// Initialisations
	if (!isset($disconnect))
		$disconnect = false;

	if (!isset($profil))
		$profil = false;

	if (!isset($add_article))
		$add_article = false;

	if (!isset($add_film))
		$add_film = false;

	if (!isset($modify_film))
		$modify_film = false;

	if (!isset($ajouter_parcours))
		$ajouter_parcours = false;

	if (!isset($modify_parcours))
		$modify_parcours = false;

	if (!isset($delete_film))
			$delete_film = false;

	if (!isset($back_index))
		$back_index = false;

	if (!isset($back))
		$back = false;

	if (!isset($back_admin))
		$back_admin = false;

	if (!isset($ideas))
		$ideas = false;

	if (!isset($reports))
		$reports = false;

	// Déconnexion
	if ($disconnect == true)
	{
		echo '<form method="post" action="/inside/connexion/disconnect.php">';
			echo '<input type="submit" name="disconnect" value="" title="Déconnexion" class="icon_deconnexion" />';
			echo '<div class="hover_aside">Déconnexion</div>';
		echo '</form>';
	}

	// Profil
	if ($profil == true)
	{
		echo '<a href="/inside/profil/profil.php?user=' . $_SESSION['identifiant'] . '&action=goConsulter" title="Profil" class="link_profile">';
			echo '<img src="/inside/includes/icons/profile.png" alt="profile" title="Profil" class="icon_aside" />';
		echo '</a>';
		echo '<div class="hover_aside">Profil</div>';
	}

	// Ajouter un article
	if ($add_article == true)
	{
		echo '<a href="/inside/portail/referenceguide/saisie_article.php" title="Ajouter un article" class="link_profile">';
			echo '<img src="/inside/includes/icons/add.png" alt="add" title="Ajouter un article" class="icon_aside" />';
		echo '</a>';
		echo '<div class="hover_aside">Ajouter</div>';
	}

	// Ajouter un film (avancé)
	if ($add_film == true)
	{
		echo '<a href="/inside/portail/moviehouse/saisie.php?action=goAjouter" title="Ajouter un film (avancé)" class="link_profile">';
			echo '<img src="/inside/includes/icons/add.png" alt="add" title="Ajouter un film (avancé)" class="icon_aside" />';
		echo '</a>';
		echo '<div class="hover_aside">Ajouter</div>';
	}

	// Modifier les détails
	if ($modify_film == true AND $_SESSION['doesnt_exist'] != true)
	{
		echo '<a href="/inside/portail/moviehouse/saisie.php?modify_id=' . $_GET['id_film'] . '&action=goModifier" title="Modifier les détails" class="link_profile">';
			echo '<img src="/inside/includes/icons/edit.png" alt="modify" title="Modifier les détails" class="icon_aside" />';
		echo '</a>';
		echo '<div class="hover_aside">Modifier</div>';
	}

	// Ajouter parcours
	if ($ajouter_parcours == true)
	{
		echo '<a href="/inside/portail/petitspedestres/parcours.php?action=goajouter" title="Ajouter parcours" class="link_profile">';
			echo '<img src="/inside/includes/icons/add.png" alt="add" title="Ajouter parcours" class="icon_aside" />';
		echo '</a>';
		echo '<div class="hover_aside">Ajouter</div>';
	}

	// Modifier parcours
	if ($modify_parcours == true)
	{
		echo '<a href="/inside/portail/petitspedestres/parcours.php?id=' . $_GET['id'] . '&action=gomodifier" title="Modifier les détails" class="link_profile">';
			echo '<img src="/inside/includes/icons/edit.png" alt="modify" title="Modifier les détails" class="icon_aside" />';
		echo '</a>';
		echo '<div class="hover_aside">Modifier</div>';
	}

	// Demande suppression film
	if ($delete_film == true AND $_SESSION['doesnt_exist'] != true)
	{
		echo '<form method="post" action="details.php?delete_id=' . $_GET['id_film'] . '&action=doSupprimer" onclick="if(!confirm(\'Demander la suppression de ce film ?\')) return false;">';
			echo '<input type="submit" name="delete_film" value="" title="Demander la suppression" class="icon_delete" />';
			echo '<div class="hover_aside">Suppression</div>';
		echo '</form>';
	}

	// Retour à l'accueil
	if ($back_index == true)
	{
		echo '<a href="/inside/index.php" class="link_profile" title="Retour à l\'accueil" style="margin-top: 0;">';
				echo '<img src="/inside/includes/icons/back.png" alt="accueil" title="Retour à l\'accueil" class="icon_aside" />';
		echo '</a>';
		echo '<div class="hover_aside">Accueil</div>';
	}

	// Retour au portail
	if ($back == true)
	{
		echo '<a href="/inside/portail/portail/portail.php?action=goConsulter" title="Retour au portail" class="link_profile">';
			echo '<img src="/inside/includes/icons/back.png" alt="back" title="Retour au portail" class="icon_aside" />';
		echo '</a>';
		echo '<div class="hover_aside">Accueil</div>';
	}

	// Retour au portail administration
	if ($back_admin == true)
	{
		echo '<a href="/inside/administration/administration.php?action=goConsulter" title="Retour au portail administration" class="link_profile">';
			echo '<img src="/inside/includes/icons/back.png" alt="back_admin" title="Retour au portail administration" class="icon_aside" />';
		echo '</a>';
		echo '<div class="hover_aside">Accueil</div>';
	}

	// Boite à idées
	if ($ideas == true)
	{
		// Récupération des préférences
		switch ($_SESSION['view_the_box'])
		{
			case "P":
				$view_the_box = "inprogress";
				break;

			case "M":
				$view_the_box = "mine";
				break;

			case "D":
				$view_the_box = "done";
				break;

			case "A":
			default:
				$view_the_box = "all";
				break;
		}

		echo '<a href="/inside/portail/ideas/ideas.php?view=' . $view_the_box . '&action=goConsulter" title="&#35;TheBox" class="link_profile">';
			echo '<img src="/inside/includes/icons/ideas.png" alt="ideas" title="&#35;TheBox" class="icon_aside" />';
		echo '</a>';
		echo '<div class="hover_aside">#TheBox</div>';
	}

	// Signaler un bug
	if ($reports == true)
	{
		echo '<a href="/inside/portail/bugs/bugs.php?view=submit&action=goSignaler" title="Signaler un bug" class="link_profile">';
			echo '<img src="/inside/includes/icons/bug.png" alt="bug" title="Signaler un bug" class="icon_aside" />';
		echo '</a>';
		echo '<div class="hover_aside">Signaler</div>';
	}
?>
