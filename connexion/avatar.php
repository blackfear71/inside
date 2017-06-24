<?php 
	session_start();

	// Appel de la BDD
	include("../includes/appel_bdd.php");
	include("../includes/imagethumb.php");
	
	// On contrôle la présence du dossier, sinon on le créé
	$dossier = "avatars";
	
	if (!is_dir($dossier))
	{
	   mkdir($dossier);
	}
	
	// On ajoute une entrée dans la table en appuyant sur le bouton Poster
	if (isset($_POST['post_avatar']))
	{
		$avatar = rand();

		// Exception si on ne met pas d'image
		if ($_FILES['avatar']['name'] == NULL)
		{
			header('location: profil.php?user=' . $_SESSION['identifiant']);
		}
		else
		{
			// Dossier de destination
			$avatar_dir = 'avatars/';
				
			// Données du fichier
			$tmp_file = $_FILES['avatar']['tmp_name'];
			$maxsize = 8388608;
			$size_file = $_FILES['avatar']['size'];
			
			if ($size_file > $maxsize)
			{
				header('location: profil.php?user=' . $_SESSION['identifiant']);
			}
			else
			{			
				if (!is_uploaded_file($tmp_file))
				{
					exit("Le fichier est introuvable");
				}	
				
				$type_file = $_FILES['avatar']['type'];
				
				if (!strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp') && !strstr($type_file, 'gif') && !strstr($type_file, 'png'))
				{
					exit("Le fichier n'est pas une image valide");
				}
				else
				{
					$type_image = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
					$new_name   = $avatar . '.' . $type_image;
				}
				
				if (!move_uploaded_file($tmp_file, $avatar_dir . $new_name))
				{
					exit("Impossible de copier le fichier dans $avatar_dir");
				}

				// Créé une miniature de la source vers la destination avec une hauteur/largeur max de 120px (cf fonction imagethumb.php)
				imagethumb($avatar_dir . $new_name, $avatar_dir . $new_name, 200);

				echo "Le fichier a bien été uploadé";
				
				// On efface l'ancien avatar si présent
				$req1 = $bdd->query('SELECT identifiant, avatar FROM users WHERE identifiant="' . $_SESSION['identifiant'] . '"');
				$data1 = $req1->fetch();

				if (isset($data1['avatar']) AND !empty($data1['avatar']))
					unlink ("avatars/" . $data1['avatar'] . "");

				$req1->closeCursor();

				// On stocke la référence du nouvel avatar dans la base
				$req2 = $bdd->prepare('UPDATE users SET avatar = :avatar WHERE identifiant = "' . $_SESSION['identifiant'] . '"');
				$req2->execute(array(
					'avatar' => $new_name
				));
				$req2->closeCursor();

				// Redirection
				header('location: profil.php?user=' . $_SESSION['identifiant']);
			}
		}
	}
	elseif (isset($_POST['delete_avatar']))
	{
		// On efface l'ancien avatar si présent
		$req1 = $bdd->query('SELECT identifiant, avatar FROM users WHERE identifiant="' . $_SESSION['identifiant'] . '"');
		$data1 = $req1->fetch();

		if (isset($data1['avatar']) AND !empty($data1['avatar']))
			unlink ("avatars/" . $data1['avatar'] . "");

		$req1->closeCursor();
		
		// On efface la référence de l'ancien avatar dans la base
		$new_name = "";
		
		$req2 = $bdd->prepare('UPDATE users SET avatar = :avatar WHERE identifiant = "' . $_SESSION['identifiant'] . '"');
		$req2->execute(array(
			'avatar' => $new_name
		));
		$req2->closeCursor();
		
		// Redirection
		header('location: profil.php?user=' . $_SESSION['identifiant']);
	}
?>