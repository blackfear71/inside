<?php
	include('../includes/appel_bdd.php');
	include('../includes/fonctions_dates.php');

	/***************************/
	/* Tableau vue utilisateur */
	/***************************/
	echo '<table class="table_movie_house">';
		// Titres du tableau
		echo '<tr>';
			echo '<td class="table_titres" style="border: 0;"></td>';
			echo '<td class="init_table_dates" style="width: 120px;">Date de sortie</td>';
			echo '<td class="init_table_dates" style="width: 120px;">Fiche</td>';
			echo '<td class="init_table_dates" style="width: 120px;">Bande-annonce</td>';
			echo '<td class="init_table_dates" style="width: 120px;">Doodle</td>';
			echo '<td class="init_table_dates" style="width: 120px;">Date proposée</td>';
			echo '<td class="init_table_dates" style="width: 120px;">Commentaires</td>';
			echo '<td class="init_table_dates" style="width: 120px;">Vote</td>';
			echo '<td class="init_table_dates" style="width: 120px;">Actions</td>';
		echo '</tr>';

		// On récupère la liste des films sur la première colonne et les autres infos sur les colonnes suivantes
		$reponse = $bdd->query('SELECT * FROM movie_house WHERE SUBSTR(date_theater, 1, 4)=' . $_GET['year'] . ' AND to_delete != "Y" ORDER BY date_theater ASC, film ASC');

		$date_jour = date("Ymd");
		$date_jour_present = false;
		$i = 0;

		while($donnees = $reponse->fetch())
		{
			// On affiche la date du jour
			if (date("Y") == $_GET['year'])
			{
				if ($donnees['date_theater'] >= $date_jour AND $date_jour_present == false AND $_SESSION['today_movie_house'] == "Y")
				{
					echo '<tr class="ligne_tableau_movie_house">';
						echo '<td class="table_date_jour" colspan="100%">Aujourd\'hui, le ' . date("d/m/Y") . '</td>';
					echo '</tr>';

					$date_jour_present = true;
				}
			}

			echo '<tr class="ligne_tableau_movie_house">';
				// Nom du film
				echo '<td class="table_titres">';
					echo '<a href="moviehouse/details_film.php?id_film=' . $donnees['id'] . '" id="' . $donnees['id'] . '" class="link_film">' . $donnees['film'] . '</a>';
				echo '</td>';

				// Date de sortie cinéma
				echo '<td class="table_dates">';
					if (!empty($donnees['date_theater']))
					//SMI - déb
					{
						if (isBlankDate($donnees['date_theater']))
						{
							echo 'N.C.';
						}
						else
						{
							echo formatDateForDisplay($donnees['date_theater']);
						}
					}
					//SMI - fin
				echo '</td>';

				// Fiche du film
				echo '<td class="table_dates">';
					if (!empty($donnees['link']))
						echo '<a href="' . $donnees['link'] . '" target="_blank"><img src="moviehouse/images/pellicule.png" alt="pellicule" title="Fiche du film" class="logo_tableau_films" /></a>';
				echo '</td>';

				// Bande-annonce
				echo '<td class="table_dates">';
					if (!empty($donnees['trailer']))
						echo '<a href="' . $donnees['trailer'] . '" target="_blank"><img src="moviehouse/images/youtube.png" alt="youtube" title="Bande-annonce du film" class="logo_tableau_films" /></a>';
				echo '</td>';

				// Lien Doodle
				echo '<td class="table_dates">';
					if (!empty($donnees['doodle']))
						echo '<a href="' . $donnees['doodle'] . '" target="_blank"><img src="moviehouse/images/doodle.png" alt="doodle" title="Lien Doodle" class="logo_tableau_films" /></a>';
				echo '</td>';

				// Date de sortie proposée
				echo '<td class="table_dates">';
					if (!empty($donnees['date_doodle']))
						echo formatDateForDisplay($donnees['date_doodle']);
				echo '</td>';

				// Commentaires
				echo '<td class="table_dates">';
					$reponse1 = $bdd->query('SELECT COUNT(id) AS nb_comments FROM movie_house_comments WHERE id_film = "' . $donnees['id'] . '"');
					$donnees1 = $reponse1->fetch();
					echo $donnees1['nb_comments'];
					$reponse1->closeCursor();
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

					switch($donnees2['stars'])
					{
						case "1":
							echo '<a onclick="afficherMasquer(\'preference[' . $i . ']\'); afficherMasquer(\'preference2[' . $i . ']\');" id="preference[' . $i . ']" title="Préférence" class="link_vote" style="margin-left: auto; margin-right: auto;">';
								echo '<img src="moviehouse/images/stars/star1.png" alt="star1" class="new_star" />';
							echo '</a>';
							break;

						case "2":
							echo '<a onclick="afficherMasquer(\'preference[' . $i . ']\'); afficherMasquer(\'preference2[' . $i . ']\');" id="preference[' . $i . ']" title="Préférence" class="link_vote" style="margin-left: auto; margin-right: auto;">';
								echo '<img src="moviehouse/images/stars/star2.png" alt="star2" class="new_star" />';
							echo '</a>';
							break;

						case "3":
							echo '<a onclick="afficherMasquer(\'preference[' . $i . ']\'); afficherMasquer(\'preference2[' . $i . ']\');" id="preference[' . $i . ']" title="Préférence" class="link_vote" style="margin-left: auto; margin-right: auto;">';
								echo '<img src="moviehouse/images/stars/star3.png" alt="star3" class="new_star" />';
							echo '</a>';
							break;

						case "4":
							echo '<a onclick="afficherMasquer(\'preference[' . $i . ']\'); afficherMasquer(\'preference2[' . $i . ']\');" id="preference[' . $i . ']" title="Préférence" class="link_vote" style="margin-left: auto; margin-right: auto;">';
								echo '<img src="moviehouse/images/stars/star4.png" alt="star4" class="new_star" />';
							echo '</a>';
							break;

						case "5":
							echo '<a onclick="afficherMasquer(\'preference[' . $i . ']\'); afficherMasquer(\'preference2[' . $i . ']\');" id="preference[' . $i . ']" title="Préférence" class="link_vote" style="margin-left: auto; margin-right: auto;">';
								echo '<img src="moviehouse/images/stars/star5.png" alt="star5" class="new_star" />';
							echo '</a>';
							break;

						default:
							echo '<a onclick="afficherMasquer(\'preference[' . $i . ']\'); afficherMasquer(\'preference2[' . $i . ']\');" id="preference[' . $i . ']" title="Préférence" class="link_vote" style="margin-left: auto; margin-right: auto;">';
								echo '<img src="moviehouse/images/stars/star0.png" alt="star0" class="new_star" />';
							echo '</a>';
							break;
					}

					echo '<form method="post" action="moviehouse/vote_film.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&id_film=' . $donnees['id'] . '" id="preference2[' . $i . ']" style="display: none; min-width: 240px;">';
						// Boutons vote
						for($j = 0; $j <= 5; $j++)
						{
							echo '<img src="moviehouse/images/stars/star' . $j .'.png" alt="star' . $j . '" class="new_star_2" />';

							if ($j == $donnees2['stars'])
								echo '<input type="submit" name="preference[' . $j . ']" value="" class="link_vote_2" style="padding-bottom: 8px; border-bottom: solid 3px rgb(200, 25, 50);" />';
							else
								echo '<input type="submit" name="preference[' . $j . ']" value="" class="link_vote_2" />';
						}

						// Bouton annulation
						echo '<a onclick="afficherMasquer(\'preference[' . $i . ']\'); afficherMasquer(\'preference2[' . $i . ']\');" id="preference[' . $i . ']" title="Annuler" class="link_vote">';
							echo '<img src="moviehouse/images/not_interested.png" alt="not_interested" title="Annuler" class="cancel_vote" />';
						echo '</a>';
					echo '</form>';
				echo '</td>';

				// Actions
				echo '<td class="table_dates">';

					if (isset($donnees2['stars']))
					{
						echo '<form method="post" action="moviehouse/actions_users.php?view=' . $_GET['view'] . '&year=' . $_GET['year'] . '&id_film=' . $donnees2['id_film'] . '" class="form_not_interested">';
							// Je participe
							echo '<input type="submit" name="participate" value="" title="Je participe !" class="participate"/>';
							// J'ai vu
							echo '<input type="submit" name="seen" value="" title="J\'ai vu !" class="seen"/>';
						echo '</form>';
					}

				echo '</td>';

				$reponse2->closeCursor();
			echo '</tr>';

			$i++;
		}

		$reponse->closeCursor();
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
