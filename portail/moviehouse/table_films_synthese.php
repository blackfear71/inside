<?php
	include('../includes/appel_bdd.php');

	/************************/
	/* Tableau vue générale */
	/************************/
	echo '<table class="table_movie_house">';

		// On récupère la liste des utilisateurs du site sur la première ligne à partir de la 3ème colonne
		$user_choix = array();
		echo '<tr>';
			echo '<td class="table_titres" style="border: 0;"></td>';
			echo '<td class="init_table_dates" style="width: 120px;">Date de sortie</td>';

			$reponse1 = $bdd->query('SELECT identifiant, full_name, avatar FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');

			$nombre_users = 0;

			while($donnees1 = $reponse1->fetch())
			{
				echo '<td class="init_table_users">';
					echo '<div class="zone_avatar_films">';
						if (isset($donnees1['avatar']) AND !empty($donnees1['avatar']))
							echo '<img src="../profil/avatars/' . $donnees1['avatar'] . '" alt="avatar" title="' . $donnees1['full_name'] . '" class="avatar_films" />';
						else
							echo '<img src="../includes/icons/default.png" alt="avatar" title="' . $donnees1['full_name'] . '" class="avatar_films" />';
					echo '</div>';

					echo '<span class="full_name_films">' . $donnees1['full_name'] . '</span>';
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
		$reponse3 = $bdd->query('SELECT * FROM movie_house WHERE SUBSTR(date_theater, 1, 4)=' . $_GET['year'] . ' AND to_delete != "Y" ORDER BY date_theater ASC, film ASC');

		$date_jour = date("Ymd");
		$date_jour_present = false;
		$l = 0;

		while($donnees3 = $reponse3->fetch())
		{
			// On affiche la date du jour
			if (date("Y") == $_GET['year'])
			{
				if ($donnees3['date_theater'] >= $date_jour AND $date_jour_present == false AND $_SESSION['today_movie_house'] == "Y")
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
					echo '<a href="moviehouse/details_film.php?id_film=' . $donnees3['id'] . '" id="' . $donnees3['id'] . '" class="link_film">' . $donnees3['film'] . '</a>';
				echo '</td>';

				// Date de sortie des films sur la 2ème colonne
				echo '<td class="table_dates">';
					if (!empty($donnees3['date_theater']))
					{
						if (isBlankDate($donnees3['date_theater']))
						{
							echo 'N.C.';
						}
						else
						{
							echo formatDateForDisplay($donnees3['date_theater']);
						}
					}
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
								echo '<td class="table_users" style="background-color: #74cefb;">';
							elseif ($ligne[4] == "P")
								echo '<td class="table_users" style="background-color: #91d784;">';
							elseif ($_SESSION['identifiant'] == $ligne[2])
								echo '<td class="table_users" style="background-color: #fffde8;">';
							else
								echo '<td class="table_users">';

								if (is_numeric($ligne[3]) AND $ligne[3] != 0)
								{
									echo '<div class="stars_content">';
										// Si le user correspond à la colonne
										if ($_SESSION['identifiant'] == $ligne[2])
										{
											switch($ligne[3])
											{
												case "1":
													echo '<a onclick="afficherMasquer(\'preference[' . $l . ']\'); afficherMasquer(\'preference2[' . $l . ']\');" id="preference[' . $l . ']" title="Préférence" class="link_vote" style="margin-left: auto; margin-right: auto;">';
														echo '<img src="moviehouse/icons/stars/star1.png" alt="star1" class="new_star" />';
													echo '</a>';
													break;

												case "2":
													echo '<a onclick="afficherMasquer(\'preference[' . $l . ']\'); afficherMasquer(\'preference2[' . $l . ']\');" id="preference[' . $l . ']" title="Préférence" class="link_vote" style="margin-left: auto; margin-right: auto;">';
														echo '<img src="moviehouse/icons/stars/star2.png" alt="star2" class="new_star" />';
													echo '</a>';
													break;

												case "3":
													echo '<a onclick="afficherMasquer(\'preference[' . $l . ']\'); afficherMasquer(\'preference2[' . $l . ']\');" id="preference[' . $l . ']" title="Préférence" class="link_vote" style="margin-left: auto; margin-right: auto;">';
														echo '<img src="moviehouse/icons/stars/star3.png" alt="star3" class="new_star" />';
													echo '</a>';
													break;

												case "4":
													echo '<a onclick="afficherMasquer(\'preference[' . $l . ']\'); afficherMasquer(\'preference2[' . $l . ']\');" id="preference[' . $l . ']" title="Préférence" class="link_vote" style="margin-left: auto; margin-right: auto;">';
														echo '<img src="moviehouse/icons/stars/star4.png" alt="star4" class="new_star" />';
													echo '</a>';
													break;

												case "5":
													echo '<a onclick="afficherMasquer(\'preference[' . $l . ']\'); afficherMasquer(\'preference2[' . $l . ']\');" id="preference[' . $l . ']" title="Préférence" class="link_vote" style="margin-left: auto; margin-right: auto;">';
														echo '<img src="moviehouse/icons/stars/star5.png" alt="star5" class="new_star" />';
													echo '</a>';
													break;

												default:
													echo '<a onclick="afficherMasquer(\'preference[' . $l . ']\'); afficherMasquer(\'preference2[' . $l . ']\');" id="preference[' . $l . ']" title="Préférence" class="link_vote" style="margin-left: auto; margin-right: auto;">';
														echo '<img src="moviehouse/icons/stars/star0.png" alt="star0" class="new_star" />';
													echo '</a>';
													break;
											}

											echo '<form method="post" action="moviehouse/vote_film.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&id_film=' . $ligne[1] . '" id="preference2[' . $l . ']" style="display: none; min-width: 240px;">';
												// Boutons vote
												for($m = 0; $m <= 5; $m++)
												{
													echo '<img src="moviehouse/icons/stars/star' . $m .'.png" alt="star' . $m . '" class="new_star_2" />';

													if ($m == $ligne[3])
														echo '<input type="submit" name="preference[' . $m . ']" value="" class="link_vote_2" style="padding-bottom: 8px; border-bottom: solid 3px rgb(200, 25, 50);" />';
													else
														echo '<input type="submit" name="preference[' . $m . ']" value="" class="link_vote_2" />';
												}

												// Bouton annulation
												echo '<a onclick="afficherMasquer(\'preference[' . $l . ']\'); afficherMasquer(\'preference2[' . $l . ']\');" id="preference[' . $l . ']" title="Annuler" class="link_vote">';
													echo '<img src="moviehouse/icons/not_interested.png" alt="not_interested" title="Annuler" class="cancel_vote" />';
												echo '</a>';
											echo '</form>';
										}
										// Avis des autres utilisteurs
										else
										{
											if (isset($ligne[3]))
												echo '<img src="moviehouse/icons/stars/star' . $ligne[3] . '.png" alt="star0" class="new_star" />';
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
						if ($_SESSION['identifiant'] == $user_choix[$j])
							echo '<td class="table_users" style="background-color: #fffde8;">';
						else
							echo '<td class="table_users">';

							echo '<div class="stars_content">';
								// Si le user correspond à la colonne ($j)
								if ($_SESSION['identifiant'] == $user_choix[$j])
								{
									echo '<a onclick="afficherMasquer(\'preference[' . $l . ']\'); afficherMasquer(\'preference2[' . $l . ']\');" id="preference[' . $l . ']" title="Préférence" class="link_vote" style="margin-left: auto; margin-right: auto;">';
										echo '<img src="moviehouse/icons/stars/star0.png" alt="star0" class="new_star" />';
									echo '</a>';

									echo '<form method="post" action="moviehouse/vote_film.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&id_film=' . $donnees3['id'] . '" id="preference2[' . $l . ']" style="display: none; min-width: 240px;">';
										// Boutons vote
										for($m = 0; $m <= 5; $m++)
										{
											echo '<img src="moviehouse/icons/stars/star' . $m .'.png" alt="star' . $m . '" class="new_star_2" />';
											if ($m == 0)
												echo '<input type="submit" name="preference[' . $m . ']" value="" class="link_vote_2" style="padding-bottom: 8px; border-bottom: solid 3px rgb(200, 25, 50);" />';
											else
												echo '<input type="submit" name="preference[' . $m . ']" value="" class="link_vote_2" />';
										}

										// Bouton annulation
										echo '<a onclick="afficherMasquer(\'preference[' . $l . ']\'); afficherMasquer(\'preference2[' . $l . ']\');" id="preference[' . $l . ']" title="Annuler" class="link_vote">';
											echo '<img src="moviehouse/icons/not_interested.png" alt="not_interested" title="Annuler" class="cancel_vote" />';
										echo '</a>';
									echo '</form>';
								}
								// Avis des autres utilisteurs
								else
								{
									echo '<img src="moviehouse/icons/stars/star0.png" alt="star0" class="new_star" />';
								}
							echo '</div>';
						echo '</td>';
					}
				}
			echo '</tr>';

			$l++;
		}

		$reponse3->closeCursor();

		// On récupère la liste des utilisateurs du site sur la dernière ligne à partir de la 3ème colonne
		$user_choix = array();
		echo '<tr>';
			echo '<td class="table_titres" style="border: 0;"></td>';
			echo '<td class="init_table_dates">Date de sortie</td>';

			$reponse4 = $bdd->query('SELECT identifiant, full_name, avatar FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');

			while($donnees4 = $reponse4->fetch())
			{
				echo '<td class="init_table_users">';
					echo '<div class="zone_avatar_films">';
						if (isset($donnees4['avatar']) AND !empty($donnees4['avatar']))
							echo '<img src="../profil/avatars/' . $donnees4['avatar'] . '" alt="avatar" title="' . $donnees4['full_name'] . '" class="avatar_films" />';
						else
							echo '<img src="../includes/icons/default.png" alt="avatar" title="' . $donnees4['full_name'] . '" class="avatar_films" />';
					echo '</div>';

					echo '<span class="full_name_films">' . $donnees4['full_name'] . '</span>';
				echo '</td>';
			}

			$reponse4->closeCursor();
		echo '</tr>';

	echo '</table>';
?>

<script type="text/javascript">
	function afficherMasquer(id)
	{
		if (document.getElementById(id).style.display == "none")
			document.getElementById(id).style.display = "block";
		else
			document.getElementById(id).style.display = "none";
	}
</script>
