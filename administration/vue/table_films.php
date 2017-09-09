<?php
	echo '<div class="title_gestion">Demandes de suppression de film</div>';

	echo '<table class="table_manage_users">';
		// Entête du tableau
		echo '<tr class="init_tr_manage_users">';
			echo '<td rowspan="2" class="init_td_manage_users" style="width: 10%;">';
				echo 'Film';
			echo '</td>';

			echo '<td colspan="2" class="init_td_manage_users" style="width: 35%;">';
				echo 'Suppression du film';
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
      foreach ($listeSuppression as $film)
      {
        echo '<tr class="tr_manage_users">';
  				echo '<td class="td_manage_users">';
  					echo $film->getFilm();
  				echo '</td>';

          echo '<td class="td_manage_users">';
            if ($film->getTo_delete() == "Y")
            {
    					echo '<form method="post" action="manage_films.php?delete_id=' . $film->getId() . '&action=doDeleteFilm">';
    						echo '<input type="submit" name="accepter_suppression_film" value="ACCEPTER" class="bouton_admin" />';
    					echo '</form>';
            }
  				echo '</td>';

          echo '<td class="td_manage_users">';
            if ($film->getTo_delete() == "Y")
            {
    					echo '<form method="post" action="manage_films.php?delete_id=' . $film->getId() . '&action=doResetFilm">';
    						echo '<input type="submit" name="annuler_suppression_film" value="REFUSER" class="bouton_admin" />';
    					echo '</form>';
            }
  				echo '</td>';
  			echo '</tr>';
      }
    }
    else
      echo '<td colspan="3"class="td_manage_users" style="line-height: 100px;">Pas de films à supprimer !</td>';

		// Bas du tableau
		echo '<tr>';
			echo '<td class="td_manage_users" style="background-color: #e3e3e3; font-weight: bold;">';
				echo 'Alertes';
			echo '</td>';

			echo '<td colspan="2"class="td_manage_users">';
        if ($alerteFilms == true)
          echo '<span class="reset_warning">!</span>';
			echo '</td>';
		echo '</tr>';
	echo '</table>';
?>
