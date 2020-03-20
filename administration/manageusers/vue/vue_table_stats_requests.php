<?php
	echo '<div class="titre_section"><img src="../../includes/icons/admin/stats_grey.png" alt="stats_grey" class="logo_titre_section" /><div class="texte_titre_section">Statistiques demandes & publications</div></div>';

	echo '<table class="table_manage_users" style="margin-bottom: 0;">';
		// Entête du tableau
		echo '<tr class="init_tr_manage_users">';
			echo '<td rowspan="2" class="init_td_manage_users" style="width: 10%;">';
				echo 'Identifiant';
			echo '</td>';

			echo '<td rowspan="2" class="init_td_manage_users" style="width: 15%;">';
				echo 'Pseudo';
			echo '</td>';

			echo '<td colspan="2" class="init_td_manage_users" style="width: 37.5%;">';
				echo 'Bugs / évolutions';
			echo '</td>';

			echo '<td colspan="3" class="init_td_manage_users" style="width: 37.5%;">';
				echo '#TheBox';
			echo '</td>';
		echo '</tr>';

		echo '<tr class="init_tr_manage_users">';
			echo '<td class="init_td_manage_users">';
				echo 'Nombre de demandes (bugs/évolutions)';
			echo '</td>';

			echo '<td class="init_td_manage_users">';
				echo 'Nombre de demandes résolues (bugs/évolutions)';
			echo '</td>';

			echo '<td class="init_td_manage_users">';
				echo 'Nombre d\'idées publiées';
			echo '</td>';

			echo '<td class="init_td_manage_users">';
				echo 'Nombre d\'idées en charge';
			echo '</td>';

			echo '<td class="init_td_manage_users">';
				echo 'Nombre d\'idées terminées ou rejetées';
			echo '</td>';
		echo '</tr>';

		// Statistiques des utlisateurs inscrits
    foreach ($tabStats['inscrits'] as $stats)
    {
      echo '<tr class="tr_manage_users">';
        echo '<td class="td_manage_users">';
          echo $stats['identifiant'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $stats['pseudo'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $stats['nb_bugs'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $stats['nb_bugs_resolved'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $stats['nb_ideas'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $stats['nb_ideas_inprogress'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $stats['nb_ideas_finished'];
        echo '</td>';
      echo '</tr>';
    }

		// Séparation utilisateurs
		if (!empty($tabStats['desinscrits']))
		{
			echo '<tr>';
				echo '<td class="table_old_users" colspan="7">';
					echo '<div class="banderole_left_1"></div><div class="banderole_left_2"></div>';
					echo 'Anciens utilisateurs';
					echo '<div class="banderole_left_3"></div><div class="banderole_left_4"></div>';
				echo '</td>';
			echo '</tr>';
		}

		// Statistiques des utlisateurs désinscrits
		foreach ($tabStats['desinscrits'] as $stats)
    {
      echo '<tr class="tr_manage_users">';
        echo '<td colspan="2" class="td_manage_users">';
          echo $stats['identifiant'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $stats['nb_bugs'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $stats['nb_bugs_resolved'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $stats['nb_ideas'];
        echo '</td>';

        echo '<td class="td_manage_users">';
					if ($stats['nb_ideas_inprogress'] == 0)
						echo 'N/A';
					else
          	echo $stats['nb_ideas_inprogress'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $stats['nb_ideas_finished'];
        echo '</td>';
      echo '</tr>';
    }

		// Total
		echo '<tr>';
			echo '<td colspan="2" class="td_manage_users" style="background-color: #e3e3e3; font-weight: bold;">';
				echo 'Total';
			echo '</td>';

			echo '<td class="td_manage_users">';
				echo $totalStats['nb_tot_bugs'];
			echo '</td>';

      echo '<td class="td_manage_users">';
				echo $totalStats['nb_tot_bugs_resolus'];
			echo '</td>';

      echo '<td class="td_manage_users">';
				echo $totalStats['nb_tot_idees'];
			echo '</td>';

      echo '<td class="td_manage_users">';
				echo $totalStats['nb_tot_idees_en_charge'];
			echo '</td>';

      echo '<td class="td_manage_users">';
				echo $totalStats['nb_tot_idees_terminees'];
			echo '</td>';
		echo '</tr>';
	echo '</table>';
?>
