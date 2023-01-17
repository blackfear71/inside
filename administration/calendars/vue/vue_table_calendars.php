<?php
	echo '<div class="titre_section"><img src="../../includes/icons/admin/calendars_grey.png" alt="calendars_grey" class="logo_titre_section" /><div class="texte_titre_section">Demandes de suppression des calendriers</div></div>';

	echo '<table class="table_manage_users">';
		// Entête du tableau
		echo '<tr class="init_tr_manage_users">';
			echo '<td rowspan="2" class="init_td_manage_users init_td_manage_users_25">';
				echo 'Calendrier';
			echo '</td>';

			echo '<td rowspan="2" class="init_td_manage_users init_td_manage_users_25">';
				echo 'Equipe';
			echo '</td>';

			echo '<td colspan="2" class="init_td_manage_users init_td_manage_users_50">';
				echo 'Suppression du calendrier';
			echo '</td>';
		echo '</tr>';

		echo '<tr class="init_tr_manage_users">';
			echo '<td class="init_td_manage_users init_td_manage_users_25">';
				echo 'Accepter';
			echo '</td>';

			echo '<td class="init_td_manage_users init_td_manage_users_25">';
				echo 'Refuser';
			echo '</td>';
		echo '</tr>';

    	if (!empty($listeSuppression))
    	{
      		foreach ($listeSuppression as $calendrier)
      		{
        		echo '<tr class="tr_manage_users">';
  					echo '<td class="td_manage_users">';
  						echo '<img src="../../includes/images/calendars/' . $calendrier->getYear() . '/mini/' . $calendrier->getCalendar() . '" alt="calendrier" title="' . $calendrier->getTitle() . '" class="calendar_to_delete" />';
            			echo '<div class="title_calendar_to_delete">' . $calendrier->getTitle() . '</div>';
  					echo '</td>';

					echo '<td class="td_manage_users">';
            			echo $calendrier->getTeam();
  					echo '</td>';

          			echo '<td class="td_manage_users">';
						if ($calendrier->getTo_delete() == 'Y')
						{
							echo '<form method="post" action="calendars.php?action=doDeleteCalendrier">';
								echo '<input type="hidden" name="id_calendrier" value="' . $calendrier->getId() . '" />';
								echo '<input type="hidden" name="team_calendrier" value="' . $calendrier->getTeam() . '" />';
								echo '<input type="submit" name="accepter_suppression_calendrier" value="ACCEPTER" class="bouton_admin" />';
							echo '</form>';
						}
  					echo '</td>';

					echo '<td class="td_manage_users">';
						if ($calendrier->getTo_delete() == 'Y')
						{
							echo '<form method="post" action="calendars.php?action=doResetCalendrier">';
								echo '<input type="hidden" name="id_calendrier" value="' . $calendrier->getId() . '" />';
								echo '<input type="hidden" name="team_calendrier" value="' . $calendrier->getTeam() . '" />';
								echo '<input type="submit" name="annuler_suppression_calendrier" value="REFUSER" class="bouton_admin" />';
							echo '</form>';
						}
  					echo '</td>';
  				echo '</tr>';
      		}
    	}
    	else
		{
			echo '<tr>';
				echo '<td colspan="4" class="empty">Pas de calendriers à supprimer...</td>';
			echo '</tr>';
		}

		// Bas du tableau
		echo '<tr>';
			echo '<td colspan="2" class="td_manage_users_important">';
				echo 'Alertes';
			echo '</td>';

			echo '<td colspan="2" class="td_manage_users">';
        		if ($alerteCalendars == true)
          			echo '<span class="reset_warning">!</span>';
			echo '</td>';
		echo '</tr>';
	echo '</table>';
?>