<?php
	// Titre
	echo '<div class="titre_section"><img src="../../includes/icons/admin/calendars_grey.png" alt="calendars_grey" class="logo_titre_section" /><div class="texte_titre_section">Demandes de suppression des calendriers</div></div>';

	// Tableau des calendriers à supprimer
	echo '<table class="table_admin">';
		// Entête du tableau
		echo '<tr>';
			echo '<td class="width_10">';
				echo 'Calendrier';
			echo '</td>';

			echo '<td class="width_40">';
				echo 'Période';
			echo '</td>';

			echo '<td class="width_30">';
				echo 'Equipe';
			echo '</td>';

			echo '<td class="width_20">';
				echo 'Actions';
			echo '</td>';
		echo '</tr>';

		// Contenu du tableau
    	if (!empty($listeSuppression))
    	{
      		foreach ($listeSuppression as $calendrier)
      		{
        		echo '<tr>';
  					echo '<td class="td_table_admin_premier">';
  						echo '<img src="../../includes/images/calendars/' . $calendrier->getYear() . '/mini/' . $calendrier->getCalendar() . '" alt="calendrier" title="' . $calendrier->getTitle() . '" class="image_table_admin" />';
  					echo '</td>';

					echo '<td class="td_table_admin_important_centre">';
						echo $calendrier->getTitle();
					echo '</td>';

					echo '<td class="td_table_admin_centre">';
						echo $calendrier->getTeam();
  					echo '</td>';

          			echo '<td class="td_table_admin_actions">';
						// Valider
						echo '<form method="post" action="calendars.php?action=doDeleteCalendrier" class="lien_action_table_admin">';
							echo '<input type="hidden" name="id_calendrier" value="' . $calendrier->getId() . '" />';
							echo '<input type="hidden" name="team_calendrier" value="' . $calendrier->getTeam() . '" />';
							echo '<input type="submit" name="accepter_suppression_calendrier" value="" title="Accepter" class="icone_valider_table_admin" />';
						echo '</form>';

						// Refuser
						echo '<form method="post" action="calendars.php?action=doResetCalendrier" class="lien_action_table_admin">';
							echo '<input type="hidden" name="id_calendrier" value="' . $calendrier->getId() . '" />';
							echo '<input type="hidden" name="team_calendrier" value="' . $calendrier->getTeam() . '" />';
							echo '<input type="submit" name="annuler_suppression_calendrier" value="" title="Refuser" class="icone_annuler_table_admin" />';
						echo '</form>';
  					echo '</td>';
  				echo '</tr>';
      		}
    	}
    	else
		{
			echo '<tr class="tr_table_admin_empty">';
				echo '<td colspan="4" class="empty">Pas de calendriers à supprimer...</td>';
			echo '</tr>';
		}
	echo '</table>';
?>