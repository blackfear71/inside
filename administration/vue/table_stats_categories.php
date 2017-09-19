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
				echo 'Movie House';
			echo '</td>';

			echo '<td colspan="4" class="init_td_manage_users" style="width: 30%;">';
				echo 'Expense Center';
			echo '</td>';

			echo '<td colspan="4" class="init_td_manage_users" style="width: 20%;">';
				echo 'Collector Room';
			echo '</td>';
		echo '</tr>';

		echo '<tr class="init_tr_manage_users">';
			echo '<td class="init_td_manage_users">';
				echo 'Nombre de commentaires';
			echo '</td>';

			echo '<td colspan="4" class="init_td_manage_users">';
				echo 'Bilan des dépenses';
			echo '</td>';

			echo '<td class="init_td_manage_users">';
				echo 'Nombre de phrases cultes';
			echo '</td>';
		echo '</tr>';

		// Utilisateurs inscrits
    foreach ($tabCategoriesIns as $statsCatIns)
    {
      echo '<tr class="tr_manage_users">';
        echo '<td class="td_manage_users">';
          echo $statsCatIns['identifiant'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $statsCatIns['pseudo'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $statsCatIns['nb_comments'];
        echo '</td>';

        if ($statsCatIns['bilan'] <= -6)
					echo '<td colspan="4" class="td_stats_admin" style="background-color: #ee4949">';
				elseif ($statsCatIns['bilan'] <= -3 AND $statsCatIns['bilan'] > -6)
					echo '<td colspan="4" class="td_stats_admin" style="background-color: #ff9147;">';
				elseif ($statsCatIns['bilan'] < 0 AND $statsCatIns['bilan'] > -3)
					echo '<td colspan="4" class="td_stats_admin" style="background-color: #fffd4c;">';
				elseif ($statsCatIns['bilan'] > 0 AND $statsCatIns['bilan'] < 5)
					echo '<td colspan="4" class="td_stats_admin" style="background-color: #b6fc78;">';
				elseif ($statsCatIns['bilan'] > 0 AND $statsCatIns['bilan'] >= 5)
					echo '<td colspan="4" class="td_stats_admin" style="background-color: #71d058;">';
				else
					echo '<td colspan="4" class="td_stats_admin">';
						echo $statsCatIns['bilan_format'];
					echo '</td>';

				echo '<td class="td_manage_users">';
					echo $statsCatIns['nb_collectors'];
				echo '</td>';
			echo '</tr>';
    }

		// Séparation utilisateurs
		if (!empty($tabCategoriesDes))
		{
			echo '<tr class="ligne_tableau_movie_house">';
				echo '<td class="table_date_jour" colspan="100%">Anciens utilisateurs</td>';
			echo '</tr>';
		}

		// Utilisateurs desinscrits
		foreach ($tabCategoriesDes as $statsCatDes)
		{
			echo '<tr class="tr_manage_users">';
				echo '<td colspan="2" class="td_manage_users">';
					echo $statsCatDes['identifiant'];
				echo '</td>';

				echo '<td class="td_manage_users">';
					echo $statsCatDes['nb_comments'];
				echo '</td>';

				if ($statsCatDes['bilan'] <= -6)
					echo '<td colspan="4" class="td_stats_admin" style="background-color: #ee4949">';
				elseif ($statsCatDes['bilan'] <= -3 AND $statsCatDes['bilan'] > -6)
					echo '<td colspan="4" class="td_stats_admin" style="background-color: #ff9147;">';
				elseif ($statsCatDes['bilan'] < 0 AND $statsCatDes['bilan'] > -3)
					echo '<td colspan="4" class="td_stats_admin" style="background-color: #fffd4c;">';
				elseif ($statsCatDes['bilan'] > 0 AND $statsCatDes['bilan'] < 5)
					echo '<td colspan="4" class="td_stats_admin" style="background-color: #b6fc78;">';
				elseif ($statsCatDes['bilan'] > 0 AND $statsCatDes['bilan'] >= 5)
					echo '<td colspan="4" class="td_stats_admin" style="background-color: #71d058;">';
				else
					echo '<td colspan="4" class="td_stats_admin">';
						echo $statsCatDes['bilan_format'];
					echo '</td>';

				echo '<td class="td_manage_users">';
					echo $statsCatDes['nb_collectors'];
				echo '</td>';
			echo '</tr>';
		}

		// Bas du tableau
		echo '<tr>';
			echo '<td colspan="2" class="td_manage_users" style="background-color: #e3e3e3; font-weight: bold;">';
				echo 'Total';
			echo '</td>';

			echo '<td class="td_manage_users">';
				echo $totalCategories['nb_tot_commentaires'];
			echo '</td>';

			echo '<td class="td_manage_users" style="background-color: #e3e3e3; font-weight: bold;">';
				echo 'Bilan';
			echo '</td>';

			if ($totalCategories['alerte_bilan'] == true)
				echo '<td class="td_manage_users" style="font-family: robotolight, Verdana, sans-serif; background-color: #ee4949;">';
			else
				echo '<td class="td_manage_users" style="font-family: robotolight, Verdana, sans-serif;">';
					echo $totalCategories['somme_bilans_format'];
				echo '</td>';

			echo '<td class="td_manage_users" style="background-color: #e3e3e3; font-weight: bold;">';
				echo 'Alertes';
			echo '</td>';

			// Alerte si un utilisateur désinscrit n'a pas payé
			echo '<td class="td_manage_users" style="font-family: robotolight, Verdana, sans-serif;">';
				if ($totalCategories['alerte_bilan'] == true)
					echo '<span class="reset_warning">!</span>';
			echo '</td>';

			echo '<td class="td_manage_users">';
				echo $totalCategories['nb_tot_collectors'];
			echo '</td>';
		echo '</tr>';
	echo '</table>';
?>
