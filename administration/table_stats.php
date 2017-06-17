<?php
	echo '<div class="title_gestion">Statistiques</div>';
	
	echo '<table class="table_manage_users">';
		// Entête du tableau
		echo '<tr class="init_tr_manage_users">';
			echo '<td rowspan="2" class="init_td_manage_users" style="width: 10%;">';
				echo 'Identifiant';
			echo '</td>';
			
			echo '<td rowspan="2" class="init_td_manage_users" style="width: 15%;">';
				echo 'Pseudo';
			echo '</td>';
			
			echo '<td class="init_td_manage_users" style="width: 15%;">';
				echo 'ReferenceGuide';
			echo '</td>';
			
			echo '<td colspan="2" class="init_td_manage_users" style="width: 30%;">';
				echo 'Bugs / évolutions';
			echo '</td>';
			
			echo '<td colspan="3" class="init_td_manage_users" style="width: 30%;">';
				echo '#TheBox';
			echo '</td>';
		echo '</tr>';
		
		echo '<tr class="init_tr_manage_users">';
			echo '<td class="init_td_manage_users" style="width: 15%;">';
				echo 'Nombre d\'articles publiés';
			echo '</td>';
			
			echo '<td class="init_td_manage_users" style="width: 15%;">';
				echo 'Nombre de demandes (bugs/évolutions)';
			echo '</td>';
			
			echo '<td class="init_td_manage_users" style="width: 15%;">';
				echo 'Nombre de demandes résolues (bugs/évolutions)';
			echo '</td>';
			
			echo '<td class="init_td_manage_users" style="width: 10%;">';
				echo 'Nombre d\'idées publiées';
			echo '</td>';
			
			echo '<td class="init_td_manage_users" style="width: 10%;">';
				echo 'Nombre d\'idées en charge';
			echo '</td>';
			
			echo '<td class="init_td_manage_users" style="width: 10%;">';
				echo 'Nombre d\'idées terminées ou rejetées';
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
					$req4 = $bdd->query('SELECT COUNT(id) AS nb_ideas FROM ideas WHERE author = "' . $donnees['identifiant'] . '"');
					$data4 = $req4->fetch();
					echo $data4['nb_ideas'];											
					$req4->closeCursor();
				echo '</td>';
				
				echo '<td class="td_manage_users">';
					$req5 = $bdd->query('SELECT COUNT(id) AS nb_ideas_inprogress FROM ideas WHERE developper = "' . $donnees['identifiant'] . '" AND status != "D" AND status != "R"');
					$data5 = $req5->fetch();
					echo $data5['nb_ideas_inprogress'];
					$req5->closeCursor();
				echo '</td>';
				
				echo '<td class="td_manage_users">';
					$req6 = $bdd->query('SELECT COUNT(id) AS nb_ideas_finished FROM ideas WHERE developper = "' . $donnees['identifiant'] . '" AND (status = "D" OR status = "R")');
					$data6 = $req6->fetch();
					echo $data6['nb_ideas_finished'];
					$req6->closeCursor();
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
				$req7 = $bdd->query('SELECT COUNT(id) AS nb_total_articles FROM reference_guide');
				$data7 = $req7->fetch();
				echo $data7['nb_total_articles'];
				$req7->closeCursor();
			echo '</td>';
			
			echo '<td class="td_manage_users">';
				$req8 = $bdd->query('SELECT COUNT(id) AS nb_total_bugs FROM bugs');
				$data8 = $req8->fetch();
				echo $data8['nb_total_bugs'];
				$req8->closeCursor();
			echo '</td>';
			
			echo '<td class="td_manage_users">';
				$req9 = $bdd->query('SELECT COUNT(id) AS nb_total_resolved FROM bugs WHERE resolved = "Y"');
				$data9 = $req9->fetch();
				echo $data9['nb_total_resolved'];
				$req9->closeCursor();
			echo '</td>';

			echo '<td class="td_manage_users">';
				$req10 = $bdd->query('SELECT COUNT(id) AS nb_total_ideas FROM ideas');
				$data10 = $req10->fetch();
				echo $data10['nb_total_ideas'];
				$req4->closeCursor();
			echo '</td>';
			
			echo '<td class="td_manage_users">';
				$req11 = $bdd->query('SELECT COUNT(id) AS nb_total_ideas_inprogress FROM ideas WHERE developper != "" AND status != "D" AND status != "R"');
				$data11 = $req11->fetch();
				echo $data11['nb_total_ideas_inprogress'];
				$req11->closeCursor();
			echo '</td>';
			
			echo '<td class="td_manage_users">';
				$req12 = $bdd->query('SELECT COUNT(id) AS nb_total_ideas_finished FROM ideas WHERE (status = "D" OR status = "R")');
				$data12 = $req12->fetch();
				echo $data12['nb_total_ideas_finished'];
				$req12->closeCursor();
			echo '</td>';
		echo '</tr>';
	echo '</table>';
?>