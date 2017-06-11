<?php
	session_start();
	
	if (isset($_POST['saisie_mdp']) AND !empty($_POST['old_password']) AND !empty($_POST['new_password']) AND !empty($_POST['confirm_new_password']))
	{
		include('../includes/appel_bdd.php');
		
		$identifiant = $_SESSION['identifiant'];
		
		// Lecture des données actuelles de l'utilisateur
		$reponse = $bdd->query('SELECT id, identifiant, salt, mot_de_passe FROM users WHERE identifiant="' . $identifiant . '"');
		$donnees = $reponse->fetch();
		
		$wrong_password = false;
		
		$old_password = htmlspecialchars(hash('sha1', $_POST['old_password'] . $donnees['salt']));
		
		if ($old_password == $donnees['mot_de_passe'])
		{
			$salt = rand();
			$new_password = htmlspecialchars(hash('sha1', $_POST['new_password'] . $salt));
			$confirm_new_password = htmlspecialchars(hash('sha1', $_POST['confirm_new_password'] . $salt));
			
			if ($new_password == $confirm_new_password)
			{
				$req = $bdd->prepare('UPDATE users SET salt=:salt, mot_de_passe=:mot_de_passe WHERE identifiant="' . $identifiant . '"');
				$req->execute(array(
					'salt' => $salt,
					'mot_de_passe' => $new_password
				));
				$req->closeCursor();
				
				$wrong_password = false;
			}
			else
			{
				$wrong_password = true;
			}
		}
		else
		{
			$wrong_password = true;
		}
		
		$reponse->closeCursor();
		
		$_SESSION['wrong_password'] = $wrong_password;
		header('location: profil.php?user=' . $_SESSION['identifiant']);
	}
?>