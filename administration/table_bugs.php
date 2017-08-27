<?php
	include('../includes/appel_bdd.php');
	include('../includes/fonctions_dates.php');

	if ($_GET['view'] == "resolved")
		$reponse = $bdd->query('SELECT * FROM bugs WHERE resolved="Y" ORDER BY id DESC');
	elseif ($_GET['view'] == "unresolved")
		$reponse = $bdd->query('SELECT * FROM bugs WHERE resolved="N" ORDER BY id DESC');
	else
		$reponse = $bdd->query('SELECT * FROM bugs ORDER BY id DESC');

	$count = 0;

	while ($donnees = $reponse->fetch())
	{
		// Recherche du nom complet de l'auteur
		$reponse2 = $bdd->query('SELECT identifiant, full_name FROM users WHERE identifiant="' . $donnees['author'] . '"');
		$donnees2 = $reponse2->fetch();

		if (isset($donnees2['full_name']) AND !empty($donnees2['full_name']))
			$auteur_bug = $donnees2['full_name'];
		else
			$auteur_bug = "<i>un ancien utilisateur</i>";

		$reponse2->closeCursor();

		// Libellé état
		if ($donnees['resolved'] == "Y")
			$etat_bug = '<span style="color: green;">Résolu</span>';
		else
			$etat_bug = '<span style="color: red;">En cours</span>';

		// Formatage date
		$date_bug = formatDateForDisplay($donnees['date']);

		// Affichage des idées
		echo '<table class="table_ideas">';
			echo '<tr id="' . $donnees['id'] . '">';
				// Titre idée
				echo '<td class="td_ideas_title">';
					if ($donnees['type'] == "B")
						echo 'Bug';
					elseif ($donnees['type'] == "E")
						echo 'Evolution';
				echo '</td>';
				echo '<td class="td_ideas_content">';
					echo $donnees['subject'];
				echo '</td>';

				// Date
				echo '<td class="td_ideas_title">';
					echo 'Date';
				echo '</td>';
				echo '<td class="td_ideas_content">';
					echo $date_bug;
				echo '</td>';

				// Boutons de prise en charge
				echo '<td rowspan="100%" class="td_ideas_actions">';
					echo '<form method="post" action="bug_status.php?view=' . $_GET['view'] . '&id=' . $donnees['id'] . '">';
						if ($donnees['resolved'] == "N")
						{
							echo '<input type="submit" name="resolve_bug" value="Résoudre" class="button_idea" />';
						}
						else
						{
							echo '<input type="submit" name="unresolve_bug" value="Remettre en cours" class="button_idea" />';
						}
					echo '</form>';
				echo '</td>';
			echo '</tr>';

			echo '<tr>';
				// Remontée par
				echo '<td class="td_ideas_title">';
					if ($donnees['type'] == "B")
						echo 'Remonté par';
					elseif ($donnees['type'] == "E")
						echo 'Proposée par';
				echo '</td>';
				echo '<td class="td_ideas_content">';
					echo $auteur_bug;
				echo '</td>';

				// Statut
				echo '<td class="td_ideas_title">';
					echo 'Statut';
				echo '</td>';
				echo '<td class="td_ideas_content">';
					echo $etat_bug;
				echo '</td>';
			echo '</tr>';

			// Description idée
			echo '<tr class="tr_ideas_idea">';
				echo '<td colspan="4">';
					echo '<p>' . htmlspecialchars(nl2br($donnees['content'])) . '</p>';
				echo '</td>';
			echo '</tr>';
		echo '</table>';

		$count++;
	}

	if ($count == 0)
	{
		if ($_GET['view'] == "resolved")
			echo '<p class="no_bugs">Aucun(e) bug/évolution résolu(e)</p>';
		elseif ($_GET['view'] == "unresolved")
			echo '<p class="no_bugs">Aucun(e) bug/évolution non résolu(e)</p>';
		else
			echo '<p class="no_bugs">Aucun(e) bug/évolution</p>';
	}

	$reponse->closeCursor();
?>
