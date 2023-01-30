<?php
	// Titre
	echo '<div class="titre_section"><img src="../../includes/icons/admin/users_grey.png" alt="users_grey" class="logo_titre_section" /><div class="texte_titre_section">Gestion des utilisateurs</div></div>';

	// Tableau des utilisateurs
	echo '<table class="table_admin">';
		// Entête du tableau
		echo '<tr>';
			echo '<td class="width_10">';
				echo 'Identifiant';
			echo '</td>';

			echo '<td class="width_25">';
				echo 'Pseudo';
			echo '</td>';

			echo '<td class="width_25">';
				echo 'Demande';
			echo '</td>';

			echo '<td class="width_40">';
				echo 'Actions';
			echo '</td>';
		echo '</tr>';

		if (!empty($listeUsersParEquipe))
		{
			foreach ($listeUsersParEquipe as $referenceEquipe => $equipeUsers)
			{
				// Nom de l'équipe
				if (!empty($listeUsersParEquipe[$referenceEquipe]))
				{
					echo '<tr>';
						echo '<td class="td_table_admin_fusion" colspan="4">';
							if ($referenceEquipe == 'new_users')
								echo 'Utilisateurs en cours d\'inscription';
							else
								echo $listeEquipes[$referenceEquipe]->getTeam();
						echo '</td>';
					echo '</tr>';
				}
	
				// Liste des utilisateurs de l'équipe
				foreach ($equipeUsers as $user)
				{
					echo '<tr>';
						echo '<td class="td_table_admin_premier">';
							echo $user->getIdentifiant();
						echo '</td>';
	
						echo '<td class="td_table_admin_normal">';
							echo $user->getPseudo();
						echo '</td>';
	
						echo '<td class="td_table_admin_centre">';
							switch ($user->getStatus())
							{
								// Demande de réinitialisation de mot de passe
								case 'P':
									echo 'Mot de passe';
									break;
	
								// Demande de changement d'équipe
								case 'T':
									if ((isset($listeEquipes[$user->getTeam()])     AND !empty($listeEquipes[$user->getTeam()]->getTeam()))
									AND (isset($listeEquipes[$user->getNew_team()]) AND !empty($listeEquipes[$user->getNew_team()]->getTeam())))
									{
										echo 'Changement d\'équipe (' . $listeEquipes[$user->getTeam()]->getTeam() . '<div class="zone_fleches_equipe"><img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_equipe" /><img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_equipe" /><img src="../../includes/icons/common/open_grey.png" alt="open_grey" class="fleche_equipe" /></div>' . $listeEquipes[$user->getNew_team()]->getTeam() . ')';
									}
									else
										echo 'Changement d\'équipe';
									break;
	
								// Demande d'inscription
								case 'I':
									echo 'Inscription (' . $listeEquipes[$user->getNew_team()]->getTeam() . ')';
									break;
	
								// Demande de désinscription
								case 'D':
									echo 'Désinscription';
									break;
	
								// Statut utilisateur normal
								case 'U':
								default:
									break;
							}
						echo '</td>';
	
						echo '<td class="td_table_admin_actions">';
							switch ($user->getStatus())
							{
								case 'P':
									// Validation
									echo '<form method="post" action="manageusers.php?action=doChangerMdp" class="lien_action_table_admin">';
										echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
										echo '<input type="submit" name="reinitialiser" value="" title="Réinitialiser" class="icone_valider_table_admin" />';
									echo '</form>';
		
									// Annulation
									echo '<form method="post" action="manageusers.php?action=doAnnulerMdp" class="lien_action_table_admin">';
										echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
										echo '<input type="submit" name="annuler_reinitialisation" value="" title="Annuler" class="icone_annuler_table_admin" />';
									echo '</form>';
									break;

								case 'T':
									// Validation
									echo '<form method="post" action="manageusers.php?action=doAccepterEquipe" class="form_selection_equipe">';
										echo '<div class="zone_selection_equipe">';
											echo '<select name="team" class="selection_equipe" required>';
												foreach ($listeEquipes as $equipe)
												{
													if ($equipe->getActivation() != 'N')
													{
														if (isset($listeEquipes[$user->getNew_team()]) AND $listeEquipes[$user->getNew_team()]->getActivation() != 'N' AND $user->getNew_team() == $equipe->getReference())
															echo '<option value="' . $equipe->getReference() . '" selected>' . $equipe->getTeam() . '</option>';
														else
															echo '<option value="' . $equipe->getReference() . '">' . $equipe->getTeam() . '</option>';
													}
												}

												if (!empty($user->getNew_team()) AND (!isset($listeEquipes[$user->getNew_team()]) OR $listeEquipes[$user->getNew_team()]->getActivation() == 'N'))
													echo '<option value="other" selected>Créer une équipe</option>';
												else
													echo '<option value="other">Créer une équipe</option>';
											echo '</select>';

											if (!empty($user->getNew_team()) AND isset($listeEquipes[$user->getNew_team()]) AND $listeEquipes[$user->getNew_team()]->getActivation() == 'N')
											{
												echo '<input type="hidden" name="team_temp_reference" value="' . $user->getNew_team() . '" />';
												echo '<input type="text" placeholder="Référence" name="team_reference" value="" maxlength="100" class="saisie_nouvelle_equipe" required />';
												echo '<input type="text" placeholder="Nom" name="team_name" value="' . $listeEquipes[$user->getNew_team()]->getTeam() . '" class="saisie_nouvelle_equipe" required />';
											}
											else
											{
												echo '<input type="text" placeholder="Référence" name="team_reference" value="" maxlength="100" class="saisie_nouvelle_equipe" style="display: none;" />';
												echo '<input type="text" placeholder="Nom" name="team_name" value="" class="saisie_nouvelle_equipe" style="display: none;" />';
											}
										echo '</div>';

										echo '<div class="lien_action_table_admin">';
											echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
											echo '<input type="hidden" name="team_user" value="' . $user->getTeam() . '" />';
											echo '<input type="submit" name="accept_inscription" value="" title="Accepter" class="icone_valider_table_admin" />';
										echo '</div>';
									echo '</form>';
		
									// Annulation
									echo '<form method="post" action="manageusers.php?action=doRefuserEquipe" class="lien_action_table_admin">';
										echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
										echo '<input type="hidden" name="team_user" value="' . $user->getTeam() . '" />';
										echo '<input type="hidden" name="new_team_user" value="' . $user->getNew_team() . '" />';
										echo '<input type="submit" name="decline_inscription" value="" title="Refuser" class="icone_annuler_table_admin" />';
									echo '</form>';
									break;

								case 'I':
									// Validation
									echo '<form method="post" action="manageusers.php?action=doAccepterInscription" class="form_selection_equipe">';
										echo '<div class="zone_selection_equipe">';
											echo '<select name="team" class="selection_equipe" required>';
												foreach ($listeEquipes as $equipe)
												{
													if ($equipe->getActivation() != 'N')
													{
														if (isset($listeEquipes[$user->getNew_team()]) AND $listeEquipes[$user->getNew_team()]->getActivation() != 'N' AND $user->getNew_team() == $equipe->getReference())
															echo '<option value="' . $equipe->getReference() . '" selected>' . $equipe->getTeam() . '</option>';
														else
															echo '<option value="' . $equipe->getReference() . '">' . $equipe->getTeam() . '</option>';
													}
												}
	
												if (!empty($user->getNew_team()) AND (!isset($listeEquipes[$user->getNew_team()]) OR $listeEquipes[$user->getNew_team()]->getActivation() == 'N'))
													echo '<option value="other" selected>Créer une équipe</option>';
												else
													echo '<option value="other">Créer une équipe</option>';
											echo '</select>';
	
											if (!empty($user->getNew_team()) AND isset($listeEquipes[$user->getNew_team()]) AND $listeEquipes[$user->getNew_team()]->getActivation() == 'N')
											{
												echo '<input type="hidden" name="team_temp_reference" value="' . $user->getNew_team() . '" />';
												echo '<input type="text" placeholder="Référence" name="team_reference" value="" maxlength="100" class="saisie_nouvelle_equipe" required />';
												echo '<input type="text" placeholder="Nom" name="team_name" value="' . $listeEquipes[$user->getNew_team()]->getTeam() . '" class="saisie_nouvelle_equipe" required />';
											}
											else
											{
												echo '<input type="text" placeholder="Référence" name="team_reference" value="" maxlength="100" class="saisie_nouvelle_equipe" style="display: none;" />';
												echo '<input type="text" placeholder="Nom" name="team_name" value="" class="saisie_nouvelle_equipe" style="display: none;" />';
											}
										echo '</div>';
	
										echo '<div class="lien_action_table_admin">';
											echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
											echo '<input type="hidden" name="team_user" value="' . $user->getNew_team() . '" />';
											echo '<input type="submit" name="accept_inscription" value="" title="Accepter" class="icone_valider_table_admin" />';
										echo '</div>';
									echo '</form>';
		
									// Annulation
									echo '<form method="post" action="manageusers.php?action=doRefuserInscription" class="lien_action_table_admin">';
										echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
										echo '<input type="hidden" name="team_user" value="' . $user->getNew_team() . '" />';
										echo '<input type="submit" name="decline_inscription" value="" title="Refuser" class="icone_annuler_table_admin" />';
									echo '</form>';
									break;

								case 'D':
									// Validation
									echo '<form method="post" action="manageusers.php?action=doAccepterDesinscription" class="lien_action_table_admin">';
										echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
										echo '<input type="hidden" name="team_user" value="' . $user->getTeam() . '" />';
										echo '<input type="submit" name="accept_desinscription" value="" title="Accepter" class="icone_valider_table_admin" />';
									echo '</form>';
		
									// Annulation
									echo '<form method="post" action="manageusers.php?action=doRefuserDesinscription" class="lien_action_table_admin">';
										echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
										echo '<input type="submit" name="decline_desinscription" value="" title="Refuser" class="icone_annuler_table_admin" />';
									echo '</form>';
									break;
		
								default:
									break;
							}

							// Forçage désinscription
							if ($user->getStatus() != 'I' AND $user->getStatus() != 'D')
							{
								echo '<form method="post" action="manageusers.php?action=doForcerDesinscription" class="lien_action_table_admin">';
									echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
									echo '<input type="submit" name="force_desinscription" value="" title="Forcer la désinscription" class="icone_forcer_table_admin" />';
								echo '</form>';
							}
						echo '</td>';
					echo '</tr>';
				}
			}
		}
		else
		{
			echo '<tr class="tr_table_admin_empty">';
				echo '<td colspan="4" class="empty">Pas d\'utilisateurs existants...</td>';
			echo '</tr>';
		}
	echo '</table>';
?>