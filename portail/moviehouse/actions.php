<?php
	session_start();
	include ('../../includes/appel_bdd.php');
	
	if (isset($_POST['not_interested']))
	{
		$id_film = $_GET['id_film'];
		$identifiant = $_SESSION['identifiant'];
		
		// Suppression de la table
		$req = $bdd->exec('DELETE FROM movie_house_users WHERE id_film=' . $id_film . ' AND identifiant="' . $identifiant . '"');
	}
	elseif(isset($_POST['participate']))
	{
		$id_film = $_GET['id_film'];
		
		// Lecture de l'état de la participation
		$req = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = ' . $id_film . ' AND identifiant = "' . $_SESSION['identifiant'] . '"');
		$data = $req->fetch();
		
		$participate = $data['participate'];
		
		$req->closeCursor();
		
		// Inversion de la participation
		if ($participate == "N")
			$participate = "Y";
		else
			$participate = "N";
		
		// Mise à jour
		$req2 = $bdd->prepare('UPDATE movie_house_users SET participate = :participate WHERE id_film = ' . $id_film . ' AND identifiant = "' . $_SESSION['identifiant'] . '"');
		$req2->execute(array(
			'participate' => $participate
		));
		$req2->closeCursor();
	}
	elseif(isset($_POST['seen']))
	{
		$id_film = $_GET['id_film'];
		
		// Lecture de l'état de la vue
		$req = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = ' . $id_film . ' AND identifiant = "' . $_SESSION['identifiant'] . '"');
		$data = $req->fetch();
		
		$seen = $data['seen'];
		
		$req->closeCursor();
		
		// Inversion de la vue
		if ($seen == "N")
			$seen = "Y";
		else
			$seen = "N";
		
		// Mise à jour
		$req2 = $bdd->prepare('UPDATE movie_house_users SET seen = :seen WHERE id_film = ' . $id_film . ' AND identifiant = "' . $_SESSION['identifiant'] . '"');
		$req2->execute(array(
			'seen' => $seen
		));
		$req2->closeCursor();
	}
	
	// Redirection
	if (isset($_GET['view']) AND isset($_GET['year']))
		header('location: ../moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year']);
	else
		header('location: details_film.php?id_film=' . $id_film);
?>