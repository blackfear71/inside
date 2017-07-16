<?php
	session_start();

	include('../includes/appel_bdd.php');

	if (isset($_POST['annuler_suppression_film']))
	{
		// Mise à jour de la table (remise à N de l'indicateur de demande)
    $id_film = $_GET['delete_id'];
		$to_delete = "N";

		$req = $bdd->prepare('UPDATE movie_house SET to_delete = :to_delete WHERE id = ' . $id_film);
		$req->execute(array(
			'to_delete' => $to_delete
		));
		$req->closeCursor();

		$_SESSION['film_reseted'] = true;
	}
	elseif (isset($_POST['accepter_suppression_film']))
	{
    $id_film = $_GET['delete_id'];

    // Suppression des avis movie_house_users
    $req1 = $bdd->exec('DELETE FROM movie_house_users WHERE id_film = ' . $id_film);

		// SUppression des Commentaires
		$req2 = $bdd->exec('DELETE FROM movie_house_comments WHERE id_film = ' . $id_film );

    // Suppression du film
    $req3 = $bdd->exec('DELETE FROM movie_house WHERE id = ' . $id_film );

		$_SESSION['film_deleted'] = true;
	}

	header('location: manage_films.php');
?>
