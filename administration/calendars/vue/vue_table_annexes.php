<?php
	echo '<div class="titre_section"><img src="../../includes/icons/admin/calendars_grey.png" alt="calendars_grey" class="logo_titre_section" /><div class="texte_titre_section">Demandes de suppression des annexes</div></div>';

	echo '<table class="table_manage_users">';
		// Entête du tableau
		echo '<tr class="init_tr_manage_users">';
			echo '<td rowspan="2" class="init_td_manage_users init_td_manage_users_50">';
				echo 'Annexe';
			echo '</td>';

			echo '<td colspan="2" class="init_td_manage_users init_td_manage_users_50">';
				echo 'Suppression de l\'annexe';
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

    if (!empty($listeSuppressionAnnexes))
    {
      foreach ($listeSuppressionAnnexes as $annexes)
      {
        echo '<tr class="tr_manage_users">';
  				echo '<td class="td_manage_users">';
  					echo '<img src="../../includes/images/calendars/annexes/mini/' . $annexes->getAnnexe() . '" alt="calendrier" title="' . $annexes->getTitle() . '" class="calendar_to_delete" />';
            echo '<div class="title_calendar_to_delete">' . $annexes->getTitle() . '</div>';
  				echo '</td>';

          echo '<td class="td_manage_users">';
            if ($annexes->getTo_delete() == 'Y')
            {
    					echo '<form method="post" action="calendars.php?action=doDeleteAnnexe">';
								echo '<input type="hidden" name="id_annexe" value="' . $annexes->getId() . '" />';
    						echo '<input type="submit" name="accepter_suppression_annexe" value="ACCEPTER" class="bouton_admin" />';
    					echo '</form>';
            }
  				echo '</td>';

          echo '<td class="td_manage_users">';
            if ($annexes->getTo_delete() == 'Y')
            {
    					echo '<form method="post" action="calendars.php?action=doResetAnnexe">';
								echo '<input type="hidden" name="id_annexe" value="' . $annexes->getId() . '" />';
    						echo '<input type="submit" name="annuler_suppression_annexe" value="REFUSER" class="bouton_admin" />';
    					echo '</form>';
            }
  				echo '</td>';
  			echo '</tr>';
      }
    }
    else
		{
			echo '<tr>';
				echo '<td colspan="3" class="empty">Pas d\'annexes à supprimer !</td>';
			echo '</tr>';
		}

		// Bas du tableau
		echo '<tr>';
			echo '<td class="td_manage_users_important">';
				echo 'Alertes';
			echo '</td>';

			echo '<td colspan="2" class="td_manage_users">';
        if ($alerteAnnexes == true)
          echo '<span class="reset_warning">!</span>';
			echo '</td>';
		echo '</tr>';
	echo '</table>';
?>
