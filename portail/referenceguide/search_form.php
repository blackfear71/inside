<?php	
	if (!isset($_GET['univers']))
	{
		echo '<form method="post" action="search_tree.php" style="margin-top: -10px; padding-bottom: 20px;">';
			echo '<input type="text" name="search_content" value="' . $_SESSION['search'] . '" placeholder="Rechercher..." maxlength="255" class="monoligne_search" />';
			echo '<input type="submit" name="search" value="" title="Rechercher" class="icon_search"/>';
		echo '</form>';
	}
	else
	{
		switch ($_GET['univers'])
		{
			case "rdz":
				echo '<form method="post" action="search_tree.php?univers=' . $_GET['univers']. '">';
					echo '<input type="text" name="search_content" value="' . $_SESSION['search'] . '" placeholder="Rechercher dans RDZ..." maxlength="255" class="monoligne_search" />';
					echo '<input type="submit" name="search_rdz" value="" title="Rechercher" class="icon_search"/>';
				echo '</form>';
				break;
				
			case "tso":
				echo '<form method="post" action="search_tree.php?univers=' . $_GET['univers']. '">';
					echo '<input type="text" name="search_content" value="' . $_SESSION['search'] . '" placeholder="Rechercher dans TSO..." maxlength="255" class="monoligne_search" />';
					echo '<input type="submit" name="search_tso" value="" title="Rechercher" class="icon_search"/>';
				echo '</form>';
				break;
				
			case "ims":
				echo '<form method="post" action="search_tree.php?univers=' . $_GET['univers']. '">';
					echo '<input type="text" name="search_content" value="' . $_SESSION['search'] . '" placeholder="Rechercher dans IMS..." maxlength="255" class="monoligne_search" />';
					echo '<input type="submit" name="search_ims" value="" title="Rechercher" class="icon_search"/>';
				echo '</form>';
				break;
				
			case "micrortc":
				echo '<form method="post" action="search_tree.php?univers=' . $_GET['univers']. '">';
					echo '<input type="text" name="search_content" value="' . $_SESSION['search'] . '" placeholder="Rechercher dans Micro/RTC..." maxlength="255" class="monoligne_search" />';
					echo '<input type="submit" name="search_micrortc" value="" title="Rechercher" class="icon_search"/>';
				echo '</form>';
				break;
				
			case "portaileid":
				echo '<form method="post" action="search_tree.php?univers=' . $_GET['univers']. '">';
					echo '<input type="text" name="search_content" value="' . $_SESSION['search'] . '" placeholder="Rechercher dans Portail EID..." maxlength="255" class="monoligne_search" />';
					echo '<input type="submit" name="search_portaileid" value="" title="Rechercher" class="icon_search"/>';
				echo '</form>';
				break;
				
			case "glossaire":
				echo '<form method="post" action="search_tree.php?univers=' . $_GET['univers']. '">';
					echo '<input type="text" name="search_content" value="' . $_SESSION['search'] . '" placeholder="Rechercher dans Glossaire..." maxlength="255" class="monoligne_search" />';
					echo '<input type="submit" name="search_glossaire" value="" title="Rechercher" class="icon_search"/>';
				echo '</form>';
				break;
				
			default:
				echo '<form method="post" action="search_tree.php" style="margin-top: -10px;  padding-bottom: 20px;">';
					echo '<input type="text" name="search_content" value="' . $_SESSION['search'] . '" placeholder="Rechercher..." maxlength="255" class="monoligne_search" />';
					echo '<input type="submit" name="search" value="" title="Rechercher" class="icon_search"/>';
				echo '</form>';	
				break;
		}
	}
?>