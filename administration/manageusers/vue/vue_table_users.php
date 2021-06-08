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

			echo '<td class="init_td_manage_users init_td_manage_users_15">';
				echo 'Demande';
			echo '</td>';

			echo '<td class="init_td_manage_users init_td_manage_users_20">';
				echo 'Réinitialisation mot de passe';
			echo '</td>';

			echo '<td class="init_td_manage_users init_td_manage_users_20">';
				echo 'Inscription';
			echo '</td>';

			echo '<td class="init_td_manage_users init_td_manage_users_20">';
				echo 'Désinscription';
			echo '</td>';
		echo '</tr>';

    foreach ($listeUsers as $user)
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

						// Demande d'inscription
						case 'I':
							echo 'Inscription';
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

				echo '<td class="td_manage_users">';
					if ($user->getStatus() == 'P')
					{
						echo '<form method="post" action="manageusers.php?action=doAnnulerMdp" class="form_manage_user">';
							echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
							echo '<input type="submit" name="annuler_reinitialisation" title="Annuler" value="" class="icone_annuler" />';
						echo '</form>';

						echo '<form method="post" action="manageusers.php?action=doChangerMdp" class="form_manage_user">';
							echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
							echo '<input type="submit" name="reinitialiser" title="Réinitialiser" value="" class="icone_accepter" />';
						echo '</form>';
					}
				echo '</td>';

				echo '<td class="td_manage_users">';
					if ($user->getStatus() == 'I')
					{
						echo '<form method="post" action="manageusers.php?action=doRefuserInscription" class="form_manage_user">';
							echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
							echo '<input type="submit" name="decline_inscription" title="Refuser" value="" class="icone_annuler" />';
						echo '</form>';

						echo '<form method="post" action="manageusers.php?action=doAccepterInscription" class="form_manage_user">';
							echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
							echo '<input type="submit" name="accept_inscription" title="Accepter" value="" class="icone_accepter" />';
						echo '</form>';
					}
				echo '</td>';

				echo '<td class="td_manage_users">';
					if ($user->getStatus() == 'D')
					{
						echo '<form method="post" action="manageusers.php?action=doRefuserDesinscription" class="form_manage_user">';
							echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
							echo '<input type="submit" name="decline_desinscription" title="Refuser" value="" class="icone_annuler" />';
						echo '</form>';

						echo '<form method="post" action="manageusers.php?action=doAccepterDesinscription" class="form_manage_user">';
							echo '<input type="hidden" name="id_user" value="' . $user->getIdentifiant() . '" />';
							echo '<input type="submit" name="accept_desinscription" title="Accepter" value="" class="icone_accepter" />';
						echo '</form>';
					}
				echo '</td>';
			echo '</tr>';
    }

		// Bas du tableau
		echo '<tr>';
			echo '<td colspan="3" class="td_manage_users_important">';
				echo 'Alertes';
			echo '</td>';

			echo '<td colspan="3" class="td_manage_users">';
        if ($alerteUsers == true)
          echo '<span class="reset_warning">!</span>';
			echo '</td>';
		echo '</tr>';
	echo '</table>';
?>
