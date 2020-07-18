<?php
	echo '<div class="titre_section"><img src="../../includes/icons/admin/expense_center_grey.png" alt="expense_center_grey" class="logo_titre_section" /><div class="texte_titre_section">Statistiques catégories</div></div>';

	echo '<table class="table_manage_users">';
		// Entête du tableau
		echo '<tr class="init_tr_manage_users">';
			echo '<td rowspan="2" class="init_td_manage_users init_td_manage_users_10">';
				echo 'Identifiant';
			echo '</td>';

			echo '<td rowspan="2" class="init_td_manage_users init_td_manage_users_15">';
				echo 'Pseudo';
			echo '</td>';

			echo '<td colspan="2" class="init_td_manage_users init_td_manage_users_30">';
				echo 'Movie House';
			echo '</td>';

			echo '<td class="init_td_manage_users init_td_manage_users_15">';
				echo 'Collector Room';
			echo '</td>';

			echo '<td colspan="4" class="init_td_manage_users init_td_manage_users_30">';
				echo 'Expense Center';
			echo '</td>';
		echo '</tr>';

		echo '<tr class="init_tr_manage_users">';
			echo '<td class="init_td_manage_users init_td_manage_users_15">';
				echo 'Films ajoutés';
			echo '</td>';

			echo '<td class="init_td_manage_users init_td_manage_users_15">';
				echo 'Commentaires';
			echo '</td>';

			echo '<td class="init_td_manage_users init_td_manage_users_15">';
				echo 'Nombre de phrases cultes rapportées';
			echo '</td>';

			echo '<td colspan="4" class="init_td_manage_users init_td_manage_users_30">';
				echo 'Bilan des dépenses';
			echo '</td>';
		echo '</tr>';

		// Utilisateurs inscrits
    foreach ($tableauStatistiquesIns as $statistiquesIns)
    {
      echo '<tr class="tr_manage_users">';
        echo '<td class="td_manage_users">';
          echo $statistiquesIns->getIdentifiant();
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $statistiquesIns->getPseudo();
        echo '</td>';

				echo '<td class="td_manage_users">';
					echo $statistiquesIns->getNb_films_ajoutes();
        echo '</td>';

        echo '<td class="td_manage_users">';
					echo $statistiquesIns->getNb_films_comments();
        echo '</td>';

				echo '<td class="td_manage_users">';
					echo $statistiquesIns->getNb_collectors();
				echo '</td>';

        if ($statistiquesIns->getExpenses() <= -6)
					echo '<td colspan="4" class="td_stats_admin bilan_red">';
				elseif ($statistiquesIns->getExpenses() > -6 AND $statistiquesIns->getExpenses() <= -3)
					echo '<td colspan="4" class="td_stats_admin bilan_orange">';
				elseif ($statistiquesIns->getExpenses() > -3 AND $statistiquesIns->getExpenses() < -0.01)
					echo '<td colspan="4" class="td_stats_admin bilan_jaune">';
				elseif ($statistiquesIns->getExpenses() > 0.01 AND $statistiquesIns->getExpenses() < 5)
					echo '<td colspan="4" class="td_stats_admin bilan_vert">';
				elseif ($statistiquesIns->getExpenses() >= 5)
					echo '<td colspan="4" class="td_stats_admin bilan_vert_fonce">';
				elseif ($statistiquesIns->getExpenses() > -0.01 AND $statistiquesIns->getExpenses() < 0.01)
					echo '<td colspan="4" class="td_stats_admin">';
						echo formatAmountForDisplay($statistiquesIns->getExpenses());
					echo '</td>';
			echo '</tr>';
    }

		// Séparation utilisateurs
		if (!empty($tableauStatistiquesDes))
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
		foreach ($tableauStatistiquesDes as $statistiquesDes)
		{
			echo '<tr class="tr_manage_users">';
				echo '<td colspan="2" class="td_manage_users">';
					echo $statistiquesDes->getIdentifiant();
				echo '</td>';

				echo '<td class="td_manage_users">';
					echo $statistiquesDes->getNb_films_ajoutes();
				echo '</td>';

				echo '<td class="td_manage_users">';
					echo $statistiquesDes->getNb_films_comments();
				echo '</td>';

				echo '<td class="td_manage_users">';
					echo $statistiquesDes->getNb_collectors();
				echo '</td>';

				if ($statistiquesDes->getExpenses() <= -6)
					echo '<td colspan="4" class="td_stats_admin bilan_red">';
				elseif ($statistiquesDes->getExpenses() > -6 AND $statistiquesDes->getExpenses() <= -3)
					echo '<td colspan="4" class="td_stats_admin bilan_orange">';
				elseif ($statistiquesDes->getExpenses() > -3 AND $statistiquesDes->getExpenses() < -0.01)
					echo '<td colspan="4" class="td_stats_admin bilan_jaune">';
				elseif ($statistiquesDes->getExpenses() > 0.01 AND $statistiquesDes->getExpenses() < 5)
					echo '<td colspan="4" class="td_stats_admin bilan_vert">';
				elseif ($statistiquesDes->getExpenses() >= 5)
					echo '<td colspan="4" class="td_stats_admin bilan_vert_fonce">';
				elseif ($statistiquesDes->getExpenses() > -0.01 AND $statistiquesDes->getExpenses() < 0.01)
					echo '<td colspan="4" class="td_stats_admin">';
						echo formatAmountForDisplay($statistiquesDes->getExpenses());
					echo '</td>';
			echo '</tr>';
		}

		// Bas du tableau
		echo '<tr>';
			echo '<td colspan="2" class="td_manage_users_important">';
				echo 'Total';
			echo '</td>';

			echo '<td class="td_manage_users">';
				echo $totalStatistiques->getNb_films_ajoutes_total();
			echo '</td>';

			echo '<td class="td_manage_users">';
				echo $totalStatistiques->getNb_films_comments_total();
			echo '</td>';

			echo '<td class="td_manage_users">';
				echo $totalStatistiques->getNb_collectors_total();
			echo '</td>';

			echo '<td class="td_manage_users_important td_manage_users_7">';
				echo 'Bilan';
			echo '</td>';

			if ($totalStatistiques->getAlerte_expenses() == true)
				echo '<td class="td_manage_users_red td_manage_users_7">';
			else
				echo '<td class="td_manage_users td_manage_users_7">';
					echo formatAmountForDisplay($totalStatistiques->getExpenses_total());
				echo '</td>';

			echo '<td class="td_manage_users_important td_manage_users_7">';
				echo 'Alertes';
			echo '</td>';

			// Alerte si un utilisateur désinscrit n'a pas payé
			echo '<td class="td_manage_users td_manage_users_7">';
				if ($totalStatistiques->getAlerte_expenses() == true)
					echo '<span class="reset_warning">!</span>';
			echo '</td>';
		echo '</tr>';
	echo '</table>';
?>
