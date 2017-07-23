<?php
	echo '<div class="title_gestion">Statistiques catégories</div>';

	echo '<table class="table_manage_users">';
		// Entête du tableau
		echo '<tr class="init_tr_manage_users">';
			echo '<td rowspan="2" class="init_td_manage_users" style="width: 10%;">';
				echo 'Identifiant';
			echo '</td>';

			echo '<td rowspan="2" class="init_td_manage_users" style="width: 15%;">';
				echo 'Pseudo';
			echo '</td>';

			echo '<td class="init_td_manage_users" style="width: 25%;">';
				echo 'ReferenceGuide';
			echo '</td>';

			echo '<td class="init_td_manage_users" style="width: 25%;">';
				echo 'Movie House';
			echo '</td>';

			echo '<td class="init_td_manage_users" style="width: 25%;">';
				echo 'ExpenseCenter';
			echo '</td>';
		echo '</tr>';

		echo '<tr class="init_tr_manage_users">';
			echo '<td class="init_td_manage_users">';
				echo 'Nombre d\'articles publiés';
			echo '</td>';

			echo '<td class="init_td_manage_users">';
				echo 'Nombre de commentaires';
			echo '</td>';

			echo '<td class="init_td_manage_users">';
				echo 'Bilan des dépenses';
			echo '</td>';
		echo '</tr>';

		include('../includes/appel_bdd.php');

		// Initialisations calcul total des inscrits
		$nb_tot_publications    = 0;
		$nb_tot_commentaires    = 0;

		// Recherche des données utilisateurs
		$reponse = $bdd->query('SELECT id, identifiant, full_name, reset FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');

		while($donnees = $reponse->fetch())
		{
			echo '<tr class="tr_manage_users">';
				echo '<td class="td_manage_users">';
					echo $donnees['identifiant'];
				echo '</td>';

				echo '<td class="td_manage_users">';
					echo $donnees['full_name'];
				echo '</td>';

				echo '<td class="td_manage_users">';
					$req1 = $bdd->query('SELECT COUNT(id) AS nb_publications FROM reference_guide WHERE author = "' . $donnees['identifiant'] . '"');
					$data1 = $req1->fetch();
					echo $data1['nb_publications'];
					$nb_tot_publications += $data1['nb_publications'];
					$req1->closeCursor();
				echo '</td>';

				echo '<td class="td_manage_users">';
					$req2 = $bdd->query('SELECT COUNT(id) AS nb_comments FROM movie_house_comments WHERE author = "' . $donnees['identifiant'] . '"');
					$data2 = $req2->fetch();
					echo $data2['nb_comments'];
					$nb_tot_commentaires += $data2['nb_comments'];
					$req2->closeCursor();
				echo '</td>';


























				// Calcul des bilans
				$reponse1 = $bdd->query('SELECT * FROM expense_center ORDER BY id ASC');

				$bilan = 0;

				while($donnees1 = $reponse1->fetch())
				{
					// Prix d'achat
					$prix_achat = $donnees1['price'];

					// Identifiant de l'acheteur
					$acheteur = $donnees1['buyer'];

					// Nombre de parts et prix par parts
					$reponse2 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense = ' . $donnees1['id']);

					$nb_parts_total = 0;
					$nb_parts_user = 0;

					while($donnees2 = $reponse2->fetch())
					{
						// Nombre de parts total
						$nb_parts_total = $nb_parts_total + $donnees2['parts'];

						// Nombre de parts de l'utilisateur
						if ($donnees['identifiant'] == $donnees2['identifiant'])
							$nb_parts_user = $donnees2['parts'];
					}

					if ($nb_parts_total != 0)
						$prix_par_part = $prix_achat / $nb_parts_total;
					else
						$prix_par_part = 0;

					// On fait la somme des dépenses moins les parts consommées pour trouver le bilan
					if ($donnees1['buyer'] == $donnees['identifiant'])
						$bilan = $bilan + $prix_achat - ($prix_par_part * $nb_parts_user);
					else
						$bilan = $bilan - ($prix_par_part * $nb_parts_user);

					$reponse2->closeCursor();

				}
				$reponse1->closeCursor();

				$bilan_format = str_replace('.', ',', round($bilan, 2));

				if ($bilan < 0 AND $bilan <= -5)
					echo '<td class="td_manage_users" style="background-color: rgb(255, 25, 55);">';
				elseif ($bilan < 0 AND $bilan > -5)
					echo '<td class="td_manage_users" style="background-color: #fffd4c;">';
				elseif ($bilan > 0 AND $bilan < 5)
					echo '<td class="td_manage_users" style="background-color: #91d784;">';
				elseif ($bilan > 0 AND $bilan >= 5)
					echo '<td class="td_manage_users" style="background-color: rgb(90, 200, 70);">';
				else
					echo '<td class="td_manage_users">';

						echo $bilan_format . ' €';

				echo '</td>';








			echo '</tr>';
		}

		$reponse->closeCursor();

		// On récupère un tableau des utilisateurs inscrits
		$utilisateurs_inscrits = array();
		$i = 0;
		$req4 = $bdd->query('SELECT identifiant FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
		while($data4 = $req4->fetch())
		{
			$utilisateurs_inscrits[$i] = $data4['identifiant'];
			$i++;
		}
		$req4->closeCursor();

		// On cherche les utilisateurs désinscrits qui ont une dépenses
		$utilisateurs_desinscrits = array();
		$j = 0;

		$req5 = $bdd->query('SELECT DISTINCT identifiant FROM expense_center_users ORDER BY identifiant ASC');
		while($data5 = $req5->fetch())
		{
			$founded = false;

			foreach($utilisateurs_inscrits as $ligne)
			{
				if ($data5['identifiant'] == $ligne)
				{
					$founded = true;
					break;
				}
			}

			if ($founded == false)
			{
				$utilisateurs_desinscrits[$j] = $data5['identifiant'];
				$j++;
			}
		}
		$req5->closeCursor();

		// Séparation utilisateurs désinscrits
		$compteur_tableau = count($utilisateurs_desinscrits);
		if ($compteur_tableau > 0)
		{
			echo '<tr class="ligne_tableau_movie_house">';
				echo '<td class="table_date_jour" colspan="100%">Anciens utilisateurs</td>';
			echo '</tr>';
		}

		// Utilisateurs désinscrits
		foreach($utilisateurs_desinscrits as $ligne)
		{
			echo '<tr class="tr_manage_users">';
				echo '<td colspan="2" class="td_manage_users">';
					echo $ligne;
				echo '</td>';

				echo '<td class="td_manage_users">';
					$req6 = $bdd->query('SELECT COUNT(id) AS nb_publications FROM reference_guide WHERE author = "' . $ligne . '"');
					$data6 = $req6->fetch();
					echo $data6['nb_publications'];
					$req6->closeCursor();
				echo '</td>';

				echo '<td class="td_manage_users">';
					$req7 = $bdd->query('SELECT COUNT(id) AS nb_comments FROM movie_house_comments WHERE author = "' . $ligne . '"');
					$data7 = $req7->fetch();
					echo $data7['nb_comments'];
					$req7->closeCursor();
				echo '</td>';

				// Calcul des bilans pour les utilisateurs désinscrits uniquement
				$reponse1 = $bdd->query('SELECT * FROM expense_center ORDER BY id ASC');

				$bilan = 0;

				while($donnees1 = $reponse1->fetch())
				{
					// Prix d'achat
					$prix_achat = $donnees1['price'];

					// Identifiant de l'acheteur
					$acheteur = $donnees1['buyer'];

					// Nombre de parts et prix par parts
					$reponse2 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense = ' . $donnees1['id'] . ' AND identifiant = "' . $ligne . '"');

					$nb_parts_total_user = 0;

					while($donnees2 = $reponse2->fetch())
					{
						// Nombre de parts total de l'utilisateur
						$nb_parts_total_user = $nb_parts_total_user + $donnees2['parts'];
					}

					if ($nb_parts_total_user != 0)
						$prix_par_part = $prix_achat / $nb_parts_total_user;
					else
						$prix_par_part = 0;

					// On fait la somme des dépenses moins les parts consommées pour trouver le bilan
					if ($donnees1['buyer'] == $ligne AND $nb_parts_total_user >= 0)
						$bilan = $bilan + $prix_achat - ($prix_par_part * $nb_parts_total_user);
					elseif ($donnees1['buyer'] != $ligne AND $nb_parts_total_user > 0)
						$bilan = $bilan - ($prix_par_part * $nb_parts_total_user);

					$reponse2->closeCursor();
				}
				$reponse1->closeCursor();

				$bilan_format = str_replace('.', ',', round($bilan, 2));

				if ($bilan < 0 AND $bilan <= -5)
					echo '<td class="td_manage_users" style="background-color: rgb(255, 25, 55);">';
				elseif ($bilan < 0 AND $bilan > -5)
					echo '<td class="td_manage_users" style="background-color: #fffd4c;">';
				elseif ($bilan > 0 AND $bilan < 5)
					echo '<td class="td_manage_users" style="background-color: #91d784;">';
				elseif ($bilan > 0 AND $bilan >= 5)
					echo '<td class="td_manage_users" style="background-color: rgb(90, 200, 70);">';
				else
					echo '<td class="td_manage_users">';

						echo $bilan_format . ' €';

				echo '</td>';
			echo '</tr>';
		}

		// Bas du tableau
		echo '<tr>';
			echo '<td colspan="2" class="td_manage_users" style="background-color: #e3e3e3; font-weight: bold;">';
				echo 'Total';
			echo '</td>';

			echo '<td class="td_manage_users">';
				$req7 = $bdd->query('SELECT COUNT(id) AS nb_total_articles FROM reference_guide');
				$data7 = $req7->fetch();
				echo $data7['nb_total_articles'];
				$req7->closeCursor();
			echo '</td>';

			echo '<td class="td_manage_users">';
				$req8 = $bdd->query('SELECT COUNT(id) AS nb_total_comments FROM movie_house_comments');
				$data8 = $req8->fetch();
				echo $data8['nb_total_comments'];
				$req8->closeCursor();
			echo '</td>';







			echo '<td class="td_manage_users">';




				// Alerte si un utilisateur désinscrit n'a pas payé





				/*$req9 = $bdd->query('SELECT COUNT(id) AS nb_total_bugs FROM bugs');
				$data9 = $req9->fetch();
				echo $data9['nb_total_bugs'];
				$req9->closeCursor();*/
			echo '</td>';






		echo '</tr>';
	echo '</table>';
?>
