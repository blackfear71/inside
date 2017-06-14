<?php
	session_start();
	
	include ('../../includes/appel_bdd.php');
	
	// On récupère le choix utilisateur
	if (isset($_POST['star'][1]))
		$preference = 1;
	elseif (isset($_POST['star'][2]))
		$preference = 2;
	elseif (isset($_POST['star'][3]))
		$preference = 3;
	elseif (isset($_POST['star'][4]))
		$preference = 4;
	elseif (isset($_POST['star'][5]))
		$preference = 5;
	else
		$preference = 0;
	
	if (isset($_GET['id_film']) AND is_numeric($_GET['id_film']))
	{
		// On récupère le numéro du film
		$id_film = $_GET['id_film'];
		
		// Onrécupère l'identifiant de l'utilisateur
		$identifiant = $_SESSION['identifiant'];
		
		// On verifie qu'il n'existe pas déjà un choix pour ce film
		$existe = false;
		
		$req1 = $bdd->query('SELECT COUNT(id) AS existe_deja FROM movie_house_users WHERE id_film=' . $id_film . ' 
																					AND   identifiant="' . $identifiant . '"
																					ORDER BY id ASC');
			
		$data1 = $req1->fetch();
		if (is_numeric($data1['existe_deja']) AND $data1['existe_deja'] > 0)
			$existe = true;
		$req1->closeCursor();
			
		// Si trouvé alors on fait une MAJ
		if ($existe == true)
		{
			$req2 = $bdd->prepare('UPDATE movie_house_users SET stars = :stars WHERE id_film = ' . $id_film . ' AND identifiant = "' . $identifiant . '"');
			$req2->execute(array(
				'stars' => $preference
			));
			$req2->closeCursor();
		}	
		// Sinon on insère une nouvelle ligne
		else
		{
			// Initialisation de la participation
			$participation = "N";
			
			$req3 = $bdd->prepare('INSERT INTO movie_house_users(id_film, identifiant, stars, participation) VALUES(:id_film, :identifiant, :stars, :participation)');
			$req3->execute(array(
				'id_film' => $id_film,
				'identifiant' => $identifiant,
				'stars' => $preference,
				'participation' => $participation
				));
			$req3->closeCursor();
		}

		// Redirection
		if (isset($_GET['year']))
		{
			// On revient à la liste
			header('location: ../moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '#' . $_GET['id_film']);
		}
		else
		{
			// On revient au détail
			header('location: details_film.php?id_film=' . $_GET['id_film']);
		}
	}
	else
	{
		header('location: ../moviehouse.php?view=' . $_GET['view'] . '&year=' . $_GET['year']);
	}
?>