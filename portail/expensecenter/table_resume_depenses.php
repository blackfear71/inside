<?php
	include('../includes/appel_bdd.php');
	include('../includes/fonctions_dates.php');

	/************************/
	/* Tableau des dépenses */
	/************************/
	echo '<table class="table_movie_house">';

		// On récupère la liste des utilisateurs du site sur la première ligne à partir de la 4ème colonne
		$user_parts = array();
		echo '<tr>';
			echo '<td class="init_table_dates">Prix</td>';
			echo '<td class="init_table_dates">Acheteur</td>';
			echo '<td class="init_table_dates">Date</td>';

			$reponse1 = $bdd->query('SELECT identifiant, full_name, avatar FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');

			$nombre_users = 0;

			while($donnees1 = $reponse1->fetch())
			{
				echo '<td class="init_table_users">';
					echo '<div class="zone_avatar_films">';
						if (isset($donnees1['avatar']) AND !empty($donnees1['avatar']))
							echo '<img src="../connexion/avatars/' . $donnees1['avatar'] . '" alt="avatar" title="' . $donnees1['full_name'] . '" class="avatar_films" />';
						else
							echo '<img src="../includes/icons/default.png" alt="avatar" title="' . $donnees1['full_name'] . '" class="avatar_films" />';
					echo '</div>';

					echo '<span class="full_name_films">' . $donnees1['full_name'] . '</span>';
				echo '</td>';

				$nombre_users++;
				$user_parts[$nombre_users][1] = $donnees1['identifiant'];
				$user_parts[$nombre_users][2] = $donnees1['full_name'];
			}

			$reponse1->closeCursor();

      echo '<td class="init_table_dates">Actions</td>';
		echo '</tr>';

		// On récupère dans un tableau les choix utilisateurs
		$prices = array();
		$i = 1;

		$reponse2 = $bdd->query('SELECT * FROM expense_center_users ORDER BY identifiant ASC');

		while($donnees2 = $reponse2->fetch())
		{
			$prices[$i][1] = $donnees2['id_expense'];
			$prices[$i][2] = $donnees2['identifiant'];
			$prices[$i][3] = $donnees2['parts'];

			$i++;
		}

		$reponse2->closeCursor();

		// On récupère la liste des prix sur la première colonne, le nom de l'acheteur sur la 2ème et la date sur la 3ème
		$reponse3 = $bdd->query('SELECT * FROM expense_center ORDER BY id ASC');

		$l = 0;

		while($donnees3 = $reponse3->fetch())
		{
			/***************************************************/
			/* Ligne visualisation normale (sans modification) */
			/***************************************************/
			echo '<tr class="ligne_tableau_movie_house" id="modifier_depense_2[' . $l . ']">';
				// Prix sur la 1ère colonne
				echo '<td class="prices">';
					echo $donnees3['price'] . ' €';
				echo '</td>';

				// Acheteur sur la 2ème colonne
				echo '<td class="prices">';
					for ($j = 1; $j <= $nombre_users; $j++)
					{
						if ($user_parts[$j][1] == $donnees3['buyer'])
						{
							echo $user_parts[$j][2];
							break;
						}
					}
				echo '</td>';

				// Date sur la 3ème colonne
				echo '<td class="table_dates">';
					echo substr($donnees3['date'], 2, 2) . '/' . substr($donnees3['date'], 0, 2) . '/' . substr($donnees3['date'], 4, 4);
				echo '</td>';

				// On parcours chaque colonne pour tester l'identifiant (colonne <=> $j)
				for ($k = 1; $k <= $nombre_users; $k++)
				{
					// On initialise comme si on n'avait pas trouvé de ligne à afficher
					$empty = true;

					// On parcourt chaque ligne du tableau de chaque personne en fonction des parts qu'il a consommé
					foreach ($prices as $ligne)
					{
						if ($ligne[2] == $user_parts[$k][1] AND $ligne[1] == $donnees3['id'])
						{
							// On affiche le nombre de parts + la couleur si utilisateur concerné
              if ($_SESSION['identifiant'] == $ligne[2])
								echo '<td class="table_users" style="background-color: #fffde8;">';
							else
								echo '<td class="table_users">';

								if (is_numeric($ligne[3]))
								{
									echo $ligne[3];
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
						if ($_SESSION['identifiant'] == $user_parts[$k][1])
							echo '<td class="table_users" style="background-color: #fffde8;">';
						else
							echo '<td class="table_users">';
								echo 0;
							echo '</div>';
						echo '</td>';
					}
				}

				// Boutons d'action
				echo '<td class="action_depenses">';
					// Modification ligne
					echo '<a onclick="afficherMasquerRow(\'modifier_depense[' . $l . ']\'); afficherMasquerRow(\'modifier_depense_2[' . $l . ']\');" class="link_action_depenses"><img src="../includes/icons/edit.png" alt="edit" title="Modifier la ligne" class="icone_resume_depenses" /></a>';

					// Suppression ligne
					echo '<form method="post" action="" onclick="if(!confirm(\'Supprimer cette dépense ?\')) return false;" title="Supprimer la ligne" class="link_action_depenses">';
						echo '<img src="../includes/icons/delete.png" alt="delete" title="Supprimer la ligne" class="icone_resume_depenses" />';
					echo '</form>';
				echo '</td>';

			echo '</tr>';

			/**********************************/
			/* Ligne cachée pour modification */
			/**********************************/
			echo '<tr class="ligne_tableau_movie_house" id="modifier_depense[' . $l . ']" style="display: none;">';

				// Prix sur la 1ère colonne
				echo '<td class="prices">';
					echo $donnees3['price'] . ' €';
				echo '</td>';

				// Acheteur sur la 2ème colonne
				echo '<td class="prices">';
					for ($m = 1; $m <= $nombre_users; $m++)
					{
						if ($user_parts[$m][1] == $donnees3['buyer'])
						{
							echo $user_parts[$m][2];
							break;
						}
					}
				echo '</td>';

				// Date sur la 3ème colonne
				echo '<td class="table_dates">';
					echo substr($donnees3['date'], 2, 2) . '/' . substr($donnees3['date'], 0, 2) . '/' . substr($donnees3['date'], 4, 4);
				echo '</td>';

				// On parcours chaque colonne pour tester l'identifiant (colonne <=> $j)
				for ($n = 1; $n <= $nombre_users; $n++)
				{
					// On initialise comme si on n'avait pas trouvé de ligne à afficher
					$empty = true;

					// On parcourt chaque ligne du tableau de chaque personne en fonction des parts qu'il a consommé
					foreach ($prices as $ligne)
					{
						if ($ligne[2] == $user_parts[$n][1] AND $ligne[1] == $donnees3['id'])
						{
							// On affiche le nombre de parts + la couleur si utilisateur concerné
							if ($_SESSION['identifiant'] == $ligne[2])
								echo '<td class="table_users" style="background-color: #fffde8;">';
							else
								echo '<td class="table_users">';

									echo '<select name="depense_user" class="parts_2">';
									for($p = 0; $p <= 5; $p++)
									{
										if ($p == $ligne[3])
											echo '<option value="' . $p . '" selected>' . $p . '</option>';
										else
											echo '<option value="' . $p . '">' . $p . '</option>';
									}
									echo '</select>';

							echo '</td>';

							// Si on a affiché une seule ligne, on saura qu'il ne faut pas ajouter une case vide
							$empty = false;
						}
					}

					// Si jamais on n'a trouvé aucune ligne à afficher, l'indicateur va permettre d'afficher un case vide et de continuer sur la colonne suivante
					if ($empty == true)
					{
						// Pas de couleur sur la case car on n'a pas fait de choix
						if ($_SESSION['identifiant'] == $user_parts[$j][1])
							echo '<td class="table_users" style="background-color: #fffde8;">';
						else
							echo '<td class="table_users">';
								echo '<select name="depense_user" class="parts_2">';
								for($o = 0; $o <= 5; $o++)
								{
									echo '<option value="' . $o . '">' . $o . '</option>';
								}
								echo '</select>';
							echo '</div>';
						echo '</td>';
					}
				}

				// Boutons d'action
				echo '<td class="action_depenses">';
					// Validation modification
					echo '<form method="post" action="" title="Valider la modification" class="link_action_depenses">';
						echo '<img src="../includes/icons/validate.png" alt="validate" title="Supprimer la ligne" class="icone_resume_depenses" />';
					echo '</form>';

					// Annulation modification ligne
					echo '<a onclick="afficherMasquerRow(\'modifier_depense[' . $l . ']\'); afficherMasquerRow(\'modifier_depense_2[' . $l . ']\');" class="link_action_depenses"><img src="../includes/icons/cancel.png" alt="cancel" title="Annuler la modification" class="icone_resume_depenses" /></a>';
				echo '</td>';

			echo '</tr>';

			// Compteur ligne en cours pour affichage modification
			$l++;
		}

		$reponse3->closeCursor();

		// S'il n'y a rien à afficher
		if ($l == 0)
		{
			echo '<tr>';
				echo '<td colspan="100%" class="no_expenses">';
					echo 'Pas de dépenses à afficher';
				echo '</td>';
			echo '</tr>';
		}

    // On récupère la liste des utilisateurs du site sur la dernière ligne à partir de la 4ème colonne
		echo '<tr>';
			echo '<td class="init_table_dates">Prix</td>';
			echo '<td class="init_table_dates">Acheteur</td>';
			echo '<td class="init_table_dates">Date</td>';

			$reponse4 = $bdd->query('SELECT identifiant, full_name, avatar FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');

			while($donnees4 = $reponse4->fetch())
			{
				echo '<td class="init_table_users">';
					echo '<div class="zone_avatar_films">';
						if (isset($donnees4['avatar']) AND !empty($donnees4['avatar']))
							echo '<img src="../connexion/avatars/' . $donnees4['avatar'] . '" alt="avatar" title="' . $donnees4['full_name'] . '" class="avatar_films" />';
						else
							echo '<img src="../includes/icons/default.png" alt="avatar" title="' . $donnees4['full_name'] . '" class="avatar_films" />';
					echo '</div>';

					echo '<span class="full_name_films">' . $donnees4['full_name'] . '</span>';
				echo '</td>';
			}

			$reponse4->closeCursor();

      echo '<td class="init_table_dates">Actions</td>';
		echo '</tr>';

	echo '</table>';
?>

<script type="text/javascript">
	function afficherMasquerRow(id)
	{
		if (document.getElementById(id).style.display == "none")
			document.getElementById(id).style.display = "table-row";
		else
			document.getElementById(id).style.display = "none";
	}
</script>
