<?php
	session_start();
	
	include('../includes/appel_bdd.php');
	
	if (isset($_POST['saisie_pseudo']))
	{
		$new_pseudo = $_POST['new_pseudo'];
		
		$req = $bdd->prepare('UPDATE users SET full_name = :full_name WHERE identifiant = "' . $_SESSION['identifiant'] . '" AND full_name = "' . $_SESSION['full_name'] . '"');
		$req->execute(array(
			'full_name' => $new_pseudo
		));
		$req->closeCursor();
		
		$_SESSION['full_name'] = $new_pseudo;
	}
	
	header('location: profil.php?user=' . $_SESSION['identifiant']);
?>