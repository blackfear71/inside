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

			/*echo '<td class="init_td_manage_users" style="width: 25%;">';
				echo 'ReferenceGuide';
			echo '</td>';*/

			echo '<td class="init_td_manage_users" style="width: 35%;">';
				echo 'Movie House';
			echo '</td>';

			echo '<td colspan="4" class="init_td_manage_users" style="width: 40%;">';
				echo 'ExpenseCenter';
			echo '</td>';
		echo '</tr>';

		echo '<tr class="init_tr_manage_users">';
			/*echo '<td class="init_td_manage_users">';
				echo 'Nombre d\'articles publiés';
			echo '</td>';*/

			echo '<td class="init_td_manage_users">';
				echo 'Nombre de commentaires';
			echo '</td>';

			echo '<td colspan="4" class="init_td_manage_users">';
				echo 'Bilan des dépenses';
			echo '</td>';
		echo '</tr>';

		include('../includes/appel_bdd.php');

		// Initialisations calcul total des inscrits
		$nb_tot_publications    = 0;
		$nb_tot_commentaires    = 0;

		// Initialisation contrôle somme des bilans nulle pour les dépenses avec parts
		$somme_bilans = 0;
		$somme_bilans_finale = 0;

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

				/*echo '<td class="td_manage_users">';
					$req1 = $bdd->query('SELECT COUNT(id) AS nb_publications FROM reference_guide WHERE author = "' . $donnees['identifiant'] . '"');
					$data1 = $req1->fetch();
					echo $data1['nb_publications'];
					$nb_tot_publications += $data1['nb_publications'];
					$req1->closeCursor();
				echo '</td>';*/

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
						$nb_parts_total += $donnees2['parts'];

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
						$bilan += $prix_achat - ($prix_par_part * $nb_parts_user);
					else
						$bilan -= $prix_par_part * $nb_parts_user;

					$reponse2->closeCursor();
				}
				$reponse1->closeCursor();

				$bilan_format = str_replace('.', ',', number_format($bilan, 2));

				if ($bilan <= -6)
					echo '<td colspan="4" class="td_depenses" style="background-color: #ee4949">';
				elseif ($bilan <= -3 AND $bilan > -6)
					echo '<td colspan="4" class="td_depenses" style="background-color: #ff9147;">';
				elseif ($bilan < 0 AND $bilan > -3)
					echo '<td colspan="4" class="td_depenses" style="background-color: #fffd4c;">';
				elseif ($bilan > 0 AND $bilan < 5)
					echo '<td colspan="4" class="td_depenses" style="background-color: #b6fc78;">';
				elseif ($bilan > 0 AND $bilan >= 5)
					echo '<td colspan="4" class="td_depenses" style="background-color: #71d058;">';
				else
					echo '<td colspan="4" class="td_depenses">';
						echo $bilan_format . ' €';
					echo '</td>';
			echo '</tr>';

			// Somme des bilans (pour contrôle somme avec parts à 0)
			$somme_bilans += $bilan;
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

		// On cherche les utilisateurs désinscrits qui ont une dépense
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

		// On cherche les utilisateurs désinscrits qui ont un achat
		$req6 = $bdd->query('SELECT DISTINCT buyer FROM expense_center ORDER BY buyer ASC');
		while($data6 = $req6->fetch())
		{
			$founded = false;

			// On cherche déja s'il y a un acheteur qui n'est pas dans les inscrits
			foreach($utilisateurs_inscrits as $ligne)
			{
				if ($data6['buyer'] == $ligne)
				{
					$founded = true;
					break;
				}
			}

			// Si c'est le cas, on cherche s'il n'est pas déjà dans les désinscrits
			if ($founded == false)
			{
				foreach($utilisateurs_desinscrits as $ligne2)
				{
					if ($data6['buyer'] == $ligne2)
					{
						$founded = true;
						break;
					}
				}
			}

			// Sinon on le rajoute à la liste des désinscrits
			if ($founded == false)
			{
				$utilisateurs_desinscrits[$j] = $data6['buyer'];
				$j++;
			}
		}
		$req6->closeCursor();

		// Séparation utilisateurs désinscrits
		$compteur_tableau = count($utilisateurs_desinscrits);
		if ($compteur_tableau > 0)
		{
			echo '<tr class="ligne_tableau_movie_house">';
				echo '<td class="table_date_jour" colspan="100%">Anciens utilisateurs</td>';
			echo '</tr>';
		}

		// Utilisateurs désinscrits (avec alerte s'il reste des dettes)
		$alerte_bilan = false;

		foreach($utilisateurs_desinscrits as $ligne)
		{
			echo '<tr class="tr_manage_users">';
				echo '<td colspan="2" class="td_manage_users">';
					echo $ligne;
				echo '</td>';

				/*echo '<td class="td_manage_users">';
					$req7 = $bdd->query('SELECT COUNT(id) AS nb_publications FROM reference_guide WHERE author = "' . $ligne . '"');
					$data7 = $req7->fetch();
					echo $data7['nb_publications'];
					$req7->closeCursor();
				echo '</td>';*/

				echo '<td class="td_manage_users">';
					$req8 = $bdd->query('SELECT COUNT(id) AS nb_comments FROM movie_house_comments WHERE author = "' . $ligne . '"');
					$data8 = $req8->fetch();
					echo $data8['nb_comments'];
					$req8->closeCursor();
				echo '</td>';

				// Calcul des bilans pour les utilisateurs désinscrits uniquement
				$req9 = $bdd->query('SELECT * FROM expense_center ORDER BY id ASC');

				$bilan = 0;

				while($data9 = $req9->fetch())
				{
					// Prix d'achat
					$prix_achat = $data9['price'];

					// Identifiant de l'acheteur
					$acheteur = $data9['buyer'];

					// Nombre de parts total et utilisateur
					$req10 = $bdd->query('SELECT * FROM expense_center_users WHERE id_expense = ' . $data9['id']);

					$nb_parts_total = 0;
					$nb_parts_user = 0;

					while($data10 = $req10->fetch())
					{
						// Nombre de parts total
						$nb_parts_total += $data10['parts'];

						// Nombre de parts de l'utilisateur
						if ($ligne == $data10['identifiant'])
							$nb_parts_user = $data10['parts'];
					}

					// Prix par parts
					if ($nb_parts_total != 0)
						$prix_par_part = $prix_achat / $nb_parts_total;
					else
						$prix_par_part = 0;

					// On fait la somme des dépenses moins les parts consommées pour trouver le bilan
					if ($data9['buyer'] == $ligne AND $nb_parts_user >= 0)
					{
						$bilan += $prix_achat - ($prix_par_part * $nb_parts_user);
						$somme_bilans += $bilan;
					}
					elseif ($data9['buyer'] != $ligne AND $nb_parts_user > 0)
					{
						$bilan -= $prix_par_part * $nb_parts_user;
						$somme_bilans += $bilan;
					}

					$req10->closeCursor();
				}
				$req9->closeCursor();

				$bilan_format = str_replace('.', ',', number_format($bilan, 2));

				if ($bilan != 0)
				{
					$alerte_bilan = true;
				}

				if ($bilan <= -6)
					echo '<td colspan="4" class="td_depenses" style="background-color: #ee4949">';
				elseif ($bilan <= -3 AND $bilan > -6)
					echo '<td colspan="4" class="td_depenses" style="background-color: #ff9147;">';
				elseif ($bilan < 0 AND $bilan > -3)
					echo '<td colspan="4" class="td_depenses" style="background-color: #fffd4c;">';
				elseif ($bilan > 0 AND $bilan < 5)
					echo '<td colspan="4" class="td_depenses" style="background-color: #b6fc78;">';
				elseif ($bilan > 0 AND $bilan >= 5)
					echo '<td colspan="5" class="td_depenses" style="background-color: #71d058;">';
				else
					echo '<td colspan="4" class="td_depenses">';
						echo $bilan_format . ' €';
					echo '</td>';
			echo '</tr>';

			// Somme des bilans (pour contrôle somme avec parts à 0)
			$somme_bilans += $bilan;
		}

		// On cherche les dépenses sans parts
		$reponse3 = $bdd->query('SELECT * FROM expense_center ORDER BY id ASC');

		$depense_0_parts = 0;

		while($donnees3 = $reponse3->fetch())
		{
			$reponse4 = $bdd->query('SELECT COUNT(id) AS nb_parts_depense FROM expense_center_users WHERE id_expense = ' . $donnees3['id']);
			$donnees4 = $reponse4->fetch();

			//echo '$donnees4[nb_parts_depense] : ' . $donnees4['nb_parts_depense'] . '<br />';

			if ($donnees4['nb_parts_depense'] == 0)
				$depense_0_parts += $donnees3['price'];

			$reponse4->closeCursor();
		}
		$reponse3->closeCursor();

		// On retire les dépenses à 0 parts de la somme des bilans
		$somme_bilans_finale = $somme_bilans - $depense_0_parts;

		// Bas du tableau
		echo '<tr>';
			echo '<td colspan="2" class="td_manage_users" style="background-color: #e3e3e3; font-weight: bold;">';
				echo 'Total';
			echo '</td>';

			/*echo '<td class="td_manage_users">';
				$req11 = $bdd->query('SELECT COUNT(id) AS nb_total_articles FROM reference_guide');
				$data11 = $req11->fetch();
				echo $data11['nb_total_articles'];
				$req11->closeCursor();
			echo '</td>';*/

			echo '<td class="td_manage_users">';
				$req12 = $bdd->query('SELECT COUNT(id) AS nb_total_comments FROM movie_house_comments');
				$data12 = $req12->fetch();
				echo $data12['nb_total_comments'];
				$req12->closeCursor();
			echo '</td>';

			echo '<td class="td_manage_users" style="background-color: #e3e3e3; font-weight: bold;">';
				echo 'Bilan';
			echo '</td>';

			if ($somme_bilans_finale < 0.01 AND $somme_bilans_finale > -0.01)
				echo '<td class="td_manage_users" style="font-family: robotolight, Verdana, sans-serif;">';
			else
				echo '<td class="td_manage_users" style="font-family: robotolight, Verdana, sans-serif; background-color: #ee4949;">';
					$somme_bilans_finale_format = str_replace('.', ',', number_format($somme_bilans_finale, 2));
					// Somme des dépenses de chacun dont les parts ne sont pas toutes à 0 : doit être toujours égal à 0€
					echo $somme_bilans_finale_format . ' €';
			echo '</td>';

			echo '<td class="td_manage_users" style="background-color: #e3e3e3; font-weight: bold;">';
				echo 'Alertes';
			echo '</td>';

			echo '<td class="td_manage_users" style="font-family: robotolight, Verdana, sans-serif;">';
				// Alerte si un utilisateur désinscrit n'a pas payé
				if ($alerte_bilan == true OR $somme_bilans_finale > 0.01 OR $somme_bilans_finale < -0.01)
					echo '<span class="reset_warning">!</span>';
			echo '</td>';
		echo '</tr>';
	echo '</table>';
?>
