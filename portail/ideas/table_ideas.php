<?php
	include('../includes/appel_bdd.php');
	
	if ($_GET['view'] == "done")							
		$reponse = $bdd->query('SELECT * FROM ideas WHERE status="D" OR status="R" ORDER BY id DESC');
	elseif ($_GET['view'] == "inprogress")							
		$reponse = $bdd->query('SELECT * FROM ideas WHERE status="O" OR status="C" OR status="P" ORDER BY id DESC');
	else
		$reponse = $bdd->query('SELECT * FROM ideas ORDER BY id DESC');
			
	$count = 0;
	
	while ($donnees = $reponse->fetch())
	{
		// Recherche du nom complet de l'auteur
		$reponse2 = $bdd->query('SELECT identifiant, full_name FROM users WHERE identifiant="' . $donnees['author'] . '"');
		$donnees2 = $reponse2->fetch();
		
		$auteur_idee = $donnees2['full_name'];
		
		$reponse2->closeCursor();
		
		// Recherche du nom complet du developpeur
		$reponse3 = $bdd->query('SELECT identifiant, full_name FROM users WHERE identifiant="' . $donnees['developper'] . '"');
		$donnees3 = $reponse3->fetch();
		
		$developpeur_idee = $donnees3['full_name'];
		
		$reponse3->closeCursor();

		// Libellés états
		switch ($donnees['status'])
		{
			// Ouverte
			case "O":
				$etat_idee = '<span style="color: red;">Ouverte</span>';
				break;
											
			// Prise en charge
			case "C":
				$etat_idee = '<span style="color: red;">Prise en charge</span>';														
				break;
												
			// En progrès
			case "P":
				$etat_idee = '<span style="color: red;">En cours de développement</span>';													
				break;
												
			// Terminée
			case "D":
				$etat_idee = '<span style="color: green;">Terminée</span>';													
				break;
												
			// Rejetée
			case "R":
				$etat_idee = '<span style="color: red;">Rejetée</span>';													
				break;
												
			default:
				break;
		}
		
		// Formatage date
		$date_idee = substr($donnees['date'], 2, 2) . "/" . substr($donnees['date'], 0, 2) . "/" . substr($donnees['date'], 4, 4);

		// Affichage des idées
		echo '<table class="table_ideas">';
			// Titre idée
			echo '<tr id="' . $donnees['id'] . '">';												
				echo '<td class="td_ideas_title">';
					echo 'Idée';
				echo '</td>';
				echo '<td class="td_ideas_content">';
					echo $donnees['subject'];
				echo '</td>';
				
				// Boutons de prise en charge
				if (empty($donnees['developper']) OR (!empty($donnees['developper']) AND $_SESSION['identifiant'] == $donnees['developper']))
				{
					echo '<td rowspan="100%" class="td_ideas_actions">';
						echo '<form method="post" action="ideas/manage_ideas.php?view=' . $_GET['view'] . '&id=' . $donnees['id'] . '">';
							switch ($donnees['status'])
							{
								// Ouverte
								case "O":
									echo '<input type="submit" name="take" value="Prendre en charge" title="Prendre en charge" class="button_idea" />';
									break;
											
								// Prise en charge
								case "C":
									echo '<input type="submit" name="reset" value="Réinitialiser" title="Remettre à disposition" class="button_idea" />';
									echo '<input type="submit" name="developp" value="Développer" title="Commencer les développements" class="button_idea" />';
									echo '<input type="submit" name="reject" value="Rejeter" title="Annuler l\'idée" class="button_idea" />';															
									break;
												
								// En progrès
								case "P":
									echo '<input type="submit" name="reset" value="Réinitialiser" title="Remettre à disposition" class="button_idea" />';
									echo '<input type="submit" name="take" value="Prendre en charge" title="Prendre en charge" class="button_idea" />';
									echo '<input type="submit" name="end" value="Terminer" title="Finaliser l\'idée" class="button_idea" />';															
									break;
												
								// Terminée
								case "D":
									echo '<input type="submit" name="reset" value="Réinitialiser" title="Remettre à disposition" class="button_idea" />';														
									break;
											
								// Rejetée
								case "R":
									echo '<input type="submit" name="reset" value="Réinitialiser" title="Remettre à disposition" class="button_idea" />';														
									break;
												
								default:
									break;
							}
						echo '</form>';
					echo '</td>';
				}
			echo '</tr>';
						
			// Proposé par
			echo '<tr>';
				echo '<td class="td_ideas_title">';
					echo 'Proposée par';
				echo '</td>';
				echo '<td class="td_ideas_content">';
					echo $auteur_idee;
				echo '</td>';
			echo '</tr>';
			
			// Date
			echo '<tr>';
				echo '<td class="td_ideas_title">';
					echo 'Date';
				echo '</td>';
				echo '<td class="td_ideas_content">';
					echo $date_idee;
				echo '</td>';
			echo '</tr>';
				
			// Statut
			echo '<tr>';
				echo '<td class="td_ideas_title">';
					echo 'Statut';
				echo '</td>';
				echo '<td class="td_ideas_content">';
					echo $etat_idee;
				echo '</td>';
			echo '</tr>';
					
			// Prise en charge
			if (!empty($donnees['developper']))
			{
				echo '<tr>';
					echo '<td class="td_ideas_title">';
						echo 'Prise en charge par';
					echo '</td>';
					echo '<td class="td_ideas_content">';
						echo $developpeur_idee;
					echo '</td>';
			echo '</tr>';
			}
						
			// Description idée
			echo '<tr class="tr_ideas_idea">';
				echo '<td colspan="2">';
					echo $donnees['content'];
				echo '</td>';
			echo '</tr>';
		echo '</table>';

		$count++;
	}
	
	if ($count == 0)
	{
		if ($_GET['view'] == "done")							
			echo '<p class="submitted" style="text-align: center; color: black;">Aucune idée terminée</p>';
		elseif ($_GET['view'] == "inprogress")							
			echo '<p class="submitted" style="text-align: center; color: black;">Aucune idée en cours</p>';
		else
			echo '<p class="submitted" style="text-align: center; color: black;">Aucune idée proposée</p>';
	}
	
	$reponse->closeCursor();			
?>