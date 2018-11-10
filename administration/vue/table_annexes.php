<?php
	echo '<div class="title_gestion">Demandes de suppression des annexes</div>';

	echo '<table class="table_manage_users" style="	margin-bottom: 0;">';
		// Entête du tableau
		echo '<tr class="init_tr_manage_users">';
			echo '<td rowspan="2" class="init_td_manage_users" style="width: 10%;">';
				echo 'Annexe';
			echo '</td>';

			echo '<td colspan="2" class="init_td_manage_users" style="width: 35%;">';
				echo 'Suppression de l\'annexe';
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

    if (!empty($listeSuppressionAnnexes))
    {
      foreach ($listeSuppressionAnnexes as $annexes)
      {
        echo '<tr class="tr_manage_users">';
  				echo '<td class="td_manage_users" style="padding-bottom: 10px;">';
  					echo '<img src="../includes/images/calendars/annexes/mini/' . $annexes->getAnnexe() . '" alt="calendrier" title="' . $annexes->getTitle() . '" class="calendar_to_delete" />';
            echo '<span class="title_calendar_to_delete">' . $annexes->getTitle() . '</span>';
  				echo '</td>';

          echo '<td class="td_manage_users">';
            if ($annexes->getTo_delete() == "Y")
            {
    					echo '<form method="post" action="manage_calendars.php?delete_id=' . $annexes->getId() . '&action=doDeleteAnnexe">';
    						echo '<input type="submit" name="accepter_suppression_annexe" value="ACCEPTER" class="bouton_admin" />';
    					echo '</form>';
            }
  				echo '</td>';

          echo '<td class="td_manage_users">';
            if ($annexes->getTo_delete() == "Y")
            {
    					echo '<form method="post" action="manage_calendars.php?delete_id=' . $annexes->getId() . '&action=doResetAnnexe">';
    						echo '<input type="submit" name="annuler_suppression_annexe" value="REFUSER" class="bouton_admin" />';
    					echo '</form>';
            }
  				echo '</td>';
  			echo '</tr>';
      }
    }
    else
      echo '<td colspan="3" class="td_manage_users" style="line-height: 100px;">Pas d\'annexes à supprimer !</td>';

		// Bas du tableau
		echo '<tr>';
			echo '<td class="td_manage_users" style="background-color: #e3e3e3; font-weight: bold;">';
				echo 'Alertes';
			echo '</td>';

			echo '<td colspan="2" class="td_manage_users">';
        if ($alerteAnnexes == true)
          echo '<span class="reset_warning">!</span>';
			echo '</td>';
		echo '</tr>';
	echo '</table>';
?>
