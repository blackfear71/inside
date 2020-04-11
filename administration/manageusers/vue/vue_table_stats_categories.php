<?php
	echo '<div class="titre_section"><img src="../../includes/icons/admin/expense_center_grey.png" alt="expense_center_grey" class="logo_titre_section" /><div class="texte_titre_section">Statistiques catégories</div></div>';

	echo '<table class="table_manage_users">';
		// Entête du tableau
		echo '<tr class="init_tr_manage_users">';
			echo '<td rowspan="2" class="init_td_manage_users" style="width: 10%;">';
				echo 'Identifiant';
			echo '</td>';

			echo '<td rowspan="2" class="init_td_manage_users" style="width: 15%;">';
				echo 'Pseudo';
			echo '</td>';

			echo '<td colspan="2" class="init_td_manage_users" style="width: 20%;">';
				echo 'Movie House';
			echo '</td>';

			echo '<td class="init_td_manage_users" style="width: 20%;">';
				echo 'Collector Room';
			echo '</td>';

			echo '<td colspan="4" class="init_td_manage_users" style="width: 35%;">';
				echo 'Expense Center';
			echo '</td>';
		echo '</tr>';

		echo '<tr class="init_tr_manage_users">';
			echo '<td class="init_td_manage_users">';
				echo 'Films ajoutés';
			echo '</td>';

			echo '<td class="init_td_manage_users">';
				echo 'Commentaires';
			echo '</td>';

			echo '<td class="init_td_manage_users">';
				echo 'Nombre de phrases cultes rapportées';
			echo '</td>';

			echo '<td colspan="4" class="init_td_manage_users">';
				echo 'Bilan des dépenses';
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
          echo $statsCatIns['nombreFilms'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $statsCatIns['nombreComments'];
        echo '</td>';

				echo '<td class="td_manage_users">';
					echo $statsCatIns['nombreCollector'];
				echo '</td>';

        if ($statsCatIns['bilanUser'] <= -6)
					echo '<td colspan="4" class="td_stats_admin" style="background-color: #ee4949">';
				elseif ($statsCatIns['bilanUser'] > -6 AND $statsCatIns['bilanUser'] <= -3)
					echo '<td colspan="4" class="td_stats_admin" style="background-color: #ff9147;">';
				elseif ($statsCatIns['bilanUser'] > -3 AND $statsCatIns['bilanUser'] < -0.01)
					echo '<td colspan="4" class="td_stats_admin" style="background-color: #fffd4c;">';
				elseif ($statsCatIns['bilanUser'] > 0.01 AND $statsCatIns['bilanUser'] < 5)
					echo '<td colspan="4" class="td_stats_admin" style="background-color: #b6fc78;">';
				elseif ($statsCatIns['bilanUser'] >= 5)
					echo '<td colspan="4" class="td_stats_admin" style="background-color: #71d058;">';
				elseif ($statsCatIns['bilanUser'] > -0.01 AND $statsCatIns['bilanUser'] < 0.01)
					echo '<td colspan="4" class="td_stats_admin">';
						echo formatBilanForDisplay($statsCatIns['bilanUser']);
					echo '</td>';
			echo '</tr>';
    }

		// Séparation utilisateurs
		if (!empty($tabCategoriesDes))
		{
			echo '<tr>';
				echo '<td class="table_old_users" colspan="9">';
					echo '<div class="banderole_left_1"></div><div class="banderole_left_2"></div>';
					echo 'Anciens utilisateurs';
					echo '<div class="banderole_left_3"></div><div class="banderole_left_4"></div>';
				echo '</td>';
			echo '</tr>';
		}

		// Utilisateurs désinscrits
		foreach ($tabCategoriesDes as $statsCatDes)
		{
			echo '<tr class="tr_manage_users">';
				echo '<td colspan="2" class="td_manage_users">';
					echo $statsCatDes['identifiant'];
				echo '</td>';

				echo '<td class="td_manage_users">';
					echo $statsCatDes['nombreFilms'];
				echo '</td>';

				echo '<td class="td_manage_users">';
					echo $statsCatDes['nombreComments'];
				echo '</td>';

				echo '<td class="td_manage_users">';
					echo $statsCatDes['nombreCollector'];
				echo '</td>';

				if ($statsCatDes['bilanUser'] <= -6)
					echo '<td colspan="4" class="td_stats_admin" style="background-color: #ee4949">';
				elseif ($statsCatDes['bilanUser'] > -6 AND $statsCatDes['bilanUser'] <= -3)
					echo '<td colspan="4" class="td_stats_admin" style="background-color: #ff9147;">';
				elseif ($statsCatDes['bilanUser'] > -3 AND $statsCatDes['bilanUser'] < -0.01)
					echo '<td colspan="4" class="td_stats_admin" style="background-color: #fffd4c;">';
				elseif ($statsCatDes['bilanUser'] > 0.01 AND $statsCatDes['bilanUser'] < 5)
					echo '<td colspan="4" class="td_stats_admin" style="background-color: #b6fc78;">';
				elseif ($statsCatDes['bilanUser'] >= 5)
					echo '<td colspan="4" class="td_stats_admin" style="background-color: #71d058;">';
				elseif ($statsCatDes['bilanUser'] > -0.01 AND $statsCatDes['bilanUser'] < 0.01)
					echo '<td colspan="4" class="td_stats_admin">';
						echo formatBilanForDisplay($statsCatDes['bilanUser']);
					echo '</td>';
			echo '</tr>';
		}

		// Bas du tableau
		echo '<tr>';
			echo '<td colspan="2" class="td_manage_users" style="background-color: #e3e3e3; font-weight: bold;">';
				echo 'Total';
			echo '</td>';

			echo '<td class="td_manage_users">';
				echo $totalCategories['nombreFilms'];
			echo '</td>';

			echo '<td class="td_manage_users">';
				echo $totalCategories['nombreComments'];
			echo '</td>';

			echo '<td class="td_manage_users">';
				echo $totalCategories['nombreCollector'];
			echo '</td>';

			echo '<td class="td_manage_users" style="background-color: #e3e3e3; font-weight: bold;">';
				echo 'Bilan';
			echo '</td>';

			if ($totalCategories['alerteBilan'] == true)
				echo '<td class="td_manage_users" style="font-family: robotolight, Verdana, sans-serif; background-color: #ee4949;">';
			else
				echo '<td class="td_manage_users" style="font-family: robotolight, Verdana, sans-serif;">';
					echo formatBilanForDisplay($totalCategories['sommeBilans']);
				echo '</td>';

			echo '<td class="td_manage_users" style="background-color: #e3e3e3; font-weight: bold;">';
				echo 'Alertes';
			echo '</td>';

			// Alerte si un utilisateur désinscrit n'a pas payé
			echo '<td class="td_manage_users" style="font-family: robotolight, Verdana, sans-serif;">';
				if ($totalCategories['alerteBilan'] == true)
					echo '<span class="reset_warning">!</span>';
			echo '</td>';
		echo '</tr>';
	echo '</table>';
?>
