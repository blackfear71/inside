<?php
	session_start();
	
	// On va ajouter les images dans un dossier temporaire
	if (isset($_POST['validate_medias']) AND $_SESSION['top_images'] == true)
	{
		// Tableau de sauvegarde des noms de fichiers
		$tableau_noms_images = array();
		$j = 1;
			
		for ($i = 0; $i < count($_FILES['picture']['name']); $i++) 
		{	
			// On remplace les caractères spéciaux dans le nom de fichier en y ajoutant un nombre aléatoire
			$search    = array('ç', 'é', 'è', 'à', ' ');
			$replace   = array('c', 'e', 'e', 'a', '-');
			$nom_photo[$i] = str_replace($search, $replace, $_FILES['picture']['name'][$i]);		
			$nom_photo[$i] = rand(0, 10000) . $nom_photo[$i];		
	
			// Dossier où va être stockée l'image temporaire
			$content_dir = 'temp/';
	
			$tmp_file = $_FILES['picture']['tmp_name'];
	
			// Taille max ici et autorisée par php : 8 Mo
			$maxsize = 8388608; 
			$size_file = $_FILES['picture']['size'];
	
			// On vérifie la taille
			if ($size_file[$i] > $maxsize)
			{
				exit("Le fichier est trop gros");
			}
			else
			{		
				if(!is_uploaded_file($tmp_file[$i]))
				{
					exit("Le fichier est introuvable");
				}
				
				$type_file = $_FILES['picture']['type'];
				
				if(!strstr($type_file[$i], 'jpg') && !strstr($type_file[$i], 'jpeg') && !strstr($type_file[$i], 'bmp') && !strstr($type_file[$i], 'gif') && !strstr($type_file[$i], 'png'))
				{
					exit("Le fichier n'est pas une image valide");
				}
				
				if(!move_uploaded_file($tmp_file[$i], $content_dir . $nom_photo[$i]))
				{
					exit("Impossible de copier le fichier dans $content_dir");
				}
				
				echo "Le fichier a bien été uploadé";

				// On sauvegarde le nom du fichier dans le tableau
				$tableau_noms_images[$j] = htmlspecialchars($nom_photo[$i]);
				$j++;
			}
		}
		
		// On teste si le tableau existe
		if (!empty($tableau_noms_images) AND isset($tableau_noms_images) AND is_array($tableau_noms_images))
		{
			$_SESSION['tableau_noms_images'] = $tableau_noms_images;
				
			header('location: previsu.php');
		}
		else
			echo "Un problème a eu lieu lors de l'upload du fichier, veuillez réessayer.";
	}		
	
	// On va ajouter les liens dans une variable SESSION
	if (isset($_POST['validate_medias']) AND $_SESSION['top_liens'] == true)
	{
		// Tableau de sauvegarde des noms de liens
		$tableau_noms_liens = array();
		$j = 1;
		
		for ($i = 0; $i < count($_POST['link']); $i++) 
		{	
			$tableau_noms_liens[$j] = htmlspecialchars($_POST['link'][$i]);
			$j++;
		}
		
		$_SESSION['tableau_noms_liens'] = $tableau_noms_liens;
	}

	// Cas où on a des liens mais pas d'images
	if (isset($_POST['validate_medias']) AND $_SESSION['top_images'] == false)
	{
		header('location: previsu.php');
	}
?>