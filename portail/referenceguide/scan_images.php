<!-- Notes sur cette fonction -->

<!--
Description des différents fonction PHP utilisées :
***************************************************
	- strlen (string texte_à_mesurer) : calcule la taille d'une chaîne
	- strpos (string texte_à_scanner, 'texte_recherché'[, int offset]) : recherche la position (numérique) du texte recherché dans le texte à scanner
	- substr (string texte_à_remplacer, int position_début[, int longueur]) : récupère une chaîne de caractères
	
Fonctionnement :
****************
On détermine le texte à scanner, la longueur et la position de début et de fin de la première image.
Si elle existe et qu'on n'est pas à la fin du texte, on extrait cette image, puis on découpe le texte depuis le début jusqu'à la fin de l'image 
pour qu'il commence à la fin de l'image que l'on vient d'extraire.
On stocke le début du texte dans une variable et on recommence en concaténant à la suite de cette variable jusqu'à reconstruire tout le texte avec les bonnes balises.
-->

<?php
	// Initialisation nouveau texte à vide
	$i = 1;
	$nouveau_texte = "";
	
	/****************************************/
	/*** Recherche des images à restituer ***/
	/****************************************/
		
	if ($_SESSION['top_images'] == true)
	{
		// Zone de texte à scanner pour chercher d'éventuelles images
		$texte_a_scanner = $_SESSION['content_article'];
				
		/*echo 'Texte à scanner : ' . htmlspecialchars($texte_a_scanner) . '<br /><br />';*/
				
		// Longueur totale de la zone à scanner
		$longueur_totale = strlen($texte_a_scanner);			
				
		/*echo 'Longueur totale : ' . $longueur_totale . '<br /><br />';*/	
				
		// Position de début et de fin de l'expression initiale
		$debut_image     = strpos($texte_a_scanner, '<image>');
		$fin_image       = strpos($texte_a_scanner, '</image>');			
		
		/*echo 'Position du début de la 1ère image  : ' . $debut_image . '<br />';
		echo 'Position de la fin de la 1ère image : ' . $fin_image . '<br />';*/
		
		// On récupère la longueur + 8 car la fonction mesure jusqu'au dernier caractère avant la chaîne recherchée
		if (is_numeric($debut_image) AND is_numeric($fin_image))	
		{				
			while ($fin_image < $longueur_totale AND is_numeric($debut_image) AND is_numeric($fin_image))
			{			
				// On copie ce qu'il y a avant l'image (pour éviter de passer dans le htmlspecialchars)
				$nouveau_texte = $nouveau_texte . substr($texte_a_scanner, 0, $debut_image);
				
				// On calcule la longueur entre les balises qui délimitent l'image recherchée
				$longueur = $fin_image - $debut_image + 8;

				// Fonction qui récupère l'extrait jusqu'à l'espace préalablement cherché (du début à la position de fin de l'image)
				$image_trouvee = substr($texte_a_scanner, $debut_image, $longueur);
				
				/*echo 'Longueur totale               : ' . $longueur_totale . '<br />';
				echo 'Position début image          : ' . $debut_image . '<br />';
				echo 'Position fin image            : ' . $fin_image . '<br />';
				echo 'Chaîne extraite               : ' . htmlspecialchars($image_trouvee) . '<br />';*/

				// On rogne le texte à scanner pour retirer la première image et tout ce qu'il y a avant (offset à $fin_image + 8)
				$texte_a_scanner = substr($texte_a_scanner, $fin_image + 8);
						
				// On calcule la longueur de la nouvelle chaîne
				$longueur_totale = strlen($texte_a_scanner);
						
				// On recherche le début et la fin de la première image de la nouvelle chaîne
				$debut_image     = strpos($texte_a_scanner, '<image>');
				$fin_image       = strpos($texte_a_scanner, '</image>');
						
				/*echo 'Nouvelle chaîne à scanner     : ' . htmlspecialchars($texte_a_scanner) . '<br />';
				echo 'Nouvelle longueur totale      : ' . $longueur_totale . '<br />';
				echo 'Nouvelle position début image : ' . $debut_image . '<br />';
				echo 'Nouvelle position fin image   : ' . $fin_image . '<br /><br /><br />';*/
						
				// On remplace les balises <image> par les vraies balises qui affichent une image
				$search1  = htmlspecialchars('<image>');
				$search2  = htmlspecialchars('</image>');
				$replace1 = '<a href="temp/' . $_SESSION['tableau_noms_images'][$i] . '" target="_blank"><img src="temp/' . $_SESSION['tableau_noms_images'][$i] . '" alt="' . $_SESSION['tableau_noms_images'][$i] . '" title="';
				$replace2 =  '" class="img_article" /></a>';
				$search  = array($search1, $search2);
				$replace = array($replace1, $replace2);
				
				$nouvelle_image_trouvee = str_replace($search, $replace, htmlspecialchars($image_trouvee));
				
				// On stocke l'image dans un tableau et on incrémente sa position
				$nouveau_texte = $nouveau_texte . $nouvelle_image_trouvee;	

				$i++;
			}

			// Si on est arrivé à la dernière image, on a pris tout ce qu'il y avait avant, il reste à récupérer la fin, c'est-à-dire le dernier morceau de texte découpé dans la boucle while précédente
			if ($fin_image > $longueur_totale OR !is_numeric($debut_image) OR !is_numeric($fin_image))
			{
				$nouveau_texte = $nouveau_texte . $texte_a_scanner;
			}
			
			/*echo 'Nouveau texte : ' . $nouveau_texte;*/
		}	
	}

	/***************************************/
	/*** Recherche des liens à restituer ***/
	/***************************************/
	
	if ($_SESSION['top_liens'] == true)
	{
		// Zone de texte à scanner pour chercher d'éventuels liens
		if ($_SESSION['top_images'] == true)
		{
			$texte_a_scanner = $nouveau_texte;
			
			// On vide bien le nouveau texte pour éviter de dupliquer l'article
			$nouveau_texte = "";
			$i = 1;
		}
		else
		{
			$texte_a_scanner = $_SESSION['content_article'];
		}
				
		// Longueur totale de la zone à scanner
		$longueur_totale = strlen($texte_a_scanner);			
				
		// Position de début et de fin de l'expression initiale
		$debut_lien      = strpos($texte_a_scanner, '<lien>');
		$fin_lien        = strpos($texte_a_scanner, '</lien>');			
		
		// On récupère la longueur + 7 car la fonction mesure jusqu'au dernier caractère avant la chaîne recherchée
		if (is_numeric($debut_lien) AND is_numeric($fin_lien))	
		{				
			while ($fin_lien < $longueur_totale AND is_numeric($debut_lien) AND is_numeric($fin_lien))
			{			
				// On copie ce qu'il y a avant le lien (pour éviter de passer dans le htmlspecialchars)
				$nouveau_texte = $nouveau_texte . substr($texte_a_scanner, 0, $debut_lien);
				
				// On calcule la longueur entre les balises qui délimitent le lien recherché
				$longueur = $fin_lien - $debut_lien + 7;

				// Fonction qui récupère l'extrait jusqu'à l'espace préalablement cherché (du début à la position de fin du lien)
				$lien_trouve = substr($texte_a_scanner, $debut_lien, $longueur);

				// On rogne le texte à scanner pour retirer le premier lien et tout ce qu'il y a avant (offset à $fin_lien + 7)
				$texte_a_scanner = substr($texte_a_scanner, $fin_lien + 7);
						
				// On calcule la longueur de la nouvelle chaîne
				$longueur_totale = strlen($texte_a_scanner);
						
				// On recherche le début et la fin du premier lien de la nouvelle chaîne
				$debut_lien      = strpos($texte_a_scanner, '<lien>');
				$fin_lien        = strpos($texte_a_scanner, '</lien>');
						
				// On remplace les balises <lien> par les vraies balises qui affichent un lien
				$search       = array(htmlspecialchars('<lien>'), htmlspecialchars('</lien>'));
				$replace      = array('', '');
				$nouveau_lien = str_replace($search, $replace, htmlspecialchars($lien_trouve));
				
				// On remplace les liens contenus entre les balises <lien></lien> par un vrai lien et l'adresse déterminée juste au-dessus
				$search = $_SESSION['tableau_liens'][$i];
				$replace = '<a href="' . $nouveau_lien . '" target="_blank">' . $_SESSION['tableau_noms_liens'][$i] . '</a>';
				
				$nouveau_lien_trouve = str_replace($search, $replace, htmlspecialchars($lien_trouve));
				
				$nouveau_texte = $nouveau_texte . $nouveau_lien_trouve;	

				$i++;
			}

			// Si on est arrivé à la dernière image, on a pris tout ce qu'il y avait avant, il reste à récupérer la fin, c'est-à-dire le dernier morceau de texte découpé dans la boucle while précédente
			if ($fin_lien > $longueur_totale OR !is_numeric($debut_lien) OR !is_numeric($fin_image))
			{
				$nouveau_texte = $nouveau_texte . $texte_a_scanner;
			}
		}
	}		
	
	/************************************/
	/*** Restitution du texte complet ***/
	/************************************/
	
	$_SESSION['nouveau_texte'] = $nouveau_texte;
?>