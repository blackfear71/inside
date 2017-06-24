<?php
	session_start();
	
	include("../../includes/appel_bdd.php");
	
	// On contrôle la présence du dossier images, sinon on le créé
	$dossier1 = "images";
	
	if (!is_dir($dossier1))
	{
	   mkdir($dossier1);
	}
	
	// On contrôle la présence du dossier temp, sinon on le créé
	$dossier2 = "temp";
	
	if (!is_dir($dossier2))
	{
	   mkdir($dossier2);
	}
	
	if (isset($_POST['publish_article']))
	{
		// Titre de l'article
		$title = $_SESSION['title_article'];
		
		// Date de publication
		$publish_date = date("mdY");
		
		// Auteur
		$author = $_SESSION['identifiant'];
		
		// Contenu
		$content = str_replace('temp/', 'images/', $_SESSION['nouveau_texte']);
		
		// Catégorie
		$category = $_SESSION['category_article'];
		
		/*echo 'Titre : ' . $title . '<br />';
		echo 'Date publication : ' . $publish_date . '<br />';
		echo 'Auteur : ' . $author . '<br />';
		echo 'Contenu : ' . $content . '<br />';
		echo 'Catégorie : ' . $category . '<br />';*/
		
		// Copie des images du dossier temporaire vers le dossier de stockage
		foreach($_SESSION['tableau_noms_images'] as $nom_fichier)
		{
			// On copie le fichier du dossier TEMP vers le dossier IMAGES
			copy('temp/' . $nom_fichier, 'images/' . $nom_fichier);
			
			// On supprime le fichier du dossier TEMP
			unlink('temp/' . $nom_fichier);
		}
		
		//Stockage de l'enregistrement en table		
		$req = $bdd->prepare('INSERT INTO reference_guide(title, publish_date, author, content, category) VALUES(:title, :publish_date, :author, :content, :category)');
		$req->execute(array(
			'title' => $title,
			'publish_date' => $publish_date,
			'author' => $author,
			'content' => $content,
			'category' => $category
				));
		$req->closeCursor();
		
		// On détruit toutes les variables SESSION utilisées jusque là pour remettre la création à zéro
		include('../../includes/init_session.php');
		
		// On va chercher le numéro id qui vient d'être enregistré en table et on redirige vers la page de consultation
		$id = $bdd -> lastInsertId();
		/*echo 'ID : ' . $id;*/
		
		header('location: article.php?id=' . $id . '&univers=' . $category . '');
	}
?>