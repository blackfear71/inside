<?php
	echo '<div class="titre_section"><img src="../../includes/icons/admin/stats_grey.png" alt="stats_grey" class="logo_titre_section" /><div class="texte_titre_section">Statistiques demandes & publications</div></div>';

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
				echo 'Bugs / évolutions';
			echo '</td>';

			echo '<td colspan="3" class="init_td_manage_users init_td_manage_users_45">';
				echo '#TheBox';
			echo '</td>';
		echo '</tr>';

		echo '<tr class="init_tr_manage_users">';
			echo '<td class="init_td_manage_users init_td_manage_users_15">';
				echo 'Nombre de demandes (bugs / évolutions)';
			echo '</td>';

			echo '<td class="init_td_manage_users init_td_manage_users_15">';
				echo 'Nombre de demandes résolues (bugs / évolutions)';
			echo '</td>';

			echo '<td class="init_td_manage_users init_td_manage_users_15">';
				echo 'Nombre d\'idées publiées';
			echo '</td>';

			echo '<td class="init_td_manage_users init_td_manage_users_15">';
				echo 'Nombre d\'idées en charge';
			echo '</td>';

			echo '<td class="init_td_manage_users init_td_manage_users_15">';
				echo 'Nombre d\'idées terminées ou rejetées';
			echo '</td>';
		echo '</tr>';

		// Statistiques des utlisateurs inscrits
    foreach ($tableauStats['inscrits'] as $stats)
    {
      echo '<tr class="tr_manage_users">';
        echo '<td class="td_manage_users">';
          echo $stats['identifiant'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $stats['pseudo'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $stats['nombreBugsSoumis'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $stats['nombreBugsResolus'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $stats['nombreTheBox'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $stats['nombreTheBoxEnCharge'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $stats['nombreTheBoxTerminees'];
        echo '</td>';
      echo '</tr>';
    }

		// Séparation utilisateurs
		if (!empty($tableauStats['desinscrits']))
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
		foreach ($tableauStats['desinscrits'] as $stats)
    {
      echo '<tr class="tr_manage_users">';
        echo '<td colspan="2" class="td_manage_users">';
          echo $stats['identifiant'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $stats['nombreBugsSoumis'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $stats['nombreBugsResolus'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $stats['nombreTheBox'];
        echo '</td>';

        echo '<td class="td_manage_users">';
					if ($stats['nombreTheBoxEnCharge'] == 0)
						echo 'N/A';
					else
          	echo $stats['nombreTheBoxEnCharge'];
        echo '</td>';

        echo '<td class="td_manage_users">';
          echo $stats['nombreTheBoxTerminees'];
        echo '</td>';
      echo '</tr>';
    }

		// Total
		echo '<tr>';
			echo '<td colspan="2" class="td_manage_users_important">';
				echo 'Total';
			echo '</td>';

			echo '<td class="td_manage_users">';
				echo $totalStats['nombreBugsSoumis'];
			echo '</td>';

      echo '<td class="td_manage_users">';
				echo $totalStats['nombreBugsResolus'];
			echo '</td>';

      echo '<td class="td_manage_users">';
				echo $totalStats['nombreTheBox'];
			echo '</td>';

      echo '<td class="td_manage_users">';
				echo $totalStats['nombreTheBoxEnCharge'];
			echo '</td>';

      echo '<td class="td_manage_users">';
				echo $totalStats['nombreTheBoxTerminees'];
			echo '</td>';
		echo '</tr>';
	echo '</table>';
?>
