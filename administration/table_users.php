<?php
	echo '<table class="table_manage_users">';
		// Entête du tableau
		echo '<tr class="init_tr_manage_users">';
			echo '<td class="init_td_manage_users" style="width: 10%;">';
				echo 'Identifiant';
			echo '</td>';
			
			echo '<td class="init_td_manage_users" style="width: 15%;">';
				echo 'Pseudo';
			echo '</td>';
			
			echo '<td class="init_td_manage_users" style="width: 15%;">';
				echo 'Nombre d\'articles publiés';
			echo '</td>';
			
			echo '<td class="init_td_manage_users" style="width: 15%;">';
				echo 'Nombre de demandes (bugs/évolutions)';
			echo '</td>';
			
			echo '<td class="init_td_manage_users" style="width: 15%;">';
				echo 'Nombre de demandes résolues (bugs/évolutions)';
			echo '</td>';
			
			echo '<td class="init_td_manage_users" style="width: 15%;">';
				echo 'Demande de réinitialisation du mot de passe';
			echo '</td>';
			
			echo '<td class="init_td_manage_users" style="width: 15%;">';
				echo 'Annuler la demande de réinitialisation du mot de passe';
			echo '</td>';
			
			echo '<td class="init_td_manage_users" style="width: 15%;">';
				echo 'Réinitialiser le mot de passe';
			echo '</td>';
		echo '</tr>';
	
		include('../includes/appel_bdd.php');
		
		// Recherche des données utilisateurs
		$reponse = $bdd->query('SELECT id, identifiant, full_name, reset FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
		
		while($donnees = $reponse->fetch())
		{
			echo '<tr class="tr_manage_users">';
				echo '<td class="td_manage_users">';
					echo $donnees['identifiant'];
				echo '</td>';
				
				echo '<td class="td_manage_users">';
					echo $donnees['full_name'];
				echo '</td>';
				
				echo '<td class="td_manage_users">';
					$req1 = $bdd->query('SELECT COUNT(id) AS nb_publications FROM reference_guide WHERE author = "' . $donnees['identifiant'] . '"');
					$data1 = $req1->fetch();
					echo $data1['nb_publications'];											
					$req1->closeCursor();
				echo '</td>';
				
				echo '<td class="td_manage_users">';
					$req2 = $bdd->query('SELECT COUNT(id) AS nb_bugs FROM bugs WHERE author = "' . $donnees['identifiant'] . '"');
					$data2 = $req2->fetch();
					echo $data2['nb_bugs'];
					$req2->closeCursor();
				echo '</td>';
				
				echo '<td class="td_manage_users">';
					$req3 = $bdd->query('SELECT COUNT(id) AS nb_bugs FROM bugs WHERE author = "' . $donnees['identifiant'] . '" AND resolved = "Y"');
					$data3 = $req3->fetch();
					echo $data3['nb_bugs'];
					$req3->closeCursor();
				echo '</td>';
				
				echo '<td class="td_manage_users">';
					if ($donnees['reset'] == "Y")
						echo 'Oui';
					else
						echo 'Non';
				echo '</td>';
				
				echo '<td class="td_manage_users">';
					if ($donnees['reset'] == "Y")
					{
						echo '<form method="post" action="reset_password.php?id_user=' . $donnees['id'] . '">';
							echo '<input type="submit" name="annuler_reinitialisation" value="ANNULER" class="reset_password" />';
						echo '</form>';
					}
				echo '</td>';
				
				echo '<td class="td_manage_users">';
					if ($donnees['reset'] == "Y")
					{
						echo '<form method="post" action="reset_password.php?id_user=' . $donnees['id'] . '">';
							echo '<input type="submit" name="reinitialiser" value="REINITIALISER" class="reset_password" />';
						echo '</form>';
					}
				echo '</td>';
			echo '</tr>';
		}
		
		$reponse->closeCursor();

		// Bas du tableau		
		echo '<tr>';
			echo '<td colspan="2" class="td_manage_users" style="background-color: #e3e3e3; font-weight: bold;">';
				echo 'Total';
			echo '</td>';
			
			echo '<td class="td_manage_users">';
				$req4 = $bdd->query('SELECT COUNT(id) AS nb_total_articles FROM reference_guide');
				$data4 = $req4->fetch();
				echo $data4['nb_total_articles'];
				$req4->closeCursor();
			echo '</td>';
			
			echo '<td class="td_manage_users">';
				$req5 = $bdd->query('SELECT COUNT(id) AS nb_total_bugs FROM bugs');
				$data5 = $req5->fetch();
				echo $data5['nb_total_bugs'];
				$req5->closeCursor();
			echo '</td>';
			
			echo '<td class="td_manage_users">';
				$req6 = $bdd->query('SELECT COUNT(id) AS nb_total_resolved FROM bugs WHERE resolved = "Y"');
				$data6 = $req6->fetch();
				echo $data6['nb_total_resolved'];
				$req6->closeCursor();
			echo '</td>';
			
			echo '<td colspan="3"class="td_manage_users">';
				$req7 = $bdd->query('SELECT id, identifiant, full_name, reset FROM users WHERE identifiant != "admin" ORDER BY identifiant ASC');
				while($data7 = $req7->fetch())
				{
					if ($data7['reset'] == "Y")
					{
						echo '<span class="reset_warning">!</span>';
						break;
					}
				}
				$req7->closeCursor();
			echo '</td>';
		echo '</tr>';
	echo '</table>';
?>