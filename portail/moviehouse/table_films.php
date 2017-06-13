<?php
	include('../includes/appel_bdd.php');
	
	// Tableau vue utilisateur
	if ($_GET['view'] == "user")
	{
		echo '<table class="table_movie_house">';
			// Titres du tableau
			echo '<tr>';
				echo '<td class="table_titres" style="border: 0;"></td>';
				echo '<td class="init_table_dates">Date de sortie</td>';
				echo '<td class="init_table_dates">Fiche</td>';
				echo '<td class="init_table_dates">Bande-annonce</td>';
				echo '<td class="init_table_dates">Doodle</td>';
				echo '<td class="init_table_dates">Date proposée</td>';
				echo '<td class="init_table_dates">Vote</td>';
				echo '<td class="init_table_dates">Actions</td>';
			echo '</tr>';

			// On récupère la liste des films sur la première colonne et les autres infos sur les colonnes suivantes
			$reponse = $bdd->query('SELECT * FROM movie_house WHERE SUBSTR(date_theater,5,4)=' . $_GET['year'] . ' ORDER BY date_theater ASC, film ASC');
					
			$date_jour = date("mdY");
			$date_jour_present = false;
	
			while($donnees = $reponse->fetch())
			{
				// On affiche la date du jour
				if (date("Y") == $_GET['year'])
				{
					if ($donnees['date_theater'] > $date_jour AND $date_jour_present == false AND $_SESSION['today_movie_house'] == "Y")
					{
						echo '<tr class="ligne_tableau_movie_house">';
							echo '<td class="table_date_jour" colspan="100%">Aujourd\'hui, le ' . date("d/m/Y") . '</td>';
						echo '</tr>';
						
						$date_jour_present = true;
					}
				}
				
				// Nom du film
				echo '<tr class="ligne_tableau_movie_house">';
					echo '<td class="table_titres">';
						echo '<a href="moviehouse/details_film.php?id_film=' . $donnees['id'] . '" class="link_film">' . $donnees['film'] . '</a>';
					echo '</td>';
				
					// Date de sortie cinéma
					echo '<td class="table_dates">';
						if (!empty($donnees['date_theater']))
							echo substr($donnees['date_theater'], 2, 2) . '/' . substr($donnees['date_theater'], 0, 2) . '/' . substr($donnees['date_theater'], 4, 4);
					echo '</td>';				
				
					// Fiche du film
					echo '<td class="table_dates">';
						if (!empty($donnees['link']))
							echo '<a href="' . $donnees['link'] . '" target="_blank"><img src="moviehouse/pellicule.png" alt="pellicule" title="Fiche du film" class="logo_tableau_films" /></a>';
					echo '</td>';
					
					// Bande-annonce
					echo '<td class="table_dates">';
						if (!empty($donnees['trailer']))
							echo '<a href="' . $donnees['trailer'] . '" target="_blank"><img src="moviehouse/youtube.png" alt="youtube" title="Bande-annonce du film" class="logo_tableau_films" /></a>';
					echo '</td>';
				
					// Lien Doodle
					echo '<td class="table_dates">';
						if (!empty($donnees['doodle']))
							echo '<a href="' . $donnees['doodle'] . '" target="_blank"><img src="moviehouse/doodle.png" alt="doodle" title="Lien Doodle" class="logo_tableau_films" /></a>';
					echo '</td>';
				
					// Date de sortie proposée
					echo '<td class="table_dates">';
						if (!empty($donnees['date_doodle'])) 
							echo substr($donnees['date_doodle'], 2, 2) . '/' . substr($donnees['date_doodle'], 0, 2) . '/' . substr($donnees['date_doodle'], 4, 4);
					echo '</td>';

					// Etoiles utilisateur + couleur de participation/vue
					$reponse2 = $bdd->query('SELECT * FROM movie_house_users WHERE id_film = ' . $donnees['id'] . ' AND identifiant = "' . $_SESSION['identifiant'] . '"');
					$donnees2 = $reponse2->fetch();

					if ($donnees2['participation'] == "S")
						echo '<td class="table_users" style="background: #74cefb;">';
					elseif ($donnees2['participation'] == "P")
						echo '<td class="table_users" style="background: #91d784;">';
					else
						echo '<td class="table_users">';

						echo '<div class="stars_content">';
							echo '<form method="post" action="moviehouse/submit_stars.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&id_film=' . $donnees['id'] . '">';
								for($k = 1; $k <= $donnees2['stars']; $k++)
								{
									echo '<div class="star_five"></div>';
									echo '<input type="submit" name="star[' . $k . ']" value="" class="star_input" />';
								}
								for($k = $donnees2['stars'] + 1; $k <= 5; $k++)
								{
									echo '<div class="star_five_2"></div>';
									echo '<input type="submit" name="star[' . $k . ']" value="" class="star_input" />';
								}
							echo '</form>';	
						echo '</div>';
						
					echo '</td>';
					
					// Actions
					echo '<td class="table_dates">';
						
						if (isset($donnees2['stars']))
						{
							echo '<form method="post" action="moviehouse/actions.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&id_film=' . $donnees2['id_film'] . '" class="form_not_interested">';
								// Pas intéressé
								echo '<input type="submit" name="not_interested" value="" title="Pas intéressé" class="not_interested_2"/>';
								// Je participe								
								echo '<input type="submit" name="participate" value="" title="Je participe !" class="participate"/>';
								// J'ai vu
								echo '<input type="submit" name="seen" value="" title="J\'ai vu !" class="seen"/>';							
							echo '</form>';
						}
						
					echo '</td>';
					
					$reponse2->closeCursor();
				echo '</tr>';
			}
			
			$reponse->closeCursor();
		echo '</table>';
	}
	// Tableau vue générale
	else
	{
		echo '<table class="table_movie_house">';
						
			// On récupère la liste des utilisateurs du site sur la première ligne à partir de la 3ème colonne
			$user_choix = array();
			echo '<tr>';
				echo '<td class="table_titres" style="border: 0;"></td>';
				echo '<td class="init_table_dates">Date de sortie</td>';
				
				$reponse1 = $bdd->query('SELECT identifiant, full_name FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
				
				$nombre_users = 0;
				
				while($donnees1 = $reponse1->fetch())
				{
					echo '<td class="init_table_users">';
						echo $donnees1['full_name'];
					echo '</td>';	
										
					$nombre_users++;
					$user_choix[$nombre_users] = $donnees1['identifiant'];
				}					
								
				$reponse1->closeCursor();
			echo '</tr>';
							
			// On récupère dans un tableau les choix utilisateurs
			$choix_movies = array();
			$i = 1;
							
			$reponse2 = $bdd->query('SELECT * FROM movie_house_users ORDER BY identifiant ASC');
							
			while($donnees2 = $reponse2->fetch())
			{
				$choix_movie[$i][1] = $donnees2['id_film'];
				$choix_movie[$i][2] = $donnees2['identifiant'];
				$choix_movie[$i][3] = $donnees2['stars'];
				$choix_movie[$i][4] = $donnees2['participation'];
							
				$i++;
			}					
							
			$reponse2->closeCursor();
							
			// On récupère la liste des films sur la première colonne et la date de sortie sur la 2ème
			$reponse3 = $bdd->query('SELECT * FROM movie_house WHERE SUBSTR(date_theater,5,4)=' . $_GET['year'] . ' ORDER BY date_theater ASC, film ASC');
			
			$date_jour = date("mdY");
			$date_jour_present = false;

			while($donnees3 = $reponse3->fetch())
			{			
				// On affiche la date du jour
				if (date("Y") == $_GET['year'])
				{
					if ($donnees3['date_theater'] > $date_jour AND $date_jour_present == false AND $_SESSION['today_movie_house'] == "Y")
					{
						echo '<tr class="ligne_tableau_movie_house">';
							echo '<td class="table_date_jour" colspan="100%">Aujourd\'hui, le ' . date("d/m/Y") . '</td>';
						echo '</tr>';
						
						$date_jour_present = true;
					}
				}
				
				echo '<tr class="ligne_tableau_movie_house">';
					// Noms des films sur la 1ère colonne
					echo '<td class="table_titres">';
						echo '<a href="moviehouse/details_film.php?id_film=' . $donnees3['id'] . '" class="link_film">' . $donnees3['film'] . '</a>';
					echo '</td>';
					
					// Date de sortie des films sur la 2ème colonne
					echo '<td class="table_dates">';
						echo substr($donnees3['date_theater'], 2, 2) . '/' . substr($donnees3['date_theater'], 0, 2) . '/' . substr($donnees3['date_theater'], 4, 4);
					echo '</td>';
									
					// On parcours chaque colonne pour tester l'identifiant (colonne <=> $j)
					for ($j=1; $j <= $nombre_users; $j++)
					{		
						// On initialise comme si on n'avait pas trouvé de ligne à afficher
						$empty = true;	
						
						// On parcourt chaque ligne du tableau de chaque personne en fonction des films qu'il veut voir
						foreach ($choix_movie as $ligne)
						{
							if ($ligne[2] == $user_choix[$j] AND $ligne[1] == $donnees3['id'])
							{
								// On affiche la préférence pour le film + la couleur de participation/vue
								if ($ligne[4] == "S")
									echo '<td class="table_users" style="background: #74cefb;">';
								elseif ($ligne[4] == "P")
									echo '<td class="table_users" style="background: #91d784;">';
								else
									echo '<td class="table_users">';

									if (is_numeric($ligne[3]) AND $ligne[3] != 0)
									{
										echo '<div class="stars_content">';
											// Si le user correspond à la colonne
											if ($_SESSION['identifiant'] == $ligne[2])
											{
												echo '<form method="post" action="moviehouse/submit_stars.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&id_film=' . $donnees3['id'] . '">';
													for($k = 1; $k <= $ligne[3]; $k++)
													{
														echo '<div class="star_five"></div>';
														echo '<input type="submit" name="star[' . $k . ']" value="" class="star_input" />';
													}
													for($k = $ligne[3] + 1; $k <= 5; $k++)
													{
														echo '<div class="star_five_2"></div>';
														echo '<input type="submit" name="star[' . $k . ']" value="" class="star_input" />';
													}
												echo '</form>';
											}
											else
											{
												for($k = 1; $k <= $ligne[3]; $k++)
												{
													echo '<div class="star_five"></div>';
												}
												for($k = $ligne[3] + 1; $k <= 5; $k++)
												{
													echo '<div class="star_five_2"></div>';
												}	
											}
										echo '</div>';
									}	
									
								echo '</td>';
												
								// Si on a affiché une seule ligne, on saura qu'il ne faut pas ajouter une case vide
								$empty = false;
							}		
						}

						// Si jamais on n'a trouvé aucune ligne à afficher, l'indicateur va permettre d'afficher un case vide et de continuer sur la colonne suivante
						if ($empty == true)
						{
							// Pas de couleur sur la case car on n'a pas fait de choix
							echo '<td class="table_users">';
								echo '<div class="stars_content">';
									// Si le user correspond à la colonne ($j)
									if ($_SESSION['identifiant'] == $user_choix[$j])
									{
										echo '<form method="post" action="moviehouse/submit_stars.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&id_film=' . $donnees3['id'] . '">';
											for($k = 1; $k <= 5; $k++)
											{
												echo '<div class="star_five_2"></div>';
												echo '<input type="submit" name="star[' . $k . ']" value="" class="star_input" />';
											}	
										echo '</form>';
									}
									else
									{
										for($k = 1; $k <= 5; $k++)
										{
											echo '<div class="star_five_2"></div>';
										}
									}
								echo '</div>';
							echo '</td>';
						}
					}
				echo '</tr>';
			}					
							
			$reponse3->closeCursor();

		echo '</table>';
	}
?>