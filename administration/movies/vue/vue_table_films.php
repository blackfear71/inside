<?php
	echo '<div class="titre_section"><img src="../../includes/icons/admin/movie_house_grey.png" alt="movie_house_grey" class="logo_titre_section" /><div class="texte_titre_section">Demandes de suppression de films</div></div>';

	echo '<table class="table_manage_users">';
		// Entête du tableau
		echo '<tr class="init_tr_manage_users">';
			echo '<td rowspan="2" class="init_td_manage_users init_td_manage_users_25">';
				echo 'Film';
			echo '</td>';

			echo '<td colspan="2" class="init_td_manage_users init_td_manage_users_30">';
				echo 'Suppression du film';
			echo '</td>';

			echo '<td rowspan="2" class="init_td_manage_users init_td_manage_users_15">';
				echo 'Demande suppression par';
			echo '</td>';

			echo '<td rowspan="2" class="init_td_manage_users init_td_manage_users_15">';
				echo 'Ajouté par';
			echo '</td>';

			echo '<td rowspan="2" class="init_td_manage_users init_td_manage_users_15">';
				echo 'Personnes intéressées';
			echo '</td>';
		echo '</tr>';

		echo '<tr class="init_tr_manage_users">';
			echo '<td class="init_td_manage_users init_td_manage_users_15">';
				echo 'Accepter';
			echo '</td>';

			echo '<td class="init_td_manage_users init_td_manage_users_15">';
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
            if ($film->getTo_delete() == 'Y')
            {
    					echo '<form method="post" action="movies.php?action=doDeleteFilm">';
    						echo '<input type="hidden" name="id_film" value="' . $film->getId() . '" />';
								echo '<input type="submit" name="accepter_suppression_film" value="ACCEPTER" class="bouton_admin" />';
    					echo '</form>';
            }
  				echo '</td>';

          echo '<td class="td_manage_users">';
            if ($film->getTo_delete() == 'Y')
            {
    					echo '<form method="post" action="movies.php?action=doResetFilm">';
    						echo '<input type="hidden" name="id_film" value="' . $film->getId() . '" />';
								echo '<input type="submit" name="annuler_suppression_film" value="REFUSER" class="bouton_admin" />';
    					echo '</form>';
            }
  				echo '</td>';

					echo '<td class="td_manage_users">';
						echo formatUnknownUser($film->getPseudo_del(), true, true) . ' (' . $film->getIdentifiant_del() . ')';
					echo '</td>';

					echo '<td class="td_manage_users">';
						echo formatUnknownUser($film->getPseudo_add(), true, true) . ' (' . $film->getIdentifiant_add() . ')';
					echo '</td>';

					echo '<td class="td_manage_users">';
						echo $film->getNb_users();
					echo '</td>';
  			echo '</tr>';
      }
    }
    else
		{
			echo '<tr>';
				echo '<td colspan="6" class="empty">Pas de films à supprimer...</td>';
			echo '</tr>';
		}

		// Bas du tableau
		echo '<tr>';
			echo '<td class="td_manage_users_important">';
				echo 'Alertes';
			echo '</td>';

			echo '<td colspan="5" class="td_manage_users">';
        if ($alerteFilms == true)
          echo '<span class="reset_warning">!</span>';
			echo '</td>';
		echo '</tr>';
	echo '</table>';
?>
