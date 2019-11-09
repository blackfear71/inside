<?php
  echo '<div class="zone_profil_bottom_left">';
    echo '<div class="titre_section"><img src="../includes/icons/profil/connexion_grey.png" alt="connexion_grey" class="logo_titre_section" /><div class="texte_titre_section">Utilisateur</div></div>';

    // Mot de passe
    echo '<div class="zone_action_user">';
      echo '<div class="titre_contribution">CHANGER MOT DE PASSE</div>';

      // Modification mot de passe
      echo '<form method="post" action="profil.php?action=doUpdatePassword">';
        echo '<input type="password" name="old_password" placeholder="Ancien mot de passe" maxlength="100" class="monoligne_saisie" required />';
        echo '<input type="password" name="new_password" placeholder="Nouveau mot de passe" maxlength="100" class="monoligne_saisie" required />';
        echo '<input type="password" name="confirm_new_password" placeholder="Confirmer le nouveau mot de passe" maxlength="100" class="monoligne_saisie" required />';

        echo '<input type="submit" name="saisie_mdp" value="Valider" class="bouton_validation" />';
      echo '</form>';

      // Annulation demande
      if ($profil->getStatus() == "Y")
      {
        echo '<div class="message_profil margin_top">Si vous avez fait la demande de changement de mot de passe mais que vous souhaitez l\'annuler car vous l\'avez retrouvé, cliquez sur ce bouton.</div>';

        echo '<form method="post" action="profil.php?action=cancelResetPassword" class="margin_top">';
          echo '<input type="submit" name="cancel_reset" value="Annuler la demande" class="bouton_validation" />';
        echo '</form>';

        echo '<div class="message_profil bold margin_top">Une demande est en cours.</div>';
      }
    echo '</div>';

    // Désinscription
    echo '<div class="zone_action_user">';
      echo '<div class="titre_contribution">DÉSINSCRIPTION</div>';

      echo '<div class="message_profil">Si vous souhaitez vous désinscrire, vous pouvez en faire la demande à l\'administrateur à l\'aide de ce bouton. Il validera votre choix après vérification.</div>';

      if ($profil->getStatus() == "D")
      {
        // Annulation
        echo '<form method="post" action="profil.php?action=cancelDesinscription" class="margin_top">';
          echo '<input type="submit" name="cancel_desinscription" value="Annuler la demande" class="bouton_validation" />';
        echo '</form>';

        echo '<div class="message_profil bold margin_top">Une demande est déjà en cours.</div>';
      }
      else
      {
        // Désinscription
        echo '<form method="post" action="profil.php?action=askDesinscription" class="margin_top">';
          echo '<input type="submit" name="ask_desinscription" value="Désinscription" class="bouton_validation" />';
        echo '</form>';

        echo '<div class="message_profil bold margin_top">Aucune demande en cours.</div>';
      }
    echo '</div>';
  echo '</div>';
?>
