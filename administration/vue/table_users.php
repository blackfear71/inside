<?php
	echo '<div class="title_gestion">Gestion des utilisateurs</div>';

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
				echo 'Demande';
			echo '</td>';

			echo '<td class="init_td_manage_users" style="width: 20%;">';
				echo 'Réinitialisation mot de passe';
			echo '</td>';

			echo '<td class="init_td_manage_users" style="width: 20%;">';
				echo 'Inscription';
			echo '</td>';

			echo '<td class="init_td_manage_users" style="width: 20%;">';
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
					switch ($user->getReset())
					{
						case "Y":
							echo 'Mot de passe';
							break;

						case "I":
							echo 'Inscription';
							break;
						case "D":
							echo 'Désinscription';
							break;

						case "N":
						default:
							break;
					}
				echo '</td>';

				echo '<td class="td_manage_users">';
					if ($user->getReset() == "Y")
					{
						echo '<form method="post" action="manage_users.php?id_user=' . $user->getId() . '&action=doAnnulerMdp" class="form_manage_user">';
							echo '<input type="submit" name="annuler_reinitialisation" title="Annuler" value="" class="icone_annuler" />';
						echo '</form>';

						echo '<form method="post" action="manage_users.php?id_user=' . $user->getId() . '&action=doChangerMdp" class="form_manage_user">';
							echo '<input type="submit" name="reinitialiser" title="Réinitialiser" value="" class="icone_accepter" />';
						echo '</form>';
					}
				echo '</td>';

				echo '<td class="td_manage_users">';
					if ($user->getReset() == "I")
					{
						echo '<form method="post" action="manage_users.php?id_user=' . $user->getId() . '&action=doRefuserInscription" class="form_manage_user">';
							echo '<input type="submit" name="decline_inscription" title="Refuser" value="" class="icone_annuler" />';
						echo '</form>';

						echo '<form method="post" action="manage_users.php?id_user=' . $user->getId() . '&action=doAccepterInscription" class="form_manage_user">';
							echo '<input type="submit" name="accept_inscription" title="Accepter" value="" class="icone_accepter" />';
						echo '</form>';
					}
				echo '</td>';

				echo '<td class="td_manage_users">';
					if ($user->getReset() == "D")
					{
						echo '<form method="post" action="manage_users.php?id_user=' . $user->getId() . '&action=doRefuserDesinscription" class="form_manage_user">';
							echo '<input type="submit" name="decline_desinscription" title="Refuser" value="" class="icone_annuler" />';
						echo '</form>';

						echo '<form method="post" action="manage_users.php?id_user=' . $user->getId() . '&action=doAccepterDesinscription" class="form_manage_user">';
							echo '<input type="submit" name="accept_desinscription" title="Accepter" value="" class="icone_accepter" />';
						echo '</form>';
					}
				echo '</td>';
			echo '</tr>';
    }

		// Bas du tableau
		echo '<tr>';
			echo '<td colspan="3" class="td_manage_users" style="background-color: #e3e3e3; font-weight: bold;">';
				echo 'Alertes';
			echo '</td>';

			echo '<td colspan="3" class="td_manage_users">';
        if ($alerteUsers == true)
          echo '<span class="reset_warning">!</span>';
			echo '</td>';
		echo '</tr>';
	echo '</table>';
?>
