<?php
	/************************/
	/* Tableau des dépenses */
	/************************/
	echo '<div class="zone_resume_depenses">';
		echo '<table class="table_expense_center">';
	    // Entête tableau
			echo '<tr>';
				echo '<td class="init_table_expense_center">Prix</td>';
				echo '<td class="init_table_expense_center">Acheteur</td>';
				echo '<td class="init_table_expense_center">Date</td>';

	      foreach ($listeUsers as $user)
	      {
	        echo '<td class="init_table_expense_center_users">';
	          echo '<div class="zone_avatar_expense_center">';
	            if (!empty($user->getAvatar()))
	              echo '<img src="../../profil/avatars/' . $user->getAvatar() . '" alt="avatar" title="' . $user->getPseudo() . '" class="avatar_expense_center" />';
	            else
	              echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $user->getPseudo() . '" class="avatar_expense_center" />';
	          echo '</div>';

	          echo '<div class="pseudo_expense_center">' . $user->getPseudo() . '</div>';
	        echo '</td>';
	      }

				echo '<td class="init_table_expense_center" style="max-width: 120px;">Commentaire</td>';
				echo '<td class="init_table_expense_center">Actions</td>';
			echo '</tr>';

	    // Tableau
			if (!empty($tableauExpenses))
			{
				$i = 0;

		    foreach ($tableauExpenses as $ligneResume)
		    {
		      /***************************************************/
		      /* Ligne visualisation normale (sans modification) */
		      /***************************************************/
					if ($i % 2 == 0)
						echo '<tr class="ligne_tableau_expense_center" id="modifier_depense_2[' . $ligneResume['id_expense'] . ']">';
					else
		      	echo '<tr class="ligne_tableau_expense_center_2" id="modifier_depense_2[' . $ligneResume['id_expense'] . ']">';
						echo '<form method="post" action="expensecenter.php?year=' . $_GET['year'] . '&id_delete=' . $ligneResume['id_expense'] . '&action=doSupprimer">';
							// Prix sur la 1ère colonne
			        echo '<td class="table_price_expense_center" id=' . $ligneResume['id_expense'] . '>';
			          echo $ligneResume['price'] . ' €';
			        echo '</td>';

			        // Acheteur sur la 2ème colonne
			        echo '<td class="table_buyer_expense_center">';
			          echo $ligneResume['name_b'];
			        echo '</td>';

			        // Date sur la 3ème colonne
			        echo '<td class="table_date_expense_center">';
			          echo $ligneResume['date'];
			        echo '</td>';

			        // Parts des Utilisateurs
			        foreach ($ligneResume['tableParts'] as $part)
			        {
			          if ($part['identifiant'] == $_SESSION['identifiant'])
			            echo '<td class="table_users_expense_center" style="background-color: #fffde8;">';
			          else
			            echo '<td class="table_users_expense_center">';

								if ($part['part'] > 0)
			            echo '<span class="part_bold">' . $part['part'] . '</span>';
								else
									echo $part['part'];

			          echo '</td>';
			        }

			        // Commentaires
			        echo '<td class="table_comment_expense_center" style="max-width: 120px;">';
			          echo nl2br($ligneResume['comment']);
			        echo '</td>';

			        // Boutons d'action
			        echo '<td class="action_depenses">';
			          // Modification ligne
			          echo '<span class="link_action_depenses">';
			            echo '<a onclick="afficherMasquerRow(\'modifier_depense[' . $ligneResume['id_expense'] . ']\'); afficherMasquerRow(\'modifier_depense_2[' . $ligneResume['id_expense'] . ']\');" title="Modifier la ligne" class="icone_modifier_depense"></a>';
			          echo '</span>';

			          // Suppression ligne
								echo '<span class="link_action_depenses">';
			            echo '<input type="submit" name="delete_depense" value="" title="Supprimer la ligne" onclick="if(!confirm(\'Supprimer la dépense de ' . formatOnclick($ligneResume['name_b']) . ' du ' . $ligneResume['date'] . ' et d&rsquo;un montant de ' . $ligneResume['price'] . ' € ?\')) return false;" class="icone_supprimer_depense" />';
								echo '</span>';
			        echo '</td>';
						echo '</form>';
		      echo '</tr>';

		      /**********************************/
		      /* Ligne cachée pour modification */
		      /**********************************/
					if ($i % 2 == 0)
						echo '<tr class="ligne_tableau_expense_center" id="modifier_depense[' . $ligneResume['id_expense'] . ']" style="display: none;">';
					else
		      	echo '<tr class="ligne_tableau_expense_center_2" id="modifier_depense[' . $ligneResume['id_expense'] . ']" style="display: none;">';
						echo '<form method="post" action="expensecenter.php?year=' . $_GET['year'] . '&id_modify=' . $ligneResume['id_expense'] . '&action=doModifier" title="Valider la modification">';
							// Prix sur la 1ère colonne
							echo '<td class="table_price_expense_center">';
								echo '<input type="text" name="depense" value="' . $ligneResume['price'] . '" placeholder="Prix" maxlength="6" class="saisie_prix_2" required /> <span class="euro">€</span>';
							echo '</td>';

							// Acheteur sur la 2ème colonne
							echo '<td class="table_price_expense_center">';
								if ($ligneResume['oldUser'] == true)
								{
									echo '<input type="hidden" id="old_buyer" name="buyer_user" value="' . $ligneResume['buyer'] . '" class="buyer" required readonly />';
									echo '<label for="old_buyer">' . $ligneResume['name_b'] . '</label>';
								}
								else
								{
									echo '<select name="buyer_user" class="buyer" required>';
										foreach ($listeUsers as $user)
										{
											if ($user->getIdentifiant() == $ligneResume['buyer'])
												echo '<option value="' . $user->getIdentifiant() . '" selected>' . $user->getPseudo() . '</option>';
											else
												echo '<option value="' . $user->getIdentifiant() . '">' . $user->getPseudo() . '</option>';
										}
									echo '</select>';
								}
							echo '</td>';

							// Date sur la 3ème colonne
							echo '<td class="table_date_expense_center">';
								echo $ligneResume['date'];
							echo '</td>';

		          // Parts des Utilisateurs
		          foreach ($ligneResume['tableParts'] as $part)
		          {
		            if ($part['identifiant'] == $_SESSION['identifiant'])
		              echo '<td class="table_users_expense_center" style="background-color: #fffde8;">';
		            else
		              echo '<td class="table_users_expense_center">';

		              echo '<select name="depense_user[]" class="parts_2">';
		              for ($j = 0; $j <= $nb_max_parts; $j++)
		              {
		                if ($j == $part['part'])
		                  echo '<option value="' . $j . '" selected>' . $j . '</option>';
		                else
		                  echo '<option value="' . $j . '">' . $j . '</option>';
		              }
		              echo '</select>';

		            echo '</td>';
		          }

		          // Saisie Commentaire
		          echo '<td class="table_users_expense_center">';
		              echo '<textarea name="comment" placeholder="Commentaire" maxlength="200" class="saisie_commentaire_depense_2" />' . nl2br($ligneResume['comment']) . '</textarea>';
		          echo '</td>';

							// Boutons d'action
							echo '<td class="action_depenses">';
								// Validation modification
								echo '<span class="link_action_depenses">';
									echo '<input type="submit" name="modify_depense" value="" title="Valider la modification" class="icone_valider_depense" />';
								echo '</span>';

								// Annulation modification ligne
								echo '<span class="link_action_depenses">';
									echo '<a onclick="afficherMasquerRow(\'modifier_depense[' . $ligneResume['id_expense'] . ']\'); afficherMasquerRow(\'modifier_depense_2[' . $ligneResume['id_expense'] . ']\');" title="Annuler la modification" class="icone_annuler_depense"></a>';
								echo '</span>';
							echo '</td>';
						echo '</form>';
					echo '</tr>';

					$i++;
	    	}
			}
			// S'il n'y a pas de dépenses à afficher
			else
			{
				echo '<tr>';
					echo '<td colspan="100%" class="no_expenses">';
						echo 'Pas de dépenses à afficher';
					echo '</td>';
				echo '</tr>';
			}

	    // Bas de tableau
	    echo '<tr>';
				echo '<td class="init_table_expense_center">Prix</td>';
				echo '<td class="init_table_expense_center">Acheteur</td>';
				echo '<td class="init_table_expense_center">Date</td>';

	      foreach ($listeUsers as $user)
	      {
	        echo '<td class="init_table_expense_center_users">';
	          echo '<div class="zone_avatar_depense">';
	            if (!empty($user->getAvatar()))
	              echo '<img src="../../profil/avatars/' . $user->getAvatar() . '" alt="avatar" title="' . $user->getPseudo() . '" class="avatar_depense" />';
	            else
	              echo '<img src="../../includes/icons/default.png" alt="avatar" title="' . $user->getPseudo() . '" class="avatar_depense" />';
	          echo '</div>';

	          echo '<div class="pseudo_expense_center">' . $user->getPseudo() . '</div>';
	        echo '</td>';
	      }

				echo '<td class="init_table_expense_center" style="max-width: 120px;">Commentaire</td>';
				echo '<td class="init_table_expense_center">Actions</td>';
			echo '</tr>';
		echo '</table>';
	echo '</div>';
?>
