<?php
	//////////////////////////////////////
	// Paramétrage des boutons d'action //
	//////////////////////////////////////

	// Initialisations
	if (!isset($ajouter_parcours))
		$ajouter_parcours = false;

	if (!isset($modify_parcours))
		$modify_parcours = false;

	if (!isset($modify_success))
		$modify_success = false;

	echo '<div class="menu_aside_hidden">';
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
			echo '<a href="/inside/administration/success/success.php?action=goModifier" title="Modifier les succès" class="link_aside">';
				echo '<img src="/inside/includes/icons/common/edit.png" alt="edit" title="Modifier les succès" class="icon_aside" />';
			echo '</a>';
		}
	echo '</div>';

	echo '<div id="menuLateral" class="menu_aside_visible" title="Menu">';
		echo '<div class="logos_menu">';
			echo '<img src="/inside/includes/icons/common/menu_m.png" alt="menu" id="icon_menu_m" class="icon_menu_aside" />';
			echo '<img src="/inside/includes/icons/common/menu_e.png" alt="menu" id="icon_menu_e" class="icon_menu_aside" style="opacity: 0;" />';
			echo '<img src="/inside/includes/icons/common/menu_n.png" alt="menu" id="icon_menu_n" class="icon_menu_aside" style="opacity: 0;" />';
			echo '<img src="/inside/includes/icons/common/menu_u.png" alt="menu" id="icon_menu_u" class="icon_menu_aside" style="opacity: 0;" />';
		echo '</div>';
	echo '</div>';
?>
