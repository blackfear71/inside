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
		echo '<form method="post" action="/insidecgi/connexion/disconnect.php">';
			echo '<input type="submit" name="disconnect" value="" title="Déconnexion" class="icon_deconnexion" />';
		echo '</form>';
	}
	
	// Profil
	if ($profil == true)
	{
		echo '<a href="/insidecgi/connexion/profil.php?user=' . $_SESSION['identifiant'] . '" title="Profil" class="link_profile">';
			echo '<img src="/insidecgi/includes/profile.png" alt="profile" title="Profil" class="icon_profile" />';
		echo '</a>';
	}
	
	// Menu
	if ($menu_rg == true)
	{
		echo '<a onclick="afficherMasquer(\'menu\')" title="Menu" class="link_profile">';
			echo '<img src="/insidecgi/includes/menu.png" alt="menu" title="Menu" class="icon_profile" />';
		echo '</a>';
		
		include($_SERVER["DOCUMENT_ROOT"] . '/insidecgi/portail/referenceguide/menu_rg.php');
		
		// Récupération de l'univers en cas d'ajout d'article
		if (isset($_GET['univers']))
			$_SESSION['univers'] = $_GET['univers'];
	}

	// Ajouter un article
	if ($add_article == true)
	{
		echo '<a href="/insidecgi/portail/referenceguide/saisie_article.php" title="Ajouter un article" class="link_profile">';
			echo '<img src="/insidecgi/includes/add.png" alt="add" title="Ajouter un article" class="icon_profile" />';
		echo '</a>';
	}
	
	// Ajouter un film (avancé)
	if ($add_film == true)
	{
		echo '<a href="/insidecgi/portail/moviehouse/saisie_film_plus.php" title="Ajouter un film (avancé)" class="link_profile">';
			echo '<img src="/insidecgi/includes/add.png" alt="add" title="Ajouter un film (avancé)" class="icon_profile" />';
		echo '</a>';
	}
	
	// Modifier les détails
	if ($modify_film == true)
	{
		echo '<a href="/insidecgi/portail/moviehouse/saisie_film_plus.php?modify_id=' . $_GET['id_film'] . '" title="Modifier les détails" class="link_profile">';
			echo '<img src="/insidecgi/includes/edit.png" alt="profile" title="Modifier les détails" class="icon_profile" />';
		echo '</a>';
	}
	
	// Retour à l'accueil
	if ($back_index == true)
	{
		echo '<a href="/insidecgi/index.php" class="link_profile" title="Retour à l\'accueil" style="margin-top: 0;">';
			echo '<img src="/insidecgi/includes/back.png" alt="profile" title="Retour à l\'accueil" class="icon_profile" />';
		echo '</a>';	
	}
	
	// Retour au portail
	if ($back == true)
	{
		echo '<a href="/insidecgi/portail/portail.php" title="Retour au portail" class="link_profile">';
			echo '<img src="/insidecgi/includes/back.png" alt="back" title="Retour au portail" class="icon_profile" />';
		echo '</a>';
	}
	
	// Retour au portail administration
	if ($back_admin == true)
	{
		echo '<a href="/insidecgi/administration/administration.php" title="Retour au portail administration" class="link_profile">';
			echo '<img src="/insidecgi/includes/back.png" alt="back" title="Retour au portail administration" class="icon_profile" />';
		echo '</a>';
	}	
	
	// Boite à idées
	if ($ideas == true)
	{
		echo '<a href="/insidecgi/portail/ideas.php?view=inprogress" title="&#35;TheBox" class="link_profile">';
			echo '<img src="/insidecgi/includes/ideas.png" alt="ideas" title="&#35;TheBox" class="icon_profile" />';
		echo '</a>';
	}		
	
	// Signaler un bug
	if ($bug == true)
	{
		echo '<a href="/insidecgi/portail/bug.php" title="Signaler un bug" class="link_profile">';
			echo '<img src="/insidecgi/includes/bug.png" alt="bug" title="Signaler un bug" class="icon_profile" />';
		echo '</a>';
	}		
?>