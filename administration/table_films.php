<?php
	echo '<div class="title_gestion">Gestion des utilisateurs</div>';

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

		include('../includes/appel_bdd.php');

		// Recherche des données du film
		$reponse = $bdd->query('SELECT id, film, to_delete FROM movie_house WHERE to_delete = "Y" ORDER BY id ASC');

		while($donnees = $reponse->fetch())
		{
			echo '<tr class="tr_manage_users">';
				echo '<td class="td_manage_users">';
					echo $donnees['film'];
				echo '</td>';

        echo '<td class="td_manage_users">';
          if ($donnees['to_delete'] == "Y")
          {
  					echo '<form method="post" action="delete_film.php?delete_id=' . $donnees['id'] . '">';
  						echo '<input type="submit" name="accepter_suppression_film" value="ACCEPTER" class="reset_password" />';
  					echo '</form>';
          }
				echo '</td>';

        echo '<td class="td_manage_users">';
          if ($donnees['to_delete'] == "Y")
          {
  					echo '<form method="post" action="delete_film.php?delete_id=' . $donnees['id'] . '">';
  						echo '<input type="submit" name="annuler_suppression_film" value="REFUSER" class="reset_password" />';
  					echo '</form>';
          }
				echo '</td>';
			echo '</tr>';
		}

		$reponse->closeCursor();

		// Bas du tableau
		echo '<tr>';
			echo '<td class="td_manage_users" style="background-color: #e3e3e3; font-weight: bold;">';
				echo 'Alertes';
			echo '</td>';

			echo '<td colspan="2"class="td_manage_users">';
				$req1 = $bdd->query('SELECT id, to_delete FROM movie_house WHERE to_delete = "Y"');
				while($data1 = $req1->fetch())
				{
					if ($data1['to_delete'] == "Y")
					{
						echo '<span class="reset_warning">!</span>';
						break;
					}
				}
				$req1->closeCursor();
			echo '</td>';
		echo '</tr>';
	echo '</table>';
?>
