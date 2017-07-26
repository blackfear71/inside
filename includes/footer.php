<?php
	//echo '<div class="footer_title">INSIDE&nbsp;\&nbsp;CGI</div>';
	echo '<img src="/insidecgi/includes/inside_mini.png" alt="inside" class="inside_mini" />';

	echo '<div class="footer_line"></div>';

	echo '<div class="connected_as">';
		if ($_SESSION['connected'] == true)
			echo '<span class="footer_connected">Connecté en tant que ' . $_SESSION['full_name'] . ' - &copy; 2017</span>';
		else
			echo '<span class="footer_connected">&copy; 2017</span>';
	echo '</div>';
?>
