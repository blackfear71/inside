<?php
	echo '<div class="titre_section"><img src="../../includes/icons/admin/users_grey.png" alt="users_grey" class="logo_titre_section" /><div class="texte_titre_section">Gestion des utilisateurs</div></div>';

	echo '<table class="table_manage_users">';
		// Entête du tableau
		echo '<tr class="init_tr_manage_users">';
			echo '<td class="init_td_manage_users init_td_manage_users_10">';
				echo 'Identifiant';
			echo '</td>';

			echo '<td class="init_td_manage_users init_td_manage_users_15">';
				echo 'Pseudo';
			echo '</td>';

			echo '<td class="init_td_manage_users init_td_manage_users_25">';
				echo 'Demande';
			echo '</td>';

			echo '<td colspan="2" class="init_td_manage_users init_td_manage_users_50">';
				echo 'Actions';
			echo '</td>';
		echo '</tr>';

		foreach ($listeUsersParEquipe as $referenceEquipe => $equipeUsers)
		{
			// Nom de l'équipe
			echo '<tr>';
				echo '<td class="table_old_users" colspan="5">';
					echo '<div class="banderole_left_1"></div><div class="banderole_left_2"></div>';
					if ($referenceEquipe == 'new_users')
						echo 'Utilisateurs en cours d\'inscription';
					else
						echo $listeEquipes[$referenceEquipe]->getTeam();
					echo '<div class="banderole_left_3"></div><div class="banderole_left_4"></div>';
				echo '</td>';
			echo '</tr>';

			// Liste des utilisateurs de l'équipe
			foreach ($equipeUsers as $user)
	    {
	      echo '<tr class="tr_manage_users">';
					echo '<td class="td_manage_users">';
						echo $user->getIdentifiant();
					echo '</td>';

					echo '<td class="td_manage_users">';
						echo $user->getPseudo();
					echo '</td>';

					echo '<td class="td_manage_users">';
						switch ($user->getStatus())
						{
							// Demande de réinitialisation de mot de passe
							case 'P':
								echo 'Mot de passe';
								break;

							// Demande de changement d'équipe
							case 'T':
								echo 'Changement d\'équipe (' . $listeEquipes[$user->getTeam()]->getTeam() . ' -> ' . $listeEquipes[$user->getNew_team()]->getTeam() . ')';
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

					switch ($user->getStatus())
					{
						case 'P':
							// Annulation
							echo '<td class="td_manage_users">';
								echo '<form method="post" action="manageusers.php?action=doAnnulerMdp" class="form_manage_user">';
									echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
									echo '<input type="submit" name="annuler_reinitialisation" title="Annuler" value="" class="icone_annuler" />';
								echo '</form>';
							echo '</td>';

							// Validation
							echo '<td class="td_manage_users">';
								echo '<form method="post" action="manageusers.php?action=doChangerMdp" class="form_manage_user">';
									echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
									echo '<input type="submit" name="reinitialiser" title="Réinitialiser" value="" class="icone_accepter" />';
								echo '</form>';
							echo '</td>';
							break;

						case 'T':
							// Annulation
							echo '<td class="td_manage_users init_td_manage_users_25">';
								echo '<form method="post" action="manageusers.php?action=doRefuserEquipe" class="form_manage_user">';
									echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
									echo '<input type="hidden" name="team_user" value="' . $user->getTeam() . '" />';
									echo '<input type="hidden" name="new_team_user" value="' . $user->getNew_team() . '" />';
									echo '<input type="submit" name="decline_inscription" title="Refuser" value="" class="icone_annuler" />';
								echo '</form>';
							echo '</td>';

							// Validation
							echo '<td class="td_manage_users init_td_manage_users_25">';
								echo '<form method="post" action="manageusers.php?action=doAccepterEquipe" class="form_manage_user">';
									echo '<div class="zone_select_team">';
										echo '<select name="team" class="select_form_manage_user" required>';
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

											echo '<input type="text" placeholder="Référence" name="team_reference" value="" maxlength="100" class="input_form_manage_user" required />';
											echo '<input type="text" placeholder="Nom" name="team_name" value="' . $listeEquipes[$user->getNew_team()]->getTeam() . '" class="input_form_manage_user" required />';
											echo '<input type="text" placeholder="Nom court" name="team_short_name" value="" maxlength="100" class="input_form_manage_user" required />';
										}
										else
										{
											echo '<input type="text" placeholder="Référence" name="team_reference" value="" maxlength="100" class="input_form_manage_user" style="display: none;" />';
											echo '<input type="text" placeholder="Nom" name="team_name" value="" class="input_form_manage_user" style="display: none;" />';
											echo '<input type="text" placeholder="Nom court" name="team_short_name" value="" maxlength="100" class="input_form_manage_user" style="display: none;" />';
										}
									echo '</div>';

									echo '<div class="zone_validate_team">';
										echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
										echo '<input type="submit" name="accept_inscription" title="Accepter" value="" class="icone_accepter" />';
									echo '</div>';
								echo '</form>';
							echo '</td>';
							break;

						case 'I':
							// Annulation
							echo '<td class="td_manage_users init_td_manage_users_25">';
								echo '<form method="post" action="manageusers.php?action=doRefuserInscription" class="form_manage_user">';
									echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
									echo '<input type="hidden" name="team_user" value="' . $user->getNew_team() . '" />';
									echo '<input type="submit" name="decline_inscription" title="Refuser" value="" class="icone_annuler" />';
								echo '</form>';
							echo '</td>';

							// Validation
							echo '<td class="td_manage_users init_td_manage_users_25">';
								echo '<form method="post" action="manageusers.php?action=doAccepterInscription" class="form_manage_user">';
									echo '<div class="zone_select_team">';
										echo '<select name="team" class="select_form_manage_user" required>';
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

											echo '<input type="text" placeholder="Référence" name="team_reference" value="" maxlength="100" class="input_form_manage_user" required />';
											echo '<input type="text" placeholder="Nom" name="team_name" value="' . $listeEquipes[$user->getNew_team()]->getTeam() . '" class="input_form_manage_user" required />';
											echo '<input type="text" placeholder="Nom court" name="team_short_name" value="" maxlength="100" class="input_form_manage_user" required />';
										}
										else
										{
											echo '<input type="text" placeholder="Référence" name="team_reference" value="" maxlength="100" class="input_form_manage_user" style="display: none;" />';
											echo '<input type="text" placeholder="Nom" name="team_name" value="" class="input_form_manage_user" style="display: none;" />';
											echo '<input type="text" placeholder="Nom court" name="team_short_name" value="" maxlength="100" class="input_form_manage_user" style="display: none;" />';
										}
									echo '</div>';

									echo '<div class="zone_validate_team">';
										echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
										echo '<input type="submit" name="accept_inscription" title="Accepter" value="" class="icone_accepter" />';
									echo '</div>';
								echo '</form>';
							echo '</td>';
							break;

						case 'D':
							// Annulation
							echo '<td class="td_manage_users">';
								echo '<form method="post" action="manageusers.php?action=doRefuserDesinscription" class="form_manage_user">';
									echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
									echo '<input type="submit" name="decline_desinscription" title="Refuser" value="" class="icone_annuler" />';
								echo '</form>';
							echo '</td>';

							// Validation
							echo '<td class="td_manage_users">';
								echo '<form method="post" action="manageusers.php?action=doAccepterDesinscription" class="form_manage_user">';
									echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
									echo '<input type="submit" name="accept_desinscription" title="Accepter" value="" class="icone_accepter" />';
								echo '</form>';
							echo '</td>';
							break;

						default:
							echo '<td colspan="2" class="td_manage_users"></td>';
							break;
					}
				echo '</tr>';
	    }
		}

		// Bas du tableau
		echo '<tr>';
			echo '<td colspan="2" class="td_manage_users_important">';
				echo 'Alertes';
			echo '</td>';

			echo '<td colspan="3" class="td_manage_users">';
        if ($alerteUsers == true)
          echo '<span class="reset_warning">!</span>';
			echo '</td>';
		echo '</tr>';
	echo '</table>';
?>
