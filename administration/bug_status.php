<?php
	if (isset($_POST['resolve_bug']) or isset($_POST['unresolve_bug']))
	{
		include('../includes/appel_bdd.php');
		
		if (isset($_POST['resolve_bug']))
		{
			$resolved = "Y";	
		}
		elseif (isset($_POST['unresolve_bug']))
		{
			$resolved = "N";
		}
		
		$req = $bdd->prepare('UPDATE bugs SET resolved=:resolved WHERE id=' . $_GET['id']);
		$req->execute(array(
			'resolved' => $resolved
		));
		$req->closeCursor();
		
		header('location: reports.php?view=' . $_GET['view']);
	}
?>