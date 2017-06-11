<?php
	session_start();

	if (isset($_POST['not_interested']))
	{
		include ('../../includes/appel_bdd.php');

		$id_film = $_GET['id_film'];
		$identifiant = $_SESSION['identifiant'];
		
		// Suppression de la table
		$req = $bdd->exec('DELETE FROM movie_house_users WHERE id_film=' . $id_film . ' AND identifiant="' . $identifiant . '"');
		
		// Redirection
		if (isset($_GET['view']) AND isset($_GET['year']))
			header('location: ../moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year']);
		else
			header('location: details_film.php?id_film=' . $id_film);
	}
?>