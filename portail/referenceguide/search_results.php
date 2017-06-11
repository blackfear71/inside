<?php	
	include('../../includes/appel_bdd.php');
	
	// On récupère tout d'abord les noms correspondant aux identifiants
	$full_names = array();
	$i = 1;
	
	$req = $bdd->query('SELECT identifiant, full_name FROM users WHERE identifiant != "admin" ORDER BY id ASC');
	while ($data = $req->fetch())
	{
		$full_names[$i][1] = $data['identifiant'];
		$full_names[$i][2] = $data['full_name'];
		
		$i++;
	}
	$req->closeCursor();
	
	// On affiche les résultats de recherche
	if (isset($_GET['univers']) AND isset($_GET['search']))
	{		
		if ($_GET['search'] == "no")
		{
			// Affichage par défaut des articles de la catégorie sans recherche
			$reponse = $bdd->query('SELECT * FROM reference_guide WHERE category="' . $_GET['univers'] . '" ORDER BY publish_date DESC');
			
			echo '<table class="liste">';

				echo '<tr>';
					echo '<td class="init_titre_article">Article</td>';
					echo '<td class="init_date_article">Date de publication</td>';
					echo '<td class="init_auteur_article">Auteur</td>';
				echo '</tr>';
				
				while ($donnees = $reponse->fetch())
				{		
					// On recherche le nom correspondant à l'identifiant stocké dans le tableau précédent
					foreach ($full_names as $line)
					{
						if ($donnees['author'] == $line[1])
							$auteur = $line[2];
					}

					// On formatte la date
					$formatted_date = substr($donnees['publish_date'], 2, 2) . "/" . substr($donnees['publish_date'], 0, 2) . "/" . substr($donnees['publish_date'], 4, 4);
					
					// On affiche chaque ligne							
					echo '<tr>';
						echo '<td class="titre_article">';
							echo '<a href="article.php?id=' . $donnees['id'] . '&univers=' . $donnees['category'] . '" class="lien_article">' . $donnees['title'] . '</a>';
						echo '</td>';
						echo '<td class="date_article">';
							echo '<a href="article.php?id=' . $donnees['id'] . '&univers=' . $donnees['category'] . '" class="lien_article">' . $formatted_date . '</a>';
						echo '</td>';
						echo '<td class="auteur_article">';
							echo '<a href="article.php?id=' . $donnees['id'] . '&univers=' . $donnees['category'] . '" class="lien_article">' . $auteur . '</a>';
						echo '</td>';
					echo '</tr>';
				}
				
			echo '</table>';
			
			$reponse->closeCursor();
		}
		elseif ($_GET['search'] == "yes")
		{
			$count = 0;
			
			// Affichage des articles de la catégorie après recherche
			if (empty($_GET['univers']))
			{
			$reponse = $bdd->query('SELECT * FROM reference_guide WHERE    title        LIKE "%' . $_SESSION['search'] . '%" 
																  ORDER BY publish_date DESC');			
			}
			else
			{
			$reponse = $bdd->query('SELECT * FROM reference_guide WHERE    category="' . $_GET['univers'] . '" 
																  AND      title        LIKE "%' . $_SESSION['search'] . '%" 
																  ORDER BY publish_date DESC');
																  /*AND     (title        LIKE "%' . $_SESSION['search'] . '%" 
																  OR       author       LIKE "%' . $_SESSION['search'] . '%")
																  ORDER BY publish_date DESC');*/
			}
			
			echo '<table class="liste">';
			
				echo '<tr>';
					echo '<td class="init_titre_article">Article</td>';
					echo '<td class="init_date_article">Date de publication</td>';
					echo '<td class="init_auteur_article">Auteur</td>';
				echo '</tr>';
				
				while ($donnees = $reponse->fetch())
				{		
					// On recherche le nom correspondant à l'identifiant stocké dans le tableau précédent
					foreach ($full_names as $line)
					{
						if ($donnees['author'] == $line[1])
							$auteur = $line[2];
					}
					
					// On formatte la date
					$formatted_date = substr($donnees['publish_date'], 2, 2) . "/" . substr($donnees['publish_date'], 0, 2) . "/" . substr($donnees['publish_date'], 4, 4);
					
					// On affiche chaque ligne
					echo '<tr>';
						echo '<td class="titre_article">';
							echo '<a href="article.php?id=' . $donnees['id'] . '&univers=' . $donnees['category'] . '" class="lien_article">' . str_ireplace($_SESSION['search'], '<span class="surligne">' . $_SESSION['search'] . '</span>', $donnees['title']) . '</a>';
						echo '</td>';
						echo '<td class="date_article">';
							echo '<a href="article.php?id=' . $donnees['id'] . '&univers=' . $donnees['category'] . '" class="lien_article">' . $formatted_date . '</a>';
						echo '</td>';
						echo '<td class="auteur_article">';
							echo '<a href="article.php?id=' . $donnees['id'] . '&univers=' . $donnees['category'] . '" class="lien_article">' . str_ireplace($_SESSION['search'], '<span class="surligne">' . $_SESSION['search'] . '</span>', $auteur) . '</a>';
						echo '</td>';
					echo '</tr>';
						
					$count++;
				}
				
			echo '</table>';
			
			// Récupération de la catégorie
			$univers = "";
				
			switch ($_GET['univers'])
			{
				case "rdz":
					$univers = "RDZ";
					break;
						
				case "tso":
					$univers = "TSO";
					break;
						
				case "ims":
					$univers = "IMS";
					break;
						
				case "micrortc":
					$univers = "MICRO/RTC";
					break;
						
				case "portaileid":
					$univers = "PORTAIL EID";
					break;
						
				case "glossaire":
					$univers = "GLOSSAIRE";
					break;
						
				default:
					$univers = "SANS CATÉGORIE";
					break;
			}
			
			// S'il n'y a pas de résultats, on affiche un message, sinon on affiche le nombre de résultats
			if ($count == 0)
			{				
				/*echo '<div class="nb_results">Aucun résutat parmi les articles ou les auteurs pour la catégorie <i class="upper">' . $univers . '</i>.</div>';*/
				echo '<div class="nb_results">Aucun résutat parmi les articles pour la catégorie <i class="upper">' . $univers . '</i>.</div>';
			}
			else
			{
				if ($count != 1)
					echo '<div class="nb_results">Il y a <span class="rounded">' . $count . '</span> résultats pour vos critères dans la catégorie <i class="upper">' . $univers . '</i>.</div>';
				else
					echo '<div class="nb_results">Il y a <span class="rounded">' . $count . '</span> résultat pour vos critères dans la catégorie <i class="upper">' . $univers . '</i>.</div>';
			}
			
			$reponse->closeCursor();
		}
	}
	elseif (isset($_GET['search']) AND $_GET['search'] == "yes")
	{
		// Recherche à partir du menu principal (sans catégorie)
		$count = 0;
		
		$reponse = $bdd->query('SELECT * FROM reference_guide WHERE    title        LIKE "%' . $_SESSION['search'] . '%" 
															  ORDER BY publish_date DESC');
															  /*OR       author       LIKE "%' . $_SESSION['search'] . '%"
															  ORDER BY publish_date DESC');*/
		
		echo '<table class="liste">';

			echo '<tr>';
				echo '<td class="init_titre_article_2">Article</td>';
				echo '<td class="init_date_article">Date de publication</td>';
				echo '<td class="init_auteur_article">Auteur</td>';
				echo '<td class="init_categorie_article">Catégorie</td>';
			echo '</tr>';
				
			while ($donnees = $reponse->fetch())
			{		
				$categorie = "";
					
				switch ($donnees['category'])
				{
					case "rdz":
						$categorie = "RDZ";
						break;
							
					case "tso":
						$categorie = "TSO";
						break;
							
					case "ims":
						$categorie = "IMS";
						break;
							
					case "micrortc":
						$categorie = "Micro/RTC";
						break;
							
					case "portaileid":
						$categorie = "Portail EID";
						break;
							
					case "glossaire":
						$categorie = "Glossaire";
						break;
							
					default:
						$categorie = "Sans catégorie";
						break;
				}
					
				// On recherche le nom correspondant à l'identifiant stocké dans le tableau précédent
				foreach ($full_names as $line)
				{
					if ($donnees['author'] == $line[1])
						$auteur = $line[2];
				}
				
				// On formatte la date
				$formatted_date = substr($donnees['publish_date'], 2, 2) . "/" . substr($donnees['publish_date'], 0, 2) . "/" . substr($donnees['publish_date'], 4, 4);
				
				// On affiche chaque ligne
				echo '<tr>';
					echo '<td class="titre_article_2">';
						echo '<a href="article.php?id=' . $donnees['id'] . '&univers=' . $donnees['category'] . '" class="lien_article">' . str_ireplace($_SESSION['search'], '<span class="surligne">' . $_SESSION['search'] . '</span>', $donnees['title']) . '</a>';
					echo '</td>';
					echo '<td class="date_article">';
						echo '<a href="article.php?id=' . $donnees['id'] . '&univers=' . $donnees['category'] . '" class="lien_article">' . $formatted_date . '</a>';
					echo '</td>';
					echo '<td class="auteur_article">';
						echo '<a href="article.php?id=' . $donnees['id'] . '&univers=' . $donnees['category'] . '" class="lien_article">' . str_ireplace($_SESSION['search'], '<span class="surligne">' . $_SESSION['search'] . '</span>', $auteur) . '</a>';
					echo '</td>';
					echo '<td class="categorie_article">';
						echo '<a href="article.php?id=' . $donnees['id'] . '&univers=' . $donnees['category'] . '" class="lien_article"><div class="icon_category">' . $categorie . '</div></a>';
					echo '</td>';
				echo '</tr>';
						
				$count++;
			}
				
		echo '</table>';
			
		// S'il n'y a pas de résultats, on affiche un message, sinon on affiche le nombre de résultats
		if ($count == 0)
		{
			/*echo '<div class="nb_results">Aucun résutat parmi les articles ou les auteurs.</div>';*/
			echo '<div class="nb_results">Aucun résutat parmi les articles.</div>';
		}
		else
		{
			if ($count != 1)
				echo '<div class="nb_results">Il y a <span class="rounded">' . $count . '</span> résultats pour vos critères.</div>';
			else
				echo '<div class="nb_results">Il y a <span class="rounded">' . $count . '</span> résultat pour vos critères.</div>';
		}
		
		$reponse->closeCursor();
	}
	else
	{
		header('location: ../referenceguide.php');
	}
?>