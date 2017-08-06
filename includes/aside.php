<!--<script>
	function afficherMasquer(id)
	{
		if (document.getElementById(id).style.display == "none")
			document.getElementById(id).style.display = "block";
		else
			document.getElementById(id).style.display = "none";
	}
</script>-->

<?php
	//////////////////////////////////////
	// Paramétrage des boutons d'action //
	//////////////////////////////////////

	// Initialisations
	if (!isset($disconnect))
		$disconnect = false;

	if (!isset($profil))
		$profil = false;

	if (!isset($menu_rg))
		$menu_rg = false;

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

	if (!isset($bug))
		$bug = false;

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
		echo '<a href="/inside/profil/profil.php?user=' . $_SESSION['identifiant'] . '" title="Profil" class="link_profile">';
			echo '<img src="/inside/includes/icons/profile.png" alt="profile" title="Profil" class="icon_profile" />';
		echo '</a>';
		echo '<div class="hover_aside">Profil</div>';
	}

	// Menu
	if ($menu_rg == true)
	{
		echo '<a onclick="afficherMasquer(\'menu\')" title="Menu" class="link_profile">';
			echo '<img src="/inside/includes/icons/menu.png" alt="menu" title="Menu" class="icon_profile" />';
		echo '</a>';
		echo '<div class="hover_aside">Menu</div>';

		// Menu caché
		echo '<div id="menu" style="display: none;">';
			echo '<div class="triangle2"></div>';
			echo '<ul>';
				echo '<li style="border-top-left-radius: 5px; border-top-right-radius: 5px;"><a class="link_menu" href="liste_articles.php?univers=rdz&search=no">Univers RDZ</a></li>';
				echo '<li><a class="link_menu" href="liste_articles.php?univers=tso&search=no">Univers TSO</a></li>';
				echo '<li><a class="link_menu" href="liste_articles.php?univers=ims&search=no">Univers IMS</a></li>';
				echo '<li><a class="link_menu" href="liste_articles.php?univers=micrortc&search=no">Univers Micro/RTC</a></li>';
				echo '<li><a class="link_menu" href="liste_articles.php?univers=portaileid&search=no">Portail EID</a></li>';
				echo '<li style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; border-bottom: solid 1px #e3e3e3;"><a class="link_menu" href="liste_articles.php?univers=glossaire&search=no">Glossaire</a></li>';
			echo '</ul>';
		echo '</div>';

		// Récupération de l'univers en cas d'ajout d'article
		if (isset($_GET['univers']))
			$_SESSION['univers'] = $_GET['univers'];
	}

	// Ajouter un article
	if ($add_article == true)
	{
		echo '<a href="/inside/portail/referenceguide/saisie_article.php" title="Ajouter un article" class="link_profile">';
			echo '<img src="/inside/includes/icons/add.png" alt="add" title="Ajouter un article" class="icon_profile" />';
		echo '</a>';
		echo '<div class="hover_aside">Ajouter</div>';
	}

	// Ajouter un film (avancé)
	if ($add_film == true)
	{
		echo '<a href="/inside/portail/moviehouse/saisie_avancee.php" title="Ajouter un film (avancé)" class="link_profile">';
			echo '<img src="/inside/includes/icons/add.png" alt="add" title="Ajouter un film (avancé)" class="icon_profile" />';
		echo '</a>';
		echo '<div class="hover_aside">Ajouter</div>';
	}

	// Modifier les détails
	if ($modify_film == true AND $_SESSION['doesnt_exist'] != true)
	{
		echo '<a href="/inside/portail/moviehouse/saisie_avancee.php?modify_id=' . $_GET['id_film'] . '" title="Modifier les détails" class="link_profile">';
			echo '<img src="/inside/includes/icons/edit.png" alt="modify" title="Modifier les détails" class="icon_profile" />';
		echo '</a>';
		echo '<div class="hover_aside">Modifier</div>';
	}

	// Ajouter parcours
	if ($ajouter_parcours == true)
	{
		echo '<a href="/inside/portail/petitspedestres/parcours.php?action=goajouter" title="Ajouter parcours" class="link_profile">';
			echo '<img src="/inside/includes/icons/add.png" alt="add" title="Ajouter parcours" class="icon_profile" />';
		echo '</a>';
		echo '<div class="hover_aside">Ajouter</div>';
	}

	// Modifier parcours
	if ($modify_parcours == true)
	{
		echo '<a href="/inside/portail/petitspedestres/parcours.php?id=' . $_GET['id'] . '&action=gomodifier" title="Modifier les détails" class="link_profile">';
			echo '<img src="/inside/includes/icons/edit.png" alt="modify" title="Modifier les détails" class="icon_profile" />';
		echo '</a>';
		echo '<div class="hover_aside">Modifier</div>';
	}

	// Demande suppression film
	if ($delete_film == true AND $_SESSION['doesnt_exist'] != true)
	{
		echo '<form method="post" action="actions_films.php?delete_id=' . $_GET['id_film'] . '" onclick="if(!confirm(\'Effectuer la demande de suppression de ce film ?\')) return false;">';
			echo '<input type="submit" name="delete_film" value="" title="Demander la suppression" class="icon_delete" />';
			echo '<div class="hover_aside">Suppression</div>';
		echo '</form>';
	}

	// Retour à l'accueil
	if ($back_index == true)
	{
		echo '<a href="/inside/index.php" class="link_profile" title="Retour à l\'accueil" style="margin-top: 0;">';
				echo '<img src="/inside/includes/icons/back.png" alt="accueil" title="Retour à l\'accueil" class="icon_profile" />';
		echo '</a>';
		echo '<div class="hover_aside">Accueil</div>';
	}

	// Retour au portail
	if ($back == true)
	{
		echo '<a href="/inside/portail/portail.php" title="Retour au portail" class="link_profile">';
			echo '<img src="/inside/includes/icons/back.png" alt="back" title="Retour au portail" class="icon_profile" />';
		echo '</a>';
		echo '<div class="hover_aside">Accueil</div>';
	}

	// Retour au portail administration
	if ($back_admin == true)
	{
		echo '<a href="/inside/administration/administration.php" title="Retour au portail administration" class="link_profile">';
			echo '<img src="/inside/includes/icons/back.png" alt="back_admin" title="Retour au portail administration" class="icon_profile" />';
		echo '</a>';
		echo '<div class="hover_aside">Accueil</div>';
	}

	// Boite à idées
	if ($ideas == true)
	{
		echo '<a href="/inside/portail/ideas/ideas.php?view=inprogress&action=goConsulter" title="&#35;TheBox" class="link_profile">';
			echo '<img src="/inside/includes/icons/ideas.png" alt="ideas" title="&#35;TheBox" class="icon_profile" />';
		echo '</a>';
		echo '<div class="hover_aside">#TheBox</div>';
	}

	// Signaler un bug
	if ($bug == true)
	{
		echo '<a href="/inside/portail/bugs/bugs.php?action=goSignaler" title="Signaler un bug" class="link_profile">';
			echo '<img src="/inside/includes/icons/bug.png" alt="bug" title="Signaler un bug" class="icon_profile" />';
		echo '</a>';
		echo '<div class="hover_aside">Signaler</div>';
	}
?>
