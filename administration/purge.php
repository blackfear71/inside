<?php
	session_start();
	
	$_SESSION['purged'] = false;
	
	// On supprime tous les fichiers présents dans le dossier
	$files = glob('../portail/referenceguide/temp/*.*');
	foreach($files as $filename)
	{
		unlink($filename);
	}
	
	$_SESSION['purged'] = true;
	
	header('location: show_purge.php');
?>