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
Si elle existe et qu'on n'est pas à la fin du texte, on extrait cette image, puis on découpe le texte à scanner pour qu'il commence à la fin de l'image 
que l'on vient d'extraire.
On stocke les images dans un tableau.
On recommence jusqu'à la fin du texte.

Même principe pour les liens.
-->

<?php
	session_start();
	
	if (isset ($_POST['validate_text']))
	{
		if (isset($_POST['contenu']))
		{
			// Initialisation tableaux à vide
			$tableau_images = array();
			$tableau_liens  = array();
			
			// Initialisation des variables
			$texte_a_scanner = "";
			$longueur_totale = "";
			$debut_image     = "";
			$fin_image       = "";
			$longueur        = "";
			$debut_lien      = "";
			$fin_lien        = "";
			
			/*****************************************/
			/*** Recherche des balises à remplacer ***/
			/*****************************************/
			
			// On remplace les balises simples
			$search          = array('<rouge>',
									 '</rouge>',
									 '<vert>',
									 '</vert>',
									 '<gras>',
									 '</gras>',
									 '<italique>',
									 '</italique>',
									 '<souligne>',
									 '</souligne>',
									 '<surligne>',
									 '</surligne>'
									 );
			$replace         = array('<span class="rouge">', 
									 '</span>',
									 '<span class="vert">', 
									 '</span>',
									 '<span class="gras">', 
									 '</span>',
									 '<span class="italique">', 
									 '</span>',
									 '<span class="underline">', 
									 '</span>',
									 '<span class="highlight">', 
									 '</span>'
									 );
			$contenu_balises = str_replace($search, $replace, $_POST['contenu']);

			// Sauvegarde des données en cas de retour
			$_SESSION['title_article']    = $_POST['title'];
			$_SESSION['category_article'] = $_POST['category'];
			$_SESSION['save_content_article']  = $_POST['contenu'];
			$_SESSION['content_article']  = $contenu_balises;
			
			/****************************/
			/*** Recherche des images ***/
			/****************************/
			
			// Zone de texte à scanner pour chercher d'éventuelles images
			$texte_a_scanner = $contenu_balises;
			
			/*echo 'Texte à scanner : ' . htmlspecialchars($texte_a_scanner) . '<br /><br />';*/
			
			// Longueur totale de la zone à scanner
			$longueur_totale = strlen($texte_a_scanner);
			
			//echo 'longueur totale : ' . $longueur_totale;
			
			// Position de début et de fin de l'expression initiale
			$debut_image     = strpos($texte_a_scanner, '<image>');
			$fin_image       = strpos($texte_a_scanner, '</image>');
			
			// On récupère la longueur + 8 car la fonction mesure jusqu'au dernier caractère avant la chaîne recherchée
			if (is_numeric($debut_image) AND is_numeric($fin_image))	
			{		
				// Initialisation du tableau des images à récupérer
				$i = 1;
				
				while ($fin_image < $longueur_totale AND is_numeric($debut_image) AND is_numeric($fin_image))
				{
					$longueur = $fin_image - $debut_image + 8;
				
					// Fonction qui récupère l'extrait jusqu'à l'espace préalablement cherché
					$image_trouvee = substr($texte_a_scanner, $debut_image, $longueur);
					
					/*echo 'Longueur totale               : ' . $longueur_totale . '<br />';
					echo 'Position début image          : ' . $debut_image . '<br />';
					echo 'Position fin image            : ' . $fin_image . '<br />';
					echo 'Longueur chaîne extraite      : ' . $longueur . '<br />';
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
					
					// On stocke l'image dans un tableau et on incrémente sa position
					$tableau_images[$i] = htmlspecialchars($image_trouvee);
					$i++;
				}
			}
			
			// On teste si le tableau d'images existe
			if (!empty($tableau_images) AND isset($tableau_images) AND is_array($tableau_images))
			{
				$_SESSION['tableau_images'] = $tableau_images;
				
				// On affiche le tableau d'images
				/*$j = 1;
				foreach ($tableau_images as $ligne)
				{
					echo 'tab ' . $j . ' : ' . $ligne . '<br />';
					echo 'ses ' . $j . ' : ' . $_SESSION['tableau_images'][$j] . '<br />';
					$j++;
				}*/
				
				// Indicateur présence d'images
				$_SESSION['top_images'] = true;
			}
			else
			{
				/*echo 'Pas d\'images...';*/
				
				// Indicateur absence d'images
				$_SESSION['top_images'] = false;		
			}
			
			/****************************/
			/*** Recherche des liens ***/
			/****************************/
			
			// Zone de texte à scanner pour chercher d'éventuels liens
			$texte_a_scanner = $contenu_balises;
			
			// Longueur totale de la zone à scanner
			$longueur_totale = strlen($texte_a_scanner);
			
			// Position de début et de fin de l'expression initiale
			$debut_lien      = strpos($texte_a_scanner, '<lien>');
			$fin_lien        = strpos($texte_a_scanner, '</lien>');
			
			// On récupère la longueur + 7 car la fonction mesure jusqu'au dernier caractère avant la chaîne recherchée
			if (is_numeric($debut_lien) AND is_numeric($fin_lien))	
			{						
				// Initialisation du tableau des liens à récupérer
				$i = 1;
				
				while ($fin_lien < $longueur_totale AND is_numeric($debut_lien) AND is_numeric($fin_lien))
				{
					$longueur = $fin_lien - $debut_lien + 7;
					
					// Fonction qui récupère l'extrait jusqu'à l'espace préalablement cherché
					$lien_trouve = substr($texte_a_scanner, $debut_lien, $longueur);
					
					// On rogne le texte à scanner pour retirer le premier lien et tout ce qu'il y a avant (offset à $fin_lien + 7)
					$texte_a_scanner = substr($texte_a_scanner, $fin_lien + 7);
					
					// On calcule la longueur de la nouvelle chaîne
					$longueur_totale = strlen($texte_a_scanner);
					
					// On recherche le début et la fin du premier lien de la nouvelle chaîne
					$debut_lien      = strpos($texte_a_scanner, '<lien>');
					$fin_lien        = strpos($texte_a_scanner, '</lien>');
					
					// On stocke l'image dans un tableau et on incrémente sa position
					$tableau_liens[$i] = htmlspecialchars($lien_trouve);
					$i++;
				}
			}
			
			// On teste si le tableau de liens existe
			if (!empty($tableau_liens) AND isset($tableau_liens) AND is_array($tableau_liens))
			{
				$_SESSION['tableau_liens'] = $tableau_liens;
				
				// Indicateur présence de liens
				$_SESSION['top_liens'] = true;
			}
			else
			{		
				// Indicateur absence de liens
				$_SESSION['top_liens'] = false;		
			}

			/*******************/
			/*** Redirection ***/
			/*******************/
			
			if ($_SESSION['top_images'] == false AND $_SESSION['top_liens'] == false)
			{
				header('location: previsu.php');
			}
			else
			{
				header('location: saisie_medias.php');
			}
		}
	}
?>