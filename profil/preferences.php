<?php
	session_start();

	if (isset($_POST['saisie_preferences']))
	{
		$_SESSION['preferences_updated'] = false;
		include('../includes/appel_bdd.php');

		// Préférences MOVIE HOUSE
		$view_movie_house = $_POST['movie_house_view'];

		$categories_home = "";

		if (isset($_POST['films_waited']))
			$categories_home .= "Y";
		else
			$categories_home .= "N";

		if (isset($_POST['films_way_out']))
			$categories_home .= "Y";
		else
			$categories_home .= "N";

		if (isset($_POST['affiche_date']))
			$today_movie_house = "Y";
		else
			$today_movie_house = "N";

		// Préférences #THEBOX
		$view_the_box = $_POST['the_box_view'];

		// Mise à jour de la table des préférences utilisateur
		$req = $bdd->prepare('UPDATE preferences SET view_movie_house  = :view_movie_house,
																								 categories_home   = :categories_home,
																								 today_movie_house = :today_movie_house,
																								 view_the_box      = :view_the_box
																					 WHERE identifiant = "' . $_SESSION['identifiant'] . '"');
		$req->execute(array(
			'view_movie_house'  => $view_movie_house,
			'categories_home'   => $categories_home,
			'today_movie_house' => $today_movie_house,
			'view_the_box'      => $view_the_box
		));
		$req->closeCursor();

		// Mise à jour des préférences stockées en SESSION
		$_SESSION['view_movie_house']  = $view_movie_house;
		$_SESSION['categories_home']   = $categories_home;
		$_SESSION['today_movie_house'] = $today_movie_house;
		$_SESSION['view_the_box']      = $view_the_box;

		//Redirection vers le profil
		$_SESSION['preferences_updated'] = true;
		header('location: profil.php?user=' . $_SESSION['identifiant']);
	}
?>
