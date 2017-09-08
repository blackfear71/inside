<?php
	echo '<div class="title_gestion">Gestion des utilisateurs</div>';

	echo '<table class="table_manage_users">';
		// Entête du tableau
		echo '<tr class="init_tr_manage_users">';
			echo '<td rowspan="2" class="init_td_manage_users" style="width: 10%;">';
				echo 'Identifiant';
			echo '</td>';

			echo '<td rowspan="2" class="init_td_manage_users" style="width: 15%;">';
				echo 'Pseudo';
			echo '</td>';

			echo '<td colspan="3" class="init_td_manage_users" style="width: 35%;">';
				echo 'Réinitialisation mot de passe';
			echo '</td>';

			echo '<td colspan="2" class="init_td_manage_users" style="width: 20%;">';
				echo 'Inscription';
			echo '</td>';

			echo '<td colspan="2" class="init_td_manage_users" style="width: 20%;">';
				echo 'Désinscription';
			echo '</td>';
		echo '</tr>';

		echo '<tr class="init_tr_manage_users">';
			echo '<td class="init_td_manage_users" style="width: 10%;">';
				echo 'Demande';
			echo '</td>';

			echo '<td class="init_td_manage_users" style="width: 10%;">';
				echo 'Annuler';
			echo '</td>';

			echo '<td class="init_td_manage_users" style="width: 15%;">';
				echo 'Réinitialiser';
			echo '</td>';

			echo '<td class="init_td_manage_users" style="width: 10%;">';
				echo 'Accepter';
			echo '</td>';

			echo '<td class="init_td_manage_users" style="width: 10%;">';
				echo 'Refuser';
			echo '</td>';

			echo '<td class="init_td_manage_users" style="width: 10%;">';
				echo 'Accepter';
			echo '</td>';

			echo '<td class="init_td_manage_users" style="width: 10%;">';
				echo 'Refuser';
			echo '</td>';
		echo '</tr>';

    foreach ($listeUsers as $user)
    {
      echo '<tr class="tr_manage_users">';
				echo '<td class="td_manage_users">';
					echo $user->getIdentifiant();
				echo '</td>';

				echo '<td class="td_manage_users">';
					echo $user->getFull_name();
				echo '</td>';

				echo '<td class="td_manage_users">';
					if ($user->getReset() == "Y")
						echo 'Oui';
					else
						echo 'Non';
				echo '</td>';

				echo '<td class="td_manage_users">';
					if ($user->getReset() == "Y")
					{
						echo '<form method="post" action="manage_users.php?id_user=' . $user->getId() . '&action=doAnnulerMdp">';
							echo '<input type="submit" name="annuler_reinitialisation" value="ANNULER" class="reset_password" />';
						echo '</form>';
					}
				echo '</td>';

				echo '<td class="td_manage_users">';
					if ($user->getReset() == "Y")
					{
						echo '<form method="post" action="manage_users.php?id_user=' . $user->getId() . '&action=doChangerMdp">';
							echo '<input type="submit" name="reinitialiser" value="REINITIALISER" class="reset_password" />';
						echo '</form>';
					}
				echo '</td>';

				echo '<td class="td_manage_users">';
					if ($user->getReset() == "I")
					{
						echo '<form method="post" action="manage_users.php?id_user=' . $user->getId() . '&action=doAccepterInscription">';
							echo '<input type="submit" name="accept_inscription" value="ACCEPTER" class="reset_password" />';
						echo '</form>';
					}
				echo '</td>';

				echo '<td class="td_manage_users">';
					if ($user->getReset() == "I")
					{
						echo '<form method="post" action="manage_users.php?id_user=' . $user->getId() . '&action=doRefuserInscription">';
							echo '<input type="submit" name="decline_inscription" value="REFUSER" class="reset_password" />';
						echo '</form>';
					}
				echo '</td>';

				echo '<td class="td_manage_users">';
					if ($user->getReset() == "D")
					{
						echo '<form method="post" action="manage_users.php?id_user=' . $user->getId() . '&action=doAccepterDesinscription">';
							echo '<input type="submit" name="accept_desinscription" value="ACCEPTER" class="reset_password" />';
						echo '</form>';
					}
				echo '</td>';

				echo '<td class="td_manage_users">';
					if ($user->getReset() == "D")
					{
						echo '<form method="post" action="manage_users.php?id_user=' . $user->getId() . '&action=doRefuserDesinscription">';
							echo '<input type="submit" name="decline_desinscription" value="REFUSER" class="reset_password" />';
						echo '</form>';
					}
				echo '</td>';
			echo '</tr>';
    }

		// Bas du tableau
		echo '<tr>';
			echo '<td colspan="2" class="td_manage_users" style="background-color: #e3e3e3; font-weight: bold;">';
				echo 'Alertes';
			echo '</td>';

			echo '<td colspan="7"class="td_manage_users">';
        if ($alerteUsers == true)
          echo '<span class="reset_warning">!</span>';
			echo '</td>';
		echo '</tr>';
	echo '</table>';
?>
