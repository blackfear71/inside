<?php
	//////////////////////////////////////////////////////////////////
	// Fonction de génération d'une chaîne de caractères aléatoires //
	//////////////////////////////////////////////////////////////////
	function random_string($car) 
	{
		$string = "";
		$chaine = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		
		srand((double)microtime()*1000000);
		
		for($i=0; $i < $car; $i++) 
		{
			$string .= $chaine[rand()%strlen($chaine)];
		}
		
		return $string;
	}
?>

<?php
	session_start();
	
	include('../includes/appel_bdd.php');
	
	if (isset($_POST['annuler_reinitialisation']))
	{
		// Mise à jour de la table (remise à N de l'indicateur de demande)
		$reset = "N";

		$req = $bdd->prepare('UPDATE users SET reset = :reset WHERE id = ' . $_GET['id_user']);
		$req->execute(array(
			'reset' => $reset
		));
		$req->closeCursor();
	}
	elseif (isset($_POST['reinitialiser']))
	{
		// Mise à jour de la table (remise à N de l'indicateur de demande et du mot de passe)
		$reset = "N";
		$salt = rand();
	
		// On génère un nouveau mot de passe aléatoire
		$chaine = random_string(10);
		$mot_de_passe = htmlspecialchars(hash('sha1', $chaine . $salt));
		
		$req = $bdd->prepare('UPDATE users SET salt = :salt, mot_de_passe = :mot_de_passe, reset = :reset WHERE id = ' . $_GET['id_user']);
		$req->execute(array(
			'salt' => $salt,
			'mot_de_passe' => $mot_de_passe,
			'reset' => $reset
		));
		$req->closeCursor();
		
		$reponse = $bdd->query('SELECT id, identifiant, full_name FROM users WHERE id = ' . $_GET['id_user']);
		$donnees = $reponse->fetch();
		
		$_SESSION['user_ask_id'] = $donnees['identifiant'];
		$_SESSION['user_ask_name'] = $donnees['full_name'];
		$_SESSION['new_password'] = $chaine;
		
		$reponse->closeCursor();
	}
	
	header('location: manage_users.php');
?>