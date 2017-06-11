<?php
	session_start();
	
	if (isset($_POST['ask_password']))
	{
		include('../includes/appel_bdd.php');
		
		$identifiant = $_POST['login'];
		$reset = "N";
		$_SESSION['wrong_id'] = false;
		$_SESSION['asked'] = false;
		$_SESSION['already_asked'] = false;
				
		// On vérifie que l'identifiant existe bien
		$reponse = $bdd->query('SELECT id, identifiant, reset FROM users');

		while ($donnees = $reponse->fetch())
		{
			if ($identifiant == $donnees['identifiant'])
			{
				if ($donnees['reset'] == "Y")
				{
					$_SESSION['wrong_id'] = false;
					$_SESSION['asked'] = false;
					$_SESSION['already_asked'] = true;
					break;
				}
				else
				{
					// Mise à jour de la table
					$reset = "Y";

					$req = $bdd->prepare('UPDATE users SET reset = :reset WHERE id = ' . $donnees['id']);
					$req->execute(array(
						'reset' => $reset
					));
					$req->closeCursor();

					$_SESSION['wrong_id'] = false;
					$_SESSION['asked'] = true;
					$_SESSION['already_asked'] = false;
					break;
				}
			}
			else
			{
				$_SESSION['wrong_id'] = true;
				$_SESSION['asked'] = false;
				$_SESSION['already_asked'] = false;
			}
		}
		$reponse->closeCursor();	
		
		header('location: forgot_password.php');
	}
?>