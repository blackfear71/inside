<?php
	echo '<div class="title_gestion">Demandes de suppression de calendriers</div>';

	echo '<table class="table_manage_users">';
		// Entête du tableau
		echo '<tr class="init_tr_manage_users">';
			echo '<td rowspan="2" class="init_td_manage_users" style="width: 10%;">';
				echo 'Calendrier';
			echo '</td>';

			echo '<td colspan="2" class="init_td_manage_users" style="width: 35%;">';
				echo 'Suppression du calendrier';
			echo '</td>';
		echo '</tr>';

		echo '<tr class="init_tr_manage_users">';
			echo '<td class="init_td_manage_users" style="width: 10%;">';
				echo 'Accepter';
			echo '</td>';

			echo '<td class="init_td_manage_users" style="width: 10%;">';
				echo 'Refuser';
			echo '</td>';
		echo '</tr>';

    if (!empty($listeSuppression))
    {
      foreach ($listeSuppression as $calendrier)
      {
        echo '<tr class="tr_manage_users">';
  				echo '<td class="td_manage_users" style="padding-bottom: 10px;">';
  					echo '<img src="../includes/images/calendars/' . $calendrier->getYear() . '/mini/' . $calendrier->getCalendar() . '" alt="calendrier" title="' . $calendrier->getTitle() . '" class="calendar_to_delete" />';
            echo '<span class="title_calendar_to_delete">' . $calendrier->getTitle() . '</span>';
  				echo '</td>';

          echo '<td class="td_manage_users">';
            if ($calendrier->getTo_delete() == "Y")
            {
    					echo '<form method="post" action="manage_calendars.php?action=doDeleteCalendrier">';
								echo '<input type="hidden" name="id_cal" value="' . $calendrier->getId() . '" />';
    						echo '<input type="submit" name="accepter_suppression_calendrier" value="ACCEPTER" class="bouton_admin" />';
    					echo '</form>';
            }
  				echo '</td>';

          echo '<td class="td_manage_users">';
            if ($calendrier->getTo_delete() == "Y")
            {
    					echo '<form method="post" action="manage_calendars.php?action=doResetCalendrier">';
								echo '<input type="hidden" name="id_cal" value="' . $calendrier->getId() . '" />';
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
				echo '<td colspan="3" class="td_manage_users" style="line-height: 100px;">Pas de calendriers à supprimer !</td>';
			echo '</tr>';
		}

		// Bas du tableau
		echo '<tr>';
			echo '<td class="td_manage_users" style="background-color: #e3e3e3; font-weight: bold;">';
				echo 'Alertes';
			echo '</td>';

			echo '<td colspan="2" class="td_manage_users">';
        if ($alerteCalendars == true)
          echo '<span class="reset_warning">!</span>';
			echo '</td>';
		echo '</tr>';
	echo '</table>';
?>
