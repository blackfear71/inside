<?php
	session_start();
	
	if (isset($_POST['saisie_preferences']))
	{
		$_SESSION['preferences_updated'] = false;
		include('../includes/appel_bdd.php');
		
		$view_movie_house = $_POST['movie_house_view'];
		
		if (isset($_POST['affiche_date']))
			$today_movie_house = "Y";
		else
			$today_movie_house = "N";
		
		// Mise à jour de la table des préférences utilisateur
		$req = $bdd->prepare('UPDATE preferences SET view_movie_house = :view_movie_house, today_movie_house = :today_movie_house WHERE identifiant = "' . $_SESSION['identifiant'] . '"');
		$req->execute(array(
			'view_movie_house' => $view_movie_house,
			'today_movie_house' => $today_movie_house
		));
		$req->closeCursor();
		
		// Mise à jour des préférences stockées en SESSION
		$_SESSION['view_movie_house'] = $view_movie_house;
		$_SESSION['today_movie_house'] = $today_movie_house;
		
		//Redirection vers le profil
		$_SESSION['preferences_updated'] = true;
		header('location: profil.php?user=' . $_SESSION['identifiant']);
	}
?>