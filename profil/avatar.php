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
		$_SESSION['avatar_changed'] = false;
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
			$file      = $_FILES['avatar']['name'];
			$tmp_file  = $_FILES['avatar']['tmp_name'];
			$maxsize   = 8388608;
			$size_file = $_FILES['avatar']['size'];

			if ($size_file > $maxsize)
			{
				header('location: profil.php?user=' . $_SESSION['identifiant']);
			}
			else
			{
				// Contrôle fichier temporaire existant
				if (!is_uploaded_file($tmp_file))
				{
					exit("Le fichier est introuvable");
				}

				// Contrôle type de fichier
				$type_file = $_FILES['avatar']['type'];

				if (!strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp') && !strstr($type_file, 'gif') && !strstr($type_file, 'png'))
				{
					exit("Le fichier n'est pas une image valide");
				}
				else
				{
					$type_image = pathinfo($file, PATHINFO_EXTENSION);
					$new_name   = $avatar . '.' . $type_image;
				}

				// Contrôle upload (si tout est bon, l'image est envoyée)
				if (!move_uploaded_file($tmp_file, $avatar_dir . $new_name))
				{
					exit("Impossible de copier le fichier dans $avatar_dir");
				}

				// Créé une miniature de la source vers la destination en la rognant avec une hauteur/largeur max de 200px (cf fonction imagethumb.php)
				imagethumb($avatar_dir . $new_name, $avatar_dir . $new_name, 200, FALSE, TRUE);

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

				$_SESSION['avatar_changed'] = true;

				// Redirection
				header('location: profil.php?user=' . $_SESSION['identifiant']);
			}
		}
	}
	elseif (isset($_POST['delete_avatar']))
	{
		$_SESSION['avatar_deleted'] = false;

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

		$_SESSION['avatar_deleted'] = true;

		// Redirection
		header('location: profil.php?user=' . $_SESSION['identifiant']);
	}
?>
