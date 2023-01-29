<?php
	// Titre
	echo '<div class="titre_section"><img src="../../includes/icons/admin/calendars_grey.png" alt="calendars_grey" class="logo_titre_section" /><div class="texte_titre_section">Demandes de suppression des calendriers</div></div>';

	// Tableau des calendriers à supprimer
	echo '<table class="table_calendriers">';
		// Entête du tableau
		echo '<tr>';
			echo '<td class="width_10">';
				echo 'Calendrier';
			echo '</td>';

			echo '<td class="width_30">';
				echo 'Période';
			echo '</td>';

			echo '<td class="width_30">';
				echo 'Equipe';
			echo '</td>';

			echo '<td class="width_30">';
				echo 'Actions';
			echo '</td>';
		echo '</tr>';

		// Contenu du tableau
    	if (!empty($listeSuppression))
    	{
      		foreach ($listeSuppression as $calendrier)
      		{
        		echo '<tr>';
  					echo '<td class="td_calendrier">';
  						echo '<img src="../../includes/images/calendars/' . $calendrier->getYear() . '/mini/' . $calendrier->getCalendar() . '" alt="calendrier" title="' . $calendrier->getTitle() . '" class="calendrier_a_supprimer" />';
  					echo '</td>';

					echo '<td class="td_periode_calendrier">';
						echo $calendrier->getTitle();
					echo '</td>';

					echo '<td class="td_equipe_calendrier">';
						echo $calendrier->getTeam();
  					echo '</td>';

          			echo '<td class="td_actions_calendrier">';
						// Valider
						echo '<form method="post" action="calendars.php?action=doDeleteCalendrier" class="lien_action_alerte">';
							echo '<input type="hidden" name="id_calendrier" value="' . $calendrier->getId() . '" />';
							echo '<input type="hidden" name="team_calendrier" value="' . $calendrier->getTeam() . '" />';
							echo '<input type="submit" name="accepter_suppression_calendrier" value="" title="Accepter" class="icone_valider_calendrier" />';
						echo '</form>';

						// Refuser
						echo '<form method="post" action="calendars.php?action=doResetCalendrier" class="lien_action_alerte">';
							echo '<input type="hidden" name="id_calendrier" value="' . $calendrier->getId() . '" />';
							echo '<input type="hidden" name="team_calendrier" value="' . $calendrier->getTeam() . '" />';
							echo '<input type="submit" name="annuler_suppression_calendrier" value="" title="Refuser" class="icone_annuler_calendrier" />';
						echo '</form>';
  					echo '</td>';
  				echo '</tr>';
      		}
    	}
    	else
		{
			echo '<tr class="tr_calendriers_empty">';
				echo '<td colspan="4" class="empty">Pas de calendriers à supprimer...</td>';
			echo '</tr>';
		}
	echo '</table>';
?>