<?php
	//////////////////////////////////////
	// Paramétrage des boutons d'action //
	//////////////////////////////////////

	// Initialisations
	if (!isset($disconnect))
		$disconnect = false;

	if (!isset($ajouter_parcours))
		$ajouter_parcours = false;

	if (!isset($modify_parcours))
		$modify_parcours = false;

	if (!isset($back_index))
		$back_index = false;

	if (!isset($back))
		$back = false;

	if (!isset($add_mission))
		$add_mission = false;

	if (!isset($modify_success))
		$modify_success = false;

	if (!isset($back_admin))
		$back_admin = false;

	echo '<div class="menu_aside_hidden">';
		// Retour au portail
		if ($back == true)
		{
			echo '<a href="/inside/portail/portail/portail.php?action=goConsulter" title="Retour au portail" class="link_aside">';
				echo '<img src="/inside/includes/icons/common/home.png" alt="back" title="Retour au portail" class="icon_aside" />';
			echo '</a>';
		}

		// Retour au portail administration
		if ($back_admin == true)
		{
			echo '<a href="/inside/administration/administration.php?action=goConsulter" title="Retour au portail administration" class="link_aside">';
				echo '<img src="/inside/includes/icons/common/home.png" alt="back_admin" title="Retour au portail administration" class="icon_aside" />';
			echo '</a>';
		}

		// Ajouter parcours
		if ($ajouter_parcours == true)
		{
			echo '<a href="/inside/portail/petitspedestres/parcours.php?action=goajouter" title="Ajouter parcours" class="link_aside">';
				echo '<img src="/inside/includes/icons/common/add.png" alt="add" title="Ajouter parcours" class="icon_aside" />';
			echo '</a>';
		}

		// Modifier parcours
		if ($modify_parcours == true)
		{
			echo '<a href="/inside/portail/petitspedestres/parcours.php?id=' . $_GET['id'] . '&action=gomodifier" title="Modifier les détails" class="link_aside">';
				echo '<img src="/inside/includes/icons/common/edit.png" alt="modify" title="Modifier les détails" class="icon_aside" />';
			echo '</a>';
		}

		// Modifier les succès
		if ($modify_success == true)
		{
			echo '<a href="/inside/administration/manage_success.php?action=goModifier" title="Modifier les succès" class="link_aside">';
				echo '<img src="/inside/includes/icons/common/edit.png" alt="edit" title="Modifier les succès" class="icon_aside" />';
			echo '</a>';
		}

		// Ajouter une mission
		if ($add_mission == true)
		{
			echo '<a href="/inside/administration/manage_missions.php?action=goAjouter" title="Ajouter une mission" class="link_aside">';
				echo '<img src="/inside/includes/icons/common/add.png" alt="add" title="Ajouter une mission" class="icon_aside" />';
			echo '</a>';
		}

		// Déconnexion
		if ($disconnect == true)
		{
			echo '<form method="post" action="/inside/connexion/disconnect.php" class="bouton_aside">';
				echo '<input type="submit" name="disconnect" value="" title="Déconnexion" class="icon_deconnexion" />';
			echo '</form>';
		}
	echo '</div>';

	echo '<div class="menu_aside_visible" title="Menu" onclick="deployLeftMenu(\'left_menu\', \'icon_menu_m\', \'icon_menu_e\', \'icon_menu_n\', \'icon_menu_u\');">';
		echo '<div class="logos_menu">';
			echo '<img src="/inside/includes/icons/common/menu_m.png" alt="menu" id="icon_menu_m" class="icon_menu_aside" />';
			echo '<img src="/inside/includes/icons/common/menu_e.png" alt="menu" id="icon_menu_e" class="icon_menu_aside" style="opacity: 0;" />';
			echo '<img src="/inside/includes/icons/common/menu_n.png" alt="menu" id="icon_menu_n" class="icon_menu_aside" style="opacity: 0;" />';
			echo '<img src="/inside/includes/icons/common/menu_u.png" alt="menu" id="icon_menu_u" class="icon_menu_aside" style="opacity: 0;" />';
		echo '</div>';
	echo '</div>';

	// Boutons missions
	$zone_inside = "aside";
	include($_SERVER["DOCUMENT_ROOT"] . '/inside/includes/common/missions.php');
?>
